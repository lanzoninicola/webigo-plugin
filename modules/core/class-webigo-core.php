<?php

require_once WEBIGO_PLUGIN_PATH . 'includes/abstract-class-webigo-module.php';

class Webigo_Core extends Webigo_Module
{

	protected $name;

	private $style_version;

	private $js_version;

	public function __construct()
	{
		$this->name          = Webigo_Core_Settings::MODULE_NAME;
		$this->style_version = Webigo_Core_Settings::CSS_VERSION;
		$this->js_version    = Webigo_Core_Settings::JS_VERSION;

		parent::__construct();
	}

	public function load_dependencies()
	{
		require_once WEBIGO_PLUGIN_PATH . '/modules/core/views/class-webigo-view-overlay.php';
		$this->view_overlay = new Webigo_View_Overlay();

		require_once WEBIGO_PLUGIN_PATH . '/modules/core/includes/class-webigo-woo-urls.php';
	}

	public function load_views() {}


	public function module()
	{

		return $this->name;
	}


	public function add_style()
	{
		$style_data = array(
			'module'    => $this->name,
			'file_name' => 'core.css',
			'version'   => $this->style_version,
		);

		$this->style->register_public_style($style_data);
	}

	public function add_script()
	{
		$script_data = array(
			'module'      => $this->name,
			'file_name'   => 'core.js',
			'in_footer'   => true,
			'version'     => $this->js_version,
		);

		$this->script->register_public_script( $script_data );
	}

	public function add_hooks()
	{

		$hook_footer = array(
			'hook'     => 'wp_footer',
			'callback' => array( $this->view_overlay, 'render' )
		);

		$this->hooks->register( $hook_footer );

	}
	
}
