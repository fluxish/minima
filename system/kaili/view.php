<?php

namespace Kaili;

/**
 * Kaili View Class
 *
 * This class display an action view
 *
 * @package		Kaili
 */
class View
{

    /**
     * @var Template
     */
    public $_template = null;

    /**
     * @var string
     */
    private $_controller;

    /**
     * @var string
     */
    private $_action;

    /**
     * @var boolean
     */
    private $_rendered = false;

    /**
     * @var Request
     */
    private $_request;

    /**
     * @var Output
     */
    private $_output;

    /**
     * Create new view
     * @param string $controller the name of the controller
     * @param string $action the name of the action
     * @param string $template the name of the page template
     */
    public function __construct($with_template = true)
    {
        $this->_request = Loader::get_instance()->load('request');
        $this->_output = Loader::get_instance()->load('output');

        $this->_controller = $this->_request->get('controller');
        $this->_action = $this->_request->get('action');
        if($with_template) {
            $this->_template = Loader::get_instance()->load('template');
        }
    }

    /**
     * Render an action view
     * 
     * @param array $vars an array of variables to extract and use into action view
     * @param string $view the name of the view to render
     * @param boolean $as_data true if want return the content as data
     * @param boolean $with_template true (default) if view is rendered with template
     * @return mixed if the second paramater is true, returns the content as data, 
     *      else returns null.
     */
    public function render($vars = array(), $view = null, $as_data = false, $with_template = true)
    {
        $format = $this->_request->get('format');

        // set view to render
        if(!$format || $format == 'html') {
            if($view == null) {
                // if view is null and format is html, set view to default action view
                $view = APPLICATION.DS.'views'.DS.$this->_controller.DS.$this->_action;
            }
            else if(file_exists(APPLICATION.DS.'views'.DS.$this->_controller.DS.$view.EXT)) {
                // else, set view to another view of the same controller, if this view exists
                $view = APPLICATION.DS.'views'.DS.$this->_controller.DS.$view;
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

            // if template is null or with_template is false, render view without template
            if($this->_template != null && $with_template) {
                $this->_output->set_header('Content-Type: text/html');
                ob_start();
                $this->_template->place_view('content', $vars, $view);
                $this->_template->render();
                $this->_output->append(ob_get_contents());
                ob_end_clean();
            }
            else {
                $this->_output->set_header('Content-Type: text/html');
                extract($vars);
                include($view.EXT);
            }

            // if as_data is true, save rendered view in a variable and return it
            if($as_data) {
                ob_start();
                extract($vars);
                include($view.EXT);
                $data = ob_get_contents();
                ob_end_clean();

                return $data;
            }
        }
        else {
            // other formats
            $this->_output->set_header('Content-Type: text/'.$format);
            $formatter = new Formatter($format);
            $this->_output->append($formatter->format($vars));
        }
        $this->_rendered = true;
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
     * Force the view to no render itself
     */
    public function no_render()
    {
        $this->_rendered = true;
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

