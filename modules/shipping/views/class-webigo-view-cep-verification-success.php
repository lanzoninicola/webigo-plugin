<?php



class Webigo_View_Cep_Verification_Sucess {


    public static function render() {

        $button_label = 'Seguir para á loja';
        ?>

        <div class="wbg-cep-verification-success" data-visibility="hidden">
            <h2>Oba! Você está dentro da área de cobertura.</h2>
            <p>Conseguimos te entregar!</p>
            <?php Webigo_View_Primary_Button::render( $button_label ) ?>
        </div>

<?php
    }

}