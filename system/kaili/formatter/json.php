<?php 

namespace Kaili\Formatter;

/**
 * Kaili Formatter Class
 *
 * This class generate an output in json format
 *
 * @package		Kaili
 * @subpackage	Formatter
 */
 
class Json
{
    public function format($data)
    {
        return json_encode($data);
    }
}

