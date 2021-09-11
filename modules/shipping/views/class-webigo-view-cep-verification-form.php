<?php



class Webigo_View_Cep_Verification_Form {

    
    public static function render() {
    ?>

        <div class="wbg-cep-form">

            <div class="wbg-cep-form-container">

                <!-- IMPORTANT: With the current use cases is not necessary keep visibile this field -->
                <?php self::state_selection(); ?>

                <?php self::input_cep(); ?>

                <?php self::busca_cep(); ?>

                <div class="wbg-cep-form-footer">
                    <?php Webigo_View_Buttons::render( 'verifica CEP') ?>
                    <?php Webigo_View_Buttons::render( 'verifica CEP', 'secondary') ?>
                    <?php Webigo_View_Buttons::render( 'verifica CEP', 'ternary') ?>
                </div>
            </div>
        </div>

<?php
    }

    
    private static function state_selection() {
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
    

    private static function input_cep() 
    {
        ?>
            <div class="wbg-cep-form-wrapper-user-cep">
                <label for="wbg-cep-user" class="small-text">CEP</label>
                <?  // TODO: input inputmode="numeric" not supported by Firefox23+ Safari
                    // CEP
                ?>
                <input type="number" name="wbg-cep-user" id="wbg-cep-form-usercep-input" placeholder="Preencha o campo com seu CEP" maxlength="8"></input>
            </div>

<?php

    }


    private static function busca_cep()
    {
        $correios_url = Webigo_Shipping_Settings::CORREIOS_URL_BUSCA_CEP;
        
        ?>
            <a href="<?php esc_url( $correios_url ) ?>" rel="nofollow" class="busca-cep-link small-text">
                    Não sei meu CEP
            </a>

<?php
    }
}

