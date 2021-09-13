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
        'cep'        => FILTER_VALIDATE_INT,
    );

    public static function shipping_view_options() {

        $base_url = plugin_dir_url(__DIR__);

        return array(

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
    }

}