<?php

/**
 *  Class responsible to handle the ajax request
 * 
 *  Here is managed the sanitization and validation process of POST request data
 */
class Webigo_Add_To_Cart_Request {

    /**
     * Action name used in the WP Hooks to handle the AJAX request.
     * 
     * @var string
     */
    private $action_name;

     /**
     * Array with sanitized input
     * 
     * @var array
     */
    private $post_data_sanitized;


    public function __construct( string $wp_action_name)
    {
        $this->action_name = $wp_action_name;
    }

    /**
     * Validation of add to cart request 
     * @return bool
     */
    public function is_valid(): bool
    {

        $_action     = $this->post_data_sanitized['action'];
        $_nonce      = $this->post_data_sanitized['nonce'];
        $_product_id = $this->post_data_sanitized['product_id'];

        if (
            ($_action !== $this->action_name)  ||
            $_product_id === 0 ||
            !$_nonce ||
            !wp_verify_nonce($_nonce, $this->action_name)
        ) {

            //TODO: Priority 1 add-to-cart: managing this response - Send an email to dev o record wp_errors
            wp_send_json_error([
                'message'     => 'The request is not valid.',
                'requestData' => array(
                    'action'     =>  $this->action_name,
                    'nonce'      =>  $_nonce,
                    'product_id' =>  $_product_id
                ),
                'nonceResult' => wp_verify_nonce($_nonce, $this->action_name)
            ]);

            return false;
        }

        return true;
    }

    /**
     * Data sent in post are sanitized and saved inside the object ($this->post_data_sanitized)
     * 
     * @return void
     */
    public function sanitize_input(): void
    {

        $post_data_trimmed = array_filter( $_POST, array( $this, 'trimmer' ) );

        // here is declaring which POST data are allowed and the filter type to apply for each of them
        // https://www.php.net/manual/en/function.filter-var-array.php
        $post_filter = array(
            'action'     => FILTER_SANITIZE_STRING,
            'product_id' => FILTER_VALIDATE_INT,
            'nonce'      => FILTER_SANITIZE_STRING,
            'quantity'   => FILTER_VALIDATE_INT,
        );
        
        $this->post_data_sanitized = filter_var_array( $post_data_trimmed, $post_filter );
    }

    /**
     * Internal utility function
     * 
     * @param string $value
     * @return string
     */
    private function trimmer( $value ) : string
    {
        return trim( $value );
    }

    /**
     * Returns the data from the request sanitized
     * 
     * @return string|false if the value is not found
     */
    public function post( string $value ) 
    {
        return isset( $this->post_data_sanitized[$value] ) ? $this->post_data_sanitized[$value] : false;

    }



}
