<?php

require_once WEBIGO_PLUGIN_PATH . '/modules/core/includes/class-webigo-wordpress-ajax-request.php';


class Webigo_Shipping_Ajax_Cep_Verification extends Webigo_Wordpress_Ajax_Request {


    public function __construct( string $wp_action_name ) {
        $this->set_data_filter_settings( Webigo_Shipping_Settings::AJAX_CEP_VERIFICATION_DATA );

        parent::__construct( $wp_action_name );

        $this->load_dependencies();
    }

    private function load_dependencies() {

        require_once WEBIGO_PLUGIN_PATH . '/modules/shipping/includes/class-webigo-shipping-checkout.php';
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
                $package = array(
                    'destination' => array(
                        'country'  => isset ( $request_country ) ? $request_country : Webigo_Shipping_Settings::DEFAULT_COUNTRY_CODE,
                        'state'    => $request_state,
                        'postcode' => $request_postcode,
                    ),
                );
        
                $country   = strtoupper( wc_clean( $package['destination']['country'] ) );
                $state     = strtoupper( wc_clean( $package['destination']['state'] ) );
                $postcode  = wc_normalize_postcode( wc_clean( $package['destination']['postcode'] ) );
        
                // $cache_key        = WC_Cache_Helper::get_cache_prefix( 'shipping_zones' ) . 'wc_shipping_zone_' . md5( sprintf( '%s+%s+%s', $country, $state, $postcode ) );
                // $matching_zone_id = wp_cache_get( $cache_key, 'shipping_zones' );

                $wbg_shipping = new Webigo_Shipping_Checkout( $package );

                $http_result_response = array(
                    'country_requested'  => $country,
                    'state_requested'    => $state,
                    'postcode_requested' => $postcode,
                );

                if ( $wbg_shipping->is_zone_matched() ) {

                    $this->enable_wc_session();

                    WC()->session->set( 'wbg_chosen_shipping', $wbg_shipping->get_delivery_method_id() );

                    $http_result_response['zone_matched'] = true;
                    $http_result_response['message'] = 'CEP informado está na área de cobertura';

                    $this->send_success_response( $http_result_response );
                }

                if ( !$wbg_shipping->is_zone_matched() ) {

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

    // Early enable customer WC Session
    private function enable_wc_session()
    {
        if ( isset(WC()->session) && ! WC()->session->has_session() ) { // ignore error from intellephense
            WC()->session->set_customer_session_cookie( true );
        }
    }
    
}