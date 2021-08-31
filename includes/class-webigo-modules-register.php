<?php


class Webigo_Modules_Register
{

    private $module;

    /**
     *  Collection of Webigo_Module_Descriptor objects
     * 
     *  @var    array of Webigo_Module_Descriptor
     */
    private $modules;

    /**
     *  Collection of object instanciated related to the plugin modules
     * 
     *  @var    array of Module Object
     */
    private $modules_register;

    /**
     * The styles register that's responsible for maintaining and registering all styles that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Webigo_Styles_Register    $styles    Maintains and registers all styles for the plugin.
     */
    protected $styles_register;

    /**
     * The scripts register that's responsible for maintaining and registering all scripts that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Webigo_Scripts_Register    $styles    Maintains and registers all styles for the plugin.
     */
    protected $scripts_register;


    public function __construct()
    {

        $this->modules_register = array();
        $this->modules = array();

        $this->load_dependencies();
    }


    public function load_dependencies()
    {

        /**
         * The class responsible for describing the plugin module
         * 
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-webigo-module-descriptor.php';
    }


    public function register(string $name, string $class_name, string $folder_name, string $bootstrap_class_file)
    {

        $this->module = new Webigo_Module_Descriptor(
            $name,
            $class_name,
            $folder_name,
            $bootstrap_class_file
        );

        array_push($this->modules, $this->module);
    }

    public function unregister($name)
    {
        //TODO: implement
    }

    public function load()
    {

        // TODO: Here where the single module dependencies might be developed

        foreach ($this->modules as $module) {

            require_once $module->bootstrap_class_file();

            $module_class_name = $module->class_name();

            $module_instance = new $module_class_name($this->styles_register, $this->scripts_register);

            $this->modules_register[$module->name()] = $module_instance;

        }
    }

    /**
     * 
     *  This returns the list of modules registered
     * 
     *  @return     array   $modules_register
     */

    public function get_registered_modules()
    {

        return $this->modules_register;
    }
}
