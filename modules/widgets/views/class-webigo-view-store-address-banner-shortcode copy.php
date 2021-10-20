<?php


class Webigo_View_Store_Address_Banner_Shortcode {

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
     * @var Webigo_View_Svg_Icons
     */
    private $svg_icons;


    public function __construct()
    {
        $this->settings = Webigo_Widgets_Settings::store_address_banner_shortcode_settings();
        $this->load_dependencies();
    }

    private function load_dependencies() : void
    {
        require_once WEBIGO_PLUGIN_PATH . '/modules/core/includes/class-webigo-pod-custom-settings-page.php';
        $this->pod_custom_settings_page = new Webigo_Pod_Custom_Settings_Page( $this->settings['PODS_CUSTOM_SETTINGS_PAGE'] );

        require_once WEBIGO_PLUGIN_PATH . '/modules/core/views/class-webigo-view-svg-icon.php';
        $this->svg_icons = new Webigo_View_Svg_Icons();
  
    }

    public function render() : void
    {
        
        $address = $this->pod_custom_settings_page->value_of( $this->settings['PODS_SHIPPING_STORE_ADDRESS_FIELD'] );
        $cep     = $this->pod_custom_settings_page->value_of( $this->settings['PODS_SHIPPING_STORE_CEP_FIELD'] );
        $city    = $this->pod_custom_settings_page->value_of( $this->settings['PODS_SHIPPING_STORE_CITY_FIELD'] );
        $phone   = $this->pod_custom_settings_page->value_of( $this->settings['PODS_SHIPPING_STORE_PHONE_FIELD'] );

        $full_rendered_address = "$address, $cep - $city";
        $full_raw_address = "$address $cep $city";

        ?>

        <div class="wbg-store-address-banner-container">

            <div class="wbg-store-address">
                <span><?php echo esc_html( $full_rendered_address ) ?></span>
            </div>

            <div class="wbg-call-actions">
                <?php $this->render_phone( $phone ) ?>    
                <?php $this->render_maps( $full_raw_address ) ?>
                <?php $this->render_waze( $full_raw_address ) ?>
            </div>


        </div>
<?php
        
    }


    private function render_waze( string $raw_address ) : void
    {
        $base_url = 'https://waze.com/ul?q=';
        $store_address = str_replace(' ', '%20', $raw_address);

        $url = $base_url . $store_address;
        ?>

        <a class="wbg-call-action" href='<?php echo esc_url( $url ) ?>' target='_blank'>
                <?php echo esc_html( $this->svg_icons->render( 'waze' , 'white') )?>
                <span>Waze</span>
        </a>

<?php
    }

    private function render_maps( string $raw_address ) : void
    {
        $base_url = 'https://www.google.com/maps/dir/?api=1&destination=';
        $store_address = str_replace(' ', '%20', $raw_address);

        $url = $base_url . $store_address;
        ?>

        <a class="wbg-call-action" href='<?php echo esc_url( $url ) ?>' target='_blank'>
                <?php echo esc_html( $this->svg_icons->render( 'gmap' , 'white') )?>
                <span>Maps</span>
        </a>

<?php
    }

    private function render_phone( string $phone_number ) : void
    {
        ?>

        <a class="wbg-call-action" href='tel:<?php echo esc_html( $phone_number ) ?>' target='_blank'>
                <?php echo esc_html( $this->svg_icons->render( 'phone' , 'white') )?>
                <span>Liga</span>
        </a>

<?php
    }


    
}