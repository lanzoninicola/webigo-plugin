<?php


class Webigo_Archive_Product_Shortcode
{

	public function __construct()
	{
        $this->load_dependencies();
	}

	private function load_dependencies()
	{

        require_once WEBIGO_PLUGIN_PATH . '/modules/archive-product/views/class-webigo-view-archive-product.php';
	}

	public function add_shortcodes()
	{

        add_shortcode('webigo_archive_product', array($this, 'render'));
	}

	private function render()
	{

		$view = new Webigo_View_Archive_Product();

		$view->render_archive_products();
	}
}
