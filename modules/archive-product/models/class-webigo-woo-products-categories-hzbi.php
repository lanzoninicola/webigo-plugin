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

        $wc_products = (array) $this->get_wc_products_category( $category_id );

        foreach ( $wc_products as $wc_product ) {

            $wc_product_id = $wc_product->get_id();

            $product = (object) $this->products->get_product( $wc_product_id );

            if ( $product->type() === 'simple' && $product->is_bundled_item() ) {

                $bundle_parents = $product->get_bundle_parents();

                $_result = array();

                foreach ( $bundle_parents as $bundle_parent ) {
                    
                    if ( ! isset( $_result[$bundle_parent->id()] ) ) {
                        /**
                         * Two products of the same category can bundled in the same bundle item,
                         * here I can remove the duplicate.
                         */
                        $result[$bundle_parent->id()] = $bundle_parent;
                    }
                }
            }
        }

        return $result;
    }


    

}