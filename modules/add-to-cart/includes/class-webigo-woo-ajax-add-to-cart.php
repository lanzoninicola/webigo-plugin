<?php

/**
 * This class must be only responsible for adding products to the cart.
 * 
 * This class is referenced in the Wordpress hook
 * 
 */
class Webigo_Woo_Ajax_Add_To_Cart
{

    /**
     * Action name used in the WP Hooks to handle the AJAX request.
     * Do not remove from this class.
     * 
     * @var string
     */
    private $action_name;

    /**
     * Request object that contains data and utilities to handle the AJAX request.
     * 
     * @var Webigo_Add_To_Cart_Request
     */
    private $request;

    public function __construct()
    {

        $this->action_name = 'webigo_ajax_add_to_cart';

        $this->load_dependencies();
    }

    private function load_dependencies() : void
    {
        require_once WEBIGO_PLUGIN_PATH . '/modules/add-to-cart/includes/class-webigo-add-to-cart-request.php';
        $this->request = new Webigo_Add_To_Cart_Request( $this->action_name );
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
        return $this->action_name;
    }

    /**
     * This method must be public. 
     * This method is called by the Wordpress hook
     * Do not remove from this class
     *     
     * @return void
     */
    public function ajax_add_to_cart(): void
    {

        $this->request->sanitize_input();

        if ( $this->request->is_valid() ) {

            try {
                $product_id        = apply_filters($this->action_name . '_product_id', $this->request->post('product_id') );
                $quantity          = empty( $this->request->post('quantity') ) ? 1 : wc_stock_amount( $this->request->post('quantity') );
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
