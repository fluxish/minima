<?php  if (!defined('ROOT')) exit('No direct script access allowed');

/**
 * Kaili Logger_File Class
 *
 * Log messages to a file
 *
 * @package     Kaili
 * @subpackage  Logger
 * @category    Library
 * @author      Luigi Marco Simonetti
 */

class Logger_File extends Logger
{
    private $_file;
    
    
    public function __construct($logger_data)
    {
        $this->_file = fopen($logger_data['file'], 'a');   
    }
    
    public function __destruct()
    {
        fclose($this->_file);
        unset($this->_file);
    }
    
    /**
     * Save data with the logger
     * @param mixed $data
     * @param string $marker 
     */
    public function log($data, $type = Logger::INFO)
    {
        $marker = $this->_generate_marker($type);
        return fprintf($this->_file, "%s%s\n", $marker, $data);
    }
}

/* End of file Interface.php */
/* Location: ./system/library/Logger/Interface.php */
