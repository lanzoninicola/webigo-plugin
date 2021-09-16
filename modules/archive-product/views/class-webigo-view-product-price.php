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

        $output = '<div class="wbg-product-price-container">';
        
        $output .= $this->render_price();

        $output .= $this->render_discount();

        $output .= '</div>';

        return $output;

    }

    private function render_price() : string
    {

        $output = '<div class="wbg-product-price-wrapper">';

        if ( $this->product->is_sale() ) {
            $output .= '<div class="wbg-product-full-price">';
            $output .= '<span class="wbg-price-symbol">R$</span>';
            $output .= '<span class="wbg-price wbg-full-price">' . esc_html( $this->product->price() ) . '</span>';
            $output .= '<span> - </span>';
            $output .= '</div>';
        }

        $output .= '<div class="wbg-product-sale-price">';
        $output .= '<span class="wbg-price-symbol">R$</span>';
        $output .= '<span class="wbg-price wbg-final-price">' . esc_html( $this->product->final_price() ) . '</span>';
        $output .= '</div>';

        $output .= '</div>';

        return $output;
    }

    private function render_discount() : string 
    {

        $output = '<div class="wbg-product-discount-wrapper">';

        if ( $this->product->is_sale() ) {

            $discount_percentage = ( $this->product->final_price() / $this->product->price() ) * 100;
        
            $output .= '<div class="wbg-product-discount">';

            $output .= '<span>' . esc_html( number_format((float)$discount_percentage, 2, ',', '')) . '% OFF</span>';

            $output .= '</div>';
        
        }

        $output .= '</div>';

        return $output;
    }
}