<?php



class Webigo_View_Cep_Verification_Success {


    public static function render() {
        ?>

        <div class="wbg-cep-verification-success" data-visibility="hidden">
            
            <?php self::shipping_success_image(); ?>

            <?php self::content(); ?>
            
            <?php self::button(); ?>
        </div>

<?php
    }

    private static function shipping_success_image() {

        $ship_area_success_img = Webigo_Shipping_Settings::images( 'ship_area_success' );

    ?>
            <div class='wbg-cep-verification-head-image'>
                <div class="wbg-shipping-image-wrapper">
                    <img src='<?php echo esc_url( $ship_area_success_img['src'] ) ?>' alt="<?php echo esc_attr( $ship_area_success_img['label'] ) ?>" title="<?php echo esc_attr( $ship_area_success_img['label'] ) ?>">
                </div>
            </div>

    <?php
    }

    private static function content() {
        ?>

            <div class="wbg-cep-verification-content">
                <h2>Oba! Você está dentro da área de cobertura.</h2>
                <p>Conseguimos te entregar!</p>
            </div>

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