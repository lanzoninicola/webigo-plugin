<?php



class Webigo_Woo_Minicart {


    /**
	 * This hide the bundled item in the cart
	 * 
	 * Do not remove unused parameters because used by Wordpress filter
	 */
	public function hide_bundled_items( $is_visible, $cart_item, $cart_item_key ) : bool
	{

		if ( isset( WC()->cart->cart_contents[ $cart_item_key ]['bundled_by'] ) ) {
			$bundle_cart_key = WC()->cart->cart_contents[ $cart_item_key ]['bundled_by'];
			if ( isset( WC()->cart->cart_contents[ $bundle_cart_key ] ) ) {
				return false;
			}
		}

		return $is_visible;
	}

	/**
	 * This replace the permalink of product in the cart with #.
     * Avoiding open the single product page from the minicart
	 * 
	 * Do not remove unused parameters because used by Wordpress filter
	 * 
	 */
	public function remove_product_link( $product, $cart_item, $cart_item_key ) : string
	{
		return '#';
	}


    /**
     * This replace the "X" with a BIN icon for each product
     */
	public function replace_icon_remove_link( $link, $cart_item_key ) {

		$remove_item_icon = '<svg width="16" height="16" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
		<path fill-rule="evenodd" clip-rule="evenodd" d="M4.50002 0.5H7.50002C8.0523 0.5 8.50002 0.947715 8.50002 1.5V1.99998H10C10.5523 1.99998 11 2.4477 11 2.99998V3.99998C11 4.55226 10.5523 4.99998 10 4.99998H9.95993L9.5 10.5C9.5 11.0523 9.05228 11.5 8.5 11.5H3.5C2.94771 11.5 2.5 11.0523 2.50173 10.5415L2.03993 4.99998H2C1.44772 4.99998 1 4.55226 1 3.99998V2.99998C1 2.4477 1.44772 1.99998 2 1.99998H3.50002V1.5C3.50002 0.947715 3.94774 0.5 4.50002 0.5ZM3.50002 3V2.99998H2V3.99998H10V2.99998H8.50002V3H3.50002ZM8.95662 4.99998H3.04326L3.50002 10.5H8.50002L8.50175 10.4584L8.95662 4.99998ZM7.50004 1.50001V2.00001H4.50004V1.50001H7.50004Z" fill="black"/>
		</svg>';

		$link = str_replace( '&times;' , $remove_item_icon , $link);

		return $link;
	}


}