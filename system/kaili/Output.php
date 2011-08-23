<?php  if ( ! defined('ROOT')) exit('No direct script access allowed');

/**
 * Kaili Output Class
 *
 * Manage output processes
 *
 * @package		Kaili
 * @subpackage	Libraries
 * @category	Libraries
 * @author		Luigi Marco Simonetti
 */ 

class Output
{
    /**
     * @var string
     */
    private $_output;
    
    /**
     * @var array
     */
    private $_headers;
    
    /**
     * @var Loader
     */
    private $_load;
    
    public function __construct()
    {
        $this->_load = Loader::get_instance();
        $this->_headers = array();
        
        $this->_load->helper('url');
    }
    
    /**
     * Append a buffer to final output
     * @param string a buffer
     */
    public function append($buff)
    {
        if(empty($this->_output)){
            $this->_output = $buff;
        }
        else{
            $this->_output .= $buff;
        }
    }
    
    /**
     * Display the final output
     */
    public function display()
    {
        foreach($this->_headers as $header){
            header($header, true);
        }
        echo $this->_output;
    }
    
    public function set_header($header)
    {
        $this->_headers[] = $header;
    }
    
    public function redirect_to($url)
    {
        $this->set_header('Location: '.url($url));
    }
    
    public function redirect_to_referer()
    {
        $this->set_header('Location: '.Loader::get_instance()->library('input')->referer());
    }
}

/* End of file Output.php */
/* Location: ./system/library/Outputter.php */
