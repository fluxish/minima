<?php  if (!defined('ROOT')) exit('No direct script access allowed');

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
    private $_scripts = array();
    private $_config;
    
    public function __construct()
    {
        $this->_config = Loader::get_instance()->library('config');
    }
    
    public function add_script($script, $output = false)
    {
        $script = "$(document).ready(function(){\n".$script."});\n";
        $this->_scripts[] = $script;
        if($output) return $script;
    }
    
    public function output_libraries()
    {
        $libraries = '';
        foreach($this->_config->item('javascript', 'files') as $lib){
            $libraries .= '<script type="text/javascript" src="'.$this->_config->item('base_url').$lib."\"></script>\n";
        }
        
        return $libraries;
    }
    
    public function output_scripts()
    {
        $script = "<script type=\"text/javascript\">\n";
        foreach($this->_scripts as $s){
            $script .= $s;
        }
        $script .= "\n</script>\n";
        return $script;
    }
}
 
/* End of file Javascript.php */
/* Location: ./system/library/Javascript.php */
