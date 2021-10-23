<?php

/**
 * This class encapsulate the style of module and 
 * it contains the callback function fired by the Wordpress hook
 * 
 * It is used in the Webigo class to load as param of loader->add_action
 * 
 */

class Webigo_Module_Script
{

    /**
     * Contain each scripts added for the module
     * 
     * @var array 
     */
    private $scripts;


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
    private $script_root_path;

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
     * Indicate if the script should be loaded in the footer
     * 
     * @var bool
     */
    private $in_footer;

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

        $this->callback_name = 'enqueue_script';
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
     *  Populate the Webigo_Module_Script object with the public-facing style of module
     *  
     *  @return void
     *  @param array $script_data
     *       
     *  $script_data = array(
     *                    'module'        => string - the name of module who instanciate the class
     *                    'file_name'  => string - the name of css file
     *                    'dependencies'  => array  - Optional - array of css dependencies
     *                    'version'       => string - Optional - version of css
     *                    'in_footer'     => bool   - Option - Indicate if the script should be placed in the footer
     *                 )
     * 
     */
    public function register_script(array $script_data)
    {

        $this->action_name = 'wp_enqueue_scripts';
        $this->init_module_info($script_data['module']);
        $this->build_public_js_file_root_path();
        $this->set_scripts_info($script_data);
        $this->add();
    }

    /**
     *  Populate the Webigo_Module_Script object with the public-facing style of module
     *  
     *  @return void
     *  @param array $script_data
     *       
     *  $script_data = array(
     *                    'module'        => string - the name of module who instanciate the class
     *                    'file_name'  => string - the name of css file
     *                    'dependencies'  => array  - Optional - array of css dependencies
     *                    'version'       => string - Optional - version of css
     *                    'in_footer'     => bool   - Option - Indicate if the script should be placed in the footer
     *                 )
     * 
     */
    public function register_admin_script(array $script_data)
    {

        $this->action_name = 'admin_enqueue_scripts';
        $this->init_module_info($script_data['module']);
        $this->build_admin_js_file_root_path();
        $this->set_scripts_info($script_data);
        $this->add();
    }


    /**
     *  Internal utility function, set the internal object properties
     *  
     *  @return void
     *  @param array $script_data
     *       
     *  $script_data = array(
     *                    'module'        => string - the name of module who instanciate the class
     *                    'file_name' => string - the name of css file
     *                    'dependencies'  => array  - Optional - array of css dependencies
     *                    'version'       => string - Optional - version of css
     *                 )
     * 
     */

    private function set_scripts_info($script_data)
    {

        // TODO: refactor building method for each value with single responsibility

        $this->src            = $this->script_root_path . $script_data['file_name'];

        $default_dependencies = array();
        $dependencies         = isset($script_data['dependencies']) ? $script_data['dependencies'] : $default_dependencies;
        $this->set_dependencies($dependencies);
        
        $default_version      = '1.0';
        $this->version        = isset($script_data['version']) ? $script_data['version'] : $default_version;

        $default_in_footer    = true;
        $this->in_footer      = isset($script_data['in_footer']) ? $script_data['in_footer'] : $default_in_footer;
    }


    /**
     * Internal utility function, it adds each script of module inside the scripts array
     * 
     */
    private function add() {

        $this->scripts[$this->module_name]                 = array();

        $this->scripts[$this->module_name]['src']          = $this->src;
        $this->scripts[$this->module_name]['dependencies'] = $this->dependencies;
        $this->scripts[$this->module_name]['version']      = $this->version;
        $this->scripts[$this->module_name]['in_footer']    = $this->in_footer;
        
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
     *  Internal utility function to build the full js path of public facing site
     *  
     *  @return void
     */
    public function build_public_js_file_root_path()
    {

        $path = plugin_dir_url(__DIR__) . 'modules/' . $this->module_folder . '/js/';

        $this->script_root_path = $path;
    }

    /**
     *  Internal utility function to build the full js path of admin side
     *  
     *  @return void
     */
    public function build_admin_js_file_root_path()
    {

        $path = plugin_dir_url(__DIR__) . 'modules/' . $this->module_folder . '/admin/js/';

        $this->script_root_path = $path;
    }

    /**
     *  The callback function called by the Wordpress hook responsible for the styling
     *  This method must be public to be called in the Webigo class
     *  
     *  @return void
     */
    public function enqueue_script()
    {

        // TODO: this not works with multiple stylesheet
        // This method is added to the hook, each style might have each enqueue_style method

        foreach( $this->scripts as $module_name => $script_info ) {
            
            wp_enqueue_script( $module_name, $script_info['src'], $script_info['dependencies'], $script_info['version'], $script_info['in_footer'] );
        }
       
    }
}
