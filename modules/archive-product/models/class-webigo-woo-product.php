<?php

class Webigo_Woo_Product extends WC_Product_Simple {

    protected $is_bundled_item;

    /**
     * 
     * @var array of Webigo_Woo_Product_Bundle
     * 
     * $bundle_parents = array(
     *                      'bundle_product_id#1' => Webigo_Woo_Product_Bundle 
     *                      'bundle_product_id#2' => Webigo_Woo_Product_Bundle 
     *                   )                                
     */
    private $bundle_parents;

    public function __construct( $product ) {

        parent::__construct( $product );

        $this->bundle_parents = array();
        $this->is_bundled_item = false;
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
     * The result must be echoing.
     * 
     * @return string
     */
    public function image() : string
    {
        return $this->get_image();
    }


    /**
     * 
     * @param  Webigo_Woo_Product_Bundle
     * @return void
     */

    public function add_bundle_parent(object $product_bundle) : void
    {

        $bundle_product_id = $product_bundle->id();

        $this->bundle_parents[$bundle_product_id] = $product_bundle;
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

    /**
     * Tells if the bundle product passed as param is its parent
     * 
     * @param Webigo_Woo_Product_Bundle
     * @return bool
     */
    public function is_my_parent( object $product_bundle ) : bool
    {

        $product_bundle_class_name = (string) get_class( $product_bundle );

        if ( ! $product_bundle_class_name === WEBIGO_BUNDLE_PRODUCT_CLASS_NAME ) {
            new Exception( __CLASS__ . '- ' . __FUNCTION__ . ': the parameter passed is not a Webigo_Woo_Product_Bundle class');
        }
        
        if ( !empty( $this->bundle_parents ) ) {

            foreach ( $this->bundle_parents as $bundle_parent ) {
                if ( $bundle_parent->id() === $product_bundle->id() ) {
                    return true;
                }
            }

        }

        return false;
    }

    /**
     * @return array of Webigo_Woo_Product_Bundle
     */
    public function get_bundle_parents() : array
    {

        return $this->bundle_parents;
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