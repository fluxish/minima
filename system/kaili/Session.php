<?php  if (!defined('ROOT')) exit('No direct script access allowed');

/**
 * Kaili Authentication Class
 *
 * Class to manage session variables
 *
 * @package		Kaili
 * @subpackage	Library
 * @category	Library
 * @author		Luigi Marco Simonetti
 */

class Session
{
    
    private $_session;
    
    private $_session_expiration_time = 7200;
    private $_session_update_time = 600;
    private $_cookie_name = 'kaili_session';
    private $_cookie_path = '/';
    private $_cookie_domain = '';
    private $_weak_data_key = 'kaili_weak';
    private $_now;
    
    private $_load;
    
    
    public function __construct()
    {
        $this->_load = Loader::get_instance();
        
        $this->_now = time();
        
        if(!$this->_load_session()){
            $this->_create_session();
        }
        else{
            foreach($this->_session[$this->_weak_data_key] as $key=>$value){
                if(strpos($key, '::keep') > 0){
                    $key_rep = str_replace('::keep', '', $key);
                    $this->remove_weak_data($key);
                    $this->weak_data($key_rep, $value);
                }
                else{
                    $this->remove_weak_data($key);
                }
            }
        }
    }
    
    /**
     * Returns a session variable, or set a new one, if a value is provided as second argument.
     * @param string name of the session variable
     * @param mixed a value (optional)
     * @return mixed true, if variable is set, a value or false, il variable doesn't exists.
     */
    public function data($key, $value = null)
    {
        if($value !== null){
            $this->_session[$key] = $value;
            $this->_setcookie();
            return true;
        }
        return (isset($this->_session[$key])) ? $this->_session[$key] : false;
    }
    
    /**
     * Returns all session variables
     * @return array
     */
    public function all_data()
    {
        return (isset($this->_session)) ? $this->_session : false;
    }
    
    /**
     * Remove a session variable
     * @param string name of the session variable
     * @return true, if variable is set or false, il variable doesn't exists.
     */
    public function remove_data($key)
    {
        if(isset($this->_session[$key])){
            unset($this->_session[$key]);
            $this->_setcookie();
            return true;
        }
        return false;
    }
    
    /**
     * Returns a session variable, or set a new one, if a value is provided as second argument.
     * @param string name of the session variable
     * @param mixed a value (optional)
     * @return mixed true, if variable is set, a value or false, il variable doesn't exists.
     */
    public function weak_data($key, $value = null, $keep = false, $overwrite = true)
    {
        if($value !== null){
            if($keep) $key .= '::keep';
            
            // if $overwrite is setted to false, add values with same key into an array
            if(!$overwrite){
                if(isset($this->_session[$this->_weak_data_key][$key]) 
                    && is_array($this->_session[$this->_weak_data_key][$key])){
                    array_push($this->_session[$this->_weak_data_key][$key], $value);
                }
                else{
                    $this->_session[$this->_weak_data_key][$key] = array($value);
                }
            }
            $this->_session[$this->_weak_data_key][$key] = $value;
            $this->_setcookie();
            return true;
        }
        return (isset($this->_session[$this->_weak_data_key][$key])) ? $this->_session[$this->_weak_data_key][$key] : false;
    }
    
    /**
     * Returns all session variables
     * @return array
     */
    public function all_weak_data()
    {
        return (isset($this->_session[$this->_weak_data_key])) ? $this->_session[$this->_weak_data_key] : false;
    }
    
    /**
     * Remove weak data
     * @param string $key name of the weak data
     * @return boolean true, if variable is set or false, il variable doesn't exists.
     */
    public function remove_weak_data($key)
    {
        if(isset($this->_session[$this->_weak_data_key][$key])){
            unset($this->_session[$this->_weak_data_key][$key]);
            $this->_setcookie();
            return true;
        }
        return false;
    }
    
    /**
     * Load the session
     * @return boolean
     */
    private function _load_session()
    {
        $cookie = $this->_load->load('input')->cookie($this->_cookie_name);
             
        // if there isn't a cookie...
        if(!$cookie) return false;
        else{
            $cookie = $this->_unserialize($cookie);
        }
        
        // if it isn't an array... destroy it!
        if(!is_array($cookie)){
            $this->_destroy_session();
            return false;
        }
        
        // if it isn't in a correct format... destroy it!
        if(!isset($cookie['session_id']) || !isset($cookie['ip_address']) || !isset($cookie['user_agent']) || !isset($cookie['updated_at'])){
            $this->_destroy_session();
            return false;
        }
        
        // if it expired... destroy it!
        if($cookie['updated_at'] + $this->_session_expiration_time < $this->_now){
            $this->_destroy_session();
            return false;
        }
        
        // if ip address is different... destroy it!
        if($cookie['ip_address'] !== $this->_load->load('input')->ip_address()){
            $this->_destroy_session();
            return false;
        }
        
        // if user_agent is different... destroy it!
        if($cookie['user_agent'] !== $this->_load->load('input')->user_agent()){
            $this->_destroy_session();
            return false;
        }
        
        $this->_session = $cookie;
        return true;
    }
    
    /**
     * Create a new session
     */
    private function _create_session()
    {
        $this->_session = array(
            'session_id' => md5(uniqid(mt_rand(0, mt_getrandmax()), true)),
            'ip_address' => $this->_load->load('input')->ip_address(),
            'user_agent' => $this->_load->load('input')->user_agent(),
            'updated_at' => $this->_now,
            $this->_weak_data_key => array()
        );
    }
    
    /**
     * Update the session
     */
    private function _update_session()
    {
        // update only after a time (10 minutes is default)
        if(!(($this->_session['updated_at'] + $this->_session_update_time) >= $this->_now)) 
        {
            // update the session with a new id 
            $this->_session['session_id'] = md5(uniqid(mt_rand(0, mt_getrandmax()), true));
            $this->_session['updated_at'] = $this->_now;
            
            $this->_setcookie();
        }
    }
    
    /**
     * Destroy the session
     */
    private function _destroy_session()
    {
        setcookie(
            $this->_cookie_name,
            '',
            $this->_now - $this->_session_expiration_time,
            $this->_cookie_path,
            $this->_cookie_domain,
            0
        );
    }
    
    /** 
     * Save the session cookie
     */
    private function _setcookie()
    {
        $data = $this->_session;
        
        setcookie(
            $this->_cookie_name,
            $this->_serialize($data),
            $this->_now + $this->_session_expiration_time,
            $this->_cookie_path,
            $this->_cookie_domain
        );
    }
    
    /**
     * Serialize a string preserving slashes and quotes
     * @return string
     */
    private function _serialize()
    {
        // preserve slashes and quotes before serialization
        foreach($this->_session as $key=>$value){
            $this->_session[$key] = str_replace(array('/', '"'), array('&#039;','&quot;'), $value);
        }
        
        return serialize($this->_session);
    }
    
    /**
     * Unserialize a string restoring objects
     * @return mixed
     */
    private function _unserialize($data)
    {
        $data = unserialize($data);
        
        if(is_array($data)){
            foreach($data as $key=>$value){
                $data[$key] = str_replace(array('&#039;','&quot;'), array('/', '"'), $value);
            }
        }
        
        return $data;
    }
}

/* End of file Session.php */
/* Location: ./system/library/Session.php */
