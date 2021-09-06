<?php

/**
 * This class must be only responsible for managing the ajax request of add-to-cart
 */
class Webigo_Woo_Ajax_Add_To_Cart
{

    /**
     * Action name used in the WP Hooks to handle the AJAX request.
     * 
     * @var string
     */
    private $action_name = 'webigo_ajax_add_to_cart';

    /**
     * Array with sanitized input
     * 
     * @var array
     */
    private $post_data;

    public function __construct()
    {

        $this->action_name = 'webigo_ajax_add_to_cart';
        $this->request_data = array();
    }

    /**
     * 
     * @return string
     */
    public function action_name(): string
    {
        return $this->action_name;
    }

    /**
     * This method must be public. 
     * It is called by the wp hook responsible to handle the request to add to cart.
     * 
     * 
     * @return void
     */
    public function ajax_add_to_cart(): void
    {

        $this->sanitize_input();

        if ( $this->is_valid_request() ) {

            try {
                $_product_id = $this->post_data['product_id'];
                $_quantity   = $this->post_data['quantity'];

                $product_id        = apply_filters($this->action_name . '_product_id', absint( $_product_id ));
                $quantity          = empty($_quantity) ? 1 : wc_stock_amount( $_quantity );
                $passed_validation = apply_filters($this->action_name . '_validation', true, $product_id, $quantity);
                $product_status    = get_post_status($product_id);

                if ($passed_validation && WC()->cart->add_to_cart($product_id, $quantity) && 'publish' === $product_status) {

                    do_action($this->action_name . '_added_to_cart', $product_id);

                    /* Handle the redirection to cart after adding product
                    if ( 'yes' === get_option( $this->action_name .  '_redirect_after_add' ) ) {
                        wc_add_to_cart_message( array($product_id => $quantity), true );
                    }
                    */

                    WC_AJAX::get_refreshed_fragments();

                }
            } catch (Exception $e) {
                $this->send_error_response( $e, $product_id );
            }

            wp_die();
        }
    }

    /**
     * Validation of add to cart request 
     * @return bool
     */
    private function is_valid_request(): bool
    {

        $_action     = $this->post_data['action'];
        $_nonce      = $this->post_data['nonce'];
        $_product_id = $this->post_data['product_id'];

        if (
            ($_action !== $this->action_name)  ||
            $_product_id === 0 ||
            !$_nonce ||
            !wp_verify_nonce($_nonce, $this->action_name)
        ) {

            //TODO: Priority 1 add-to-cart: managing this response - Sent an email to dev o record wp_errors
            wp_send_json_error([
                'message'     => 'something went wrong',
                'requestData' => array(
                    'action'  =>  $this->action_name,
                    'nonce'   =>  $_nonce
                ),
                'nonceResult' => wp_verify_nonce($_nonce, $this->action_name)
            ]);

            return false;
        }

        return true;
    }

    /**
     * Data sent in post are sanitized and saved inside the object ($this->post_data)
     * 
     * @return void
     */
    private function sanitize_input(): void
    {

        $this->post_data['action']     = isset( $_POST['action'] )     ? wp_unslash( $_POST['action'] )     : false;
        $this->post_data['product_id'] = isset( $_POST['product_id'] ) ? wp_unslash( $_POST['product_id'] ) : 0;
        $this->post_data['nonce']      = isset( $_POST['nonce'] )      ? wp_unslash( $_POST['nonce'] )      : false;
        $this->post_data['quantity']   = isset( $_POST['quantity'] )   ? wp_unslash( $_POST['quantity'] )   : 1;
    }

    /**
     * 
     * @param string product_id
     * @return void JSON response with error
     * 
     */
    private function send_error_response(object $e, string $product_id): void
    {
        wp_send_json_error(
            array(
                'error' => $e->getMessage(),
                'product_id' => $product_id,
                'product_url' => apply_filters($this->action_name .  '_redirect_after_error', get_permalink($product_id), $product_id)
            )
        );
    }
}
