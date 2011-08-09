<?php	

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__FILE__));
define('SYSTEM', ROOT.DS.'system');
define('APPLICATION',ROOT.DS.'application');
define('ASSETS',ROOT.DS.'assets');
define('EXT', '.php');

require_once (ROOT.DS.'system'.DS.'library'.DS.'Loader.php');

$pre_call = function(){
    $loader = Loader::get_instance();
    $loader->library('benchmark')->start();
    $loader->library('logger')->add('log1');
};

$post_call = function(){
    $loader = Loader::get_instance();
#    $elapsed = $loader->library('benchmark')->elapsed_time_from_request();
#    $loader->library('logger')->info($elapsed);
};

Loader::get_instance()->start($pre_call, $post_call);

