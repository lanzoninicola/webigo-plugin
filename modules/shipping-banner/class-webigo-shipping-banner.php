<?php

require_once WEBIGO_PLUGIN_PATH . 'includes/abstract-class-webigo-module.php';

/**
* {@inheritdoc}
*/
class Webigo_Shipping_Banner extends Webigo_Module
{

	protected $name;

	private $style_version;

	private $js_version;

	public function __construct()
	{
		$this->name          = Webigo_Shipping_Banner_Settings::MODULE_NAME;
		$this->style_version = Webigo_Shipping_Banner_Settings::CSS_VERSION;
		$this->js_version    = Webigo_Shipping_Banner_Settings::JS_VERSION;

		parent::__construct();

	}

	public function load_dependencies()
	{
		require_once WEBIGO_PLUGIN_PATH . '/modules/shipping-banner/views/class-webigo-view-shipping-banner.php';
	}

	public function module()
	{

		return $this->name;
	}


	public function add_style()
	{

		$style_data = array(
			'module'       => $this->name,
			'file_name'    => 'shipping-banner.css',
			'version'      => $this->style_version,
			'dependencies' => array('core')
		);

		$this->style->register_style($style_data);

	}

	public function add_script()
	{

		$script_data = array(
			'module'       => $this->name,
			'file_name'    => 'shipping-banner.js',
			'version'      => $this->js_version,
			'dependencies' => array('core', 'shipping'),
		);

		$this->script->register_script( $script_data );
	}

	public function add_hooks()
	{

		$view_banner = new Webigo_View_Shipping_Banner();

		$hook_footer = array(
			'hook'     => 'wp_footer',
			'callback' => array( $view_banner, 'render' )
		);

		$this->hooks->register($hook_footer);
	}


}
