<?php

/** Check if environment is development and display errors **/

function setReporting() {
    if(Loader::get_instance()->library('config')->item('development_environment') == true)
    {
        error_reporting(E_ALL);
        ini_set('display_errors','On');
        ini_set('html_errors', 'On');
    } 
    else{
        error_reporting(E_ALL & ~E_DEPRECATED);
        ini_set('display_errors','Off');
        ini_set('log_errors', 'On');
        ini_set('error_log', ROOT.DS.'system'.DS.'tmp'.DS.'logs'.DS.'error.log');
    }
}

/** Main Call Function **/

function callHook() 
{
	$controller = $_GET['controller'];
	$action = $_GET['action'];
	
#	//ACL
#	$acl = Loader::get_instance()->library('acl');
#	$acl->add_role('admin');
#	$acl->add_role('guest');
#	$acl->allow('guest','home');
#	$acl->allow('admin');
#	
#	$session = Loader::get_instance()->library('session');
#	
#	// manage controller
#	if($session->data('identity') === false) $role = 'guest';
#	else {
#        $user = $session->data('identity');	
#        $role = $user['role'];
#	}
#	
#	if($acl->is_allowed($role, $controller, $action))
	Loader::get_instance()->controller($controller, $action, $_GET);
	
	// output all buffer
	Loader::get_instance()->library('output')->display();
}

/** Autoload any classes that are required **/

function __autoload($className) 
{
    // create the path from the name of the class
    $path = str_replace('_', DS, $className);
    
	if(file_exists(SYSTEM.DS.'library'.DS.$path.EXT)) {
		require_once(SYSTEM.DS.'library'.DS.$path.EXT);
	}
    else if(file_exists(APPLICATION.DS.'controllers'.DS.$className.EXT)) {
		require_once(APPLICATION.DS.'controllers'.DS.$className.EXT);
	} 
	else if(file_exists(APPLICATION.DS.'models'.DS.$className.EXT)) {
		require_once(APPLICATION.DS.'models'.DS.$className.EXT);
	}
	else if(file_exists(SYSTEM.DS.'plugins'.DS.$className.EXT)) {
		require_once(SYSTEM.DS.'plugins'.DS.$className.EXT);
	} 
}

spl_autoload_register('__autoload');
setReporting();

Loader::get_instance()->autoload();

Loader::get_instance()->library('benchmark')->start();

callHook();

//var_dump(Loader::get_instance()->library('benchmark')->elapsed_time_from_request());


/* End of file bootstrap.php */
/* Location: ./system/bootstrap/bootstrap.php */

