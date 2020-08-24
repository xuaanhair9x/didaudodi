<?php
/**
 * The template for displaying product content within loops.
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product-grid.php
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product, $mts_options;

// Ensure visibility
if ( ! $product || ! $product->is_visible() )
	return;

$classes = array('product-grid-item', 'product');
$product_grid_img_num = isset( $mts_options['product_grid_img_num'] ) ? (int) $mts_options['product_grid_img_num'] : 3;
$product_id =  $product->get_id();
?>
<li <?php post_class( $classes ); ?>>
	<?php do_action( 'woocommerce_before_shop_loop_item' ); ?>
	<div class="product-wrap product-grid-item-wrap product-preview-slider-wrap">
		<?php $first_price = '';
		$products = new WC_Product_Variable( $product_id ); ?>
		<?php if ( $product->has_child() ) { // variable product ?>
			<?php
			$available_variations = $products->get_available_variations();
			?>
			<div class="product-preview-slider-container">
				<div class="product-preview-slider">
					<?php
					$count = 0;
					foreach ( $available_variations as $variation ) {

						$image_id = get_post_thumbnail_id( $variation['variation_id'] );

						if ( $image_id && $variation['variation_is_visible']===true && $variation['is_purchasable']===true ) {
							if ( $count == $product_grid_img_num ) break;
							++$count;
							if ( empty( $first_price ) ) $first_price = $variation['price_html'];

							$variation_image     = wp_get_attachment_image_src( $image_id, 'variableslider' );
							$variation_image_src = $variation_image[0];

							echo '<div class="img-wrap is-variable">';
								echo '<a href="' . get_the_permalink( $product->get_id() ) . '">';
								echo '<img src="'.esc_url( $variation_image_src ).'" class="variation-image">';
								echo '</a>';
								if ( ! empty( $variation['price_html'] ) )echo '<span class="variation-price-html">'.$variation['price_html'].'</span>';

								if ( ! empty( $variation['attributes'] ) && is_array( $variation['attributes'] ) ) {
									echo '<div class="variation-data">';
									foreach ( $variation['attributes'] as $name => $value ) {

										if ( '' === $value )
											continue;

										$taxonomy = wc_attribute_taxonomy_name( str_replace( 'attribute_pa_', '', urldecode( $name ) ) );

										// If this is a term slug, get the term's nice name
										if ( taxonomy_exists( $taxonomy ) ) {
											$term = get_term_by( 'slug', $value, $taxonomy );
											if ( ! is_wp_error( $term ) && $term && $term->name ) {
												$value = $term->name;
											}
											$label = wc_attribute_label( $taxonomy );

										// If this is a custom option slug, get the options name
										} else {
											$value              = apply_filters( 'woocommerce_variation_option_name', $value );
											$product_attributes = $product->get_attributes();
											if ( isset( $product_attributes[ str_replace( 'attribute_', '', $name ) ] ) ) {
												$label = wc_attribute_label( $product_attributes[ str_replace( 'attribute_', '', $name ) ]['name'] );
											} else {
												$label = $name;
											}
										}

										echo '<div class="variation-attribute">'.wp_kses_post( $label ).': '.wp_kses_post( $value ).'</div>';
									}
								}
								echo '</div>';
							echo '</div>';

						}// else {

							//echo '<div class="img-wrap">';
								//echo woocommerce_get_product_thumbnail('variableslider');
							//echo '</div>';
						//}
					}
					?>
				</div>
			</div>
		<?php } else { // Non variable product - use gallery images
			if ( version_compare( WC()->version, '3.0.0', ">=" ) ) {
				$attachment_ids = $product->get_gallery_image_ids();
			} else {
				$attachment_ids = $product->get_gallery_attachment_ids();
			}
			$count = 0;
			if ( $attachment_ids ) {

				echo '<div class="product-preview-slider-container"><div class="product-preview-slider">';

					echo '<div class="img-wrap">';
						echo '<a href="' . get_the_permalink( $product->get_id() ) . '">';
						echo woocommerce_get_product_thumbnail('variableslider');
						echo '</a>';
					echo '</div>';

				foreach ( $attachment_ids as $attachment_id ) {
					++$count;
					if ( $count == $product_grid_img_num ) break;
					echo '<div class="img-wrap"><a href="' . get_the_permalink( $product->get_id() ) . '">' . wp_get_attachment_image( $attachment_id, 'variableslider' ) . '</a></div>';
				}

				echo '</div></div>';

			} else {
				echo '<div class="img-wrap no-slider">';
					echo '<a href="' . get_the_permalink( $product->get_id() ) . '">';
					echo woocommerce_get_product_thumbnail('variableslider');
					echo '</a>';
				echo '</div>';
			}
		} ?>
		<div class="product-data">
			<div class="product-title"><a href="<?php echo esc_url( get_the_permalink() ); ?>"><?php the_title(); ?></a></div>
			<?php
			//woocommerce_template_loop_rating();
			if ( $product->has_child() && !empty( $first_price ) ) { // variable product
				echo $first_price;
			} else {
				woocommerce_template_loop_price();
			}
			?>
		</div>
	</div>
</li>
