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

            $product_id = apply_filters( $this->action_name . '_product_id', absint( $_POST['product_id'] ) );
            $quantity = empty( $_POST['quantity'] ) ? 1 : wc_stock_amount( $_POST['quantity'] );
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
            
            } else {

                // TODO: add_to_cart -> managing errors server side when problem occured during adding to cart
                $data = array(
                    'error' => true,
                    'product_url' => apply_filters( $this->action_name .  '_redirect_after_error', get_permalink( $product_id ), $product_id )
                );
                
                echo wp_send_json($data);
            }
            wp_die();

        }
    }

    private function is_valid_request()
    {

        if (
            ( ! isset( $_POST['action'] ) && $_POST['action'] !== 'webigo_ajax_add_to_cart' )  ||
            ! isset( $_POST['nonce'] ) ||
            ! wp_verify_nonce( $_POST['nonce'], $this->action_name )
        ) {

            wp_send_json_error([
                'message' => 'something went wrong',
                'requestData' => array(
                    'action' =>  $_POST['webigo_ajax_add_to_cart'],
                    'nonce'  =>  $_POST['nonce'] 
                ),
                'nonceResult' => wp_verify_nonce( $_POST['nonce'], $this->action_name )
            ]);

            return false;

        }

        return true;

    }







}