<?php  if (!defined('ROOT')) exit('No direct script access allowed');

/**
 * Kaili Language Class
 *
 * Class to manage internationalization and localization
 *
 * @package		Kaili
 * @subpackage	Library
 * @category	Library
 * @author		Luigi Marco Simonetti
 */
 
 class Language
 {
    /**
     * @var array
     */
    private $_languages = array();
    
    /**
     * @var array
     */
    private $_internal_autoloaded = array('generics');
    
    /**
     * @var array
     */
    private $_items;
    
    /**
     * @var Input
     */
    private $_input;
    
    /**
     * @var Config
     */
    private $_config;
    
    /**
     * Create new Language object
     */
    function __construct()
    {
        $this->_config = Loader::get_instance()->load('config');
        $this->_input = Loader::get_instance()->load('input');
        
        $this->_autoload();
        if($lang = $this->_input->get('l')) 
            // select the current language by the url with the key 'l'
            // (es. http://www.example.com/controller/action/l/en/key1/val1/.../keyN/valN)
            $this->_items = &$this->_languages[$lang];
        else
            $this->_items = &$this->_languages[$this->_config->item('language')];
    }
    
    /**
     * Returns a config item
     * 
     * @param string $item the item's name.
     * @return the value of the item, or <code>false</code> if item doesn't exist
     */
    function item($item)
    {
        // find item by controller_action
        if(isset($this->_items[$this->_input->get('controller').'_'.
            $this->_input->get('action').'_'.$item])){
            return $this->_items[$this->_input->get('controller').'_'.
                $this->_input->get('action').'_'.$item];
        }
        // find item in default mode
        else if(isset($this->_items[$item])){
            return $this->_items[$item];
        }
        // find item by resource_action
        else if(isset($this->_items[$this->_input->get('resource').'_'.
            trim($this->_input->get('action'), '_').'_'.$item])){
            return $this->_items[$this->_input->get('resource').'_'.
                trim($this->_input->get('action'), '_').'_'.$item];
        }
        return false;
    }
    
    /**
     * Set a new language item
     * 
     * @param string $language
     * @param mixed $item name of the item or an array of values
     * @param string $value 
     */
    function set($language, $item, $value = "")
    {
        if(!is_array($item)){
            $this->_items[$language][$item] = $value; 
        }
        else{
            $this->_languages[$language] = array_merge($this->_languages[$language], $item);
        }
    }
    
    /**
     * Load a language file
     * 
     * @param string $file the path of the language file
     */
    function load($lang_file)
    {
        foreach($this->_config->item('languages') as $language){
                        
            $file = 'language'.DS.$language.DS.$lang_file.'.lang.php';
            
            // find the current module
            $module = $this->_input->get('module').DS; 
            if(empty($module)) $module = '';
            
            // find language file in application directory
            if(file_exists(APPLICATION.DS.$module.$file)){
                $lang = array();
                include(APPLICATION.DS.$module.$file);
                $this->_languages[$language] = 
                    array_merge((array)$this->_languages[$language], (array)$lang);
            }
            // otherwise, find it in system directory
            else if(file_exists(SYSTEM.DS.$file)){
                $lang = array();
                include(SYSTEM.DS.$file);
                $this->_languages[$language] = 
                    array_merge((array)$this->_languages[$language], (array)$lang);
            }
            else throw new Exception('Language file '.$file.' ('.$language.') not found.');
        }
    }
    
    /**
     * Autoload languages setted in application/config/autoload.php
     */
    private function _autoload()
    {
        // autoload languages
        foreach($this->_config->item('languages') as $lang){
            $this->_languages[$lang] = array();
        }
        
        // autoload language files
        foreach($this->_internal_autoloaded as $file){
            $this->load($file);
        } 
    }
 }

