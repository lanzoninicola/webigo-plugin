<?php



class Webigo_View_Myaccount_Onboarding {



    public function render()
    {
        ?>

        <div class="wbg-myaccount-onboarding-container">
            <div class="wbg-myaccount-onboarding-intro">
                <h2>Bem vindo/a no seu perfil,</h2>
                <p>esta área está dividida em três seções:</p>
            </div> 
            <ul class="wbg-myaccount-section-wrapper">
                <li class="wbg-myaccount-section">
                    <?php $this->orders_section() ?>
                </li>
                <li class="wbg-myaccount-section">
                    <?php $this->edit_address() ?>
                </li>
                <li class="wbg-myaccount-section">
                    <?php $this->edit_account() ?>
                </li>
            </ul>
            <div class="wbg-myaccount-onboarding-footer">
                <?php Webigo_View_Link_Button::render('ir para loja', 'primary', [], array( 'href' => Webigo_Woo_Urls::shop(), 'title' => 'Loja' ))?>
            </div>

        </div>

<?php
    }

    private function orders_section()
    {
        ?>
            <div class="wbg-myaccount-section-title">
                <img width="35px" src="<?php echo esc_url( Webigo_Myaccount_Settings::images('orders')['src']) ?>">
                <span>Pedidos</span>
            </div>
            <div class="wbg-myaccount-section-content">para ver suas compras recentes, os status dos pedidos e os detalhes deles.</div>
<?php
    }

    private function edit_address()
    {
        ?>
            <div class="wbg-myaccount-section-title">
                <img width="35px" src="<?php echo esc_url( Webigo_Myaccount_Settings::images('edit-address')['src']) ?>">
                <span>Endereços</span>
            </div>
            <div class="wbg-myaccount-section-content">para gerenciar seus endereços de entrega, alterar o numero de telefone e e-mail. É possível adicioná-los agora ou serão adicionados automaticamente quando você concluir sua compra.</div>
<?php
    }

    private function edit_account()
    {
        ?>
            <div class="wbg-myaccount-section-title">
                <img width="35px" src="<?php echo esc_url( Webigo_Myaccount_Settings::images('edit-account')['src']) ?>">
                <span>Detalhes da conta</span>
            </div>
            <div class="wbg-myaccount-section-content">para editar senha e detalhes da conta, como nome e sobrenome. Nesta seção você pode também alterar sua senha.</div>
<?php
    }
}