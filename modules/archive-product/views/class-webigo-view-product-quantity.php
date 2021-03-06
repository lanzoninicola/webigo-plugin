<?php


class Webigo_View_Product_Quantity
{

    /**
     * @var Webigo_Woo_Product|Webigo_Woo_Product_Bundle
     */
    private $product;

    /**
     * @var Webigo_Woo_Category
     */
    private $category;

    /**
     * @var Webigo_Woo_View_Product_Quantity_Input
     */
    private $product_quantity_input;

    /**
     * @var Webigo_View_Quantity_Button
     */
    private $quantity_button;

    public function __construct( $product , $category )
    {

        $this->product = $product;
        $this->category = $category;
        $this->load_dependencies();
    }

    private function load_dependencies()
    {

        require_once WEBIGO_PLUGIN_PATH . '/modules/archive-product/views/class-webigo-view-product-quantity-button.php';

        require_once WEBIGO_PLUGIN_PATH . '/modules/archive-product/views/class-webigo-view-product-quantity-input.php';

        $this->product_quantity_input = new Webigo_Woo_View_Product_Quantity_Input( $this->product, $this->category );

        $this->quantity_button = new Webigo_View_Quantity_Button( $this->product, $this->category );
    }

    public function render() : string
    {

        $output = '<div class="wbg-product-quantity">';

        $output .= $this->render_quantity_label();

        $output .= $this->render_quantity_actions();

        $output .= '</div>';

        return $output;
    }

    private function render_quantity_actions() : string
    {
        $output = '<div class="wbg-product-quantity-actions">';

        $output .= $this->quantity_button->render_minus_button();

        $output .= $this->render_quantity_input();

        $output .= $this->quantity_button->render_plus_button();

        $output .= '</div>';

        return $output;
    }

    private function render_quantity_label()
    {
        return $this->product_quantity_input->render_html_input_label( );
    }

    private function render_quantity_input()
    {
        return $this->product_quantity_input->render_html_input_field( );

    }
}
