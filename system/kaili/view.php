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

        return new static($data, $file, $with_template);
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
     * The Template object associated to this view
     * @var Template
     */
    public $_template = null;

    /**
     * Create a new View
     * 
     * @param array $data the array of data to send to the view
     * @param string $file the file of the view
     * @param boolean $with_template set a template for this view (default: true)
     * @return Kaili\View
     */
    public function __construct(array $data = null, $file = null)
    {
        $this->_data = $data;
        $this->_file = $file;
    }

    /**
     * Render an action view
     * 
     * @param array $data the array of data to send to the view
     * @return string the code of the view
     */
    public function render()
    {
        // associates a Template object
        if($this->_template === null)
            $this->_template = Loader::get_instance()->load('template');
        
        ob_start();
        $this->_template->place_view('content', $this->_data, $this->_file);
        $this->_template->render();
        $code = ob_get_contents();
        ob_end_clean();

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
        include($this->_file);
        $code = ob_get_contents();
        ob_end_clean();
        
        return $code;
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

}

