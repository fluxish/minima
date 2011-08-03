<?php  if (!defined('ROOT')) exit('No direct script access allowed');

/**
 * Kaili Benchmark Class
 *
 * Class to manage benchmark of php scripts and to time method's execution.
 *
 * @package		Kaili
 * @subpackage	Library
 * @category	Library
 * @author		Luigi Marco Simonetti
 */

class Benchmark
{
    /**
     * Time of start() method
     * @var float
     */
    private $_time_start;
    
    /**
     * Array of checkpoints
     */
    private $_checkpoints;
    
    /**
     * Create new Benchmark object
     */
    function __construct()
    {
        $this->_checkpoints = array();
    }
    
    /**
     * Set the first checkpoint
     */
    function start()
    {
        $this->_time_start = $this->timestamp();
    }
    
    /**
     * Set a new checkpoint
     * @param string the checkpoint's name
     */
    function checkpoint($name)
    {
        $this->_checkpoints[$name] = $this->timestamp();
    }
    
    /**
     * Returns elapsed time between two checkpoints. Start and end checkpoints are optional,
     * if start is null, is considered the first checkpoint, if end is null, a new 
     * checkpoint is created and considered as end checkpoint.
     * 
     * @param string name of start checkpoint
     * @param string name of end checkpoint
     * @return int elapsed time, in milliseconds
     */
    function elapsed_time($start = null, $end = null)
    {
        if($start == null) $t_start = $this->_time_start;
        else $t_start = $this->_checkpoints[$start];
        
        if($end == null) $t_end = $this->timestamp();
        else $t_end = $this->_checkpoints[$end];
        
        return round($t_end - $t_start, 3);
    }
    
    /**
     * Returns elapsed time from the request time. The end checkpoint is optional,
     * if end is null, a new checkpoint is created and considered as end checkpoint.
     * 
     * @param string name of end checkpoint
     * @return int elapsed time, in microseconds
     */
    function elapsed_time_from_request($end = null)
    {
        $t_start = (float) $_SERVER['REQUEST_TIME'];
        
        if($end == null) $t_end = $this->timestamp();
        else $t_end = $this->_checkpoints[$end];

        return round($t_end - $t_start, 3);
    }
    
    /**
     * Return the current timestamp
     * 
     * @return the current timestamp, in microseconds
     */
    private function timestamp()
    {
        return microtime(true);
    }
}

/* End of file Benchmark.php */
/* Location: ./system/library/Benchmark.php */
