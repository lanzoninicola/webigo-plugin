<?php


class Webigo_View_Myaccount_Page_Nav {


    /**
     * @var Webigo_Myaccount_Page
     */
    private $myaccount_page;


    public function __construct()
    {

        $this->load_dependencies();
    }


    private function load_dependencies() : void
    {
        require_once WEBIGO_PLUGIN_PATH . 'modules/myaccount/includes/class-webigo-myaccount-page.php';
        $this->myaccount_page = new Webigo_Myaccount_Page();

        require_once WEBIGO_PLUGIN_PATH . 'modules/myaccount/views/class-webigo-view-myaccount-dashboard-item.php';
        
        $this->dashboard_item = new Webigo_View_Myaccount_Dashboard_Item(
            array(
                'name' => 'painel_inicial', 
                 )
        );

        $this->orders_item = new Webigo_View_Myaccount_Dashboard_Item(
            array(
                'name' => 'orders', 
                'description' => 'Ver sua compra recente'
                 )
        );

        $this->edit_address_item = new Webigo_View_Myaccount_Dashboard_Item(
            array(
                'name' => 'edit-address', 
                'description' => 'Gerencia seus endereços de entrega'
                 )
        );

        $this->edit_account_item = new Webigo_View_Myaccount_Dashboard_Item(
            array(
                'name' => 'edit-account', 
                'description' => 'Edita senha e detalhes da conta'
                 )
        );
    }


    public function render() {
        ?>
                <div class="wbg-myaccount-dashboard-container">
                    <div class="wbg-myaccount-dashboard-wrapper">
                        <?php 
                            if( $this->myaccount_page->should_redirect_to_order_section() === false ) {
                                echo esc_html( $this->dashboard_item->render() ) ;
                            };
                        ?>    
                        <?php echo esc_html( $this->orders_item->render() ) ?>
                        <?php echo esc_html( $this->edit_address_item->render() ) ?>
                        <?php echo esc_html( $this->edit_account_item->render() ) ?>
                    </div>
                </div>

        <?php
    }





}