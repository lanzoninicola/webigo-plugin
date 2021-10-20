<?php


class Webigo_Wpadmin_Menu_Settings {

    const MODULE_NAME = 'wpadmin-menu';

    const MODULE_FOLDER = 'wpadmin-menu';

    const BOOTSTRAP_CLASS_NAME = 'Webigo_Wpadmin_Menu';

    const BOOTSTRAP_CLASS_FILENAME = 'class-webigo-wpadmin-menu.php';

    const CSS_VERSION = '2109292134';

    const JS_VERSION = '2109292134';
   
    public static function images( string $image_name = '' ) {

        $base_url = plugin_dir_url(__DIR__);

        $images = array(

            'delivery' => array(
                'name'  => 'delivery',
                'label' => 'Delivery',
                'src'   => $base_url . 'images/delivery.svg',
            ),
        );

        if ( $image_name === '' ) {
            return $images;
        };

        return $images[$image_name];

    }

    

}