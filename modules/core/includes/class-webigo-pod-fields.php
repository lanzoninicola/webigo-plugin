<?php


class Webigo_Pod_Fields {

    /**
     * @var object PodArray
     */
    protected $pod_object;
    
    /**
     * @var string
     */
    protected $pod_name;


    /**
     * Contains all fields of a pod requested in the constructor.
     * 
     * array(
     *      field1 => array('name' => '', 'label' => '', 'description => '' ) ,
     *      field2 => array('name' => '', 'label' => '', 'description => '' ) ,
     * )
     * 
     * @var array
     */
    protected $pod_fields;

    /**
     * @param string $pod_name The name of POD created to extend the entity
     * 
     * @return Webigo_Pod_Fields
     */
    public function __construct( string $pod_name ) {

        $this->pod_name      = $pod_name;
        $this->pod_fields    = array();
        $this->pod_object    = (object) pods($pod_name);

        $this->load_fields();
    }

    /**
     * Load the fields built with the POD Plugin
     */
    private function load_fields() : void
    {
        if ( !isset($this->pod_name) ) {
            return;
        }

        if ( $this->pod_object instanceof stdClass ) {
            throw new Exception('==== Webigo_Pod_Fields Class - The pod "' .  $this->pod_name . '" does not exist ====');
        }

        $_pod_fields = (array) $this->pod_object->fields;

        foreach ($_pod_fields as $pod_field => $pod_field_data) {
            $this->pod_fields[$pod_field] = array(
                'name'        => $pod_field_data['name'],
                'label'       => $pod_field_data['label'],
                'description' => $pod_field_data['description'],
            );
        }
    }

}