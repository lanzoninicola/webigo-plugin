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
     *  Populate the Webigo_Module_Style object with the public-facing style of module
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

        $this->action_name = 'wp_enqueue_scripts';
        $this->init_module_info($style_data['module']);
        $this->build_public_css_file_root_path();
        $this->add($style_data);
    }

    /**
     *  Populate the Webigo_Module_Style object with the admin style of module
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

        $this->action_name = 'admin_enqueue_scripts';
        $this->init_module_info($style_data['module']);
        $this->build_admin_css_file_root_path();
        $this->add($style_data);
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

    public function add($style_data)
    {
        $default_dependencies = array();
        $default_version = '1.0';

        $this->src = $this->stylesheet_root_path . $style_data['file_name'];
        $this->dependencies = isset($style_data['dependencies']) ? $style_data['dependencies'] : $default_dependencies;
        $this->version = isset($style_data['version']) ? $style_data['version'] : $default_version;
    }


    private function init_module_info($module) {
      
        $this->module_name = PLUGIN_NAME . '-' . $module;
      
        $this->module_folder = $module;
    }

     /**
     *  Internal utility function to build the full css path for the public facing site
     *  
     *  @return void
     */
    public function build_public_css_file_root_path()
    {

        $path = plugin_dir_url(dirname(__FILE__)) . 'modules/' . $this->module_folder . '/css/';

        $this->stylesheet_root_path = $path;
    }

    /**
     *  Internal utility function to build the full css path for the public facing site
     *  
     *  @return void
     */
    public function build_admin_css_file_root_path()
    {

        $path = plugin_dir_url(dirname(__FILE__)) . 'modules/' . $this->module_folder . '/admin/css/';

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
        wp_enqueue_style($this->module_name, $this->src, $this->dependencies, $this->version, 'all');
    }
}
