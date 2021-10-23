<?php

require_once WEBIGO_PLUGIN_PATH . 'includes/abstract-class-webigo-module.php';

// TODO: create a class to encapsulate the data of TOP MENU ITEM (this class expect as param an "interface" for a view)
// TODO: create a class to encapsulate the data of SUBMENU ITEM (this class expect as param an "interface" for a view)

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
		require_once WEBIGO_PLUGIN_PATH . 'modules/wpadmin-menu/includes/class-webigo-wpadmin-menu-handler.php';
		$this->wpadmin_menu_handler = new Webigo_Wpadmin_Menu_Handler();
		
	}

	public function module()
	{

		return $this->name;
	}


	public function add_style()
	{
		$style_data = array(
			'module'       => $this->name,
			'file_name'    => 'admin-menu-handler.css',
			'dependencies' => array('core'),
			'version'      => $this->style_version,
			'admin'        => true
		);

		$this->style->register_public_style( $style_data );

	}

	public function add_script()
	{

		$script_data = array(
			'module'       => $this->name,
			'file_name'    => 'admin-menu-handler.js',
			'dependencies' => array('core'),
			'version'      => $this->js_version,
			'admin'        => true
		);

		$this->script->register_script( $script_data );
	}

	public function add_hooks()
	{

        $hook_admin_menu_init = array(
			'hook'     => 'admin_menu',
			'callback' => array( $this->wpadmin_menu_handler, 'build_menu_schema' ),
            'priority' => 900
		);
		
		$this->hooks->register( $hook_admin_menu_init );

        // $hook_admin_menu_build_menu_schema = array(
		// 	'hook'     => 'admin_menu',
		// 	'callback' => array( $this->wpadmin_menu_handler, 'build_menu_schema' ),
        //     'priority' => 910
		// );
		
		// $this->hooks->register( $hook_admin_menu_build_menu_schema );

		$this->add_submenu_items();

		// $this->hide_menus();
	}


	private function add_submenu_items()
	{
		$this->require_once( WEBIGO_PLUGIN_PATH . 'modules/wpadmin-menu/views/class-webigo-view-admin-menu-list.php' );
		$view_admin_menu_list = new Webigo_View_Admin_Menu_List( $this->wpadmin_menu_handler );

        $hook_admin_menu_add_setup_menu = array(
			'hook'     => 'admin_menu',
			'callback' => array( $view_admin_menu_list, 'add_menu' ),
            'priority' => 915
		);
		
		$this->hooks->register( $hook_admin_menu_add_setup_menu );

	}


	private function hide_menus()
	{
		$hook_admin_menu_hide_menus = array(
			'hook'     => 'admin_menu',
			'callback' => array( $this->wpadmin_menu_handler, 'hide_menus' ),
            'priority' => 915
		);
		
		$this->hooks->register( $hook_admin_menu_hide_menus );
	}

	
}
