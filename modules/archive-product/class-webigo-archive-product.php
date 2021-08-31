<?php

require_once WEBIGO_PLUGIN_PATH . 'includes/abstract-class-webigo-module.php';

class Webigo_Archive_Product extends Webigo_Module
{

	protected $name = 'archive-product';

	private $style_version = '1.0';

	public function __construct()
	{
		parent::__construct();
		
		$this->add_shortcodes();

	}

	public function load_dependencies() {

	}

	public function load_views() {
		
		require_once $this->views_path . 'class-webigo-archive-product-view.php';

	}

	public function module()
	{

		return $this->name;
	}


	public function add_style()
	{
	}

	public function add_script()
	{
	}

	public function add_hooks()
	{
	}

	private function add_shortcodes() {

		add_shortcode('webigo_product_archive', array($this, 'render'));

	}

	public function render() {

		$view = new Webigo_Archive_Product_View();

		$view->sayHello();


	}
}
