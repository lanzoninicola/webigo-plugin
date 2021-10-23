<?php

/**
 * This class encapsulate the script of module and 
 * it contains the callback function fired by the Wordpress hook
 * 
 * It is used in the Webigo class to load as param of loader->add_action
 * 
 */

class Webigo_Module_Script
{

    /**
     * The id of single css file
     * 
     * @var string
     */
    private $script_id;

    /**
     * Contain each script added for the module
     * 
     * @var array 
     */
    private $scripts = array();

    /**
     * Array of data to load the module script passed as input on module init
     * 
     * @var array
     */
    private $script_data = array();

    /**
     * The name of Wordpress hook to be appended. It used in the plugin loader class in the Webigo class 
     * 
     * @var string
     */
    // private $register_action_name = 'wp_enqueue_scripts';

    
    /**
     * The name of Wordpress hook to be appended. It used in the plugin loader class in the Webigo class 
     * 
     * @var string
     */
    private $enqueue_action_name = 'wp_footer';

    /**
     * List of callbacks name used to register the script.
     * Names are generated dinamically appending the ID of script
     * 
     * @var array
     */
    private $register_callbacks_name = [];

    /**
     * List of callbacks name used to enqueue the script
     * Names are generated dinamically appending the ID of script
     * 
     * @var array
     */
    private $enqueue_callbacks_name = [];


    public function __construct()
    {
    }

    // public function register_action_name()
    // {
    //     return $this->register_action_name;
    // }

    public function enqueue_action_name()
    {
        return $this->enqueue_action_name;
    }


    public function register_callbacks() : array
    {
        return $this->register_callbacks_name;
    }


    public function enqueue_callbacks() : array
    {
        return $this->enqueue_callbacks_name;
    }

    
    /**
     *  Populate the Webigo_Module_Script object with the PUBLIC-FACING script of module
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
    public function register_public_script( array $script_data )
    {
        $this->script_data = $script_data;

        /**
         *  Do not change the orders of these functions
         */
        $this->set_script_id();
        $this->set_handle_name();
        $this->set_admin_script();
        $this->set_script_src();
        $this->set_dependencies();
        $this->set_version();
        $this->set_register_callbacks_function();
        $this->set_inclusions();
        $this->set_disabled_script();
        $this->set_enqueue_callbacks_function();
    }


    private function set_script_id()
    {
        $this->script_id = uniqid();
        $this->scripts[$this->script_id] = array();
    }
     
    /**
     * Set the name of handle for the css.
     * The handle name is composed of the NAME_OF_PLUGIN + MODULE_NAME
     * 
     * @param string
     */
    private function set_handle_name() {

        if ( isset( $this->script_data['module'] ) === false ) {
            throw 'Webigo_Module_Style - "module" key is required on array input';
        }

        $handle_name = PLUGIN_NAME . '-' . $this->script_data['module'] . '-' . $this->script_id;

        $this->scripts[$this->script_id]['handle'] = $handle_name;
      
    }


    private function set_admin_script()
    {

        $this->scripts[$this->script_id]['admin'] = false;

        if ( isset( $this->script_data['admin'] ) && $this->script_data['admin'] === true ) {
            $this->scripts[$this->script_id]['admin'] = true;      
        }
    }

    /**
     *  Internal utility function to build the full css path for the public facing site
     *  
     *  @return void
     */
    private function set_script_src()
    {
        if ( isset( $this->script_data['file_name'] ) === false ) {
            throw 'Webigo_Module_Style - "file_name" key is required on array input';
        }

        $path = plugin_dir_url(__DIR__) . 'modules/' . $this->script_data['module'] . '/js/';

        // handle script on admin side of the site
        if ( $this->scripts[$this->script_id]['admin'] === true ) {
            $path = plugin_dir_url(__DIR__) . 'modules/' . $this->script_data['module'] . '/admin/js/';
        }

        $this->scripts[$this->script_id]['src'] = $path . $this->script_data['file_name'];
    }

    /**
     * Set the array of dependencies to pass to the wp_enqueue_script function
     * 
     * @param array of script dependencies
     */
    private function set_dependencies() {

        $default_dependencies = array();

        if ( isset( $this->script_data['dependecies'] )  === false ) {
            $this->scripts[$this->script_id]['dependencies'] = $default_dependencies;
            return;
        }


        if ( empty( $this->script_data['dependecies'] ) === false ) {

            $dependencies = array();

            foreach ( $this->script_data['dependecies'] as $dependency ) {
                array_push( $dependencies,  PLUGIN_NAME . '-' . $dependency );
            }

            $this->scripts[$this->script_id]['dependencies'] = $dependencies;
        }
    }


    private function set_version()
    {
        $default_version      = '1.0';

        if ( isset( $this->script_data['version'] )  === false ) {
            $this->scripts[$this->script_id]['version'] = $default_version;
            return;
        }

        $this->scripts[$this->script_id]['version'] = $this->script_data['version'];
    }


    /**
     * 
     */
    private function set_inclusions() : void
    {

        if ( isset( $this->script_data['includes'] ) === false ) {
            $this->scripts[$this->script_id]['includes'] = array();
            return;
        }

        if ( $this->script_data['includes'] === null ) {
            $this->scripts[$this->script_id]['includes'] = array();
            return;
        }

        $this->scripts[$this->script_id]['includes'] = $this->script_data['includes'];
    }

    private function set_disabled_script() : void
    {

        if ( isset( $this->script_data['disabled'] ) === false ) {
            $this->scripts[$this->script_id]['disabled'] = false;
            return;
        }

        if ( $this->script_data['disabled'] === null ) {
            $this->scripts[$this->script_id]['disabled'] = false;
            return;
        }

        $this->scripts[$this->script_id]['disabled'] = $this->script_data['disabled'];
    }


    
    public function set_register_callbacks_function()
    {
        foreach( array_keys( $this->scripts ) as $id ) {
            array_push( $this->register_callbacks_name, array( "wp_register_script_$id", $this->scripts[$id]['admin'] ) );
        }
    }

    /**
     * This function push the all callbacks will be fired on enqueued script wp action
     */
    public function set_enqueue_callbacks_function()
    {
        foreach( array_keys( $this->scripts ) as $id ) {
            array_push( $this->enqueue_callbacks_name, array( "wp_enqueue_script_$id", $this->scripts[$id]['admin'] ) );
        }
    }

    /**
     * __call is a magic PHP function. It is triggered when invoking inaccessible methods in an object context.
     * 
     * This function is used to intercept the call to the register and enqueue functions (did in the webigo main class)
     * and call the related methods with the ID of script
     * 
     */
    public function __call( $function, $params ){

        

        if ( substr( $function, 0, 19 ) === 'wp_register_script_' ) {
            $script_id = substr( $function, 19, 99 );
            $this->register_script( $script_id );
        }

        if ( substr( $function, 0, 18 ) === 'wp_enqueue_script_' ) {
            $script_id = substr( $function, 18, 99 );
            $this->enqueue_script( $script_id );
        }

    }

    /**
     *  The callback function called by the Wordpress hook responsible for the styling
     *  This method must be public to be called in the Webigo class
     *  
     *  @return void
     */
    public function register_script( $script_id )
    {

        $handle       = $this->scripts[$script_id]['handle'];
        $src          = $this->scripts[$script_id]['src'];
        $dependencies = $this->scripts[$script_id]['dependencies'];
        $version      = $this->scripts[$script_id]['version'];
        $in_footer    = true;

        wp_register_script( $handle, $src, $dependencies, $version, $in_footer );

    }


     /**
     * Determine if a script should be enqueued depending on the condiationals functions
     * 
     * @var bool
     */
    private function should_enqueued( $script_id ) : bool
    {

        $this->scripts[$script_id]['should_enqueued'] = false;

        $conditional_results = [];

        foreach ( $this->scripts[$script_id]['includes'] as $conditional_fn_name ) {
            $conditionals = new Webigo_Module_Conditionals();
            $conditional_results[] = $conditionals->test( $conditional_fn_name );
        }

        /**
        * if one of condition passed is true the script is enqueued
        */
        if ( count( array_filter( $conditional_results, function( $result ) {
            return $result === true;
        }) ) > 0 ) {
            $this->scripts[$script_id]['should_enqueued'] = true;
        }

        /**
         * If not is declared the script is enqueued
         */
        if ( count( $this->scripts[$script_id]['includes'] ) === 0 ) {
            $this->scripts[$script_id]['should_enqueued'] = true;
        }

         /**
         * If script is marked disabled it is not enqueued;
         */
        if ( $this->scripts[$script_id]['disabled'] === true ) {
            $this->scripts[$script_id]['should_enqueued'] = false;
        }

        return $this->scripts[$script_id]['should_enqueued'];
    }

    /**
     * This is triggered whent the wp_footer action is fired.
     * In that moment the Woocommerce and WordPress conditional tag 
     * return the correct value and it can be possible test them
     */
    public function enqueue_script( $script_id )
    {
        $handle       = $this->scripts[$script_id]['handle'];

        if ( $this->should_enqueued( $script_id ) === true ) {
            wp_enqueue_script( $handle );
        }
    }
}
