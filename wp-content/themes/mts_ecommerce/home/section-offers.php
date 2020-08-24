<?php
if ( mts_isWooCommerce() ) {
	$mts_options = get_option(MTS_THEME_NAME);
	$offers_heading = isset( $mts_options['offers_heading'] ) ? $mts_options['offers_heading'] : '';
if ( is_active_sidebar( 'homepage-offers' ) ) {
	$class = "";
	$mts_post_count = 4;
} else {
	$class = "full";
	$mts_post_count = 6;
}
?>
	<div class="limited-offers-with-sidebar home-section clearfix">
		<div class="container">
			<div class="limited-offers <?php echo isset($class) ? $class : "" ?>">
				<?php if ( !empty( $offers_heading ) ) { ?><div class="featured-category-title"><?php echo $offers_heading; ?></div><?php } ?>
				<!--<div class="readMore">
					<a href="#" title="" rel="nofollow">View All</a>
				</div> -->
				<?php
				$args = array(
					'post_type' => 'product',
					'post_status' => 'publish',
					'ignore_sticky_posts' => 1,
					'no_found_rows' => 1,
					'posts_per_page' => $mts_post_count,
					'meta_query'     => WC()->query->get_meta_query(),
					'tax_query'      => WC()->query->get_tax_query(),
					'post__in'       => array_merge( array( 0 ), wc_get_product_ids_on_sale() ),
				);
				$args = apply_filters( 'mts_products_sortby' , $args , 'offers' );

				$offer_products = new WP_Query( apply_filters( 'mts_on_sale_products_query', $args ) );
				if ( $offer_products->have_posts() ) :
				?>
				<ul class="products">
					<?php while ( $offer_products->have_posts() ) : $offer_products->the_post(); ?>
						<?php wc_get_template_part( 'content', 'product-offer' ); ?>
					<?php endwhile; // end of the loop. ?>
				</ul>
				<?php endif; ?>
				<?php wp_reset_postdata(); ?>
			</div>

			<?php if ( is_active_sidebar( 'homepage-offers' ) ) { ?>
				<aside class="sidebar c-4-12">
					<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar( 'homepage-offers' ) ) : ?><?php endif; ?>
				</aside><!-- #sidebar-->
			<?php } ?>
		</div>
	</div>
<?php } ?>
