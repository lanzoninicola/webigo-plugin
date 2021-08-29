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
    public function register_public_script(array $script_data)
    {

        $this->action_name = 'wp_enqueue_scripts';
        $this->init_module_info($script_data['module']);
        $this->build_js_file_root_path();
        $this->add($script_data);
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
        $this->build_js_file_root_path();
        $this->add($script_data);
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

    public function add($script_data)
    {

        $default_dependencies = array();
        $default_version = '1.0';
        $default_in_footer = true;
        
        $this->src          = $this->script_root_path . $script_data['file_name'];
        $this->dependencies = isset($script_data['dependencies']) ? $script_data['dependencies'] : $default_dependencies;
        $this->version      = isset($script_data['version']) ? $script_data['version'] : $default_version;
        $this->in_footer    = isset($script_data['in_footer']) ? $script_data['in_footer'] : $default_in_footer;
    }

    private function init_module_info($module) {
      
        $this->module_name = PLUGIN_NAME . '-' . $module;
      
        $this->module_folder = $module;
    }

     /**
     *  Internal utility function to build the full css path
     *  
     *  @return void
     */
    public function build_js_file_root_path()
    {

        $path = plugin_dir_url(dirname(__FILE__)) . 'modules/' . $this->module_folder . '/js/';

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
        wp_enqueue_script($this->module_name, $this->src, $this->dependencies, $this->version, $this->in_footer);
    }
}
