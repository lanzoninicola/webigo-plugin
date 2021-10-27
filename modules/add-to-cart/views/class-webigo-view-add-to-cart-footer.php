<?php



class Webigo_View_Add_To_Cart_Footer {


    public function __construct()
    {
        $this->load_dependencies();
    }

    private function load_dependencies() : void
    {
        require_once WEBIGO_PLUGIN_PATH . 'modules/core/views/class-webigo-view-buttons.php';

    }


    public function render() {

        if ( ! is_archive() ) {
            return;
        }

        ?>
        <div class="wbg-add-to-cart-footer mini" data-visibility="hidden" data-action-state="idle">
            <div class="wbg-add-to-cart-footer-button">
                <?php $this->render_mini_cart_button() ?>
            </div>
            <?php $this->render_wp_nonce() ?>
        </div>

        <?php
    }

    private function render_mini_cart_button() : void
    {
        ?>
        <div class="wbg-mini-add-to-cart-footer wbg-bulk-add-to-cart">
            <span class="wbg-add-to-cart-label"></span>    
            <i class="ti-plus"></i>    
            <i class="ti-shopping-cart"></i>
        </div>

        <?php
    }

    /*
    private function render_add_to_cart_button() : void
    {
        $button_options = array(
            'button' => array(
                'class' => ['wbg-bulk-add-to-cart'],
                'attributes' => array(
                    'data-visibility' => 'visible'
                )
            )
        );

        Webigo_View_Buttons::render('', 'primary', $button_options );

    }

    private function render_show_cart() : void
    {
        $button_options = array(
            'button' => array(
                'class'      => ['wbg-show-cart'],
                'attributes' => array(
                    'data-visibility' => 'hidden'
                )
            )
        );

        Webigo_View_Buttons::render('Veja o carrinho', 'secondary', $button_options );

    }
    */

    private function render_wp_nonce() {

        $action_name = Webigo_Add_To_Cart_Settings::AJAX_ADD_TO_CART_BULK_ACTION_NAME;

        /**
         *  The second param is also used in "add-to-cart.js" 
         *  to identify the input field with the nonce value
         */

        wp_nonce_field( $action_name , $action_name . '_nonce' );
    }

   
    
}