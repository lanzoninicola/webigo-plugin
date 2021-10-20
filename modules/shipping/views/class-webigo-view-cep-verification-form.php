<?php



class Webigo_View_Cep_Verification_Form {


    /**
     * The Customer Shipping Data object
     * 
     * @var Webigo_Customer_Shipping_Data
     */
    private $customer_shipping_data;

    /**
     * Default data for the module
     * 
     */
    private $default = array(
            'country_code'  => Webigo_Shipping_Settings::DEFAULT_COUNTRY_CODE,
            'state_code'    => Webigo_Shipping_Settings::DEFAULT_STATE_CODE,
    );

    /**
     * The customer postcode
     * 
     * @var string
     */
    private $customer_postcode;

    public function __construct()
    {

        $this->customer_shipping_data = new Webigo_Customer_Shipping_Data();
        $this->customer_postcode = $this->customer_shipping_data->postcode();

        // var_dump($this->customer_postcode);
    }


    public function render() {
    ?>

        <div class="wbg-cep-form-container" data-visibility="hidden">

            <?php $this->delivery_image() ?>

            <?php $this->intro() ?>

            <div class="wbg-cep-form-wrapper">

                <?php $this->state_selection(); ?>    
                
                <?php $this->input_cep(); ?>

                <?php $this->render_notification(); ?>

                <!-- TODO: temporary disable. It should encapsulate in a windows to avoid the user exit from the app   -->
                <!-- <?php $this->busca_cep(); ?> -->

                <?php $this->nonce_field(); ?>
                    
                <?php $this->verify_cep_button(); ?>

                <?php $this->voltar_button(); ?>

            </div>

        </div>

<?php
    }

    private function delivery_image() {

        $delivery_img_data = Webigo_Shipping_Settings::images( 'delivery' );

        $classes = 'wbg-cep-form-' . $delivery_img_data['name'];
    ?>
            <div class='wbg-cep-verification-head-image'>
                    <div class="wbg-shipping-image-wrapper">
                    <img src='<?php echo esc_url( $delivery_img_data['src'] ) ?>' alt="<?php echo esc_attr( $delivery_img_data['label'] ) ?>" title="<?php echo esc_attr( $delivery_img_data['label'] ) ?>">
                </div>
            </div>

    <?php
    }

    private function intro() 
    {
        ?>
            <div class="wbg-cep-form-intro">
                
                <h2>Onde você quer receber seu pedido?</h2>
                <p>Entregamos no mesmo dia para Pato Branco. Você também pode retirar seu pedido na loja.</p>
                <p>Verifique se o CEP de entrega está na nossa área de cobertura preenchendo o campo que segue. Depois aperte o botão "Verifica CEP".</p>
            </div>

<?php
    }

    private function state_selection() {
        ?>

            <div class="wbg-cep-form-wrapper-states" data-visibility="visible">
                <label for="wbg-cep-form-states" class="small-text">ESTADO</label>
                <select name="wbg-cep-form-states" id="wbg-cep-form-select-states" class="wbg-cep-form-states">
                    <?php

                    $states_code = WC()->countries->states[$this->default['country_code']];

                    $selected_state = $this->default['state_code'];

                    if ( $this->customer_shipping_data->state_code() !== '' ) {
                        $selected_state = $this->customer_shipping_data->state_code();
                    }

                    foreach ($states_code as $key => $value) {
                        $selected = $key === $selected_state ? 'selected' : '';
                        $optionOutput = "<option value=$key $selected>$value</option>";
                        echo $optionOutput;
                    }

                    ?>
                    
                </select>
            </div>

        <?php
    }

    private function is_customer_cep_loaded() {
        if ( isset( $this->customer_postcode ) && ( $this->customer_postcode ) !== '' ) {
            return true;
        }

        return false;
    }

    private function input_cep() 
    {

        $label = "CEP (ex: 85503328)";
        $placeholder = "Preencha o campo com seu CEP";

        
        $_customer_postcode     = '';
        
        if ( $this->is_customer_cep_loaded() ) {
            $_customer_postcode = str_replace( "-", "", $this->customer_postcode );
        }
        

        // TODO: input inputmode="numeric" not supported by Firefox23+ Safari
        ?>
            <div class="wbg-cep-form-input-cep-wrapper">
                <label for="wbg-input-cep" class="small-text"><?php echo esc_attr( $label ) ?></label>
                <input type="number" name="wbg-input-cep" id="wbg-cep-form-input-cep" placeholder="<?php echo esc_attr( $placeholder ) ?>" value="<?php echo esc_attr( $_customer_postcode ) ?>" maxlength="8">
                <?php $this->is_customer_cep_loaded() ? $this->render_notification('Seu CEP foi carregado automaticamente') : '' ?>
            </div>

<?php

    }


    private function busca_cep()
    {
        $correios_url = Webigo_Shipping_Settings::CORREIOS_URL_BUSCA_CEP;
        ?>
            <a href="<?php echo esc_url( $correios_url ) ?>" rel="nofollow" class="busca-cep-link text-small">Não sei meu CEP</a>

<?php
    }

    private function nonce_field() {

        $action_name = Webigo_Shipping_Settings::AJAX_CEP_VERIFICATION_ACTION_NAME;

        return wp_nonce_field( $action_name , 'webigo_cep_verification_nonce' );
    }

    private function verify_cep_button() {

        // TODO: managing the notification of http response with the notification module inside the AJAX class
        $button_visibility = $this->is_customer_cep_loaded() ? 'visible' : 'hidden';

        $button_options = array(
            'button' => array(
                'class' => ['wbg-button-cep-form-verifycep'],
                'attributes' => array(
                    'data-visibility' => $button_visibility,
                )
            )
        );

        Webigo_View_Buttons::render( '', 'primary', $button_options );
    }

    private function voltar_button() {

        $button_options = array(
            'button' => array(
                'class'      => ['wbg-button-cep-form-voltar'],
            )
        );

        Webigo_View_Buttons::render( 'Voltar', 'ternary', $button_options );

    }

    private function render_notification( $message = null ) 
    {
        // TODO: managing with the notification module
        $_message = $message !== null ? $message : '';
        ?>

        <div class="wbg-cep-form-notification-container">
            <div class="wbg-cep-form-notification-notice">
                <span class="text-small"><?php echo esc_html( $_message ) ?></span>
            </div>
            <?php $this->render_failed_notification(); ?>
        </div>
<?php
    }

    private function render_failed_notification()
    {
        // TODO: managing with the notification module
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

