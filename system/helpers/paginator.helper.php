<?php  if (!defined('ROOT')) exit('No direct script access allowed');

/**
 * Paginator Helpers
 *
 * @package		Kaili
 * @subpackage	Helpers
 * @category	Helpers
 */

if(!function_exists('paginator'))
{
    /**
     * Create paginator links
     * @param string $count number of paginator
     * @return string
     */
    function paginator($count)
    {
        $paginator = array();
        
        // load input library
        $input = Loader::get_instance()->load('input');
    
        // load config library
        $config = Loader::get_instance()->load('config');
        $config->load('paginator');
        $route = $config->item('paginator_route');
        $max_items = $config->item('paginator_max_items');
        $window = $config->item('paginator_window');
        
        // Compute number of pages
        $paginator['count'] = ceil($count/$max_items);
        if($paginator['count'] <= 1) return false;
        
        // assign first and last pages
        $paginator['first'] = url(array($route=>1), false);
        $paginator['last'] = url(array($route=>$paginator['count']), false);
        $window = min($window, $paginator['count']);
        
        
        // get current page and range limits (default 1)
        if($paginator['current'] = $input->get($config->item('paginator_route')))
        {
            if($paginator['current'] == 1) {
                $paginator['next'] = url(array($route=>$paginator['current']+1), false);
            } 
            else if($paginator['current'] == $paginator['count']){
                $paginator['previous'] = url(array($route=>$paginator['current']-1), false);
            } 
            else {
                $paginator['next'] = url(array($route=>$paginator['current']+1), false);
                $paginator['previous'] = url(array($route=>$paginator['current']-1), false);
            }
        } else {
            $paginator['current'] = 1;
            $paginator['next'] = url(array($route=>$paginator['current']+1), false);
        }
        
        // create $pages array
        if($paginator['current'] < $paginator['count']/2){
            $start = max(1, $paginator['current']-$window/2);
            $end = $start + ($window-1);
        }
        else{
            $end = min($paginator['current']+$window/2, $paginator['count']);
            $start = $end - ($window-1);        
        }
        
        $paginator['pages'] = array();
        for($i=$start; $i<=$end; $i++){
            $paginator['pages'][$i] = url(array($route=>$i), false);
        }
        
        return $paginator;
    }
}


if(!function_exists('paginator_params'))
{
    /**
     * Return an array with parameters for pagination
     * @return array
     */
    function paginator_params()
    {
        // load config library
        $config = Loader::get_instance()->load('config');
        $config->load('paginator');
        $route = $config->item('paginator_route');
        $max_items = $config->item('paginator_max_items');
        
        // load input library
        $input = Loader::get_instance()->load('input');
        
        $page = $input->get($route);
        if(!$page) $page = 1;
        
        return array(($page-1)*$max_items, $max_items);
    }
}


/* End of file paginator.helper.php */
/* Location: ./system/helpers/paginator.helper.php */
