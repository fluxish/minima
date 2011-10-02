<?php  

namespace Kaili;

/**
 * Kaili Javascript Class
 *
 * Support Javascript library in Kaili
 *
 * @package		Kaili
 * @subpackage	Libraries
 * @category	Libraries
 * @author		Luigi Marco Simonetti
 */
 
class Javascript
{
    /**
     * Array of all added scripts
     * @var array
     */
    private $_scripts = array();
    
    /**
     * @var Config
     */
    private $_config;
    
    public function __construct()
    {
        $this->_config = Config::factory();
        $this->_config->load('javascript');
    }
    
    /**
     * Add a script to the container
     * @param string $script
     */
    public function add_script($script)
    {
        $this->_scripts[] = $script;
    }
    
    /**
     * Generate the tag <script> for all javascript libraries
     * @return string
     */
    public function output_libraries()
    {
        $libraries = '';
        foreach($this->_config->item('javascript', 'libraries') as $lib){
            $libraries .= '<script type="text/javascript" src="'.
                $this->_config->item('base_url').$lib."\"></script>\n";
        }
        
        return $libraries;
    }
    
    /**
     * Generate a tag <script> that contains all scripts added in the container
     * @param boolean $onload set true to load the scripts when all 
     *      the page is loaded (default: true)
     * @return string
     */
    public function output_scripts($onload = true)
    {
        foreach($this->_scripts as $s){
            $script .= $s . "\n";
        }
        
        if($onload) $script = "$(document).ready(function(){\n".$script."});\n";
        
        $script = "<script type=\"text/javascript\">\n".$script."\n</script>\n";
        
        return $script;
    }
    
}

