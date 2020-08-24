<?php
$mts_options = get_option(MTS_THEME_NAME);
if ( mts_isWooCommerce() ) {
	$product_grid_heading = isset( $mts_options['product_grid_heading'] ) ? $mts_options['product_grid_heading'] : '';
	$product_grid_num = isset( $mts_options['product_grid_num'] ) ? $mts_options['product_grid_num'] : 6;
	$args = array(
		'post_type'   => 'product',
		'post_status' => 'publish',
		'posts_per_page' => $product_grid_num
	);
	$args = apply_filters( 'mts_products_sortby' , $args , 'product_grid' );
?>
	<div class="featured-product-grid home-section woocommerce clearfix">
		<div class="container">
			<?php if ( !empty( $product_grid_heading ) ) { ?>
				<div class="featured-category-title"><?php echo $product_grid_heading; ?></div>
			<?php } ?>

			<?php

			$product_grid_query = new WP_Query( apply_filters( 'mts_home_product_grid_args', $args ) );

			if ( $product_grid_query->have_posts() ) {

				echo '<ul class="products products-grid">';

				while ( $product_grid_query->have_posts() ) {
					$product_grid_query->the_post();
					wc_get_template( 'content-product-grid.php' );
				}

				echo '</ul>';
			}

			wp_reset_postdata();
			?>
		</div>
	</div>
<?php } ?>
