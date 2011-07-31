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
    
    
    
    
    public function in_place_edit($selector, $field, $action)
    {
        // add in-place textbox
        $s_on_click = "
        var content;
        var id;
        $('$selector').bind('click', function(event){
            if(!$(this).hasClass('ui-editing')){
                $(this).removeClass('ui-editable');
                $(this).addClass('ui-editing');
                content = $(this).html();
                $(this).empty();
                
                var input = document.createElement('input');
                input.name = '$field';
                input.type = 'text';
                $(input).val(content);
                $(this).append(input);
            }
        })";
        
        // remove in-place textbox and show edited text
        $s_on_keypress = "
        var val;
        $('$selector').bind('keypress', function(event){
            if($(this).hasClass('ui-editing') && event.keyCode == 13 && event.ctrlKey == true){
                $(this).addClass('ui-editable');
                $(this).removeClass('ui-editing');
                val = $('$selector input').val();
                
                /* ajax call */
                $.post('$action', {'$field': val, '_method': 'put'},
                    function(data){},
                    'html'
                );
                
                $(this).empty();
                $(this).html(val);
            }
        })";
        
        $this->add_script($s_on_click);
        $this->add_script($s_on_keypress);
    }
    
    public function in_place_editor($selector, $field, $action)
    {
        $editor = Loader::get_instance()->plugin('CKEditor');
        
        // remove in-place textbox and show edited text
        $s_on_keypress = "
        var val;
        if(event.keyCode == 13 && event.ctrlKey == true){
            $('$selector').addClass('ui-editable');
            $('$selector').removeClass('ui-editing');
            val = ".$editor->content($selector)."
            
            /* ajax call */
            $.post('$action', {'$field': val, '_method': 'put'},
                function(data){},
                'html'
            );
            
            $('$selector').html(val);
            ".$editor->remove($selector)."
        }";
        
        // add in-place textbox
        $s_on_click = "
        var content;
        var id;
        $('$selector').bind('click', function(event){
            if(!$(this).hasClass('ui-editing')){
                $(this).removeClass('ui-editable');
                $(this).addClass('ui-editing');
                
                ".$editor->replace($selector)."
                ".$editor->add_event($selector, 'keypress',$s_on_keypress)."
            }
        })";
        
        
        $this->add_script($s_on_click);
    }
    
}
 
/* End of file Javascript.php */
/* Location: ./system/library/Javascript.php */
