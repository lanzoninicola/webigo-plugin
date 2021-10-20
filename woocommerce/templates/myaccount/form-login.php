<?php
/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$view_myaccount_form_login = new Webigo_View_Myaccount_Template_Form_Login();

?>

<?php
$view_myaccount_form_login->home();

do_action( 'woocommerce_before_customer_login_form' );

if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : ?>

<div class="u-columns col2-set" id="customer_login">

<?php endif;
$view_myaccount_form_login->login_form();

if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : 
	$view_myaccount_form_login->registration_form() 
	
?>
</div>
<?php endif;

do_action( 'woocommerce_after_customer_login_form' );

?>
