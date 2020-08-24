<?php
/**
 * Aside Post Format.
 *
 * @package Bosco
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php if ( '' != get_the_title() ) : // Avoid printing empty markup if there's no title ?>
	<header class="entry-header">
		<?php if ( is_single() ) : ?>
			<h1 class="entry-title"><?php the_title(); ?></h1>
		<?php else : ?>
			<h1 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
		<?php endif; ?>
	</header><!-- .entry-header -->
	<?php endif; ?>

	<div class="entry-content">
		<?php
			/* translators: %s: Name of current post */
			the_content( sprintf(
				__( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'bosco' ), 
				the_title( '<span class="screen-reader-text">"', '"</span>', false )
			) );

			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'bosco' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-meta">
		<?php bosco_post_meta(); ?>
		<?php edit_post_link( __( 'Edit', 'bosco' ), '<span class="edit-link">', '</span>' ); ?>

		<?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
		<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'bosco' ), __( '1 Comment', 'bosco' ), __( '% Comments', 'bosco' ) ); ?></span>
		<?php endif; ?>

		<?php if ( is_single() ) : ?>
			<?php bosco_categories_tags(); ?>
		<?php endif; ?>
	</footer><!-- .entry-meta -->

</article><!-- #post-## -->
