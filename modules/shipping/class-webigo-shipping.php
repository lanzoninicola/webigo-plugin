<?php

// https://stackoverflow.com/questions/62451295/select-between-pickup-or-delivery-shipping-methods-first-in-woocommerce

require_once WEBIGO_PLUGIN_PATH . 'includes/abstract-class-webigo-module.php';

/**
* {@inheritdoc}
*/
class Webigo_Shipping extends Webigo_Module
{

	protected $name;

	private $style_version;

	private $js_version;

	public function __construct()
	{

		$this->name          = Webigo_Shipping_Settings::MODULE_NAME;
		$this->style_version = Webigo_Shipping_Settings::CSS_VERSION;
		$this->js_version    = Webigo_Shipping_Settings::JS_VERSION;

		parent::__construct();

		$this->load_dependencies();
		$this->add_shortcodes();
	}

	public function load_dependencies()
	{
		
		require_once WEBIGO_PLUGIN_PATH . '/modules/shipping/includes/class-webigo-woo-shipping-shortcode.php';

		require_once WEBIGO_PLUGIN_PATH . '/modules/shipping/includes/class-webigo-ajax-cep-verification.php';

		require_once WEBIGO_PLUGIN_PATH . '/modules/shipping/views/class-webigo-view-gotostore-button.php';

	}

	public function module()
	{

		return $this->name;
	}


	public function add_style()
	{
		$style_data = array(
			'module'       => $this->name,
			'file_name'    => 'shipping.css',
			'dependencies' => array('core'),
			'version'      => $this->style_version,
		);

		$this->style->register_public_style($style_data);

	}

	public function add_script()
	{

		$script_data = array(
			'module'       => $this->name,
			'file_name'    => 'shipping.js',
			'dependencies' => array('core', 'add-to-cart'),
			'version'      => $this->js_version,
			'in_footer'    => true
		);

		$this->script->register_script( $script_data );
	}

	public function add_hooks()
	{

        $this->cep_verification();
	}

	private function cep_verification() : void
	{
		$action_name = Webigo_Shipping_Settings::AJAX_CEP_VERIFICATION_ACTION_NAME;		
		$ajax_cep_verification = new Webigo_Shipping_Ajax_Cep_Verification( $action_name );

		// Below hook is used for Authenticated Users
		$hook_wp_ajax = array(
			'hook'     => 'wp_ajax_' . $action_name,
			'callback' => array( $ajax_cep_verification, 'handle_ajax_request' )
		);

		$this->hooks->register( $hook_wp_ajax );

		// Below hook is used for NO-Authenticated Users
		$hook_wp_ajax_nopriv = array(
			'hook'     => 'wp_ajax_nopriv_' . $action_name,
			'callback' => array( $ajax_cep_verification, 'handle_ajax_request' )
		);
		
		$this->hooks->register( $hook_wp_ajax_nopriv );

		$hook_checkout_init = array(
			'hook'     => 'woocommerce_checkout_init',
			'callback' => array( $this, 'force_checkout_shipping' )
		);
		
		$this->hooks->register( $hook_checkout_init );
	}

	private function add_shortcodes()
	{

		new Webigo_Woo_Shipping_Shortcode();
	}

	public function force_checkout_shipping() {
		
		if( ( is_checkout() && ! is_wc_endpoint_url( 'order-received' ) ) ) {
			// The session has been populated inside the response of AJAX request when the CEP was validated
			$chosen_shipping_method = array( WC()->session->get( 'wbg_chosen_shipping') );

			// if $_POST['shipping_method'] is available means the user 
			// has changed the shipping method
			if ( isset( $_POST['shipping_method'] ) === false ) {
				if ( $chosen_shipping_method[0] !== null ) {
					WC()->session->set( 'chosen_shipping_methods', $chosen_shipping_method );

					WC()->cart->calculate_totals();
				}
			}


		}
	}

	

}
