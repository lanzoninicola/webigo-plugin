<?php

require_once WEBIGO_PLUGIN_PATH . '/modules/archive-product/includes/class-webigo-woo-product.php';

class Webigo_Woo_Product_Bundle extends WC_Product_Yith_Bundle {

    protected $type;


    /**
     * Contains only the IDs of products that builds the bundle
     * 
     *  @var array of product_id
     */
    // protected $childs;

    public function __construct($product) {
        parent::__construct( $product );

        /**
         *  By default the YITH plugin doesn't add this support for the WC_Product_Yith_Bundle
         */
        $this->supports[] = 'ajax_add_to_cart';
        
        $this->type = 'bundle';
        $this->childs = array();

        // $this->define_bundle_childs_ids();

    }

    public function id() {

        return $this->get_id();
    }


    public function type() {

        return $this->type;
    }

    public function name() {

        return $this->get_name();

    }

    public function description() {

        return $this->get_description();
    }

    public function price() {

        return $this->get_regular_price();

    }

    public function sale_price() {

        return $this->get_sale_price();

    }

    public function final_price() {

        return $this->get_price();
    }

     /**
     * 
     * Returns the <img> html tag of image.
     * The result must be echoing.
     * 
     * @return string
     */
    public function image() {

        return $this->get_image();

    }

    
    

}