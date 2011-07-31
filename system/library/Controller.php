<?php  if (!defined('ROOT')) exit('No direct script access allowed');

/**
 * Kaili Controller Class
 *
 * The Controller Class for Kaili MVC Pattern
 *
 * @package		Kaili
 * @subpackage	Libraries
 * @category	Libraries
 * @author		Luigi Marco Simonetti
 */

class Controller
{
    /**
     * @var View
     */
    public $view;

    /**
     * @var Loader
     */
    public $_load;
    
    function __construct()
    {
        $this->_load = Loader::get_instance();
        
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
        return $this->_load->library($lib);
    }
}

/* End of file Controller.php */
/* Location: ./system/library/Controller.php */