<?php




class Webigo_View_Admin_Menu_List {


    private $role_selected = 'administrator';


    public function __construct( object $menu_handler )
    {
        $this->wpadmin_menu_handler = $menu_handler;
        $this->handle_role_change();
    }

    private function handle_role_change()
    {

        if ( isset( $_POST['system-role-selected'] ) ) {
            $this->role_selected = filter_var( trim( $_POST['system-role-selected'] ), FILTER_SANITIZE_STRING );
        }

    }

    private function set_default()
    {

    }


    /**
     * This function is triggered when the "admin_menu" hook is fired
     * 
     */
    public function add_menu() : void
    {
        add_submenu_page(
            'webigo',
            __('Admin Menu Handle'),
            __('Admin Menu Handle'),
            'manage_options',
            'webigo_hide_menu_setup',
            array( $this, 'render' )
        );
    }

    public function render()
    {
      ?>
        
            <div class="wbg-admin-menu-visibility-editor">
                <?php $this->role_selection() ?>
                <?php $this->render_edit_menu_visibility() ?>
            </div>
        

      <?php

    }

    private function render_edit_menu_visibility() : void
    {

                 /**
             *      $wp_admin_menu_item[0] = menu label
             *      $wp_admin_menu_item[1] = capability
             *      $wp_admin_menu_item[2] = menu slug
             */
            $wp_admin_menu_items = $GLOBALS['menu']
     
     ?>

            <form method="post" role="edit-menu-visibility">
                <div style="display: grid; grid-template-columns: 1fr 1fr; grid-template-rows: repeat(5, 1fr); grid-gap: 0.5rem;">
                    <?php 
                    foreach( $wp_admin_menu_items as $wp_admin_menu_item) {
                        $this->render_menu_item( $wp_admin_menu_item[0], $wp_admin_menu_item[2] );
                    }
                    ?>
                </div>
                <div>
                    <button type="submit" name="action" value="update-admin-menu-settings">Save</button>
                </div>
            </form>


<?php
    }


    private function render_menu_item( string $label, string $slug )
    {

        if ( substr( $slug, 0, strlen( 'separator' ) ) === 'separator' )  {
            return;
        }

        $should_visible = $this->wpadmin_menu_handler->should_menu_visible_for_role( $slug, $this->role_selected );

        ?>
            <div class="wbg-menu-item">
                <label for="<?php echo esc_attr( $slug ); ?>">
                    <input type="checkbox" name="<?php echo esc_attr( $slug ); ?>" id="<?php echo esc_attr( $slug ); ?>" <?php checked( $should_visible, true ); ?> >
                        <?php 
                            if ( $slug == 'edit-comments.php' ) {
                                echo 'Comentários';
                            } elseif ( $slug == 'plugins.php' ) {
                                echo 'Plugins';
                            } else {
                                echo esc_html( $label );
                            }
                        ?>
                </label>
                <?php if ( $should_visible === true ) : ?>
                    <span class="wbg-menu-item-default">(Default: enabled)</span>
                <?php endif; ?>
            </div>

<?php
    }


    private function role_selection()
    {
        ?>
            <form method="post" role="system-roles-selections">
                <div class="wbg-system-roles-selections">
                    <select name="system-role-selected">
                        <?php echo esc_html( wp_dropdown_roles( $this->role_selected ) ) ?>
                    </select>
                    <button type="submit" name="action" value="system-role-selection">Go</button>
                </div>
            </form>

<?php
    }
}
