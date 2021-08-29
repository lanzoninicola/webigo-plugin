<?php

require_once WEBIGO_PLUGIN_PATH . 'includes/abstract-class-webigo-module.php';

class Webigo_Archive_Product extends Webigo_Module
{

	private $name = 'archive-product';

	private $style_version = '1.0';

	public function __construct()
	{
		parent::__construct();
		$this->add_style();
		$this->add_script();
	}

	public function module()
	{

		return $this->name;
	}


	public function add_style()
	{
		$style_data = array(
			'module' => $this->name,
			'file_name' => 'archive-product.css'
		);

		$this->style->register_public_style($style_data);
	}

	public function add_script()
	{

		$script_data = array(
			'module' => $this->name,
			'file_name' => 'archive-product.js'
		);

		$this->script->register_public_script($script_data);
	}
}
