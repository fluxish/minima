<?php

namespace Kaili;

/**
 * Kaili Controller Class
 *
 * The Controller Class for Kaili MVC Pattern
 *
 * @package		Kaili
 */
class Controller
{

    /**
     * The Request object
     * @var Kaili\Request
     */
    private $request;
    
    /**
     * The Response object
     * @var Kaili\Response
     */
    private $response;
    
    /**
     * Create a new Controller object
     */
    function __construct()
    {
        $this->request = Request::current();
        $this->response = new \Kaili\Response();
    }

    function __destruct()
    {
    }

    /**
     * Return a loaded library (if exists) as attribute of this controller.
     * @param string $lib the name of the loaded library (with lower capital letter)
     */
    function __get($lib)
    {
        return Loader::get_instance()->load($lib);
    }

}

