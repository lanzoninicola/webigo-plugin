<?php

require_once WEBIGO_PLUGIN_PATH . 'includes/abstract-class-webigo-module.php';

/**
* {@inheritdoc}
*/
class Webigo_Shipping_Banner extends Webigo_Module
{

	protected $name = 'shipping-banner';

	private $style_version = '1.0';

	public function __construct()
	{
		parent::__construct();

	}

	public function load_dependencies()
	{
		
		// require_once WEBIGO_PLUGIN_PATH . '/modules/shipping/includes/class-webigo-shipping-settings.php';

		require_once WEBIGO_PLUGIN_PATH . '/modules/shipping-banner/views/class-webigo-view-shipping-banner.php';
		
	}

	public function module()
	{

		return $this->name;
	}


	public function add_style()
	{
		$style_data = array(
			'module'      => $this->name,
			'file_name'   => 'shipping-banner.css',
			'dependencies' => array('core')
		);

		$this->style->register_public_style($style_data);

	}

	public function add_script()
	{

		$script_data = array(
			'module'      => $this->name,
			'file_name'   => 'shipping-banner.js',
			'dependencies' => array('core', 'shipping'),
			'in_footer'   => true
		);

		$this->script->register_public_script( $script_data );
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
