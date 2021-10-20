<?php




class Webigo_Customer {



    /**
     * @var WC_Customer
     * 
     */
    private $customer;

    public function __construct( int $user_id = null )
    {

        if ( $user_id === null) {
            // https://developer.wordpress.org/reference/functions/get_current_user_id/
            $current_user = get_current_user_id();

            if( $current_user === 0 ) {
                $this->user_id = null;
            }

            if( $current_user !== 0 ) {
                $this->user_id = $current_user;
            }
        }

        if ($user_id !== null ) {
            $this->user_id = $user_id;
        }
        
        $this->customer = false;

        $this->load_customer();
    }

    private function load_customer() : void
    {
        $this->customer = new WC_Customer( $this->user_id );
    }

    public function avatar_url() : string
    {
        return $this->customer->get_avatar_url();
    }


    public function name() : string
    {
        return $this->customer->get_first_name();
    }

    public function lastname() : string
    {
        return $this->customer->get_last_name();
    }

    public function joined() : object
    {
        return $this->customer->get_date_created();
    }

    public function has_shipping_address() : bool
    {
        return $this->customer->has_shipping_address();
    }

    public function has_orders()
    {
        $nr_of_orders = $this->customer->get_order_count();

        return $nr_of_orders === 0 ? false : true;
    }
}