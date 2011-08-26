<?php

namespace Kaili;

/**
 * Paginator Helpers
 *
 * @package		Kaili
 * @subpackage	Helpers
 * @category	Helpers
 */
class Paginator
{

    /**
     * Create paginator links
     * 
     * @param string $count number of paginator
     * @return string
     */
    public static function paginator($count)
    {
        $paginator = array();

        // load request library
        $request = Loader::get_instance()->load('request');

        // load config library
        $config = Loader::get_instance()->load('config');
        $config->load('paginator');
        $route = $config->item('paginator_route');
        $max_items = $config->item('paginator_max_items');
        $window = $config->item('paginator_window');

        // Compute number of pages
        $paginator['count'] = ceil($count / $max_items);
        if($paginator['count'] <= 1)
            return false;

        // assign first and last pages
        $paginator['first'] = abs(array($route => 1), false);
        $paginator['last'] = abs(array($route => $paginator['count']), false);
        $window = min($window, $paginator['count']);


        // get current page and range limits (default 1)
        if($paginator['current'] = $request->get($config->item('paginator_route'))) {
            if($paginator['current'] == 1) {
                $paginator['next'] = abs(array($route => $paginator['current'] + 1), false);
            }
            else if($paginator['current'] == $paginator['count']) {
                $paginator['previous'] = abs(array($route => $paginator['current'] - 1), false);
            }
            else {
                $paginator['next'] = abs(array($route => $paginator['current'] + 1), false);
                $paginator['previous'] = abs(array($route => $paginator['current'] - 1), false);
            }
        }
        else {
            $paginator['current'] = 1;
            $paginator['next'] = abs(array($route => $paginator['current'] + 1), false);
        }

        // create $pages array
        if($paginator['current'] < $paginator['count'] / 2) {
            $start = max(1, $paginator['current'] - $window / 2);
            $end = $start + ($window - 1);
        }
        else {
            $end = min($paginator['current'] + $window / 2, $paginator['count']);
            $start = $end - ($window - 1);
        }

        $paginator['pages'] = array();
        for($i = $start; $i <= $end; $i++) {
            $paginator['pages'][$i] = abs(array($route => $i), false);
        }

        return $paginator;
    }

    /**
     * Return an array with parameters for pagination
     * 
     * @return array
     */
    public static function paginator_params()
    {
        // load config library
        $config = Loader::get_instance()->load('config');
        $config->load('paginator');
        $route = $config->item('paginator_route');
        $max_items = $config->item('paginator_max_items');

        // load request library
        $request = Loader::get_instance()->load('request');

        $page = $request->get($route);
        if(!$page)
            $page = 1;

        return array(($page - 1) * $max_items, $max_items);
    }

}

