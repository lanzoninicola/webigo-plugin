<?php



class Webigo_View_Product_Info {

    /**
     * @var Webigo_Woo_Product|Webigo_Woo_Product_Bundle
     */
    private $product;

    /**
     * 
     * @var Webigo_View_Product_Price
     */
    private $product_price;


    public function __construct( $product ) {

        $this->product = $product;
        $this->load_dependencies();
    }

    private function load_dependencies() : void
    {

        require_once WEBIGO_PLUGIN_PATH . '/modules/archive-product/views/class-webigo-view-product-price.php';

        $this->product_price = new Webigo_View_Product_Price( $this->product );

    }

    public function render() {

        echo '<div class="wbg-product-info">';

        $this->render_name();

        $this->render_description();

        $this->product_price->render();

        echo '</div>';
    }

    public function render_image() {

        echo '<div class="wbg-product-image">';
        echo  $this->product->image();
        echo '</div>';
    }

    private function render_name() {

        echo '<h3 class="wbg-product-name">' . esc_html( $this->product->name() ) . '</h3>';
    }

    private function render_description() {

        echo '<p class="wbg-product-description">' . esc_html( $this->product->description() ) . '</p>';
    }



}