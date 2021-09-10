<?php


class Webigo_Woo_Shipping_Shortcode
{


	public function __construct()
	{
        $this->load_dependencies();
		$this->add_shortcodes();
		
	}

	private function load_dependencies()
	{
        
		require_once WEBIGO_PLUGIN_PATH . '/modules/shipping/views/class-webigo-view-shipping.php';
	}

	public function add_shortcodes()
	{
        add_shortcode('delivery', array($this, 'render'));
	}

	public function render()
	{

		$view = new Webigo_View_Shipping( );

		$output = $view->render_shipping(  );

		return $output;

	}
}
