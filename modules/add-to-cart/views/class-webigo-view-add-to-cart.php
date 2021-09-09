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
     * @var Webigo_Woo_Add_To_Cart
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
            '<div class="wbg-add-to-cart" data-visibility=%s data-product-id=%s data-category-id=%s data-action-state=%s>%s%s%s</div>',
            esc_attr( 'hidden' ),
            esc_attr( $this->product->id() ),
            esc_attr( $this->category->id() ),
            esc_attr('idle'),
            $this->render_wp_nonce(),
            $this->render_add_to_cart_button(),
            $this->render_notification(),
        );
        
    /*

        $html = sprintf(
            '<div class="wbg-add-to-cart" data-visibility=%s data-product-id=%s data-category-id=%s data-action-state=%s>',
            esc_attr( 'hidden' ),
            esc_attr( $this->product->id() ),
            esc_attr( $this->category->id() ),
            esc_attr('idle'),
        );
        $output = wp_kses_post( $html );


        $output .= $this->render_wp_nonce();
        $output .= $this->render_add_to_cart_button();
        $output .= $this->render_notification();

        $output .= '</div>';

        return $output;
        */
    }

    private function render_wp_nonce() {

        $action_name = $this->add_to_cart->action_name();

        /**
         *  The second param is also used in "add-to-cart.js" 
         *  to identify the input field with the nonce value
         */

        return wp_nonce_field( $action_name , 'webigo_woo_add_to_cart_nonce' );
    }

    private function render_add_to_cart_button() : string
    {

        $output =  '<div class="wbg-add-to-cart-button-wrapper">';
        
        // $button_label = 'Adicionar ao carrinho';
        
        // do not remove the proudct_id and category_id attributes
        // data is used into javascript to understand which product is selected
        $html = sprintf(
            '<div class="wbg-add-to-cart-button" data-product-id=%s data-category-id=%s>',
            esc_attr( $this->product->id() ),
            esc_attr( $this->category->id() ),
        );
        $output .= wp_kses_post( $html );

        // echo '<span class="add-to-cart-label">' .  esc_html( $button_label ) . '</span>';
        $output .= '<span class="add-to-cart-label"></span>';
             
        $output .= '</div>';

        $output .= '</div>';

        return $output;
    }


    private function render_notification() : string 
    {
        $output =  '<div class="wbg-add-to-cart-notification">';

        $output .= $this->render_notification_success();

        $output .= $this->render_notification_failed();
        
        $output .=  '</div>';

        return $output;
    }

    private function render_notification_success() : string
    {

        $message = 'Produto adicionado ao carrinho!';

        $output =  '<div class="wbg-add-to-cart-notification-success" data-visibility="hidden">';
        
        $output .=  '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path fill-rule="evenodd" clip-rule="evenodd" d="M12 23C5.92487 23 1 18.0751 1 12C1 5.92487 5.92487 1 12 1C18.0751 1 23 5.92487 23 12C23 18.0751 18.0751 23 12 23ZM12 21C16.9706 21 21 16.9706 21 12C21 7.02944 16.9706 3 12 3C7.02944 3 3 7.02944 3 12C3 16.9706 7.02944 21 12 21ZM15.2929 8.29289L10 13.5858L7.70711 11.2929L6.29289 12.7071L10 16.4142L16.7071 9.70711L15.2929 8.29289Z" fill="#005930"/>
        </svg>';

        $output .=  '<span class="text-small">' . esc_html( $message ) . '</span>';

        $output .=  '</div>';

        return $output;
    }

    private function render_notification_failed() : string
    {

        $message = 'Occoreu um erro! Ritente por favor';

        $output = '<div class="wbg-add-to-cart-notification-failed" data-visibility="hidden">';
        
        $output .= '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path fill-rule="evenodd" clip-rule="evenodd" d="M12 23C5.92487 23 1 18.0751 1 12C1 5.92487 5.92487 1 12 1C18.0751 1 23 5.92487 23 12C23 18.0751 18.0751 23 12 23ZM12 21C16.9706 21 21 16.9706 21 12C21 7.02944 16.9706 3 12 3C7.02944 3 3 7.02944 3 12C3 16.9706 7.02944 21 12 21ZM8.70711 16.7071L12 13.4142L15.2929 16.7071L16.7071 15.2929L13.4142 12L16.7071 8.70711L15.2929 7.29289L12 10.5858L8.70711 7.29289L7.29289 8.70711L10.5858 12L7.29289 15.2929L8.70711 16.7071Z" fill="#BB0623"/>
        </svg>';

        $output .= '<span class="text-small">' . esc_html( $message ) . '</span>';

        $output .= '</div>';

        return $output;
    }
}
