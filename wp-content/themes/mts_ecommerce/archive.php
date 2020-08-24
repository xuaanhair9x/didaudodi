<?php $mts_options = get_option(MTS_THEME_NAME); ?>
<?php get_header(); ?>
<div id="page" class="blog-page">
	<div class="<?php mts_article_class(); ?>">
		<div id="content_box">
			<h1 class="postsby">
				<span><?php the_archive_title(); ?></span>
			</h1>
			<p><?php the_archive_description('<div class="mts-archive-description">', '</div>'); ?></p>
			<?php $j = 0; if (have_posts()) : while (have_posts()) : the_post(); ?>
				<?php mts_archive_post(); ?>
			<?php endwhile; endif; ?>

			<?php //if ( $j !== 0 ) { // No pagination if there is no posts ?>
				<?php mts_pagination(); ?>
			<?php //} ?>
		</div>
	</div>
	<?php get_sidebar(); ?>
<?php get_footer(); ?>
