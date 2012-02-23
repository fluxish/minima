<?php

namespace Minima;

/**
 * Minima Model Class
 *
 *
 * @package		Minima
 * @subpackage	Library
 * @category	Library
 * @author		Luigi Marco Simonetti
 */
 
class Model
{
    protected $db;
    
    public function __construct()
    {
        $this->db = Loader::get_instance()->load('db');
    }
    
    public function get_table()
    {
        $this->_table;
    }
}
