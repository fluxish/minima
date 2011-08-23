<?php

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__FILE__));
define('SYSTEM', ROOT.DS.'system');
define('APPLICATION', ROOT.DS.'application');
define('ASSETS', ROOT.DS.'assets');
define('EXT', '.php');


require_once(SYSTEM.DS.'kaili'.DS.'loader'.EXT);

use Kaili\Loader;
use Kaili\Logger;



$pre_call = function() {
            $loader = Loader::get_instance();
            $loader->load('benchmark')->start();
            Logger::add('log1');
        };

$post_call = function() {
            $loader = Loader::get_instance();
//            $elapsed = $loader->library('benchmark')->elapsed_time_from_request();
//            Logger::get('log1')->info($elapsed);
        };

Loader::get_instance()->start($pre_call, $post_call);
