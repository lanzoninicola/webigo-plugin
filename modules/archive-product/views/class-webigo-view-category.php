<?php



class Webigo_View_Category
{

    /**
     * 
     * @var Webigo_Woo_Category
     */
    private $category;

    /**
     * 
     * @var Webigo_Database_Facade
     */
    private $database;

    
    /**
     * @var Webigo_View_Category_Attributes
     */
    private $view_category_attributes;


    public function __construct( object $category, object $database )
    {
        $this->category = $category;
        $this->database = $database;
        $this->load_dependencies();
    }

    private function load_dependencies() {

        require_once WEBIGO_PLUGIN_PATH . '/modules/archive-product/views/class-webigo-view-category-attributes.php';
        require_once WEBIGO_PLUGIN_PATH . '/modules/archive-product/views/class-webigo-view-product.php';

        $this->view_category_attributes = new Webigo_View_Category_Attributes( $this->category );

    }

    public function render() : string
    {
        $output = '<li class="wbg-category-card">';
        
        $output .= '<div class="wbg-category-inner-card">';

        $output .= $this->render_image();

        $output .= $this->render_category_content();

        $output .= '</div>';

        $output .= $this->render_products();

        $output .= '</li>';

        return $output;
    }

    private function render_image() : string
    {
        $output = '<div class="wbg-category-inner-image">';
        $output .= '<img src="' . esc_url( $this->category->image_url() ) . '" width="100px" alt loading="lazy" />';
        $output .= '</div>';

        return $output;
    }

    private function render_category_content() : string
    {

        $output = '<div class="wbg-category-content">';
        $output .= '<h2 class="wbg-category-name">' . esc_html( $this->category->name() ) . '</h2>';
        $output .= '<p class="wbg-category-description">' . esc_html( $this->category->description() ) . '</p>';

        $output .= $this->render_category_info();

        $output .= $this->render_expande_collapse_products();

        $output .= '</div>';

        return $output;

    }

    private function render_category_info() : string 
    {

        $output = '<div class="wbg-category-info">';

        $output .= $this->view_category_attributes->render();

        $output .= '</div>';

        return $output;
    }

    private function render_expande_collapse_products() : string
    {

        $output = '<div class="product-category-toggle" data-category-id="' . esc_attr( $this->category->id() ) . '" data-action-state="collapsed">';
        
        $output .= '<span></span>';
        
        $output .= '<div class="toggleExpandLabel arrow-toggle-expand-collapse" data-category-id="' . esc_attr( $this->category->id() ) . '" data-action-state="collapsed">';
        
                $output .= $this->arrow_expand_collapse();
        
                // $this->arrow_collapse();
            
        $output .= '</div>';
        $output .= '</div>';

        return $output;
    }

    private function arrow_expand_collapse() : string
    {

        $output = '<div class="action-buttons arrow-expand arrow-toggle-expand-collapse" data-visibility="visible" data-category-id="' . esc_html( $this->category->id() ) . '">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M12 23C5.92487 23 1 18.0751 1 12C1 5.92487 5.92487 1 12 1C18.0751 1 23 5.92487 23 12C23 18.0751 18.0751 23 12 23ZM12 21C16.9706 21 21 16.9706 21 12C21 7.02944 16.9706 3 12 3C7.02944 3 3 7.02944 3 12C3 16.9706 7.02944 21 12 21ZM7 13H13.5858L11.2929 15.2929L12.7071 16.7071L17.4142 12L12.7071 7.29289L11.2929 8.70711L13.5858 11H7V13Z" 
                    fill="#005930"/>
                </svg>
            </div>';

        return $output;
    }

    // private function arrow_collapse() {

    //     $output = '<div class="arrow-collapse arrow-toggle-expand-collapse" data-visibility="hidden" data-category-id="' . esc_html( $this->category->id() ) . '">
    //             <svg id="arrow-collapse" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
    //                 <path fill-rule="evenodd" clip-rule="evenodd" d="M12 23C5.925 23 1 18.075 1 12S5.925 1 12 1s11 4.925 11 11-4.925 11-11 11zm0-2a9 9 0 100-18 9 9 0 000 18zm5-10h-6.586l2.293-2.293-1.414-1.414L6.586 12l4.707 4.707 1.414-1.414L10.414 13H17v-2z" 
    //                 fill="#005930"/>
    //             </svg>
    //         </div>';

    // }

    private function render_products() : string
    {

        $output = '<div class="wbg-product-list-wrapper" data-category-id="' . esc_attr( $this->category->id() ) . '" data-visibility="hidden">';
       
        $output .= '<ul class="wbg-product-list">';

        $products = (array) $this->database->get_products_category( $this->category->id() );

        foreach ( $products as $product ) {

            $view_product = new Webigo_View_Product( $product, $this->category );

            $output .= $view_product->render();

        }

        $output .= '</ul>';

        $output .= $this->collapse_category();

        $output .= '</div>';

        return $output;

    }

    private function collapse_category() : string
    {

        $output = '<div class="wbg-category-collapse-wrapper">';
        
        $output .= '<div class="action-buttons wbg-category-collapse" data-category-id="' . esc_attr( $this->category->id() ) . '">';

        $output .= '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path fill-rule="evenodd" clip-rule="evenodd" d="M9.00888 8.125L17.3169 16.4331L16.4331 17.3169L8.125 9.00888V13.75H6.875V6.875H13.75V8.125H9.00888Z" fill="white"/>
        <circle cx="12" cy="12" r="11" stroke="white" stroke-width="2"/>
        </svg>';

        $output .= '<span>Voltar</span>';

        $output .= '</div>';

        $output .= '</div>';

        return $output;

        return $output;
    }
}
