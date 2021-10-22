<?php

require_once WEBIGO_PLUGIN_PATH . 'includes/abstract-class-webigo-module.php';

/**
* {@inheritdoc}
*/
class Webigo_Archive_Product extends Webigo_Module
{

	protected $name;

	private $style_version;

	private $js_version;

	public function __construct()
	{
		
		$this->name          = Webigo_Archive_Product_Settings::MODULE_NAME;
		$this->style_version = Webigo_Archive_Product_Settings::CSS_VERSION;
		$this->js_version    = Webigo_Archive_Product_Settings::JS_VERSION;
		
		parent::__construct();

		$this->load_dependencies();
		$this->add_shortcodes();
	}

	public function load_dependencies()
	{

		require_once WEBIGO_PLUGIN_PATH . '/modules/archive-product/includes/class-webigo-woo-archive-product-shortcode.php';

		require_once WEBIGO_PLUGIN_PATH . '/modules/core/views/class-webigo-view-buttons.php';
		
	}

	public function module()
	{

		return $this->name;
	}


	public function add_style()
	{
		$style_data = array(
			'module'       => $this->name,
			'file_name'    => 'archive-product.css',
			'dependencies' => array('core'),
			'version'      => $this->style_version,
			'includes'	   => array( 'is_shop' ),
		);

		$this->style->register_public_style($style_data);

	}

	public function add_script()
	{

		$script_data = array(
			'module'      => $this->name,
			'file_name'   => 'archive-product.js',
			'dependencies' => array('core', 'add-to-cart'),
			'version'      => $this->js_version,
			'includes'	   => array( 'is_shop' ),
		);

		$this->script->register_public_script( $script_data );
	}

	public function add_hooks()
	{

		// $view_notifications = new Webigo_View_Notifications();

		// $hook_head = array(
		// 	'hook'     => 'wp_head',
		// 	'callback' => array( $view_notifications, 'render' )
		// );

		// $this->hooks->register($hook_head);
	}

	private function add_shortcodes()
	{

		new Webigo_Woo_Archive_Product_Shortcode();
	}


}
