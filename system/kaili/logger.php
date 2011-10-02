<?php  

namespace Kaili;

/**
 * Kaili Logger Class
 *
 * Class to log messages
 *
 * @package Kaili
 */

abstract class Logger
{
    /**
     * Array of all added loggers
     * @var array
     */
    private static $_loggers = array();
    
    /**
     * Create a new loggers container
     */
    private function __construct()
    {
    }
    
    /**
     * Log a message with a logger
     * @param mixed $data
     * @param string $logger the name of the logger to use
     */
    public abstract function log($data, $type = Logger::INFO);
    
    /**
     * Log an info message with a logger
     * @param mixed $data
     * @param string $logger the name of the logger to use
     */
    public function info($data)
    {
        $this->log($data, Logger::INFO);
    }
    
    /**
     * Log a warning message with a logger
     * @param mixed $data
     * @param string $logger the name of the logger to use
     */
    public function warning($data)
    {
        $this->log($data, Logger::WARNING);
    }
    
    /**
     * Log an error message with a logger
     * @param mixed $data
     * @param string $logger the name of the logger to use
     */
    public function error($data)
    {
        $this->log($data, Logger::ERROR);
    }
    
    /**
     * Log a success message with a logger
     * @param mixed $data
     * @param string $logger the name of the logger to use
     */
    public function success($data)
    {
        $this->log($data, Logger::SUCCESS);
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
    protected function _generate_marker($type)
    {
        $timestamp = date(DATE_W3C);
        return $timestamp.' ['.$type.']: ';
    }
    
    /**
     * Add a logger to the container
     * @param string $name the name of the logger
     * @param Logger_Interface $logger the logger
     */
    public static function add($name, Logger_Interface $logger = null)
    {
        $config = Config::factory('logger');
        
        if($logger != null)
            $this->_loggers[$name] = $logger;
        else{
            // create a new logger
            $loggers = $config->item('loggers');
            $logger = 'Kaili\\Logger\\'.ucwords($loggers[$name]['type']);
            self::$_loggers[$name] = new $logger($loggers[$name]);
        }
        
    }
    
    /**
     * Returns a particular named logger
     * 
     * @return Logger
     */
    public static function get($name)
    {
        $logger = self::$_loggers[$name];
        if(isset($logger)){
            return $logger;
        }
        else throw new Logger\Exception('Logger "'.$name.'" doesn\'t exists.');
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

