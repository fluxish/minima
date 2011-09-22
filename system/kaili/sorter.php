<?php

namespace Kaili;

/**
 * Sorter class
 *
 * @package Kaili
 */
class Sorter
{

    /**
     * Create sorter links
     * @param array $fields array of column fields
     * @return array
     */
    public static function sorter($fields)
    {
        $sorter = array();

        // load config library
        $config = Loader::get_instance()->load('config');
        $config->load('sorter');
        $asc = $config->item('sorter_asc_route');
        $desc = $config->item('sorter_desc_route');

        // load request library
        $request = Request::current();

        // create links for all fields
        $sorter['fields'] = array();
        foreach($fields as $field => $title) {
            $sorter['fields'][$field] = abs(array($asc => $field, $desc => ''), false);
        }

        // set current field
        if($curr_field = $request->get($asc)) {
            $sorter['fields'][$curr_field] = abs(array($desc => $curr_field, $asc => ''), false);
            $sorter['current_field'] = $curr_field;
            $sorter['current_order'] = $asc;
        }
        else if($curr_field = $request->get($desc)) {
            $sorter['fields'][$curr_field] = abs(array($asc => $curr_field, $desc => ''), false);
            $sorter['current_field'] = $curr_field;
            $sorter['current_order'] = $desc;
        }


        return $sorter;
    }

    /**
     * Return an array with parameters for sort
     * @return array
     */
    public static function sorter_params()
    {
        // load config library
        $config = Loader::get_instance()->load('config');
        $config->load('sorter');
        $asc = $config->item('sorter_asc_route');
        $desc = $config->item('sorter_desc_route');

        // load request library
        $request = Request::current();

        if($curr_field = $request->get($asc)) {
            $curr_order = $asc;
        }
        else if($curr_field = $request->get($desc)) {
            $curr_order = $desc;
        } else
            return array();

        return array($curr_field, $curr_order);
    }

}

