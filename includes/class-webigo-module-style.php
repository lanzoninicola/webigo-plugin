<?php

/**
 * This class encapsulate the style of module and 
 * it contains the callback function fired by the Wordpress hook
 * 
 * It is used in the Webigo class to load as param of loader->add_action
 * 
 */

class Webigo_Module_Style
{

    /**
     * Contain each style added for the module
     * 
     * @var array 
     */
    private $styles;

    /**
     * The name of module who instanciate this class, passed by 
     * the register_public_style or register_admin_style methods
     * 
     * @var string
     */
    private $module_name;

    
    /**
     * The name of folder that contains the style
     * 
     * @var string
     */
    private $module_folder;

    /**
     * The root path of css file
     * 
     * @var string
     */
    private $stylesheet_root_path;

    /**
     * The full path of css file
     * 
     * @var string
     */
    private $src;

    /**
     * The array of css dependencies
     * 
     * @var array
     */
    private $dependencies;

    /**
     * The version of style
     * 
     * @var string
     */
    private $version;

    /**
     * The name of Wordpress hook to be appended. It used in the plugin loader class in the Webigo class 
     * 
     * @var string
     */
    private $action_name;

    /**
     * The name of callback to be called when the hook is fired 
     * 
     * @var string
     */
    private $callback_name;

    public function __construct()
    {

        $this->callback_name = 'enqueue_style';
    }

    // this method must be public to be called to plugin loader
    public function action_name()
    {

        return $this->action_name;
    }

    // this method must be public to be called to plugin loader
    public function callback_name()
    {

        return $this->callback_name;
    }

    /**
     *  Populate the Webigo_Module_Style object with the PUBLIC-FACING style of module
     *  
     *  @return void
     *  @param array $style_data
     *       
     *  $style_data = array(
     *                    'module'        => string - the name of module who instanciate the class
     *                    'file_name' => string - the name of css file
     *                    'dependencies'  => array  - Optional - array of css dependencies
     *                    'version'       => string - Optional - version of css
     *                 )
     * 
     */
    public function register_public_style(array $style_data)
    {
        /**
         *  Do not change the orders of these functions
         */
        $this->styles = array();
        $this->action_name = 'wp_enqueue_scripts';
        $this->init_module_info($style_data['module']);
        $this->set_public_stylesheet_src_path();
        $this->set_stylesheet_info($style_data);
        $this->add();
    }

    /**
     *  Populate the Webigo_Module_Style object with the ADMIN style of module
     *  
     *  @return void
     *  @param array $style_data
     *       
     *  $style_data = array(
     *                    'module'        => string - the name of module who instanciate the class
     *                    'file_name' => string - the name of css file
     *                    'dependencies'  => array  - Optional - array of css dependencies
     *                    'version'       => string - Optional - version of css
     *                 )
     * 
     */
    public function register_admin_style(array $style_data)
    {

        /**
         *  Do not change the orders of these functions
         */
        $this->action_name = 'admin_enqueue_scripts';
        $this->init_module_info($style_data['module']);
        $this->set_admin_stylesheet_src_path();
        $this->set_stylesheet_info($style_data);
        $this->add();
    }


    /**
     *  Internal utility function, set the internal object properties
     *  
     *  @return void
     *  @param array $style_data
     *       
     *  $style_data = array(
     *                    'module'        => string - the name of module who instanciate the class
     *                    'file_name' => string - the name of css file
     *                    'dependencies'  => array  - Optional - array of css dependencies
     *                    'version'       => string - Optional - version of css
     *                 )
     * 
     */

    private function set_stylesheet_info($style_data)
    {

        // TODO: refactor building method for each value with single responsibility

        $this->src            = $this->stylesheet_root_path . $style_data['file_name'];

        $default_dependencies = array();
        $dependencies         = isset($style_data['dependecies']) ? $style_data['dependecies'] : $default_dependencies;
        $this->set_dependencies($dependencies);
        
        $default_version      = '1.0';
        $this->version        = isset($style_data['version']) ? $style_data['version'] : $default_version;
    }

    /**
     * Internal utility function, it adds each style of module inside the styles array
     * 
     */
    private function add() {

        $this->styles[$this->module_name]                 = array();

        $this->styles[$this->module_name]['src']          = $this->src;
        $this->styles[$this->module_name]['dependencies'] = $this->dependencies;
        $this->styles[$this->module_name]['version']      = $this->version;
        
    }


    /**
     * Set the array of dependencies to pass to the wp_enqueue_style function
     * 
     * @param array of style dependencies
     */
    private function set_dependencies( array $dependencies ) {

        if ( empty( $dependencies ) ) {

            $this->dependencies = $dependencies;
        }

        if ( !empty( $dependencies ) ) {

            $next_dependencies = array();

            foreach ( $dependencies as $dependency ) {
                array_push( $next_dependencies,  PLUGIN_NAME . '-' . $dependency );
            }

            $this->dependencies = $next_dependencies;
        }
    }

    /**
     * Set the name of handle for the css.
     * The handle name is composed of the NAME_OF_PLUGIN + MODULE_NAME
     * 
     * @param string
     */
    private function init_module_info(string $module) {
      
        $this->module_name = PLUGIN_NAME . '-' . $module;
      
        $this->module_folder = $module;
    }

     /**
     *  Internal utility function to build the full css path for the public facing site
     *  
     *  @return void
     */
    public function set_public_stylesheet_src_path()
    {

        $path = plugin_dir_url(__DIR__) . 'modules/' . $this->module_folder . '/css/';

        $this->stylesheet_root_path = $path;
    }

    /**
     *  Internal utility function to build the full css path for the public facing site
     *  
     *  @return void
     */
    public function set_admin_stylesheet_src_path()
    {

        $path = plugin_dir_url(__DIR__) . 'modules/' . $this->module_folder . '/admin/css/';

        $this->stylesheet_root_path = $path;
    }

    /**
     *  The callback function called by the Wordpress hook responsible for the styling
     *  This method must be public to be called in the Webigo class
     *  
     *  @return void
     */
    public function enqueue_style()
    {

        // TODO: this not works with multiple stylesheet
        // This method is added to the hook, each style might have each enqueue_style method
        foreach( $this->styles as $module_name => $style_info ) {

            wp_enqueue_style($module_name, $style_info['src'], $style_info['dependencies'], $style_info['version'], 'all');
        }
    }
}
