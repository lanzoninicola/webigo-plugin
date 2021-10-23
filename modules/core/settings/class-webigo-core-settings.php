<?php


class Webigo_Core_Settings {

    const MODULE_NAME = 'core';

    const MODULE_FOLDER = 'core';

    const BOOTSTRAP_CLASS_NAME = 'Webigo_Core';

    const BOOTSTRAP_CLASS_FILENAME = 'class-webigo-core.php';

    const CSS_VERSION = '2010071538';

    const JS_VERSION = '2010071538';

    const PLUGIN_MENU = array(
        'root'  => array(
            'page_title'   => 'Webigo',
            'menu_title'   => 'Webigo',
            'capability'   => 'manage_options',
            'menu_slug'    => 'webigo'
        ),
        'submenus' => array(),
    );

    /**
     *  @return array
     */
    public static function images( string $image_name = '' ) : array
    {

        $base_url = plugin_dir_url(__DIR__);

        $images = array(

            'logo' => array(
                'name'  => 'logo_hazbier',
                'label' => 'Logo Hazbier',
                'src'   => plugins_url() . '/webigo/modules/core/images/logo_50.png',
            ),

            'gmap' => array(
                'name'  => 'gmap',
                'label' => 'Map',
                'src'   => plugins_url() . '/webigo/modules/core/images/gmap.svg',
            ),

            'waze' => array(
                'name'  => 'waze',
                'label' => 'Waze',
                'src'   => plugins_url() . '/webigo/modules/core/images/waze.svg',
            ),

            'phone' => array(
                'name'  => 'phone',
                'label' => 'Liga',
                'src'   => plugins_url() . '/webigo/modules/core/images/phone.svg',
            ),

            'address' => array(
                'name'  => 'address',
                'label' => 'Endereço',
                'src'   => plugins_url() . '/webigo/modules/core/images/pin.svg',
            ),

            'whatsapp' => array(
                'name'  => 'whatsapp',
                'label' => 'Zapi',
                'src'   => plugins_url() . '/webigo/modules/core/images/whatsapp.svg',
            ),

        );

        if ( $image_name === '' ) {
            return $images;
        };

        return $images[$image_name];

    }
}