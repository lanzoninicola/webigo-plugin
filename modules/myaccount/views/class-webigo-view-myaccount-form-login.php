<?php



class Webigo_View_Myaccount_Form_Login {



    public function render()
    {
        ?>

        <div class="u-column1 col-1" data-visibility="hidden">
            <h2><?php esc_html_e( 'Login', 'woocommerce' ); ?></h2>

            <form class="woocommerce-form woocommerce-form-login login" method="post">

                <?php do_action( 'woocommerce_login_form_start' ); ?>

                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                    <label for="username"><?php esc_html_e( 'Username or email address', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
                    <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" autocomplete="username" placeholder="<?php esc_html_e( 'Username or email address', 'woocommerce' ); ?>" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
                </p>

                <?php $this->password_field() ?>
                

                <?php do_action( 'woocommerce_login_form' ); ?>

                <p class="form-row">
                    <?php 
                    $this->rememberme_flag();
                    wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' );
                    $this->login_button(); ?>
                </p>
                <?php $this->lost_password(); ?>
                <?php do_action( 'woocommerce_login_form_end' ); ?>

            </form>
        </div>

        <?php
    }

    private function rememberme_flag()
    {
        
            if ( Webigo_MyAccount_Settings::HIDE_REMEMBERME_FLAG === false ) : ?>
                <label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
                    <input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" checked="true" /> <span><?php esc_html_e( 'Remember me', 'woocommerce' ); ?></span>
                </label>
            <?php endif;
    }

    private function lost_password()
    {
        ?>
            <p class="woocommerce-LostPassword lost_password">
                <a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Lost your password?', 'woocommerce' ); ?></a>
            </p>
<?php
    }

    private function login_button()
    {
        ?>
        <button type="submit" class="woocommerce-button button woocommerce-form-login__submit wbg-button wbg-primary-button" name="login" value="<?php esc_attr_e( 'Log in', 'woocommerce' ); ?>"><?php esc_html_e( 'Log in', 'woocommerce' ); ?></button>

        <?php
    }

    private function password_field()
    {
        ?>

            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <label for="password"><?php esc_html_e( 'Password', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
                <input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password" autocomplete="current-password" placeholder="<?php esc_html_e( 'Password', 'woocommerce' ); ?>" />
            </p>

<?php
    }

}