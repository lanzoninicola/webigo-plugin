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
		$this->add_modules();
		$this->register_module_dependencies();
		$this->load_modules();
		$this->set_locale();
		$this->define_styles();
		$this->define_scripts();
		$this->define_hooks();
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

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path(__DIR__) . 'includes/class-webigo-i18n.php';

		/**
		 * The class responsible for orchestrating the modules
		 * of the plugin.
		 */
		require_once plugin_dir_path(__DIR__) . 'includes/class-webigo-modules-registry.php';

		$this->modules_registry = new Webigo_Modules_Registry();

		$this->modules_registry->init();

		$this->loader = new Webigo_Loader();
	}

	private function add_modules()
	{
	
		$this->modules_registry->register(
			'core',
			'Webigo_Core',
			'core',
			'class-webigo-core.php'
		);

		$this->modules_registry->register(
			'archive-product',
			'Webigo_Archive_Product',
			'archive-product',
			'class-webigo-archive-product.php'
		);

		$this->modules_registry->register(
			'add-to-cart',
			'Webigo_Add_To_Cart',
			'add-to-cart',
			'class-webigo-add-to-cart.php'
		);

		$this->modules_registry->register(
			'cart-bundle-products',
			'Webigo_Cart_Bundle_Products',
			'cart-bundle-products',
			'class-webigo-cart-bundle-products.php'
		);
		
	}

	private function register_module_dependencies() {

		// TODO: modify - array of each module with its dependencies
		// module1 => array(dep1, dep2, dep3)
		// module2 => array(dep1, dep2, dep3)
		$dependencies = array( 'core', 'archive-product' );

		$this->modules_registry->define_module_dependencies( $dependencies );

	}

	private function load_modules()
	{

		$this->modules_registry->load();
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


	private function define_styles()
	{

		$module_registered = $this->modules_registry->get_registered_modules();

		foreach ($module_registered as $module_obj_instance) {

			$action_name = $module_obj_instance->style->action_name();
			$style_object = $module_obj_instance->style;
			$callback_name = $module_obj_instance->style->callback_name();

			// TODO: if multiple styles is added the code breaks here
			$this->loader->add_action($action_name, $style_object, $callback_name);
		}
	}


	private function define_scripts()
	{

		$module_registered = $this->modules_registry->get_registered_modules();

		foreach ($module_registered as $module_obj_instance) {

			$action_name = $module_obj_instance->script->action_name();
			$style_object = $module_obj_instance->script;
			$callback_name = $module_obj_instance->script->callback_name();

			$this->loader->add_action($action_name, $style_object, $callback_name);
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
