<?php

/**
 *  Class responsible to handle the ajax request
 * 
 *  Here is managed the sanitization and validation process of POST request data
 */
class Webigo_Add_To_Cart_Request {

    /**
     * Action name used in the WP Hooks to handle the AJAX request.
     * 
     * @var string
     */
    private $action_name;

    /**
     * Woocommerce Logger object
     * 
     * @var WC_Logger
     */
    private $logger;

     /**
     * Array with sanitized input
     * 
     * @var array
     */
    private $post_data_sanitized;

    public function __construct( string $wp_action_name )
    {
        $this->action_name = $wp_action_name;
        $this->load_error_logger();
    }

    private function load_error_logger() : void
    {
        if ( function_exists( 'wc_get_logger' ) ) {
            $this->logger = wc_get_logger();
        } else {
            $this->logger = new WC_Logger();
        }

    }

    /**
     * Validation of add to cart request 
     * @return bool
     */
    public function is_valid(): bool
    {

        $_action     = $this->post_data_sanitized['action'];
        $_nonce      = $this->post_data_sanitized['nonce'];
        $_product_id = $this->post_data_sanitized['product_id'];

        if (
            ($_action !== $this->action_name)  ||
            $_product_id === 0 ||
            !$_nonce ||
            !wp_verify_nonce($_nonce, $this->action_name)
        ) {

            $log_error = array(
                'plugin'      => 'Webigo',
                'class'       => 'Class: ' . __CLASS__,
                'function'    => 'Method: is_valid',
                'message'     => 'Message: Nonce verification failed'  
            );

            $this->record_error_log( $log_error );

            $http_error_response = array(
                'message'     => 'The request is not valid.',
                'requestData' => array(
                    'action'     =>  $this->action_name,
                    'nonce'      =>  $_nonce,
                    'product_id' => $_product_id,
                ),
                'nonceResult' => wp_verify_nonce($_nonce, $this->action_name)
            );

            $this->send_error_response( $http_error_response );

            return false;
        }

        return true;
    }

    /**
     * Data sent in post are sanitized and saved inside the object ($this->post_data_sanitized)
     * 
     * @return void
     */
    public function sanitize_input(): void
    {

        $post_data_trimmed = array_filter( $_POST, array( $this, 'trimmer' ) );

        // here is declaring which POST data are allowed and the filter type to apply for each of them
        // https://www.php.net/manual/en/function.filter-var-array.php
        $post_filter = array(
            'action'     => FILTER_SANITIZE_STRING,
            'product_id' => FILTER_VALIDATE_INT,
            'nonce'      => FILTER_SANITIZE_STRING,
            'quantity'   => FILTER_VALIDATE_INT,
        );
        
        $this->post_data_sanitized = filter_var_array( $post_data_trimmed, $post_filter );
    }

    /**
     * Internal utility function
     * 
     * @param string $value
     * @return string
     */
    private function trimmer( $value ) : string
    {
        return trim( $value );
    }

    
     /**
     * 
     * Responds to client with success data
     * 
     * @param array $successData
     * @return void JSON response with success data
     * 
     */
    public function send_success_response( array $successData ) : void
    {

        wp_send_json_success( $successData );

    }

    /**
     * Responds to client with error data
     * 
     * @param  array $errorData
     * @return void
     */
    public function send_error_response( array $errorData ): void
    {

        $_http_response = array_merge( array(
            'error' => true,
        ), $errorData);

         wp_send_json_error( $_http_response );
    }

    /**
     * Records the errors inside the WC logs Woocommerce->Status->Logs
     * 
     * @param array $errorData
     * @return void 
     */
    public function record_error_log( array $errorData ) : void
    {
        if ( !isset( $this->logger ) ) {
            return;
        }

        date_default_timezone_set(wp_timezone_string());

        $message = ', ';

        $_log_error = array_merge( array(
            'date'        =>  date("Y-m-d"),
            'hour'        =>  date("H:i:s"),
        ), $errorData );

        $message .= implode(', ', $_log_error);

        $this->logger->log('error', $message);
    }

    /**
     * Returns the data from the request sanitized
     * 
     * @return string|false if the value is not found
     */
    public function post( string $value ) 
    {
        return isset( $this->post_data_sanitized[$value] ) ? $this->post_data_sanitized[$value] : false;

    }



}
