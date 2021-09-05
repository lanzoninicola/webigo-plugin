<?php

require_once WEBIGO_PLUGIN_PATH . '/modules/archive-product/models/class-webigo-woo-product-bundle.php';
require_once WEBIGO_PLUGIN_PATH . '/modules/archive-product/models/class-webigo-woo-product.php';

class Webigo_Woo_Category {

    /**
     * @var string
     */
    protected $id;
    
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $slug;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var string
     */
    protected $image_url;

    /**
     * @var string
     */
    private $pod_name = 'product_cat';

    /**
     * @var array
     */
    private $custom_fields;


    public function __construct() {

        $this->custom_fields = array();
        $this->load_dependencies();

        //TODO: check if POD plugin is enabled
        $this->load_custom_fields();
    }

    protected function load_dependencies() : void
    {

    }

    /**
     * Load custom fields built with the POD Plugin
     */
    private function load_custom_fields() : void
    {

        $pod_object = (object) pods($this->pod_name);

        $pod_fields = (array) $pod_object->fields;

        foreach ($pod_fields as $pod_field => $pod_field_data) {

            $this->custom_fields[$pod_field] = array(
                'label'       => $pod_field_data['label'],
                'description' => $pod_field_data['description']
            );
        }
    }


    public function init( string $id, string $name, string $slug, string $description, string $url, string $image_url ) : void
    {

        // TODO: think a way to exclude the categories that not be showned in the UI
        $this->id          = isset( $id ) ? $id : false;
        $this->name        = isset( $name ) ? $name : false;
        $this->slug        = isset( $slug ) ? $slug : false;
        $this->description = isset( $description ) ? $description : false;
        $this->url         = isset( $url ) ? $url : false;
        $this->image_url   = isset( $image_url ) ? $image_url : false;

        $this->load_custom_fields_data();
    }

    /**
     * Loads the data of custom fields.
     */
    private function load_custom_fields_data() : void
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

    public function get_instance( ) : Webigo_Woo_Category
    {
        return $this;
    }

    public function id() : string
    {
        return $this->id;
    }

    public function name() : string
    {
        return $this->name;
    }

    public function slug() : string
    {
        return $this->slug;
    }

    public function description() : string
    {
        return $this->description;
    }

    public function url() : string
    {
        return $this->url;
    }

    public function image_url() : string
    {
        return $this->image_url;
    }

    public function custom_fields() : array
    {
        return $this->custom_fields;
    }

    /**
     * 
     * @return array of products
     */
    public function get_products() : array
    {

        // This method to retrieve the product is the same of wc_get_products() function
        $query = new WC_Product_Query( array(
            'status' => 'publish',
            'category' => array( $this->slug ),
            'return' => 'objects',
        ) );

        $wc_products = (array) $query->get_products();

        foreach ( $wc_products as $wc_product ) {
            // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
            // son qua
            // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
            $wc_product_id = (string) $wc_product->get_id();

            // dovrei interrogare il database per retrieve dei prodotti
            // in quanto l'oggetto prodotto contiene anche i parent che dovrei 
            // aggiungere all'array dei prodotti di categoria che ritornerebbe questa funzione

            // 1. fare array dei solo prodotti della categoria
            // 2. fare array dei prodotti combo che
            // 3. merge array e returns
            // 
            // o pensare di estendere questa classe per gestire la specificità del cliente
            // questa classe ritorna il risultato standard (servirà una category facade?)

        }
         
       
        return array();
    }

}