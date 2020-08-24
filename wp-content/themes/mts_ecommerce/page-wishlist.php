<?php
/**
 * Template Name: Wishlist page
 */
?>
<?php get_header(); ?>
<div id="page" class="single mts-wishlist-page woocommerce single-page">

    <article class="<?php echo mts_article_class(); ?>">
        <div id="content_box" >
        <?php if ( mts_isWooCommerce() ) { ?>
            <?php wc_print_notices();?>
            <div class="single_page">
                <header class="entry-header">
                    <h1 class="title entry-title"><?php the_title(); ?></h1>
                </header>
                <div id="mts-wishlist-content" class="post-content box mark-links entry-content">
                    <?php mts_wishlist_page_loop(); ?>
                </div><!--.post-content box mark-links-->
            </div>
            <?php } ?>
        </div>
    </article>

    <?php get_sidebar(); ?>

<?php get_footer(); ?>