<?php


class Webigo_Wpadmin_Menu_Handler
{
    private $menus_to_hide = ['painel', 'bricks', 'posts', 'páginas', 'ferramentas'];

    private $target_roles = ['shop_manager', 'editor'];

    private $is_admin_area = false;

    private $is_valid_user_logged = false;

    private $user_logged;

    private $user_logged_role;

    private $menu_list;
    // private $show_menu_list = true; // discover mode: setting true shows all menu values


    /**
     * @var Webigo_View_Wpadmin_Menu
     */
    private $view_wpadmin_menu;


    public function __construct()
    {
        $this->load_dependencies();
    }

    private function load_dependencies()
    {
        require_once WEBIGO_PLUGIN_PATH . '/modules/wpadmin-menu/views/class-webigo-wpadmin-menu.php';
        $this->$view_wpadmin_menu = new Webigo_View_Wpadmin_Menu();
    }


    public function init_checks()
    {
        $this->should_admin_area();
        $this->should_current_user_logged();
        $this->should_valid_user_logged();
        $this->get_user_logged_roles();
    }

    private function should_admin_area()
    {
        $this->is_admin_area = is_admin();
    }


    private function should_current_user_logged()
    {
        $this->user_logged = wp_get_current_user();
    }

    private function should_valid_user_logged()
    {
        if (isset($this->user_logged) & $this->user_logged->exists()) {
            $this->is_valid_user_logged = true;
        }
    }

    private function get_user_logged_roles()
    {
        if ($this->is_valid_user_logged) {
            $user_roles = (array) $this->user_logged->roles;
            $this->user_logged_role = $user_roles[0];
        }
    }

    public function build_menu_schema()
    {
        if (!$this->is_admin_area || !$this->is_valid_user_logged) {
            return;
        }
        // start he hiding menu items process
        $wp_admin_menu = $GLOBALS['menu'];

        $this->menu_list = array();

        // build a menu schema
        foreach ($wp_admin_menu as $key => $wp_admin_menu_item) {
            /**
             *      $wp_admin_menu_item[0] = menu label
             *      $wp_admin_menu_item[2] = menu slug
             */

            $menu_item_label = strtolower($wp_admin_menu_item[0]);

            if (substr($wp_admin_menu_item[2], 0, 9) === 'separator') {
                $menu_item_label = 'separator';
            }

            $this->menu_list[$menu_item_label] = array('hidden' => false, 'slug' => $wp_admin_menu_item[2]);
        }
    }

    public function hide_menus()
    {
        if (!$this->is_admin_area || !$this->is_valid_user_logged) {
            return;
        }
        // hide the menu
        foreach ($this->menu_list as $label => $menu_data) {

            if (in_array($label, $this->menus_to_hide)) {

                if (in_array($this->user_logged_role, $this->target_roles)) {
                    $this->menu_list[$label]['hidden'] = true;
                    $has_menu_removed = remove_menu_page($menu_data['slug']);
                }
                // if(!$has_menu_removed) {
                //     echo esc_html('<h1>Menu ' . $label . ' has not be removed</h1>');
                // }
            }
        }
    }

    public function add_setup_menu()
    {
        add_submenu_page(
            'webigo',
            __('Admin Menu List'),
            __('Admin Menu List'),
            'manage_options',
            'webigo_hide_menu_setup',
            array( $this->view_wpadmin_menu, 'render' )
        );
    }

    public function menu_schema()
    {
        return $this->menu_list;
    }

   
}
