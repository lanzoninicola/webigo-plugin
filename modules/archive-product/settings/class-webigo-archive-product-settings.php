<?php


class Webigo_Archive_Product_Settings {

    const MODULE_NAME = 'archive-product';

    const MODULE_FOLDER = 'archive-product';

    const BOOTSTRAP_CLASS_NAME = 'Webigo_Archive_Product';

    const BOOTSTRAP_CLASS_FILENAME = 'class-webigo-archive-product.php';

    const CSS_VERSION = '2109292134';

    const JS_VERSION = '2109292134';

    // const AJAX_CEP_VERIFICATION_ACTION_NAME = "";

    // const AJAX_CEP_VERIFICATION_DATA = array(
    //     'action'     => FILTER_SANITIZE_STRING,
      
    // );

    const SHOW_SAVING_COMBO_SHEET = false;

    public static function images( string $image_name = '' ) : array
    {

        $base_url = plugin_dir_url(__DIR__);

        $images = array(

            'ibu' => array(
                'name'  => 'ibu',
                'label' => 'IBU',
                'src'   => $base_url . 'images/ibu.png',
            ),

            'abv' => array(
                'name'  => 'abv',
                'label' => 'ABV',
                'src'   => $base_url . 'images/abv.png',
            ),
            
        );

        if ( $image_name === '' ) {
            return $images;
        };

        return $images[$image_name];

    }

    const PODS_PRODUCT_WA_YESNO_FIELD = 'product_wa_contact';

    const PODS_PRODUCT_WA_NUMBER_FIELD = 'shipping_wa_number';

    const PODS_PRODUCT_WA_TEXT_FIELD = 'shipping_wa_text';

    const PODS_PRODUCT_WA_TEXT_FALLBACK = 'Para este produto, você precisa entrar em contato com nossa equipe. Pressione o botão WhatsApp e entre em contato conosco.';

    const PRODUCT_WA_PREFIX_CUSTOMER_MSG = 'Olá, estou entrando em contato com você porque estou interessado na compra do produto';

    

}