<?php


class Webigo_Woo_Products_Categories {

    /**
     * @var Webigo_Products
     */
    protected $products;

    /**
     * @var Webigo_Products_Categories
     */
    protected $categories;


    public function __construct(object $products, object $categories) 
    {
        $this->products = $products;
        $this->categories = $categories;
    }

    protected function get_wc_products_category ( string $category_id ) : array
    {

        $category_object = (object) $this->categories->get_category( $category_id );

        $query = new WC_Product_Query( array(
            'status' => 'publish',
            'category' => array( $category_object->slug() ),
            'return' => 'objects',
        ) );

        return $query->get_products();
    }

    /**
     * 
     * @return array of Webigo_Woo_Product|Webigo_Woo_Product_Bundle
     */
    public function get_products_category( string $category_id ) : array 
    {
        $result = array();

        $wc_products = $this->get_wc_products_category( $category_id );

        foreach ( $wc_products as $wc_product ) {

            $wc_product_id = $wc_product->get_id();

            $product = $this->products->get_product( $wc_product_id );

            array_push( $result, $product );
        }

        return $result;
    }


    

}