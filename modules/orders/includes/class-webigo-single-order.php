<?php




class Webigo_Single_Order {


    /**
     * @var WC_Order|WC_Order_Refund
     */
    private $order;

    /**
     * PENDING|AGUARDANDO|PROCESSANDO|ENTREGANDO = order_in_progress
     * CANCELADO|CONCLUIDO = done
     */
    private $order_working_state = array();


    public function __construct( object $order )
    {
        $this->order = $order;
        $this->order_working_state = Webigo_Orders_Settings::ORDER_WORKING_STATE;
    }

    public function id()
    {
        return $this->order->get_id();
    }

    public function number()
    {
        return $this->order->get_order_number();
    }

    public function url()
    {
        return $this->order->get_view_order_url();
    }

    public function created_date()
    {
        return $this->order->get_date_created();
    }

    public function created_date_datetime()
    {
        return $this->order->get_date_created()->date( 'c' );
    }

    public function view_order_url() : string
    {
        return $this->order->get_view_order_url();
    }

    public function status()
    {
        return wc_get_order_status_name( $this->order->get_status() );
    }
    
    private function current_working_state() : string
    {
        $current_order_status = $this->status();

        if( isset( $current_order_status ) ) {
             
            $_order_working_state = $this->order_working_state[strtoupper( $current_order_status )];

            if ( isset( $_order_working_state ) && $_order_working_state !== null ) {
                return $_order_working_state;
            }

            return '';
        }

        return '';
    }
    
    /**
     * Based on Woocommerce order status 
     * it returns if the order state of work is DONE
     *  
     * @return bool true|false
     */
    public function is_closed() : bool
    {

        $current_working_state = $this->current_working_state();

        if ( isset( $current_working_state ) && $current_working_state !== '' ) {
            return $current_working_state === 'DONE' ? true : false;
        }

        return false;
    }

    public function shipping_methods() : array
    {
        return $this->order->get_shipping_methods();
    }

    public function total() : string
    {
        return $this->order->get_total();
    }

    public function item_count() : int
    {
        return $this->order->get_item_count() - $this->order->get_item_count_refunded();
    }

    /**
     * @return array of WP_Comment
     */
    public function note() : array
    {
        return $this->order->get_customer_order_notes();
    }

    /**
     * Check if the order contain note
     * 
     * @return bool true|false
     */
    public function has_note() : bool
    {

        $note = $this->note();

        if ( isset( $note ) && count( $note ) > 0 ) {
            return true;
        }

        return false;
    }




}