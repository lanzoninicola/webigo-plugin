<?php

require_once WEBIGO_PLUGIN_PATH . 'includes/abstract-class-webigo-module.php';

/**
* {@inheritdoc}
*/
class Webigo_Cart_Widget extends Webigo_Module
{

	protected $name = 'cart-widget';

	private $style_version = '1.0';

	/**
	 * Here the cupouns are not managed
	 */
	public function __construct()
	{
		parent::__construct();
	}

	public function load_dependencies()
	{
		
	}

	public function module()
	{

		return $this->name;
	}


	public function add_style()
	{
		$style_data = array(
			'module'      => $this->name,
			'file_name'   => 'cart-widget.css',
			'dependencies' => array('core')
		);

		$this->style->register_public_style($style_data);

	}

	public function add_script()
	{

		/**
		 * Javascript code is not fired after the hitting the UPDATE CART button
		 */
		$script_data = array(
			'module'      => $this->name,
			'file_name'   => 'cart-widget.js',
			'dependencies' => array('core'),
			'in_footer'   => true
		);

		$this->script->register_public_script( $script_data );
		
	}

	public function add_hooks()
	{

				
	}


}
