<?php



class Webigo_Woo_Urls {


    // TODO: replace along the plugin code with these functions
    public static function home() {
        return get_site_url();
    }

    public static function shop() {
        return wc_get_page_permalink('shop');
    }

    public static function checkout() {
        return wc_get_checkout_url();
    }

    public static function myaccount() {
        return wc_get_page_permalink('myaccount');
    }

    public static function account_orders() {
        return wc_get_endpoint_url( 'orders', '', wc_get_page_permalink('myaccount') );
    }

    public static function account_addresses() {
        return wc_get_endpoint_url( 'edit-address', '', wc_get_page_permalink('myaccount') );
    }

    public static function current_page() {
        global $post;
        return get_permalink( $post->ID );
    }

}

