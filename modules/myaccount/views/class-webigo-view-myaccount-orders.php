<?php




class Webigo_View_Myaccount_Orders {

    /**
     * Array of customer orders
     * 
     * Passed by Woocommerce /woocommerce/includes/wc-template-functions.php (row: 3166)
     * 
     */
    private $customer_orders;

    /**
     * @param Webigo_Woo_Custom_Template    $woo_custom_template
     * @param array of WC_Orders[]          $customer_orders
     */
    public function __construct( $customer_orders ) {

        $this->customer_orders     = $customer_orders;
        $this->load_dependencies();
    }

    private function load_dependencies() 
    {
        require_once WEBIGO_PLUGIN_PATH . 'modules/myaccount/views/class-webigo-view-myaccount-single-order.php';
        
        require_once WEBIGO_PLUGIN_PATH . 'modules/orders/includes/class-webigo-single-order.php';
    }
    
    public function render()
    {
        foreach ( $this->customer_orders->orders as $customer_order ) {
            $order = new Webigo_Single_Order( $customer_order );
            $view_customer_order = new Webigo_View_Myaccount_Single_Order( $customer_order );

            ?>
            <div class="wbg-order-wrapper">
                <div class="wbg-order-head" data-order-id="<?php echo esc_attr( $order->id() ) ?>">
                    <div class="wbg-order-head-col1">
                        <div class="wbg-order-icon">
                            <?php $view_customer_order->icon() ?>    
                        </div>
                        <div>
                            <?php $view_customer_order->number() ?>
                            <?php $view_customer_order->created_date() ?>    
                        </div>
                    </div>
                    <div class="wbg-order-head-col2">
                        <?php $view_customer_order->status() ?>
                    </div>
                    
                 </div>
                <div class="wbg-order-content" data-visibility="hidden">
                    <div class="wbg-order-content-row1">
                       <?php $view_customer_order->notifications_alert(); ?>
                       <?php $view_customer_order->shipping_methods(); ?>
                       <?php $view_customer_order->total(); ?>
                       <?php $view_customer_order->item_count(); ?>
                    </div>

                    <div class="wbg-order-content-row2">
                        <?php $view_customer_order->order_actions(); ?>
                    </div>
                
                </div> <!-- end of wbg-order-content -->
            </div>

            <?php
        } // end foreach
    }
    
}