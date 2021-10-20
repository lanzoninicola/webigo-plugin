<?php

// TODO: need to create a separeted class for admin styles

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
     * The id of single css file
     * 
     * @var string
     */
    private $style_id;

    /**
     * Contain each style added for the module
     * 
     * @var array 
     */
    private $styles = array();

    /**
     * Array of data to load the module style passed as input on module init
     * 
     * @var array
     */
    private $style_data = array();

    /**
     * The name of Wordpress hook to be appended. It used in the plugin loader class in the Webigo class 
     * 
     * @var string
     */
    private $register_action_name = 'wp_enqueue_scripts';

    
    /**
     * The name of Wordpress hook to be appended. It used in the plugin loader class in the Webigo class 
     * 
     * @var string
     */
    private $enqueue_action_name = 'wp_footer';

    /**
     * List of callbacks name used to register the style.
     * Names are generated dinamically appending the ID of style
     * 
     * @var array
     */
    private $register_callbacks_name = [];

    /**
     * List of callbacks name used to enqueue the style
     * Names are generated dinamically appending the ID of style
     * 
     * @var array
     */
    private $enqueue_callbacks_name = [];


    public function __construct()
    {
    }

    public function register_action_name()
    {
        return $this->register_action_name;
    }

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
    public function register_public_style( array $style_data )
    {
        $this->style_data = $style_data;

        /**
         *  Do not change the orders of these functions
         */
        $this->set_style_id();
        $this->set_handle_name();
        $this->set_stylesheet_src();
        $this->set_dependencies();
        $this->set_version();
        $this->set_register_callbacks_function();
        $this->set_inclusions();
        $this->set_enqueue_callbacks_function();
    }


    private function set_style_id()
    {
        $this->style_id = uniqid();
        $this->styles[$this->style_id] = array();
    }

     
    /**
     * Set the name of handle for the css.
     * The handle name is composed of the NAME_OF_PLUGIN + MODULE_NAME
     * 
     * @param string
     */
    private function set_handle_name() {

        if ( isset( $this->style_data['module'] ) === false ) {
            throw 'Webigo_Module_Style - "module" key is required on array input';
        }

        $handle_name = PLUGIN_NAME . '-' . $this->style_data['module'] . '-' . $this->style_id;

        $this->styles[$this->style_id]['handle'] = $handle_name;
      
    }

    /**
     *  Internal utility function to build the full css path for the public facing site
     *  
     *  @return void
     */
    private function set_stylesheet_src()
    {
        if ( isset( $this->style_data['file_name'] ) === false ) {
            throw 'Webigo_Module_Style - "file_name" key is required on array input';
        }

        $path = plugin_dir_url(__DIR__) . 'modules/' . $this->style_data['module'] . '/css/';

        $this->styles[$this->style_id]['src'] = $path . $this->style_data['file_name'];
    }

    /**
     * Set the array of dependencies to pass to the wp_enqueue_style function
     * 
     * @param array of style dependencies
     */
    private function set_dependencies() {

        $default_dependencies = array();

        if ( isset( $this->style_data['dependecies'] )  === false ) {
            $this->styles[$this->style_id]['dependencies'] = $default_dependencies;
            return;
        }


        if ( empty( $this->style_data['dependecies'] ) === false ) {

            $dependencies = array();

            foreach ( $this->style_data['dependecies'] as $dependency ) {
                array_push( $dependencies,  PLUGIN_NAME . '-' . $dependency );
            }

            $this->styles[$this->style_id]['dependencies'] = $dependencies;
        }
    }


    private function set_version()
    {
        $default_version      = '1.0';

        if ( isset( $this->style_data['version'] )  === false ) {
            $this->styles[$this->style_id]['version'] = $default_version;
            return;
        }

        $this->styles[$this->style_id]['version'] = $this->style_data['version'];
    }


    /**
     * 
     */
    private function set_inclusions() : void
    {

        if ( isset( $this->style_data['includes'] ) === false ) {
            $this->styles[$this->style_id]['includes'] = array();
            return;
        }

        if ( $this->style_data['includes'] === null ) {
            $this->styles[$this->style_id]['includes'] = array();
            return;
        }

        $this->styles[$this->style_id]['includes'] = $this->style_data['includes'];
    }


    
    public function set_register_callbacks_function()
    {
        foreach( array_keys( $this->styles ) as $id ) {
            array_push( $this->register_callbacks_name, "wp_register_style_$id");
        }
    }

    public function set_enqueue_callbacks_function()
    {
        foreach( array_keys( $this->styles ) as $id ) {
            array_push( $this->enqueue_callbacks_name, "wp_enqueue_style_$id");
        }
    }

    /**
     * __call is a magic PHP function. It is triggered when invoking inaccessible methods in an object context.
     * 
     * This function is used to intercept the call to the register and enqueue functions (did in the webigo main class)
     * and call the related methods with the ID of style
     * 
     */
    public function __call( $function, $params ){

        if ( substr( $function, 0, 18 ) === 'wp_register_style_' ) {
            $style_id = substr( $function, 18, 99 );
            $this->register_style( $style_id );
        }

        if ( substr( $function, 0, 17 ) === 'wp_enqueue_style_' ) {
            $style_id = substr( $function, 17, 99 );
            $this->enqueue_style( $style_id );
        }

    }

    /**
     *  The callback function called by the Wordpress hook responsible for the styling
     *  This method must be public to be called in the Webigo class
     *  
     *  @return void
     */
    public function register_style( $style_id )
    {

        $handle       = $this->styles[$style_id]['handle'];
        $src          = $this->styles[$style_id]['src'];
        $dependencies = $this->styles[$style_id]['dependencies'];
        $version      = $this->styles[$style_id]['version'];

        wp_register_style( $handle, $src, $dependencies, $version, 'all' );

    }


     /**
     * Determine if a style should be enqueued depending on the condiationals functions
     * 
     * @var bool
     */
    private function should_enqueued( $style_id ) : bool
    {

        $this->styles[$style_id]['should_enqueued'] = false;

        $conditional_results = [];

        foreach ( $this->styles[$style_id]['includes'] as $conditional_fn_name ) {
            $conditionals = new Webigo_Module_Conditionals();
            $conditional_results[] = $conditionals->test( $conditional_fn_name );
        }

        /**
        * if one of condition passed is true the style is enqueued
        */
        if ( count( array_filter( $conditional_results, function( $result ) {
            return $result === true;
        }) ) > 0 ) {
            $this->styles[$style_id]['should_enqueued'] = true;
        }

        /**
         * If not is declared the style is enqueued
         */
        if ( count( $this->styles[$style_id]['includes'] ) === 0 ) {
            $this->styles[$style_id]['should_enqueued'] = true;
        }

        return $this->styles[$style_id]['should_enqueued'];
    }

    /**
     * This is triggered whent the wp_footer action is fired.
     * In that moment the Woocommerce and WordPress conditional tag 
     * returns the correct value and it can be possible test them
     */
    public function enqueue_style( $style_id )
    {
        $handle       = $this->styles[$style_id]['handle'];

        if ( $this->should_enqueued( $style_id ) === true ) {
            wp_enqueue_style( $handle );
        }
    }
}
