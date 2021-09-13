<?php



class Webigo_View_Cep_Verification_Failed {


    public static function render( ) {
        
        $button_label = 'Seguir para á loja';
        ?>

        <div class="wbg-cep-verification-failed" data-visibility="visible">
            <div>
                <h2>Nós sentimos muito. Infelizmente o CEP informado não está na área de cobertura</h2>
                <p>Fique à vontade para explorar nosso site, lembrando que você pode retirar seu pedido na nossa loja também.</p>
            </div>
            <?php Webigo_View_Buttons::render( $button_label ) ?>
        </div>

<?php
    }

}