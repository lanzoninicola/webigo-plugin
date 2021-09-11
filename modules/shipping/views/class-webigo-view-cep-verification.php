<?php


class Webigo_View_Cep_Verification {


    public function __construct(  ) {

        $this->load_dependencies();
        
    }


    private function load_dependencies() {

        require_once WEBIGO_PLUGIN_PATH . '/modules/shipping/views/class-webigo-view-cep-verification-intro.php';

        require_once WEBIGO_PLUGIN_PATH . '/modules/shipping/views/class-webigo-view-cep-verification-form.php';

        require_once WEBIGO_PLUGIN_PATH . '/modules/shipping/views/class-webigo-view-cep-verification-success.php';

        require_once WEBIGO_PLUGIN_PATH . '/modules/shipping/views/class-webigo-view-cep-verification-failed.php';

        require_once WEBIGO_PLUGIN_PATH . '/modules/core/views/class-webigo-view-buttons.php';
        
    }
    
    public function render( ) 
    {
        ?>

        <div class="wbg-cep-verification-container" data-visibility="hidden">

            <?php Webigo_View_Cep_Verification_Intro::render() ?>

            <div class="wbg-cep-verification-wrapper">

                <?php Webigo_View_Cep_Verification_Form::render() ?>

            </div>

            <button id="wbg-cep-form-submit-cep-voltar" class="wbg-cep-button cep-ternary-button">Voltar</button>

        </div>


<?php      
    }

  
}