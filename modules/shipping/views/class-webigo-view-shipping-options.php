<?php


class Webigo_View_Shipping_Options {


    public function render() 
    {
        $shipping_view_options = Webigo_Shipping_Settings::shipping_view_options();
        ?>

        <div class="wbg-shipping-options-container" data-visibility="visible">
            <div>Something here</div>
            <div class="wbg-shipping-options">
                <?php 
                    foreach ($shipping_view_options as $shipping_view_data) {
                        $this->render_shipping_option( $shipping_view_data );
                    }
                ?>
            </div>
        </div>

<?php
    }

    private function render_shipping_option( array $shipping_view_data )
    {
        $classes = 'wbg-shipping-option ' . $shipping_view_data['name'];
        ?>
            <div class='<?php echo esc_attr( $classes ) ?>'>
                <div class="wbg-shipping-option-wrapper">
                    <img src='<?php echo esc_url( $shipping_view_data['src'] ) ?>' alt="<?php echo esc_attr( $shipping_view_data['label'] ) ?>" title="<?php echo esc_attr( $shipping_view_data['label'] ) ?>">
                    <span class="small-text"><?php echo esc_html( $shipping_view_data['label'] ) ?></span>
                </div>
            </div>

<?php
    }

}