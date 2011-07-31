<?php  if ( ! defined('ROOT')) exit('No direct script access allowed');

/**
 * Kaili Loader Class
 *
 *
 * @package		Kaili
 * @subpackage	Libraries
 * @category	Libraries
 * @author		Luigi Marco Simonetti
 */
 
class Loader
{
    /**
     * @var Loader
     */   
    private static $_instance = null;
    
    /**
     * @var array
     */
    private $_loaded_libraries = array();
    
    /**
     * @var array
     */
    private $_loaded_plugins = array();
    
    /**
     * @var Config
     */
    private $_config;
    
    /**
     * @var array
     */
    private $_internal_autoload = array('input', 'output');
    
    
    /**
     * Create the loader for all classes 
     */
    private function __construct()
    {
        $this->_config = $this->library('config');
    }
    
    /**
     * Load an helper
     * 
     * @param string $helper the unique name of the helper 
     */
    function helper($helper)
    {
        include_once(SYSTEM.DS.'helpers'.DS.$helper.'.helper.php');
    }
    
    /**
     * Load an MVC controller
     * 
     * @param string $controller the name of the controller (for convention,
     *        in plural form)  
     * @param string $action the name of controller's action called 
     * @param array $url_segments an array of parameters give from the url
     * @param boolean $with_template TRUE (default) if the template is required, 
     *        FALSE otherwise
     */
    function controller($controller, $action, $url_segments = array())
    {
        $controller_name = $controller;
        $action_name = strtolower($action);
        
        // creation of controller
        $controller = ucwords($controller_name);
	    $controller = new $controller();
	    
        // call action of the controller
	    if ((int)method_exists($controller_name, $action_name)) {
		    call_user_func_array(array($controller, $action_name), array_values($url_segments));
	    } 
    }
    
    /**
     * Load a class from the framework's library
     * 
     * @param string $lib the name of the class
     */
    function library($lib)
    {
        if(!array_key_exists($lib, $this->_loaded_libraries)){
            $libName = ucwords($lib);
            $this->_loaded_libraries[$lib] = new $libName();
        }
        return $this->_loaded_libraries[$lib];
    }
    
    /**
     * Autoload all classes selected in the config file autoload.php
     */
    function autoload()
    {
        // autoload internal libraries
        foreach($this->_internal_autoload as $lib){
            $this->library($lib);
        }
        
        // autoload libraries in autoload config file
        foreach($this->_config->item('libraries') as $lib){
            $this->library($lib);
        }
        
        // autoload helpers
        foreach($this->_config->item('helpers') as $help){
            $this->helper($help);
        }
    }
    
    /**
     * Returns the singleton instance of the loader
     * 
     * @return Loader
     */
    static function get_instance()
    {
        if(self::$_instance == null){
            self::$_instance = new Loader();
        }
        return self::$_instance;
    }
}

/* End of file Loader.php */
/* Location: ./system/library/Loader.php */
