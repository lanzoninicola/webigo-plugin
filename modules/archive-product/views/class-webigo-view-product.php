<?php


class Webigo_View_Product {

    private $product;

    private $render_price;

    /**
     * 
     * @param Webigo_Woo_Product|Webigo_Woo_Product_Bundle
     */
    public function __construct($product) {

        $this->product = $product;

        $this->load_dependencies();

    }

    private function load_dependencies() {

        require_once WEBIGO_PLUGIN_PATH . '/modules/archive-product/views/class-webigo-view-product-price.php';

        require_once WEBIGO_PLUGIN_PATH . '/modules/archive-product/views/class-webigo-view-product-quantity.php';

        // TODO Future feature Below is a module dependency, so i might to query the registry to load this module
        // Because I can unregister the module. The app must work but the specific funcionality no
        require_once WEBIGO_PLUGIN_PATH . '/modules/add-to-cart/views/class-webigo-view-add-to-cart.php';

    }

    public function render_product() {

        echo '<li class="webigo-product" data-product-id="' . esc_html( $this->product->id() ) . '">';
            echo '<div class="webigo-product-container">';

                $this->render_image();
            
                echo '<div class="webigo-product-inner-details">';
                $this->render_product_info();
                $this->render_quantity_action_area();
                    echo  '<div class="webigo-product-cart-footer">';
                    $this->render_subtotal();
                    $this->render_add_to_cart();
                    echo '</div>';
                echo '</div>';

                
            echo '</div>';
        echo '</li>';
    }

    private function render_image() {

        echo '<div class="webigo-product-image">';
        echo  $this->product->image();
        echo '</div>';
    }

    private function render_product_info() {

        echo '<div class="webigo-product-info">';

        $this->render_name();

        $this->render_price();

        echo '</div>';
    }

    private function render_name() {

        echo '<h3 class="webigo-product-name">' . esc_html( $this->product->name() ) . '</h3>';
    }

    private function render_price() {

        $price = new Webigo_View_Product_Price( $this->product );

        $price->render();
    }

    private function render_quantity_action_area() {

        $quantity = new Webigo_View_Product_Quantity( $this->product );

        $quantity->render();
    }

    private function render_subtotal() {
        
        echo  '<div class="webigo-product-cart-subtotal">';
        
        echo '<div class="webigo-product-cart-subtotal-label">Subtotal</div>';
        echo '<div class="webigo-product-cart-subtotal-value" data-product-id="' . esc_attr( $this->product->id() ) . '">R$0</div>';
        
        echo '</div>';
    }

    private function render_add_to_cart() {

        $add_to_cart = new Webigo_View_Add_To_Cart(  );

        $add_to_cart->render($this->product);

    }

}