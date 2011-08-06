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

class Logger_File implements Logger_Interface
{
    private $_file;
    
    
    public function __construct($logger_data)
    {
        $this->_file = fopen($logger_data['file'], 'w');   
    }
    
    public function __destruct()
    {
        fclose($this->_file);
        unset($this->_file);
    }
    
    /**
     * Save data with the logger
     * @param mixed $data
     */
    public function log($data)
    {
        return fprintf($this->_file, '%s', $data);
    }
}

/* End of file Interface.php */
/* Location: ./system/library/Logger/Interface.php */
