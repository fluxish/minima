<?php	

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__FILE__));
define('SYSTEM', ROOT.DS.'system');
define('APPLICATION',ROOT.DS.'application');
define('ASSETS',ROOT.DS.'assets');
define('EXT', '.php');

require_once (ROOT.DS.'system'.DS.'library'.DS.'Loader.php');
Loader::get_instance()->start();

