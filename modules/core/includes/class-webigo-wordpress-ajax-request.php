<?php

require_once WEBIGO_PLUGIN_PATH . '/modules/core/includes/class-webigo-http-request.php';

abstract class Webigo_Wordpress_Ajax_Request extends Webigo_Http_Request {

    /**
     * The Wordpress action handled by the request.
     * 
     * IMPORTANT: This must be initialized in the children constructor.
     * 
     * @var string
     */
    private $wp_action_name;

    /**
     * Each AJAX request handle a set of data.
     * This array describes which data and the filter to apply to each of it.
     * 
     * IMPORTANT: This must be initialized in the children constructor.
     * 
     * @var array
     */
    protected $data_filter_settings;

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
     * Object that contains the sanitized http request data 
     * 
     * @var Webigo_Http_Request_Data
     */
    protected $http_request_data;

    /**
     * Woocommerce Logger object
     * 
     * @var Webigo_Logger
     */
    protected $logger;

    /**
     * This array describes which data and the filter to apply to each of it
     * 
     */
    public function __construct( string $wp_action_name ) {

        $this->wp_action_name = $wp_action_name;
        $this->load_dependencies();
    }

    /**
     * This class is dedicated for handling the Wordpress AJAX requests.
     *
     * This class extends the "Webigo_Http_Request" class, then all methods are available here.
     *
     */
    private function load_dependencies() : void 
    {
        require_once WEBIGO_PLUGIN_PATH . '/modules/core/includes/class-webigo-http-request-data.php';
        $this->http_request_data = new Webigo_Http_Request_Data( 'post', $this->data_filter_settings );

        require_once WEBIGO_PLUGIN_PATH . '/modules/core/includes/class-webigo-logger.php';
        $this->logger = new Webigo_Logger();

    }

    /**
     * Validation of nonce sent 
     * @return bool
     */
    public function is_nonce_valid(): bool
    {

        $this->request_action     = $this->http_request_data->get_value( 'action' );
        $this->request_nonce      = $this->http_request_data->get_value( 'nonce' );

        if (
            ( $this->request_action !== $this->wp_action_name )  ||
            !isset( $this->request_nonce ) ||
            !wp_verify_nonce($this->request_nonce, $this->request_action)
        ) {

            return false;
        }

        return true;
    }

     /**
     * Failed response sent to the client
     * IMPORTANT: This method is dedicated to nonce verification failed
     * 
     * @param  array $errorData
     * @return void
     */
    public function send_error_ajax_response( ): void
    {

        $ajax_error_data = array(
            'wp_action'       => $this->wp_action_name,
            'request_action'  => $this->request_action,
            'request_nonce'   => $this->request_nonce,
            'nonce_result'    => wp_verify_nonce($this->request_nonce, $this->action_name),
            'message'         => 'The request is not valid.',
        );

        $this->send_error_response( $ajax_error_data );
    }

    /**
     * Record failed response to Logger.
     * IMPORTANT: This method is dedicated to nonce verification failed
     * 
     * @param  array $errorData
     * @return void
     */
    public function record_error_ajax_response( ): void
    {

        $log_error = array(
            'class'       => 'Class: ' . __CLASS__,
            'function'    => 'Method: ajax_cep_verification',
            'message'     => 'Message: Nonce verification failed'  
        );

        $this->logger->record_error( $log_error );
    }

    /**
     * This method is called by the Wordpress hook.
     * This method must be public.
     * Do not remove from this class
     * 
     * @return string
     */
    public function action_name(): string
    {
        return $this->wp_action_name;
    }


    
}