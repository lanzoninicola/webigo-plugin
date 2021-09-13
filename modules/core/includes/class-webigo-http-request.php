<?php

require_once WEBIGO_PLUGIN_PATH . '/modules/core/includes/interface-webigo-http-request-data.php';

class Webigo_HTTP_Request {

     /**
     * Object implements IWebigo_Http_Request_Data
     * 
     * @var object
     */
    protected $http_data;

    /**
     *  Class responsible to handle an HTTP request
     * 
     *  Here is managed the sanitization and validation of request data
     * 
     * @param object $http_data (object that implements the IWebigo_Http_Request_Data interface)
     */
    public function __construct( IWebigo_Http_Request_Data $http_data )
    {
        $this->http_data = $http_data;
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
            'message'         => 'Something goes wrong with the request',
        );

        $http_error_response = array_merge( $base_http_response, $errorData );

         wp_send_json_error( $http_error_response );
    }

     /**
     * Returns all the data sanitized from the request 
     * 
     * @return array
     */
    public function get_all_data( ) : array
    {
        return $this->http_data->get( );

    }
    
    /**
     * Returns the requested data sanitized 
     * 
     * @return string|false if the value is not found
     */
    public function get_data( string $value ) : string
    {
        return $this->http_data->get_value( $value );

    }

}
