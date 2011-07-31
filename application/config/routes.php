<?php  if ( ! defined('ROOT')) exit('No direct script access allowed');
/**
 * -------------------------------------------------------------------------
 * URI ROUTING
 * -------------------------------------------------------------------------
 * Remap uri to redirect requests to a particular controller.
 *
 */

$route['default_controller'] = 'core';
$route['default_action'] = 'index';

$route['home'] = array('controller' => 'hello');

$route[':module/:controller/:action/:id'] = array();


/* End of file routes.php */
/* Location: ./application/config/routes.php */
