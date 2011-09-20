<?php

namespace Kaili;

/**
 * Kaili Response Class
 *
 * Manage the response process
 *
 * @package Kaili
 */
class Response
{

    /**
     * The body of the response
     * @var string
     */
    private $_body;

    /**
     * Array of headers
     * @var array
     */
    private $_headers;
    
    /**
     * Create a new Response object
     */
    public function __construct()
    {
        $this->_headers = array();
    }

    /**
     * Append a buffer to final output
     * @param string a buffer
     */
    public function append($buff)
    {
        if(empty($this->_body)) {
            $this->_body = $buff;
        }
        else {
            $this->_body .= $buff;
        }
    }

    /**
     * Flush all response buffer
     */
    public function flush()
    {
        foreach($this->_headers as $header) {
            header($header, true);
        }
        echo $this->_body;
    }
    
    /**
     * Add a new header
     * @param string $header 
     */
    public function set_header($header)
    {
        $this->_headers[] = $header;
    }
    
    /**
     * Redirect the response to specific URL
     * @param string $url 
     */
    public function redirect_to($url)
    {
        $this->set_header('Location: '. Kaili\Url::abs($url));
    }
    
    /**
     * Redirect the response to the referer URL
     */
    public function redirect_to_referer()
    {
        $this->set_header('Location: '.Loader::get_instance()->load('request')->referer());
    }

}

