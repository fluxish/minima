<?php  if (!defined('ROOT')) exit('No direct script access allowed');

/**
 * Kaili Router Class
 *
 * The class manage the routing of urls
 *
 * @package		Kaili
 * @subpackage	Libraries
 * @category	Libraries
 * @author		Luigi Marco Simonetti
 */

class Router
{
    /**
     * Array of routes in routes.php config file
     * @var array
     */
    private $_route;
    
    /**
     * Array of all paramaters in the url
     * @var array
     */
    private $_params;

    public function __construct()
    {
        $route = array();
        include_once(APPLICATION.DS.'config'.DS.'routes.php');
        $this->_route = $route;
        
        $this->_params = array('route'=>array(), 'others'=>array());
    }
    
    /**
     * Parse an URL matching a route and add all parameters to $_GET
     * @param string $uri
     * @return array of route's and others detected params
     */
    public function parse_route($uri)
    {
        // extract format output (html default)
        foreach($this->_route as $patt=>$vars)
        {
            $param_names = $this->_get_param_names($patt);
            $patt = strtr($patt, array('/'=>'\/?', '.'=>'\.?'));
            $patt = preg_replace('/'.Router::ROUTE_WILDCARD.'/', Router::ROUTE_SEGMENT, $patt);
            
            if(preg_match('/^'.$patt.'/', $uri, $matches))
            {
                // parse variables in route
                foreach($param_names as $k=>$key){
				    if(!strlen($matches[$k+1]) == 0){
				        $vars[$key] = $matches[$k+1];
				    }
				}
				
                // set controller and action with normal routing
                if(empty($vars['controller'])){
                    $vars['controller'] = $this->_route['default_controller'];
                }
                else if(!class_exists(ucwords($vars['controller']))){
                    $vars['action'] = $vars['controller'];
                    $vars['controller'] = $this->_route['default_controller'];
                }
                if(empty($vars['action'])){
                    $vars['action'] = $this->_route['default_action'];
                }
			    
			    $this->_params['route'] = $vars;
			    
				// parse other variables in the url
				$other = substr($uri, strlen($matches[0])+1);
				if(strlen($other) > 0){
				    preg_match_all('/'.Router::ROUTE_SEGMENT.'\/'.Router::ROUTE_SEGMENT.'\/?/', $other, $matches);
				    
				    for($i=0; $i<count($matches[2]); $i++)
				    {
				        if(!isset($vars[$matches[1][$i]]))
				            $vars[$matches[1][$i]] = $matches[2][$i];
				            $this->_params['others'][$matches[1][$i]] = $matches[2][$i];
				    }
				}
                				
				$_GET = array_merge($_GET, $vars);
			    //var_dump($vars);
				return $this->_params;
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

