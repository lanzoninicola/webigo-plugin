<?php


class Webigo_View_Product_Image {

    /**
     * @var Webigo_Woo_Product|Webigo_Woo_Product_Bundle
     */
    private $product;

    /**
     * @var Webigo_Woo_Category
     */
    private $category;

    public function __construct(object $product, object $category) {

        $this->product  = $product;
        $this->category = $category;
    }

    public function render() : string
    {

        $output = '<div class="wbg-product-image wbg-zoom-product-image" data-product-id="' . esc_attr( $this->product->id() ) . '" data-category-id="' . esc_attr( $this->category->id() ) . '">';
        $output .=  $this->product->image();
        $output .= '</div>';

        return $output;
    }

}