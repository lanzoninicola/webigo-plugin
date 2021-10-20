<?php



class Webigo_View_Quantity_Button {
   
    /**
     * @var Webigo_Woo_Product|Webigo_Woo_Product_Bundle
     */
    private $product;

    /**
     * 
     * @var Webigo_Woo_Category
     */
    private $category;


    public function __construct( $product, $category) {

        $this->product = $product;
        $this->category = $category;
    }
    

    public function render_plus_button() : string
    {

        $button_style = array(
            'style'      => 'box-shadow: rgb(136 165 191 / 22%) 6px 2px 16px 0px, rgb(255 255 255 / 80%) -6px -2px 16px 0px; border-radius: 50%;',
            'width'      => '35',
            'height'     => '35',
            'background' => '#00593080',
            'stroke'     => '#FFF'
        );

        $output = '<div class="btn-quantity-plus btn-quantity-actions" data-product-id="' . esc_attr( $this->product->id() ) . '" data-product-price="' . esc_attr( $this->product->final_price() ) . '" data-category-id="' . esc_attr( $this->category->id() ) . '"">';

        $output .= '<svg
            width="' . esc_attr( $button_style['width'] ) . '"
            height="' . esc_attr( $button_style['height'] ) . '"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
            style="' . esc_attr( $button_style['style'] ) . '">
            <circle cx="17.5" cy="17.5" r="17.5" fill="' . esc_attr( $button_style['background'] ) . '" />
            <line x1="17.3" y1="8.40002" x2="17.3" y2="26.6" stroke="' . esc_attr( $button_style['stroke'] ) . '"/>
            <line x1="8.40002" y1="17.7" x2="26.6" y2="17.7" stroke="' . esc_attr( $button_style['stroke'] ) . '"/>';
        $output .= '</svg>';

        $output .= '</div>';

        return $output;

    }

    public function render_minus_button() : string
    {

        $button_style = array(
            'style'      => 'box-shadow: rgb(136 165 191 / 22%) 6px 2px 16px 0px, rgb(255 255 255 / 80%) -6px -2px 16px 0px; border-radius: 50%;',
            'width'      => '35',
            'height'     => '35',
            'background' => '#E7E7E7',
            'stroke'     => '#000'
        );

        $output = '<div class="btn-quantity-minus btn-quantity-actions" data-product-id="' . esc_attr( $this->product->id() ) . '" data-product-price="' . esc_attr( $this->product->final_price() ) . '" data-category-id="' . esc_attr( $this->category->id() ) . '"">';

        $output .= '<svg
            width="' . esc_attr( $button_style['width'] ) . '"
            height="' . esc_attr( $button_style['height'] ) . '"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
            style="' . esc_attr( $button_style['style'] ) . '">
            <circle cx="17.5" cy="17.5" r="17.5" fill="' . esc_attr( $button_style['background'] ) . '" />
            <line x1="8.40002" y1="17.7" x2="26.6" y2="17.7" stroke="' . esc_attr( $button_style['stroke'] ) . '"/>';
        $output .= '</svg>';

        $output .= '</div>';

        return $output;

    }
    

}