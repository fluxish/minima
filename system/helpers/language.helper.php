<?php  if (!defined('ROOT')) exit('No direct script access allowed');

/**
 * Language Helpers
 *
 * @package		Kaili
 * @subpackage	Helpers
 * @category	Helpers
 * @author		Luigi Marco Simonetti
 */

if(!function_exists('lang'))
{
    function lang($item)
    {
        return Loader::get_instance()->load('language')->item($item);
    }
}
