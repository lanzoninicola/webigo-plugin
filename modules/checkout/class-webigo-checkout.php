<?php

require_once WEBIGO_PLUGIN_PATH . 'includes/abstract-class-webigo-module.php';

/**
* {@inheritdoc}
*/
class Webigo_Checkout extends Webigo_Module
{

	protected $name;

	private $style_version;

	private $js_version;

	public function __construct()
	{

		$this->name          = Webigo_Checkout_Settings::MODULE_NAME;
		$this->style_version = Webigo_Checkout_Settings::CSS_VERSION;
		$this->js_version    = Webigo_Checkout_Settings::JS_VERSION;
		
		parent::__construct();

	}

	public function load_dependencies()
	{
		require_once WEBIGO_PLUGIN_PATH . '/modules/checkout/includes/class-webigo-checkout-fields.php';

		require_once WEBIGO_PLUGIN_PATH . '/modules/checkout/views/class-webigo-view-before-checkout-registration.php';
		require_once WEBIGO_PLUGIN_PATH . '/modules/checkout/views/class-webigo-view-before-checkout-form.php';
		require_once WEBIGO_PLUGIN_PATH . '/modules/checkout/views/class-webigo-view-checkout-store-contacts.php';
		require_once WEBIGO_PLUGIN_PATH . '/modules/checkout/views/class-webigo-view-thank-you-page-intro.php';
		require_once WEBIGO_PLUGIN_PATH . '/modules/checkout/views/class-webigo-view-thank-you-page-footer.php';
		
	}

	public function module()
	{

		return $this->name;
	}


	public function add_style()
	{
		$style_data = array(
			'module'       => $this->name,
			'file_name'    => 'checkout.css',
			'dependencies' => array('core'),
			'version'      => $this->style_version,
			'includes'	   => array( 'is_checkout '),
		);

		$this->style->register_public_style($style_data);

		$pagseguro = array(
			'module'      => $this->name,
			'file_name'   => 'pagseguro.css',
			'dependencies' => array('core'),
			'includes'	   => array( 'is_checkout '),
		);

		$this->style->register_public_style($pagseguro);

	}

	public function add_script()
	{

		$script_data = array(
			'module'       => $this->name,
			'file_name'    => 'checkout.js',
			'dependencies' => array('core'),
			'version'      => $this->js_version,
			'includes'	   => array( 'is_checkout '),
		);

		$this->script->register_public_script( $script_data );
		
	}

	public function add_hooks()
	{

		$this->hooks_checkout_page();

		$this->filters_checkout_page();

		$this->hooks_shared();

		$this->hooks_thankyou_page();



	
	}


	private function hooks_checkout_page()
	{

		$view_before_checkout_registration = new Webigo_View_Before_Checkout_Registration();

		$hook_before_checkout_registration_form = array(
			'hook'     => 'woocommerce_before_checkout_registration_form',
			'callback' => array( $view_before_checkout_registration, 'render' )
		);

		$this->hooks->register( $hook_before_checkout_registration_form );

		$view_before_checkout_form = new Webigo_View_Before_Checkout_Form();

		$hook_before_checkout_form = array(
			'hook'     => 'woocommerce_before_checkout_form',
			'callback' => array( $view_before_checkout_form, 'render' )
		);

		$this->hooks->register( $hook_before_checkout_form );

		

	}

	private function filters_checkout_page()
	{

		$checkout_fields = new Webigo_Checkout_Fields();

		add_filter( 'woocommerce_billing_fields', array( $checkout_fields, 'checkout_billing_fields' ), 11 );
		// add_filter( 'woocommerce_shipping_fields', array( $checkout_fields, 'checkout_shipping_fields' ), 10 );
	}

	private function hooks_shared()
	{
		$view_checkout_store_contacts = new Webigo_View_Checkout_Store_Contacts();

		$hook_before_payment = array(
			'hook'     => 'woocommerce_review_order_before_payment',
			'callback' => array( $view_checkout_store_contacts, 'render' )
		);

		$this->hooks->register( $hook_before_payment );

		$hook_thankyou = array(
			'hook'     => 'woocommerce_thankyou',
			'callback' => array( $view_checkout_store_contacts, 'render' )
		);

		$this->hooks->register( $hook_thankyou );
	}


	private function hooks_thankyou_page()
	{

		$view_before_thank_you = new Webigo_View_Thank_You_Page_Intro();

		$hook_before_thank_you = array(
			'hook'     => 'woocommerce_before_thankyou',
			'callback' => array( $view_before_thank_you, 'render' )
		);

		$this->hooks->register( $hook_before_thank_you );


		$view_thank_you_footer = new Webigo_View_Thank_You_Page_Footer();

		$hook_thankyou_footer = array(
			'hook'     => 'woocommerce_thankyou',
			'callback' => array( $view_thank_you_footer, 'render' )
		);

		$this->hooks->register( $hook_thankyou_footer );

	}


}
