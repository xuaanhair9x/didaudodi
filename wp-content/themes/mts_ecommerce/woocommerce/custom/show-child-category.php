<?php
$current_category_id;
$categories = get_term_children( $current_category_id, 'product_cat' );
?>
<?php
$mts_options = get_option(MTS_THEME_NAME);
$brands_heading = isset( $mts_options['brands_heading'] ) ? $mts_options['brands_heading'] : '';
?>
<style>
    .brand-container {

    }
    .owl-carousel .owl-item {
        border: 1px solid #d5d8db;
        border-radius: 10px;
        margin-left: 5px;
    }
    .shop-by-brand {
        float: left;
        width: 100%;
        clear: both;
        display: flex;
    }
    .owl-stage {
        display: flex;
    }
    .brand-slider {
        padding: 10px 15px;
    }
</style>
<div class="shop-by-brand home-section clearfix">
    <div class="container">
        <div class="brand-controls">
            <div class="custom-nav">
                <a class="btn brand-prev"><i class="fa fa-angle-left"></i></a>
                <a class="btn brand-next"><i class="fa fa-angle-right"></i></a>
            </div>
        </div>
            <div class="brand-container clearfix loading">
                <div id="brands-slider" class="brand-category">
                    <?php foreach ( $categories as $category ) {
                        $term = get_term( $category, 'product_cat' );
                        ?>
                        <div class="brand-slider">
                            <a href="<?= get_term_link($term) ?>" class="cat-name"><?= $term->name ?>
                            </a>
                        </div>
                        <?php }
                     ?>
                </div>
            </div>
    </div>
</div>