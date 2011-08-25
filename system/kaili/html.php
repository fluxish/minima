<?php

namespace Kaili;

/**
 * HTML class
 *
 * @package Kaili
 */
class Html
{

    /**
     * Return a doctype string between xhtml11, xhtml1-strict, xhtml1-trans, xhtml1-frame,
     * html5, html4-strict, html4-trans, html4-frame (default xhtml11)
     * @param string type of doctype 
     * @return string
     */
    public static function doctype($type = 'xhtml11')
    {
        global $doctypes;

        if(!is_array($doctypes)) {
            if(!require_once(APPLICATION.DS.'config'.DS.'doctypes.php'))
                return false;
        }

        if(isset($doctypes[$type])) {
            return $doctypes[$type];
        }
        else
            return false;
    }

    /**
     * Return a tag link
     * @param mixed
     * @param string 
     * @param string  
     * @param array
     * @return string
     */
    public static function head_link($rel, $type = "", $href = "", $attributes = array())
    {
        if(is_array($rel)) {
            $attributes = $rel;
        }
        else {
            $attributes['rel'] = $rel;
            $attributes['type'] = $type;
            $attributes['href'] = $href;
        }

        return $label.tag_open('link', $attributes, true);
    }

    /**
     * Create an anchor link
     * @param string $url
     * @param string $content
     * @param string $title the text of the link
     * @param array $attributes an array of all other attributes of the input tag
     * @param string $method the request method (get (default), post, put, delete)
     * @return string 
     */
    public static function anchor($url, $content, $title = null, $attributes = array(), $method = 'get')
    {
        // return a custom input text
        $attributes['href'] = $url;

        if($title == null)
            $attributes['title'] = $content;
        else
            $attributes['title'] = $title;

        if($method == 'get') {
            return tag_open('a', $attributes).$content.'</a>';
        }
        else {
            $attributes .= ' style="background:transparent;border:none;border-bottom:1px solid #00F;color:#00F;display:inline;cursor:pointer;margin:0;padding:0;height:1.3em"';
            return '<form method="post" action="'.$url.'">'
                    ."\n".'<input type="hidden" name="_method" value="'.$method.'"/>'
                    ."\n".'<input type="submit" value="'.$title.'" '.$title_tag.$attributes.'/>'
                    ."\n</form>";
        }
    }

    /**
     * Return one or more breking tag
     * @param integer number of br tags (default 1)
     * @return string
     */
    public static function br($num = 1)
    {
        return str_repeat("<br/>\n", $num);
    }

    /**
     * Return one or more non breaking spaces
     * @param integer number of nbsp (default 1)
     * @return string
     */
    public static function nbsp($num = 1)
    {
        return str_repeat('&nbsp;', $num);
    }

    /**
     * Generate an unordered list
     * @param array items
     * @param array attributes of ul tag
     * @return string
     */
    public static function ul($items, $attributes = array())
    {
        $ul = tag_open('ul', $attributes);
        $lis = array();
        foreach($items as $item) {
            if(is_array($item)) {
                $lis[] = '<li>'.$item;
                $lis[] = ul($item);
                $lis[] = '</li>';
            }
            else
                $lis[] = '<li>'.$item.'</li>';
        }

        return $ul.implode("\n", $lis)."</ul>\n";
    }

    /**
     * Generate an unordered list
     * @param array items
     * @param array attributes of ol tag
     * @return string
     */
    public static function ol($items, $attributes = array())
    {
        $ul = tag_open('ol', $attributes);
        $lis = array();
        foreach($items as $item) {
            if(is_array($val)) {
                $lis[] = '<li>'.$val;
                $lis[] = ul($val);
                $lis[] = '</li>';
            }
            else
                $lis[] = '<li>'.$val.'</li>';
        }

        return $ul.implode("\n", $lis)."</li>\n";
    }

    /**
     * Return a form open tag
     * @param string the action to execute on form submit
     * @param array an array of attributes and their values
     * @return string
     */
    public static function form_open($action, $attributes = array(), $multipart = false)
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

        return tag_open('form', $attributes);
    }

    /**
     * Return a form close tag
     * @return string
     */
    public static function form_close()
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
    public static function form_input($type, $name, $value = "", $label = "", $attributes = array())
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
        else if(function_exists('lang')) {
            $label = lang('form_'.$name);
//if(!$label) $label = $name; 
//form_label($label, $attributes['name']).$sep."\n"; 
        }

        return $label.tag_open('input', $attributes, true);
    }

    /**
     * Return an input text field
     * @param string $name
     * @param string $value
     * @param string $label a label for the input field
     * @param array $attributes an array of all other attributes of the input tag
     * @return string
     */
    public static function form_text($name, $value, $label, $attributes = array())
    {
        return form_input('text', $name, $value, $label, $attributes);
    }

    /**
     * Return an input hidden field
     * @param mixed name of input hidden field (given also as id), or an array of all attributes
     * @param string the value
     * @return string
     */
    public static function form_hidden($name, $value)
    {
        return form_input('hidden', $name, $value);
    }

    /**
     * Return an input password field
     * @param string $name
     * @param string $value
     * @param string $label a label for the input field
     * @param array $attributes an array of all other attributes of the input tag
     * @return string
     */
    public static function form_password($name, $value, $label, $attributes = array())
    {
        return form_input('password', $name, $value, $label, $attributes);
    }

    /**
     * Return a textarea field
     * @param mixed name of the textarea field (given also as id), or an array of all attributes
     * @param string $value an initial value
     * @param string $label a label for the input field
     * @param array $attributes an array of all other attributes of the input tag
     * @return string
     */
    public static function form_textarea($name, $value = '', $label = '', $attributes = array())
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
        else if(function_exists('lang')) {
            $label = lang('form_'.$name);
#            if(!$label) $label = $name; 
#            form_label($label, $attributes['name'])."<br/>\n"; 
        }

        return $label.tag_open('textarea', $attributes).$value.tag_close('textarea');
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
    public static function form_checkbox($name, $value, $label, $checked = false, $attributes = array())
    {
        if($checked) {
            $attributes['checked'] = 'checked';
        }
        else {
            unset($attributes['checked']);
        }

        return form_input('checkbox', $name, $value, $label, $attributes);
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
    public static function form_radio($name, $value, $label, $checked = false, $attributes = array())
    {
        if($checked) {
            $attributes['checked'] = 'checked';
        }
        else {
            unset($attributes['checked']);
        }

        return form_input('radio', $name, $value, $label, $attributes);
    }

    /**
     * Return an input file field
     * @param string $name
     * @param string $value
     * @param string $label a label for the input field
     * @param array $attributes an array of all other attributes of the input tag
     * @return string
     */
    public static function form_file($name, $value, $label, $attributes = array())
    {
        return form_input('file', $name, $value, $label, $attributes);
    }

    /**
     * Return a submit button
     * @param string $name
     * @param string $value
     * @param array $attributes an array of all other attributes of the input tag
     * @return string
     */
    public static function form_submit($name, $value, $attributes = array())
    {
        return form_input_button('submit', $name, $value, '', $attributes);
    }

    /**
     * Return a reset button
     * @param string $name
     * @param string $value
     * @param array $attributes an array of all other attributes of the input tag
     * @return string
     */
    public static function form_reset($name, $value, $attributes = array())
    {
        return form_input_button('reset', $name, $value, '', $attributes);
    }

    /**
     * Return an input button
     * @param string $type
     * @param string $name
     * @param string $value
     * @param array $attributes an array of all other attributes of the input tag
     * @return string
     */
    public static function form_input_button($type, $name, $value = '', $attributes = array())
    {
        if(empty($value) && function_exists('lang')) {
            $value = lang('form_'.$name);
            if(!$value)
                $value = $name;
        }

        $attributes['id'] = $name;
        $attributes['name'] = $name;
        $attributes['type'] = $type;
        $attributes['value'] = $value;

        return tag_open('input', $attributes, true);
    }

    /**
     * Return a graphical submit button
     * @param string $name
     * @param string $value
     * @param string path of image used as submit button background
     * @param array $attributes an array of all other attributes of the input tag
     * @return string
     */
    public static function form_image($name, $value, $src = "", $attributes = array())
    {
        if(!empty($src)) {
            $attributes['src'] = $src;
        }

        return form_input('image', $name, $value, '', $attributes);
    }

    /**
     * Return a generic form button
     * @param string type
     * @param string name
     * @param string the content
     * @param array $attributes an array of all other attributes of the input tag
     * @return string
     */
    public static function form_button($type, $name, $content = '', $attributes = array())
    {
// if $name is a string, return simple input text with id, name and value
        $attributes['id'] = $name;
        $attributes['name'] = $name;
        $attributes['type'] = $type;

        return tag_open('button', $attributes).$content.'</button>';
    }

    /**
     * Return a label for a field
     * @param string $label text of the label
     * @param string $for name of the field to witch is associated
     * @param array $attributes an array of all other attributes of the input tag
     * @return string
     */
    public static function form_label($label, $for = '', $attributes = array())
    {
        if(!empty($for)) {
            $attributes['for'] = $for;
        }
        return tag_open('label', $attributes).$label.'</label>';
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
    public static function form_select($name, $options = array(), $selected = array(), $label = '', $attributes = array())
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
        $select = $label.tag_open('select', $attributes, $name);

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

    /**
     * Create any tag
     * @param string name of the tag
     * @param array attributes of the tag
     * @param boolean true if tag must be close (/>), otherwise false
     * @return string
     */
    public static function tag_open($tag, $attributes = array(), $close = false)
    {
// add attributes
        $attrs = array();
        if(!empty($attributes)) {
            foreach($attributes as $attr => $val) {
                $attrs[] = $attr.'="'.$val.'"';
            }
        }

        if($close) {
            return '<'.$tag.' '.implode(" ", $attrs)."/>\n";
        }

        return '<'.$tag.' '.implode(" ", $attrs).">\n";
    }

    /**
     * Create any closing tag
     * @param string name of the tag
     * @return string
     */
    public static function tag_close($tag)
    {
        return '</'.$tag.'>';
    }

}

