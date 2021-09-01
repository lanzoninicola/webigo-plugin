<?php

require_once WEBIGO_PLUGIN_PATH . 'includes/abstract-class-webigo-module.php';

class Webigo_Core extends Webigo_Module
{

	protected $name = 'core';

	private $style_version = '1.0';

	public function __construct()
	{
		parent::__construct();

		$this->add_shortcodes();
	}

	public function load_dependencies()
	{

	}

	public function load_views() {}


	public function module()
	{

		return $this->name;
	}


	public function add_style()
	{
		$style_data = array(
			'module' => $this->name,
			'file_name' => 'core.css'
		);

		$this->style->register_public_style($style_data);
	}

	public function add_script()
	{
	}

	public function add_hooks()
	{
	}

	private function add_shortcodes()
	{

		
	}

	public function render()
	{

	
	}
}
