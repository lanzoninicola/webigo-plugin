<?php


class Webigo_Checkout_Fields {



    public function checkout_billing_fields( $fields )
    {
        // remove telephone from list
        if ( isset( $fields['billing_phone'] ) ){
            unset( $fields['billing_phone'] );
        }

        // set the cellphone mandatory
        if ( isset( $fields['billing_cellphone'] ) ){
            $fields['billing_cellphone']['required'] = true;
        }

        return $fields;

    }

    public function checkout_shipping_fields( $fields )
    {

    }

}