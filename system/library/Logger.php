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
     * Log a message with a logger
     * @param mixed $data
     * @param string $logger the name of the logger to use
     */
    public function log($data, $type = Logger::INFO, $logger = null)
    {
        // select the provided logger, or the current if $logger is null
        if($logger != null)
            $logger = $this->_loggers[$logger];
        else{
            $logger = reset($this->_loggers);
        }
        
        $logger->log($data, $this->_generate_marker($type));
    }
    
    /**
     * Log an info message with a logger
     * @param mixed $data
     * @param string $logger the name of the logger to use
     */
    public function info($data, $logger = null)
    {
        $this->log($data, Logger::INFO, $logger);
    }
    
    /**
     * Log a warning message with a logger
     * @param mixed $data
     * @param string $logger the name of the logger to use
     */
    public function warning($data, $logger = null)
    {
        $this->log($data, Logger::WARNING, $logger);
    }
    
    /**
     * Log an error message with a logger
     * @param mixed $data
     * @param string $logger the name of the logger to use
     */
    public function error($data, $logger = null)
    {
        $this->log($data, Logger::ERROR, $logger);
    }
    
    /**
     * Log a success message with a logger
     * @param mixed $data
     * @param string $logger the name of the logger to use
     */
    public function success($data, $logger = null)
    {
        $this->log($data, Logger::SUCCESS, $logger);
    }
    
    public function __destruct()
    {
        unset($this->_logger);
    }
    
    /**
     * Generate a marker for a log message
     * @param string $type the type of the message
     * @return string
     */
    private function _generate_marker($type)
    {
        $timestamp = date(DATE_W3C);
        return $timestamp.' ['.$type.']: ';
    }
    
    /**
     * Type of information message
     * @var string
     */
    const INFO = 'INFO';
    
    /**
     * Type of warning message
     * @var string
     */
    const WARNING = 'WARNING';
    
    /**
     * Type of error message
     * @var string
     */
    const ERROR = 'ERROR';
    
    /**
     * Type of success message
     * @var string
     */
    const SUCCESS ='SUCCESS';
}

/* End of file Logger.php */
/* Location: ./system/library/Logger.php */
