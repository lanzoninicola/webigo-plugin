<?php



class Webigo_View_Quantity_Button {

    private $product;

    private $button_user_style;

    public function __construct( $product ) {

        $this->product = $product;
        $this->button_user_style = array();
    }


    /**
     * 
     * @param string $action "increase" | "decrease"
     * @param array  $style
     */

    public function render( string $action, array $style ) {

        $this->button_user_style['style'] = isset( $style['style'] ) ? $style['style'] : '';
        $this->button_user_style['width'] = isset( $style['width'] ) ? $style['width'] : '25';
        $this->button_user_style['height'] = isset( $style['height'] ) ? $style['height'] : '25';
        $this->button_user_style['background'] = isset( $style['background'] ) ? $style['background'] : 'red';
        $this->button_user_style['stroke'] = isset( $style['stroke'] ) ? $style['stroke'] : '#000';

        $this->render_svg_button( $action );
    }


    private function render_svg_button( $action ) {

        $svg_path_final = '';
        $css_class_final = '';

        if ( $action == 'decrease' ) {
            $svg_path_final = 'M6 12.5h13';
            $css_class_final = 'add-to-cart-minus-qty';
        }

        if ( $action == 'increase' ) {
            $svg_path_final = 'M12.5 6v13M6 12.5h13';
            $css_class_final = 'add-to-cart-plus-qty';
        }

        echo '<div class="' . $css_class_final . ' btn-addto-cart-qty" data-product-id="' . $this->product->id() . '" data-product-price="' . $this->product->final_price() . '">';

        echo '<svg
            width="' . $this->button_user_style['width'] . '"
            height="' . $this->button_user_style['height'] . '"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
            style="' . $this->button_user_style['style'] . '">
            <circle cx="12.5" cy="12.5" r="12.5" fill="' . $this->button_user_style['background'] . '" />
            <path stroke="' . $this->button_user_style['stroke'] . '" d="' . $svg_path_final . '" />
        </svg>';

        echo '</div>';

    }
    

}