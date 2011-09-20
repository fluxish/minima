<?php

namespace Kaili;

/**
 * Response Class
 * The Response class manage the response process
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
     * Append content to response body
     * @param string $buff the content to add
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
        // TODO: manage various content types
        $this->set_header('Content-Type: text/html');
        
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
        $this->set_header('Location: '.Request::current()->referer());
    }

}

