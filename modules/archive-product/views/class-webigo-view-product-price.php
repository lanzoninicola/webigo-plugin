<?php


class Webigo_View_Product_Price {

    /**
     * @var Webigo_Woo_Product|Webigo_Woo_Product_Bundle
     */
    private $product;

    public function __construct(object $product) {

        $this->product = $product;
    }

    public function render() : string
    {

        $price = $this->product->price();

        $sale_price = $this->product->sale_price();

        // $final_price = $this->product->final_price();

        $this->render_price = $price;

        if ( !empty( $sale_price ) ) {

            $this->render_price = $sale_price;
        }

        // TODO: improve UI when a sale price is available
        $output = '<div class="wbg-product-price">R$' . esc_html( $this->product->final_price() ) . '</div>';

        return $output;

    }
}