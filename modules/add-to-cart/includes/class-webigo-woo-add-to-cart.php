<?php


class Webigo_Woo_Add_To_Cart {


    private $product;

    private $is_purchasable;

    private $is_in_stock;


    public function __construct( $product ) {

        $this->product = $product;

        $this->is_in_stock();
        $this->is_purchasable();
    }


    private function is_in_stock() {

        $this->is_in_stock = $this->product->is_in_stock();
    }

    private function is_purchasable(){

        $this->is_purchasable = $this->product->is_purchasable();
    }


    /**
     * Verify if the product can be added to the cart
     * 
     * @param bool true|false
     */
    public function should_be_added_to_cart(){

        if ( !$this->is_purchasable ) {
            return false;
        }

        if( !$this->is_in_stock ) {
            return false;
        }

        return true;
    }







}