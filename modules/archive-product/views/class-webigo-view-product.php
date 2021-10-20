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
     * 
     * @var Webigo_View_Product_Image
     */
    private $view_product_image;

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
     * Dependency
     * 
     * @var Webigo_View_Product_Zoom
     */
    private $product_zoom;
    

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

        require_once WEBIGO_PLUGIN_PATH . '/modules/archive-product/views/class-webigo-view-product-image.php';
        $this->view_product_image = new Webigo_View_Product_Image( $this->product, $this->category );

        require_once WEBIGO_PLUGIN_PATH . '/modules/archive-product/views/class-webigo-view-product-quantity.php';
        $this->product_quantity = new Webigo_View_Product_Quantity( $this->product, $this->category );

        require_once WEBIGO_PLUGIN_PATH . '/modules/archive-product/views/class-webigo-view-product-whatsapp.php';
        $this->product_whatsapp = new Webigo_View_Product_Whatsapp( $this->product );

        require_once WEBIGO_PLUGIN_PATH . '/modules/archive-product/views/class-webigo-view-product-zoom.php';
        $this->product_zoom = new Webigo_View_Product_Zoom( $this->product, $this->category );

        // TODO Future feature Below is a module dependency, so i might to query the registry to load this module
        // Because I can unregister the module. The app must work but the specific funcionality no
        require_once WEBIGO_PLUGIN_PATH . '/modules/add-to-cart/views/class-webigo-view-add-to-cart.php';
        $this->add_to_cart = new Webigo_View_Add_To_Cart();
    }

    public function render() : string
    {

        $output = '<li class="wbg-product" data-product-id="' . esc_html( $this->product->id() ) . '">';
            
            $output .= $this->product_zoom->render();

            $output .= $this->render_combo_promotion_message();

            $output .= '<div class="wbg-product-container">';

                if ( $this->product->is_sale() ) {

                    $output .= $this->render_sale_banner();
                }

                $output .= $this->render_product_col1();
            
                $output .= $this->render_product_col2();
                
            $output .= '</div>';
        
        $output .= '</li>';

        return $output;
    }


    private function render_product_col1() : string
    {

        $output = '<div class="wbg-product-details-col1">';
        
        $output .= $this->view_product_image->render();

        $output .= $this->product_info->render_details_button();

        $output .= '</div>';

        return $output;

    }

    private function render_product_col2() {

        $output = '<div class="wbg-product-details-col2">';
                
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
        }
    
        $output .= '</div>';

        return $output;
        
    }
    

    private function render_subtotal() : string
    {
        
        $output =  '<div class="wbg-product-subtotal">';
        
        $output .= '<div class="wbg-product-subtotal-label">Subtotal</div>';
        $output .= '<div class="wbg-product-subtotal-wrapper" data-product-id="' . esc_attr( $this->product->id() ) . '" data-category-id="' . esc_attr( $this->category->id() ) . '">';
        $output .= '<span class="wbg-price-symbol">R$</span>';
        $output .= '<span class="wbg-product-subtotal-value">0</span>';
        $output .= '</div>';
        
        $output .= '</div>';

        return $output;
    }


    private function render_sale_banner() : string 
    {

        $output =  '<div class="wbg-product-sale-banner">';
        $output .= '<span>promoção</span>';
        $output .= '</div>';

        return $output;
    }

    private function render_combo_promotion_message() : string
    {
        
        $output = '';
        
        if ( $this->product->type() === 'bundle' && $this->category->name() !== 'Combos') {
            
            $output .= '<div class="wbg-product-combo-wrapper">';

            $output .= '<p class="wbg-product-combo-message">Descubra nossa ' . esc_html( $this->product->name() ) . '.</p>';
            
            $output .= '<p class="wbg-product-combo-message lowercase">Seu ' . esc_html( $this->category->name() ) . ' junto com nossas outras criações a um preço mais barato.</p>';

            $output .= '</div>';
        
        }

        return $output;
    }
    

}