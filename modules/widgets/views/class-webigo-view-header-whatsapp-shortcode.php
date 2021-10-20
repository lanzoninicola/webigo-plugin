<?php


class Webigo_View_Header_Whatsapp_Shortcode {

    /**
     * Array of settings
     * 
     * @var array
     */
    private $settings;
    
    /**
     * Dependency
     * 
     * @var Webigo_Pod_Custom_Settings_Page
     */
    private $pod_custom_settings_page;


    public function __construct()
    {
        $this->settings = Webigo_Widgets_Settings::header_whatsapp_shortcode_settings();
        $this->load_dependencies();
    }

    private function load_dependencies() : void
    {
        require_once WEBIGO_PLUGIN_PATH . '/modules/core/includes/class-webigo-pod-custom-settings-page.php';
        $this->pod_custom_settings_page = new Webigo_Pod_Custom_Settings_Page( $this->settings['PODS_CUSTOM_SETTINGS_PAGE'] );
    }

    public function render( ) : void
    {
        ?>

        <a class="wbg-header-whatsapp" href='<?php echo esc_url( $this->build_wa_url() ) ?>' target='_blank'>
            <i class="fab fa-whatsapp"></i>
        </a>

<?php
    }

    private function build_wa_url() : string
    {
        $whatsapp_number = $this->pod_custom_settings_page->value_of( $this->settings['PODS_STORE_WA_NUMBER'] );
        $message = $this->settings['WA_PREFIX_CUSTOMER_MSG'];
        
        return "https://api.whatsapp.com/send?phone=$whatsapp_number&text=$message";
    }


    
}