<?php



class Webigo_View_Cep_Verification_Form {

    
    public static function render() {
    ?>

        <div class="wbg-cep-form-container" data-visibility="visible">

            <?php self::intro() ?>

            <div class="wbg-cep-form-wrapper">

                <?php self::input_cep(); ?>

                <?php self::busca_cep(); ?>

                <?php self::nonce_field(); ?>
                    
                <?php Webigo_View_Buttons::render( 'verifica CEP') ?>

                <?php Webigo_View_Buttons::render( 'Voltar', 'ternary') ?>

            </div>

        </div>

<?php
    }

    /*
    private static function state_selection() {

        IMPORTANT: With the current use cases is not necessary keep visibile this field

        ?>

            <div class="wbg-cep-form-wrapper-states">
                <label for="wbg-cep-form-states" class="small-text">Seu estado:</label>
                <select name="wbg-cep-form-states" id="wbg-cep-form-states">
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
    */

    private static function intro() 
    {
        ?>
            <div class="wbg-cep-form-intro">
                <h2>Onde você quer receber seu pedido?</h2>
                <p>Entregamos no mesmo dia para Pato Branco. Você também pode retirar seu pedido na loja.</p>
                <p>Verifique se o seu CEP está na nossa área de cobertura.</p>
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
                <input type="number" name="wbg-input-cep" placeholder="<?php echo esc_attr( $placeholder ) ?>" maxlength="8">
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
}

