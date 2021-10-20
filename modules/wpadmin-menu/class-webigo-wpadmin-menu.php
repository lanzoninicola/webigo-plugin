<?php

// https://stackoverflow.com/questions/62451295/select-between-pickup-or-delivery-shipping-methods-first-in-woocommerce

require_once WEBIGO_PLUGIN_PATH . 'includes/abstract-class-webigo-module.php';

/**
* {@inheritdoc}
*/
class Webigo_Wpadmin_Menu extends Webigo_Module
{

	protected $name;

	private $style_version;

	private $js_version;

	public function __construct()
	{

		$this->name          = Webigo_Wpadmin_Menu_Settings::MODULE_NAME;
		$this->style_version = Webigo_Wpadmin_Menu_Settings::CSS_VERSION;
		$this->js_version    = Webigo_Wpadmin_Menu_Settings::JS_VERSION;

		parent::__construct();

		$this->load_dependencies();
	}

	public function load_dependencies()
	{
		require_once WEBIGO_PLUGIN_PATH . '/modules/wpadmin-menu/includes/class-webigo-wpadmin-menu-handler.php';
		
	}

	public function module()
	{

		return $this->name;
	}


	public function add_style()
	{
		// $style_data = array(
		// 	'module'       => $this->name,
		// 	'file_name'    => 'wpadmin.css',
		// 	'dependencies' => array('core'),
		// 	'version'      => $this->style_version,
		// );

		// $this->style->register_public_style($style_data);

	}

	public function add_script()
	{

		// $script_data = array(
		// 	'module'       => $this->name,
		// 	'file_name'    => 'wpadmin.js',
		// 	'dependencies' => array('core', 'add-to-cart'),
		// 	'version'      => $this->js_version,
		// 	'in_footer'    => true
		// );

		// $this->script->register_public_script( $script_data );
	}

	public function add_hooks()
	{

        $wpadmin_menu_handler = new Webigo_Wpadmin_Menu_Handler();

        $hook_admin_menu_init_checks = array(
			'hook'     => 'admin_menu',
			'callback' => array( $wpadmin_menu_handler, 'init_checks' ),
            'priority' => 900
		);
		
		$this->hooks->register( $hook_admin_menu_init_checks );

        $hook_admin_menu_build_menu_schema = array(
			'hook'     => 'admin_menu',
			'callback' => array( $wpadmin_menu_handler, 'build_menu_schema' ),
            'priority' => 910
		);
		
		$this->hooks->register( $hook_admin_menu_build_menu_schema );

        $hook_admin_menu_add_setup_menu = array(
			'hook'     => 'admin_menu',
			'callback' => array( $wpadmin_menu_handler, 'add_setup_menu' ),
            'priority' => 915
		);
		
		$this->hooks->register( $hook_admin_menu_add_setup_menu );

        $hook_admin_menu_hide_menus = array(
			'hook'     => 'admin_menu',
			'callback' => array( $wpadmin_menu_handler, 'hide_menus' ),
            'priority' => 915
		);
		
		$this->hooks->register( $hook_admin_menu_hide_menus );


	}

	
}
