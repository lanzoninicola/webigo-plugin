<?php


class Webigo_MyAccount_Settings {

    const MODULE_NAME = 'myaccount';

    const MODULE_FOLDER = 'myaccount';

    const BOOTSTRAP_CLASS_NAME = 'Webigo_Myaccount';

    const BOOTSTRAP_CLASS_FILENAME = 'class-webigo-myaccount.php';

    const CSS_VERSION = '2109292134';

    const JS_VERSION = '2109292134';

    const LOAD_MYACCOUNT_FORM_LOGIN_CUSTOM_TEMPLATE = true;

    const LOAD_MYACCOUNT_DASHBOARD_CUSTOM_TEMPLATE = true;
    
    const LOAD_MYACCOUNT_ORDERS_CUSTOM_TEMPLATE = true;

    const LOAD_MYACCOUNT_VIEW_ORDERS_CUSTOM_TEMPLATE = true;

    const HIDE_REMEMBERME_FLAG = true;

   
    public static function images( string $image_name = '' ) {

        $base_url = plugin_dir_url(__DIR__);

        $images = array(

            'gotostore' => array(
                'name'  => 'gotostore',
                'label' => 'Ir para loja',
                'src'   => $base_url . 'images/gotostore.svg',
            ),

            'painel_inicial' => array(
                'name'  => 'painel_inicial',
                'label' => 'Painel Inicial',
                'src'   => $base_url . 'images/painel_inicial.svg',
            ),

            'orders' => array(
                'name'  => 'orders',
                'label' => 'Pedidos',
                'src'   => $base_url . 'images/orders.svg',
            ),

            'edit-address' => array(
                'name'  => 'edit-address',
                'label' => 'Endereços',
                'src'   => $base_url . 'images/edit-address.svg',
            ),

            'edit-account' => array(
                'name'  => 'edit-account',
                'label' => 'Detalhes da conta',
                'src'   => $base_url . 'images/edit-account.svg',
            ),

            'order_notification' => array(
                'name'  => 'order_notification',
                'label' => 'Order Notification',
                'src'   => $base_url . 'images/order_notification.svg',
            ),

            'login' => array(
                'name'  => 'login',
                'label' => 'Login',
                'src'   => $base_url . 'images/login.jpg',
            ),

            'logout' => array(
                'name'  => 'logout',
                'label' => 'Sair',
                'src'   => $base_url . 'images/logout.svg',
            ),

            'logo_login' => array(
                'name'  => 'login',
                'label' => 'Login',
                'src'   => $base_url . 'images/logo_login.png',
            ),

            'avatar' => array(
                'name'  => 'avatar',
                'label' => 'Avatar',
                'src'   => $base_url . 'images/avatar.svg',
            ),

            'lost_password' => array(
                'name'  => 'lost_password',
                'label' => 'Redefinir Senha',
                'src'   => $base_url . 'images/lost_password.svg',
            ),
        );

        if ( $image_name === '' ) {
            return $images;
        };

        return $images[$image_name];

    }

    

}