<?php



class Webigo_View_Thank_You_Page_Intro {



    public function render() {

        ?>

        <div class="wbg-thank-you-intro">
            <div class="wbg-thank-you-image-wrapper">
                <!-- <img class="wbg-thank-you-image" src="<?php echo esc_url( Webigo_Checkout_Settings::images('thankyou')['src'] ) ?>"></img> -->
                <div class="wbg-thank-you-image" style="background-image: url(<?php echo esc_url( Webigo_Checkout_Settings::images('thankyou')['src']) ?>)"></div>
            </div>
        </div>

        <?php
    }

}