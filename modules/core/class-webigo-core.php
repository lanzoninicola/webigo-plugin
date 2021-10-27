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

		require_once WEBIGO_PLUGIN_PATH . '/modules/core/includes/class-webigo-user.php';
	}

	public function load_views() {}


	public function module()
	{

		return $this->name;
	}


	public function add_style()
	{
		$core = array(
			'module'    => $this->name,
			'file_name' => 'core.css',
			'version'   => $this->style_version,
		);

		$this->style->register_style( $core );

		$colors = array(
			'module'    => $this->name,
			'file_name' => CUSTOMER_NAME . '-colors.css',
			'version'   => $this->style_version,
		);

		$this->style->register_style( $colors );

		$fonts = array(
			'module'    => $this->name,
			'file_name' => CUSTOMER_NAME . '-fonts.css',
			'version'   => $this->style_version,
		);

		$this->style->register_style( $fonts );

		$buttons = array(
			'module'    => $this->name,
			'file_name' => CUSTOMER_NAME . '-buttons.css',
			'version'   => $this->style_version,
		);

		$this->style->register_style( $buttons );

		$hazbier = array(
			'module'    => $this->name,
			'file_name' => 'hazbier.css',
			'version'   => $this->style_version,
		);

		$this->style->register_style( $hazbier );

		$keyframes = array(
			'module'    => $this->name,
			'file_name' => 'keyframes.css',
			'version'   => $this->style_version,
		);

		$this->style->register_style( $keyframes );

		$overlay = array(
			'module'    => $this->name,
			'file_name' => 'overlay.css',
			'version'   => $this->style_version,
		);

		$this->style->register_style( $overlay );

		$pix = array(
			'module'    => $this->name,
			'file_name' => 'pix.css',
			'version'   => $this->style_version,
			'includes'  => array( 'is_checkout' ),
		);

		$this->style->register_style( $pix );

		$swiper = array(
			'module'    => $this->name,
			'file_name' => 'swiper.css',
			'version'   => $this->style_version,
			'disabled'  => true
		);

		$this->style->register_style( $swiper );

		$woocommerce = array(
			'module'    => $this->name,
			'file_name' => 'woocommerce.css',
			'version'   => $this->style_version,
		);

		$this->style->register_style( $woocommerce );
		
	}

	public function add_script()
	{
		$state_manager = array(
			'module'      => $this->name,
			'file_name'   => 'state-manager.js',
			'version'     => $this->js_version,
			'disabled'    => true
		);

		$this->script->register_script( $state_manager );

		$script_data = array(
			'module'      => $this->name,
			'file_name'   => 'core.js',
			'version'     => $this->js_version,
		);

		$this->script->register_script( $script_data );

		$admin_script_data = array(
			'module'      => $this->name,
			'file_name'   => 'core.js',
			'version'     => $this->js_version,
			'admin'       => true,
			'use_public'  => true,
		);

		$this->script->register_script( $admin_script_data );

		$header = array(
			'module'      => $this->name,
			'file_name'   => 'header.js',
			'version'     => $this->js_version,
			'includes'    => array( 'is_archive' ),
		);

		$this->script->register_script( $header );

		$buttons = array(
			'module'      => $this->name,
			'file_name'   => 'buttons.js',
			'version'     => $this->js_version,
		);

		$this->script->register_script( $buttons );

		$overlay = array(
			'module'      => $this->name,
			'file_name'   => 'overlay.js',
			'version'     => $this->js_version,
		);

		$this->script->register_script( $overlay );
	}

	public function add_hooks()
	{

		$hook_footer = array(
			'hook'     => 'wp_footer',
			'callback' => array( $this->view_overlay, 'render' )
		);

		$this->hooks->register( $hook_footer );

		$this->add_root_menu_page();

	}

	private function add_root_menu_page()
	{
		$this->require_once( WEBIGO_PLUGIN_PATH . 'modules/core/includes/class-webigo-plugin-menu-handler.php' );
		$plugin_menu_handler = new Webigo_Plugin_Menu_Handler();

        $hook_admin_menu_add_root_page = array(
			'hook'     => 'admin_menu',
			'callback' => array( $plugin_menu_handler, 'add_root_page' ),
            'priority' => 915
		);
		
		$this->hooks->register( $hook_admin_menu_add_root_page );
	}
	
}
