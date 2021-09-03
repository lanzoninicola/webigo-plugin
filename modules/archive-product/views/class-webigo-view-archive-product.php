<?php


class Webigo_View_Archive_Product {


    /**
     * 
     * @var Webigo_Woo_Hzbi_Product_Categories
     */
    private $product_cats;

    public function __construct() {

        $this->product_cats = array();

        $this->load_dependencies();
        $this->load_views();
    }


    private function load_dependencies() {

        require_once WEBIGO_PLUGIN_PATH . '/modules/archive-product/includes/class-webigo-woo-product-categories.php';

        

        $this->product_cats = new Webigo_Woo_Product_Categories();

    }

    private function load_views() {

        require_once WEBIGO_PLUGIN_PATH . '/modules/archive-product/views/class-webigo-view-product-category.php';

    }


    public function render_archive_products() {

        echo '<div class="webigo-archive-product-container">';

        echo '<ul class="webigo-archive-product-wrapper">';

        $categories = $this->product_cats->get_categories();

        foreach ( $categories as $category ) {

            $product_cat_view = new Webigo_View_Product_Category($category);

            $product_cat_view->render();

        }
        
        echo '</ul>';

        echo '</div>';
    
        
    }


}