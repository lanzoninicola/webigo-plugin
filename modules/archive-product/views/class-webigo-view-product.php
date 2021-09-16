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
     * Dependency
     * 
     * @var Webigo_View_Product_Whatsapp
     */
    private $product_whatsapp;

    /**
     * 
     * @param Webigo_Woo_Product|Webigo_Woo_Product_Bundle
     */
    public function __construct( $product , $category ) {

        $this->product = $product;
        $this->category = $category;

        $this->custom_fields = array();

        $this->load_dependencies();

    }

    private function load_dependencies() : void
    {

        require_once WEBIGO_PLUGIN_PATH . '/modules/archive-product/views/class-webigo-view-product-info.php';
        $this->product_info = new Webigo_View_Product_Info( $this->product, $this->category );

        require_once WEBIGO_PLUGIN_PATH . '/modules/archive-product/views/class-webigo-view-product-quantity.php';
        $this->product_quantity = new Webigo_View_Product_Quantity( $this->product, $this->category );

        require_once WEBIGO_PLUGIN_PATH . '/modules/archive-product/views/class-webigo-view-product-whatsapp.php';
        $this->product_whatsapp = new Webigo_View_Product_Whatsapp( $this->product );

        // TODO Future feature Below is a module dependency, so i might to query the registry to load this module
        // Because I can unregister the module. The app must work but the specific funcionality no
        require_once WEBIGO_PLUGIN_PATH . '/modules/add-to-cart/views/class-webigo-view-add-to-cart.php';
        $this->add_to_cart = new Webigo_View_Add_To_Cart();
    }

    public function render() : string
    {

        $output = '<li class="wbg-product" data-product-id="' . esc_html( $this->product->id() ) . '">';
            $output .= '<div class="wbg-product-container">';

                $output .= $this->product_info->render_image();
            
                $output .= '<div class="wbg-product-inner-details">';
                $output .= $this->product_info->render();
      
                if ( $this->product_whatsapp->is_whatsapp_required() ) {
                    $output .= $this->product_whatsapp->render();
                }

                if ( !$this->product_whatsapp->is_whatsapp_required() ) {
                    $output .= $this->product_quantity->render();    
      
                    $output .=  '<div class="wbg-product-footer">';
                        $output .= $this->render_subtotal();
                        $output .= $this->add_to_cart->render( $this->product, $this->category );
                        $output .= '</div>';
                    $output .= '</div>';
                }

                
            $output .= '</div>';
        $output .= '</li>';

        return $output;
    }

    

    private function render_subtotal() : string
    {
        
        $output =  '<div class="wbg-product-subtotal">';
        
        $output .= '<div class="wbg-product-subtotal-label">Subtotal</div>';
        $output .= '<div class="wbg-product-subtotal-value" data-product-id="' . esc_attr( $this->product->id() ) . '" data-category-id="' . esc_attr( $this->category->id() ) . '">R$0</div>';
        
        $output .= '</div>';

        return $output;
    }
    

}