<?php




class Webigo_View_Icon_Card {


    /**
     * @var array
     */
    private $endpoint;

    /**
     * @var array
     */
    private $options;


    /**
     * @var array $endpoint
     * 
     * $endpoint = array(
     *      'url'         => '',
     *      'name'       => '',
     *      'description' => '',
     *       )
     */
    public function __construct( array $endpoint , array $options = array() ) 
    {

        $this->endpoint = $endpoint;
        $this->options = $options;

    }

    public function render() {

        $class = '';

        if ( isset( $this->options['class'] ) ) {
            $class = implode( ' ', $this->options['class']);
        }
        
        $attributes = '';

        if ( isset( $this->options['attributes'])) {
            foreach ( $this->options['attributes'] as $attr => $attr_value) {
                $attributes .= " $attr=$attr_value";
            }
        }

        ?>

        <a href="<?php echo esc_url( $this->endpoint['url'] ); ?>" class="wbg-icon-card <?php echo esc_attr( $class ); ?>" <?php echo esc_attr( $attributes ); ?>>
            <img width="30px" src="<?php echo esc_url( Webigo_Core_Settings::images( $this->endpoint['name'])['src'] ) ?>" />
            <div class="wbg-icon-card-content">
                <span><?php echo esc_html( Webigo_Core_Settings::images( $this->endpoint['name'])['label'] ) ?></span>

                <?php if ( isset( $this->endpoint['description'] )) { ?>
                <span><?php echo esc_html( $this->endpoint['description'] ) ?></span>
                <?php } ?>
            
            </div>
        </a>


<?php
    }

}

