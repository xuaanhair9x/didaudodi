<?php
/**
 * Single Product Sale Flash
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post, $product, $mts_options;

?>
<div class="mts-product-badges">
<?php if ( $product->is_on_sale() ) : ?>

	<?php echo apply_filters( 'woocommerce_sale_flash', '<span class="onsale-badge">' . __( 'Sale!', MTS_THEME_TEXTDOMAIN ) . '</span>', $post, $product ); ?>

<?php endif; ?>

<?php if ( $mts_options['mts_mark_new_products'] ) : ?>

	<?php
	$postdate      = get_the_time( 'Y-m-d' );
	$postdatestamp = strtotime( $postdate );
	$newness       = isset( $mts_options['mts_new_products_time'] ) ? $mts_options['mts_new_products_time'] : 30;

	// If the product was published within the newness time frame display the new badge
	if ( ( time() - ( 60 * 60 * 24 * $newness ) ) < $postdatestamp ) {

		echo apply_filters( 'mts_new_badge', '<span class="new-badge">' . __( 'New!', MTS_THEME_TEXTDOMAIN ) . '</span>', $post, $product );
    }
    ?>

<?php endif; ?>

<?php if ( !$product->is_in_stock() ) : ?>

	<?php echo apply_filters( 'mts_outstock_badge', '<span class="outstock-badge">' . __( 'Out Of Stock', MTS_THEME_TEXTDOMAIN ) . '</span>', $post, $product ); ?>

<?php endif; ?>
</div>