<?php



class Webigo_Customer_Shipping_Data {

    /**
     * The user_id
     * 
     * @var int|bool
     */
    private $user_id;

    /**
     * Customer from the user_id
     * 
     * @var WC_Customer
     */
    private $customer;

    /**
     * Get data from WC_Customer class.
     * 
     * @param int|null $user_id
     * 
     * If param is not passed the constructor will try to get data 
     * from the current logged user.
     */
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

    public function address_1() : string
    {
        if ( $this->customer !== false ) {
            return $this->customer->get_shipping_address_1();
        }

        return '';
    }

    public function address_2() : string
    {
        if ( $this->customer !== false ) {
            return $this->customer->get_shipping_address_2();
        }

        return '';
    }

    public function city() : string
    {
        if ( $this->customer !== false ) {
            return $this->customer->get_shipping_city();
        }

        return '';
    }

    public function state_code() : string
    {
        if ( $this->customer !== false ) {
            return $this->customer->get_shipping_state();
        }

        return '';
    }

    public function state()
    {
        $customer_state_code  = trim( $this->customer_shipping_data->state() );
        $woo_states           = WC()->countries->get_states();

        if ( isset( $woo_states ) && isset( $customer_state_code ) ) {
            return $woo_states[$customer_state_code];
        }

        return '';
    }

    public function postcode() : string
    {
        if ( $this->customer !== false ) {
            return $this->customer->get_shipping_postcode();
        }

        return '';
    }


    public function country_code() : string
    {
        if ( $this->customer !== false ) {
            return $this->customer->get_shipping_country();
        }

        return '';
    }

    public function country() : string
    {
        $customer_country_code  = trim( $this->country_code() );
        $woo_countries          = WC()->countries->get_countries();

        if ( isset( $woo_countries ) && isset( $customer_country_code ) ) {
            return $woo_countries[$customer_country_code];
        }

       return '';
    }

}