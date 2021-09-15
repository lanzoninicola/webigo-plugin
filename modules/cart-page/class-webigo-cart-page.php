<?php

require_once WEBIGO_PLUGIN_PATH . 'includes/abstract-class-webigo-module.php';

/**
* {@inheritdoc}
*/
class Webigo_Cart_Page extends Webigo_Module
{

	//TODO: Priority 1 - Update the cart after quantity changed
	// https://www.businessbloomer.com/woocommerce-automatically-update-cart-quantity-change/

	protected $name = 'cart-page';

	private $style_version = '1.0';

	public function __construct()
	{
		parent::__construct();
	}

	public function load_dependencies()
	{
		
		require_once WEBIGO_PLUGIN_PATH . '/modules/cart/views/class-webigo-view-woo-after-cart.php';

		require_once WEBIGO_PLUGIN_PATH . '/modules/cart/views/class-webigo-view-woo-cart-updated.php';
	}

	public function module()
	{

		return $this->name;
	}


	public function add_style()
	{
		$style_data = array(
			'module'      => $this->name,
			'file_name'   => 'cart-page.css',
			'dependencies' => array('core')
		);

		$this->style->register_public_style($style_data);

	}

	public function add_script()
	{

		/**
		 * Javascript code is not fired after the hitting the UPDATE CART button
		 */
		$script_data = array(
			'module'      => $this->name,
			'file_name'   => 'cart-page.js',
			'dependencies' => array('core'),
			'in_footer'   => true
		);

		$this->script->register_public_script( $script_data );
		
	}

	public function add_hooks()
	{

		/** Wordpress Action fired to render content after the default cart content*/
		$view_woo_after_cart = new Webigo_View_Woo_After_Cart();

		$hook_woocommerce_after_cart = array(
			'hook'     => 'woocommerce_after_cart',
			'callback' => array($view_woo_after_cart, 'render')
		);

		$this->hooks->register($hook_woocommerce_after_cart);

		/** Wordpress Action fired after hit the update cart button */
		// $view_woo_cart_updated = new Webigo_View_Woo_Cart_Updated();

		// $hook_woocommerce_update_cart_action_cart_updated = array(
		// 	'hook'     => 'woocommerce_update_cart_action_cart_updated',
		// 	'callback' => array($view_woo_cart_updated, 'render'),
		// 	'priority' => '20'
		// );

		// $this->hooks->register($hook_woocommerce_update_cart_action_cart_updated);


		
	}


}
