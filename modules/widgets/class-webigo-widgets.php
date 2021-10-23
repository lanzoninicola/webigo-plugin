<?php

require_once WEBIGO_PLUGIN_PATH . 'includes/abstract-class-webigo-module.php';

/**
* {@inheritdoc}
*/
class Webigo_Widgets extends Webigo_Module
{

	protected $name;

	private $style_version;

	private $js_version;

	public function __construct()
	{
		$this->name          = Webigo_Widgets_Settings::MODULE_NAME;
		$this->style_version = Webigo_Widgets_Settings::CSS_VERSION;
		$this->js_version    = Webigo_Widgets_Settings::JS_VERSION;

		parent::__construct();
		$this->load_dependencies();
		$this->add_shortcodes();
	}

	public function load_dependencies()
	{

		require_once WEBIGO_PLUGIN_PATH . '/modules/widgets/includes/class-webigo-store-contacts-banner-shortcode.php';
		require_once WEBIGO_PLUGIN_PATH . '/modules/widgets/includes/class-webigo-header-whatsapp-shortcode.php';

	}

	public function module()
	{

		return $this->name;
	}


	public function add_style()
	{
		$style_data = array(
			'module'       => $this->name,
			'file_name'    => 'widgets.css',
			'dependencies' => array('core'),
			'version'	   => $this->style_version,
		);

		$this->style->register_style($style_data);

	}

	public function add_script()
	{

		$script_data = array(
			'module'       => $this->name,
			'file_name'    => 'widgets.js',
			'dependencies' => array('core'),
			'version'	   => $this->js_version,
			'in_footer'    => true
		);

		$this->script->register_script( $script_data );
	}

	public function add_hooks()
	{
        
	}

	private function add_shortcodes()
	{

		new Webigo_Store_Contacts_Banner_Shortcode();
		new Webigo_Header_Whatsapp_Shortcode();
	}

}
