<?php

require_once WEBIGO_PLUGIN_PATH . 'includes/abstract-class-webigo-module.php';

class Webigo_Archive_Product extends Webigo_Module
{

	private $name = 'archive-product';

	private $style_version = '1.0';

	public function __construct()
	{
		parent::__construct();
		
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

	public function add_hooks() {
		
	}

	
}
