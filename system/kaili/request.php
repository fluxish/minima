<?php

namespace Kaili;

/**
 * Request Class
 * The Request class handles all the request from a client
 * 
 * @package Kaili
 */
class Request
{

    /**
     * The current Request object
     * @var Kaili\Request
     */
    private static $_current = null;
    
    
   /**
    * Returns the current 
    * @return Kaili\Request
    */
    public static function current()
    {
        return self::$_current;
    }
    
    public static function factory()
    {
        $request = new Request();
        static::$_current = $request;
        return $request;
    }
    
    /**
     * Array of all parameters of a requested URL
     * @var array
     */
    private $_params;

    
    /**
     * Create a new Request object
     */
    public function __construct()
    {
        $this->_remove_magic_quotes();
        $this->_unregister_globals();

        $router = new Router();
        $this->_params = $router->parse_route($this->get('url'));
    }

    /**
     * Prepare the framework to the requests
     * @param function $pre_controller a closure to call before controller
     * @param function $post_controller a closure to call after controller
     */
    public function handle($pre_controller = null, $post_controller = null)
    {
        // execute a pre-controller call function
        if(is_callable($pre_controller))
            $pre_controller();

        // get controller and action names
        $controller = $this->get('controller');
        $action = strtolower($this->get('action'));

        // create the new controller
        $controller = ucfirst($controller);
        $controller_obj = new $controller();

        // call action of the controller
        if((int) method_exists($controller, $action)) {
            $controller_obj->{$action}(array_values($this->get()));
        }

        // execute a post-controller call function
        if(is_callable($post_controller))
            $post_controller();
    }

    /**
     * Returns a GET parameter.
     * 
     * @param string parameter name
     * @return string parameter value
     */
    public function get($par = null, $xss_clean = false)
    {
        return $this->_from_array($_GET, $par, $xss_clean);
    }

    /**
     * Returns a POST parameter.
     * 
     * @param string parameter name
     * @return string parameter value
     */
    public function post($par = null, $xss_clean = false)
    {
        return $this->_from_array($_POST, $par, $xss_clean);
    }

    /**
     * Returns a parameter (GET or POST).
     * 
     * @param string parameter name
     * @return string parameter value
     */
    public function parameter($par = null, $xss_clean = false)
    {
        $value = $this->_from_array($_POST, $par, $xss_clean);
        if(!$value) {
            return $this->_from_array($_GET, $par, $xss_clean);
        }
        return $value;
    }

    /**
     * Returns a variable from SERVER array.
     * 
     * @param string parameter name
     * @return string parameter value
     */
    public function server($par = null, $xss_clean = false)
    {
        return $this->_from_array($_SERVER, $par, $xss_clean);
    }

    /**
     * Returns a variable from COOKIE array.
     * 
     * @param string parameter name
     * @return string parameter value
     */
    public function cookie($par = null, $xss_clean = false)
    {
        return $this->_from_array($_COOKIE, $par, $xss_clean);
    }

    /**
     * Cleans a string from various types of cross site scripting attempts.
     * 
     * @param string
     * @return string
     */
    public function xss_clean($value)
    {
        if(is_array($value)) {
            foreach($value as $k => $v) {
                $value[$k] = htmlentities($v);
            }
        }
        else {
            $value = htmlentities($value);
        }
        return $value;
    }

    /**
     * Returns the http address of the host
     * It's an alias for $_SERVER['HTTP_HOST']
     * 
     * @return string 
     */
    public function user_host()
    {
        return $this->server('HTTP_HOST');
    }

    /**
     * Returns the user agent of that have made the request.
     * It's an alias for $_SERVER['HTTP_USER_AGENT'].
     * 
     * @return string the user agent
     */
    public function user_agent()
    {
        return $this->server('HTTP_USER_AGENT');
    }

    /**
     * Returns the ip address of the user that has made the request.
     * It's an alias for $_SERVER['REMOTE_ADDR].
     * 
     * @return string the remote ip address 
     */
    public function ip_address()
    {
        return $this->server('REMOTE_ADDR');
    }

    /**
     * Return the URL of the page from where the request has been made.
     * It's an alias for $_SERVER['HTTP_REFERER'].
     * 
     * @return string the http referer
     */
    public function referer()
    {
        return $this->server('HTTP_REFERER');
    }

    /**
     * Return the current URL
     * 
     * @return string the current URL
     */
    public function current_url()
    {
        return trim($this->get('url'), '/');
    }

    /**
     * Return an array af all parameters in the current URL
     * 
     * @return array of paramaters in the current URL
     */
    public function url_parameters()
    {
        return $this->_params;
    }

    /**
     * Get a value from an array
     * 
     * @param array
     * @param string key within key-value pair
     * @param boolean true if need to clean value from xss before to return it, 
     *      false otherwise (default)
     * @return mixed
     */
    private function _from_array($array, $par, $xss_clean = false)
    {
        if(isset($array[$par])) {
            if($xss_clean) {
                return $this->xss_clean($array[$par]);
            }
            else
                return $array[$par];
        }
        else if($par == null) {
            if($xss_clean) {
                return $this->xss_clean($array);
            }
            else
                return $array;
        }
        else
            return false;
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
        if(get_magic_quotes_gpc()) {
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
        if(ini_get('register_globals')) {
            $array = array('_SESSION', '_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');
            foreach($array as $value) {
                foreach($GLOBALS[$value] as $key => $var) {
                    if($var === $GLOBALS[$key]) {
                        unset($GLOBALS[$key]);
                    }
                }
            }
        }
    }

}

