<?php



class Webigo_Woo_Product extends WC_Product_Simple {

    protected $is_bundled_item;


    public function __construct( $product ) {
        parent::__construct( $product );

        $this->is_bundled_item = false;
        $this->parents = array();

    }

    public function is_bundled_item() {

        if ( !empty( $this->parents ) ) {
            return true;
        }

    }

    public function type() {

        return $this->get_type();
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