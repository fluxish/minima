<?php  if (!defined('ROOT')) exit('No direct script access allowed');

/**
 * Kaili Input Class
 *
 * Provides various functions to get input parameters and manage input checking and security.
 *
 * @package		Kaili
 * @subpackage	Libraries
 * @category	Libraries
 * @author		Luigi Marco Simonetti
 */
 
class Input
{
    private $_params;
    
    public function __construct()
    {
        $this->_remove_magic_quotes();
        $this->_unregister_globals();
        
        $router = new Router();
        $this->_params = $router->parse_route($this->get('url'));
        //unset($_GET['url']);
    }
    
    /**
     * Return a GET parameter
     * @param string parameter name
     * @return string parameter value
     */
    public function get($par = null, $xss_clean = false)
    {
        return $this->_from_array($_GET, $par, $xss_clean);
    }
    
    /**
     * Return a POST parameter
     * @param string parameter name
     * @return string parameter value
     */
    public function post($par = null, $xss_clean = false)
    {
        return $this->_from_array($_POST, $par, $xss_clean);
    }
    
    /**
     * Return a parameter (GET or POST)
     * @param string parameter name
     * @return string parameter value
     */
    public function parameter($par = null, $xss_clean = false)
    {
        $value = $this->_from_array($_POST, $par, $xss_clean);
        if(!$value){
            return $this->_from_array($_GET, $par, $xss_clean);
        }
        return $value;
    }
    
    /**
     * Return a variable from SERVER array
     * @param string parameter name
     * @return string parameter value
     */
    public function server($par = null, $xss_clean = false)
    {
        return $this->_from_array($_SERVER, $par, $xss_clean);
    }
    
    /**
     * Return a variable from COOKIE array
     * @param string parameter name
     * @return string parameter value
     */
    public function cookie($par = null, $xss_clean = false)
    {
        return $this->_from_array($_COOKIE, $par, $xss_clean);
    }
    
    /**
     * Clean a string from various types of cross site scripting attempts
     * @param string
     * @return string
     */
    public function xss_clean($value)
    {
        if(is_array($value)){
            foreach($value as $k=>$v){
                $value[$k] = htmlentities($v);
            }
        }
        else{
            $value = htmlentities($value);
        }
        return $value;
    }
    
    public function user_host()
    {
        return $this->server('HTTP_HOST');
    }
    
    public function user_agent()
    {
        return $this->server('HTTP_USER_AGENT');
    }
    
    public function ip_address()
    {
        return $this->server('REMOTE_ADDR');
    }
    
    public function referer()
    {
        return $this->server('HTTP_REFERER');
    }
    
    public function current_url()
    {
        return trim($this->get('url'), '/');
    }
    
    public function url_parameters()
    {
        return $this->_params;
    }
    
    /**
     * Get a value from an array
     * @param array
     * @param string key within key-value pair
     * @param boolean true if need to clean value from xss before to return it, 
     *      false otherwise (default)
     * @return mixed
     */ 
    private function _from_array($array, $par, $xss_clean = false)
    {
        if(isset($array[$par])){ 
            if($xss_clean){
                return $this->xss_clean($array[$par]);
            }
            else return $array[$par];
        }
        else if($par == null){
            if($xss_clean){
                return $this->xss_clean($array);
            }
            else return $array;
        }
        else return false;
    }
    
    /** 
     * Check for Magic Quotes in a value, and remove them.
     * @param string value
     * @return clean value
     */
    private function _strip_slashes_deep($value)
    {
        $value = is_array($value) ? array_map(array($this, '_strip_slashes_deep'), $value) : stripslashes($value);
        return $value;
    }

    /**
     * Remove magic quotes from POST and COOKIE parameters
     */
    private function _remove_magic_quotes()
    {
        if(get_magic_quotes_gpc()){
            $_GET = $this->_strip_slashes_deep($_GET);
            $_POST = $this->_strip_slashes_deep($_POST);
            $_COOKIE = $this->_strip_slashes_deep($_COOKIE);
        }
    }

    /** 
     * Check register globals and remove them 
     */
    private function _unregister_globals()
    {
        if (ini_get('register_globals')) {
            $array = array('_SESSION', '_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');
            foreach ($array as $value) {
                foreach ($GLOBALS[$value] as $key => $var) {
                    if ($var === $GLOBALS[$key]) {
                        unset($GLOBALS[$key]);
                    }
                }
            }
        }
    }
}
 
/* End of file Input.php */
/* Location: ./system/library/Input.php */
