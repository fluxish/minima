<?php

namespace Kaili;

/**
 * Loader class
 * The Loader class is used to bootstrap entire framework and to load all classes
 * and other resource of Kaili
 *
 * @package	Kaili
 */
class Loader
{

    /**
     * Singleton instance of Loader
     *
     * @var Loader
     */
    private static $_instance = null;

    /**
     * Returns the singleton instance of the loader
     *
     * @return Loader
     */
    public static function get_instance()
    {
        if(self::$_instance == null) {
            self::$_instance = new Loader();
        }
        return self::$_instance;
    }

    /**
     * Instance of Config class
     *
     * @var Config
     */
    private $_config;

    /**
     * Array of default loaded libraries
     *
     * @var array
     */
    private $_internal_autoload = array('request', 'output');

    /**
     * Create the Loader
     */
    private function __construct()
    {
        
    }

    /**
     * Register the autoloader of this class loader
     */
    public function register()
    {
        spl_autoload_register('Kaili\Loader::_autoload');
        $this->_config = $this->load('config');

        $this->_preload();
    }

    /**
     * Load an helper
     * @param string $helper the unique name of the helper 
     */
    public function helper($helper)
    {
        include_once(SYSTEM.DS.'kaili'.DS.$helper.EXT);
    }

    /**
     * Load a class
     * @param string $class the name of the class
     */
    public function load($class)
    {
        // namespaced
        $class = '\\Kaili\\'.ucfirst($class);
        if(class_exists($class)) {
            return new $class;
        }
        else {
            $this->_autoload($class);
        }
    }

    /**
     * Load an MVC controller
     * @param string $controller the name of the controller (for convention,
     *        in plural form)  
     * @param string $action the name of controller's action called 
     * @param array $url_segments an array of parameters give from the url
     */
    public function controller($controller, $action, $url_segments = array())
    {
        $controller_name = $controller;
        $action_name = strtolower($action);

        // creation of controller
        $controller = ucfirst($controller_name);
        $controller = new $controller();

        // call action of the controller
        if((int) method_exists($controller_name, $action_name)) {
            call_user_func_array(array($controller, $action_name), array_values($url_segments));
        }
    }

    /**
     * Autoload any classes that are required
     *
     * @param string $class the name of required class
     */
    private function _autoload($class)
    {
        // create the path from the name of the class
        $class = strtolower(str_replace('\\', DS, $class));

        if(file_exists(SYSTEM.DS.$class.EXT)) {
            // namespaced classes
            require_once(SYSTEM.DS.$class.EXT);
        }
        else if(file_exists(SYSTEM.DS.'kaili'.DS.$class.EXT)) {
            require_once(SYSTEM.DS.'kaili'.DS.$class.EXT);

            $class_name = ucfirst($class);
            class_alias('\\Kaili\\'.$class_name, $class_name);
        }
        else if(file_exists(APPLICATION.DS.'controllers'.DS.$class.EXT)) {
            // controllers (generally in /application/controllers)
            require_once(APPLICATION.DS.'controllers'.DS.$class.EXT);
        }
        else if(file_exists(APPLICATION.DS.'models'.DS.$class.EXT)) {
            // models (generally in /application/models)
            require_once(APPLICATION.DS.'models'.DS.$class.EXT);
        }
    }

    /**
     * Autoload all classes selected in the config file autoload.php
     */
    private function _preload()
    {
        // autoload internal libraries
        foreach($this->_internal_autoload as $lib) {
            $this->load($lib);
        }

        // autoload libraries in autoload config file
        $libraries = $this->_config->item('libraries');
        foreach($libraries as $lib) {
            $this->load($lib);
        }
        unset($libraries, $lib);
    }

}

