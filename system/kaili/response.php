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
        $this->_body = '';
    }

    /**
     * Append content to response body
     * @param string $buff the content to add
     */
    public function append($buff)
    {
        if($buff instanceof Kaili\View) {
            $buff = $buff->render();
        }
        $this->_body .= $buff;
    }

    /**
     * Flush all response buffer
     */
    public function flush()
    {
        // TODO: manage various content types
        $this->set_content_type('text/html');

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
     * Set the content-type of the response
     * @param string $content_type
     */
    public function set_content_type($content_type)
    {
        $this->_headers[] = 'Content-Type: '.$content_type;
    }
    
    /**
     * Redirect the response to specific URL
     * @param string $url 
     */
    public function redirect_to($url)
    {
        $this->set_header('Location: '.Kaili\Url::abs($url));
    }

    /**
     * Redirect the response to the referer URL
     */
    public function redirect_to_referer()
    {
        $this->set_header('Location: '.Request::current()->referer());
    }

}

