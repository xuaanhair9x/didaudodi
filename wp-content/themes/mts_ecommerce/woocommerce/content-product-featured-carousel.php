<?php
/**
 * The custom template for displaying product content within carousel loops.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product, $mts_options;

// Ensure visibility
if ( ! $product || ! $product->is_visible() )
	return;

// Extra post classes
$classes = array('featured-products-slider');

if ( is_home() || isset( $_POST['home_tab'] ) ) {

	$tag = 'li';
	$product_effect =  isset( $mts_options['mts_tabs_hover_effect'] ) ? $mts_options['mts_tabs_hover_effect'] : 'featured';

} else {

	$tag = 'div';
	$product_effect =  isset( $mts_options['mts_featured_hover_effect'] ) ? $mts_options['mts_featured_hover_effect'] : 'featured';
}

?>
<<?php echo $tag; ?> <?php post_class( $classes ); ?>>

	<?php do_action( 'woocommerce_before_shop_loop_item' ); ?>

	<?php wc_get_template_part( 'loop/product-hover-effect', $product_effect ); ?>

	<?php do_action( 'woocommerce_after_shop_loop_item' ); ?>

</<?php echo $tag; ?>>