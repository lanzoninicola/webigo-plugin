<?php


class Webigo_View_Shipping {


    /**
     * @var Webigo_View_Shipping_Options
     */
    private $view_shipping_options;

    /**
     * @var Webigo_View_Check_Cep
     */
    private $view_check_cep;


    public function __construct(  ) {

        $this->load_dependencies();
        
    }


    private function load_dependencies() {

        require_once WEBIGO_PLUGIN_PATH . '/modules/shipping/includes/class-webigo-customer-shipping-data.php';

        require_once WEBIGO_PLUGIN_PATH . '/modules/shipping/views/class-webigo-view-shipping-options.php';
        $this->view_shipping_options = new Webigo_View_Shipping_Options();

        require_once WEBIGO_PLUGIN_PATH . '/modules/shipping/views/class-webigo-view-cep-verification.php';
        $this->view_check_cep = new Webigo_View_Cep_Verification();
    }
    
    public function render( ) 
    {
        ?>

        <div class="wbg-shipping-container" style="background-image: url('<?php echo esc_url( Webigo_Shipping_Settings::images('background_home')['src'] ) ?>' )">
            <div class="wbg-shipping-header">
                <img src="<?php echo esc_url( Webigo_Core_Settings::images('logo')['src'] ) ?>" />
            </div>
            <div class="wbg-shipping-wrapper">
                <?php $this->view_shipping_options->render(); ?>
                <?php $this->view_check_cep->render(); ?>
            </div>
        </div>

        <?php
    }

  

}