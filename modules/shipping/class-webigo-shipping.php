<?php

require_once WEBIGO_PLUGIN_PATH . 'includes/abstract-class-webigo-module.php';

/**
* {@inheritdoc}
*/
class Webigo_Shipping extends Webigo_Module
{

	protected $name = 'shipping';

	private $style_version = '1.0';

	public function __construct()
	{
		parent::__construct();

		$this->load_dependencies();
		$this->add_shortcodes();
	}

	public function load_dependencies()
	{
		
		require_once WEBIGO_PLUGIN_PATH . '/modules/shipping/includes/class-webigo-shipping-settings.php';

		require_once WEBIGO_PLUGIN_PATH . '/modules/shipping/includes/class-webigo-woo-shipping-shortcode.php';

		require_once WEBIGO_PLUGIN_PATH . '/modules/shipping/includes/class-webigo-ajax-cep-verification.php';

	}

	public function module()
	{

		return $this->name;
	}


	public function add_style()
	{
		$style_data = array(
			'module'      => $this->name,
			'file_name'   => 'shipping.css',
			'dependencies' => array('core')
		);

		$this->style->register_public_style($style_data);

	}

	public function add_script()
	{

		$script_data = array(
			'module'      => $this->name,
			'file_name'   => 'shipping.js',
			'dependencies' => array('core', 'add-to-cart'),
			'in_footer'   => true
		);

		$this->script->register_public_script( $script_data );
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
	}

	private function add_shortcodes()
	{

		new Webigo_Woo_Shipping_Shortcode();
	}

}
