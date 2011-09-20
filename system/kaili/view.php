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
     * @param array $data the array of data to send to the view
     * @param string $view the name of the view
     * @param boolean $with_template set a template for this view (default: true)
     * @return Kaili\View
     */
    public static function factory(array $data = null, $view = null, $with_template = true)
    {
        $request = Request::current();
        $controller = $request->get('controller');
        $action = $request->get('action');

        if($view === null) {
            // if view is null and format is html, set view to default action view
            $view = APPLICATION.DS.'views'.DS.$controller.DS.$action;
        }
        else if(file_exists(APPLICATION.DS.'views'.DS.$controller.DS.$view.EXT)) {
            // else, set view to another view of the same controller, if this view exists
            $view = APPLICATION.DS.'views'.DS.$controller.DS.$view;
        }
        else if(file_exists(APPLICATION.DS.'views'.DS.$view.EXT)) {
            // else, set view to another view, if exists
            $view = APPLICATION.DS.'views'.DS.$view;
        }
        else {
            // else, search the view in the tp directory of default theme, and render it
            $theme = Loader::get_instance()->load('config')->item('interface_theme');
            $view = ASSETS.DS.'themes'.DS.$theme.DS.'tp'.DS.$view;
        }

        return new static($data, $view, $with_template);
    }

    /**
     * @var Template
     */
    public $_template = null;

    /**
     * @var boolean
     */
    private $_rendered = false;

    /**
     * Create a new View
     * 
     * @param array $data the array of data to send to the view
     * @param string $file the file of the view
     * @param boolean $with_template set a template for this view (default: true)
     * @return Kaili\View
     */
    public function __construct(array $data = null, $file = null, $with_template = true)
    {
        if($with_template) {
            $this->_template = Loader::get_instance()->load('template');
            $this->render($data, $file);
        }
        else{
            $this->render_no_template($data, $file);
        }
    }

    /**
     * Render an action view
     * 
     * @param array $data the array of data to send to the view
     * @param string $file the file of the view
     * @return string the code of the view
     */
    public function render(array $data = null, $file = null)
    {
        ob_start();
        $this->_template->place_view('content', $data, $file);
        $this->_template->render();
        $code = ob_get_contents();
        ob_end_clean();

//        else {
//            // other formats
//            $this->_output->set_header('Content-Type: text/'.$format);
//            $formatter = new Formatter($format);
//            $this->_output->append($formatter->format($vars));
//        }
        $this->_rendered = true;
        return $code;
    }
    
    /**
     * Render an action view without a template
     * 
     * @param array $data the array of data to send to the view
     * @param string $file the file of the view
     * @return string the code of the view
     */
    public function render_no_template(array $data = null, $file = null)
    {
        ob_start();
        extract($vars);
        include($view.EXT);
        $code = ob_get_contents();
        ob_end_clean();
        
        return $code;
    }

    /**
     * Check if the action view is rendered.
     * @return boolean true if the action view is really rendered, false otherwise.
     */
    public function is_rendered()
    {
        return $this->_rendered;
    }

    /**
     * Set a template object
     * @param Template $template a Template object
     */
    function set_template($template)
    {
        $this->_template = $template;
    }

}

