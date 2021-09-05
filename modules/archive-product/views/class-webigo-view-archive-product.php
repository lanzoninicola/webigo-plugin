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
    public function render_archive_products( object $database ) : void
    {

        echo '<div class="webigo-archive-product-container">';

        echo '<ul class="webigo-archive-product-wrapper">';

        $categories = (array) $database->get_categories();

        foreach ( $categories as $category ) {

            $product_cat_view = new Webigo_View_Category( $category, $database );

            $product_cat_view->render();

        }
        
        echo '</ul>';

        echo '</div>';
    
        
    }


}