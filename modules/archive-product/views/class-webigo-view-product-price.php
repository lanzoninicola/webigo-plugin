<?php


class Webigo_View_Product_Price {

    private $product;

    public function __construct($product) {

        $this->product = $product;
        $this->load_dependencies();
    }

    private function load_dependencies() {


    }

    public function render() {

        $price = $this->product->price();

        $sale_price = $this->product->sale_price();

        $final_price = $this->product->final_price();

        $this->render_price = $price;

        if ( !empty( $sale_price ) ) {

            $this->render_price = $sale_price;
        }

        // TODO: improve UI when a sale price is available
        echo '<div class="webigo-product-price">R$' . $this->product->final_price() . '</div>';


    }

    


}