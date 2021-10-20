<?php



class Webigo_View_Product_Zoom {

        /**
     * @var Webigo_Woo_Product|Webigo_Woo_Product_Bundle
     */
    private $product;

    /**
     * @var Webigo_Woo_Category
     */
    private $category;

    /**
     * 
     * @var Webigo_View_Product_Price
     */
    private $view_product_price;

    /**
     * 
     * @var Webigo_View_Product_Image
     */
    private $view_product_image;

    /**
     * 
     * @var Webigo_View_Product_Bundled_Items
     */
    private $view_product_bundled_items;

    public function __construct( $product , $category )
    {

        $this->product = $product;
        $this->category = $category;
        $this->load_dependencies();
    }

    private function load_dependencies()
    {

        $this->view_product_price = new Webigo_View_Product_Price( $this->product );

        $this->view_product_image = new Webigo_View_Product_Image( $this->product, $this->category );

        $this->view_category_attributes = new Webigo_View_Category_Attributes( $this->category );

        require_once WEBIGO_PLUGIN_PATH . '/modules/archive-product/views/class-webigo-view-product-bundled-items.php';
        $this->view_product_bundled_items = new Webigo_View_Product_Bundled_Items( $this->product );
    }


    public function render() : string
    {

        $output = '<div class="wbg-product-zoom" data-product-id="' . esc_attr( $this->product->id() ) . '" data-category-id="' . esc_attr( $this->category->id() ) . '" data-visibility="hidden" >';

        $output .= $this->product_sheet();

        $output .= $this->zoom_image();

        $output .= '<div class="wbg-product-zoom-footer">';
        $output .=  $this->close_sheet_button();
        $output .= '</div>';
        
        $output .= '</div>';

        return $output;

    }

    private function zoom_image() : string
    {
        
        $output = '<div class="wbg-product-zoom-image-wrapper" data-visibility="hidden">';
        $output .= '<h3 class="wbg-product-name">' . esc_html( $this->product->name() ) . '</h3>';
        $output .=  $this->view_product_image->render();
        $output .= '</div>';

        return $output;
    }

    private function product_sheet() : string
    {

        $output = '<div class="wbg-product-sheet-wrapper" data-visibility="hidden">';
       
        $output .= '<div class="wbg-product-head">';
        
        $output .= '<div class="wbg-product-head-col1">';
            $output .=  $this->view_product_image->render();
        $output .= '</div>';
        
        $output .= '<div class="wbg-product-head-col2">';
            $output .= '<h3 class="wbg-product-name">' . esc_html( $this->product->name() ) . '</h3>';
            $output .= $this->view_product_price->render();
        $output .= '</div>';

        $output .= '</div>';

        $output .= '<div class="wbg-product-attribute-category">';
        $output .= $this->view_category_attributes->render();
        $output .= '</div>';

        $output .= '<div class="wbg-product-attributes">';
        $output .= '</div>';

        $output .= '<div class="wbg-product-description">';
        $output .=  $this->product->description();
        $output .= '</div>';

        $output .= '</div>';
        
        $output .= $this->view_product_bundled_items->render();

        return $output;
    }

    private function close_sheet_button()
    {

        $output = '<div class="wbg-button wbg-primary-button wbg-product-zoom-close" data-action-state="idle">';
        $output .= '<span class="wbg-button-label">VOLTAR</span>';
        $output .= '</div>';

        return $output;
    }

}
