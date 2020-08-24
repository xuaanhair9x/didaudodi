<?php
/**
 * The template for displaying product content within loops.
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product;

// Ensure visibility
if ( ! $product || ! $product->is_visible() )
	return;

// Extra post classes
$classes = array('product-offers', 'product');
?>
<li <?php post_class( $classes ); ?>>
	<div class="product-wrap">
		<?php do_action( 'woocommerce_before_shop_loop_item' ); ?>

		<a href="<?php echo esc_url( get_the_permalink() ); ?>">
		<?php
			mts_show_product_loop_offer_sale_flash();
			echo woocommerce_get_product_thumbnail('offers');
		?>
		</a>

		<div class="product-title"><a href="<?php echo esc_url( get_the_permalink() ); ?>"><?php the_title(); ?></a></div>

		<?php
			/**
			 * woocommerce_after_shop_loop_item_title hook
			 *
			 * @hooked woocommerce_template_loop_rating - 5
			 * @hooked woocommerce_template_loop_price - 10
			 */
			do_action( 'woocommerce_after_shop_loop_item_title' );
		?>
	</div>
</li>