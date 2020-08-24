<?php
/**
 * Template Name: Deals page
 */
?>
<?php get_header(); ?>
<div class="mts-ad-widgets catalog-ad-widgets clearfix">
    <div class="container mts-ads-container">
        <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar( 'deals-banners' ) ) : ?><?php endif; ?>
    </div>

<?php get_footer(); ?>