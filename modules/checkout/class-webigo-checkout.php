<?php

require_once WEBIGO_PLUGIN_PATH . 'includes/abstract-class-webigo-module.php';

/**
* {@inheritdoc}
*/
class Webigo_Checkout extends Webigo_Module
{

	protected $name = 'checkout';

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
			'file_name'   => 'checkout.css',
			'dependencies' => array('core')
		);

		$this->style->register_public_style($style_data);

	}

	public function add_script()
	{
		
	}

	public function add_hooks()
	{

		
	}


}
