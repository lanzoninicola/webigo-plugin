<?php



class Webigo_View_Myaccount_Registration_Form {



    public function render()
    {
        ?>

            <div class="u-column2 col-2" data-visibility="hidden">
                <?php $this->title(); ?>

                <form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action( 'woocommerce_register_form_tag' ); ?> >

                    <?php 
                    do_action( 'woocommerce_register_form_start' );
                    $this->username();
                    $this->email();
                    $this->password();
                    do_action( 'woocommerce_register_form' );
                    $this->register_button();
                    do_action( 'woocommerce_register_form_end' ); 
                    ?>

                </form>
            </div>

        <?php
    }

    private function title()
    {
        ?>
        <h2><?php esc_html_e( 'Register', 'woocommerce' ); ?></h2>

<?php
    }

    private function username()
    {
       
       if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <label for="reg_username"><?php esc_html_e( 'Username', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
                <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" autocomplete="username" placeholder="<?php esc_html_e( 'Username', 'woocommerce' ); ?>" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
            </p>

        <?php endif;

    }

    private function email()
    {
        ?>
            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <label for="reg_email"><?php esc_html_e( 'Email address', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
                <input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" autocomplete="email" placeholder="<?php esc_html_e( 'Email address', 'woocommerce' ); ?>" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
            </p>

<?php
    }

    private function password()
    {
        if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>

            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <label for="reg_password"><?php esc_html_e( 'Password', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
                <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password" placeholder="<?php esc_html_e( 'Password', 'woocommerce' ); ?>" />
            </p>

        <?php else : ?>

            <p><?php esc_html_e( 'A password will be sent to your email address.', 'woocommerce' ); ?></p>

        <?php endif;
    }

    private function register_button()
    {
        ?>

            <p class="woocommerce-form-row form-row">
                <?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
                <button type="submit" class="woocommerce-Button woocommerce-button button woocommerce-form-register__submit wbg-button wbg-primary-button" name="register" value="<?php esc_attr_e( 'Register', 'woocommerce' ); ?>"><?php esc_html_e( 'Register', 'woocommerce' ); ?></button>
            </p>
<?php
    }


}