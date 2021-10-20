<?php




class Webigo_View_Myaccount_Dashboard_Item {


    /**
     * @var array
     */
    private $endpoint;



    public function __construct( array $endpoint )
    {

        $this->endpoint = $endpoint;

    }

    public function render() {

        $endpoint_url = wc_get_endpoint_url( $this->endpoint['name'], '', wc_get_page_permalink('myaccount') );

        if ( $this->endpoint['name'] === 'painel_inicial' ) {
            $endpoint_url = wc_get_page_permalink('myaccount');
        }

        ?>

        <a href="<?php echo esc_url( $endpoint_url ); ?>" class="wbg-dashboard-nav-item">

            <img width="30px" src="<?php echo esc_url( Webigo_MyAccount_Settings::images($this->endpoint['name'])['src']) ?>" />
            <div class="wbg-dashboard-content">
                <span><?php echo esc_html( Webigo_MyAccount_Settings::images($this->endpoint['name'])['label'] ) ?></span>
               
                <?php if ( isset(  $this->endpoint['description'] ) ) : ?>
                <span><?php echo esc_html( $this->endpoint['description']) ?></span>
                <?php endif; ?>
            
            </div>
        </a>


<?php
    }

}

