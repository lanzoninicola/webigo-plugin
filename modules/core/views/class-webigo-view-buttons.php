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
    public static function render( string $label, string $type = 'primary', array $options = array()) 
    {

        /**
         * $options = array(
         *      'button' => array (
         *          'class'    => array()
         *          'attributes' => array(
         *                         att1 => '',
         *                         att2 => '',
         *                          )
         *          )
         *       'label' => array (
         *          'class'    => array()
         *          'attributes' => array(
         *                         att1 => '',
         *                         att2 => '',
         *                          )
         *          )
         * )
         */

        $button_classes = '';

        if ( isset( $options['button']['class'] ) ) {
            $button_classes = implode( ' ', $options['button']['class']);
        }

        $button_attributes = '';

        if ( isset( $options['button']['attributes'])) {
            foreach ( $options['button']['attributes'] as $attr => $attr_value) {
                $button_attributes .= " $attr=$attr_value";
            }
        } 

        
        // $button_attributes = '';
        // $button_attributes .= "data-status='enabled'";

        // if ( isset( $options['button']['attributes'])) {
        //     foreach ( $options['button']['attributes'] as $attr => $attr_value) {
        //         $button_attributes .= " $attr=$attr_value";
        //     }
        // } 

        // $base_button_classes = "wbg-button wbg-$type-button";
        // $button_classes = isset( $options['button']['classes'] ) ? $base_button_classes . ' ' . $options['button']['classes'] : $base_button_classes;

        
        // $base_label_classes = "wbg-button-label";
        // $label_classes = isset( $options['label']['classes'] ) ? $base_label_classes . ' ' . $options['label']['classes'] : $base_label_classes;


        // //  $label = null, string $type = 'primary'

        // $label = isset( $options['label'] ) ? $options['label'] : '';

        // // button string result
        //  return sprintf(
        //     '<div class=%s %s>%s</div>',
        //     esc_attr( $button_classes ),
        //     esc_attr( $button_attributes )
        //  );


?>
        <div class="wbg-button wbg-<?php echo esc_attr( $type ); ?>-button <?php echo esc_attr( $button_classes ); ?>" <?php echo esc_attr( $button_attributes ); ?> data-action-state="idle">
            <span class="wbg-button-label"><?php echo esc_html( trim( $label ) ); ?></span>
        </div>

<?php
    }

}