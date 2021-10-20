<?php


class Webigo_Order_Status {

    //TODO: melhorar adding a dedicated class for creation of a new status

    // Register new status
    function register_entregando() {
        register_post_status( 'wc-shipment', array(
            'label'                     => 'Saiu para entrega',
            'public'                    => true,
            'exclude_from_search'       => false,
            'show_in_admin_all_list'    => true,
            'show_in_admin_status_list' => true,
            'label_count'               => _n_noop( 'Saiu para entrega (%s)', 'Saiu para entrega (%s)' )
        ) );
    }

    // Add to list of WC Order statuses
    public function entregando( $order_statuses )
    {
        $new_order_statuses = array();

         // add new order status after processing
        foreach ( $order_statuses as $key => $status ) {
    
            $new_order_statuses[ $key ] = $status;
    
            if ( 'wc-processing' === $key ) {
                $new_order_statuses['wc-shipment'] = 'Saiu para entrega';
            }
        }
    
        return $new_order_statuses;

    }

    // Adding custom status to admin order list bulk actions dropdown
    function custom_dropdown_bulk_actions_shop_order( $actions ) {
        $new_actions = array();

        // Add new custom order status after processing
        foreach ($actions as $key => $action) {
            $new_actions[$key] = $action;
            if ('mark_processing' === $key) {
                $new_actions['mark_order-shipped'] = 'Mudar status para Saiu para entrega';
            }
        }

        return $new_actions;
    }


}
