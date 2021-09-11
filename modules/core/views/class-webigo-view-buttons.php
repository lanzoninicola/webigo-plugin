<?php


class Webigo_View_Buttons {

    /**
     * Render a button.
     * 
     * Button status: enabled (default status)|disabled|pressed
     * 
     * @param string $label
     * @param string $type primary|secondary|ternary
     */
    public static function render( string $label, string $type = 'primary' ) 
    {
?>
        <div class="wbg-button wbg-<?php echo esc_attr( $type ); ?>-button" data-status="enabled"><?php echo esc_html( trim( $label ) ); ?></div>

<?php
    }

}