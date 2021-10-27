<?php


class Webigo_View_Store_Contacts_Banner {

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

    /**
     * Dependency
     * 
     * @var array
     */
    private $store_contacts;


    public function __construct()
    {
        $this->settings = Webigo_Widgets_Settings::store_address_banner_shortcode_settings();
        $this->load_dependencies();
        $this->load_store_contacts();
    }

    private function load_dependencies() : void
    {
        require_once WEBIGO_PLUGIN_PATH . '/modules/core/includes/class-webigo-pod-custom-settings-page.php';
        $this->pod_custom_settings_page = new Webigo_Pod_Custom_Settings_Page( $this->settings['PODS_CUSTOM_SETTINGS_PAGE'] );

        require_once WEBIGO_PLUGIN_PATH . '/modules/core/views/class-webigo-view-icon-mini-card.php';

        require_once WEBIGO_PLUGIN_PATH . '/modules/core/views/class-webigo-view-icon-card.php';
    }

    private function load_store_contacts()
    {
        $address   = $this->pod_custom_settings_page->value_of( $this->settings['PODS_SHIPPING_STORE_ADDRESS_FIELD'] );
        $cep       = $this->pod_custom_settings_page->value_of( $this->settings['PODS_SHIPPING_STORE_CEP_FIELD'] );
        $city      = $this->pod_custom_settings_page->value_of( $this->settings['PODS_SHIPPING_STORE_CITY_FIELD'] );
        $phone     = $this->pod_custom_settings_page->value_of( $this->settings['PODS_SHIPPING_STORE_PHONE_FIELD'] );
        $whatsapp  = $this->pod_custom_settings_page->value_of( $this->settings['PODS_SHIPPING_STORE_WHATSAPP_FIELD'] );

        $this->store_contacts = array(
            'address'             => $address,
            'cep'                 => $cep,
            'city'                => $city,
            'full_render_address' => "$address, $cep - $city",
            'full_raw_address'    => "$address $cep $city",
            'phone'               => $phone,  
            'whatsapp'            => $whatsapp,  
        );
    }

    public function render() : void
    {
        ?>
        <div class="wbg-store-address-banner-container">
            <?php $this->render_address() ?>
            <div class="wbg-store-address-banner-content">
                <?php $this->render_waze() ?>
                <?php $this->render_maps() ?>
                <?php $this->render_whatsapp() ?>
            </div>
        </div>
<?php
        
    }

    private function render_waze( ) : void
    {
        
        $base_url = 'https://waze.com/ul?q=';

        $endpoint = array(
            'url' => $base_url . str_replace(' ', '%20', $this->store_contacts['full_raw_address']),
            'name' => 'waze',
        );
        
        
        $icon_card = new Webigo_View_Icon_Mini_Card( $endpoint );

        echo esc_html( $icon_card->render() );
    }

    private function render_maps() : void
    {
        $base_url = 'https://www.google.com/maps/dir/?api=1&destination=';

        $endpoint = array(
            'url' => $base_url . str_replace(' ', '%20', $this->store_contacts['full_raw_address']),
            'name' => 'gmap',
        );

        $icon_card = new Webigo_View_Icon_Mini_Card( $endpoint );

        echo esc_html( $icon_card->render() );
    }

    /*
    private function render_phone() : void
    {

        $base_url = 'tel:';

        $endpoint = array(
            'url' => $base_url . $this->store_contacts['phone'],
            'name' => 'phone',
        );

        $icon_card = new Webigo_View_Icon_Mini_Card( $endpoint );

        echo esc_html( $icon_card->render() );
       
    }
    */

    private function render_address() : void
    {

        $endpoint = array(
            'url'         => '#',
            'name'        => 'address',
            'description' => $this->store_contacts['full_render_address']
        );

        $icon_card = new Webigo_View_Icon_Card( $endpoint );

        echo esc_html( $icon_card->render() );
       
    }

    private function render_whatsapp() : void
    {

        $endpoint = array(
            'url'         => 'https://api.whatsapp.com/send?phone=' . $this->store_contacts['whatsapp'],
            'name'        => 'whatsapp',
        );

        $icon_card = new Webigo_View_Icon_Mini_Card( $endpoint );

        echo esc_html( $icon_card->render() );
       
    }


    
}