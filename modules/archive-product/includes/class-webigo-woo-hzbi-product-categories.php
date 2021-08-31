<?php

require_once WEBIGO_PLUGIN_PATH . '/modules/archive-product/includes/class-webigo-woo-product-categories.php';

class Webigo_Woo_Hzbi_Product_Categories extends Webigo_Woo_Product_Categories {


    public function __construct() {

        parent::__construct();

    }

    protected function load_dependencies() {

        require_once WEBIGO_PLUGIN_PATH . '/modules/archive-product/includes/class-webigo-woo-hzbi-product-category.php';

        $this->category_object = new Webigo_Woo_Hzbi_Product_Category();

    }


}