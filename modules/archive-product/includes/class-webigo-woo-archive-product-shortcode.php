<?php


class Webigo_Woo_Archive_Product_Shortcode
{

	/**
	 * @var Webigo_Database_Facade
	 */
	private $database;

	public function __construct()
	{
        $this->load_dependencies();
		$this->load_database();
		$this->add_shortcodes();
		
	}

	private function load_dependencies()
	{
		require_once WEBIGO_PLUGIN_PATH . '/modules/archive-product/facades/class-webigo-database-facade.php';
        
		require_once WEBIGO_PLUGIN_PATH . '/modules/archive-product/views/class-webigo-view-archive-product.php';
	}

	public function load_database() 
	{
		
		$this->database = new Webigo_Database_Facade();
		$this->database->load();
	}

	public function add_shortcodes()
	{
        add_shortcode('webigo_archive_product', array($this, 'render'));
	}

	public function render()
	{

		$view = new Webigo_View_Archive_Product( );

		$output = $view->render_archive_products( $this->database );

		return $output;

	}
}
