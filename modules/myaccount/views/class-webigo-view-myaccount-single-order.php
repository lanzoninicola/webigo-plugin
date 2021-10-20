<?php



class Webigo_View_Myaccount_Single_Order {


     /**
     * @var Webigo_Single_Order
     */
    private $order;

    /**
     * Array of notes leaved by the Shop Manager inside the order.
     * 
     * @var array
     */
    private $note = array();


    /**
     * @param WC_Order|WC_Order_Refund
     */
    public function __construct( object $wc_order )
    {
        $this->load_dependencies();
        
        $this->order = new Webigo_Single_Order( $wc_order );
        $this->load_related_note();
    }

    private function load_dependencies()
    {
        require_once WEBIGO_PLUGIN_PATH . 'modules/orders/includes/class-webigo-single-order.php';
    }

    private function load_related_note()
    {
        $this->note = (array) $this->order->note();
    }

    public function icon()
    {
        $order_is_closed = $this->order->is_closed();

        if ( $this->order->has_note() && $order_is_closed === false ) {
            $this->notification_icon();
        } else {
            $this->order_icon();
        }
    }

    private function order_icon()
    {
        ?>
        <img width="30px" src="<?php echo esc_url( Webigo_MyAccount_Settings::images( 'orders' )['src']) ?>" />
        <?php
    }

    private function notification_icon()
    {
        ?>
        <img width="30px" src="<?php echo esc_url( Webigo_MyAccount_Settings::images( 'order_notification' )['src']) ?>" class="new-order-notification" />
        <?php
    }

    public function number()
    {
        ?>
        
        <?php echo esc_html( _x( '#', 'hash before order number', 'woocommerce' ) . $this->order->number() ); ?>
<?php
    }

    public function created_date()
    {
        ?>
        <div class="wbg-order-created-date">
            <time datetime="<?php echo esc_attr( $this->order->created_date_datetime() ); ?>"><?php echo esc_html( wc_format_datetime( $this->order->created_date() ) ); ?></time>
            <time datetime="<?php echo esc_attr( $this->order->created_date_datetime() ); ?>"><?php echo esc_html( wc_format_datetime( $this->order->created_date(), 'H:m' ) ); ?></time>
        </div>
<?php
    }

    public function status( )
    {
        $order_status_name = $this->order->status();
        $css_order_status_name =  str_replace( ' ', '-', strtolower( $order_status_name ) );
    ?>

        <span class="wbg-order-status <?php echo esc_attr( $css_order_status_name ) ?>">
            <?php echo esc_html( $order_status_name ); ?>
        </span>

<?php
    }

    public function shipping_methods()
    {
        ?>
        <div class="wbg-order-shipping-method">
            <?php
            foreach( $this->order->shipping_methods() as $wc_order_item_shipping ) {
                ?>
                <span class="label">Metodo Entrega:</span>
                <span class="order-value wbg-shipping-method"><?php echo esc_html( $wc_order_item_shipping->get_name() ) ?></span>
                <?php
            }
            ?>
        </div>

<?php
    }

    public function total()
    {
        ?>

        <div class="wbg-order-total">
            <span class="label">Total:</span>
            <span class="order-value wbg-order-total"><?php echo esc_html( $this->order->total() ) ?></span>
        </div>

<?php
    }

    public function item_count()
    {
        ?>

        <div class="wbg-order-items-count">
            <span class="label">Nr. Itens:</span>
            <span class="order-value wbg-order-total"><?php echo esc_html( $this->order->item_count() ) ?></span>
        </div>

<?php
    }

    public function order_actions()
    {
        $actions = wc_get_account_orders_actions( $this->order->id() );

        if ( ! empty( $actions ) ) {
            foreach ( $actions as $key => $action ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
                echo '<a href="' . esc_url( $action['url'] ) . '" class="wwwwww ' . sanitize_html_class( $key ) . '">' . esc_html( $action['name'] ) . '</a>';
            } // end foreach
        }
    }

    public function notifications_alert()
    {

        if ( $this->order->has_note() ) {
            ?>
            <div class="wbg-order-notification-alert">
                <span class="message">Há uma mensagem para você.</span>
                <a href="<?php echo esc_url( $this->order->url() ); ?>">
                    <span>Visualizar</span>
                </a>
            </div>
            
            <?php
        }
        
    }


}