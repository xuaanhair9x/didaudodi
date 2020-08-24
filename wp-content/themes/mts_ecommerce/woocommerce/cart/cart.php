<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.8.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $woocommerce;
?>
<ul id="checkout-progress" class="clearfix">
	<li class="active"><a href="#" class="disabled"><span class="step-title"><?php esc_html_e( 'Shopping Cart', MTS_THEME_TEXTDOMAIN ); ?></span><span class="items"><?php echo $woocommerce->cart->cart_contents_count; ?></span></a><span class="icon"><i class="fa fa-angle-right"></i></span></li>
	<li><a href="<?php echo esc_url( wc_get_checkout_url() ); ?>"><span class="step-title"><?php esc_html_e( 'Checkout Details', MTS_THEME_TEXTDOMAIN ); ?></span></a><span class="icon"><i class="fa fa-angle-right"></i></span></li>
	<li><a href="#" class="disabled"><span class="step-title"><?php _e( 'Order Complete', MTS_THEME_TEXTDOMAIN ); ?></span></a></li>
</ul>
<?php
$mts_options = get_option( MTS_THEME_NAME );
wc_print_notices();

do_action( 'woocommerce_before_cart' ); ?>

<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">

	<div class="c-8-12">

		<?php do_action( 'woocommerce_before_cart_table' ); ?>

		<table class="shop_table cart woocommerce-cart-form__contents" cellspacing="0">
			<tbody>
				<?php do_action( 'woocommerce_before_cart_contents' ); ?>

				<?php
				foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
					$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
					$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

					if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
						$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
						?>
						<tr class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

							<td class="product-remove">
								<?php
								echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									'woocommerce_cart_item_remove_link',
									sprintf(
										'<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
										esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
										esc_html__( 'Remove this item', MTS_THEME_TEXTDOMAIN ),
										esc_attr( $product_id ),
										esc_attr( $_product->get_sku() )
									),
									$cart_item_key
								);
								?>
							</td>

							<td class="product-thumbnail">
								<?php
								$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

								if ( ! $product_permalink ) {
									echo $thumbnail; // PHPCS: XSS ok.
								} else {
									printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // PHPCS: XSS ok.
								}
								?>
							</td>

							<td class="product-data">
								<?php
								echo '<div class="product-name">';
								$name = version_compare( WC()->version, '3.0.0', '>=' ) ? $_product->get_name() : $_product->get_title();
								if ( ! $product_permalink ) {
									echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $name, $cart_item, $cart_item_key ) . '&nbsp;' );
								} else {
									echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', $_product->get_permalink( $cart_item ), $name ), $cart_item, $cart_item_key ) );
								}
								echo '</div>';

								do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

								// Meta data.
								echo wc_get_formatted_cart_item_data( $cart_item ); // PHPCS: XSS ok.

								// Backorder notification.
								if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
									echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', MTS_THEME_TEXTDOMAIN ) . '</p>', $product_id ) );
								}

								echo sprintf( '<dl><dt>%s</dt><dd>%s</dd></dl>', esc_html__( 'Unit Price:', MTS_THEME_TEXTDOMAIN ), apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ) );
								?>
							</td>

							<td class="product-quantity">
								<?php
								echo sprintf( '<span class="mts-cart-label clearfix">%s</span>', esc_html__( 'Quantity:', MTS_THEME_TEXTDOMAIN ) );
								if ( $_product->is_sold_individually() ) {
									$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
								} else {
									$product_quantity = woocommerce_quantity_input(
										array(
											'input_name'   => "cart[{$cart_item_key}][qty]",
											'input_value'  => $cart_item['quantity'],
											'max_value'    => $_product->get_max_purchase_quantity(),
											'min_value'    => '0',
											'product_name' => $_product->get_name(),
										),
										$_product,
										false
									);
								}

								echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // PHPCS: XSS ok.
								?>
							</td>

							<td class="product-subtotal">
								<?php
								echo sprintf( '<span class="mts-cart-label clearfix">%s</span>%s', esc_html__( 'Subtotal:', MTS_THEME_TEXTDOMAIN ), apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ) ); // PHPCS: XSS ok.
								?>
							</td>
						</tr>
						<?php
					}
				}

				do_action( 'woocommerce_cart_contents' );
				?>

				<?php do_action( 'woocommerce_after_cart_contents' ); ?>
			</tbody>
		</table>

		<?php do_action( 'woocommerce_after_cart_table' ); ?>

	</div>

	<div class="c-4-12">
		<div class="cart-actions clearfix">
			<?php if ( WC()->cart->coupons_enabled() ) { ?>
				<div class="coupon">

					<input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php _e( 'Enter Coupon code', MTS_THEME_TEXTDOMAIN ); ?>" /> <input type="submit" class="button" name="apply_coupon" value="<?php _e( 'OK', MTS_THEME_TEXTDOMAIN ); ?>" />

					<?php do_action('woocommerce_cart_coupon'); ?>

				</div>
			<?php } ?>

			<?php woocommerce_cart_totals(); ?>

			<input type="submit" class="button update-cart-button" name="update_cart" value="<?php _e( 'Update Cart', MTS_THEME_TEXTDOMAIN ); ?>" />

			<a href="<?php echo esc_url( wc_get_checkout_url() ) ;?>" class="checkout-button button">
				<?php _e( 'Proceed to Checkout', MTS_THEME_TEXTDOMAIN ); ?>
			</a>

			<?php //do_action( 'woocommerce_proceed_to_checkout' ); ?>
			<?php //do_action( 'woocommerce_cart_actions' ); ?>

			<?php wp_nonce_field( 'woocommerce-cart' ); ?>
		</div>

	</div>

</form>

<div class="cart-collaterals c-8-12">
	<?php do_action( 'woocommerce_cart_collaterals' ); ?>
</div>

<?php do_action( 'woocommerce_after_cart' ); ?>
