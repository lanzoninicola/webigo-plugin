<?php

require_once WEBIGO_PLUGIN_PATH . 'includes/abstract-class-webigo-module.php';

/**
* {@inheritdoc}
*/
class Webigo_Checkout_PagSeguro extends Webigo_Module
{

	protected $name = 'checkout-pagseguro';

	private $style_version = '1.0';

	public function __construct()
	{
		parent::__construct();

	}

	public function load_dependencies()
	{
		
	}

	public function module()
	{

		return $this->name;
	}


	public function add_style()
	{
		$style_data = array(
			'module'      => $this->name,
			'file_name'   => 'checkout-pagseguro.css',
			'dependencies' => array('core', 'checkout')
		);

		$this->style->register_public_style($style_data);

	}

	public function add_script()
	{
		
	}

	public function add_hooks()
	{

		$hook_dequeue_pagseguro_style = array(
			'hook'     => 'wp_enqueue_scripts',
			'callback' => array( $this, 'dequeue_pagseguro_style'),
			'priority' => 11
		);

		$this->hooks->register($hook_dequeue_pagseguro_style);
	
	}

	public function dequeue_pagseguro_style() : void
	{
		wp_dequeue_style( 'pagseguro-checkout' );
	}


}
