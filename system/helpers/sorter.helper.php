<?php  if (!defined('ROOT')) exit('No direct script access allowed');

/**
 * Sorter Helpers
 *
 * @package		Kaili
 * @subpackage	Helpers
 * @category	Helpers
 */

if(!function_exists('sorter'))
{
    /**
     * Create sorter links
     * @param array $fields array of column fields
     * @return array
     */
    function sorter($fields)
    {
        $sorter = array();
        
        // load config library
        $config = Loader::get_instance()->load('config');
        $config->load('sorter');
        $asc = $config->item('sorter_asc_route');
        $desc = $config->item('sorter_desc_route');
        
        // load input library
        $input = Loader::get_instance()->load('input');
        
        // create links for all fields
        $sorter['fields'] = array();
        foreach($fields as $field=>$title){
            $sorter['fields'][$field] = url(array($asc=>$field, $desc=>''), false);
        }
        
        // set current field
        if($curr_field = $input->get($asc)){
            $sorter['fields'][$curr_field] = url(array($desc=>$curr_field, $asc=>''), false);
            $sorter['current_field'] = $curr_field;
            $sorter['current_order'] = $asc;
        }
        else if($curr_field = $input->get($desc)){
            $sorter['fields'][$curr_field] = url(array($asc=>$curr_field, $desc=>''), false);
            $sorter['current_field'] = $curr_field;
            $sorter['current_order'] = $desc;
        }
        
        
        return $sorter;
    }
}


if(!function_exists('sorter_params'))
{
    /**
     * Return an array with parameters for sort
     * @return array
     */
    function sorter_params()
    {
        // load config library
        $config = Loader::get_instance()->load('config');
        $config->load('sorter');
        $asc = $config->item('sorter_asc_route');
        $desc = $config->item('sorter_desc_route');
        
        // load input library
        $input = Loader::get_instance()->load('input');
        
        if($curr_field = $input->get($asc)){
            $curr_order = $asc;
        }
        else if($curr_field = $input->get($desc)){
            $curr_order = $desc;
        } else return array();
        
        return array($curr_field, $curr_order);
    }
}


/* End of file sorter.helper.php */
/* Location: ./system/helpers/sorter.helper.php */
