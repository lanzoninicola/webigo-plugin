<?php



class Webigo_Woo_Product_Category {

    protected $id;
    
    protected $name;

    protected $description;

    protected $url;

    protected $image_url;

    public function __construct() {
    }

    public function init( $id, $name, $description, $url, $image_url ) {

        $this->id = isset( $id ) ? $id : false;
        $this->name = isset( $name ) ? $name : false;
        $this->description = isset( $description ) ? $description : false;;
        $this->url = isset( $url ) ? $url : false;;
        $this->image_url = isset( $image_url ) ? $image_url : false;;
    }

    public function get_instance( ) {

        return $this;

    }

    public function name(){

        return $this->name;

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


}