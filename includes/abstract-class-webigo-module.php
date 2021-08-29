<?php

require_once WEBIGO_PLUGIN_PATH . '/includes/class-webigo-module-style.php';
require_once WEBIGO_PLUGIN_PATH . '/includes/class-webigo-module-script.php';

abstract class Webigo_Module
{

	/**
	 * Encapsulate the style of module
	 * 
	 * @var Webigo_Module_Style
	 */
	public $style;

		/**
	 * Encapsulate the style of module
	 * 
	 * @var Webigo_Module_Script
	 */
	public $script;


	public function __construct() {

		// with this setup the module can manage only 1 css file
		// alternative call the Webigo_Module_Style directly in the module class
		$this->style = new Webigo_Module_Style();
		
		$this->script = new Webigo_Module_Script();

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
	 */
	abstract protected function add_style();

	/**
	 * Method to register the script of module
	 * 
	 * @return void
	 */
	abstract protected function add_script();


}
