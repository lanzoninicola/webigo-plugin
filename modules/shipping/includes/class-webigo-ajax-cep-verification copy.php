<?php

require_once WEBIGO_PLUGIN_PATH . '/modules/core/includes/class-webigo-wordpress-ajax-request.php';


class Webigo_Shipping_Ajax_Cep_Verification extends Webigo_Wordpress_Ajax_Request {


    /**
     * The postcode normalized by Woocommerce
     * 
     * @var string
     */
    private $postcode;


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
    

    public function __construct( string $wp_action_name ) {
        $this->set_data_filter_settings( Webigo_Shipping_Settings::AJAX_CEP_VERIFICATION_DATA );

        parent::__construct( $wp_action_name );
    }

    public function handle_ajax_request() 
    {
        parent::handle_ajax_request();

        if ( !$this->is_nonce_valid() ) {
            $this->record_error_ajax_response();
            $this->send_error_ajax_response();

            return;
        }

        $request_resource = $this->http_request_data->get_value( 'resource' );
        $request_country  = $this->http_request_data->get_value( 'country' );
        $request_state    = $this->http_request_data->get_value( 'state' );
        $request_postcode = $this->http_request_data->get_value( 'postcode' );

        try {
            
            if ( "cep-form" === $request_resource ) {
                $this->package = array(
                    'destination' => array(
                        'country'  => isset ( $request_country ) ? $request_country : Webigo_Shipping_Settings::DEFAULT_COUNTRY_CODE,
                        'state'    => $request_state,
                        'postcode' => $request_postcode,
                    ),
                );
        
                $country   = strtoupper( wc_clean( $this->package['destination']['country'] ) );
                $state     = strtoupper( wc_clean( $this->package['destination']['state'] ) );
                $this->postcode  = wc_normalize_postcode( wc_clean( $this->package['destination']['postcode'] ) );
        
                // $cache_key        = WC_Cache_Helper::get_cache_prefix( 'shipping_zones' ) . 'wc_shipping_zone_' . md5( sprintf( '%s+%s+%s', $country, $state, $postcode ) );
                // $matching_zone_id = wp_cache_get( $cache_key, 'shipping_zones' );

                $this->load_package_shipping_zone_id();
                $this->load_shipping_methods_of_zone();

                $http_result_response = array(
                    'country_requested'  => $country,
                    'state_requested'    => $state,
                    'postcode_requested' => $this->postcode,
                );

                if ( $this->is_zone_matched() ) {

                    // $this->set_chosen_shipping_type_to_session();
                    //  $this->set_chosen_shipping_type_to_session();
                    // $this->set_chosen_shipping();

                    $http_result_response['zone_matched'] = true;
                    $http_result_response['message'] = 'CEP informado está na área de cobertura';

                    $this->send_success_response( $http_result_response );
                }

                if ( !$this->is_zone_matched() ) {

                    $http_result_response['zone_matched'] = false;
                    $http_result_response['message'] = 'CEP informado não está na área de cobertura';

                    $this->send_error_response( $http_result_response );
                }

            }

        } catch (Exception $e) {
            
            $errorData = array(
                'exception' => $e,
            );

            $this->send_error_response( $errorData );
        }
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
    function get_title_shipping_method_from_method_id( string $method_rate_id = '' ){
        if( ! empty( $method_rate_id ) ){
            $method_key_id = str_replace( ':', '_', $method_rate_id ); // Formating
            $option_name = 'woocommerce_'.$method_key_id.'_settings'; // Get the complete option slug
            return get_option( $option_name, true )['title']; // Get the title and return it
        } else {
            return false;
        }
    }


    /**
     * Returns the "method_id" (eg. flat_rate:1, shipping_free) of Delivery shipping method.
     * 
     * This method requires that the "shipping_zone_id" and "shipping_methods_of_zone" are loaded
     * with the related function in this class.
     * 
     * The result of this function is used to set the method in the Woocommerce Session 
     * ## WC()->session->set( 'chosen_shipping_methods', ['flat_rate:1'] ) ##
     * hooked to "woocommerce_before_checkout_form" action
     */
    function get_delivery_method_id() : string
    {

        var_dump(array_search( $this->delivery_default_label, $this->shipping_zone_methods ));

        return array_search( $this->delivery_default_label, $this->shipping_zone_methods );
    }


    private function is_zone_matched( ) {

        if ( null === $this->shipping_zone_id ) {
            return false;
        }

        return true;

    }

    
}