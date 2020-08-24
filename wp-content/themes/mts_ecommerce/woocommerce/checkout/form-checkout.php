<?php
/**
 * Checkout Form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $woocommerce;
?>
<ul id="checkout-progress" class="clearfix">
	<li><a href="<?php echo esc_url( wc_get_cart_url() ) ?>"><span class="step-title"><?php _e( 'Shopping Cart', MTS_THEME_TEXTDOMAIN ); ?></span><span class="items"><?php echo $woocommerce->cart->cart_contents_count; ?></span></a><span class="icon"><i class="fa fa-angle-right"></i></span></li>
	<li class="active"><a href="#" class="disabled"><span class="step-title"><?php _e( 'Checkout Details', MTS_THEME_TEXTDOMAIN ); ?></span></a><span class="icon"><i class="fa fa-angle-right"></i></span></li>
	<li><a href="#" class="disabled"><span class="step-title"><?php _e( 'Order Complete', MTS_THEME_TEXTDOMAIN ); ?></span></a></li>
</ul>
<div class="checkout-container clearfix">
	<div class="checkout-content">
		<?php
		wc_print_notices();

		do_action( 'woocommerce_before_checkout_form', $checkout );

		// If checkout registration is disabled and not logged in, the user cannot checkout
		if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
			echo apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', MTS_THEME_TEXTDOMAIN ) );
			return;
		}
		?>

		<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

			<div class="checkout-left">

			<?php if ( $checkout->get_checkout_fields() ) : ?>

				<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

				
						<?php do_action( 'woocommerce_checkout_billing' ); ?>
					
						<?php do_action( 'woocommerce_checkout_shipping' ); ?>
					

				<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

			<?php endif; ?>

			</div>

			<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

			<div id="order_review" class="woocommerce-checkout-review-order">
				<h3 id="order_review_heading"><?php _e( 'Your order', MTS_THEME_TEXTDOMAIN ); ?></h3>
				<?php do_action( 'woocommerce_checkout_order_review' ); ?>
			</div>

			<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>

		</form>

		<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
	</div>
</div>
