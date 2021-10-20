<?php



class Webigo_View_Login_Button {


    /**
     * @param string $type primary|secondary
     * @param array  $options styles
     * @param bool   $redirect_origin
     */
    public static function render( string $type = 'primary', array $options = array(), bool $redirect_origin = true ) {

        $href = get_permalink( get_option('woocommerce_myaccount_page_id') );

        if ( $redirect_origin ) {
            global $post;
            $current_page_url = get_permalink( $post->ID );
            $href .= '?wbg-referer=' . $current_page_url;
        }

        ?>

        <div class="wbg-login-button-wrapper">

            <?php if ( is_user_logged_in() ) { ?>
                <a href="<?php echo esc_url( $href );  ?>" title="<?php _e('My Account','woothemes'); ?>">

                    <?php Webigo_View_Buttons::render( 'Entra' , $type, $options ) ?>

                </a>
            
            <?php } 
            
            else { ?>
                <a href="<?php echo esc_url( $href ); ?>" title="<?php _e('Login / Register','woothemes'); ?>">
                
                    <?php Webigo_View_Buttons::render( 'Entra / Cadastra-se' , $type , $options ) ?>
                
                </a>
            
            <?php } ?>

            </div>

        <?php

    }
}