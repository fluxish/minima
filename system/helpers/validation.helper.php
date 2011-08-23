<?php  if (!defined('ROOT')) exit('No direct script access allowed');

/**
 * Validation Helpers
 *
 * @package		Kaili
 * @subpackage	Helpers
 * @category	Helpers
 * @author		Luigi Marco Simonetti
 */

/**
 * Show a list of validation errors (Generated by Validation library)
 * @return string
 */
if(!function_exists('validation_errors'))
{
    function validation_errors($start, $end)
    {
        $errs = array();
        $errors = Loader::get_instance()->load('session')
            ->weak_data('validation_errors');
        if(!$errors) return '';
            
        foreach($errors as $field_name=>$field_errors){
            foreach($field_errors as $err){
                $errs[] = $err;
            }
        }
        return $start."\n".ul($errs).$end."\n";
    }
}

/**
 * Show a list of errors for a field, individually
 * @param string $name field name
 * @return string
 */
if(!function_exists('validation_error'))
{
    function validation_error($name, $start, $end)
    {
        $errors = Loader::get_instance()->load('session')
            ->weak_data('validation_errors');
        return isset($errors[$name]) ? $start."\n".ul($errors[$name]).$end."\n" : '';
    }
}


/* End of file validation.helper.php */
/* Location: ./system/helpers/validation.helper.php */
