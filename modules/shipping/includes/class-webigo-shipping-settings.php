<?php


class Webigo_Shipping_Settings {

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

    public static function shipping_view_options( string $option = '' ) {

        $base_url = plugin_dir_url(__DIR__);

        $shipping_options = array(

            'delivery' => array(
                'name'  => 'delivery',
                'label' => 'Delivery',
                'src'   => $base_url . 'images/delivery.png',
            ),

            'retirar_na_loja' => array(
                'name'  => 'retirar_na_loja',
                'label' => 'Retira na Loja',
                'src'   => $base_url . 'images/retirar_na_loja.png',
            ),

        );

        if ( $option === '' ) {
            return $shipping_options;
        }

        return $shipping_options[$option];
    }

    public static function images( string $image_name = '' ) {

        $base_url = plugin_dir_url(__DIR__);

        $images = array(

            'delivery' => array(
                'name'  => 'delivery',
                'label' => 'Delivery',
                'src'   => $base_url . 'images/delivery.png',
            ),

            'retirar_na_loja' => array(
                'name'  => 'retirar_na_loja',
                'label' => 'Retira na Loja',
                'src'   => $base_url . 'images/retirar_na_loja.png',
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

        );

        if ( $image_name === '' ) {
            return $images;
        };

        return $images[$image_name];

    }

}