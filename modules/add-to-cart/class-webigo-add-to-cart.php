<?php

require_once WEBIGO_PLUGIN_PATH . 'includes/abstract-class-webigo-module.php';

/**
* {@inheritdoc}
*/
class Webigo_Add_To_Cart extends Webigo_Module
{

	protected $name = 'add-to-cart';

	private $style_version = '1.0';

	public function __construct()
	{
		parent::__construct();

	}

	public function load_dependencies()
	{
		require_once WEBIGO_PLUGIN_PATH . '/modules/add-to-cart/includes/class-webigo-woo-add-to-cart.php';
		require_once WEBIGO_PLUGIN_PATH . '/modules/add-to-cart/includes/class-webigo-woo-ajax-add-to-cart.php';

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
			'module'      => $this->name,
			'file_name'   => 'add-to-cart.css',
			'dependencies' => array('core')
		);

		$this->style->register_public_style($style_data);
	}

	public function add_script()
	{

		$script_data = array(
			'module'      => $this->name,
			'file_name'   => 'add-to-cart.js',
			'dependencies' => array('core'),
			'in_footer'   => true
		);

		$this->script->register_public_script( $script_data );
	}

	public function add_hooks()
	{

		$this->add_to_cart_hooks();

	}

	private function add_to_cart_hooks() 
	{

		$webigo_woo_ajax_add_to_cart = new Webigo_Woo_Ajax_Add_To_Cart();
		$action_name            = $webigo_woo_ajax_add_to_cart->action_name();

		// Below hook is used for Authenticated Users
		$hook_wp_ajax = array(
			'hook'     => 'wp_ajax_' . $action_name,
			'callback' => array($webigo_woo_ajax_add_to_cart, 'ajax_add_to_cart')
		);

		$this->hooks->register($hook_wp_ajax);

		// Below hook is used for NO-Authenticated Users
		$hook_wp_ajax_nopriv = array(
			'hook'     => 'wp_ajax_nopriv_' . $action_name,
			'callback' => array($webigo_woo_ajax_add_to_cart, 'ajax_add_to_cart')
		);

		$this->hooks->register($hook_wp_ajax_nopriv);

	}

}
