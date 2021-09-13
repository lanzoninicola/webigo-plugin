<?php



class Webigo_View_Cep_Verification_Success {


    public static function render() {

        $button_label = 'Seguir para á loja';
        ?>

        <div class="wbg-cep-verification-success" data-visibility="visible">
            <h2>Oba! Você está dentro da área de cobertura.</h2>
            <p>Conseguimos te entregar!</p>
            <?php Webigo_View_Buttons::render( $button_label ) ?>
        </div>

<?php
    }

}