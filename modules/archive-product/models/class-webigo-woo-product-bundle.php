<?php


class Webigo_Woo_Product_Bundle extends WC_Product_Yith_Bundle {

    protected $type;

    private $bundle_parents;

    public function __construct($product) {
        parent::__construct( $product );

        /**
         *  By default the YITH plugin doesn't add this support for the WC_Product_Yith_Bundle
         */
        $this->supports[] = 'ajax_add_to_cart';

        $this->type = 'bundle';
    }

     /**
     * @return string
     */
    public function id() : string
    {
        return $this->get_id();
    }

    /**
     * @return string 'bundle'
     */
    public function type() : string
    {
        return $this->type;
    }

    /**
     * @return string 
     */
    public function name() : string
    {
        return $this->get_name();
    }

     /**
     * @return string
     */
    public function description() : string
    {
        return $this->get_description();
    }

     /**
     * @return string
     */
    public function price() : string
    {
        return $this->get_regular_price();
    }

    /**
     * @return string
     */
    public function sale_price() : string
    {
        return $this->get_sale_price();
    }

    /**
     * @return string
     */
    public function final_price() : string
    {
        return $this->get_price();
    }

     /**
     * 
     * Returns the <img> html tag of image. 
     * The return value must be echoing.
     * 
     * @return string
     */
    public function image() : string
    {

        return $this->get_image();
    }

    /**
     * Tells if the product is in sale
     * 
     * @return bool 
     */
    public function is_sale() : bool
    {
        if ( absint( $this->get_sale_price() ) > 0 ) {
            return true;
        }

        return false;
    }

     /**
     * Tells if the product is part of bundle product
     * 
     * @return bool
     */
    public function is_bundled_item() : bool
    {
        if ( !empty( $this->bundle_parents ) ) {
            return true;
        }

        return false;
    }


}