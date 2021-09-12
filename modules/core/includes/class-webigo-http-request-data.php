<?php

require_once WEBIGO_PLUGIN_PATH . '/modules/core/includes/interface-webigo-http-request-data.php';

class Webigo_Http_Request_Data implements IWebigo_Http_Request_Data {

     /**
     * HTTP verb used in request
     * 
     * @var string 'get'|'post'
     */
    private $http_method;

    /**
     * Here is declaring the filter type to apply for the request data
     * https://www.php.net/manual/en/function.filter-var-array.php
     * 
     * @var array
     */
    private $data_filter_settings;

    /**
     * Array with sanitized request data
     * 
     * @var array
     */
    private $data_sanitized;

    /**
     * This class is responsible to encapsulate the request data.
     * 
     * Before that, it sanitizes the request data 
     * based on filter passed in the constructor.
     * 
     * @param string $http_method
     * @param array  $data_filter_settings
     */
    public function __construct( string $http_method = 'post', array $data_filter_settings ) 
    {
        $this->http_method = $this->trimmer( $http_method );
        $this->data_filter_settings = $data_filter_settings;

        $this->check_filter_requested();
    }

    /**
     * Check if the filter was passed to the constructor
     */
    private function check_filter_requested() : void
    {
        foreach ($this->data_filter_settings as $filter_setting) {
            if ( substr( $filter_setting, 0, 6 ) !== 'FILTER') {
                throw new Exception('No valid filter was passed to the constructor');
            }
        }
    }
    
    /**
     * Data sent are sanitized and saved inside the object ($this->post_data_sanitized)
     * 
     * @return void
     */
    public function sanitize_input() : void
    {

        $raw_request_data = array();
        
        if ( $this->http_method === 'post') {
            $raw_request_data = $_POST;
        }

        if ( $this->http_method === 'get') {
            $raw_request_data = $_GET;
        }

        $post_data_trimmed = array_filter( $raw_request_data, array( $this, 'trimmer' ) );

        $this->data_sanitized = filter_var_array( $post_data_trimmed, $this->data_filter_settings );

    }

    /**
     * Internal utility function
     * 
     * @param string $value
     * @return string
     */
    public function trimmer( $value ) : string
    {
        return trim( $value );
    }


    /**
     * Returns all the data sanitized
     * 
     * @return array $data_sanitized
     */
    public function get() : array
    {
        return $this->data_sanitized;
    }

    /**
     * Returns a specific data sanitized
     * 
     * @return array $data_sanitized
     */
    public function get_value( string $value ) : string
    {
        return $this->data_sanitized[$value];
    }

}