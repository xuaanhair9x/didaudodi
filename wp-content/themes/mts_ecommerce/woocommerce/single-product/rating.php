<?php
/**
 * Single Product Rating
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/rating.php.
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

global $post, $product;

if ( 'no' === get_option( 'woocommerce_enable_review_rating' ) ) {
	return;
}

$rating_count = $product->get_rating_count();
$review_count = $product->get_review_count();
$average      = $product->get_average_rating();

if ( $rating_count > 0 ) : ?>
	<?php
	$product_tags = get_the_terms( $post->ID, 'product_tag' );
	if( $product_tags && !is_wp_error( $product_tags ) )	{
		$tag_count = sizeof( $product_tags );
	} else {
		$tag_count = 0;
	}
	if ( version_compare( WC()->version, '3.0.0', ">=" ) ) { ?>
		<div class="woocommerce-product-rating">
			<?php echo wc_get_rating_html( $average, $rating_count ); ?>
			<?php if ( comments_open() ) : ?>
				<div class="review-count-wrap">
					<span class="review-count"><?php printf( _n( '%s Review', '%s Reviews', $review_count, MTS_THEME_TEXTDOMAIN ), '<span itemprop="ratingCount" class="count">' . $review_count . '</span>' ); ?></span><a href="#reviews" class="woocommerce-review-link"><?php _e('Add Your Review', MTS_THEME_TEXTDOMAIN)?></a>
				</div>
			<?php endif ?>

			<div class="product_meta">
				<?php echo wc_get_product_category_list( $product->get_id(), ', ', '<span class="tagged_as">' . _n( 'Tag:', 'Tags:', $tag_count, MTS_THEME_TEXTDOMAIN ) . ' ', '.</span>' ); ?>
			</div>
		</div>
	<?php } else { ?>
		<div class="woocommerce-product-rating" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
			<div class="star-rating" title="<?php printf( __( 'Rated %s out of 5', MTS_THEME_TEXTDOMAIN ), $average ); ?>">
				<span style="width:<?php echo ( ( $average / 5 ) * 100 ); ?>%">
					<strong itemprop="ratingValue" class="rating"><?php echo esc_html( $average ); ?></strong> <?php printf( __( 'out of %s5%s', MTS_THEME_TEXTDOMAIN ), '<span itemprop="bestRating">', '</span>' ); ?>
					<?php printf( _n( 'based on %s customer rating', 'based on %s customer ratings', $rating_count, MTS_THEME_TEXTDOMAIN ), '<span itemprop="ratingCount" class="rating">' . $rating_count . '</span>' ); ?>
				</span>
			</div>
			<?php if ( comments_open() ) : ?>
				<div class="review-count-wrap">
					<span class="review-count"><?php printf( _n( '%s Review', '%s Reviews', $review_count, MTS_THEME_TEXTDOMAIN ), '<span itemprop="ratingCount" class="count">' . $review_count . '</span>' ); ?></span><a href="#reviews" class="woocommerce-review-link"><?php _e('Add Your Review', MTS_THEME_TEXTDOMAIN)?></a>
				</div>
			<?php endif ?>

			<div class="product_meta">
				<?php echo $product->get_tags( ', ', '<span class="tagged_as">' . _n( 'Tag:', 'Tags:', $tag_count, MTS_THEME_TEXTDOMAIN ) . ' ', '.</span>' ); ?>
			</div>
		</div>
	<?php } ?>
<?php endif; ?>
