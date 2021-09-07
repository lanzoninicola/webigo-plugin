<?php


class Webigo_View_Product {

    /**
     * @var Webigo_Woo_Product|Webigo_Woo_Product_Bundle
     */
    private $product;

    /**
     * 
     * @var Webigo_Woo_Category
     */
    private $category;

    /**
     * Dependency
     * 
     * @var Webigo_View_Product_Info
     */
    private $product_info;

    /**
     * Dependency
     * 
     * @var Webigo_View_Product_Quantity
     */
    private $product_quantity;

    /**
     * Dependency
     * 
     * @var Webigo_View_Add_To_Cart
     */
    private $add_to_cart;

    /**
     * 
     * @param Webigo_Woo_Product|Webigo_Woo_Product_Bundle
     */
    public function __construct( $product , $category ) {

        $this->product = $product;
        $this->category = $category;

        $this->load_dependencies();

    }

    private function load_dependencies() {

        require_once WEBIGO_PLUGIN_PATH . '/modules/archive-product/views/class-webigo-view-product-info.php';
        require_once WEBIGO_PLUGIN_PATH . '/modules/archive-product/views/class-webigo-view-product-quantity.php';
        // TODO Future feature Below is a module dependency, so i might to query the registry to load this module
        // Because I can unregister the module. The app must work but the specific funcionality no
        require_once WEBIGO_PLUGIN_PATH . '/modules/add-to-cart/views/class-webigo-view-add-to-cart.php';

        $this->product_info = new Webigo_View_Product_Info( $this->product );
        $this->product_quantity = new Webigo_View_Product_Quantity( $this->product, $this->category );
        $this->add_to_cart = new Webigo_View_Add_To_Cart();
    }

    public function render_product() {

        echo '<li class="wbg-product" data-product-id="' . esc_html( $this->product->id() ) . '">';
            echo '<div class="wbg-product-container">';

                $this->product_info->render_image();
            
                echo '<div class="wbg-product-inner-details">';
                $this->product_info->render();
                $this->product_quantity->render();
                    echo  '<div class="wbg-product-footer">';
                    $this->render_subtotal();
                    $this->add_to_cart->render( $this->product, $this->category );
                    echo '</div>';
                echo '</div>';

                
            echo '</div>';
        echo '</li>';
    }

    private function render_subtotal() {
        
        echo  '<div class="wbg-product-subtotal">';
        
        echo '<div class="wbg-product-subtotal-label">Subtotal</div>';
        echo '<div class="wbg-product-subtotal-value" data-product-id="' . esc_attr( $this->product->id() ) . '" data-category-id="' . esc_attr( $this->category->id() ) . '">R$0</div>';
        
        echo '</div>';
    }
    

}