<?php


class Webigo_Archive_Product_View {

    private $product_cats;


    public function __construct() {

        $this->product_cats = array();

        $this->load_dependencies();
    }


    private function load_dependencies() {

        // require_once WEBIGO_PLUGIN_PATH . '/modules/archive-product/includes/class-webigo-woo-product-category.php';

        require_once WEBIGO_PLUGIN_PATH . '/modules/archive-product/includes/class-webigo-woo-hzbi-product-categories.php';

        $this->product_cats = new Webigo_Woo_Hzbi_Product_Categories();

    }




    public function sayHello() {



        // $product_cat = new Webigo_Woo_Product_Category(35);
         var_dump($this->product_cats); die;
       
        
        // echo '<h1>Hello World!'. $product_cat->name() . '</h1>';
    }


}