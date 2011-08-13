<?php  if ( ! defined('ROOT')) exit('No direct script access allowed');
/**
 * -------------------------------------------------------------------------
 * URI ROUTING
 * -------------------------------------------------------------------------
 * Remap uri to redirect requests to a particular controller.
 *
 */

/**
 * default_controller
 * Set the controller class, if it is not explicit
 */
$route['default_controller'] = 'core';

/**
 * default_action
 * Set the action method, if it is not explicit
 */
$route['default_action'] = 'index';


/**
 * ROUTES
 *
 * Special keys:
 * :controller - controller class
 * :action - action method
 * :params - list of all params (/key/value)
 * :format - output format (default: html)
 */

//$route[':controller/:action'] = array();
$route[':controller/:action.:format'] = array();

/* End of file routes.php */
/* Location: ./application/config/routes.php */
