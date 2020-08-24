<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Bosco
 */

if ( ! function_exists( 'bosco_paging_nav' ) ) :
/**
 * Display navigation to next/previous set of posts when applicable.
 */
function bosco_paging_nav() {
	// Don't print empty markup if there's only one page.
	if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
		return;
	}
	?>
	<nav class="navigation paging-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Posts navigation', 'bosco' ); ?></h1>
		<div class="nav-links">

			<?php if ( get_next_posts_link() ) : ?>
			<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'bosco' ) ); ?></div>
			<?php endif; ?>

			<?php if ( get_previous_posts_link() ) : ?>
			<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'bosco' ) ); ?></div>
			<?php endif; ?>

		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'bosco_post_nav' ) ) :
/**
 * Display navigation to next/previous post when applicable.
 *
 * @return void
 */
function bosco_post_nav() {
	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous ) {
		return;
	}
	?>
	<nav class="navigation post-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Post navigation', 'bosco' ); ?></h1>
		<div class="nav-links">

			<?php previous_post_link( '<div class="nav-previous">%link</div>', '<div class="arrow">' . _x( '&larr;', 'Previous post link', 'bosco' ) . '</div><div class="link">%title</div>' ); ?>
			<?php next_post_link( '<div class="nav-next">%link</div>',  '<div class="arrow">' . _x( '&rarr;', 'Next post link', 'bosco' ) . '</div><div class="link">%title</div>' ); ?>

		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'bosco_posted_on' ) ) :
/**
 * Prints HTML with the post format, date/time, author and permalink for the current post.
 */
function bosco_posted_on() {

	/* Post Format */
	$format = get_post_format();
	if ( $format ) {
		printf( '<span class="post-format"><a class="entry-format" href="%1$s">%2$s</a></span>',
			esc_url( get_post_format_link( $format ) ),
			get_post_format_string( $format )
		);
	}

	/* Date */
	$time_string = '<span class="posted-on"><span class="screen-reader-text">Posted on </span><time class="entry-date published" datetime="%1$s">%2$s</time></span>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string .= '<time class="updated" datetime="%3$s">%4$s</time>';
	}

	printf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);

	/* Author */
	printf( '<span class="byline"><span class="screen-reader-text"> by </span><span class="author vcard"><a class="url fn n" href="%1$s">%2$s</a></span></span>',
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_html( get_the_author() )
	);

	/* Permalink */
	printf( '<span class="permalink"><a href="%1$s" rel="bookmark">&#8734</a></span>',
		esc_url( get_permalink() )
	);
}
endif;

if ( ! function_exists( 'bosco_post_meta' ) ) :
/**
 * Prints HTML with the post format, date/time, author and permalink for the current post.
 */
function bosco_post_meta() {

	// Post Format
	$format = get_post_format();
	$supported_formats = get_theme_support( 'post-formats' );

	if ( $format && has_post_format( $supported_formats[0] ) ) {
		printf( '<span class="post-format"><a class="entry-format" href="%1$s">%2$s</a></span>',
			esc_url( get_post_format_link( $format ) ),
			get_post_format_string( $format )
		);
	}

	// Date and Permalink
	$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string .= '<time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);

	printf( __( '<span class="posted-on">Posted on %1$s</span><span class="byline"> by %2$s</span>', 'bosco' ),
		sprintf( '<a href="%1$s" rel="bookmark">%2$s</a>',
			esc_url( get_permalink() ),
			$time_string
		),
		sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s">%2$s</a></span>',
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			esc_html( get_the_author() )
		)
	);
}
endif;

if ( ! function_exists( 'bosco_categories_tags' ) ) :
/**
 * Prints HTML with the categories and tags for the current post.
 */
function bosco_categories_tags() {
	$categories_tags = '';

	/* translators: used between list items, there is a space after the comma */
	$categories_list = get_the_category_list( __( ', ', 'bosco' ) );

	/* translators: used between list items, there is a space after the comma */
	$tags_list = get_the_tag_list( '', __( ', ', 'bosco' ) );

	// Don't print anything if there's only 1 category and no tags.
	if ( bosco_categorized_blog() || $tags_list ) :
?>
	<div class="categories-tags">
		<?php if( bosco_categorized_blog() ) : ?>
		<span class="cat-links">
			<?php printf( __( 'Posted in %1$s', 'bosco' ), $categories_list ); ?>
		</span>
		<?php endif; // End if bosco_categorized_blog() ?>

		<?php if ( $tags_list ) : ?>
		<span class="tags-links">
			<?php printf( __( 'Tagged %1$s', 'bosco' ), $tags_list ); ?>
		</span>
		<?php endif; // End if $tags_list ?>
	</div><!-- .categories-tags -->
<?php
	endif;
}
endif;

if ( ! function_exists( 'bosco_the_attached_image' ) ) :
/**
 * Print the attached image with a link to the next attached image.
 */
function bosco_the_attached_image() {
	$next_attachment_url = wp_get_attachment_url();
	$post                = get_post();

	/*
	 * Grab the IDs of all the image attachments in a gallery so we can get the URL
	 * of the next adjacent image in a gallery, or the first image (if we're
	 * looking at the last image in a gallery), or, in a gallery of one, just the
	 * link to that image file.
	 */
	$attachment_ids = get_posts( array(
		'post_parent'    => $post->post_parent,
		'fields'         => 'ids',
		'numberposts'    => -1,
		'post_status'    => 'inherit',
		'post_type'      => 'attachment',
		'post_mime_type' => 'image',
		'order'          => 'ASC',
		'orderby'        => 'menu_order ID'
	) );

	// If there is more than 1 attachment in a gallery...
	if ( count( $attachment_ids ) > 1 ) {
		foreach ( $attachment_ids as $attachment_id ) {
			if ( $attachment_id == $post->ID ) {
				$next_id = current( $attachment_ids );
				break;
			}
		}

		// get the URL of the next image attachment...
		if ( $next_id )
			$next_attachment_url = get_attachment_link( $next_id );

		// or get the URL of the first image attachment.
		else
			$next_attachment_url = get_attachment_link( array_shift( $attachment_ids ) );
	}

	printf( '<a href="%1$s" title="%2$s" rel="attachment">%3$s</a>',
		esc_url( $next_attachment_url ),
		the_title_attribute( array( 'echo' => false ) ),
		wp_get_attachment_image( $post->ID, 'full' )
	);
}
endif;

/**
 * Uses get_url_in_content() to get the URL of the first link found in the post content.
 * Falls back to the post permalink if no URL is found in the post.
 */
function bosco_get_link_url() {
	$content = get_the_content();
	$has_url = get_url_in_content( $content );

	return ( $has_url ) ? $has_url : apply_filters( 'the_permalink', get_permalink() );
}

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with a Continue reading link.
 *
 * To override this in a child theme, remove the filter and add your own
 * function tied to the excerpt_more filter hook.
 */
if ( ! function_exists( 'bosco_excerpt_more' ) ) :
function bosco_excerpt_more( $more ) {
	return sprintf( ' <a href="%1$s" class="more-link">%2$s</a>',
		esc_url( get_permalink( get_the_ID() ) ),
		sprintf( __( 'Continue reading %s', 'bosco' ), '<span class="screen-reader-text">' . get_the_title( get_the_ID() ) . '</span> <span class="meta-nav">&rarr;</span>' )
		);
}
add_filter( 'excerpt_more', 'bosco_excerpt_more' );
endif;
