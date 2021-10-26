<?php

require_once WEBIGO_PLUGIN_PATH . '/modules/core/includes/class-webigo-wordpress-ajax-request.php';

// TODO: managing json error https://www.php.net/manual/en/function.json-last-error.php

class Webigo_Add_To_Cart_Ajax_Bulk_Request extends Webigo_Wordpress_Ajax_Request {


    /**
     * Contains the array of products sent by request 
     * (hitting the "add to cart" button in the footer)
     * 
     * @var array
     */
    private $user_cart;


    /**
     * Action name used to add products to cart
     * 
     * @var string
     */
    private $wp_action_name;

    /**
     * Contains the array of failed add to cart results
     * 
     * $add_to_cart_results = array(
     *      'product_id' => false
     * );
     * 
     * @var array
     */
    private $results;

    
    public function __construct( string $wp_action_name ) {
        $this->set_data_filter_settings( Webigo_Add_To_Cart_Settings::AJAX_ADD_TO_CART_BULK_DATA );
        $this->wp_action_name = $wp_action_name;
        $this->user_cart = array();

        parent::__construct( $wp_action_name );
        $this->load_dependencies();
        
    }

    private function load_dependencies()
    {
        require_once WEBIGO_PLUGIN_PATH . '/modules/add-to-cart/includes/class-webigo-product-add-to-cart-handler.php';
        
    }
    

    public function handle_ajax_request() 
    {
        parent::handle_ajax_request();

        if ( !$this->is_nonce_valid() ) {
            $this->record_error_ajax_response();
            $this->send_error_ajax_response();

            return;
        }

        $this->parse_request_cart_data();

        if ( count( $this->user_cart ) > 0 ) {
            foreach ( $this->user_cart as $product_id => $data) {

                $product_add_to_cart_handler = new Webigo_Product_Add_To_Cart_Handler( $this->wp_action_name, $product_id, $data['qty'] );
                
                $product_add_to_cart_handler->add_to_cart();
    
                $this->record_results( $product_id , $product_add_to_cart_handler->is_product_added_to_cart() );
    
            }
    
            $this->handle_success_response( );
    
            $this->handle_failed_response( );
        }

        if ( count( $this->user_cart ) === 0 ) {
        
            $errorData = array(
                'message' => 'Nenhum produto foi adicionado ao carrinho. Por favor, verifique se selecionou as quantidades de produto desejadas.'
            );

            $this->send_error_response( $errorData );
        }

    }


    /**
     * 1- Get the "cart" param from the http request 
     * 2- Encode the json data
     * 3- Save the data inside the object
     * 
     * @return void
     */
    private function parse_request_cart_data() : void
    {

        $http_request_data = $this->http_request_data->get();

        $cart_json = str_replace('\"', '"', $http_request_data['cart']);

        if ( 'undefined' !== $http_request_data['cart'] ) {
            $this->user_cart = (array) json_decode( $cart_json , true);
        }

    }
   
    /**
     * Records in an array the results of add to cart process
     * 
     * @param string $product_id
     * @param bool   $result
     */
    private function record_results( string $product_id, bool $result = false) : void
    {
        $this->results[$product_id] = $result;
    }


     /**
     * This method uses the Webigo_Http_Request class to handle the response
     * 
     * @return void  JSON response with success data
     * 
     */
    private function handle_success_response() : void
    {
        $products_response_data = array();

        foreach ( $this->results as $product_id => $result ) {

            if ( $result === true ) {
                $product = (object) wc_get_product( $product_id );
                $products_response_data[$product_id] = array( 
                    'id'     => $product_id,
                    'name'   => $product->get_name(),
                    'result' => $result,
                );
            }
        }

        if ( count( $products_response_data ) > 0 ) {
            $mini_cart = (array) $this->get_refreshed_fragments();

            $data = array(
                'products'     => json_encode( $products_response_data ),
                'fragments'    => $mini_cart['fragments'],
                'cart_hash'    => $mini_cart['cart_hash']
            );
            
            $this->send_success_response( $data );
        }

    }

    /**
     * Code from WC_AJAX::get_refreshed_fragments();
	 * Get a refreshed cart fragment, including the mini cart HTML.
	 */
	private function get_refreshed_fragments() : array
    {
		ob_start();

		woocommerce_mini_cart();

		$mini_cart = ob_get_clean();

		return array(
			'fragments' => apply_filters(
				'woocommerce_add_to_cart_fragments',
				array(
					'div.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>',
				)
			),
			'cart_hash' => WC()->cart->get_cart_hash(),
		);
	}


    private function handle_failed_response() {

        $products_response_data = array();

        foreach ( $this->results as $product_id => $result ) {

            if ( $result === false ) {
                $product = (object) wc_get_product( $product_id );
                $products_response_data[$product_id] = array( 
                    'id'     => $product_id,
                    'name'   => $product->get_name(),
                    'result' => $result,
                );
            }
        }

        if ( count( $products_response_data ) > 0 ) {
            $data = array(
                'products'     => json_encode( $products_response_data ),
                'message'      => 'Alguns produtos não foram adicionados ao carrinho'
            );
    
            $this->send_error_response( $data );
            $this->record_error_to_logger( $data );
        }
    }
    

    /**
     * Records the errors inside the WC logs Woocommerce->Status->Logs using
     * the Webigo_Logger class inside the parent class
     * 
     * @param object $e Exception object
     * @param string $product_id The id of the product
     * 
     */
    private function record_error_to_logger( array $data ) : void
    {

        $data_string = implode( ' , ', $data );

        $error_data = array(
            'plugin'      => 'Webigo',
            'class'       => __CLASS__,
            'function'    => 'handle_ajax_request',
            'products'    => $data_string,
            'message'     => 'Add to cart failed',
        );

        $this->logger->record_error( $error_data );
    }









    
}