<?php  if (!defined('ROOT')) exit('No direct script access allowed');

/**
 * Kaili Upload Class
 *
 * Class to manage uploading of files to the server.
 *
 * @package		Kaili
 * @subpackage	Library
 * @category	Library
 * @author		Luigi Marco Simonetti
 */

class Upload
{

    /**
     * @var Config
     */
    private $_config;

    function __construct()
    {
        $this->_config = Loader::get_instance()->load('config');
    }
}

