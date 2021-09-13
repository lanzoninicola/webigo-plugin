<?php


class Webigo_Logger {

    /**
     * Woocommerce Logger object
     * 
     * @var WC_Logger
     */
    private $logger;


    public function __construct( )
    {
        $this->load();
    }

    private function load() : void
    {
        if ( function_exists( 'wc_get_logger' ) ) {
            $this->logger = wc_get_logger();
            return;
        } 
        
        $this->logger = new WC_Logger();
    }

    /**
     * Records the errors inside the WC logs Woocommerce->Status->Logs
     * 
     * @param array $errorData
     * @return void 
     */
    public function record_error( array $errorData = array() ) : void
    {
        if ( !isset( $this->logger ) ) {
            return;
        }

        date_default_timezone_set(wp_timezone_string());

        $message = ', ';

        $base_error_log = array(
            'date'        =>  date("Y-m-d"),
            'hour'        =>  date("H:i:s"),
            'plugin'      => 'Webigo',
        );
        
        $_log_error = array_merge( $base_error_log, $errorData );

        $message .= implode(', ', $_log_error);

        $this->logger->log('error', $message);
    }

}