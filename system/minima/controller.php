<?php

namespace Minima;

/**
 * Minima Controller Class
 *
 * The Controller Class for Minima MVC Pattern
 *
 * @package		Minima
 */
class Controller
{

    /**
     * The Request object
     * @var Minima\Request
     */
    protected $request;
    
    /**
     * The Response object
     * @var Minima\Response
     */
    protected $response;
    
    /**
     * Create a new Controller object
     */
    function __construct()
    {
        $this->request = Request::current();
        $this->response = new \Minima\Response();
    }

    function __destruct()
    {
        $this->response->flush();
    }

}

