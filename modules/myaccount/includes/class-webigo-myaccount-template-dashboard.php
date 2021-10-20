<?php




class Webigo_Myaccount_Template_Dashboard {

    /**
     * @param Webigo_Woo_Custom_Template_Handler    $woo_custom_template_handler
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

    /**
     *  Let the view class available to the template file in \woocommerce\templates\...
     */
    public function load_view_template_class()
	{
		require_once WEBIGO_PLUGIN_PATH . '/modules/myaccount/views/class-webigo-view-myaccount-onboarding.php';
	}
    
}