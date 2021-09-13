<?php



class Webigo_View_Cep_Verification_Form {

    
    public static function render() {
    ?>

        <div class="wbg-cep-form-container" data-visibility="visible">

            <?php self::delivery_image() ?>

            <?php self::intro() ?>

            <div class="wbg-cep-form-wrapper">

                <?php self::state_selection(); ?>    
                
                <?php self::input_cep(); ?>

                <?php self::render_notification_failed(); ?>

                <?php self::busca_cep(); ?>

                <?php self::nonce_field(); ?>
                    
                <?php self::verify_cep_button(); ?>

                <?php self::voltar_button(); ?>

            </div>

        </div>

<?php
    }

    private static function delivery_image() {

        $delivery_img_data = Webigo_Shipping_Settings::shipping_view_options( 'delivery' );

        $classes = 'wbg-cep-form-' . $delivery_img_data['name'];
    ?>
            <div class='wbg-cep-verification-head-image'>
                    <div class="wbg-shipping-option-wrapper">
                    <img src='<?php echo esc_url( $delivery_img_data['src'] ) ?>' alt="<?php echo esc_attr( $delivery_img_data['label'] ) ?>" title="<?php echo esc_attr( $delivery_img_data['label'] ) ?>">
                </div>
            </div>

    <?php
    }

    private static function intro() 
    {
        ?>
            <div class="wbg-cep-form-intro">
                
                <h2>Onde você quer receber seu pedido?</h2>
                <p>Entregamos no mesmo dia para Pato Branco. Você também pode retirar seu pedido na loja.</p>
                <p>Verifique se o CEP de entrega está na nossa área de cobertura preenchendo o campo que segue. Depois aperte o botão "Verifica CEP".</p>
            </div>

<?php
    }

    private static function state_selection() {
        ?>

            <div class="wbg-cep-form-wrapper-states" data-visibility="visible">
                <label for="wbg-cep-form-states" class="small-text">ESTADO</label>
                <select name="wbg-cep-form-states" id="wbg-cep-form-select-states" class="wbg-cep-form-states">
                    <?php

                    $default_country_code = Webigo_Shipping_Settings::DEFAULT_COUNTRY_CODE;
                    $default_state_code = Webigo_Shipping_Settings::DEFAULT_STATE_CODE;

                    $states_code = WC()->countries->states[$default_country_code];

                    foreach ($states_code as $key => $value) {
                        $selected = $key === $default_state_code ? 'selected' : '';
                        $optionOutput = "<option value=$key $selected>$value</option>";
                        echo $optionOutput;
                    }

                    ?>
                    
                </select>
            </div>

        <?php
    }

    private static function input_cep() 
    {

        $label = "CEP (ex: 85503328)";
        $placeholder = "Preencha o campo com seu CEP";

        // TODO: input inputmode="numeric" not supported by Firefox23+ Safari
        ?>
            <div class="wbg-cep-form-input-cep-wrapper">
                <label for="wbg-input-cep" class="small-text"><?php echo esc_attr( $label ) ?></label>
                <input type="number" name="wbg-input-cep" id="wbg-cep-form-input-cep" placeholder="<?php echo esc_attr( $placeholder ) ?>" maxlength="8">
            </div>

<?php

    }


    private static function busca_cep()
    {
        $correios_url = Webigo_Shipping_Settings::CORREIOS_URL_BUSCA_CEP;
        ?>
            <a href="<?php echo esc_url( $correios_url ) ?>" rel="nofollow" class="busca-cep-link small-text">Não sei meu CEP</a>

<?php
    }

    private static function nonce_field() {

        $action_name = Webigo_Shipping_Settings::AJAX_CEP_VERIFICATION_ACTION_NAME;

        return wp_nonce_field( $action_name , 'webigo_cep_verification_nonce' );
    }

    private static function verify_cep_button() {

        $button_options = array(
            'button' => array(
                'class' => ['wbg-button-cep-form-verifycep'],
            )
        );

        Webigo_View_Buttons::render( '', 'primary', $button_options );

    }

    private static function voltar_button() {

        $button_options = array(
            'button' => array(
                'class'      => ['wbg-button-cep-form-voltar'],
            )
        );

        Webigo_View_Buttons::render( 'Voltar', 'ternary', $button_options );

    }

    private static function render_notification_failed()
    {
        $message = 'Occoreu um erro! Ritente por favor';
        ?>

        <div class="wbg-cep-form-notification-failed" data-visibility="hidden">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M12 23C5.92487 23 1 18.0751 1 12C1 5.92487 5.92487 1 12 1C18.0751 1 23 5.92487 23 12C23 18.0751 18.0751 23 12 23ZM12 21C16.9706 21 21 16.9706 21 12C21 7.02944 16.9706 3 12 3C7.02944 3 3 7.02944 3 12C3 16.9706 7.02944 21 12 21ZM8.70711 16.7071L12 13.4142L15.2929 16.7071L16.7071 15.2929L13.4142 12L16.7071 8.70711L15.2929 7.29289L12 10.5858L8.70711 7.29289L7.29289 8.70711L10.5858 12L7.29289 15.2929L8.70711 16.7071Z" fill="#BB0623"/>
            </svg>
            <span class="text-small"><?php echo esc_html( $message ) ?></span>
        </div>
<?php
        
    }
}

