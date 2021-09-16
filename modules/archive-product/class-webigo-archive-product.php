<?php

require_once WEBIGO_PLUGIN_PATH . 'includes/abstract-class-webigo-module.php';

/**
* {@inheritdoc}
*/
class Webigo_Archive_Product extends Webigo_Module
{

	protected $name = 'archive-product';

	private $style_version = '1.0';

	public function __construct()
	{
		parent::__construct();

		$this->load_dependencies();
		$this->add_shortcodes();
	}

	public function load_dependencies()
	{

		require_once WEBIGO_PLUGIN_PATH . '/modules/archive-product/includes/class-webigo-woo-archive-product-settings.php';

		require_once WEBIGO_PLUGIN_PATH . '/modules/archive-product/includes/class-webigo-woo-archive-product-shortcode.php';
		
	}

	public function module()
	{

		return $this->name;
	}


	public function add_style()
	{
		$style_data = array(
			'module'      => $this->name,
			'file_name'   => 'archive-product.css',
			'dependencies' => array('core')
		);

		$this->style->register_public_style($style_data);

		// $style_data = array(
		// 	'module' => $this->name,
		// 	'file_name' => 'view-product.css',
		// 	'dependecies' => array('core')
		// );

		// $this->style->register_public_style($style_data);
	}

	public function add_script()
	{

		$script_data = array(
			'module'      => $this->name,
			'file_name'   => 'archive-product.js',
			'dependencies' => array('core', 'add-to-cart'),
			'in_footer'   => true
		);

		$this->script->register_public_script( $script_data );
	}

	public function add_hooks()
	{
	}

	private function add_shortcodes()
	{

		new Webigo_Woo_Archive_Product_Shortcode();
	}


}
