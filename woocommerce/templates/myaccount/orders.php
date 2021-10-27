<?php
/**
 * Orders
 *
 * Shows orders on the account page.
 *
 * This template has been overriden, check the filter used in the Webigo_My_Account class
 *
 */

defined( 'ABSPATH' ) || exit;

//TODO: verify the pagination
?>

<div class="wbg-myaccount-order-content-header">
    <div class="wbg-myaccount-order-content-col1">
        <h2>Pedidos</h2>
        <p>Abaixo segue a lista de seus pedidos</p>
    </div>
</div>

<?php do_action( 'woocommerce_before_account_orders', $has_orders ); ?>

<?php if ( $has_orders ) : ?>


    <div class="wbg-customer-orders-container">
    <?php
		
		require_once WEBIGO_PLUGIN_PATH . 'modules/myaccount/views/class-webigo-view-myaccount-orders.php';
        $myaccount_orders = new Webigo_View_Myaccount_Orders( $customer_orders );
        $myaccount_orders->render();
    
    ?>
    </div>

	<?php do_action( 'woocommerce_before_account_orders_pagination' ); ?>

	<?php if ( 1 < $customer_orders->max_num_pages ) : ?>
		<div class="woocommerce-pagination woocommerce-pagination--without-numbers woocommerce-Pagination">
			<?php if ( 1 !== $current_page ) : ?>
				<a class="woocommerce-button woocommerce-button--previous woocommerce-Button woocommerce-Button--previous button" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page - 1 ) ); ?>"><?php esc_html_e( 'Previous', 'woocommerce' ); ?></a>
			<?php endif; ?>

			<?php if ( (int)$customer_orders->max_num_pages !== $current_page ) : ?>
				<a class="woocommerce-button woocommerce-button--next woocommerce-Button woocommerce-Button--next button" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page + 1 ) ); ?>"><?php esc_html_e( 'Next', 'woocommerce' ); ?></a>
			<?php endif; ?>
		</div>
	<?php endif; ?>

<?php else : ?>
	<div class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info">
		<a class="woocommerce-Button button" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>"><?php esc_html_e( 'Browse products', 'woocommerce' ); ?></a>
		<?php esc_html_e( 'No order has been made yet.', 'woocommerce' ); ?>
	</div>
<?php endif; ?>

<?php do_action( 'woocommerce_after_account_orders', $has_orders ); ?>
