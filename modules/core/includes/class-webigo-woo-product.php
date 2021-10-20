<?php

class Webigo_Woo_Product extends WC_Product {


    /**
	 * Get the product if ID is passed, otherwise the product is new and empty.
	 * This class should NOT be instantiated, but the wc_get_product() function
	 * should be used. It is possible, but the wc_get_product() is preferred.
	 *
	 * @param int|WC_Product|object $product Product to init.
	 */
    public function __construct( $product ) {

        parent::__construct( $product );
    }

    /**
    * @return string
    */
    public function id() : string
    {
        return $this->get_id();
    }

    /**
    * @return string
    */
    public function type() : string
    {
        return $this->get_type();
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
    public function link() : string
    {
        return $this->get_permalink();
    }

    /**
    * @return string
    */
    public function is_featured() : bool
    {
        return $this->get_featured();
    }


    /**
    * @return float
    */
    public function price() : float
    {
        return (float)$this->get_regular_price();
    }

    /**
    * @return float
    */
    public function sale_price() : float
    {
        return (float)$this->get_sale_price();
    }

    /**
    * @return float
    */
    public function final_price() : float
    {
        return (float)$this->get_price();
    }

    /**
     * 
     * Returns the <img> html tag of image.
     * The result must be echoing.
     * 
     * @return string
     */
    public function image() : string
    {
        return $this->get_image();
    }

  	/**
	 * Returns product attributes.
	 *
	 * @return array
	 */
    public function attributes() : array
    {
        return $this->get_attributes();
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


}