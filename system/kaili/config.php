<?php

namespace Kaili;

/**
 * Kaili Config Class
 *
 * Class to manage config files
 *
 * @package Kaili
 */
class Config
{
    /**
     * Create a Config object
     * @param string $file the name of the config file to load at the creation
     * @return Config
     */
    public static function factory($file = null)
    {
        // Create a new empty Config object
        $config = new static();
        
        if($file === null){
            // load main config file (CONFIG/config.php)
            $config->load('config');
            
            //load other autoloaded config files (in CONFIG/autoload.php)
            $config->load('autoload');
            $config->_autoload();
        }
        else{
            $config->load($file);
        }
        
        return $config;
    }
    
    
    /**
     * Array of config items
     * @var array
     */
    private $_config;

    /**
     * Create new Config object
     */
    function __construct()
    {
        $this->_config = array();
    }

    /**
     * Returns a config item
     * 
     * @param string $item the item's name.
     * @return the value of the provided item
     */
    function item($item)
    {
        if(isset($this->_config[$item]))
            return $this->_config[$item];
        else
            throw new \InvalidArgumentException('Undefined item "'.$item.'" in configuration files.');
    }

    /**
     * Set a new config item
     * 
     * @param string item's name
     * @param mixed item's value
     */
    function set($item, $value)
    {
        $this->_config[$item] = $value;
    }

    /**
     * Load a config file
     * 
     * @param the name of the config file
     */
    function load($file)
    {
        // check in application dir
        $file = CONFIG.DS.$file.EXT;
        if(!file_exists($file))
            throw new Exception('Config file "'.$file.'" not found.');

        $config = array();
        include($file);
        $this->_config = array_merge((array) $this->_config, (array) $config);
        unset($config);
    }

    /**
     * Autoload config files setted in application/config/autoload.php
     */
    private function _autoload()
    {
        foreach($this->item('configs') as $config) {
            $this->load($config);
        }
    }

}

