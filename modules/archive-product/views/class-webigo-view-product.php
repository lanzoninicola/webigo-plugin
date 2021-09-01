<?php


class Webigo_View_Product {

    private $product;

    /**
     * 
     * @param Webigo_Woo_Product|Webigo_Woo_Product_Bundle
     */
    public function __construct($product) {

        $this->product = $product;

    }

    public function render_product() {

        echo '<li class="webigo-product" data-product-id="' . $this->product->id() . '">';

        

        echo '</li>';


    }


}