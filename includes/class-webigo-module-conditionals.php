<?php




class Webigo_Module_Conditionals {


    public function test( string $name ) 
    {

        $name = trim( $name );

        /** Start Woocommerce conditionals */
        if ( $name === 'is_woocommerce') {
            return is_woocommerce();
        }

        // Returns true when on the product archive page (shop).
        if ( $name === 'is_shop') {
            return is_shop();
        }

        if ( $name === 'is_product_category') {
            return is_product_category();
        }

        if ( $name === 'is_product') {
            return is_product();
        }

        if ( $name === 'is_cart') {
            return is_cart();
        }

        if ( $name === 'is_checkout') {
            return is_checkout();
        }

        if ( $name === 'is_account_page') {
            return is_account_page();
        }

        if ( $name === 'is_search') {
            return is_search();
        }

        // When the endpoint page for order received is being displayed.
        if ( $name === 'is_wc_endpoint_url_order_received') {
            return is_wc_endpoint_url( 'order-received' );
        }

        if ( $name === 'is_wc_endpoint_url_order_pay') {
            return is_wc_endpoint_url( 'order-pay' );
        }

        if ( $name === 'is_wc_endpoint_url_view_order') {
            return is_wc_endpoint_url( 'view-order' );
        }

        if ( $name === 'is_wc_endpoint_url_edit_account') {
            return is_wc_endpoint_url( 'edit-account' );
        }

        if ( $name === 'is_wc_endpoint_url_edit_address') {
            return is_wc_endpoint_url( 'edit-address' );
        }

        if ( $name === 'is_wc_endpoint_url_lost_password') {
            return is_wc_endpoint_url( 'lost-password' );
        }

        if ( $name === 'is_wc_endpoint_url_customer_logout') {
            return is_wc_endpoint_url( 'customer-logout' );
        }

        if ( $name === 'is_wc_endpoint_url_add_payment_method') {
            return is_wc_endpoint_url( 'add-payment-method' );
        }

        /** End Woocommerce conditionals */

        if ( $name === 'is_front_page') {
            return is_front_page();
        }

        if ( $name === 'is_single') {
            return is_single();
        }

        if ( $name === 'is_404') {
            return is_404();
        }

        if ( $name === 'is_home') {
            return is_home();
        }

    }


}