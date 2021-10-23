<?php



class Webigo_View_Admin_Menu_List {


    public function __construct()
    {
        $this->load_dependencies();
    }

    private function load_dependencies()
    {
        require_once WEBIGO_PLUGIN_PATH . '/modules/wpadmin-menu/includes/class-webigo-wpadmin-menu-handler.php';
        $this->wpadmin_menu_handler = new Webigo_Wpadmin_Menu_Handler();
    }

    /**
     * This function is triggered when the "admin_menu" hook is fired
     * 
     */
    public function add_menu() : void
    {
        add_submenu_page(
            'webigo',
            __('Admin Menu List'),
            __('Admin Menu List'),
            'manage_options',
            'webigo_hide_menu_setup',
            array( $this, 'render' )
        );
    }

    public function render()
    {
        echo '<div class="webigo-menu-container">';
        
        echo '<div class="webigo-menu-target-roles">';
        echo '<p><strong>Current target roles: ' . implode(", ", $this->target_roles)  .'</strong></p>';
        echo '</div>';
        
        echo '<div class="webigo-menu-wrapper"">';

        foreach ($this->wpadmin_menu_handler->menu_schema() as $label => $menu_data) {
            $is_menu_hidden = $menu_data['hidden'] == 1 ? 1 : 0;

            $current_menu_visibility_status = $menu_data['hidden'] == 1 ? 'hidden' : 'shown';

            echo '<div class="webigo-menu-item" data-menu-hidden="' . $is_menu_hidden .'">';
            
            echo sprintf('<p><strong>Menu label:</strong> %s</p>', $label);
            echo '<p><strong>Menu slug:</strong> ' . esc_html($menu_data['slug']) . '</p>';
            echo '<p><strong>Current Visibility Status:</strong> ' . esc_html($current_menu_visibility_status) . '</p>';
            echo '</div>';
        }

        echo '</div>';

        echo '</div>';


        // var_dump($menu_list);
        // die;


    }
}
