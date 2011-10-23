<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * @author luigi
 */
// TODO: check include path
ini_set('include_path', ini_get('include_path').PATH_SEPARATOR.dirname(__FILE__).'/../../../../../../usr/share/php/PHPUnit');

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__FILE__).'/../..');
define('SYSTEM', ROOT.DS.'system');
define('APPLICATION', ROOT.DS.'application');
define('ASSETS', APPLICATION.DS.'assets');
define('CONFIG', APPLICATION.DS.'config');
define('EXT', '.php');

?>
