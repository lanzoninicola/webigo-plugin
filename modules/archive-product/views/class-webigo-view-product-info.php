<?php



class Webigo_View_Product_Info {

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
    
    public function __construct( $product, $category ) {

        $this->product = $product;
        $this->category = $category;
        $this->load_dependencies();
    }

    private function load_dependencies() : void
    {

        require_once WEBIGO_PLUGIN_PATH . '/modules/archive-product/views/class-webigo-view-product-price.php';
        $this->view_product_price = new Webigo_View_Product_Price( $this->product );
    }

    public function render() : string
    {

        $output = '<div class="wbg-product-info">';

        $output .= $this->render_name();

        $output .= $this->render_description();

        $output .= $this->view_product_price->render();

        $output .= '</div>';

        return $output;
    }

    private function render_name() : string
    {

        $output = '<h3 class="wbg-product-name">' . esc_html( $this->product->name() ) . '</h3>';

        return $output;
    }

    private function render_description() : string
    {

        $output = '<div class="wbg-product-description-container">';
        
        $output .= '<p class="wbg-product-description" data-visibility="clamped">' . esc_html( $this->product->description() ) . '</p>';

        $output .= '</div>';

        return $output;
        
    }

    public function render_details_button() : string
    {

        $output = '<div class="action-buttons wbg-zoom-product-sheet" data-product-id="' . esc_attr( $this->product->id() ) . '" data-category-id="' . esc_attr( $this->category->id() ) . '">';

        $output .= '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="color: var(--hzbi-green);">
        <path fill-rule="evenodd" clip-rule="evenodd" d="M1 12C1 18.0751 5.92487 23 12 23C18.0751 23 23 18.0751 23 12C23 5.92487 18.0751 1 12 1C5.92487 1 1 5.92487 1 12ZM21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12ZM13.0036 13.9983H14.003V15.9983H10.003V13.9983H11.003V11.9983H10.003V9.99835H13.0036V13.9983ZM13.0007 7.99835C13.0007 8.55063 12.5528 8.99835 12.0003 8.99835C11.4479 8.99835 11 8.55063 11 7.99835C11 7.44606 11.4479 6.99835 12.0003 6.99835C12.5528 6.99835 13.0007 7.44606 13.0007 7.99835Z" fill="rgba(0, 0, 0, 0.6)"/>
      </svg>';

        $output .= '<span>DETALHES</span>';

        $output .= '</div>';

        return $output;
    }




}