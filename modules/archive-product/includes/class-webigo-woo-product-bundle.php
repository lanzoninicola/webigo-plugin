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

        $this->type = 'bundle';
        $this->childs = array();

        // $this->define_bundle_childs_ids();

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

    public function image() {

        return $this->get_image();

    }

}