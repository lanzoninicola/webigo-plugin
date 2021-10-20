<?php



class Webigo_View_Lost_Password_Header {



    public function render() {
        ?>

        <div class="wbg-lost-password-intro">
            
            <img src="<?php echo esc_url( Webigo_Myaccount_Settings::images("lost_password")["src"] ) ?>" alt="<?php echo esc_attr( Webigo_Myaccount_Settings::images("lost_password")["label"] ) ?>" >
            <h2>Redefinir senha</h2>
        </div>

        <?php
    }

}