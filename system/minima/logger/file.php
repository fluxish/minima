<?php  

namespace Minima\Logger;

/**
 * Minima Logger_File Class
 *
 * Log messages to a file
 *
 * @package     Minima
 * @subpackage  Logger
 */

class File extends \Minima\Logger
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

