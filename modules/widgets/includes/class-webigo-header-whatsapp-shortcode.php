<?php


class Webigo_Header_Whatsapp_Shortcode {
 

    public function __construct()
    {
        $this->load_dependencies();
        $this->add_shortcodes();
        
    }

    private function load_dependencies()
    {
        require_once WEBIGO_PLUGIN_PATH . '/modules/widgets/views/class-webigo-view-header-whatsapp-shortcode.php';
    }

    public function add_shortcodes()
    {
        add_shortcode( 'header_whatsapp', array( $this, 'render' ) );
    }

    public function render()
    {

        $view = new Webigo_View_Header_Whatsapp_Shortcode( );

        $view->render( );
    }


}
