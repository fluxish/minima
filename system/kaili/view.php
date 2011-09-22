<?php

namespace Kaili;

/**
 * Kaili View Class
 *
 * This class display an action view
 *
 * @package Kaili
 */
class View
{

    /**
     * Returns a new View object
     * 
     * @param string|array $view the name of the view or the array of data to pass 
     *      a default view
     * @param array $data the array of data to send to the view
     * @return View
     */
    public static function factory($view = null, array $data = null)
    {
        // check if the first argument is an array
        if(is_array($view)){
            $data = $view;
            $view = null;
        }
        
        // create the View
        $request = Request::current();
        $controller = $request->get('controller');
        $action = $request->get('action');

        if($view === null) {
            // if view is null and format is html, set view to default action view
            $file = APPLICATION.DS.'views'.DS.$controller.DS.$action.EXT;
        }
        else if(file_exists(APPLICATION.DS.'views'.DS.$controller.DS.$view.EXT)) {
            // else, set view to another view of the same controller, if this view exists
            $file = APPLICATION.DS.'views'.DS.$controller.DS.$view.EXT;
        }
        else if(file_exists(APPLICATION.DS.'views'.DS.$view.EXT)) {
            // else, set view to another view, if exists
            $file = APPLICATION.DS.'views'.DS.$view.EXT;
        }
        else {
            // else, search the view in the tp directory of default theme, and render it
            $theme = Loader::get_instance()->load('config')->item('interface_theme');
            $file = ASSETS.DS.'themes'.DS.$theme.DS.'tp'.DS.$view.EXT;
        }

        return new static($file, $data);
    }
    
    
    /**
     * The path of the view
     * @var string
     */
    private $_file = null;
    
    /**
     * An array of data to extract in the view
     * @var array
     */
    private $_data = null;
    
    /**
     * Array of all places in the view
     * @var array
     */
    private $_places = null;
    
    /**
     * The prefix of place's name
     * @var string
     */
    private $_place_prefix = 'place_';

    /**
     * Create a new View
     * 
     * @param array $data the array of data to send to the view
     * @param string $file the file of the view
     * @return Kaili\View
     */
    public function __construct($file = null, array $data = null)
    {
        $this->_file = $file;
        $this->_data = $data;
        $this->_places = array();
        
        // TEMPORARY VARIABLES
        $this->config = Loader::get_instance()->load('config');
        $this->session = Loader::get_instance()->load('session');
    }

    /**
     * Render an action view
     * 
     * @param array $data the array of data to send to the view
     * @return string the code of the view
     */
    public function render()
    {
        $template = View::factory(Loader::get_instance()->load('config')->item('main_template'));
        $template->place('content', $this->render_no_template());
        $code = $template->render_no_template();

//        else {
//            // other formats
//            $this->_output->set_header('Content-Type: text/'.$format);
//            $formatter = new Formatter($format);
//            $this->_output->append($formatter->format($vars));
//        }
        return $code;
    }
    
    /**
     * Render an action view without a template
     * 
     * @param array $data the array of data to send to the view
     * @param string $file the file of the view
     * @return string the code of the view
     */
    public function render_no_template()
    {
        ob_start();
        if($this->_data !== null) extract($this->_data);
        if(count($this->_places) !== 0) extract($this->_places);
        include($this->_file);
        $code = ob_get_contents();
        ob_end_clean();
        
        return $code;
    }
    
    /**
     * Add html code in a "place" and assign content to place_[name_place] variable
     * 
     * @param string $name the name of the place
     * @param string $code the html code to include in the place
     */
    public function place($name, $code)
    {
        $this->_places[$this->_place_prefix.$name] = $code;
    }
    
    /**
     * Add a rendered view in a "place" and assign content to plac_[place_name] variable
     * @param $name the name of the place
     * @param string|View $view the name of the view or a View object 
     * @param array $data the array of data to send to the view
     */
    public function place_view($name, $view, $data = null)
    {
        if($view instanceof Kaili\View){
            $code = $view->render_no_template();
        }
        else{
            $code = View::factory($view, $data)->render_no_template();
        }
        $this->place($name, $code);
    }
    
    /**
     * Place a template and render it
     * @param string $template the name of the template
     * @param array $data the array of data to send to the view
     */
    public function place_template($template, $data = null)
    {
        echo View::factory($template, $data)->render_no_template();
    }
    
    /**
     * Set the file of the view
     * @param string $file 
     */
    public function set_file($file)
    {
        $this->_file = $file;
    }
    
    /**
     * Set the data of the view
     * @param array $data 
     */
    public function set_data(array $data)
    {
        $this->_data = $data;
    }

    /**
     * Set a template object
     * @param Kaili\Template $template a Template object
     */
    function set_template(\Kaili\Template $template)
    {
        $this->_template = $template;
    }
    
//     /**
//     * Executes an action and add the view in a "place" and assign content 
//     * to place_nameplace variable.
//     * 
//     * @param string $place_name the name of the place
//     * @param array $path an array with controller and action
//     */
//    public function place_action($place_name, $path = array())
//    {
//        if(Request::current()->get('format') == 'html'){
//            $route = array();
//            include(APPLICATION.DS.'config'.DS.'routes.php');
//            
//            if(isset($path['controller'])) $controller = $path['controller'];
//            else $controller = $route['default_controller'];
//            
//            if(isset($path['action'])) $action = $path['action'];
//            else $action = $route['default_action'];
//            
//            ob_start();
//            Loader::get_instance()->controller($controller, $action, array());
//            $this->_places['place_'.$place_name] = ob_get_contents();
//            ob_end_clean();
//        }
//    }

}

