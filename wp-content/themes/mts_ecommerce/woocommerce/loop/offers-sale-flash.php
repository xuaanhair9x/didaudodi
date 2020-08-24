<?php
/**
 * Product loop sale flash
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

	<?php
	
	if ( 'variable' === $product->get_type() ) {
        $percentage = ceil( ( ( $product->get_variation_regular_price() - $product->get_variation_sale_price() ) / $product->get_variation_regular_price() ) * 100 );
        
    } else {
        $percentage = ceil( ( ( $product->get_regular_price() - $product->get_sale_price() ) / $product->get_regular_price() ) * 100 );
    }
	?>

	<?php echo apply_filters( 'mts_offers_sale_flash', '<span class="onsale-badge">-' . $percentage . '%</span>', $post, $product ); ?>

<?php endif; ?>
</div>
