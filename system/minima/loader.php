<?php

namespace Minima;

/**
 * Loader class
 * The Loader class is used to bootstrap entire framework and to load all classes
 * and other resource of Minima
 *
 * @package	Minima
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
    private $_internal_autoload = array();

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
        spl_autoload_register('Minima\Loader::_autoload');
        //$this->_config = $this->load('config');

        //$this->_preload();
    }

    /**
     * Load a class
     * @param string $class the name of the class
     */
    public function load($class)
    {
        // namespaced
        $class = '\\Minima\\'.ucfirst($class);
        if(class_exists($class)) {
            return new $class;
        }
        else {
            $this->_autoload($class);
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
        else if(file_exists(SYSTEM.DS.'minima'.DS.$class.EXT)) {
            require_once(SYSTEM.DS.'minima'.DS.$class.EXT);

            $class_name = ucfirst($class);
            class_alias('\\Minima\\'.$class_name, $class_name);
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
//
//    /**
//     * Autoload all classes selected in the config file autoload.php
//     */
//    private function _preload()
//    {
//        // autoload internal libraries
//        foreach($this->_internal_autoload as $lib) {
//            $this->load($lib);
//        }
//
//        // autoload libraries in autoload config file
//        $libraries = $this->_config->item('libraries');
//        foreach($libraries as $lib) {
//            $this->load($lib);
//        }
//        unset($libraries, $lib);
//    }

}

