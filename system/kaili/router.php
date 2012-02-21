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
    /**
     * Create a new Router object
     * @return Router
     */
    public static function factory()
    {
        return new static();
    }
    
    
    /**
     * Routes 
     * @var array
     */
    private $_routes;
    
    public function __construct()
    {
        
        $routes = array();
        include(CONFIG.DS.'routes.php');
        
        $this->_routes = array();
        $this->_routes = array_merge($this->_routes, $routes);
    }

    /**
     * Parse an URL matching a route and add all parameters to $_GET
     * @param string $uri
     * @return array of route's and others detected params
     */
    public function parse($uri)
    {
        // extract format output (html default)
        foreach($this->_routes as $patt => $vars) {
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
                    $vars['controller'] = $this->_routes['default_controller'];
                }
                if(empty($vars['action'])) {
                    $vars['action'] = $this->_routes['default_action'];
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

