<?php

namespace Kaili;

/**
 * Kaili Config Class
 *
 * Class to manage config files
 *
 * @package		Kaili
 */
 
 class Config
 {
    
    var $_config = array(); 
    
    /**
     * Create new Config object
     */
    function __construct(){
        // load main config file (application/config/config.php)
        $this->load('config');
        $this->load('autoload');
        $this->_autoload();
    }
    
    /**
     * Returns a config item
     * 
     * @param string the item's name.
     * @return the value of the provided item
     */
    function item()
    {
        if(func_num_args() != 0){
            $config = $this->_config;
            $args = func_get_args();
            foreach($args as $item){
                $config = $config[$item];
            }
            unset($args);
            return $config;
        }
        else throw new InvalidArgumentException('Undefined item "'.$item.'" in configuration files.');
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
     * @param the path of the config file
     */
    function load($file)
    {
        // check in application dir
        $file = APPLICATION.DS.'config'.DS.$file.EXT;
        if(!file_exists($file))
            throw new Exception('Config file "'.$file.'" not found.');
        
        $config = array();
        include($file);
        $this->_config = array_merge((array)$this->_config, (array)$config);
        unset($config);
    }
    
    /**
     * Autoload config files setted in application/config/autoload.php
     */
    private function _autoload()
    {
        foreach($this->item('configs') as $config){
            $this->load($config);
        }
    }
 }

