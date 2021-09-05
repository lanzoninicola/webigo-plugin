<?php

require_once WEBIGO_PLUGIN_PATH . '/modules/archive-product/models/class-webigo-woo-products-categories.php';

class Webigo_Woo_Products_Categories_Hzbi extends Webigo_Woo_Products_Categories {

    /**
     * 
     * @return array of Webigo_Woo_Product|Webigo_Woo_Product_Bundle
     */

    public function get_products_category( string $category_id ) : array 
    {
        $result = array();

        $wc_products = $this->get_wc_products_category( $category_id );

        foreach ( $wc_products as $wc_product ) {

            $wc_product_id = $wc_product->get_id();

            $product = $this->products->get_product( $wc_product_id );

            array_push( $result, $product );
        }

        return $result;
    }


    

}