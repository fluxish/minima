<?php 

namespace Minima\Formatter;

/**
 * Minima Formatter Class
 *
 * This class generate an output in json format
 *
 * @package		Minima
 * @subpackage	Formatter
 */
 
class Json
{
    public function format($data)
    {
        return json_encode($data);
    }
}

