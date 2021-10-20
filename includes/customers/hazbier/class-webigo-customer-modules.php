<?php



class Webigo_Customer_Modules {



    public static function get() : array
    {
        return array(
            'notifications'   => 'Webigo_Notifications_Settings',
            'archive-product' => 'Webigo_Archive_Product_Settings',
            'add-to-cart'     => 'Webigo_Add_To_Cart_Settings',
            'shipping'        => 'Webigo_Shipping_Settings',
            'cart-widget'     => 'Webigo_Cart_Widget_Settings',
            'checkout'        => 'Webigo_Checkout_Settings',
            'widgets'         => 'Webigo_Widgets_Settings',
            'myaccount'       => 'Webigo_Myaccount_Settings',
            'orders'          => 'Webigo_Orders_Settings'
        );
    }
}