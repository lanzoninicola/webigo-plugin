<?php


class Webigo_Shipping_Settings {

    const MODULE_NAME = 'shipping';

    const MODULE_FOLDER = 'shipping';

    const BOOTSTRAP_CLASS_NAME = 'Webigo_Shipping';

    const BOOTSTRAP_CLASS_FILENAME = 'class-webigo-shipping.php';

    const CSS_VERSION = '2109292134';

    const JS_VERSION = '2109292134';

    const DEFAULT_COUNTRY = 'Brazil';

    const DEFAULT_STATE = 'Paraná';

    const DEFAULT_COUNTRY_CODE = "BR";

    const DEFAULT_STATE_CODE = "PR";

    const CORREIOS_URL_BUSCA_CEP = "https://buscacepinter.correios.com.br/app/endereco/index.php";

    const AJAX_CEP_VERIFICATION_ACTION_NAME = "shipping_area_validation";

    const AJAX_CEP_VERIFICATION_DATA = array(
        'action'     => FILTER_SANITIZE_STRING,
        'nonce'      => FILTER_SANITIZE_STRING,
        'resource'   => FILTER_SANITIZE_STRING,
        'country'    => FILTER_SANITIZE_STRING,
        'state'      => FILTER_SANITIZE_STRING,
        'postcode'   => FILTER_SANITIZE_STRING,
    );

    const SESSION_KEYS = array(
        'shipping_method'    => "wbg-shipping-method",
    );

    public static function images( string $image_name = '' ) {

        $base_url = plugin_dir_url(__DIR__);

        $images = array(

            'delivery' => array(
                'name'  => 'delivery',
                'label' => 'Delivery',
                'src'   => $base_url . 'images/delivery.svg',
            ),

            'pickupstore' => array(
                'name'  => 'pickupstore',
                'label' => 'Retira na Loja',
                'src'   => $base_url . 'images/retirar_na_loja.svg',
            ),

            'ship_area_success' => array(
                'name'  => 'ship_area_success',
                'label' => 'CEP está na área de cobertura',
                'src'   => $base_url . 'images/ship_area_success.png',
            ),

            'ship_area_failed' => array(
                'name'  => 'ship_area_failed',
                'label' => 'CEP não está na área de cobertura',
                'src'   => $base_url . 'images/ship_area_failed.png',
            ),

            'background_home' => array(
                'name'  => 'background_home',
                'label' => 'background_home',
                'src'   => $base_url . 'images/background_home.png',
            ),

            'blob' => array(
                'name'  => 'blob',
                'label' => 'blob',
                'src'   => $base_url . 'images/blob.svg',
            ),

        );

        if ( $image_name === '' ) {
            return $images;
        };

        return $images[$image_name];

    }

    

}