<?php


class Webigo_Auth_Login {



    public function redirect() {
        function ts_redirect_login( $redirect, $user ) {

            $redirect_page_id = url_to_postid( $redirect );
            $checkout_page_id = wc_get_page_id( 'checkout' );
            $home_page_id     = get_page_by_title( 'home' );
            
            if( $redirect_page_id == $checkout_page_id ) {
                return wc_get_page_permalink( 'checkout' );
            }
            
            return wc_get_page_permalink( 'shop' );

            }
            
            add_filter( 'woocommerce_login_redirect', 'ts_redirect_login' );
    }
}