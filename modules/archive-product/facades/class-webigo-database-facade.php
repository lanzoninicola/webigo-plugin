<?php



class Webigo_Database_Facade {

    /**
     * @var Webigo_Woo_Products
     */
    private $products;

    
    /**
     * @var Webigo_Woo_Categories
     */
    private $categories;


    /**
     * @var Webigo_Woo_Products_Categories_Facade
     */
    private $products_categories;


    public function __construct()
    {
        $this->load_dependencies();
    }

    private function load_dependencies() : void
    {
        require_once WEBIGO_PLUGIN_PATH . '/modules/archive-product/models/class-webigo-woo-products.php';
        require_once WEBIGO_PLUGIN_PATH . '/modules/archive-product/models/class-webigo-woo-categories.php';
        require_once WEBIGO_PLUGIN_PATH . '/modules/archive-product/facades/class-webigo-woo-products-categories-facade.php';
        
        $this->products = new Webigo_Woo_Products();
        $this->categories = new Webigo_Woo_Categories();
        $this->products_categories = new Webigo_Woo_Products_Categories_Facade( );
        
    }

    public function load() : void
    {
        $this->products->load();
        $this->categories->load();
        $this->products_categories->load( $this->products, $this->categories );
    }

    /**
     * @return array of Webigo_Woo_Product|Webigo_Woo_Product_Bundle
     */
    public function get_products() : array
    {
        return $this->products->get_products();
    }

    /**
     * @param string The id of product
     * @return Webigo_Woo_Product|Webigo_Woo_Product_Bundle
     */
    public function get_product( string $id ) : ?object
    {
        return $this->products->get_product( $id );
    }

    /**
     * @return array of Webigo_Woo_Category
     */
    public function get_categories() : array 
    {
        return $this->categories->get_categories();
    }


    public function get_products_category( string $category_id ) : array
    {

        $products_categories = (array) $this->products_categories->get_products_category( $category_id );
        // $products_categories = (array) $this->products_categories->get_products_category( $category_id );

        return isset( $products_categories ) ? $products_categories : array();
    }


}