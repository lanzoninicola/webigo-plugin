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

    private $button_user_style;

    public function __construct( $product, $category) {

        $this->product = $product;
        $this->category = $category;
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
            $css_class_final = 'btn-quantity-minus';
        }

        if ( $action == 'increase' ) {
            $svg_path_final = 'M12.5 6v13M6 12.5h13';
            $css_class_final = 'btn-quantity-plus';
        }

        echo '<div class="' . esc_attr( $css_class_final ) . ' btn-quantity-actions" data-product-id="' . esc_attr( $this->product->id() ) . '" data-product-price="' . esc_attr( $this->product->final_price() ) . '" data-category-id="' . esc_attr( $this->category->id() ) . '"">';

        echo '<svg
            width="' . esc_attr( $this->button_user_style['width'] ) . '"
            height="' . esc_attr( $this->button_user_style['height'] ) . '"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
            style="' . esc_attr( $this->button_user_style['style'] ) . '">
            <circle cx="12.5" cy="12.5" r="12.5" fill="' . esc_attr( $this->button_user_style['background'] ) . '" />
            <path stroke="' . esc_attr( $this->button_user_style['stroke'] ) . '" d="' . esc_attr( $svg_path_final ) . '" />
        </svg>';

        echo '</div>';

    }
    

}