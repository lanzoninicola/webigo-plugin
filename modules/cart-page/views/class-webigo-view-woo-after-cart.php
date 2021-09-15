<?php



class Webigo_View_Woo_After_Cart {



    public function render() {

        $shop_page_url = get_permalink( wc_get_page_id( 'shop' ) );
        
        ?>

            <div class="wbg-cart-voltar">
                <a href="<?php echo esc_url( $shop_page_url ); ?>" class="wbg-button wbg-ternary-button">Voltar para loja</a>
            </div>
<?php
    }
}