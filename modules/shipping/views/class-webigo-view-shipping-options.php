<?php


class Webigo_View_Shipping_Options {


    public function render() 
    {
        ?>
        <div class="wbg-shipping-options-container" data-visibility="visible">
            <div class="wbg-shipping-options-intro">
                <h4>Bem vindo na Hazbier.</h4>
                <p>Para começar, escolha um método de entrega:</p>
        </div> 
            <div class="wbg-shipping-inline-notifications" data-visibility="hidden">
                <span>Aguarde, estou elaborando...</span>
            </div>
            <div class="wbg-shipping-options">
                <?php $this->render_shipping_option( 'delivery' ) ?>
                <?php $this->render_shipping_option( 'pickupstore' ) ?>
            </div>
            <?php do_action('webigo_home_after_shipping_options'); ?>
        </div>

<?php
    }

    private function render_shipping_option( string $shipping_option )
    {
        $name  = Webigo_Shipping_Settings::images( $shipping_option )['name'];
        $label = Webigo_Shipping_Settings::images( $shipping_option )['label'];
        $src   = Webigo_Shipping_Settings::images( $shipping_option )['src'];
        $href  = '';

        if ( $shipping_option === 'delivery' ) {
            $href = Webigo_Woo_Urls::home() . "?shipping_method=" . $name;
        }

        if ( $shipping_option === 'pickupstore' ) {
            $href = Webigo_Woo_Urls::shop();
        }

        $class = "wbg-shipping-option $name"

        ?>
            <a href="<?php echo esc_url( $href ) ?>" class="<?php echo esc_attr( $class ) ?>" data-action-state="idle">
                <div class="wbg-shipping-image-wrapper">
                    <img src='<?php echo esc_url( $src ) ?>' alt="<?php echo esc_attr( $label ) ?>" title="<?php echo esc_attr( $label ) ?>">
                </div>
                <span class="text-small"><?php echo esc_html( $label ) ?></span>
            </a>

<?php
    }

   

    private function shipping_info()
    {
        ?>

        <div class="wbg-shipping-options-info">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M1 12C1 18.0751 5.92487 23 12 23C18.0751 23 23 18.0751 23 12C23 5.92487 18.0751 1 12 1C5.92487 1 1 5.92487 1 12ZM21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12ZM13.0036 13.9983H14.003V15.9983H10.003V13.9983H11.003V11.9983H10.003V9.99835H13.0036V13.9983ZM13.0007 7.99835C13.0007 8.55063 12.5528 8.99835 12.0003 8.99835C11.4479 8.99835 11 8.55063 11 7.99835C11 7.44606 11.4479 6.99835 12.0003 6.99835C12.5528 6.99835 13.0007 7.44606 13.0007 7.99835Z" fill="#005930"/>
                    </svg>    
                    <span class=".text-small">Poderá mudar sua escolha na fase de finalização da compra</span>
        </div>

        <?php
    }


}