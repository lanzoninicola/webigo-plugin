<?php



class Webigo_Gotostore_Button {



    public static function render()
    {
        $button_label = 'ir para loja'
        ?>

        <a href="<?php echo esc_url( Webigo_Woo_Urls::shop() ) ?>" data-action-state="idle">
            <?php Webigo_View_Buttons::render( $button_label, 'primary' ); ?>
        </a>

<?php
    }


}

