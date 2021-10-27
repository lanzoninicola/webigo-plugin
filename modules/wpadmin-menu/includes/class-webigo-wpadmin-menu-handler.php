<?php


class Webigo_Wpadmin_Menu_Handler
{
    
    private $menu_visibility = array();

    public function __construct()
    {
        $this->init_module_options();
    }

    private function init_module_options()
    {
        $visibility_edited_option = get_option('webigo_admin_menu_visibility');

        if ( is_array( $visibility_edited_option ) === true && empty( $visibility_edited_option) === false ) {
            return;
        }

        add_option( 'webigo_admin_menu_visibility', array() );
    }

    public function build_menu_schema()
    {
        
        // start hiding menu items process
        global $menu;

        $this->menu_list = array();

        // build a menu schema
        foreach ($menu as $wp_admin_menu_item) {
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

    }


    public function handle_menu_items_visibility_changed()
    {
        
        $admin_menu_edited = filter_input( INPUT_POST, 'edit-admin-menu-settings' );

        if ( $admin_menu_edited !== null ) {
            $menu_visibility_json_decoded = json_decode( $admin_menu_edited, true );

            // Administrator menu cannot be changed
            if ( isset( $menu_visibility_json_decoded['administrator'] ) ) {
                unset( $menu_visibility_json_decoded['administrator'] );
            }

            if ( $menu_visibility_json_decoded !== false ) {
                $this->menu_visibility = $menu_visibility_json_decoded;

                $this->update_visibility_settings();
            }
        }
    }

    
    private function update_visibility_settings() : void
    {

        $visibility_edited_option = get_option('webigo_admin_menu_visibility');
        $new_visibility_edited_option = array();

        if ( is_array( $visibility_edited_option ) === true && empty( $visibility_edited_option) === false ) {
            $new_visibility_edited_option = $visibility_edited_option;
            
            foreach ( array_keys( $this->menu_visibility ) as $role ) {
                foreach ( $this->menu_visibility[$role] as $menu_item => $new_value ) {
                    $new_visibility_edited_option[$role][$menu_item] = $new_value; 
                }
            }

            update_option( 'webigo_admin_menu_visibility', $new_visibility_edited_option );
        }
    }


    public function hide_menus()
    {

        $current_user = new Webigo_User();

        if ( is_admin() === false || $current_user->is_valid() === false ) {
            return;
        }

        // hide the menu
        foreach ( array_keys( $this->menu_list_by_slug ) as $slug ) {
           
            $should_hidden = $this->should_menu_hidden_for_role( $slug , $current_user->role() );

            if ( $should_hidden === true ) {
                remove_menu_page( $slug );
            }
        }
    }





    public function should_default_visibile_for_role( string $slug, string $role ) : bool
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

    /**
     * 
     * Returns null if the user has not changed the default visibility
     * 
     * @return bool|null 
     */
    public function should_menu_hidden_for_role( string $slug, string $role )
    {
        
        $visibility_edited_option = get_option( 'webigo_admin_menu_visibility' );
        

        if ( is_array( $visibility_edited_option ) === true && empty( $visibility_edited_option) === false ) {

            if ( isset( $visibility_edited_option[$role][$slug] ) ) {
                return $visibility_edited_option[$role][$slug] === false ? true : false;
            }
        }

        return null;
    }

    public function should_menu_visible_for_capability( string $slug, string $capability ) : bool
    {
        global $menu;

        foreach ( $menu as $wp_admin_menu_item ) {
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


    /**
     * @param string $attribute label|slug|capability
     * @return array 
     */
    public function get_menu_by( string $attribute = 'label' ) : array
    {

        if ( $attribute === 'label' ) {
            return $this->menu_list_by_label;
        }

        if ( $attribute === 'slug' ) {
            return $this->menu_list_by_slug;
        }

        if ( $attribute === 'capability' ) {
            return $this->menu_list_by_capabilities;
        }

        return [];
        
    }

   
}
