<?php  

namespace Kaili;

/**
 * Kaili Template Class
 *
 * This class manage the template system
 *
 * @package		Kaili
 */
 
class Template 
{
    /**
     * Current template variables
     * @var array
     */
    protected $_template_vars = array();

    /**
     * Array of places in a template
     * @var array
     */
    protected $_places = array();

    /**
     * @var Loader
     */
    private $_load;

    /**
     * @var Config
     */
    private $_config;
    
    /**
     * @var string
     */
    private $_controller;
    
    /**
     * @var string
     */
    private $_action;

    /**
     * Create a template
     * @param string $controller
     * @param string $action
     */
    public function __construct()
    {
        $this->_load = Loader::get_instance();
        $this->_config = $this->_load->load('config');
        
        $this->_controller = $this->_load->load('input')->get('controller');
        $this->_action = $this->_load->load('input')->get('action');
    }
    

    /**
     * Render the template
     * @param array $vars an array of variables to extract and use in the template
     */
    public function render($vars = array())
    {
        // load content
        extract($this->_places);
        
        // load template
        extract($this->_template_vars);
        include(ASSETS.DS.'themes'.DS.$this->_config->item('interface_theme').DS.'tp'.DS.$this->_config->item('main_template').EXT);
    }
    
    /**
     * Add a view in a "place" and assign content to place_nameplace variable.
     * 
     * @param string $place_name the name of the place, or null to include 
     *      in place of function call
     * @param array $vars an array of variables to extract in the view
     * @param string $view the path of the view
     */
    public function place($place_name = null, $vars = null, $view = null)
    {
        // set view to render
        if(file_exists(APPLICATION.DS.'views'.DS.$view.EXT)){
            // if null, set view to default action view
            $view = APPLICATION.DS.'views'.DS.$view;
        }
        else if(file_exists(APPLICATION.DS.'views'.DS.$this->_controller.$view.EXT)){
            // else, set view to another view of the same controller, if this view exists
            $view = APPLICATION.DS.'views'.DS.$this->_controller.$view;
        }
        else if(file_exists(APPLICATION.DS.'views'.DS.$view.EXT)){
            // else, set view to another view, if exists
            $view = APPLICATION.DS.'views'.DS.$view;
        }
        else{
            // else, search the view in the tp directory of default theme, and render founded view
            $theme = Loader::get_instance()->load('config')->item('interface_theme');
            $view = ASSETS.DS.'themes'.DS.$theme.DS.'tp'.DS.$view;
        }
        $this->_render_place($place_name, $vars, $view);
    }
    
    /**
     * Add a rendered view in a "place" and assign content to 
     * place_[place_name] variable.
     * 
     * @param string $place_name the name of the place
     * @param array $vars an array of variables to extract in the view
     * @param string $view the path of the view
     */
    public function place_view($place_name, $vars, $view)
    {   
        $this->_render_place($place_name, $vars, $view);
    }
    
    /**
     * Place a template and render
     * 
     * @param string $template the name of the template
     */
    public function place_template($template)
    {
        $this->place(null, null, $template);
    }
    
    /**
     * Executes an action and add the view in a "place" and assign content 
     * to place_nameplace variable.
     * 
     * @param string $place_name the name of the place
     * @param array $path an array with controller and action
     */
    public function place_action($place_name, $path = array())
    {
        if(Loader::get_instance()->load('input')->get('format') == 'html'){
            $route = array();
            include(APPLICATION.DS.'config'.DS.'routes.php');
            
            if(isset($path['controller'])) $controller = $path['controller'];
            else $controller = $route['default_controller'];
            
            if(isset($path['action'])) $action = $path['action'];
            else $action = $route['default_action'];
            
            ob_start();
            Loader::get_instance()->controller($controller, $action, array());
            $this->_places['place_'.$place_name] = ob_get_contents();
            ob_end_clean();
        }
    }
    
    /**
     * Set the template's variables
     * 
     * @param array $template_name an array of variables
     */
    public function set_template_vars($template_name)
    {
        $this->_template_vars = $template_name;
    }
    
    /**
     * Return a loaded library (if exists) as attribute of this template.
     * 
     * @param string $lib the name of the loaded library (with lower capital letter)
     */
    public function __get($lib)
    {
        return $this->_load->load($lib);
    }
    
    private function _render_place($place_name, $vars, $view)
    {
        extract($this->_template_vars);
        extract($this->_places);
        if(isset($vars)){
            extract($vars);
            unset($vars);
        }
        
        if($place_name != null){
            ob_start();
            include($view.EXT);
            $this->_places['place_'.$place_name] .= ob_get_contents() . "\n";
            ob_end_clean();
        }
        else{
            include($view.EXT);
        }
    }
}

/* End of file Template.php */
/* Location: ./system/library/Template.php */
