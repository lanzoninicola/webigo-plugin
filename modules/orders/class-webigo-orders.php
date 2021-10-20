<?php

// https://stackoverflow.com/questions/62451295/select-between-pickup-or-delivery-shipping-methods-first-in-woocommerce

require_once WEBIGO_PLUGIN_PATH . 'includes/abstract-class-webigo-module.php';

/**
* {@inheritdoc}
*/
class Webigo_Orders extends Webigo_Module
{

	protected $name;

	private $style_version;

	private $js_version;

	public function __construct()
	{

		$this->name          = Webigo_Orders_Settings::MODULE_NAME;
		$this->style_version = Webigo_Orders_Settings::CSS_VERSION;
		$this->js_version    = Webigo_Orders_Settings::JS_VERSION;

		parent::__construct();

		$this->load_dependencies();
	}

	public function load_dependencies()
	{

		require_once WEBIGO_PLUGIN_PATH . '/modules/orders/includes/class-webigo-order-status.php';

	}

	public function module()
	{

		return $this->name;
	}


	public function add_style()
	{
		$style_data = array(
			'module'       => $this->name,
			'file_name'    => 'orders.css',
			'dependencies' => array('core'),
			'version'      => $this->style_version,
		);

		$this->style->register_public_style($style_data);

	}

	public function add_script()
	{

		$script_data = array(
			'module'       => $this->name,
			'file_name'    => 'orders.js',
			'dependencies' => array('core'),
			'version'      => $this->js_version,
			'in_footer'    => true
		);

		$this->script->register_public_script( $script_data );
	}

	public function add_hooks()
	{
		$this->add_order_status_entregando();
	
	}

	private function add_order_status_entregando()
	{
		$order_status = new Webigo_Order_Status();

		// THIS HOOK MUST BE IN PRIORITY 11 (AT 10 START THE PLUGIN)
		$hook_init = array(
			'hook'     => 'init',
			'callback' => array( $order_status, 'register_entregando' ),
			'priority' => '11'
		);

		$this->hooks->register( $hook_init );


		add_filter( 'wc_order_statuses', array( $order_status , 'entregando' ) );

		add_filter( 'bulk_actions-edit-shop_order', array( $order_status, 'custom_dropdown_bulk_actions_shop_order'), 20, 1 );
	}


}
