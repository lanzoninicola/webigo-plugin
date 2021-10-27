<?php

require_once WEBIGO_PLUGIN_PATH . '/includes/class-webigo-module-conditionals.php';
require_once WEBIGO_PLUGIN_PATH . '/includes/class-webigo-module-style.php';
require_once WEBIGO_PLUGIN_PATH . '/includes/class-webigo-module-script.php';
require_once WEBIGO_PLUGIN_PATH . '/includes/class-webigo-module-hooks.php';
require_once WEBIGO_PLUGIN_PATH . '/includes/class-webigo-module-localize-script.php';
require_once WEBIGO_PLUGIN_PATH . '/includes/class-webigo-woo-custom-template-handler.php';

/**
 * The children classes are responsible to load their:
 * 
 * - css styles
 * - js scripts
 * - hooks
 * - shortcodes
 * 
 * 
 * This is instanciated through the load() method of Webigo_Modules_Registry class called in the "class-webigo.php"
 */

abstract class Webigo_Module
{
	/**
	 * Name of module.
	 * This must be the same as the module folder name
	 * 
	 * @var string
	 */
	protected $name;

	/**
	 * Path where the module is located.
	 * 
	 * @var string
	 */
	protected $module_path;

	/**
	 * The module might depend on Wordpress plugin or feature.
	 * This is an array of wp dependencies
	 * 
	 * @var array
	 */
	// TODO: Implements module dependencies
	// private $wp_dependencies;

	/**
	 * The path where the classes of module dependecies are located.
	 * 
	 * @var string
	 */
	protected $dependecies_path;


	/**
	 * The path where the classes of module views are located.
	 * 
	 * @var string
	 */
	protected $views_path;

	/**
	 * Encapsulate the style of module
	 * 
	 * @var Webigo_Module_Style
	 */
	public $style;

	/**
	 * Encapsulate the script of module
	 * 
	 * @var Webigo_Module_Script
	 */
	public $script;
	
	/**
	 * Encapsulate the script of module
	 * 
	 * @var Webigo_Module_Localize_Script
	 */
	public $localize_script;

	/**
	 * Encapsulate the script of module
	 * 
	 * @var Webigo_Module_Hooks
	 */
	public $hooks;

	/**
	 * Encapsulate method for overriding Woocommerce Templates
	 * From here is available to all modules.
	 * 
	 * The module that require custom template need to appropriate Woocommerce Filter
	 * Open the class file for the instructions
	 * 
	 * @var Webigo_Woo_Custom_Template_Handler
	 */
	public $woo_custom_template_handler;


	public function __construct() {

		// with this setup the module can manage only 1 css file
		// alternative call the Webigo_Module_Style directly in the module class
		$this->style                       = new Webigo_Module_Style();
		$this->script                      = new Webigo_Module_Script();
		$this->localize_script             = new Webigo_Module_Localize_Script();
		$this->hooks                       = new Webigo_Module_Hooks();
		$this->woo_custom_template_handler = new Webigo_Woo_Custom_Template_Handler();
		$this->module_path                 = plugin_dir_path(__DIR__) . 'modules/' . $this->name;
		$this->dependecies_path            = plugin_dir_path(__DIR__) . 'modules/' . $this->name . '/includes/';
		$this->views_path                  = plugin_dir_path(__DIR__) . 'modules/' . $this->name . '/views/';

		$this->load_dependencies();
		$this->add_style();
		$this->add_script();
		$this->add_hooks();

	}

	/**
	 * Returns the Webigo_Module_Style that encapsulates the style of module
	 *
	 * @return Webigo_Module_Style of related module
	 */
	public function style() {

		return $this->style;
	}

	/**
	 * Returns the Webigo_Module_Script that encapsulates the style of module
	 *
	 * @return Webigo_Module_Script of related module
	 */
	public function script() {

		return $this->script;
	}


	/**
	 * Returns the Webigo_Module_Script that encapsulates the style of module
	 *
	 * @return Webigo_Module_Script of related module
	 */
	public function localize_script() {

		return $this->localize_script;
	}


	abstract protected function load_dependencies();


	// abstract protected function load_views();
	
	/**
	 * Method to register the style of module
	 * 
	 * @return void
	 * 
	 * 	Example:
	 * 
	 * 	public function add_style()
	 *	{
	 *		$style_data = array(
	 *			'module' => $this->name,
	 *			'file_name' => 'archive-product.css'
	 *		);
	 *	
	 *		$this->style->register_style($style_data);
	 *		
	 *	}
	 * 
	 */
	abstract protected function add_style();

	/**
	 * Method to register the script of module
	 * 
	 * @return void
	 * 
	 * 	Example:
	 * 
	 * 	public function add_script()
	 *	{
	 *
	 *		$script_data = array(
	 *			'module' => $this->name,
	 *			'file_name' => 'archive-product.js'
	 *		);
	 *	
	 *		$this->script->register_script($script_data);
	 *	}
	 * 
	 */
	abstract protected function add_script();

	/**
	 * Method to register the hooks of module.
	 * The callback must be a static method
	 * 
	 * @return void
	 * 
	 * 	public function add_hooks() {
	 *	
	 *	   $hook_data = array(
	 *		    'hook' => 'init',
	 *			'callback' => 'sayHello'
	 *	        );
	 *
	 *		$this->hooks->register($hook_data);
	 *	
	 *	}
	 *
	 *	public static function sayHello() {
	 *
	 *		// some code
	 *	}
	 */
	abstract protected function add_hooks();



	/**
	 * Method to register the hooks of module.
	 * The callback must be a static method
	 * 
	 * @return void
	 * 
	 * 	public function add_hooks() {
	 *	
	 *	   $script_data = array(
	 *		    'module'        => 'core',
	 *			'object_name'   => 'core_params'
	 *			'params'        => array( 'timeout_settings' => 5000 )
	 *	        );
	 *
	 *		$this->localize_scripts->register_script_data($hook_data);
	 *	
	 *	}
	 *
	 */
	// abstract protected function add_script_data();

	protected function require_once( string $module_file_path ) {

		if ( is_file( $module_file_path ) === false ) {
			throw "=== The file or path $module_file_path doesn't exists";
		}

		require_once $module_file_path;
	}

}





