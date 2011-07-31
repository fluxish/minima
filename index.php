<?php	

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__FILE__));
define('SYSTEM', ROOT.DS.'system');
define('APPLICATION',ROOT.DS.'application');
define('ASSETS',ROOT.DS.'assets');
define('EXT', '.php');

$url = $_GET['url'];

require_once (ROOT.DS.'system'.DS.'bootstrap'.DS.'bootstrap.php');



