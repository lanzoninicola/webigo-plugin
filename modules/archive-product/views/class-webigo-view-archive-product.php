<?php


class Webigo_View_Archive_Product {


    public function __construct(  ) {

        $this->load_dependencies();
        
    }


    private function load_dependencies() {
        
        require_once WEBIGO_PLUGIN_PATH . '/modules/archive-product/views/class-webigo-view-category.php';
    }


    /**
     * 
     * @param object Webigo_Database_Facade
     */
    public function render_archive_products( object $database ) : string
    {

        $output = '<div class="wbg-archive-product-container" data-visibility="visible">';

        $output .= '<ul class="wbg-archive-product-wrapper">';

        $categories = (array) $database->get_categories();

        foreach ( $categories as $category ) {

            $product_cat_view = new Webigo_View_Category( $category, $database );

            $output .= $product_cat_view->render();

        }
        
        $output .= '</ul>';

        $output .= '</div>';

        return $output;
        
    }
}