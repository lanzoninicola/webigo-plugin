<?php



class Webigo_View_Form_Login_Home {



    public function render() {
        ?>

        <div class="wbg-login-intro" style="background-image: url(<?php echo esc_url( Webigo_Myaccount_Settings::images('login')['src']) ?>)">
            <img class="wbg-login-intro-logo" src="<?php echo esc_url(Webigo_Myaccount_Settings::images('logo_login')['src']) ?>">
        </div>
        <div class="wbg-form-login-options" >
            <div class="wbg-form-login-options-wrapper" data-visibility="visible">
                <div class="wbg-form-login-options-buttons">
                    <?php $this->register_button() ?>
                    <?php $this->login_button() ?>
                </div>
                <?php $this->gotostore() ?>
            </div>
            
        </div>
        <?php
    }
    
    private function gotostore()
    {
        ?>
        <a href="<?php echo esc_url( Webigo_Woo_Urls::home() ) ?>">IR PARA LOJA</a>

<?php
    }

    private function register_button()
    {
        ?>
        <!-- I used the a tag, so if the client press the back button he returns to the myaccount page and not in elsewhere site -->
        <a href="<?php echo esc_url( Webigo_Woo_Urls::myaccount() . "?action=register" ) ?>">
            <div class="wbg-button wbg-primary-button wbg-show-register-form" data-action-state="idle">Cadastra-se</div>
        </a>

        <?php
    }

    private function login_button()
    {
        ?>
        <!-- I used the a tag, so if the client press the back button he returns to the myaccount page and not in elsewhere site -->
        <a href="<?php echo esc_url( Webigo_Woo_Urls::myaccount() . "?action=login" ) ?>">
            <div class="wbg-button wbg-secondary-button wbg-show-login-form" data-action-state="active">Entrar</div>
        </a>

        <?php
    }

}