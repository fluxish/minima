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
     * @var View
     */
    protected $view;

    /**
     * @var Loader
     */
    protected $load;
    protected $_load;

    function __construct()
    {
        $this->load = Loader::get_instance();
        $this->_load = $this->load;

        $this->view = new View();
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

