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
    protected $_route;
    private $_params;
    private $_var_patt = '([^\\/]*)';
    private $_var_name_patt = '\\:([^\\/\\\\:]+)';
    private $_output_format_patt = '[\w\W]*\.([a-z0-9]*)';

    function __construct()
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
    function parse_route($uri)
    {
        // extract format output (html default)
        $format = $this->output_format($uri);
        foreach($this->_route as $patt=>$vars)
        {
            $var_names = $this->var_names($patt);
            $patt = str_replace('/', '\/?', $patt);
            $patt = preg_replace('/'.$this->_var_name_patt.'/',$this->_var_patt, $patt);
            
            if(preg_match('/^'.$patt.'/', $uri, $matches))
            {
                // parse variables in route
                foreach($var_names as $k=>$key){
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
				    preg_match_all('/'.$this->_var_patt.'\/'.$this->_var_patt.'\/?/', $other, $matches);
				    
				    for($i=0; $i<count($matches[2]); $i++)
				    {
				        if(!isset($vars[$matches[1][$i]]))
				            $vars[$matches[1][$i]] = $matches[2][$i];
				            $this->_params['others'][$matches[1][$i]] = $matches[2][$i];
				    }
				}

                // add format
                $vars['format'] = $format;
                				
				$_GET = array_merge($_GET, $vars);
			    //var_dump($vars);
				return $this->_params;
			}
        }
    }
    
    public function var_names($str)
    {
        $matches = array();
        preg_match_all('/'.$this->_var_name_patt.'/', $str, $matches);
        return $matches[1];
    }
    
    public function output_format(&$uri)
    {
        $matches = array();
        if(preg_match('/'.$this->_output_format_patt.'/', $uri, $matches) != 0){
            $uri = substr($uri, 0, -(strlen($matches[1])+1));
            return $matches[1];
        }
        else
            return 'html';
    }
}

/* End of file Router.php */
/* Location: ./system/library/Router.php */
