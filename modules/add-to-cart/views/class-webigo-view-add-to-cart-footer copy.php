<?php



class Webigo_View_Add_To_Cart_Footer {

     /**
     * 
     * @var Webigo_View_Inline_Add_To_Cart_Notification
     */
    private $inline_notification;

    public function __construct()
    {
        $this->load_dependencies();
    }

    private function load_dependencies() : void
    {
        require_once WEBIGO_PLUGIN_PATH . 'modules/core/views/class-webigo-view-buttons.php';

        require_once WEBIGO_PLUGIN_PATH . '/modules/add-to-cart/views/class-webigo-view-add-to-cart-inline-notification.php';
        $this->inline_notification = new Webigo_View_Inline_Add_To_Cart_Notification();
    }


    public function render() {

        if ( ! is_archive() ) {
            return;
        }

        ?>
        <div class="wbg-add-to-cart-footer" data-visibility="hidden" data-action-state="idle">
            <div class="wbg-add-to-cart-miniature"></div>
            <div class="wbg-add-to-cart-footer-button">
                <?php echo $this->inline_notification->render() ?>    
                <?php $this->render_add_to_cart_button() ?>
                <?php $this->render_show_cart() ?>
            </div>
            <?php $this->render_wp_nonce() ?>
        </div>

        <?php
    }

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

    private function render_wp_nonce() {

        $action_name = Webigo_Add_To_Cart_Settings::AJAX_ADD_TO_CART_BULK_ACTION_NAME;

        /**
         *  The second param is also used in "add-to-cart.js" 
         *  to identify the input field with the nonce value
         */

        wp_nonce_field( $action_name , $action_name . '_nonce' );
    }

   
    
}