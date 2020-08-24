<?php
/**
 * Template Name: Blog
 *
 */
?>
<?php $mts_options = get_option(MTS_THEME_NAME); ?>
<?php get_header(); ?>
<?php if (mts_get_thumbnail_url()) : ?>
    <div class="blog-background" <?php echo 'style="background-image: url('.mts_get_thumbnail_url().');"'; ?>>
        <div class="container">
            <h1 class="title"><?php echo get_the_title(); ?></h1>
        </div>
    </div>
<?php endif; ?>
<div id="page" class="blog-page">
    <div class="<?php mts_article_class(); ?>">
        <div id="content_box">
            <?php
            if( is_front_page() ){
              if ( get_query_var( 'paged' ) ) { $paged = get_query_var( 'paged' ); }
              elseif ( get_query_var( 'page' ) ) { $paged = get_query_var( 'page' ); }
              else { $paged = 1; }
            } else{
              $paged = ( get_query_var('paged') > 1 ) ? get_query_var('paged') : 1;
            }
                $args = array(
                    'post_type' => 'post',
                    'post_status' => 'publish',
                    'paged' => $paged,
                    'ignore_sticky_posts'=> 1,
                );
                $latest_posts = new WP_Query( $args );
                global $wp_query;
                $tmp_query = $wp_query;
                $wp_query = null;
                $wp_query = $latest_posts;
                $j = 0;
            if ( $latest_posts->have_posts() ) : while ( $latest_posts->have_posts() ) : $latest_posts->the_post(); ?>
                <?php mts_archive_post(); ?>
            <?php $j++; endwhile; endif; ?>
            <?php //if ( $j !== 0 ) { // No pagination if there is no results ?>
                <?php mts_pagination(); ?>
            <?php //}
            // Restore original query object
            $wp_query = $tmp_query;
            wp_reset_postdata();
            ?>
        </div>
    </div>
    <?php get_sidebar(); ?>
<?php get_footer(); ?>
