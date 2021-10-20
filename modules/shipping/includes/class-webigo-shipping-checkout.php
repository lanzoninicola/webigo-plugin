<?php



class Webigo_Shipping_Checkout {

    /**
     * Package information used by Woocommerce to identify the shipping zone
     * 
     * @var array
     */
    private $package = array();

    /**
     * The ID of shipping zone from the package
     * 
     * @var array
     */
    private $shipping_zone_id = null;

    /**
     *  list of shipping methods for a specific zone.
     *  from WC_Shipping_Zone_Data_Store
     * 
     *  Array of objects containing method_id, method_order, instance_id, is_enabled
     * 
     * @var array
     */
    private $shipping_zone_methods = array();


    /**
     * This is hardcoded because it is not possibile identify the method with name "delivery" in Woocommerce.
     */
    private $delivery_default_label = 'delivery';


    public function __construct( array $package )
    {
        $this->package = $package;
        $this->load_package_shipping_zone_id();
        $this->load_shipping_methods_of_zone();
    }


    /**
     * Returns the number of shipping zone based on $package
     * This is the number of shipping zone stored in Woocommerce -> Settings -> Shipping -> Shipping Zones
     * 
     * If a shipping zone is not found it returns null
     * 
     * @return int|null 
     */
    private function load_package_shipping_zone_id()
    {
        $data_store             = WC_Data_Store::load('shipping-zone');
        $this->shipping_zone_id = $data_store->get_zone_id_from_package( $this->package );
        // wp_cache_set( $cache_key, $matching_zone_id, 'shipping_zones' );
    }


    /**
     * Return the shipping methods for each shipping zone
     * 
     */
    private function load_shipping_methods_of_zone()
    {
        $data_store                  = WC_Data_Store::load('shipping-zone');
        $woo_shipping_zone_methods   = (array)$data_store->get_methods( $this->shipping_zone_id, true );

        foreach ($woo_shipping_zone_methods as $woo_shipping_zone_method ) {
            $key = $woo_shipping_zone_method->method_id . ':' . $woo_shipping_zone_method->instance_id;
            $value = $this->get_title_shipping_method_from_method_id( $key );

            $this->shipping_zone_methods[$key] = isset( $value ) ? strtolower( $value ) : $value;
        }
      
    }

     /**
     * @param string ex. flate_rate:1
     */
    private function get_title_shipping_method_from_method_id( string $method_rate_id = '' ){
        if( ! empty( $method_rate_id ) ){
            $method_key_id = str_replace( ':', '_', $method_rate_id ); // Formating
            $option_name = 'woocommerce_'.$method_key_id.'_settings'; // Get the complete option slug
            return get_option( $option_name, true )['title']; // Get the title and return it
        } else {
            return false;
        }
    }
    
     /**
     * Returns the "method_id" (eg. flat_rate:1, shipping_free) of "Delivery" shipping method.
     * 
     * This method requires that the "shipping_zone_id" and "shipping_methods_of_zone" are loaded
     * with the related function in this class.
     * 
     * The result of this function is used to set the method in the Woocommerce Session 
     * ## WC()->session->set( 'chosen_shipping_methods', ['flat_rate:1'] ) ##
     * hooked to "woocommerce_before_checkout_form" action
     */
    public function get_delivery_method_id() : string
    {
        return array_search( $this->delivery_default_label, $this->shipping_zone_methods );
    }

    /**
     * Check if the package match with the shipping zone available in Woocommerce
     */
    public function is_zone_matched( ) {

        if ( null === $this->shipping_zone_id ) {
            return false;
        }

        return true;

    }

 
}