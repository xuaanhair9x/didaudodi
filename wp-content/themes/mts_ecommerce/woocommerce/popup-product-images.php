<?php
/**
 * Popup Product Images
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post, $product;

if ( version_compare( WC()->version, '3.0.0', ">=" ) ) {
	$attachment_ids = $product->get_gallery_image_ids();
} else {
	$attachment_ids = $product->get_gallery_attachment_ids();
}
$attachment_count = count( $attachment_ids );
if ( $attachment_count > 0 ) {
	$slider_container_class = ' primary-slider-container loading';
	$slider_class           = ' product-img-slider';
} else {
	$slider_container_class = '';
	$slider_class           = '';
}
?>
<div class="images<?php echo $slider_container_class;?>">
	<div class="images-inner clearfix<?php echo $slider_class;?>">
		<?php
		if ( has_post_thumbnail() ) {

			$image_title = esc_attr( get_the_title( get_post_thumbnail_id() ) );
			$image_link  = wp_get_attachment_url( get_post_thumbnail_id() );
			$image       = get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ), array( 'title' => $image_title ) );

			echo $image;
		}

		//$attachment_ids = $product->get_gallery_attachment_ids();

		if ( $attachment_ids ) {

			foreach ( $attachment_ids as $attachment_id ) {

				$image_link = wp_get_attachment_url( $attachment_id );

				if ( ! $image_link )
					continue;

				$image       = wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ) );
				$image_class = '';//esc_attr( implode( ' ', $classes ) );
				$image_title = esc_attr( get_the_title( $attachment_id ) );

				echo $image;
			}
		}
		?>
	</div>

	<?php wc_get_template('single-product/sale-flash.php'); ?>
	<?php //do_action( 'woocommerce_product_thumbnails' ); ?>

</div>