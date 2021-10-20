<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://webigo.com.br
 * @since      1.0.0
 *
 * @package    Webigo
 * @subpackage Webigo/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Webigo
 * @subpackage Webigo/includes
 * @author     Lanzoni Nicola <lanzoni.nicola@gmail.com>
 */
class Webigo
{
	/**
	 * The modules that's responsible for maintaining and registering all plugin modules.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Webigo_Modules_Register    $modules_register    Maintains and registers all modules for the plugin.
	 */
	protected $modules_register;


	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Webigo_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct()
	{
		if (defined('WEBIGO_VERSION')) {
			$this->version = WEBIGO_VERSION;
		} else {
			$this->version = '1.0.0';
		}

		if (defined('PLUGIN_NAME')) {
			$this->plugin_name = PLUGIN_NAME;
		} else {
			$this->plugin_name = 'webigo';
		}
		
		
		$this->load_dependencies();
		$this->set_locale();
	
		$this->load_modules_registry();
		$this->add_modules();
		// $this->register_module_dependencies();
		$this->load_modules();
		/**
		 * Here the plugin manages also the actions hooks for the AJAX request, 
		 * these requests are handled on ADMIN side, then this calls must be external of !is_admin() condition
		 */
		$this->define_hooks();

		/**
		 * Styles and scripts are loaded only on front-end side
		 * //TODO: criar specific methods for admin
		 */
		if ( !is_admin() ) {
			$this->define_styles();
			$this->define_scripts();
			$this->define_data_for_scripts();
		}

		
		
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Webigo_Loader. Orchestrates the hooks of the plugin.
	 * - Webigo_i18n. Defines internationalization functionality.
	 * - Webigo_Admin. Defines all hooks for the admin area.
	 * - Webigo_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies()
	{

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path(__DIR__) . 'includes/class-webigo-loader.php';

		$this->loader = new Webigo_Loader();

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path(__DIR__) . 'includes/class-webigo-i18n.php';

		
	}

	private function load_modules_registry()
	{
		/**
		 * The class responsible for orchestrating the modules
		 * of the plugin.
		 */
		require_once plugin_dir_path(__DIR__) . 'includes/class-webigo-modules-registry.php';

		$this->modules_registry = new Webigo_Modules_Registry();

		$this->modules_registry->init();
	}

	private function add_modules()
	{
	
		// TODO: adding conditionally load related to the page
		/**
		 * I cannot do here because the modules are loaded before
		 * the conditional page function are executed.
		 * 
		 * I should move the js and css loading in a class that 
		 * it will instantiate after
		 */

		require_once WEBIGO_PLUGIN_PATH . '/modules/core/settings/class-webigo-core-settings.php';
		
		$this->modules_registry->register(
			Webigo_Core_Settings::MODULE_NAME,
			Webigo_Core_Settings::BOOTSTRAP_CLASS_NAME,
			Webigo_Core_Settings::MODULE_FOLDER,
			Webigo_Core_Settings::BOOTSTRAP_CLASS_FILENAME,
		);

		require_once WEBIGO_PLUGIN_PATH . '/modules/notifications/settings/class-webigo-notifications-settings.php';
		
		$this->modules_registry->register(
			Webigo_Notifications_Settings::MODULE_NAME,
			Webigo_Notifications_Settings::BOOTSTRAP_CLASS_NAME,
			Webigo_Notifications_Settings::MODULE_FOLDER,
			Webigo_Notifications_Settings::BOOTSTRAP_CLASS_FILENAME,
		);

		require_once WEBIGO_PLUGIN_PATH . '/modules/archive-product/settings/class-webigo-archive-product-settings.php';

		$this->modules_registry->register(
			Webigo_Archive_Product_Settings::MODULE_NAME,
			Webigo_Archive_Product_Settings::BOOTSTRAP_CLASS_NAME,
			Webigo_Archive_Product_Settings::MODULE_FOLDER,
			Webigo_Archive_Product_Settings::BOOTSTRAP_CLASS_FILENAME,
		);

		require_once WEBIGO_PLUGIN_PATH . '/modules/add-to-cart/settings/class-webigo-add-to-cart-settings.php';

		$this->modules_registry->register(
			Webigo_Add_To_Cart_Settings::MODULE_NAME,
			Webigo_Add_To_Cart_Settings::BOOTSTRAP_CLASS_NAME,
			Webigo_Add_To_Cart_Settings::MODULE_FOLDER,
			Webigo_Add_To_Cart_Settings::BOOTSTRAP_CLASS_FILENAME,
		);

		$this->modules_registry->register(
			'cart-bundle-products',
			'Webigo_Cart_Bundle_Products',
			'cart-bundle-products',
			'class-webigo-cart-bundle-products.php'
		);

		require_once WEBIGO_PLUGIN_PATH . '/modules/shipping/settings/class-webigo-shipping-settings.php';

		$this->modules_registry->register(
			Webigo_Shipping_Settings::MODULE_NAME,
			Webigo_Shipping_Settings::BOOTSTRAP_CLASS_NAME,
			Webigo_Shipping_Settings::MODULE_FOLDER,
			Webigo_Shipping_Settings::BOOTSTRAP_CLASS_FILENAME,
		);

		/*
		$this->modules_registry->register(
			'shipping-banner',
			'Webigo_Shipping_Banner',
			'shipping-banner',
			'class-webigo-shipping-banner.php'
		);
		*/

		/*
		* Since the problem with the UPDATE CART
		* https://forum.bricksbuilder.io/t/urgent-woocommerce-cart-after-update-the-cart-the-products-images-disappear/995
		* I decided to use the cart widget of Bricks team to manage the cart
		$this->modules_registry->register(
			'cart-page',
			'Webigo_Cart_Page',
			'cart-page',
			'class-webigo-cart-page.php'
		);
		 */

		require_once WEBIGO_PLUGIN_PATH . '/modules/cart-widget/settings/class-webigo-cart-widget-settings.php';
		 /**
		  * Here the cupouns are not managed
		  */
		$this->modules_registry->register(
			Webigo_Cart_Widget_Settings::MODULE_NAME,
			Webigo_Cart_Widget_Settings::BOOTSTRAP_CLASS_NAME,
			Webigo_Cart_Widget_Settings::MODULE_FOLDER,
			Webigo_Cart_Widget_Settings::BOOTSTRAP_CLASS_FILENAME,
		);

		$this->modules_registry->register(
			'checkout-pagseguro',
			'Webigo_Checkout_Pagseguro',
			'checkout-pagseguro',
			'class-webigo-checkout-pagseguro.php'
		);

		require_once WEBIGO_PLUGIN_PATH . '/modules/checkout/settings/class-webigo-checkout-settings.php';

		$this->modules_registry->register(
			Webigo_Checkout_Settings::MODULE_NAME,
			Webigo_Checkout_Settings::BOOTSTRAP_CLASS_NAME,
			Webigo_Checkout_Settings::MODULE_FOLDER,
			Webigo_Checkout_Settings::BOOTSTRAP_CLASS_FILENAME,
		);

		require_once WEBIGO_PLUGIN_PATH . '/modules/widgets/settings/class-webigo-widgets-settings.php';

		$this->modules_registry->register(
			Webigo_Widgets_Settings::MODULE_NAME,
			Webigo_Widgets_Settings::BOOTSTRAP_CLASS_NAME,
			Webigo_Widgets_Settings::MODULE_FOLDER,
			Webigo_Widgets_Settings::BOOTSTRAP_CLASS_FILENAME,
		);

		require_once WEBIGO_PLUGIN_PATH . '/modules/myaccount/settings/class-webigo-myaccount-settings.php';

		$this->modules_registry->register(
			Webigo_Myaccount_Settings::MODULE_NAME,
			Webigo_Myaccount_Settings::BOOTSTRAP_CLASS_NAME,
			Webigo_Myaccount_Settings::MODULE_FOLDER,
			Webigo_Myaccount_Settings::BOOTSTRAP_CLASS_FILENAME,
		);

		require_once WEBIGO_PLUGIN_PATH . '/modules/orders/settings/class-webigo-orders-settings.php';

		$this->modules_registry->register(
			Webigo_Orders_Settings::MODULE_NAME,
			Webigo_Orders_Settings::BOOTSTRAP_CLASS_NAME,
			Webigo_Orders_Settings::MODULE_FOLDER,
			Webigo_Orders_Settings::BOOTSTRAP_CLASS_FILENAME,
		);

	
	}

	// private function register_module_dependencies() {

	// 	// TODO: modify - array of each module with its dependencies
	// 	// module1 => array(dep1, dep2, dep3)
	// 	// module2 => array(dep1, dep2, dep3)
	// 	$dependencies = array( 'core', 'archive-product' );

	// 	$this->modules_registry->define_module_dependencies( $dependencies );

	// }

	private function load_modules()
	{

		$this->modules_registry->load();
	}

	private function define_styles()
	{

		$module_registered = $this->modules_registry->get_registered_modules();

		foreach ($module_registered as $module_obj_instance) {

			$action_name   = $module_obj_instance->style->action_name();
			$style_object  = $module_obj_instance->style;
			$callback_name = $module_obj_instance->style->callback_name();

			// TODO: if multiple styles is added the code breaks here
			$this->loader->add_action($action_name, $style_object, $callback_name);
		}
	}


	private function define_scripts()
	{

		$module_registered = $this->modules_registry->get_registered_modules();

		foreach ($module_registered as $module_obj_instance) {

			$action_name   = $module_obj_instance->script->action_name();
			$style_object  = $module_obj_instance->script;
			$callback_name = $module_obj_instance->script->callback_name();

			$this->loader->add_action($action_name, $style_object, $callback_name);
		}
	}

	
	/**
	 * From the Wordpress documentation : 
	 * IMPORTANT! wp_localize_script() MUST be called after the script has been registered using wp_register_script() or wp_enqueue_script().
	 * 
	 * So I must run this function hooked to wp_footer hook
	 */
	private function define_data_for_scripts()
	{

		$module_registered = $this->modules_registry->get_registered_modules();

		foreach ( $module_registered as $module_obj_instance ) {

			$action_name      = $module_obj_instance->localize_script->action_name();
			$localize_object  = $module_obj_instance->localize_script;
			$callback_name    = $module_obj_instance->localize_script->callback_name();
			
			$this->loader->add_action( $action_name, $localize_object, $callback_name );
		}
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_hooks()
	{

		$module_registered = $this->modules_registry->get_registered_modules();
		// for each modules I let available the related actions

		foreach ( $module_registered as $module_name => $module_obj_instance ) {

			$module_hooks = $module_obj_instance->hooks->hooks();

			foreach ( $module_hooks as $hook => $hook_data ) {

				if ( $hook_data['callback'][0] === false ) {
					throw new Exception('Class Webigo->define_hooks(). Action callback object is not defined. Check in the module ' . $module_name . ' the registration of ' . $module_hooks . ' hook');
				}

				if ( $hook_data['callback'][1] === false ) {
					throw new Exception('Class Webigo->define_hooks(). Action callback method is not defined. Check in the module ' . $module_name . ' the registration of ' . $module_hooks . ' hook');
				}

				$this->loader->add_action( $hook_data['name'], $hook_data['callback'][0], $hook_data['callback'][1], $hook_data['priority'], $hook_data['accepted_args'] );
				
			}
		}

	}



	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Webigo_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale()
	{

		$plugin_i18n = new Webigo_i18n();

		$this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run()
	{
		$this->loader->run();
		
	}




	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name()
	{
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Webigo_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader()
	{
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version()
	{
		return $this->version;
	}
}
