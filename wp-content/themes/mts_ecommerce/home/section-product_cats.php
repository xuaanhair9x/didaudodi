<?php
if ( mts_isWooCommerce() ) {
	$mts_options = get_option(MTS_THEME_NAME);

	$browse_heading = isset( $mts_options['browse_heading'] ) ? $mts_options['browse_heading'] : '';

	$args = apply_filters( 'mts_home_product_cats_args', array() );

	$product_categories = get_terms( 'product_cat', $args );
	?>
	<div class="browse-our-categories home-section clearfix">
		<div class="container">
		<?php if ( !empty( $browse_heading ) ) { ?>
			<div class="featured-category-title">
				<?php echo $browse_heading; ?>
				<div class="readMore">
					<a href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>"><?php _e('View All', MTS_THEME_TEXTDOMAIN ); ?></a>
				</div>
				<div class="custom-nav">
					<a class="btn browse-prev"><i class="fa fa-angle-left"></i></a>
					<a class="btn browse-next"><i class="fa fa-angle-right"></i></a>
				</div>
			</div>
		<?php } ?>
		<?php if ( $product_categories ) { ?>
			<div class="browse-category-container clearfix loading">
				<div id="product-categories-slider" class="browse-category-slider">
				<?php foreach ( $product_categories as $category ) {
					wc_get_template( 'content-product_cat.php', array(
						'category' => $category
					) );
				}?>
				</div>

			</div>
		<?php } ?>
		</div>
	</div>
<?php } ?>
