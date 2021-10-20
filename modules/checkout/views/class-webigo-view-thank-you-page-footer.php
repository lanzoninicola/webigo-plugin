<?php



class Webigo_View_Thank_You_Page_Footer {



    public function render() {

        ?>

        <div class="wbg-thank-you-footer">
            <?php $this->redirect_options() ?>
        </div>

        <?php
    }

    private function redirect_options() 
    {

        ?>

            <div class="wbg-checkout-user-page">
                <h2>Outros colegamentos</h2>
                <div class="wbg-checkout-redirect">
                    <a href="<?php echo esc_url( Webigo_Woo_Urls::shop() )?>" class="wbg-checkout-redirect-option">
                        <i class="fas fa-arrow-left"></i>
                        <span>Loja</span>
                    </a>
                    <a href="<?php echo esc_url( Webigo_Woo_Urls::myaccount() )?>" class="wbg-checkout-redirect-option">
                        <span>Meus pedidos</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        
        <?php
    }

}