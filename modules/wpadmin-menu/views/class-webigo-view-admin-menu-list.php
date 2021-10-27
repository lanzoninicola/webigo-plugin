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

        $role_selected = filter_input( INPUT_POST, 'system-role-selected', FILTER_SANITIZE_STRING );

        if ( $role_selected !== null ) {
            $this->role_selected = $role_selected;
        }
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
        
            <div class="wbg-admin-menu-visibility-container">
                <div class="wbg-admin-menu-header">
                    <?php $this->role_selection() ?>
                    <?php $this->reset() ?>
                </div>
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
            $admin_menu_items = $this->wpadmin_menu_handler->get_menu_by( 'label' );
     ?>

            <form method="post" role="edit-menu-visibility">
                <div class="wbg-admin-menu-visibility-editor">
                    <?php 
                    foreach( array_keys( $admin_menu_items ) as $label ) {
                        $this->render_menu_item( $label, $admin_menu_items[$label]['slug'] );
                    }
                    ?>
                </div>
                <div class="wbg-admin-menu-visibility-editor-footer">
                    <button type="submit" name="edit-admin-menu-settings" value="false">Save</button>
                </div>
            </form>


<?php
    }


    private function render_menu_item( string $label, string $slug )
    {

        if ( substr( $slug, 0, strlen( 'separator' ) ) === 'separator' )  {
            return;
        }

        $should_default_visible = $this->wpadmin_menu_handler->should_default_visibile_for_role( $slug, $this->role_selected );
        $should_hide            = $this->wpadmin_menu_handler->should_menu_hidden_for_role( $slug, $this->role_selected );

        $checked = $should_hide !== null ? !$should_hide : $should_default_visible;

        ?>
            <div class="wbg-menu-item">
                <label for="<?php echo esc_attr( $slug ); ?>">
                    <input type="checkbox" name="<?php echo esc_attr( $label ); ?>" id="<?php echo esc_attr( $slug ); ?>" <?php checked( $checked ); ?> >
                        <?php 
                            if ( $slug == 'edit-comments.php' ) {
                                echo 'Comentários';
                            } elseif ( $slug == 'plugins.php' ) {
                                echo 'Plugins';
                            } else {
                                echo esc_html( ucfirst( $label ) );
                            }
                        ?>
                </label>
                <?php if ( $should_default_visible === true ) : ?>
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
                    <select name="system-role">
                        <?php echo esc_html( wp_dropdown_roles( $this->role_selected ) ) ?>
                    </select>
                    <button type="submit" name="system-role-selected" value="<?php echo esc_attr( $this->role_selected ) ?>">Go</button>
                </div>
            </form>

<?php
    }

    private function reset()
    {
        ?>
            <form method="post" role="reset-to-default">
                <div>
                    <button type="submit" name="wbg-menu-visibility-reset-role" value="true">Reset Role</button>
                    <button type="submit" name="wbg-menu-visibility-reset-all" value="true">Reset All</button>
                </div>
            </form>

<?php
    }
}
