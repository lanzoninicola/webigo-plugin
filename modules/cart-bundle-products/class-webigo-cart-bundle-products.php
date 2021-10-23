<?php

require_once WEBIGO_PLUGIN_PATH . 'includes/abstract-class-webigo-module.php';

/**
* {@inheritdoc}
*/
class Webigo_Cart_Bundle_Products extends Webigo_Module
{

	protected $name = 'cart-bundle-products';

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
			'file_name'   => 'cart-bundle-products.css',
			'dependencies' => array('core')
		);

		$this->style->register_style($style_data);
	}

	public function add_script()
	{


	}

	public function add_hooks()
	{

	}


}
