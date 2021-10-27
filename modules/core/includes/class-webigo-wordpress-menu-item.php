<?php

require_once WEBIGO_PLUGIN_PATH . '/includes/interface-webigo-module-hooks.php';
require_once WEBIGO_PLUGIN_PATH . '/includes/interface-webigo-view.php';
require_once WEBIGO_PLUGIN_PATH . '/modules/core/includes/interface-webigo-wp-menu-item-descriptor.php';

class Webigo_Wordpress_Menu_Item {


    private $menu_item = array();
    
    /**
     * @var IWebigo_View
     */
    private $view;

    /**
     * @var IWebigo_Module_Hooks
     */
    private $hooks;

    /**
     * @var array $menu_item - Array that describes the menu item
     * Array of TOP MENU ITEM
     *  array(
     *      'type'       => 'topmenu',
     *      'menu_item'  => '',
     *      'menu_title' => '',
     *      'capability' => '',
     *      'menu_slug'  => '',
     *      )
     * 
     * Array of SUBMENU ITEM
     *  array(
     *      'type'         => 'submenu',
     *      'parent_slug'  => '',
     *      'menu_item'    => '',
     *      'menu_title'   => '',
     *      'capability'   => '',
     *      'menu_slug'   => '',
     *      )
     * 
     * @var IWebigo_View  $view - View object that will be used to render the menu item
     * @var IWebigo_Hooks $hooks - Hooks object that will be used to register the menu item
     */
    public function __construct( array $menu_item, IWebigo_View $view, IWebigo_Hooks $hooks ) {

        $this->validate_menu_item( $menu_item );

        $this->view  = $view;
        $this->hooks = $hooks;
    }

    private function validate_menu_item( array $menu_item )
    {

        if ( isset( $menu_item['type'] ) === false 
            || $menu_item['type'] !== 'topmenu' 
            || $menu_item['type'] !== 'submenu'
            ) {
            throw 'You must define if the menu is a topmenu or a submenu ( topmenu|submenu )';
        }

        if ( $menu_item['type'] === 'submenu' && isset( $menu_item['parent_slug']) === false ) {
            throw 'You must define the slug name for the parent menu with ( parent_slug )';
        }

        if ( isset( $menu_item['page_title'] ) === false ) {
            throw 'You must define the text to be displayed in the title tags of the page when the menu is selected ( page_title )';
        } 

        if ( isset( $menu_item['menu_title'] ) === false ) {
            throw 'You must define the text to be used for the menu ( menu_title )';
        } 

        if ( isset( $menu_item['capability'] ) === false ) {
            throw 'You must define the capability required for this menu to be displayed to the user ( capability )';
        } 

        if ( isset( $menu_item['menu_slug'] ) === false ) {
            throw 'You must define the slug name to refer to this menu by. Should be unique. ( menu_slug )';
        } 
    }

    public function add()
    {
        if ( $this->menu_descriptor->type() === 'topmenu') {
            $this->add_top_menu();
        }

        if ( $this->menu_descriptor->type() === 'submenu') {
            $this->add_submenu_page();
        }
        
    }

    public function hook()
    {
        $hook_admin_menu_add_setup_menu = array(
			'hook'     => 'admin_menu',
			'callback' => array( $this, 'add' ),
            'priority' => 915
		);
		
		$this->hooks->register( $hook_admin_menu_add_setup_menu );
    }

    private function add_top_menu()
    {
        add_menu_page( 
            $this->menu_item['page_title'],
            $this->menu_item['menu_title'],
            $this->menu_item['capability'],
            $this->menu_item['menu_slug'],
            array( $this->view, 'render' )
         );
    }

    private function add_submenu_page()
    {
        add_submenu_page(
            $this->menu_item['parent_slug'],
            $this->menu_item['page_title'],
            $this->menu_item['menu_title'],
            $this->menu_item['capability'],
            $this->menu_item['menu_slug'],
            array( $this->view, 'render' )
        );
    }
}