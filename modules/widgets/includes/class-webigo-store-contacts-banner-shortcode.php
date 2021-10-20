<?php


class Webigo_Store_Contacts_Banner_Shortcode {
 

    public function __construct()
    {
        $this->load_dependencies();
        $this->add_shortcodes();
        
    }

    private function load_dependencies()
    {
        require_once WEBIGO_PLUGIN_PATH . '/modules/widgets/views/class-webigo-view-store-contacts-banner.php';
    }

    public function add_shortcodes()
    {
        add_shortcode( 'webigo_store_contacts_banner', array( $this, 'render' ) );
    }

    public function render()
    {

        $view = new Webigo_View_Store_Contacts_Banner( );

        $view->render( );
    }


}
