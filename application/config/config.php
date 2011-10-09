<?php

/**
 *  base_url
 *  URL to the framework root
 *  @var string
 */
$config['base_url'] = 'http://'.$_SERVER['HTTP_HOST'].'/kaili';

/**
 *  language
 *  The default language
 *  @var string
 */
$config['language'] = 'it';


/**
 * ASSETS DIRECTORIES
 */

/**
 *  images_directory
 *  Path to the directory of the images
 *  @var string
 */
$config['images_directory'] = ASSETS.DS.'images';

/**
 *  thumbs_directory
 *  Path to the directory of the thumbnails of the images
 *  @var string
 */
$config['thumbs_directory'] = ASSETS.DS.'images'.DS.'thumbs';

/**
 *  files_directory
 *  Path to the directory of the uploaded files
 *  @var string
 */
$config['files_directory'] = ASSETS.DS.'files';


/**
 * INTERFACE
 */

/**
 *  interface_theme
 *  Name of the default theme
 *  @var string
 */
$config['interface_theme'] = 'admin';

/**
 *  main_template
 *  Name of the main template (default main.php, so it is 'main')
 *  @var string
 */
$config['main_template'] = 'main';

/**
 *  javascript_url
 *  URL to the javascript assets directory 
 *  @var string
 */
$config['javascript_url'] = $config['base_url'].'/application/assets/js/';

/**
 *  images_url
 *  URL to the images assets directory 
 *  @var string
 */
$config['images_url'] = $config['base_url'].'/application/assets/images/';

/**
 *  theme_url
 *  URL to the theme assets directory 
 *  @var string
 */
$config['theme_url'] = $config['base_url'].'/application/assets/themes/'.$config['interface_theme'].'/';

/**
 *  ENVIRONMENT
 */
 
/**
 *  development_environment
 *  Set true if the environment is in a development state (default: false)
 *  @var boolean
 */
$config['development_environment'] = true;

/**
 *  environment infos
 */
$config['environment_name'] = 'Kaili Framework';
$config['environment_version'] = '0.2';

/* End of file config.php */
/* Location: ./application/config/config.php */
