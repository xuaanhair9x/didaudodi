<?php
$mts_options = get_option(MTS_THEME_NAME);
$latest_posts_heading = isset( $mts_options['latest_posts_heading'] ) ? $mts_options['latest_posts_heading'] : '';
?>
<div class="featured-section home-section clearfix featured-blog-posts">
	<div class="container">
		<header class="featured-section-header clearfix featured-blog-posts-header">
			<?php if ( !empty( $latest_posts_heading ) ) { ?><h3 class="featured-category-title"><?php echo $latest_posts_heading; ?></h3><?php } ?>
			<div class="readMore">
				<a href="<?php echo esc_url( apply_filters( 'ecommerce_blog_page_redirect', get_permalink( get_page_by_path( 'blog' ) ) ) ); ?>"><?php _e('View All', MTS_THEME_TEXTDOMAIN ); ?></a>
			</div>
		</header>
		<?php 
		$args = array(
			'post_type' => 'post',
			'post_status' => 'publish',
			'ignore_sticky_posts' => 1,
			'posts_per_page' => 4,
		);

		$latest_posts = new WP_Query( apply_filters( 'mts_home_latest_posts_args', $args ) );

		if ( $latest_posts->have_posts() ) :
			?>
		<div class="featured-blog-posts-container clearfix">
		<?php while ( $latest_posts->have_posts() ) : $latest_posts->the_post(); ?>
			<article class="featured-blog-post">
				<div class="featured-blog-post-inner">
					<a href="<?php echo esc_url( get_the_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>" class="featured-image">
						<?php the_post_thumbnail('related',array('title' => '')); ?>
						<span class="icon"><i class="fa fa-pencil"></i></span>
						<?php if (function_exists('wp_review_show_total')) wp_review_show_total(true, 'latestPost-review-wrapper'); ?>
					</a>
					<header class="featured-blog-post-header">
						<h4 class="title featured-blog-post-title">
							<a href="<?php echo esc_url( get_the_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>"><?php the_title(); ?></a>
						</h4>
					</header>
					<div class="post-excerpt"><?php echo mts_excerpt(15); ?></div>
					<div class="blog-post-info">
						<div class="thetime updated"><span><?php the_time( get_option( 'date_format' ) ); ?></span></div>
						<?php mts_readmore(); ?>
					</div>
				</div>
			</article>
		<?php endwhile; ?>
		</div>
		<?php endif; ?>
	</div>
</div>