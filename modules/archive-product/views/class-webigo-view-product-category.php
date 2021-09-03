<?php



class Webigo_View_Product_Category
{

    /**
     * 
     * @var Webigo_Woo_Product_Category|Webigo_Woo_Product_Category_With_Custom_Fields
     */
    private $category;

    public function __construct($category)
    {
        $this->category = $category;
        $this->load_dependencies();
    }

    private function load_dependencies() {

        require_once WEBIGO_PLUGIN_PATH . '/modules/archive-product/views/class-webigo-view-product.php';
    }

    public function render()
    {
        echo '<li class="webigo-category-card">';
        
        echo '<div class="webigo-category-inner-card">';

        $this->render_image();

        $this->render_category_content();

        echo '</div>';
        
        echo '</li>';

        $this->render_products();
    }

    private function render_image()
    {
        echo '<div class="webigo-category-inner-image">';
        echo '<img src="' . esc_url( $this->category->image_url() ) . '" width="100px" alt loading="lazy" />';
        echo '</div>';
    }

    private function render_category_content() 
    {

        echo '<div class="webigo-category-content">';
        echo '<h2 class="webigo-category-name">' . esc_html( $this->category->name() ) . '</h2>';
        echo '<p class="webigo-category-description">' . esc_html( $this->category->description() ) . '</p>';

        $this->render_category_info();

        $this->render_expande_collapse_products();

        echo '</div>';

    }

    private function render_category_info() 
    {

        // TODO: custom fields description open on click
        echo '<div class="webigo-category-info">';

        echo '<div class="webigo-category-info-details">';
        foreach ( $this->category->custom_fields() as $custom_field => $custom_field_values ) {

            if( ! empty( $custom_field_values['value'] ) ) {
                echo '<div class="webigo-category-info-detail">';
                
                echo '<span>' . esc_html($custom_field_values['label']) . ' ' . esc_html($custom_field_values['value']) . '</span>';
                echo '<div class="webigo-category-info-detail-description" data-visibility="hidden">' . esc_html($custom_field_values['description']) . '</div>';
                
                echo '</div>';
            }
        }
        echo '</div>';

        echo '</div>';
    }

    private function render_expande_collapse_products() {

        echo '<div class="product-category-explode" data-category-id="' . esc_html( $this->category->id() ) . '">';
                
        echo '<div class="toggleExpandLabel" data-category-id="' . esc_html( $this->category->id() ) . '">';
        
                $this->arrow_expand_collapse();
        
                // $this->arrow_collapse();
            
        echo '</div>';
        echo '</div>';
    }

    private function arrow_expand_collapse() {

        echo '<div class="arrow-expand arrow-toggle-expand-collapse" data-visibility="hidden" data-category-id="' . esc_html( $this->category->id() ) . '">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M12 23C5.92487 23 1 18.0751 1 12C1 5.92487 5.92487 1 12 1C18.0751 1 23 5.92487 23 12C23 18.0751 18.0751 23 12 23ZM12 21C16.9706 21 21 16.9706 21 12C21 7.02944 16.9706 3 12 3C7.02944 3 3 7.02944 3 12C3 16.9706 7.02944 21 12 21ZM7 13H13.5858L11.2929 15.2929L12.7071 16.7071L17.4142 12L12.7071 7.29289L11.2929 8.70711L13.5858 11H7V13Z" 
                    fill="#005930"/>
                </svg>
            </div>';
    }

    // private function arrow_collapse() {

    //     echo '<div class="arrow-collapse arrow-toggle-expand-collapse" data-visibility="hidden" data-category-id="' . esc_html( $this->category->id() ) . '">
    //             <svg id="arrow-collapse" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
    //                 <path fill-rule="evenodd" clip-rule="evenodd" d="M12 23C5.925 23 1 18.075 1 12S5.925 1 12 1s11 4.925 11 11-4.925 11-11 11zm0-2a9 9 0 100-18 9 9 0 000 18zm5-10h-6.586l2.293-2.293-1.414-1.414L6.586 12l4.707 4.707 1.414-1.414L10.414 13H17v-2z" 
    //                 fill="#005930"/>
    //             </svg>
    //         </div>';

    // }

    private function render_products() {

        echo '<div class="webigo-product-list-wrapper" data-visibility="hidden">';
       
        echo '<ul class="webigo-product-list" data-category-id="' . $this->category->id() . '">';

        $products = $this->category->get_products();

        foreach ( $products as $product ) {

            $view_product = new Webigo_View_Product( $product );

            $view_product->render_product();
        }

        echo '</ul>';

        echo '</div>';

    }
}
