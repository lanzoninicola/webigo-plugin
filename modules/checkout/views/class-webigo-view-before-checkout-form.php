<?php



class Webigo_View_Before_Checkout_Form {



    public function render() {

        ?>

        <div class="wbg-checkout-intro">
            
            <a href="<?php echo esc_url( get_site_url () ) ?>" >
                <img class="wbg-logo" src="<?php echo esc_url( Webigo_Checkout_Settings::images('logo')['src'] ) ?>"></img>
            </a>
            <h2>Confira e finalize seu pedido</h2>
        </div>

        <?php
    }

}