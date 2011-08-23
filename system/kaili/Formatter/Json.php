<?php  if ( ! defined('ROOT')) exit('No direct script access allowed');

/**
 * Kaili Formatter Class
 *
 * This class generate an output in json format
 *
 * @package		Kaili
 * @subpackage	Libraries/Formatter/Object
 * @category	Libraries/Formatter
 * @author		Luigi Marco Simonetti
 */
 
class Formatter_Json
{
    public function format($data)
    {
        return json_encode($data);
    }
}

/* End of file Json.php */
/* Location: ./system/library/Formatter/Json.php */
