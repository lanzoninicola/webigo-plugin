<?php

require_once WEBIGO_PLUGIN_PATH . '/includes/class-webigo-module-style.php';
require_once WEBIGO_PLUGIN_PATH . '/includes/class-webigo-module-script.php';
require_once WEBIGO_PLUGIN_PATH . '/includes/class-webigo-module-hooks.php';

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
	private $wp_dependencies;

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
	 * @var Webigo_Module_Hooks
	 */
	public $hooks;


	public function __construct() {

		// with this setup the module can manage only 1 css file
		// alternative call the Webigo_Module_Style directly in the module class
		$this->style = new Webigo_Module_Style();
		
		$this->script = new Webigo_Module_Script();

		$this->hooks = new Webigo_Module_Hooks();

		$this->module_path = plugin_dir_path(dirname(__FILE__)) . 'modules/' . $this->name;
		
		$this->dependecies_path = plugin_dir_path(dirname(__FILE__)) . 'modules/' . $this->name . '/includes/';

		$this->views_path = plugin_dir_path(dirname(__FILE__)) . 'modules/' . $this->name . '/views/';

		$this->load_dependencies();
		$this->load_views();
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

	abstract protected function load_dependencies();


	abstract protected function load_views();
	
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
	 *		$this->style->register_public_style($style_data);
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
	 *		$this->script->register_public_script($script_data);
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


}





