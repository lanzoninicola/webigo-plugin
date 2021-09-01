<?php

require_once WEBIGO_PLUGIN_PATH . '/modules/archive-product/includes/class-webigo-woo-product-bundle.php';
require_once WEBIGO_PLUGIN_PATH . '/modules/archive-product/includes/class-webigo-woo-product.php';

class Webigo_Woo_Product_Category {

    protected $id;
    
    protected $name;

    protected $slug;

    protected $description;

    protected $url;

    protected $image_url;

    protected $bundle_relations;

    private $wc_product_simple;

    private $wc_product_bundle;

    private $pod_name = 'product_cat';

    private $custom_fields;


    public function __construct() {

        $this->wc_product_simple = 'WC_Product_Simple';
        $this->wc_product_bundle = 'WC_Product_Yith_Bundle';
        $this->custom_fields = array();
        $this->load_dependencies();
        $this->load_custom_fields();
    }

    protected function load_dependencies() {

        require_once WEBIGO_PLUGIN_PATH . '/modules/archive-product/includes/class-webigo-woo-product-bundle-relations.php';

        $this->bundle_relations_tool = new Webigo_Woo_Product_Bundle_Relations();
    }

    private function load_custom_fields() {

        $pod_object = pods($this->pod_name);

        $pod_fields = $pod_object->fields;

        foreach ($pod_fields as $pod_field => $pod_field_data) {

            $this->custom_fields[$pod_field] = array(
                'label'       => $pod_field_data['label'],
                'description' => $pod_field_data['description']
            );
        }
    }


    public function init( $id, $name, $slug, $description, $url, $image_url ) {

        // TODO: think a way to exclude the categories that not be showned in the UI
        $this->id          = isset( $id ) ? $id : false;
        $this->name        = isset( $name ) ? $name : false;
        $this->slug        = isset( $slug ) ? $slug : false;
        $this->description = isset( $description ) ? $description : false;
        $this->url         = isset( $url ) ? $url : false;
        $this->image_url   = isset( $image_url ) ? $image_url : false;

        $this->load_custom_fields_data();
    }

    public function load_custom_fields_data()
    {
        foreach ($this->custom_fields as $custom_field => $custom_field_data) {

            $category_id     = absint( $this->id );
            $term_meta_value = (array) get_term_meta( $category_id, $custom_field );

            if ( isset( $term_meta_value ) & is_array( $term_meta_value ) & !empty( $term_meta_value ) ) {

                $this->custom_fields[$custom_field]['value'] = $term_meta_value[0];
            } else {

                $this->custom_fields[$custom_field]['value'] = false;
            }

        }
    }

    public function get_instance( ) {

        return $this;

    }

    public function id(){

        return $this->id;

    }

    public function name(){

        return $this->name;

    }

    public function slug(){

        return $this->slug;

    }

    public function description(){

        return $this->description;

    }

    public function url(){

        return $this->url;

    }

    public function image_url(){

        return $this->image_url;

    }

    public function custom_fields()
    {

        return $this->custom_fields;

    }

    /**
     * 
     * @return array of products
     */
    public function get_products() {

        // This method to retrieve the product is the same of wc_get_products() function
        $query = new WC_Product_Query( array(
            'status' => 'publish',
            'category' => array( $this->slug ),
            'return' => 'objects',
        ) );

        $products = $query->get_products();
         
        $output_products = array();
       
        foreach ($products as $product) {
            
            $product_data_type = get_class($product);
            
            $id = $product->get_id();
            
            if ( $product_data_type === $this->wc_product_simple ) {
                
                $product_object = new Webigo_Woo_Product($id);

                // $this->bundle_relations->add_item( $product_object );

                array_push($output_products, $product_object);
            }

            if ( $product_data_type === $this->wc_product_bundle ) {

                $product_bundle_object = new Webigo_Woo_Product_Bundle($id);

                // $this->bundle_relations->add_item( $product_bundle_object );
                $this->bundle_relations_tool->load_relations( $product_bundle_object );
                
                array_push($output_products, $product_bundle_object);
            }

            // var_dump($this->products); die;
            // YITH_WC_Bundled_Item each product item inside the Bundle
            // var_dump($fo->get_bundled_items());
        }

        return $output_products;

    }

}