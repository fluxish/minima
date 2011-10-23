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
            if(!require_once(CONFIG.DS.'doctypes.php'))
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

        return $label.static::tag_open('link', $attributes, true);
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
            return static::tag_open('a', $attributes).$content.'</a>';
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
        $ul = static::tag_open('ul', $attributes);
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
        $ul = static::tag_open('ol', $attributes);
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

