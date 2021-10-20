<?php


class Webigo_Notifications_Settings {

    const MODULE_NAME = 'notifications';

    const MODULE_FOLDER = 'notifications';

    const BOOTSTRAP_CLASS_NAME = 'Webigo_Notifications';

    const BOOTSTRAP_CLASS_FILENAME = 'class-webigo-notifications.php';

    const CSS_VERSION = '2109292134';

    const JS_VERSION = '2109292134';

    
    public static function images( string $image_name = '' ) {

        $base_url = plugin_dir_url(__DIR__);

        $images = array(

            'success' => array(
                'name'  => 'success',
                'label' => 'Success',
                'src'   => $base_url . 'images/success.svg',
            ),

            'failed' => array(
                'name'  => 'failed',
                'label' => 'failed',
                'src'   => $base_url . 'images/failed.svg',
            ),
         

        );

        if ( $image_name === '' ) {
            return $images;
        };

        return $images[$image_name];

    }

    

}