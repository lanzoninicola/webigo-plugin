<?php


class Webigo_Modules_Registry
{

    /**
     * The name of customer for whom this plugin is developed.
     * 
     * @var string
     */
    private $customer;

    /**
     * List of customer modules
     * 
     * @var array
     */
    private $customer_modules = array(
        'core'         => 'Webigo_Core_Settings', 
        'wpadmin-menu' => 'Webigo_Wpadmin_Menu_Settings'
    );

    /**
     * 
     * @var Webigo_Module_Descriptor
     */
    private $module_descriptor;

    /**
     *  Collection of Webigo_Module_Descriptor objects
     * 
     *  @var    array of Webigo_Module_Descriptor
     */
    private $modules;

    /**
     * Ordered list of module dependencies.
     * 
     * The order in this array is important to establish the dependency between modules
     * 
     * @var array of string
     */
    protected $module_dependencies;


    public function __construct( string $customer )
    {
        $this->customer = $customer;
        $this->require_customer_modules_class();
        $this->load_customer_modules();
    }

    public function init() {

        $this->modules_registry = array();
        $this->modules = array();
        $this->module_dependencies = array();

        $this->load_dependencies();
    }


    protected function load_dependencies()
    {
        /**
         * The class responsible for describing the plugin module
         */
        require_once plugin_dir_path(__DIR__) . 'includes/class-webigo-module-descriptor.php';
    }


    private function require_customer_modules_class()
    {
        $customer = $this->customer;
        require_once plugin_dir_path(__DIR__) . "includes/customers/$customer/class-webigo-customer-modules.php";
    }


    private function load_customer_modules()
    {
        $modules = (array) Webigo_Customer_Modules::get();

        foreach( $modules as $module_name => $settings_class ) {
            $this->customer_modules[$module_name] = $settings_class;
        }
    }

    /**
     * @param bool Optional - If true, only the core module will be loaded
     */
    public function register_all( bool $emergency = false )
    {

        $customer_modules = $this->customer_modules;

        if ( $emergency === true ) {
            $customer_modules = array_filter( $this->customer_modules , function( $settings_class ) { 
                return $settings_class === 'Webigo_Core_Settings';
            });
        }

        foreach ( $customer_modules as $module_name => $settings_class ) {

            $module_settings_path = WEBIGO_PLUGIN_PATH . "/modules/$module_name/settings/class-webigo-$module_name-settings.php";

            if ( is_file( $module_settings_path ) === false ) {
                throw "============ Trying to load the settings file class-webigo-$module_name-settings.php but it doesn't exist in the following path: WEBIGO_PLUGIN_PATH . '/modules/$module_name/settings/class-webigo-$module_name-settings.php'";
            }

            require_once $module_settings_path;

            $this->register(
                $settings_class::MODULE_NAME,
                $settings_class::BOOTSTRAP_CLASS_NAME,
                $settings_class::MODULE_FOLDER,
                $settings_class::BOOTSTRAP_CLASS_FILENAME,
            );

        }
    }


    /**
     * Register in the Module Registry array the new module
     * 
     *  @param string name                 - Name of module
     *  @param string class_name           - Name of class
     *  @param string folder_name          - Name of module folder
     *  @param string bootstrap_class_file - Name of class file
     */
    private function register( string $name, string $class_name, string $folder_name, string $bootstrap_class_file )
    {

        $this->module_descriptor = new Webigo_Module_Descriptor(
            $name,
            $class_name,
            $folder_name,
            $bootstrap_class_file
        );

        array_push( $this->modules, $this->module_descriptor );
    }

    public function unregister($name)
    {
        //TODO: implement
    }

    /**
     * 
     * Expects a list of name of module (string) to register the dependencies
     * 
     * @param array of strings
     */
    public function define_module_dependencies( array $dependencies ) {

        $module_not_found = array();

        if ( empty( $this->modules ) ) {
            throw new Exception('No modules found. Before defining dependencies you have to register modules');
        }

        foreach ( $dependencies as $module_name ) {
            if ( $this->is_module_exists( $module_name ) ) {
                $this->add_module_dependencies( $module_name );
            } else {
                array_push( $module_not_found, $module_name );
            }
        }

        if ( !empty( $module_not_found ) ) {
            $message = '==========  ';
            $message .= 'Cannot added dependencies for these modules: "' . implode( ',' ,  $module_not_found) . '". Modules not found.' ;
            $message .= '  =========';

            throw new Exception($message);
        }
    }

    private function add_module_dependencies( string $module_name ) {

        array_push( $this->module_dependencies, $module_name );
    }

    /**
     * 
     * This method instantiates the class of each module 
     * responsible to load css, js, hooks, shortcodes
     * 
     * @return void
     */
    public function load()
    {

        // TODO: Here where the single module dependencies might be developed

        foreach ($this->modules as $module) {

            require_once $module->bootstrap_class_file();

            $module_class_name = $module->class_name();

            $module_instance = new $module_class_name();

            $this->modules_registry[$module->name()] = $module_instance;
        }
        
    }

     /**
     * 
     * Internal utility function: checks inside the module array if the module exists
     * 
     * @param string Expects a string with the name of module
     */
    private function is_module_exists( string $module_name ) {


        foreach ( $this->modules as $module ) {

            if ( $module->name() === $module_name ) {

                return true;
            }
        }

        return false;

    }

    /**
     * 
     *  This returns the list of modules registered
     * 
     *  @return     array   $modules_register
     */

    public function get_registered_modules()
    {

        return $this->modules_registry;
    }

    public function get_module_dependencies() {

        return $this->module_dependencies;
    }


}
