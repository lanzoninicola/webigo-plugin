<?php



class Webigo_View_Overlay {


    /**
     * Firstly, I need to put a container in the footer and doing the action.
     * But, adding a parameter I can programmatically show or not the 
     */
    public function render( )
    {
        $overlay_visibility = apply_filters( 'webigo_overlay_visibility', 'hidden' );
        
        ?>

        <div class="wbg-overlay" data-visibility="<?php echo esc_attr( $overlay_visibility ); ?>">
            <?php do_action('webigo_render_overlay_content') ; ?>
        </div>

        <?php
    }

   

}

