<?php

/**
 * 
 * Here is managed the particular requirements of a client related
 * to products/categories
 * 
 */

class Webigo_Woo_Products_Categories_Facade {

    /**
     * @var Webigo_Products
     */
    private $products;

    /**
     * @var Webigo_Products_Categories
     */
    private $categories;


    public function __construct()
    {
        $this->load_dependencies();
    }

    /**
     * @param Webigo_Products
     * @param Webigo_Products_Categories
     */
    public function load( object $products, object $categories ) : void
    {
        $this->products = $products;
        $this->categories = $categories;

        $this->products_categories = new Webigo_Woo_Products_Categories( $this->products, $this->categories );
        $this->products_categories_hzbi = new Webigo_Woo_Products_Categories_Hzbi ( $this->products, $this->categories );
    }

    private function load_dependencies()
    {
        require_once WEBIGO_PLUGIN_PATH . '/modules/archive-product/models/class-webigo-woo-products-categories-hzbi.php';
        require_once WEBIGO_PLUGIN_PATH . '/modules/archive-product/models/class-webigo-woo-products-categories.php';
    }

    public function get_products_category( string $category_id ) : array
    {

        $standard_products_cats = $this->get_standard_product_cats( $category_id );

        $hazbier_products_cats = $this->get_hazbier_product_cats( $category_id );

        $result = array_merge( $standard_products_cats, $hazbier_products_cats );

        return isset( $result ) ? $result : array();
    }


    private function get_standard_product_cats( $category_id ) : array
    {

        $product_cats = $this->products_categories->get_products_category( $category_id );

        return isset( $product_cats) ? $product_cats : array();
    }

    private function get_hazbier_product_cats( $category_id ) : array
    {

        $product_cats = $this->products_categories_hzbi->get_products_category( $category_id );

        return isset( $product_cats) ? $product_cats : array();
    }

   


}