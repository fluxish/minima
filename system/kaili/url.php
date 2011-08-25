<?php

namespace Kaili;

/**
 * Url class
 *
 * @package Kaili
 */
class Url
{

    /**
     * Generate an URL according to the route
     * 
     * @param mixed $url an URL or an array of parameters
     * @return string the generated URL
     */
    public static function abs($url, $reset = true)
    {
        if(is_array($url)) {
            $input = Loader::get_instance()->load('input');
            $params = $input->url_parameters();

            if($reset) {
                $vars = array_merge($params['route'], $url);
            }
            else {
                $vars = array_merge($params['route'], $params['others'], $url);
            }

            $url = '';
            foreach($params['route'] as $k => $v) {
                $url .= $vars[$k].'/';
                unset($vars[$k]);
            }
            foreach($vars as $k => $v) {
                if(!empty($v) || $v === '0')
                    $url .= $k.'/'.$v.'/';
            }
            unset($vars, $params);
        }
        $base = Loader::get_instance()->load('config')->item('base_url');
        return ltrim($base.'/'.$url, '/');
    }

    /**
     * Generate a tiny url from a long url
     * 
     * @param string $url the long url
     * @param string $provider a tinyurl web service (default: is.gd)
     * @param array $parameters provider's parameters
     * @return string the tiny url generated
     */
    public static function tiny($url, $provider = null, $parameters = array())
    {
        $timeout = 5;
        $cs = curl_init();
        if($provider == 'bit.ly') {
            $login = 'kaili';
            $apiKey = 'R_5fd5cb7c52da0ac6152c5e073abf8cba';
            curl_setopt($cs, CURLOPT_URL, 'http://api.bit.ly/shorten?version=2.0.1&longUrl='.$url.'&login='.$login.'&apiKey='.$apiKey);
        }
        else {
            curl_setopt($cs, CURLOPT_URL, 'http://is.gd/api.php?longurl='.$url);
        }
        curl_setopt($cs, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($cs, CURLOPT_CONNECTTIMEOUT, $timeout);
        $data = curl_exec($cs);
        curl_close($cs);
        //$res = json_decode($data)->results->$url;

        return $data;
    }

    /**
     * Convert a local path to url
     * 
     * @param string $path local path
     * @return string the converted url
     */
    public static function from_path($path)
    {
        $base_url = Loader::get_instance()->load('config')->item('base_url');
        return str_replace($_SERVER['DOCUMENT_ROOT'].$_SERVER['PHP_SELF'], $base_url, $path);
    }

}

