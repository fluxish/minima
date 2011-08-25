<?php

namespace Kaili;

/**
 * Kaili Validation Class
 *
 * Class to manage data validation. 
 *
 * @package		Kaili
 * @subpackage	Library
 * @category	Library
 * @author		Luigi Marco Simonetti
 */
class Validation
{

    /**
     * List of all fields to validate
     * @var array
     */
    private $_fields;

    /**
     * @var Config
     */
    private $_config;

    public function __construct()
    {
        // Load validation.lang
        Loader::get_instance()->load('language')->load('validation');
        Loader::get_instance()->helper('validation');

        $this->_config = Loader::get_instance()->load('config');
        $this->_fields = array();
    }

    /**
     * Add a validation rule to validation process
     * @param string $field field name
     * @param string $label a field label
     * @param string $rules cascading validation rules with structure: 
     *      rule1,rule2:param,...,ruleN (example: required,max_length:15,valid_ip) 
     */
    public function add_rules($field, $label = '', $rules = '')
    {
        // if there isn't label, we use the field name
        if(empty($label))
            $label = $field;

        $matches = array();
        preg_match_all('/([\w]+)\:?([0-9]+)?,?/', $rules, $matches);
        $rules = array_combine($matches[1], $matches[2]);

        //$rules = explode(',',$rules);
        //var_dump($rules);

        $this->_fields[$field] = array(
            'name' => $field,
            'label' => $label,
            'rules' => $rules,
            'errors' => array()
        );
    }

    /**
     * Start validation process
     * @return boolean result of validation process
     */
    public function validate()
    {
        $input = Loader::get_instance()->load('input');
        $valid = true;

        foreach($this->_fields as $field_name => &$field) {
            $value = $input->post($field['name']);
            foreach($field['rules'] as $rule => $param) {
                if(!call_user_func(array($this, $rule), $value, $param)) {
                    $field['errors'][] = $rule;
                    $valid = false;
                }
            }
        }
        $this->_generate_errors();
        return $valid;
    }

    /**
     * Generate errors and save them in a weak_data
     */
    private function _generate_errors()
    {
        $errors = array();
        $language = Loader::get_instance()->load('language');
        $session = Loader::get_instance()->load('session');

        foreach($this->_fields as $field_name => $field) {
            foreach($field['errors'] as $err) {
                if(empty($field['rules'][$err])) {
                    $errors[$field['name']][] = sprintf($language->item('validation_'.$err), $field['label']);
                }
                else {
                    $errors[$field['name']][] = sprintf($language->item('validation_'.$err), $field['label'], $field['rules'][$err]);
                }
            }
        }
        $session->weak_data('validation_errors', $errors, true);
    }

    /**
     * Reset validation process to start conditions and clear all validation 
     * rules added previously.
     */
    public function reset_validation()
    {
        $this->_fields = array();
    }

    /**
     * Check presence of data
     * @param $value
     * @return boolean
     */
    public function required($value)
    {
        if(is_string($value)) {
            if(trim($value) == '')
                return false;
            else
                return true;
        }
        else {

            return!empty($value);
        }
    }

    /**
     * Check minimum length
     * @param string $value
     * @param integer $lim
     * @return boolean
     */
    public function min_length($value, $lim)
    {
        $value = (string) $value;
        if(strlen($value) < $lim)
            return false;
        return true;
    }

    /**
     * Check maximum length
     * @param string $value
     * @param integer $lim
     * @return boolean
     */
    public function max_length($value, $lim)
    {
        $value = (string) $value;
        if(strlen($value) > $lim)
            return false;
        return true;
    }

    /**
     * Check exact length
     * @param string $value
     * @param integer $lim
     * @return boolean
     */
    public function exact_length($value, $lim)
    {
        $value = (string) $value;
        if(strlen($value) == $lim)
            return true;
        return false;
    }

    /**
     * Check minimum value
     * @param number $value
     * @param number $lim
     * @return boolean
     */
    public function min_value($value, $lim)
    {
        if((float) $value < (float) $lim)
            return false;
        return true;
    }

    /**
     * Check maximum value
     * @param number $value
     * @param number $lim
     * @return boolean
     */
    public function max_value($value, $lim)
    {
        if((float) $value > (float) $lim)
            return false;
        return true;
    }

    /**
     * Check exact value
     * @param number $value
     * @param number $lim
     * @return boolean
     */
    public function exact_value($value, $lim)
    {
        if((float) $value == (float) $lim)
            return true;
        return false;
    }

    /**
     * Check if a string have all alphabetic characters.
     * @param string $value
     * @return boolean
     */
    public function alpha($str)
    {
        if(preg_match('/^([A-Za-z\ ])+$/', $str) == 0)
            return false;
        return true;
    }

    /**
     * Check if a string have all alphanumeric characters.
     * @param string $value
     * @return boolean
     */
    public function alpha_numeric($str)
    {
        if(preg_match('/^([A-Za-z0-9\ ])+$/', $str) == 0)
            return false;
        return true;
    }

    /**
     * Check if a string have all alphanumeric characters. Also undescores, 
     * dashes and dots are accepted.
     * @param string $value
     * @return boolean
     */
    public function alpha_dash($str)
    {
        if(preg_match('/^([A-Za-z0-9_.\-\ ])+$/', $str) == 0)
            return false;
        return true;
    }

    /**
     * Check if string is numeric (or with positive/negative sign, floating point)
     * @param string $str
     * @return boolean
     */
    public function numeric($str)
    {
        if(preg_match('/^([+\-]?[0-9]+\.?[0-9]*)$/', $str) == 0)
            return false;
        return true;
    }

    /**
     * Check if number is integer ([+|-]0,1,2...)
     * @param string $str
     * @return boolean
     */
    public function integer($str)
    {
        if(preg_match('/^([+\-]?[0-9]+)$/', $str) == 0)
            return false;
        return true;
    }

    /**
     * Check if number is natural (0,1,2...)
     * @param string $str
     * @return boolean
     */
    public function natural($str)
    {
        if(preg_match('/^([0-9]+)$/', $str) == 0)
            return false;
        return true;
    }

    /**
     * Check if number is natural but non zero (1,2...)
     * @param string $str
     * @return boolean
     */
    public function natural_no_zero($str)
    {
        if($str == "0")
            return false;
        else if(preg_match('/^([0-9]+)$/', $str) == 0)
            return false;
        return true;
    }

    /**
     * Check if a string match with value of another parameter (or field).
     * @param string $str
     * @param string $field field name
     * @return boolean
     */
    public function matches($str, $field)
    {
        $field = Loader::get_instance()->load('input')->post($field);
        if($field && $str === $field)
            return true;
        return false;
    }

    /**
     * Check if string is a valid email
     * @param string $str
     * @return boolean
     */
    public function valid_email($str)
    {
        //TODO: valid_email
        if(preg_match('/^([0-9])+$/', $str) == 0)
            return false;
        return true;
    }

    /**
     * Check if string is a valid url
     * @param string $str
     * @return boolean
     */
    public function valid_url($str)
    {
        //TODO: valid_url
        if(preg_match('/^([0-9])+$/', $str) == 0)
            return false;
        return true;
    }

    /**
     * Check if string is a valid IP address
     * @param string $str
     * @return boolean
     */
    public function valid_ip($str)
    {
        //TODO: valid_ip
        if(preg_match('/^([0-9])+$/', $str) == 0)
            return false;
        return true;
    }

    
    /**
     * Helper to show a list of validation errors (Generated by Validation library)
     * @return string
     */
    public static function validation_errors($start, $end)
    {
        $errs = array();
        $errors = Loader::get_instance()->load('session')
                ->weak_data('validation_errors');
        if(!$errors)
            return '';

        foreach($errors as $field_name => $field_errors) {
            foreach($field_errors as $err) {
                $errs[] = $err;
            }
        }
        return $start."\n".ul($errs).$end."\n";
    }

    /**
     * Helper to show a list of errors for a field, individually
     * @param string $name field name
     * @return string
     */
    public static function validation_error($name, $start, $end)
    {
        $errors = Loader::get_instance()->load('session')
                ->weak_data('validation_errors');
        return isset($errors[$name]) ? $start."\n".ul($errors[$name]).$end."\n" : '';
    }

}

