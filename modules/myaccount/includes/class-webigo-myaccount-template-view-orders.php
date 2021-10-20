<?php




class Webigo_Myaccount_Template_View_Orders {

    /**
     * @param Webigo_Woo_Custom_Template_handler    $woo_custom_template_handler
     */
    public function __construct( object $woo_custom_template_handler = null ) {

        $this->woo_custom_template_handler = $woo_custom_template_handler;
    }

    /**
     * Method passed to Woocommerce filter: 'wc_get_template'
     * in the module bootstrap class
     */
    public function template( $template, $slug, $name ) 
    {
        if ( $this->woo_custom_template_handler !== null) {
           
            return $this->woo_custom_template_handler->override_woocommerce_template( $template, $slug, $name);
        }
        
    }
}