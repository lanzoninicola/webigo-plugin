<?php

require_once WEBIGO_PLUGIN_PATH . 'includes/abstract-class-webigo-module.php';

/**
* {@inheritdoc}
*/
class Webigo_Myaccount extends Webigo_Module
{

	protected $name;

	private $style_version;

	private $js_version;

	/**
	 * @var Webigo_Myaccount_Page
	 */
	private $myaccount_page;

	/**
	 * @var Webigo_View_Myaccount_Page
	 */
	private $view_myaccount_page;

	public function __construct()
	{

		$this->name          = Webigo_Myaccount_Settings::MODULE_NAME;
		$this->style_version = Webigo_Myaccount_Settings::CSS_VERSION;
		$this->js_version    = Webigo_Myaccount_Settings::JS_VERSION;

		parent::__construct();

		$this->load_dependencies();
	
	}

	public function load_dependencies()
	{
		
        require_once WEBIGO_PLUGIN_PATH . '/modules/myaccount/includes/class-webigo-myaccount-page.php';
		$this->myaccount_page = new Webigo_Myaccount_Page();

		require_once WEBIGO_PLUGIN_PATH . '/modules/myaccount/views/class-webigo-view-myaccount-page.php';
		$this->view_myaccount_page = new Webigo_View_Myaccount_Page();

		require_once WEBIGO_PLUGIN_PATH . '/modules/myaccount/views/class-webigo-login-button-shipping-screen.php';

		require_once WEBIGO_PLUGIN_PATH . '/modules/myaccount/views/class-webigo-view-login-button.php';

		require_once WEBIGO_PLUGIN_PATH . '/modules/myaccount/views/class-webigo-view-lost-password-header.php';

		
		

	}

	public function module()
	{

		return $this->name;
	}


	public function add_style()
	{
		$style_data = array(
			'module'       => $this->name,
			'file_name'    => 'myaccount.css',
			'dependencies' => array('core'),
			'version'      => $this->style_version,
			'includes'     => array( 'is_account_page' ),
		);

		$this->style->register_public_style($style_data);

	}

	public function add_script()
	{

		$script_data = array(
			'module'       => $this->name,
			'file_name'    => 'myaccount.js',
			'dependencies' => array('core'),
			'version'      => $this->js_version,
			'includes'     => array( 'is_account_page' ),
		);

		$this->script->register_public_script( $script_data );
	}

	public function add_hooks()
	{

        $this->render_custom_elements();

		$this->remove_default_navitems();

		$this->redirect_active_customers();
		
		$this->custom_template_myaccount_form_login();
		
		$this->custom_template_myaccount_onboarding();
		
		$this->custom_template_myaccount_orders();

		$this->custom_template_myaccount_view_orders();

		$this->view_login_button_on_shipping_home();

		$this->view_lost_password();
	}

	private function render_custom_elements()
	{
		$hook_before_checkout_registration_form = array(
			'hook'     => 'woocommerce_before_account_navigation',
			'callback' => array( $this->view_myaccount_page, 'render' )
		);

		$this->hooks->register( $hook_before_checkout_registration_form );
	}

	private function remove_default_navitems()
	{
		add_filter( 'woocommerce_account_menu_items', array( $this->myaccount_page , 'remove_default_navitems' ) );
	}

	private function redirect_active_customers()
	{
		$hook_parse_request = array(
			'hook'     => 'parse_request',
			'callback' => array( $this->myaccount_page, 'redirect_to_order_section' )
		);

		$this->hooks->register($hook_parse_request);
	}

	private function view_login_button_on_shipping_home()
	{
		$view_login_button = new Webigo_Login_Button_Shipping_Screen();

		$hook_home_after_shipping_options = array(
			'hook'     => 'webigo_home_after_shipping_options',
			'callback' => array( $view_login_button, 'render' )
		);

		$this->hooks->register($hook_home_after_shipping_options);
	}

	private function view_lost_password()
	{
		$view_lost_password_header = new Webigo_View_Lost_Password_Header();

		$hook_before_lost_password_form = array(
			'hook'     => 'woocommerce_before_lost_password_form',
			'callback' => array( $view_lost_password_header, 'render' )
		);

		$this->hooks->register($hook_before_lost_password_form);
	}

	private function custom_template_myaccount_form_login()
	{
		//TODO: the IF doesn't work because I'm using the same object of template handler so I only CAN enable for all or disabled for all
		// if ( Webigo_Myaccount_Settings::LOAD_MYACCOUNT_FORM_LOGIN_CUSTOM_TEMPLATE === false ) {
		// 	return;
		// }

		$this->woo_custom_template_handler->enable_custom_template();

		require_once WEBIGO_PLUGIN_PATH . '/modules/myaccount/includes/class-webigo-myaccount-template-form-login.php';
		$my_account_login = new Webigo_Myaccount_Template_Form_Login( $this->woo_custom_template_handler );
		$my_account_login->load_view_template_class();

		add_filter( 'wc_get_template', array( $my_account_login, 'template' ), 10, 3 );
	}

	private function custom_template_myaccount_onboarding()
	{
		// if ( Webigo_Myaccount_Settings::LOAD_MYACCOUNT_DASHBOARD_CUSTOM_TEMPLATE === false ) {
		// 	return;
		// }

		$this->woo_custom_template_handler->enable_custom_template();

		require_once WEBIGO_PLUGIN_PATH . '/modules/myaccount/includes/class-webigo-myaccount-template-dashboard.php';
		$my_account_dashboard = new Webigo_Myaccount_Template_Dashboard( $this->woo_custom_template_handler );
		$my_account_dashboard->load_view_template_class();

		add_filter( 'wc_get_template', array( $my_account_dashboard, 'template' ), 10, 3 );
	}

	
	private function custom_template_myaccount_orders()
	{
		// if ( Webigo_Myaccount_Settings::LOAD_MYACCOUNT_ORDERS_CUSTOM_TEMPLATE === false ) {
		// 	return;
		// }

		$this->woo_custom_template_handler->enable_custom_template();

		require_once WEBIGO_PLUGIN_PATH . '/modules/myaccount/includes/class-webigo-myaccount-template-orders.php';
		$my_account_orders = new Webigo_Myaccount_Template_Orders( $this->woo_custom_template_handler );

		add_filter( 'wc_get_template', array( $my_account_orders, 'template' ), 10, 3 );
	}

	private function custom_template_myaccount_view_orders()
	{

		// if ( Webigo_Myaccount_Settings::LOAD_MYACCOUNT_VIEW_ORDERS_CUSTOM_TEMPLATE === false ) {
		// 	return;
		// }

		$this->woo_custom_template_handler->enable_custom_template();

		require_once WEBIGO_PLUGIN_PATH . '/modules/myaccount/includes/class-webigo-myaccount-template-view-orders.php';
		$my_account_view_orders = new Webigo_Myaccount_Template_View_Orders( $this->woo_custom_template_handler );

		add_filter( 'wc_get_template', array( $my_account_view_orders, 'template' ), 10, 3 );
	}

}
