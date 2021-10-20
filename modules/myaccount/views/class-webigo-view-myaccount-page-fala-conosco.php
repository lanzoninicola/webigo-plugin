<?php


class Webigo_View_Myaccount_Page_Fala_Conosco {


    /**
     * @var Webigo_View_Store_Contacts_Banner
     */
    private $view_store_contacts;


    public function __construct()
    {

        $this->load_dependencies();
    }


    private function load_dependencies() : void
    {
        require_once WEBIGO_PLUGIN_PATH . '/modules/widgets/views/class-webigo-view-store-contacts-banner.php';
        $this->view_store_contacts = new Webigo_View_Store_Contacts_Banner();

    }


    public function render() {
        ?>
            <div class="wbg-myaccount-fala-conosco-container">
                <div class="wbg-myaccount-fala-conosco-head">
                    <span>Fala conosco</span>
                    <i class="fas fa-arrow-right"></i>
                </div>
                <div class="wbg-myaccount-fala-conosco-content" data-visibility="hidden">
                    <?php $this->view_store_contacts->render() ?>
                </div>
            </div>  
        <?php
    }


}