<?php


class Webigo_Orders_Settings {

    const MODULE_NAME = 'orders';

    const MODULE_FOLDER = 'orders';

    const BOOTSTRAP_CLASS_NAME = 'Webigo_Orders';

    const BOOTSTRAP_CLASS_FILENAME = 'class-webigo-orders.php';

    const CSS_VERSION = '2109292134';

    const JS_VERSION = '2109292134';

    const ORDER_WORKING_STATE = array(
        'PENDING'             => 'ORDER_IN_PROGRESS',
        'AGUARDANDO'          => 'ORDER_IN_PROGRESS',
        'PROCESSANDO'         => 'ORDER_IN_PROGRESS',
        'SAIU PARA ENTREGA'   => 'ORDER_IN_PROGRESS',
        'CANCELADO'           => 'DONE',
        'CONCLUIDO'           => 'DONE'
    );
    
    public static function images( string $image_name = '' ) {

        $base_url = plugin_dir_url(__DIR__);

        $images = array(

            'order_notification' => array(
                'name'  => 'order_notification',
                'label' => 'Order Notification',
                'src'   => $base_url . 'images/order_notification.svg',
            ),

        );

        if ( $image_name === '' ) {
            return $images;
        };

        return $images[$image_name];

    }

    

}