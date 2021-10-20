<?php


class Webigo_Add_To_Cart_Settings {

    const MODULE_NAME = 'add-to-cart';

    const MODULE_FOLDER = 'add-to-cart';

    const BOOTSTRAP_CLASS_NAME = 'Webigo_Add_To_Cart';

    const BOOTSTRAP_CLASS_FILENAME = 'class-webigo-add-to-cart.php';

    const CSS_VERSION = '2109292134';

    const JS_VERSION = '2109292134';

    const AJAX_ADD_TO_CART_BULK_ACTION_NAME = 'webigo_bulk_add_to_cart';

    // TODO: test if json data (cart) are filtered, same TODO placed in the request data object
    const AJAX_ADD_TO_CART_BULK_DATA = array(
        'action'     => FILTER_SANITIZE_STRING,
        'nonce'      => FILTER_SANITIZE_STRING,
        'resource'   => FILTER_SANITIZE_STRING,
        'cart'       => array(
            'qty'      => FILTER_SANITIZE_STRING,
            'subtotal' => FILTER_SANITIZE_STRING,
        )
    );

    const SESSION_KEYS = array();

}