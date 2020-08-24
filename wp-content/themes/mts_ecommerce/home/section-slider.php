<?php $mts_options = get_option(MTS_THEME_NAME); ?>
<div class="primary-slider-container clearfix loading">
    <div id="home-slider" class="primary-slider">
    <?php if ( !empty( $mts_options['mts_custom_slider'] ) ) { ?>
        <?php foreach( $mts_options['mts_custom_slider'] as $slide ) : ?>
            <?php 
            $image_url = wp_get_attachment_image_src( $slide['mts_custom_slider_image'], 'full' );
            $image_url = $image_url[0];

            $slide_link        = $slide['mts_custom_slider_link'];

            $slide_title       = $slide['mts_custom_slider_title'];
            $slide_subtitle    = $slide['mts_custom_slider_subtitle'];
            $slide_heading     = $slide['mts_custom_slider_heading'];
            $slide_subheading  = $slide['mts_custom_slider_subheading'];
            $slide_button_text = $slide['mts_custom_slider_button_text'];
            ?>
            <div class="home-slide" style="background-image: url(<?php echo $image_url;?>);">
                <div class="container">
                    <a href="<?php echo esc_url( $slide_link ); ?>">
                        <div class="slide-caption">
                            <?php if ( !empty( $slide_title ) ) { ?><div class="slide-content-1"><?php echo $slide_title; ?></div><?php } ?>
                            <?php if ( !empty( $slide_subtitle ) ) { ?><div class="slide-content-2"><?php echo $slide_subtitle; ?></div><?php } ?>
                            <?php if ( !empty( $slide_heading ) ) { ?><div class="slide-content-3"><?php echo $slide_heading; ?></div><?php } ?>
                            <?php if ( !empty( $slide_subheading ) ) { ?><div class="slide-content-4"><?php echo $slide_subheading; ?></div><?php } ?>
                            <?php if ( !empty( $slide_button_text ) ) { ?><div class="readMore"><?php echo $slide_button_text; ?></div><?php } ?>
                        </div>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php } ?>
    </div><!-- .primary-slider -->
</div><!-- .primary-slider-container -->