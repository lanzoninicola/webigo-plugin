<?php



class Webigo_View_Link_Button {



    public static function render( string $label, string $type = 'primary', array $options = array(), array $link = array() )
    {

        ?>

            <a href="<?php echo esc_url( isset( $link['href'] ) ? $link['href'] : '' );  ?>" title="<?php echo esc_attr( isset( $link['title'] ) ? $link['title'] : ''  );  ?>">

                <?php Webigo_View_Buttons::render( $label , $type, $options ) ?>

            </a>


<?php
    }

}