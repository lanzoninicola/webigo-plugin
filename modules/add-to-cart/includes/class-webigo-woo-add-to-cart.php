<?php


class Webigo_Woo_Add_To_Cart {


    private $product;

    private $is_purchasable;

    private $is_in_stock;

    private $action_name = 'webigo_ajax_add_to_cart';


    public function __construct( ) {
    }

    public function init( $product ) {
        $this->product = $product;

        $this->is_in_stock();
        $this->is_purchasable();
    }


    private function is_in_stock() {

        $this->is_in_stock = $this->product->is_in_stock();
    }

    private function is_purchasable(){

        $this->is_purchasable = $this->product->is_purchasable();
    }


    /**
     * Verify if the product can be added to the cart
     * 
     * @param bool true|false
     */
    public function should_be_added_to_cart(){

        if ( !$this->is_purchasable ) {
            return false;
        }

        if( !$this->is_in_stock ) {
            return false;
        }

        return true;
    }

    public function action_name()
    {
        return $this->action_name;
    }

    public function ajax_add_to_cart() {

        if ( $this->is_valid_request() ) {

            $_product_id = wp_unslash( $_POST['product_id'] );
            $_quantity = wp_unslash( $_POST['quantity'] );

            $product_id = apply_filters( $this->action_name . '_product_id', absint( $_product_id ) );
            $quantity = empty(  $_quantity  ) ? 1 : wc_stock_amount( $_quantity  );
            $passed_validation = apply_filters( $this->action_name . '_validation', true, $product_id, $quantity);
            $product_status = get_post_status( $product_id );

             if ($passed_validation && WC()->cart->add_to_cart( $product_id, $quantity ) && 'publish' === $product_status) {
                
                do_action( $this->action_name . '_added_to_cart', $product_id );

                /* Handle the redirection to cart after adding product
                if ( 'yes' === get_option( $this->action_name .  '_redirect_after_add' ) ) {
                    wc_add_to_cart_message( array($product_id => $quantity), true );
                }
                */
                
                WC_AJAX::get_refreshed_fragments();

                return;
            } 

            $this->send_error_add_to_cart( $product_id );
            
            wp_die();

        }
    }

    /**
     * Validation of add to cart request 
     * @return void
     */
    private function is_valid_request() : bool
    {

        $_action = wp_unslash( $_POST['action'] );
        $_nonce = wp_unslash( $_POST['nonce'] );
        $_webigo_ajax_addtocart = wp_unslash( $_POST['webigo_ajax_add_to_cart'] );
        
        if (
            ( ! isset( $_action ) && $_action !== 'webigo_ajax_add_to_cart' )  ||
            ! isset( $_nonce ) ||
            ! wp_verify_nonce( $_nonce, $this->action_name )
        ) {

            wp_send_json_error([
                'message' => 'something went wrong',
                'requestData' => array(
                    'action' =>  $_webigo_ajax_addtocart,
                    'nonce'  =>  $_nonce
                ),
                'nonceResult' => wp_verify_nonce( $_nonce, $this->action_name )
            ]);

            return false;

        }

        return true;
    }

    /**
     * 
     * @param string product_id
     * @return void JSON response with error
     * 
     */
    private function send_error_add_to_cart( string $product_id ) : void
    {

        // TODO: add_to_cart -> managing errors server side when problem occured during adding to cart
        $data = array(
            'error' => true,
            'product_url' => apply_filters( $this->action_name .  '_redirect_after_error', get_permalink( $product_id ), $product_id )
        );

        echo wp_send_json($data);
    }







}