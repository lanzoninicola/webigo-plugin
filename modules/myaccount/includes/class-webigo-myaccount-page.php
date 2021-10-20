<?php




class Webigo_Myaccount_Page {

    /**
     * @var Webigo_Customer
     */
    private $customer;


    public function __construct()
    {

        $this->load_dependencies();
    }

    private function load_dependencies()
    {

        // require_once WEBIGO_PLUGIN_PATH . '/modules/core/includes/class-webigo-http-request-data.php';
        // $this->http_request_data = new Webigo_Http_Request_Data( 'get', array() );

        // require_once WEBIGO_PLUGIN_PATH . '/modules/core/includes/class-webigo-http-request.php';
        // $this->http_request = new Webigo_Http_Request( $this->http_request_data );

        require_once WEBIGO_PLUGIN_PATH . 'modules/myaccount/includes/class-webigo-customer.php';
        $this->customer = new Webigo_Customer();
    }

    public function remove_default_navitems( $items ) {
        
        unset($items['dashboard']);
        unset($items['orders']);
        unset($items['downloads']);
        unset($items['customer-logout']);
        unset($items['edit-address']);
        unset($items['edit-account']);
        return $items;
    }

    public function redirect_to_order_section(){
        // TODO: This collide with the JS of shipping home for redirecting user from home->login->home
        // This redirect win
        // TODO: Using own HTTP REQUEST classes?

        if ( $this->should_redirect_to_order_section() ){

            global $wp;
                
            if ( $wp->request === 'my-account' ) {
                wp_redirect( site_url( '/my-account/orders/' ) );
                exit();
            }
        
            if ( $wp->request === 'minha-conta' ) {
                wp_redirect( site_url( '/minha-conta/orders/' ) );
                exit();
            }
        }
    }

    public function should_redirect_to_order_section()
    {
        return is_user_logged_in() === true && is_admin() === false 
                && $this->customer->has_orders() === true && $this->customer->has_shipping_address() === true;
    }
}