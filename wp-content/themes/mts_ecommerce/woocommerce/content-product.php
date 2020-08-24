<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
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
 * @version 3.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product, $mts_options;

// Ensure visibility
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}
// Extra post classes
$classes = array('excerpt');
?>
<li <?php wc_product_class( $classes , $product ); ?>>

	<?php $product_effect =  isset( $mts_options['mts_shop_hover_effect'] ) ? $mts_options['mts_shop_hover_effect'] : 'default'; ?>

	<?php do_action( 'woocommerce_before_shop_loop_item' ); ?>

	<?php wc_get_template_part( 'loop/product-hover-effect', $product_effect ); ?>

	<?php do_action( 'woocommerce_after_shop_loop_item' ); ?>

</li>
