<?php

namespace Kaili;

/**
 * Kaili Router Class
 *
 * The class manage the routing of urls
 *
 * @package Kaili
 */
class Router
{

    public function __construct()
    {
        
    }

    /**
     * Parse an URL matching a route and add all parameters to $_GET
     * @param string $uri
     * @return array of route's and others detected params
     */
    public function parse($uri)
    {
        $route = array();
        include(APPLICATION.DS.'config'.DS.'routes.php');
        
        // extract format output (html default)
        foreach($route as $patt => $vars) {
            $params = array('route' => array(), 'segments' => array());
            
            $param_names = $this->_get_param_names($patt);
            $patt = strtr($patt, array('/' => '\/?', '.' => '\.?'));
            $patt = preg_replace('/'.Router::ROUTE_WILDCARD.'/', Router::ROUTE_SEGMENT, $patt);

            if(preg_match('/^'.$patt.'/', $uri, $matches)) {
                // parse variables in route
                foreach($param_names as $k => $key) {
                    if(!strlen($matches[$k + 1]) == 0) {
                        $vars[$key] = $matches[$k + 1];
                    }
                }

                // set controller and action with normal routing
                if(empty($vars['controller'])) {
                    $vars['controller'] = $route['default_controller'];
                }
//                else if(!class_exists(ucwords($vars['controller']))) {
//                    $vars['action'] = $vars['controller'];
//                    $vars['controller'] = $route['default_controller'];
//                }
                if(empty($vars['action'])) {
                    $vars['action'] = $route['default_action'];
                }

                $params['route'] = $vars;

                // parse other variables in the url
                $other = substr($uri, strlen($matches[0]) + 1);
                if(strlen($other) > 0) {
                    preg_match_all('/'.Router::ROUTE_SEGMENT.'\/'.Router::ROUTE_SEGMENT.'\/?/', $other, $matches);

                    for($i = 0; $i < count($matches[2]); $i++) {
                        if(!isset($vars[$matches[1][$i]]))
                            $vars[$matches[1][$i]] = $matches[2][$i];
                        $params['segments'][$matches[1][$i]] = $matches[2][$i];
                    }
                }

                $_GET = array_merge($_GET, $vars);
                var_dump($vars);
                return $params;
            }
        }
    }

    private function _get_param_names($str)
    {
        $matches = array();
        preg_match_all('/'.Router::ROUTE_WILDCARD.'/', $str, $matches);
        return $matches[1];
    }

    /**
     * ROUTE of a parameter
     * @var string
     */
    const ROUTE_SEGMENT = '(\w*)';

    /**
     * ROUTE of a parameter
     * @var string
     */
    const ROUTE_PARAMS = '(\w*\/)';

    /**
     * ROUTE of a parameter name
     * @var string
     */
    const ROUTE_WILDCARD = '\:(\w+)';
}

