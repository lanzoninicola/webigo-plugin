<?php

/**
 * This class is responsibile for managing the process of adding products in the cart.
 * 
 */

class Webigo_View_Add_To_Cart
{

    /**
     * @var Webigo_Woo_Product|Webigo_Woo_Product_Bundle
     */
    private $product;

    /**
     * @var Webigo_Woo_Category
     */
    private $category;

    /**
     * 
     * @var Webigo_Woo_Ajax_Add_To_Cart
     */
    private $add_to_cart;


    public function __construct()
    {

        $this->load_dependencies();
    }

    private function load_dependencies()
    {
        require_once WEBIGO_PLUGIN_PATH . '/modules/add-to-cart/includes/class-webigo-woo-ajax-add-to-cart.php';
        $this->add_to_cart = new Webigo_Woo_Ajax_Add_To_Cart();
		
    }

    public function render( $product, $category) : string 
    {
        $this->product = $product;
        $this->category = $category;

        return sprintf(
            '<div class="wbg-add-to-cart" data-visibility=%s data-product-id=%s data-category-id=%s data-action-state=%s>%s%s</div>',
            esc_attr( 'hidden' ),
            esc_attr( $this->product->id() ),
            esc_attr( $this->category->id() ),
            esc_attr('idle'),
            $this->render_wp_nonce(),
            $this->render_add_to_cart_button(),
        );
    }

    private function render_wp_nonce() {

        $action_name = $this->add_to_cart->action_name();

        /**
         *  The second param is also used in "add-to-cart.js" 
         *  to identify the input field with the nonce value
         */

        return wp_nonce_field( $action_name , 'webigo_woo_add_to_cart_nonce', true, false );
    }

    private function render_add_to_cart_button() : string
    {

        $output =  '<div class="wbg-add-to-cart-button-wrapper">';
        
        // do not remove the proudct_id and category_id attributes
        // data is used into javascript to understand which product is selected
        $html = sprintf(
            '<div class="wbg-add-to-cart-button" data-product-id=%s data-category-id=%s>',
            esc_attr( $this->product->id() ),
            esc_attr( $this->category->id() ),
        );
        $output .= wp_kses_post( $html );

        $output .= '<span class="add-to-cart-label"></span>';
             
        $output .= '</div>';

        $output .= '</div>';

        return $output;
    }

}
