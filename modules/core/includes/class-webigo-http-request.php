<?php

require_once WEBIGO_PLUGIN_PATH . '/modules/core/includes/interface-webigo-http-request-data.php';

class Webigo_HTTP_Request {

    /**
     * The Wordpress action handled by the request.
     * This must be passed as input of object
     * 
     * @var string
     */
    private $wp_action_name;

    /**
     * The nonce sent with the request data
     * 
     * @var string 
     */
    private $request_nonce;

    /**
     * The action related sent with the request data
     * 
     * @var string 
     */
    private $request_action;

     /**
     * Object implements IWebigo_Http_Request_Data
     * 
     * @var object
     */
    private $http_data;

    /**
     *  Class responsible to handle the Wordpress AJAX requests
     * 
     *  Here is managed the sanitization and validation of request data
     * 
     * @param string $wp_action_name (the action name handled by Wordpress hook)
     * @param object $http_data (object that implements the IWebigo_Http_Request_Data interface)
     */
    public function __construct( string $wp_action_name, IWebigo_Http_Request_Data $http_data )
    {
        $this->wp_action_name = $wp_action_name;
        $this->http_data = $http_data;
    }

    /**
     * Validation of nonce sent 
     * @return bool
     */
    public function is_nonce_valid(): bool
    {

        $this->request_action     = $this->http_data->get_value( 'action' );
        $this->request_nonce      = $this->http_data->get_value( 'nonce' );

        if (
            ( $this->request_action !== $this->wp_action_name )  ||
            !isset( $this->request_nonce ) ||
            !wp_verify_nonce($this->request_nonce, $this->action_name)
        ) {

            return false;
        }

        return true;
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

        $base_http_response = array(
            'error'           => true,
            'wp_action'       => $this->wp_action_name,
            'request_action'  => $this->request_action,
            'request_nonce'   => $this->request_nonce,
            'nonce_result'    => wp_verify_nonce($this->request_nonce, $this->action_name),
            'message'         => 'The request is not valid.',
        );

        $http_error_response = array_merge( $base_http_response, $errorData );

         wp_send_json_error( $http_error_response );
    }

    
    /**
     * Returns the data from the request sanitized
     * 
     * @return string|false if the value is not found
     */
    public function get_data( string $value ) : string
    {
        return $this->http_data->get_value( $value );

    }

}
