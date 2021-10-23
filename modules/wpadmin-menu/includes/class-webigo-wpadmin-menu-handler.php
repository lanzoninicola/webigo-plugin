<?php


class Webigo_Wpadmin_Menu_Handler
{
    

    // private $is_admin_area = false;

    // private $is_valid_user_logged = false;

    // private $user_logged;

    // private $user_logged_role;

    /**
     * @var array
     */
    private $menu_list = array();

    public function __construct()
    {
    }

    // public function init_checks()
    // {
    //     $this->should_admin_area();
    //     $this->should_current_user_logged();
    //     $this->should_valid_user_logged();
    //     $this->get_user_logged_roles();
    // }

    // private function should_admin_area()
    // {
    //     $this->is_admin_area = is_admin();
    // }


    // private function should_current_user_logged()
    // {
    //     $this->user_logged = wp_get_current_user();
    // }

    // private function should_valid_user_logged()
    // {
    //     if (isset($this->user_logged) & $this->user_logged->exists()) {
    //         $this->is_valid_user_logged = true;
    //     }
    // }

    // private function get_user_logged_roles()
    // {
    //     if ($this->is_valid_user_logged) {
    //         $user_roles = (array) $this->user_logged->roles;
    //         $this->user_logged_role = $user_roles[0];
    //     }
    // }

    public function build_menu_schema()
    {
            // start he hiding menu items process
        $wp_admin_menu = $GLOBALS['menu'];

        $this->menu_list = array();

        // build a menu schema
        foreach ($wp_admin_menu as $wp_admin_menu_item) {
            /**
             *      $wp_admin_menu_item[0] = menu label
             *      $wp_admin_menu_item[1] = capability
             *      $wp_admin_menu_item[2] = menu slug
             */

            $menu_item_label = strtolower($wp_admin_menu_item[0]);

            if ( substr( $wp_admin_menu_item[2], 0, strlen('separator')) === 'separator' ) {
                $menu_item_label = 'separator';
            }

            $this->menu_list_by_label[$menu_item_label] = array(
                'capability' => $wp_admin_menu_item[1],
                'slug'       => $wp_admin_menu_item[2]
            );

            $this->menu_list_by_slug[$wp_admin_menu_item[2]] = array(
                'capability' => $wp_admin_menu_item[1],
                'label'      => $menu_item_label,
            );

            if ( isset( $this->menu_list_by_capabilities[$wp_admin_menu_item[1]] ) === false ) {
                $this->menu_list_by_capabilities[$wp_admin_menu_item[1]] = array();
            }

            array_push( $this->menu_list_by_capabilities[$wp_admin_menu_item[1]], array(
                'label' => $menu_item_label,
                'slug'  => $wp_admin_menu_item[2]
            ));
        }

        // var_dump( $this->menu_list_by_slug); die;
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



    public function menu_schema()
    {
        return $this->menu_list;
    }

    public function should_menu_visible_for_role( string $slug, string $role ) : bool
    {
        
        if ( isset( $this->menu_list_by_slug[$slug] ) === false ) {
            return false;
        }

        // This return an array as following: 'capability' => true
        // The key is the capability, all values of each capability is true
        $role_capabilities = (array) get_role( $role )->capabilities;

        if ( array_key_exists( $this->menu_list_by_slug[$slug]['capability'], $role_capabilities ) ) {
            return true;
        }

        return false;
    }


    // public function should_menu_visible_for_role( string $slug, string $role ) : bool
    // {
    //     $wp_admin_menu = $GLOBALS['menu'];

    //     // This return an array as following: 'capability' => true
    //     // The key is the capability, all values of each capability is true
    //     $role_capabilities = (array) get_role( $role )->capabilities;
        

    //     // TODO: using the menu in this class, but pay attention when this class is instatiated
    //     foreach ( $wp_admin_menu as $wp_admin_menu_item ) {
    //         /**
    //          *      $wp_admin_menu_item[0] = menu label
    //          *      $wp_admin_menu_item[1] = capability
    //          *      $wp_admin_menu_item[2] = menu slug
    //          */

    //          if ( $slug === $wp_admin_menu_item[2] ) {
    //              if ( array_key_exists( $wp_admin_menu_item[1], $role_capabilities ) ) {
    //                  return true;
    //              }
    //          }
    //     }

    //     return false;
    // }


    public function should_menu_visible_for_capability( string $slug, string $capability ) : bool
    {
        $wp_admin_menu = $GLOBALS['menu'];

        foreach ( $wp_admin_menu as $wp_admin_menu_item ) {
            /**
             *      $wp_admin_menu_item[0] = menu label
             *      $wp_admin_menu_item[1] = capability
             *      $wp_admin_menu_item[2] = menu slug
             */

             if ( $slug === $wp_admin_menu_item[2] ) {
                 if ( $capability === $wp_admin_menu_item[1] ) {
                     return true;
                 }
             }
        }

        return false;
    }


    public function roles() {

    }

   
}
