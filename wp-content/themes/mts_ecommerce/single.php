<?php get_header(); ?>
<?php $mts_options = get_option(MTS_THEME_NAME); ?>
<div id="page" class="<?php mts_single_page_class(); ?> blog-page">

	<?php $header_animation = mts_get_post_header_effect(); ?>
	<?php if ( 'parallax' === $header_animation ) {?>
		<?php if (mts_get_thumbnail_url()) : ?>
	        <div id="parallax" <?php echo 'style="background-image: url('.mts_get_thumbnail_url().');"'; ?>></div>
	    <?php endif; ?>
	<?php } else if ( 'zoomout' === $header_animation ) {?>
		 <?php if (mts_get_thumbnail_url()) : ?>
	        <div id="zoom-out-effect"><div id="zoom-out-bg" <?php echo 'style="background-image: url('.mts_get_thumbnail_url().');"'; ?>></div></div>
	    <?php endif; ?>
	<?php } ?>

	<article class="<?php mts_article_class(); ?>">
		<div id="content_box">
			<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
				<div class="single_post">
					<div id="post-<?php the_ID(); ?>" <?php post_class('g post'); ?>>
						<?php
						// Single post parts ordering
						if ( isset( $mts_options['mts_single_post_layout'] ) && is_array( $mts_options['mts_single_post_layout'] ) && array_key_exists( 'enabled', $mts_options['mts_single_post_layout'] ) ) {
							$single_post_parts = $mts_options['mts_single_post_layout']['enabled'];
						} else {
							$single_post_parts = array( 'content' => 'content', 'related' => 'related', 'author' => 'author' );
						}
						foreach( $single_post_parts as $part => $label ) {
							switch ($part) {
								case 'content':
									?>
									<div class="thecontent">
										<header>
											<h1 class="title single-title entry-title"><?php the_title(); ?></h1>
											<?php mts_the_postinfo( 'single' ); ?>
										</header><!--.headline_area-->
										<div class="post-single-content box mark-links entry-content">
											<?php if ($mts_options['mts_posttop_adcode'] != '') { ?>
												<?php $toptime = $mts_options['mts_posttop_adcode_time']; if (strcmp( date("Y-m-d", strtotime( "-$toptime day")), get_the_time("Y-m-d") ) >= 0) { ?>
													<div class="topad">
														<?php echo do_shortcode($mts_options['mts_posttop_adcode']); ?>
													</div>
												<?php } ?>
											<?php } ?>
											<?php if (isset($mts_options['mts_social_button_position']) && $mts_options['mts_social_button_position'] == 'top') mts_social_buttons(); ?>
												<?php the_content(); ?>
											<?php wp_link_pages(array('before' => '<div class="pagination">', 'after' => '</div>', 'link_before'  => '<span class="current"><span class="currenttext">', 'link_after' => '</span></span>', 'next_or_number' => 'next_and_number', 'nextpagelink' => __('Next', MTS_THEME_TEXTDOMAIN ), 'previouspagelink' => __('Previous', MTS_THEME_TEXTDOMAIN ), 'pagelink' => '%','echo' => 1 )); ?>
											<?php if ($mts_options['mts_postend_adcode'] != '') { ?>
												<?php $endtime = $mts_options['mts_postend_adcode_time']; if (strcmp( date("Y-m-d", strtotime( "-$endtime day")), get_the_time("Y-m-d") ) >= 0) { ?>
													<div class="bottomad">
														<?php echo do_shortcode($mts_options['mts_postend_adcode']); ?>
													</div>
												<?php } ?>
											<?php } ?>
											<?php if (isset($mts_options['mts_social_button_position']) && $mts_options['mts_social_button_position'] !== 'top') mts_social_buttons(); ?>
										</div><!--.post-single-content-->
									</div><!--.thecontent-->
									<?php
								break;

								case 'tags':
									?>
									<?php mts_the_tags('<span class="tagtext">'.__('Tags', MTS_THEME_TEXTDOMAIN ).':</span>',', ') ?>
									<?php
								break;

								case 'author':
									?>
									<div class="postauthor">
										<h4><?php _e('The Author', MTS_THEME_TEXTDOMAIN ); ?></h4>
										<?php if(function_exists('get_avatar')) { echo get_avatar( get_the_author_meta('email'), '142' );  } ?>
										<h5 class="vcard"><a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" class="fn"><?php the_author_meta( 'nickname' ); ?></a></h5>
										<?php
											$userID = get_the_author_meta( 'ID' );
											$facebook = get_the_author_meta( 'facebook', $userID );
											$twitter = get_the_author_meta( 'twitter', $userID );
											$behance = get_the_author_meta( 'behance', $userID );
											$pinterest = get_the_author_meta( 'pinterest', $userID );
											$stumbleupon = get_the_author_meta( 'stumbleupon', $userID );
											$linkedin = get_the_author_meta( 'linkedin', $userID );
											$author_url = get_the_author_meta( 'url', $userID );

											if(!empty($facebook) || !empty($twitter) || !empty($behance) || !empty($pinterest) || !empty($stumbleupon) || !empty($linkedin) || !empty($author_url)){
												echo '<div class="author-social">';
													if(!empty($author_url)){
														echo '<a href="'.esc_url($author_url).'" class="social-link"><i class="fa fa-link"></i></a>';
													}
													if(!empty($facebook)){
														echo '<a href="'.esc_url($facebook).'" class="social-facebook"><i class="fa fa-facebook"></i></a>';
													}
													if(!empty($twitter)){
														echo '<a href="'.esc_url($twitter).'" class="social-twitter"><i class="fa fa-twitter"></i></a>';
													}
													if(!empty($behance)){
														echo '<a href="'.esc_url($behance).'" class="social-behance"><i class="fa fa-behance"></i></a>';
													}
													if(!empty($pinterest)){
														echo '<a href="'.esc_url($pinterest).'" class="social-pinterest"><i class="fa fa-pinterest"></i></a>';
													}
													if(!empty($stumbleupon)){
														echo '<a href="'.esc_url($stumbleupon).'" class="social-stumbleupon"><i class="fa fa-stumbleupon"></i></a>';
													}
													if(!empty($linkedin)){
														echo '<a href="'.esc_url($linkedin).'" class="social-linkedin"><i class="fa fa-linkedin"></i></a>';
													}
												echo '</div>';
											}
										?>
										<p><?php the_author_meta('description') ?></p>
										<div class="readMore">
											<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" title=""><?php _e('More Articles By', MTS_THEME_TEXTDOMAIN ); echo ' '; the_author_meta( 'nickname' ); ?></a>
										</div>
									</div>
									<?php
								break;
							}
						}
						?>
					</div><!--.g post-->
					<?php comments_template( '', true ); ?>
				</div><!--.single_post-->
			<?php endwhile; /* end loop */ ?>
		</div><!--#content_box-->
	</article>
	<?php get_sidebar(); ?>
<?php get_footer(); ?>
