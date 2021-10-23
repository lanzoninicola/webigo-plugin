<?php

require_once WEBIGO_PLUGIN_PATH . 'includes/abstract-class-webigo-module.php';

/**
* {@inheritdoc}
*/
class Webigo_Cart_Widget extends Webigo_Module
{

	protected $name;

	private $style_version;

	private $js_version;

	/**
	 * Here the coupons are not managed
	 */
	public function __construct()
	{
		$this->name          = Webigo_Cart_Widget_Settings::MODULE_NAME;
		$this->style_version = Webigo_Cart_Widget_Settings::CSS_VERSION;
		$this->js_version    = Webigo_Cart_Widget_Settings::JS_VERSION;

		parent::__construct();
	}

	public function load_dependencies()
	{
		require_once WEBIGO_PLUGIN_PATH . '/modules/cart-widget/includes/class-webigo-woo-minicart.php';

		require_once WEBIGO_PLUGIN_PATH . '/modules/cart-widget/includes/class-webigo-skip-cart-page.php';

		require_once WEBIGO_PLUGIN_PATH . '/modules/cart-widget/views/class-webigo-view-minicart-close-button.php';
	}

	public function module()
	{

		return $this->name;
	}


	public function add_style()
	{
		$style_data = array(
			'module'       => $this->name,
			'file_name'    => 'cart-widget.css',
			'dependencies' => array('core'),
			'version'	   => $this->style_version,
			'includes'	   => array( 'is_shop' ),
		);

		$this->style->register_style($style_data);

	}

	public function add_script()
	{

		/**
		 * Javascript code is not fired after the hitting the UPDATE CART button
		 */
		$script_data = array(
			'module'       => $this->name,
			'file_name'    => 'cart-widget.js',
			'dependencies' => array('core', 'archive-product'),
			'version'	   => $this->js_version,
			'includes'	   => array( 'is_shop' ),
		);

		$this->script->register_script( $script_data );
		
	}

	public function add_hooks()
	{

		$minicar_close_button = new Webigo_View_Minicart_Close_Button();

		$hook_footer = array(
			'hook'     => 'woocommerce_widget_shopping_cart_after_buttons',
			'callback' => array( $minicar_close_button, 'render' )
		);

		$this->hooks->register($hook_footer);

		// TODO: make this a plugin options
		$skip_cart_page = new Webigo_Skip_Cart_page();

		$hook_template_redirect = array(
			'hook'     => 'template_redirect',
			'callback' => array( $skip_cart_page, 'redirect' )
		);

		$this->hooks->register($hook_template_redirect);


		//TODO: manage the filters in the GLOBAL PLUGIN HOOKS CLASS

		$mini_cart = new Webigo_Woo_Minicart();

		/**
		 * The plugin YITH hooked with priority 10
		 */
		add_filter('woocommerce_widget_cart_item_visible', array( $mini_cart, 'hide_bundled_items' ), 11, 3);

		add_filter('woocommerce_cart_item_permalink', array( $mini_cart, 'remove_product_link' ), 11, 3);

		add_filter( 'woocommerce_cart_item_remove_link', array( $mini_cart, 'replace_icon_remove_link' ), 11, 2 );
				
	}

	


}
