<?php  if (!defined('ROOT')) exit('No direct script access allowed');

/**
 * Minima Formatter Class
 *
 * Class to format output
 *
 * @package     Minima
 * @subpackage  Library
 * @category    Library
 * @author      Luigi Marco Simonetti
 */

class Formatter
{
    /**
     * @var string
     */
    private $_formatter;
    
    /**
     * Create a new data formatter
     * @param string $format the format output of the formatter
     */
    public function __construct($format)
    {
        $formatter = 'Formatter_'.ucwords($format);
        
        $this->_formatter = new $formatter();
    }
    
    /**
     * Format data output from an input object, using a specific formatter  
     * @param mixed $data
     */
    public function format($data)
    {
        return $this->_formatter->format($data);        
    }
    
    public function __destruct()
    {
        unset($this->_formatter);
    }
}

