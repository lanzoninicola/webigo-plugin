<?php


class Webigo_View_Shipping_Options {


    public function render() 
    {
        $shipping_view_options = Webigo_Shipping_Settings::shipping_view_options();
        ?>

        <div class="wbg-shipping-options-container" data-visibility="visible">
            <div class="wbg-shipping-options-intro">
                <h4>Bem vindo na Hazbier</h4>
                <p>Para começar, escolha um método de entrega:</p>
            </div>
            <div class="wbg-shipping-options">
                <?php 
                    foreach ($shipping_view_options as $shipping_view_data) {
                        $this->render_shipping_option( $shipping_view_data );
                    }
                ?>
            </div>
            <div class="wbg-shipping-options-footer">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M1 12C1 18.0751 5.92487 23 12 23C18.0751 23 23 18.0751 23 12C23 5.92487 18.0751 1 12 1C5.92487 1 1 5.92487 1 12ZM21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12ZM13.0036 13.9983H14.003V15.9983H10.003V13.9983H11.003V11.9983H10.003V9.99835H13.0036V13.9983ZM13.0007 7.99835C13.0007 8.55063 12.5528 8.99835 12.0003 8.99835C11.4479 8.99835 11 8.55063 11 7.99835C11 7.44606 11.4479 6.99835 12.0003 6.99835C12.5528 6.99835 13.0007 7.44606 13.0007 7.99835Z" fill="#005930"/>
                </svg>    
                <p class=".text-small">Poderá mudar sua escolha na fase de finalização da compra</p>
            </div>
        </div>

<?php
    }

    private function render_shipping_option( array $shipping_view_data )
    {
        $classes = 'wbg-shipping-option ' . $shipping_view_data['name'];
        ?>
            <div class='<?php echo esc_attr( $classes ) ?>'>
                <div class="wbg-shipping-image-wrapper">
                    <img src='<?php echo esc_url( $shipping_view_data['src'] ) ?>' alt="<?php echo esc_attr( $shipping_view_data['label'] ) ?>" title="<?php echo esc_attr( $shipping_view_data['label'] ) ?>">
                </div>
                <span class="text-small"><?php echo esc_html( $shipping_view_data['label'] ) ?></span>
            </div>

<?php
    }

}