<?php


class Webigo_Shipping_Banner_Content {


    /**
     * Shipping method choosed by the user and stored in the session.
     * 
     * @var string
     */
    private $session_shipping_method;



    public function __construct()
    {
        $this->load_dependencies();
        $this->load_session_data();
    }

    private function load_dependencies() : void
    {

   

    }


    private function load_session_data() : void
    {

        $shipping_method_keys = Webigo_Shipping_Settings::SESSION_KEYS;

        if ( isset( $shipping_method_keys['shipping_method'] ) ) {

            if ( !is_admin() ) {
                $this->session_shipping_method = WC()->session->get( $shipping_method_keys['shipping_method'] );
            }
            
        }

    }

    public function session_shipping_method()
    {
        return $this->session_shipping_method;
    }

}