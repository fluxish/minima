<?php  if (!defined('ROOT')) exit('No direct script access allowed');

/**
 * Url Helpers
 *
 * @package		Kaili
 * @subpackage	Helpers
 * @category	Helpers
 * @author		Luigi Marco Simonetti
 */


if(!function_exists('url'))
{
    /**
     * Generate an URL according to the route
     * @param mixed $url an URL or an array of parameters
     * @return string the generated URL
     */
    function url($url, $reset = true)
    {
        if(is_array($url)){
            $input = Loader::get_instance()->library('input');
            $params = $input->url_parameters();
            
            if($reset) {
                $vars = array_merge($params['route'], $url);
            } else{
                $vars = array_merge($params['route'], $params['others'], $url);
            }
            
            $url = '';
            foreach($params['route'] as $k=>$v){
                $url .= $vars[$k].'/';
                unset($vars[$k]);
            }
            foreach($vars as $k=>$v){
                if(!empty($v) || $v==='0') $url .= $k.'/'.$v.'/';
            }
            unset($vars, $params);
        } 
        $base = Loader::get_instance()->library('config')->item('base_url');
        return ltrim($base.'/'.$url, '/');
    }
}

if(!function_exists('tiny_url'))
{
    /**
     * Generate a tiny url from a long url
     * @param string $url the long url
     * @param string $provider a tinyurl web service (default: is.gd)
     * @param array $parameters provider's parameters
     * @return string the tiny url generated
     */
    function tiny_url($url, $provider = null, $parameters = array())
    {
        $timeout = 5;
        $cs = curl_init();
        if($provider == 'bit.ly'){
            $login = 'kaili';
            $apiKey = 'R_5fd5cb7c52da0ac6152c5e073abf8cba';
    		curl_setopt($cs,CURLOPT_URL,'http://api.bit.ly/shorten?version=2.0.1&longUrl='.$url.'&login='.$login.'&apiKey='.$apiKey);    
        } 
        else {
    		curl_setopt($cs,CURLOPT_URL,'http://is.gd/api.php?longurl='.$url);    
        }
        curl_setopt($cs,CURLOPT_RETURNTRANSFER,1); 
		curl_setopt($cs,CURLOPT_CONNECTTIMEOUT,$timeout); 
		$data = curl_exec($cs); 
		curl_close($cs); 
        //$res = json_decode($data)->results->$url;
        
        return $data;
    }
}

if(!function_exists('path_to_url'))
{
    /**
     * Convert a local path to url
     * @param string $path local path
     * @return string the converted url
     */
    function path_to_url($path)
    {
        $base_url = Loader::get_instance()->library('config')->item('base_url');
        return str_replace($_SERVER['DOCUMENT_ROOT'].$_SERVER['PHP_SELF'], $base_url, $path);
    }
}

if(!function_exists('anchor'))
{
    /**
     * Create an anchor link
     * @param string $url
     * @param string $content
     * @param string $title the text of the link
     * @param array $attrs html attributes
     * @param string $method the request method (get (default), post, put, delete)
     * @return string the html code of the anchor link
     */
    function anchor($url, $content, $title = null, $attrs = array(), $method = 'get')
    {
        
        if($title == null){
            $title = $content;
        }
        $title_tag = ' title="'.$title.'"';
        
        $attributes = '';
        foreach($attrs as $key=>$value)
        {
            $attributes .= ' '.$key.'="'.$value.'"';
        }
        
        if($method == 'get'){ 
            return '<a href="'.$url.'"'.$title_tag.$attributes.'>'.$content.'</a>';
        }
        else{
            $attributes .= ' style="background:transparent;border:none;border-bottom:1px solid #00F;color:#00F;display:inline;cursor:pointer;margin:0;padding:0;height:1.3em"';
            return '<form method="post" action="'.$url.'">'
                ."\n".'<input type="hidden" name="_method" value="'.$method.'"/>'
                ."\n".'<input type="submit" value="'.$title.'" '.$title_tag.$attributes.'/>'
                ."\n</form>";
        }
    }
}
