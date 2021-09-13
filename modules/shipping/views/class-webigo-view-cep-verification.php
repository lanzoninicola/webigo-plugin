<?php


class Webigo_View_Cep_Verification {


    public function __construct(  ) {

        $this->load_dependencies();
        
    }


    private function load_dependencies() {

        require_once WEBIGO_PLUGIN_PATH . '/modules/shipping/views/class-webigo-view-cep-verification-form.php';

        require_once WEBIGO_PLUGIN_PATH . '/modules/shipping/views/class-webigo-view-cep-verification-success.php';

        require_once WEBIGO_PLUGIN_PATH . '/modules/shipping/views/class-webigo-view-cep-verification-failed.php';

        require_once WEBIGO_PLUGIN_PATH . '/modules/core/views/class-webigo-view-buttons.php';
        
    }
    
    public function render( ) 
    {
        ?>

        <div class="wbg-cep-verification-container" data-visibility="visible">

            <div class="wbg-cep-verification-wrapper">

                <?php Webigo_View_Cep_Verification_Form::render() ?>

                <?php Webigo_View_Cep_Verification_Success::render(); ?>

                <?php Webigo_View_Cep_Verification_Failed::render(); ?>

            </div>

        </div>

<?php      
    }

  
}