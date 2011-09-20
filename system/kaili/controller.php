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
     * @var View
     */
    protected $view;

    /**
     * @var Loader
     */
    protected $load;
    
    /**
     *
     * @param Kaili\Request $request the request object 
     */
    function __construct(Kaili\Request $request)
    {
        $this->load = Loader::get_instance();
        $this->view = new View();
        
        $this->request = $request;
        $this->response = new Kaili\Response();
    }

    function __destruct()
    {
        if(!$this->view->is_rendered())
            $this->view->render();
    }

    /**
     * Set a view object
     * @param string $view a View object
     */
    function set_view($view)
    {
        $this->view = $view;
    }

    /**
     * Return a loaded library (if exists) as attribute of this controller.
     * @param string $lib the name of the loaded library (with lower capital letter)
     */
    function __get($lib)
    {
        return $this->load->load($lib);
    }

}

