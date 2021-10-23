<?php

require_once WEBIGO_PLUGIN_PATH . 'includes/abstract-class-webigo-module.php';

/**
* {@inheritdoc}
*/
class Webigo_Add_To_Cart extends Webigo_Module
{

	protected $name;

	private $style_version;

	private $js_version;

	public function __construct()
	{
		$this->name          = Webigo_Add_To_Cart_Settings::MODULE_NAME;
		$this->style_version = Webigo_Add_To_Cart_Settings::CSS_VERSION;
		$this->js_version    = Webigo_Add_To_Cart_Settings::JS_VERSION;
		
		parent::__construct();
	}

	public function load_dependencies()
	{
		require_once WEBIGO_PLUGIN_PATH . '/modules/notifications/views/class-webigo-view-notifications.php';
		require_once WEBIGO_PLUGIN_PATH . '/modules/add-to-cart/includes/class-webigo-woo-add-to-cart.php';
		require_once WEBIGO_PLUGIN_PATH . '/modules/add-to-cart/includes/class-webigo-woo-ajax-add-to-cart.php';
		require_once WEBIGO_PLUGIN_PATH . '/modules/add-to-cart/views/class-webigo-view-add-to-cart-footer.php';
		require_once WEBIGO_PLUGIN_PATH . '/modules/add-to-cart/includes/class-webigo-add-to-cart-bulk-ajax-request.php';
		require_once WEBIGO_PLUGIN_PATH . '/modules/add-to-cart/views/class-webigo-view-add-to-cart-notifications.php';
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
			'module'       => $this->name,
			'file_name'    => 'add-to-cart-mini.css',
			'dependencies' => array('core'),
			'version'	   => $this->style_version,
			'includes'     => array( 'is_shop' )
		);

		$this->style->register_public_style($style_data);
	}

	public function add_script()
	{

		$script_data = array(
			'module'       => $this->name,
			'file_name'    => 'add-to-cart-mini.js',
			'dependencies' => array('core', 'notifications'),
			'version'	   => $this->js_version,
			'includes'     => array( 'is_shop' )
		);

		$this->script->register_script( $script_data );
	}

	public function add_hooks()
	{

		$this->ajax_add_to_cart();
		$this->ajax_bulk_add_to_cart();
		$this->add_to_cart_banner();
		$this->notifications();

	}

	private function add_to_cart_banner() {
		$view_banner = new Webigo_View_Add_To_Cart_Footer();

		$hook_footer = array(
			'hook'     => 'wp_footer',
			'callback' => array( $view_banner, 'render' )
		);

		$this->hooks->register($hook_footer);
	}

	private function ajax_add_to_cart() : void
	{
		$webigo_woo_ajax_add_to_cart = new Webigo_Woo_Ajax_Add_To_Cart();
		$action_name            = $webigo_woo_ajax_add_to_cart->action_name();

		// Below hook is used for Authenticated Users
		$hook_wp_ajax = array(
			'hook'     => 'wp_ajax_' . $action_name,
			'callback' => array($webigo_woo_ajax_add_to_cart, 'handle_ajax_request')
		);

		$this->hooks->register($hook_wp_ajax);

		// Below hook is used for NO-Authenticated Users
		$hook_wp_ajax_nopriv = array(
			'hook'     => 'wp_ajax_nopriv_' . $action_name,
			'callback' => array($webigo_woo_ajax_add_to_cart, 'handle_ajax_request')
		);

		$this->hooks->register($hook_wp_ajax_nopriv);
	}

	private function ajax_bulk_add_to_cart() : void
	{

		$action_name = Webigo_Add_To_Cart_Settings::AJAX_ADD_TO_CART_BULK_ACTION_NAME;
		$ajax_request = new Webigo_Add_To_Cart_Ajax_Bulk_Request( $action_name );

		// Below hook is used for Authenticated Users
		$hook_wp_ajax = array(
			'hook'     => 'wp_ajax_' . $action_name,
			'callback' => array($ajax_request, 'handle_ajax_request')
		);

		$this->hooks->register($hook_wp_ajax);

		// Below hook is used for NO-Authenticated Users
		$hook_wp_ajax_nopriv = array(
			'hook'     => 'wp_ajax_nopriv_' . $action_name,
			'callback' => array($ajax_request, 'handle_ajax_request')
		);

		$this->hooks->register($hook_wp_ajax_nopriv);

	}

	private function notifications()
	{

		$view_add_to_cart_notifications = new Webigo_View_Add_To_Cart_Notifications();

		$hook_new_notifications = array(
			'hook'     => 'webigo_new_notifications',
			'callback' => array($view_add_to_cart_notifications, 'render')
		);

		$this->hooks->register($hook_new_notifications);
	}

	

}