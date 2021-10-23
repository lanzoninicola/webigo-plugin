<?php

// https://stackoverflow.com/questions/62451295/select-between-pickup-or-delivery-shipping-methods-first-in-woocommerce

require_once WEBIGO_PLUGIN_PATH . 'includes/abstract-class-webigo-module.php';

/**
* {@inheritdoc}
*/
class Webigo_Notifications extends Webigo_Module
{

	// TODO: JS code/PHP code not manage multiple notifications (hide or show fn)
	// TODO: managing parameters js side
	// possible solution, when the app is loaded will downloads
	// json with configs
	protected $name;

	private $style_version;

	private $js_version;

	public function __construct()
	{

		$this->name          = Webigo_Notifications_Settings::MODULE_NAME;
		$this->style_version = Webigo_Notifications_Settings::CSS_VERSION;
		$this->js_version    = Webigo_Notifications_Settings::JS_VERSION;

		parent::__construct();

		$this->load_dependencies();
		
	}

	public function load_dependencies()
	{
		
		require_once WEBIGO_PLUGIN_PATH . '/modules/notifications/views/class-webigo-view-notifications.php';
	}

	public function module()
	{

		return $this->name;
	}


	public function add_style()
	{
		$style_data = array(
			'module'       => $this->name,
			'file_name'    => 'notifications.css',
			'dependencies' => array('core'),
			'version'      => $this->style_version,
		);

		$this->style->register_public_style($style_data);

	}

	public function add_script()
	{

		$script_data = array(
			'module'       => $this->name,
			'file_name'    => 'notifications.js',
			'dependencies' => array('core'),
			'version'      => $this->js_version,
			'in_footer'    => true
		);

		$this->script->register_script( $script_data );
	}

	public function add_hooks()
	{
		$view_notifications = new Webigo_View_Notifications();

		$hook_head = array(
			'hook'     => 'wp_head',
			'callback' => array( $view_notifications, 'render' )
		);

		$this->hooks->register($hook_head);
	}



}
