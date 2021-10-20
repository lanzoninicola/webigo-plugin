<?php




class Webigo_View_Product_Bundled_Items {

    /**
     * @var Webigo_Woo_Product|Webigo_Woo_Product_Bundle
     */
    private $product;

    /**
     * 
     * @var array of YITH_WC_Bundled_Item
     */
    private $bundled_items;

    /**
     * Here is stored final price of bundle item
     * @var float
     */
    private $bundle_final_price;

    /**
     * Here is stored the sum of bundled items final price
     * @var float
     */
    private $bundled_items_total_price;

    public function __construct( object $product )
    {
        $this->product = $product;
        $this->bundle_final_price = $product->final_price();

        $this->load_dependencies();
    }

    private function load_dependencies()
    {
        require_once WEBIGO_PLUGIN_PATH . '/modules/archive-product/models/class-webigo-woo-product.php';
    }


    public function render()
    {
        if ( $this->product->type() !== 'bundle' ) {
            return;
        }

        $this->bundled_items = $this->product->get_bundled_items();

        $output = '<div class="wbg-combo-info-container">';

        $output .= '<h4>Composição do Combo</h4>';

        foreach ( $this->bundled_items as $bundled_item ) {

            $product_id = $bundled_item->get_product_id();
            $quantity   = $bundled_item->get_quantity();

            $wbg_product = new Webigo_Woo_Product( $product_id );

            $output .= '<div class="wbg-combo-single-item-wrapper">';

            $output .= '<div class="wbg-combo-single-product-qty">#';
            $output .= esc_html( $quantity );
            $output .= '</div>';

            $output .= '<div class="wbg-combo-single-product-name">';
            $output .= esc_html( $wbg_product->name() );
            $output .= '</div>';
            
            $output .= '</div>';

            $this->total_price_bundled_items( $wbg_product, $quantity );
        }

        if ( Webigo_Archive_Product_Settings::SHOW_SAVING_COMBO_SHEET === true ) {
            $output .= $this->render_saving();
        }

        $output .= '</div>';

        return $output;


    }


    private function total_price_bundled_items( $product, $quantity )
    {
        $total_item_price = $product->final_price() * $quantity;

        $this->bundled_items_total_price = $this->bundled_items_total_price + $total_item_price;
    }

    private function render_saving()
    {
        $saving = $this->bundled_items_total_price - $this->bundle_final_price;

        $output = '';

        if ( $saving > 0 ) {
            $output .= '<div class="wbg-combo-saving">';
            $output .= 'Ao comprar esta combinação você pode economizar ' . esc_html( esc_html( number_format((float)$saving, 2, ',', '')) ) . ' reais';
            $output .= '</div>';
        }

        return $output;
    }


    


}