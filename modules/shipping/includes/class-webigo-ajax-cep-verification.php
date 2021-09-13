<?php

require_once WEBIGO_PLUGIN_PATH . '/modules/core/includes/class-webigo-wordpress-ajax-request.php';


class Webigo_Shipping_Ajax_Cep_Verification extends Webigo_Wordpress_Ajax_Request {

    public function __construct( string $wp_action_name ) {
        $this->data_filter_settings = Webigo_Shipping_Settings::AJAX_CEP_VERIFICATION_DATA;

        parent::__construct( $wp_action_name );
    }

    public function ajax_cep_verification() 
    {

        if ( !$this->http_request->is_valid() ) {

            $log_error = array(
                'class'       => 'Class: ' . __CLASS__,
                'function'    => 'Method: is_valid',
                'message'     => 'Message: Nonce verification failed'  
            );

            $this->logger->record_error( $log_error );
            return;

        }

        var_dump($this->http_request_data);die;

        try {
            
            // $requestFrom = $_POST["request_id"];

            // if ($requestFrom === "cep-form") {
            //     $package = array(
            //         'destination' => array(
            //             'country'  => $_POST["country"],
            //             'state'    => $_POST["state"],
            //             'postcode' => $_POST["postcode"]
            //         ),
            //     );
        
            //     $country          = strtoupper(wc_clean($package['destination']['country']));
            //     $state            = strtoupper(wc_clean($package['destination']['state']));
            //     $postcode         = wc_normalize_postcode(wc_clean($package['destination']['postcode']));
        
            //     // $cache_key        = WC_Cache_Helper::get_cache_prefix( 'shipping_zones' ) . 'wc_shipping_zone_' . md5( sprintf( '%s+%s+%s', $country, $state, $postcode ) );
            //     // $matching_zone_id = wp_cache_get( $cache_key, 'shipping_zones' );
        
            //     $matching_zone_id = false;
        
            //     if (false === $matching_zone_id) {
            //         $data_store       = WC_Data_Store::load('shipping-zone');
            //         $matching_zone_id = $data_store->get_zone_id_from_package($package);
            //         // wp_cache_set( $cache_key, $matching_zone_id, 'shipping_zones' );
            //     }
        
            //     $result = array(
            //         'country_requested' => $country,
            //         'state_requested' => $state,
            //         'postcode_requested' => $postcode,
            //         'result' => $matching_zone_id ? true : false,
            //     );
        
            //     wp_send_json_success($result);

        } catch (\Throwable $th) {
            //throw $th;
        }

    }
}