<?php



class Webigo_View_Cep_Verification_Failed {


    public static function render() {
        ?>

        <div class="wbg-cep-verification-success" data-visibility="visible">
            
            <?php self::shipping_success_image(); ?>

            <?php self::content(); ?>
            
            <?php self::button(); ?>
        </div>

<?php
    }

    private static function shipping_success_image() {

        $ship_area_failed_img = Webigo_Shipping_Settings::images( 'ship_area_failed' );

    ?>
            <div class='wbg-cep-verification-head-image'>
                <div class="wbg-shipping-option-wrapper">
                    <img src='<?php echo esc_url( $ship_area_failed_img['src'] ) ?>' alt="<?php echo esc_attr( $ship_area_failed_img['label'] ) ?>" title="<?php echo esc_attr( $ship_area_failed_img['label'] ) ?>">
                </div>
            </div>

    <?php
    }

    private static function content() {
        ?>

            <h2>Nós sentimos muito. Infelizmente o CEP informado não está na área de cobertura</h2>
            <p>Fique à vontade para explorar nosso site, lembrando que você pode retirar seu pedido na nossa loja também.</p>

<?php
    }


    private static function button () {

        $button_label = 'Seguir para á loja';

        $button_options = array(
            'button' => array(
                'class'  => ['wbg-button-goto-store']
            )
        );

        Webigo_View_Buttons::render( $button_label, 'primary', $button_options ); 

    }

}