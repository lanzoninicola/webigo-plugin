<?php

/**
 * This class is responsibile for managing the process of adding products in the cart.
 * 
 */

class Webigo_View_Add_To_Cart
{

    private $product;

    /**
     * 
     * @var Webigo_Woo_Add_To_Cart
     */
    private $add_to_cart;

    public function __construct()
    {

        $this->load_dependencies();
    }

    private function load_dependencies()
    {
        require_once WEBIGO_PLUGIN_PATH . '/modules/add-to-cart/includes/class-webigo-woo-add-to-cart.php';
        $this->add_to_cart = new Webigo_Woo_Add_To_Cart();
		
    }

    public function render($product)
    {
        $this->product = $product;

        echo  '<div class="webigo-add-to-cart-container" data-visibility="hidden" data-product-id="' . $this->product->id() . '">';

        $this->render_wp_nonce();
        $this->render_add_to_cart_button();

        echo  '</div>';

        $this->render_notification();
    }

    private function render_wp_nonce() {

        $action_name = $this->add_to_cart->action_name();

        /**
         *  The second param is also used in "add-to-cart.js" 
         *  to identify the input field with the nonce value
         */

        wp_nonce_field( $action_name , 'webigo_woo_add_to_cart_nonce' );
    }

    private function render_add_to_cart_button() {

        echo  '<div class="webigo-add-to-cart-button-wrapper">';
        
        $button_label = 'Adicionar ao carrinho';
        
        $html = sprintf(
            '<button class="webigo-add-to-cart-button" data-product-id=%s data-state=%s>%s</button>',
            esc_attr( $this->product->id() ),
            esc_attr('idle'),
            esc_html( $button_label )
            
        );
        echo wp_kses_post( $html );

        echo '</div>';

    }


    private function render_notification() {
        echo  '<div class="added-to-cart-notification" data-visibility="hidden" data-product-id="' . esc_html( $this->product->id() ) . '"></div>';
    }
}
