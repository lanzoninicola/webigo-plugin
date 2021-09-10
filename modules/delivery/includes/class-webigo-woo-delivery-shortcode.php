<?php


class Webigo_Woo_Delivery_Shortcode
{


	public function __construct()
	{
        $this->load_dependencies();
		$this->add_shortcodes();
		
	}

	private function load_dependencies()
	{
        
		require_once WEBIGO_PLUGIN_PATH . '/modules/archive-product/views/class-webigo-view-archive-product.php';
	}

	public function add_shortcodes()
	{
        add_shortcode('delivery', array($this, 'render'));
	}

	public function render()
	{

		$view = new Webigo_View_Delivery( );

		$output = $view->render_delivery(  );

		return $output;

	}
}
