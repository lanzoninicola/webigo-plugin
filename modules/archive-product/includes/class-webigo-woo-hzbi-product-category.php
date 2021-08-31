<?php

require_once WEBIGO_PLUGIN_PATH . '/modules/archive-product/includes/class-webigo-woo-product-category.php';

class Webigo_Woo_Hzbi_Product_Category extends Webigo_Woo_Product_Category{


    private $pod_name = 'product_cat';

    private $pod_object;

    public $abv_value;

    public $abv_description;

    public $ibu_value;

    public $ibu_description;




    // TODO: adding ABV, IBU info from POD FRAMEWORK

    public function __construct() {

        parent::__construct();

        $this->pod_object = pods($this->pod_name);

        $this->set_abv_description();
        $this->set_ibu_description();
    }

    private function set_abv_description() {

        $pod_field = $this->pod_object->fields('abv');

        $this->abv_description = $pod_field['description'];

    }

    private function set_ibu_description() {

        $pod_field = $this->pod_object->fields('ibu');

        $this->ibu_description = $pod_field['description'];

    }

    public function init($id, $name, $description, $url, $image_url) {

        parent::init($id, $name, $description, $url, $image_url);

        $abv_value = get_term_meta( absint( $this->id ), 'abv' );

        // var_dump($this->id);

        $ibu_value = get_term_meta( absint( $this->id ), 'ibu' );

        if (isset( $abv_value ) & is_array( $abv_value ) & !empty( $abv_value )) {

            $this->abv_value = $abv_value[0];

        }

        if (isset( $ibu_value ) & is_array( $ibu_value ) & !empty( $ibu_value )) {

            $this->ibu_value = $ibu_value[0];

        }

        // $this->abv =  is_null( $abv_value[0] ) ? false : $abv_value[0];

        // $this->ibu =  is_null( $ibu_value[0] ) ? false : $ibu_value[0];


    }


    // public function foo() {

    //     $pod_name = 'product_cat';
    //     $pod_field = 'abv';

    //     $pod_theme_editor = pods($pod_name);

    //     var_dump(get_term_meta(34, 'abv')); die;

    //     // returns all related data from the pod field value
    //     var_dump($pod_theme_editor); die;

    // }

}