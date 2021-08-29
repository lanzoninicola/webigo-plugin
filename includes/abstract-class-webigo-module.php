<?php

require_once WEBIGO_PLUGIN_PATH . '/includes/class-webigo-module-style.php';
require_once WEBIGO_PLUGIN_PATH . '/includes/class-webigo-module-script.php';
require_once WEBIGO_PLUGIN_PATH . '/includes/class-webigo-module-hooks.php';

abstract class Webigo_Module
{

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





