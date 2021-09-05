<?php


class Webigo_Woo_Products {

    /**
     * @var array of Webigo_Woo_Product
     * 
     * $product_collection = array( 
     *                          'product_id' => Webigo_Woo_Product,
     *                       )
     */
    private $product_collection;

    /**
     * @var array of Webigo_Woo_Product_Bundle
     * $product_bundle_collection = array( 
     *                          'product_bundle_id' => Webigo_Woo_Product_Bundle,
     *                       )
     */
    private $product_bundle_collection;

    /**
     * Merge of arrays: product_collection and product_bundle_collection
     * 
     * $all_products_collection = array(
     *                              'id#1 => 'Webigo_Woo_Product|Webigo_Woo_Product_Bundle'
     *                              'id#2 => 'Webigo_Woo_Product|Webigo_Woo_Product_Bundle'
     *                             )                
     * 
     * @var array
     */
    private $all_products_collection;


    public function __construct() {

        $this->product_collection = array();
        $this->product_bundle_collection = array();
        
        $this->load_dependencies();
    }

    
    private function load_dependencies() : void
    {

        require_once WEBIGO_PLUGIN_PATH . '/modules/archive-product/models/class-webigo-woo-product.php';

        require_once WEBIGO_PLUGIN_PATH . '/modules/archive-product/models/class-webigo-woo-product-bundle.php';
        

    }

    public function load() : void
    {
        // add_action('plugins_loaded', array($this, 'load_products'));
        $this->load_products();
        $this->add_bundle_parent_to_simple();
        $this->merge_data();
    }


    /**
     * Populates the respective product simple and product bundle collection
     * 
     * @return void
     */
    private function load_products() : void
    {

        /**
         * This method to retrieve the products. 
         * It is the same of wc_get_products() function
         * Recommended way by the Woocommerce team
         */
        $query = new WC_Product_Query( array(
            'status' => 'publish',
            'return' => 'objects',
            'limit'  => -1
        ) );

        $wc_products = $query->get_products();

        foreach ($wc_products as $wc_product) {
            $wc_product_class_name = (string) get_class($wc_product);
            $id = $wc_product->get_id();
            
            if ( $wc_product_class_name === WEBIGO_SIMPLE_PRODUCT_CLASS_NAME ) {
                $product_object = new Webigo_Woo_Product( $id );
                $product_id = (string) $product_object->id();

                

                $this->product_collection[$product_id] = $product_object;
            }

            if ( $wc_product_class_name === WEBIGO_BUNDLE_PRODUCT_CLASS_NAME ) {
                $product_bundle_object = new Webigo_Woo_Product_Bundle( $id );
                $product_bundle_id = (string) $product_bundle_object->id();

                $this->product_bundle_collection[$product_bundle_id] = $product_bundle_object;
            }
        
        }
    }


    /**
     * It populates the internal bundle_parents array for each Webigo_Woo_Product 
     * 
     * Let the Webigo_Woo_Product to know it is part of bundle and who are its parents
     * 
     * @return void
     */
    private function add_bundle_parent_to_simple() : void
    {
        // iterate the array that contains the bundle items
        foreach ( $this->product_bundle_collection as $bundle_product_id => $bundle_product_object ) {

            // for each bundle items I will get the bundled items (childs)
            // array of YITH_WC_Bundled_Item
            $bundled_items = (array) $bundle_product_object->get_bundled_items();

            // for each bundled item
            foreach ( $bundled_items as $bundled_item ){
                $bundled_item_id = (string) $bundled_item->get_product_id();
                // get the respective product simple
                $product_simple = (object) $this->get_simple_product( $bundled_item_id );

                if ( isset( $product_simple )) {
                    // to the product simple I will add the parent that correspond to the bundle item
                    $product_simple->add_bundle_parent( $bundle_product_object );
                }                
            }
        }
    }

    /**
     * Internal utility function
     * 
     * @var string ID of Product
     * @return Webigo_Woo_Product
     */
    private function get_simple_product( string $id ) : Webigo_Woo_Product
    {
        if ( !empty( $this->product_collection ) ) {
            $product = (object) $this->product_collection[$id];
        }

        if ( isset( $product ) ) {
            return $product;
        }
        
        return null;
    }

    private function merge_data() : void
    {

        foreach ( $this->product_collection as $product_id => $product_object ) {

            $this->all_products_collection[$product_id] = $product_object;

        }

        foreach ( $this->product_bundle_collection as $bundle_id => $bundle_object ) {

            $this->all_products_collection[$bundle_id] = $bundle_object;

        }

        unset( $this->product_collection );
        unset( $this->product_bundle_collection );
    }

    public function get_products() : array
    {
        return $this->all_products_collection;
    }

     /**
     * @param string The id of product
     * @return Webigo_Woo_Product|Webigo_Woo_Product_Bundle
     */
    public function get_product( string $id ) : ?object
    {
        return isset ( $this->all_products_collection[$id] ) ? $this->all_products_collection[$id] : null;
    }

}
