<?php

require_once WEBIGO_PLUGIN_PATH . '/modules/core/includes/class-webigo-wordpress-ajax-request.php';


class Webigo_Shipping_Ajax_Cep_Verification extends Webigo_Wordpress_Ajax_Request {

    public function __construct( string $wp_action_name ) {
        $this->data_filter_settings = Webigo_Shipping_Settings::AJAX_CEP_VERIFICATION_DATA;

        parent::__construct( $wp_action_name );
    }

    public function ajax_cep_verification() 
    {

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
        
                $http_result_response = array(
                    'country_requested'  => $country,
                    'state_requested'    => $state,
                    'postcode_requested' => $postcode,
                );

                if ( $this->is_zone_matched( $package )) {

                    $http_result_response['zone_matched'] = true;
                    $http_result_response['message'] = 'CEP informado está na área de cobertura';

                    $this->send_success_response( $http_result_response );
                }

                if ( !$this->is_zone_matched( $package )) {

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


    private function is_zone_matched( array $package ) {

        $data_store           = WC_Data_Store::load('shipping-zone');
        $shipping_zone_id     = $data_store->get_zone_id_from_package( $package );
        // wp_cache_set( $cache_key, $matching_zone_id, 'shipping_zones' );

        if ( null === $shipping_zone_id ) {
            return false;
        }

        return true;

    }
}