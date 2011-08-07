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
        Loader::get_instance()->library('config')->load('logger');
    }
    
    /**
     * Add a logger to the container
     * @param string $name the name of the logger
     * @param Logger_Interface $logger the logger
     */
    public function add($name, Logger_Interface $logger = null)
    {
        if($logger != null)
            $this->_loggers[$name] = $logger;
        else{
            // create a new logger
            $loggers = Loader::get_instance()->library('config')->item('loggers');
            $logger = 'Logger_'.ucwords($loggers[$name]['type']);
            $this->_loggers[$name] = new $logger($loggers[$name]);
        }
        
    }
    
    /**
     * Save data with a logger
     * @param mixed $data
     * @param string $logger the name of the logger to use
     */
    public function log($data, $logger = null)
    {
        // select the provided logger, or the current if $logger is null
        if($logger != null)
            $logger = $this->_loggers[$logger];
        else
            $logger = current($this->_loggers);
        
        $logger->log($data, $marker);
    }
    
    public function __destruct()
    {
        unset($this->_logger);
    }
    
    private function _generate_marker()
    {
        $timestamp = date(DATE_W3C);
        return $timestamp.' ['.$type.']: ';
    }
}

/* End of file Logger.php */
/* Location: ./system/library/Logger.php */
