<?php  if (!defined('ROOT')) exit('No direct script access allowed');

/**
 * Kaili Logger Class
 *
 * Class to log messages
 *
 * @package     Kaili
 * @subpackage  Library
 * @category    Library
 * @author      Luigi Marco Simonetti
 */

class Logger
{
    /**
     * Array of all added loggers
     * @var array
     */
    private $_loggers;
    
    /**
     * Create a new loggers container
     */
    public function __construct()
    {
        $this->_loggers = array();
    }
    
    /**
     * Add a logger to the container
     * @param string $name the name of the logger
     * @param Logger_Interface $logger the logger
     */
    public function add($name, Logger_Interface $logger)
    {
        $this->_loggers[$name] = $logger;
    }
    
    /**
     * Save data with a logger
     * @param mixed $data
     * @param string $logger the name of the logger to use
     */
    public function log($data, $logger = null)
    {
        // select the provided logger, or the first if $logger is null
        if($logger != null)
            $logger = $this->_loggers[$logger];
        else
            $logger = $this->_loggers[0];
        
        $logger->log($data);
    }
    
    public function __destruct()
    {
        unset($this->_logger);
    }
}

/* End of file Logger.php */
/* Location: ./system/library/Logger.php */
