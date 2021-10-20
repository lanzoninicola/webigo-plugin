<?php



class Webigo_Skip_Cart_page {


    public function redirect() {
        
        // Redirect to checkout (when cart is not empty)
        if ( ! WC()->cart->is_empty() && is_cart() ) {
            wp_safe_redirect( wc_get_checkout_url() ); 
            exit();
        }
        // Redirect to shop if cart is empty
        elseif ( WC()->cart->is_empty() && is_cart() ) {
            wp_safe_redirect( wc_get_page_permalink( 'shop' ) );
            exit();
        }
    }


}


