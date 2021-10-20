<?php

/**
 * This class is used to let data available to js scripts
 */

class Webigo_Module_Localize_Script {


    /**
     * Array that contains the localized script of each module
     * 
     * @var array
     */
    private $localize_scripts = array();

    /**
     * The hook name indentified to run the wp_localize_script() function
     * 
     * @var string
     */
    private $action_name = 'wp_footer';

    /**
     * The name of module who need to localize the script
     * 
     * @var string
     */
    private $module_name = '';


    /**
     * Name for the JavaScript object. Passed directly, so it should be qualified JS variable. Example: '/[a-zA-Z0-9_]+/'.
     * 
     * @var string
     */
    private $object_name = '';


    /**
     * The data itself. The data can be either a single or multi-dimensional array.
     *  
     * 
     * @var array
     */
    private $params = array();

    /**
     * The name of callback to be called when the hook is fired 
     * 
     * @var string
     */
    private $callback_name = 'localize_script';

    /**
     * @var array $script_data
     * 
     * Example:
     *	$script_data = array(
	 *		'module'	  => $this->name,
	 *		'object_name' => 'lupilupi',
	 *		'params'      => array(
	 *			'timeout_foo' => '5000',
	 *		)
	 *	);
     *
	 *	$this->localize_script->register_script_data( $script_data );
     *  
     */
    public function register_script_data( array $script_data ) : void
    {

        if ( empty( $script_data['params'] ) ){
            return;
        }

        $this->init_module_info( $script_data['module'] );
        $this->object_name = $script_data['object_name'];
        $this->params      = $script_data['params'];
        $this->add();
    }


    private function init_module_info( string $module ) : void
    {
        $this->module_name = PLUGIN_NAME . '-' . $module;
    }

    
    private function add() {

        $this->localize_scripts[$this->module_name]                 = array();

        $this->localize_scripts[$this->module_name]['object_name']  = $this->object_name;
        $this->localize_scripts[$this->module_name]['params']       = $this->params;
    }


    public function action_name() {

        return $this->action_name;
    }


    public function callback_name() {

        return $this->callback_name;
    }

    /**
     * This is the function launched by the hook that has been sat in this class ($action_name)
     */
    public function localize_script( ) : void
    {
        foreach( $this->localize_scripts as $module_name => $script_data ) {
            if ( wp_script_is( $module_name ) ) {
                wp_localize_script( $module_name, $script_data['object_name'], $script_data['params'] );
            }
        }
    }

  
}