<?php 



class Webigo_Login_Button_Shipping_Screen {


    public function __construct()
    {
        $this->load_dependencies();
    }

    private function load_dependencies()
    {
        require_once WEBIGO_PLUGIN_PATH . 'modules/core/views/class-webigo-view-buttons.php';

        require_once WEBIGO_PLUGIN_PATH . 'modules/core/views/class-webigo-view-link-button.php';

        require_once WEBIGO_PLUGIN_PATH . 'modules/core/views/class-webigo-view-divider.php';
    }

    public function render()
    {

        $href = wc_get_endpoint_url( 'orders', '', wc_get_page_permalink('myaccount') );
    ?>
        
        <div style="padding: 0 2rem;" class="wbg-shipping-login-button">
            <div class="divider"></div>

            <?php
            if ( ! is_user_logged_in() ) {
                Webigo_View_Login_Button::render( 'primary' ); 
            }
             
            if ( is_user_logged_in() ) {
                Webigo_View_Link_Button::render( 'Meus pedidos', 'primary', [], array( 'href' => $href, 'title' => 'Meus Pedidos')); 
            }
             ?>
        </div>


<?php
    }


}