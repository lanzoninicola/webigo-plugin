<?php


class Webigo_View_Myaccount_Page_Header {

    public function render() {
        ?>

            <div class="wbg-myaccount-header">
                <?php $this->goto_store() ?>
                <?php $this->logout_button() ?>
            </div> 
        <?php
    }


    private function goto_store() 
    {
        ?>

        <a href="<?php echo esc_url( home_url() ); ?>" class="wbg-gotostore">
            <!-- <img src="<?php echo esc_url( Webigo_MyAccount_Settings::images('gotostore')['src']) ?>" />
            <span><?php echo esc_html( Webigo_MyAccount_Settings::images('gotostore')['label'] ) ?></span> -->
            <?php Webigo_View_Buttons::render('Ir para loja', 'primary') ?>
        </a>

<?php
    }
    

    private function logout_button()
    {
        ?>

        <a href="<?php echo esc_url( wc_logout_url() ); ?>" class="wbg-logout-button">
            <img src="<?php echo esc_url( Webigo_MyAccount_Settings::images('logout')['src']) ?>" />
            <!-- <span><?php echo esc_html( Webigo_MyAccount_Settings::images('logout')['label'] ) ?></span> -->
        </a>

<?php

    }

}