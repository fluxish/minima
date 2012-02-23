<?php

namespace Minima;

/**
 * HTML class
 *
 * @package Minima
 */
class Form
{

    /**
     * Return a form open tag
     * @param string the action to execute on form submit
     * @param array an array of attributes and their values
     * @return string
     */
    public static function open($action, $attributes = array(), $multipart = false)
    {


        // add action
        $attributes['action'] = $action;

        // set method to 'post' if not is setted
        if(!isset($attributes['method'])) {
            $attributes['method'] = 'post';
        }

        // add enctype, if multipart or if not setted between attributes
        if($multipart) {
            $attributes['enctype'] = 'multipart/form-data';
        }

        return Html::tag_open('form', $attributes);
    }

    /**
     * Return a form close tag
     * @return string
     */
    public static function close()
    {
        return '</form>';
    }

    /**
     * Return an input field
     * @param string $type
     * @param string $name
     * @param string $value
     * @param string $label a label for the input field
     * @param array $attributes an array of all other attributes of the input tag
     * @return string
     */
    public static function input($type, $name, $value = "", $label = "", $attributes = array())
    {
        // return a custom input text
        $attributes['name'] = $name;
        $attributes['type'] = $type;
        $attributes['value'] = $value;

        // if not setted, set id with same value of name
        if(!isset($attributes['id']) && isset($attributes['name'])) {
            $attributes['id'] = $attributes['name'];
        }


        // add label
        if(!empty($label)) {
            $label = form_label($label, $attributes['name'])."<br/>\n";
        }
        else if(method_exists('Language', 'lang')) {
            $label = lang('form_'.$name);
            //if(!$label) $label = $name; 
            //form_label($label, $attributes['name']).$sep."\n"; 
        }

        return $label.Html::tag_open('input', $attributes, true);
    }

    /**
     * Return an input text field
     * @param string $name
     * @param string $value
     * @param string $label a label for the input field
     * @param array $attributes an array of all other attributes of the input tag
     * @return string
     */
    public static function text($name, $value, $label, $attributes = array())
    {
        return static::input('text', $name, $value, $label, $attributes);
    }

    /**
     * Return an input hidden field
     * @param mixed name of input hidden field (given also as id), or an array of all attributes
     * @param string the value
     * @return string
     */
    public static function hidden($name, $value)
    {
        return static::input('hidden', $name, $value);
    }

    /**
     * Return an input password field
     * @param string $name
     * @param string $value
     * @param string $label a label for the input field
     * @param array $attributes an array of all other attributes of the input tag
     * @return string
     */
    public static function password($name, $value, $label, $attributes = array())
    {
        return static::input('password', $name, $value, $label, $attributes);
    }

    /**
     * Return a textarea field
     * @param mixed name of the textarea field (given also as id), or an array of all attributes
     * @param string $value an initial value
     * @param string $label a label for the input field
     * @param array $attributes an array of all other attributes of the input tag
     * @return string
     */
    public static function textarea($name, $value = '', $label = '', $attributes = array())
    {

        // return a custom textarea
        $attributes['name'] = $name;

        // if not setted, set id with same value of name
        if(!isset($attributes['id']) && isset($attributes['name'])) {
            $attributes['id'] = $attributes['name'];
        }

        // add label
        if(!empty($label)) {
            $label = form_label($label, $attributes['name'])."<br/>\n";
        }
        else if(method_exists('Language', 'lang')) {
            $label = lang('form_'.$name);
            //if(!$label) $label = $name; 
            //form_label($label, $attributes['name'])."<br/>\n"; 
        }

        return $label.Html::tag_open('textarea', $attributes).$value.tag_close('textarea');
    }

    /**
     * Return an input checkbox field
     * @param string $name
     * @param string $value
     * @param boolean $checked true to set checkbox to true, false otherwise (default)
     * @param string $label a label for the input field
     * @param array $attributes an array of all other attributes of the input tag
     * @return string
     */
    public static function checkbox($name, $value, $label, $checked = false, $attributes = array())
    {
        if($checked) {
            $attributes['checked'] = 'checked';
        }
        else {
            unset($attributes['checked']);
        }

        return static::input('checkbox', $name, $value, $label, $attributes);
    }

    /**
     * Return an input radio field
     * @param string $name
     * @param string $value
     * @param boolean true to set radio to true, false otherwise
     * @param string $label a label for the input field
     * @param array $attributes an array of all other attributes of the input tag
     * @return string
     */
    public static function radio($name, $value, $label, $checked = false, $attributes = array())
    {
        if($checked) {
            $attributes['checked'] = 'checked';
        }
        else {
            unset($attributes['checked']);
        }

        return static::input('radio', $name, $value, $label, $attributes);
    }

    /**
     * Return an input file field
     * @param string $name
     * @param string $value
     * @param string $label a label for the input field
     * @param array $attributes an array of all other attributes of the input tag
     * @return string
     */
    public static function file($name, $value, $label, $attributes = array())
    {
        return static::input('file', $name, $value, $label, $attributes);
    }

    /**
     * Return a submit button
     * @param string $name
     * @param string $value
     * @param array $attributes an array of all other attributes of the input tag
     * @return string
     */
    public static function submit($name, $value, $attributes = array())
    {
        return static::input_button('submit', $name, $value, '', $attributes);
    }

    /**
     * Return a reset button
     * @param string $name
     * @param string $value
     * @param array $attributes an array of all other attributes of the input tag
     * @return string
     */
    public static function reset($name, $value, $attributes = array())
    {
        return static::input_button('reset', $name, $value, '', $attributes);
    }

    /**
     * Return an input button
     * @param string $type
     * @param string $name
     * @param string $value
     * @param array $attributes an array of all other attributes of the input tag
     * @return string
     */
    public static function input_button($type, $name, $value = '', $attributes = array())
    {
        if(empty($value) && method_exists('Language', 'lang')) {
            $value = lang('form_'.$name);
            if(!$value)
                $value = $name;
        }

        $attributes['id'] = $name;
        $attributes['name'] = $name;
        $attributes['type'] = $type;
        $attributes['value'] = $value;

        return Html::tag_open('input', $attributes, true);
    }

    /**
     * Return a graphical submit button
     * @param string $name
     * @param string $value
     * @param string path of image used as submit button background
     * @param array $attributes an array of all other attributes of the input tag
     * @return string
     */
    public static function image($name, $value, $src = "", $attributes = array())
    {
        if(!empty($src)) {
            $attributes['src'] = $src;
        }

        return static::input('image', $name, $value, '', $attributes);
    }

    /**
     * Return a generic form button
     * @param string type
     * @param string name
     * @param string the content
     * @param array $attributes an array of all other attributes of the input tag
     * @return string
     */
    public static function button($type, $name, $content = '', $attributes = array())
    {
        // if $name is a string, return simple input text with id, name and value
        $attributes['id'] = $name;
        $attributes['name'] = $name;
        $attributes['type'] = $type;

        return Html::tag_open('button', $attributes).$content.'</button>';
    }

    /**
     * Return a label for a field
     * @param string $label text of the label
     * @param string $for name of the field to witch is associated
     * @param array $attributes an array of all other attributes of the input tag
     * @return string
     */
    public static function label($label, $for = '', $attributes = array())
    {
        if(!empty($for)) {
            $attributes['for'] = $for;
        }
        return Html::tag_open('label', $attributes).$label.'</label>';
    }

    /**
     * Return a select field
     * @param string $name of the select (given also as id)
     * @param array $options array of the options
     * @param mixed $selected one or more selected options
     * @param string $label a label for the select field
     * @param array $attributes an array of all other attributes
     * @return string
     */
    public static function select($name, $options = array(), $selected = array(), $label = '', $attributes = array())
    {
        $attributes['name'] = $name;

        if(is_array($selected)) {
            $attributes['multiple'] = 'multiple';
        }
        else {
            $selected = array($selected);
        }

        // open select tag
        if(!empty($label)) {
            $label = form_label($label, $name['name'])."<br/>\n";
        }
        $select = $label.Html::tag_open('select', $attributes, $name);

        // create option tags
        $opts = array();
        foreach($options as $opt_val => $opt_content) {
            if(is_array($opt_val)) {
                $opts[] = '<optgroup label="'.$opt_val.'">';
                foreach($opt_val as $optgroup_val => $optgroup_content) {
                    $sel = (in_array($opt_val, $selected)) ? ' selected="selected"' : '';
                    $opts[] = '<option value="'.$optgroup_val.'"'.$sel.'>'.$optgroup_content.'</option>';
                }
                $opts[] = '</optgroup>';
            }
            else {
                $sel = (in_array($opt_val, $selected)) ? ' selected="selected"' : '';
                $opts[] = '<option value="'.$opt_val.'"'.$sel.'>'.$opt_content.'</option>';
            }
        }

        // close select tag and return
        $select .= implode("\n", $opts)."\n</select>";
        unset($opts);

        return $select;
    }

}

