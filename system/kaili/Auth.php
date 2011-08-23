<?php  if (!defined('ROOT')) exit('No direct script access allowed');

/**
 * Kaili Authentication Class
 *
 * Class to manage an authentication system
 *
 * @package		Kaili
 * @subpackage	Library
 * @category	Library
 * @author		Luigi Marco Simonetti
 */

class Auth
{
    /**
     * @var Loader
     */
    private $_load;
    
    public function __construct()
    {
        $this->_load = Loader::get_instance();
    }
    
    /**
     * Authenticate an existent identity with a valid credential
     * @param string name of the identity
     * @param string password of the identity
     * @return a code message
     */
    public function authenticate($identity, $pwd)
    {
        if($this->_load->load('session')->data('identity') != false){
            return self::FAILURE_IDENTITY_ALREADY_AUTH;
        }
              
        $users = new User();
        $user = $users->check_identity($identity, $pwd);
        
        if($user != false){
            $this->_load->load('session')->data('identity', $user);
            return self::SUCCESS;
        } else if($user == false){
            return self::FAILURE_CREDENTIAL_INVALID;
        }
        return self::FAILURE_IDENTITY_NOT_FOUND;
    }
    
    /**
     * Verify if identity is authenticated
     * @return boolean
     */
    public function is_authenticated()
    {
        return ($this->_load->load('session')->data('identity') !== false);
    }
    
    /**
     * Revoke authentication to current local identity
     * @return a code message
     */
    public function revoke_authentication()
    {
        if($this->_load->load('session')->data('identity') != false){
            $this->_load->load('session')->remove_data('identity');
            return self::SUCCESS;
        }
        return self::FAILURE_IDENTITY_NOT_AUTH;
    }
    
    /**
     * Result of an authentication failure
     */
    const FAILURE = 0;
    
    /**
     * Result of an authentication success
     */
    const SUCCESS = 1;
    
    /**
     * Result of an authentication failure not finding the identity
     */
    const FAILURE_IDENTITY_NOT_FOUND = -1;
    
    /**
     * Result of an authentication failure with invalid credential
     */
    const FAILURE_CREDENTIAL_INVALID = -2;
    
    /**
     * Result of an authentication failure with an already authenticated identity
     */
    const FAILURE_IDENTITY_ALREADY_AUTH = -3;
    
    /**
     * Result of an authentication revoke failure of a not authenticated identity
     */
    const FAILURE_IDENTITY_NOT_AUTH = -4;
}

/* End of file Auth.php */
/* Location: ./system/library/Auth.php */
