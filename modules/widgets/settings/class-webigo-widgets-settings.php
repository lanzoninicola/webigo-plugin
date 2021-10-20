<?php


class Webigo_Widgets_Settings {

    const MODULE_NAME = 'widgets';

    const MODULE_FOLDER = 'widgets';

    const BOOTSTRAP_CLASS_NAME = 'Webigo_Widgets';

    const BOOTSTRAP_CLASS_FILENAME = 'class-webigo-widgets.php';

    const CSS_VERSION = '2109292134';

    const JS_VERSION = '2109292134';


    /**
     * Returns all or specified setting for the widget
     * 
     * @return array|string
     */
    public static function store_address_banner_shortcode_settings( string $option = null )
    {
        $settings = array(
            'PODS_CUSTOM_SETTINGS_PAGE'            => 'informacoes_comerciais',
            'PODS_SHIPPING_STORE_ADDRESS_FIELD'    => 'shipping_store_address',
            'PODS_SHIPPING_STORE_CEP_FIELD'        => 'shipping_store_cep',
            'PODS_SHIPPING_STORE_CITY_FIELD'       => 'shipping_store_city',
            'PODS_SHIPPING_STORE_PHONE_FIELD'      => 'shipping_store_phone',
            'PODS_SHIPPING_STORE_WHATSAPP_FIELD'   => 'shipping_wa_number',
        ); 

        if ( $option !== null ) {
            
            if ( isset( $settings[$option] ) ) {
                return $settings[$option];
            }

            return '';
        }

        return $settings;
    }

    /**
     * Returns all or specified setting for the widget
     * 
     * @return array|string
     */
    public static function header_whatsapp_shortcode_settings( string $option = null )
    {
        $settings = array(
            'PODS_CUSTOM_SETTINGS_PAGE'   => 'informacoes_comerciais',
            'PODS_STORE_WA_NUMBER'        => 'store_wa_number',
            'WA_PREFIX_CUSTOMER_MSG'      => 'Olá, preciso de algumas informações'
        ); 

        if ( $option !== null ) {
            
            if ( isset( $settings[$option] ) ) {
                return $settings[$option];
            }

            return '';
        }

        return $settings;
    }


  

    

}