<?php


class Webigo_Checkout_Settings {

    const MODULE_NAME = 'checkout';

    const MODULE_FOLDER = 'checkout';

    const BOOTSTRAP_CLASS_NAME = 'Webigo_Checkout';

    const BOOTSTRAP_CLASS_FILENAME = 'class-webigo-checkout.php';

    const CSS_VERSION = '2109292134';

    const JS_VERSION = '2109292134';

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

            'thankyou' => array(
                'name'  => 'thank_you',
                'label' => 'Obrigado',
                'src'   => $base_url . 'images/thank-you.jpg',
            ),

        );

        if ( $image_name === '' ) {
            return $images;
        };

        return $images[$image_name];

    }
}