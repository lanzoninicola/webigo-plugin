<?php



class Webigo_Plugin_Menu_Handler {

    /**
     * @var array
     */
    private $plugin_menu = array();

    public function __construct( )
    {
        $this->plugin_menu = (array) Webigo_Core_Settings::PLUGIN_MENU;
    }

    public function add_root_page()
    {
        $root_page = (array) $this->plugin_menu['root'];

        add_menu_page( 
            $root_page['page_title'],
            $root_page['menu_title'],
            $root_page['capability'],
            $root_page['menu_slug'],
            function() {
                echo '<div></div>'; // TODO: adding a view for this menu
            }
         );
      
    }


    // public function add_menu() : void
    // {
    //     add_submenu_page(
    //         'webigo',
    //         __('Admin Menu List'),
    //         __('Admin Menu List'),
    //         'manage_options',
    //         'webigo_hide_menu_setup',
    //         array( $this, 'render' )
    //     );
    // }

    

    


}