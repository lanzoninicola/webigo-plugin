<?php

/**
 *  Inspired by "woocommerce_quantity_input" function
 * 
 *  C:\xampp\htdocs\hazbier\wp-content\plugins\woocommerce\includes\wc-template-function.php
 * 
 *  That function is used to output the quantity input in the cart form on the product single page
 */

class Webigo_Woo_Product_Quantity_Html_Input {

    
    private $product;

    private $min_value;

    private $max_value;


    /**
     * 
     * @param Webigo_Woo_Product|Webigo_Woo_Product_Bundle|WC_Product
     */
       
    public function __construct( $product ) {

        $this->product = $product;

    }

    /**
     * 
     * @param int $min_value - Optional - If passed it will be the min value of input field with type number
     */
    private function min_value( int $min_value = null ) {

        if ( ! is_null( $min_value ) ) {
            return absint( $min_value );
        }

        $this->min_value = apply_filters( 'woocommerce_quantity_input_min', $this->product->get_min_purchase_quantity(), $this->product );

        return $this->min_value;
    }

    /**
     * 
     * @param int $max_value - Optional -  If passed it will be the max value of input field with type number
     */
    private function max_value( int $max_value = null ) {

        if ( ! is_null( $max_value ) ) {
            return absint( $max_value );
        }

        $this->max_value = apply_filters( 'woocommerce_quantity_input_max', $this->product->get_max_purchase_quantity(), $this->product );

        if ( $this->max_value < $this->min_value ) {
			$this->max_value = $this->min_value;
		}

        return $this->max_value;
    }

    private function size( ) {

        return absint( 4 );

    }

    private function input_name( ) {

        return 'quantity';
    }

    private function input_step(){

        return apply_filters( 'woocommerce_quantity_input_step', 1, $this->product );

    }

    private function pattern() {

        return apply_filters( 'woocommerce_quantity_input_pattern', has_filter( 'woocommerce_stock_amount', 'intval' ) ? '[0-9]*' : '' );
    }

    private function inputmode() {

        return apply_filters( 'woocommerce_quantity_input_inputmode', has_filter( 'woocommerce_stock_amount', 'intval' ) ? 'numeric' : '' );
    }

     /**
     * 
     * @param int $input_value - Optional - If passed it will be the input value of input field with type number
     */
    private function input_value( int $input_value = null ) {

        // The " $_POST['quantity'] " value must have more priority than the value forced by the parameter

        return ! is_null( $input_value ) ? absint( $input_value ) : $this->product->get_min_purchase_quantity();

    }

    /**
     * 
     * @param array $args - At the moment it handles the "for" attribute of label (label_for)
     */
    public function render_html_input_label( array $args  ) {

        $label_for = isset( $args['label_for'] ) ? $args['label_for'] : '';

        $input_label_text = 'Quantitade';

        $html = sprintf(
            '<label for="%s" class="webigo-product-qty-label">%s</label>',
            esc_attr( $label_for ),
            esc_html( $input_label_text )
        );
        echo wp_kses_post( $html );
    }


    /**
     * 
     * @param array $args
     * 
     * It handles the following attributes:
     * - "id"          (arg: input_id)
     * - "class"       (arg: input_classes) - Used to identify the context like Wordpress.
     * - "type=hidden" (arg: hidden)
     *      * 
     * 
     */
    public function render_html_input_field( array $args ) {

        $input_id       = isset( $args['input_id'] ) ? $args['input_id'] : uniqid( 'webigo-addtocart-input-quantity-' );
        $input_classes  = isset( $args['input_classes'] ) ? $args['input_classes'] : uniqid( 'webigo-addtocart-input-quantity-' );
        $input_hidden   = isset( $args['hidden'] ) && $args['hidden'] ? true : false ;
        $input_type     = $input_hidden ? 'hidden': 'number';


        $input_string = '<input name=%s id=%s class=%s type=%s value=%s data-product-id=%s min=%u max=%u size=%u step=%u readonly style="text-align:center;"/>';
        
        echo sprintf(
            $input_string,    
            esc_attr( $this->input_name() ),
            esc_attr( $input_id ),
            esc_attr( $input_classes ),
            esc_attr( $input_type ),
            esc_attr( $this->input_value( 0 ) ),
            esc_attr( $this->product->id() ),
            esc_attr( $this->min_value( 0 ) ),
            esc_attr( $this->max_value( 999 ) ),
            esc_attr( $this->size() ),
            esc_attr( $this->input_step() ),
        );
    }
}