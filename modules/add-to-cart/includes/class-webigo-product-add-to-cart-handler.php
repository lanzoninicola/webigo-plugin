<?php



class Webigo_Product_Add_To_Cart_Handler {


    /**
     * The action name used to fire the add to cart actions
     * 
     * @var string
     */
    private $action_name;

    /**
     * The ID of product to add to cart
     * 
     * @var int
     */
    private $product_id;

    /**
     * The product quantity to add to cart
     * 
     * @var int
     */
    private $quantity;


    /**
     * Result of add to cart operation
     * 
     * @var bool
     */
    private $is_product_added_to_cart;


    public function __construct( $action_name, $product_id, $quantity )
    {
        $this->action_name = $action_name;
        $this->product_id = 0;
        $this->quantity = 0;
        $this->is_product_added_to_cart = false;
        
        $this->set_cart_required_data( $product_id, $quantity );
    }


    /**
     * Set the data in Woocommerce compatible mode
     * 
     * @param int $product_id
     * @param int $quantity
     * @return void
     */
    private function set_cart_required_data( int $product_id, int $quantity ) 
    {
        $this->product_id  = apply_filters($this->action_name . '_product_id', $product_id, );
        $this->quantity    = $quantity;
    }

    /**
     * Cart Validation
     * 
     * @return bool
     */
    private function is_passed_validation() : bool
    {
        $passed_validation = apply_filters($this->action_name . '_validation', true, $this->product_id, $this->quantity);
        $product_status    = get_post_status( $this->product_id );

        if ( $passed_validation && 'publish' === $product_status ) {
            return true;
        }

        return false;
    }


    /**
     * Add product to cart
     * 
     * @return void
     */
    public function add_to_cart() : void
    {

        
        if ( $this->is_passed_validation() ) {

            if ( WC()->cart->add_to_cart( $this->product_id, $this->quantity ) ) {
            
                $this->is_product_added_to_cart = true;

                do_action($this->action_name . '_added_to_cart', $this->product_id);

            }

        }

        if ( ! $this->is_passed_validation() ) {

            $this->is_product_added_to_cart = false;
        }
    }

    public function is_product_added_to_cart() : bool
    {
        return $this->is_product_added_to_cart;
    }
}