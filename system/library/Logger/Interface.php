<?php  if (!defined('ROOT')) exit('No direct script access allowed');

/**
 * Kaili Logger Interface
 *
 * Interface for logger objects
 *
 * @package     Kaili
 * @subpackage  Logger
 * @category    Library
 * @author      Luigi Marco Simonetti
 */

interface Logger_Interface
{
    /**
     * Save data with the logger
     * @param mixed $data
     */
    public function log($data);
}

/* End of file Interface.php */
/* Location: ./system/library/Logger/Interface.php */
