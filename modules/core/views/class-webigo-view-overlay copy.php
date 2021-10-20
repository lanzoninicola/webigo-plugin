<?php



class Webigo_View_Overlay {


    public function render( )
    {
        ?>

        <div class="wbg-overlay-container">
            <?php do_action('webigo_render_overlay') ?>
        </div>

        <?php
    }


    public function render_overlay( bool $show_overlay = false )
    {
        
        
        if ( $show_overlay === true ) :
        ?>
        
        <div class="wbg-overlay" data-visibility="visible">
            <?php do_action('webigo_render_overlay_content') ; ?>
        </div>
        
        <?php
        endif;
    }

   

}

