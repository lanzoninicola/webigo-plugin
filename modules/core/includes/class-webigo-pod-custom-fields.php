<?php

require_once WEBIGO_PLUGIN_PATH . 'modules/core/includes/class-webigo-pod-fields.php';

/**
 * This class is responsible to encapsulate
 * the custom fields and their data created with the POD Framework.
 * 
 * The custom fields are new fields that extend a Wordpress entity
 * 
 * The data are retrieved with "get_term_meta" WP function,
 * it requires the ID of entity
 * 
 */

class Webigo_Pod_Custom_Fields extends Webigo_Pod_Fields {


    /**
     * The entity that owns the custom fields
     * 
     * @var object
     */
    private $entity;

    /**
     * Depending on type POST or TERM the related function is called
     * 
     * - POST: get_post_meta()
     * - TERM: get_term_meta()
     * 
     * A POST can be a PRODUCT and TERM can be a CATEGORY
     * 
     * @var string
     */
    private $entity_type;

     /**
     * This array contains the custom fields and their data
     * 
     * @var array
     */
    private $custom_fields;

    /**
     * @param string $pod_name The name of POD created to extend the entity
     * @param object $entity The object that owns the custom fields
     * @param string $entity_type => 'post'|'term' (Post is a product, Term is a category)
     * 
     * @return Webigo_Pod_Custom_Fields
     */
    public function __construct( string $pod_name , object $entity, string $entity_type ) {

        parent::__construct( $pod_name );

        $this->entity        = $entity;
        $this->entity_type   = $entity_type;
        $this->custom_fields = array();

        $this->load_custom_fields();
        $this->load_custom_fields_data();
    }


    /**
     * Load custom fields built with the POD Plugin
     */
    private function load_custom_fields() : void
    {

        $this->custom_fields = array_merge( $this->custom_fields, $this->pod_fields );

        unset( $this->pod_fields );

    }

    /**
     * Loads the data of custom fields.
     */
    private function load_custom_fields_data() : void
    {

        foreach ( array_keys($this->custom_fields) as $custom_field ) {

            if ( $this->is_term() ) {
                $meta_value = (array) get_term_meta( $this->entity->id(), $custom_field );
            }

            if ( $this->is_post() ) {
                $meta_value = (array) get_post_meta( $this->entity->id(), $custom_field );
            }

            if ( isset( $meta_value ) & is_array( $meta_value ) & !empty( $meta_value ) ) {
                $this->custom_fields[$custom_field]['value'] = $meta_value[0];
            } else {
                $this->custom_fields[$custom_field]['value'] = false;
            }

        }
    }

    /**
     * Verify if the entity_type passed is "term"
     * 
     *  @return bool
     */
    private function is_term() : bool
    {
        return isset( $this->entity_type ) && ($this->entity_type === 'term');
    }

    /**
     * Verify if the entity_type passed is "post"
     * 
     *  @return bool
     */
    private function is_post() : bool
    {
        return isset( $this->entity_type ) && ($this->entity_type === 'post');
    }

    /**
     * Returns the list of entity custom fields with the data
     * 
     * @return array
     */
    public function get() : array
    {

        return !empty( $this->custom_fields ) ? $this->custom_fields : array();
    }
}