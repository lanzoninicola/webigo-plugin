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

class Webigo_Pod_Custom_Settings_Page extends Webigo_Pod_Fields {


    /**
     * Contains all fields with data of Custom Settings Page
     * 
     * array(
     *      field1 => array('name' => '', 'label' => '', 'description => '', 'value' => '' ) ,
     *      field2 => array('name' => '', 'label' => '', 'description => '', 'value' => '' ) ,
     * )
     * 
     * @var array
     */
    private $settings_page;

    /**
     * @param string $pod_name The name of POD created to extend the entity
     * 
     * @return Webigo_Pod_Custom_Settings
     */
    public function __construct( string $pod_name ) {

        parent::__construct( $pod_name );

        $this->settings_page = array();
        $this->load_settings_fields();
        $this->load_settings_fields_data();
    }

    /**
     * Load custom fields built with the POD Plugin
     */
    private function load_settings_fields() : void
    {

        $this->settings_page = array_merge( $this->settings_page, $this->pod_fields );

        unset( $this->pod_fields );
    }

    /**
     * Loads the data of custom fields.
     */
    private function load_settings_fields_data() : void
    {

        foreach ( array_keys( $this->settings_page) as $setting ) {
           
            $field_value = $this->pod_object->field( array( 'name' => $setting ));

            if ( isset($field_value) ) {
                $this->settings_page[$setting]['value'] = $field_value;
            }

            if ( !isset($field_value) ) {
                $this->settings_page[$setting]['value'] = '';
            }
        }
    }

    /**
     * Check if the field exists in the custom settings page.
     * 
     * @return bool 
     */
    public function is_field_exists( string $field_name ) : bool
    {
        return isset( $this->settings_page[$field_name] ) ? true : false;
    }

    /**
     * Returns the list of fields of the Custom Settings Page
     * 
     * @return array of fields 
     */
    public function fields() : array
    {
        return array_keys($this->settings_page);
    }

    /**
     * Returns the list of custom setting page fields with the data
     * 
     * @return array
     */
    public function get() : array
    {
        return !empty( $this->settings_page ) ? $this->settings_page : array();
    }

    /**
     * Returns the value of field passed as parameter
     * 
     * @param string|bool $field_name|false
     */
    public function value_of( string $field_name ) : string 
    {

        return $this->settings_page[$field_name]['value'];

    }

}