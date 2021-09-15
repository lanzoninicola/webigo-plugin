<?php


class Webigo_View_Shipping_Banner {



    public function __construct(  )
    {
        $this->load_dependencies();
    }

    private function load_dependencies() : void
    {

        // require_once WEBIGO_PLUGIN_PATH . '/modules/shipping-banner/includes/class-webigo-shipping-banner-content.php';
        // $this->shipping_banner = new Webigo_Shipping_Banner_Content();

    }

    public function render( ) 
    {
        ?>

        <div class="wbg-shipping-banner">

            <?php self::pickup_store_message() ?>

            <?php self::delivery_message() ?>
       
        </div>

<?php
    }

    private function pickup_store_message()
    {
        ?>

        <div class="wbg-shipping-banner-message wbg-shipping-banner-pickup-store" data-visibility="hidden">
            <p>Este pedido será retirado na loja</p>
        </div>

<?php

    }

    private function delivery_message()
    {
        ?>

        <div class="wbg-shipping-banner-message wbg-shipping-banner-delivery" data-visibility="hidden">
            <p>Este pedido será entregue pela equipe de Hazbier</p>
        </div>

<?php

    }
}