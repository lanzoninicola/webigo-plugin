<?php

require_once WEBIGO_PLUGIN_PATH . 'includes/abstract-class-webigo-module.php';

/**
* {@inheritdoc}
*/
class Webigo_Add_To_Cart extends Webigo_Module
{

	protected $name = 'add-to-cart';

	private $style_version = '1.0';

	public function __construct()
	{
		parent::__construct();

	}

	public function load_dependencies()
	{
	}

	public function load_views()
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
			'file_name'   => 'add-to-cart.css',
			'dependencies' => array('core')
		);

		$this->style->register_public_style($style_data);
	}

	public function add_script()
	{

		$script_data = array(
			'module'      => $this->name,
			'file_name'   => 'add-to-cart.js',
			'dependencies' => array('core'),
			'in_footer'   => true
		);

		$this->script->register_public_script( $script_data );
	}

	public function add_hooks()
	{
	}


}
