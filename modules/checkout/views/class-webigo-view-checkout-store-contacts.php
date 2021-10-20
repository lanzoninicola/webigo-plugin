<?php



class Webigo_View_Checkout_Store_Contacts {


    /**
     * @var Webigo_View_Store_Contacts_Banner
     */
    private $view_store_contacts;

    public function __construct()
    {
        $this->load_dependencies();
    }

    private function load_dependencies()
    {
        require_once WEBIGO_PLUGIN_PATH . '/modules/widgets/views/class-webigo-view-store-contacts-banner.php';
        $this->view_store_contacts = new Webigo_View_Store_Contacts_Banner();
    }

    public function render() {
        ?>

            <div class="wbg-checkout-store-contacts">
                <h2>Contatos Loja</h2>
                <?php $this->view_store_contacts->render() ?>
            </div>


<?php

    }
}