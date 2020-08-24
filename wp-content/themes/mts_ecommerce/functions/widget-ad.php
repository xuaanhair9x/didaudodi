<?php

// widget class
class mts_advanced_ads extends WP_Widget {

    function __construct() {
        $widget_ops = array('classname' => 'mts-ads');
        parent::__construct('mts-ads-widget', 'MyThemeShop: Ad', $widget_ops);
    }

    function form($instance) {
        $defaults = array(
            'image_uri' => '',
            'attachment_id' => '',
            'width' => 'full-width',
            'link' => '',
            'top_text' => '',
            'middle_text' => '',
            'bottom_text' => '',
            'button_label' => '',
            'horizontal_position' => 'left',
            'vertical_position' => 'top',
            'top_text_color' => '',
            'middle_text_color' => '',
            'bottom_text_color' => '',
            'btn_color' => '',
            'btn_bg_color' => '',
        );
        $instance = wp_parse_args((array) $instance, $defaults);

        $image_uri     = $instance['image_uri'];
        $attachment_id = abs($instance['attachment_id']);
        $width         = $instance['width'];
        $first         = isset( $instance['first'] ) ? (bool) $instance['first'] : false;
        $link          = $instance['link'];

        $top_text        = $instance['top_text'];
        $middle_text     = $instance['middle_text'];
        $bottom_text     = $instance['bottom_text'];
        $button_label    = $instance['button_label'];

        $horizontal_position = $instance['horizontal_position'];
        $vertical_position   = $instance['vertical_position'];

        $text_colors = isset( $instance['text_colors'] ) ? (bool) $instance['text_colors'] : false;

        $top_text_color    = $instance['top_text_color'];
        $middle_text_color = $instance['middle_text_color'];
        $bottom_text_color = $instance['bottom_text_color'];
        $btn_color         = $instance['btn_color'];
        $btn_bg_color      = $instance['btn_bg_color'];

        $id_prefix = $this->get_field_id('');
    ?>
    <p>
        <div  class="" id="<?php echo $this->get_field_id('preview'); ?>">
        <?php
        if ( $image_uri != '' ) {
            echo '<img class="custom_media_image" src="' . $image_uri . '" style="margin:0 0 10px;padding:0;max-width:100%;height:auto;float:left;display:inline-block" />';
        }
        ?>
        </div>
        <input type="hidden" id="<?php echo $this->get_field_id('attachment_id'); ?>" name="<?php echo $this->get_field_name('attachment_id'); ?>" value="<?php echo $attachment_id; ?>" />
        <input type="hidden" id="<?php echo $this->get_field_id('image_uri'); ?>" name="<?php echo $this->get_field_name('image_uri'); ?>" value="<?php echo $image_uri; ?>" />
        <input type="submit" class="button" name="<?php echo $this->get_field_name('uploader_button'); ?>" id="<?php echo $this->get_field_id('uploader_button'); ?>" value="<?php _e('Select an Image', 'image_widget'); ?>" onclick="mtsImageWidgetField.uploader( '<?php echo $this->id; ?>', '<?php echo $id_prefix; ?>' ); return false;" />
    </p>

    <p>
        <label for="<?php echo $this->get_field_id('width'); ?>"><?php _e('Widget Width:', MTS_THEME_TEXTDOMAIN); ?></label>
        <select id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>">
            <option value="one-fourth" <?php selected($width, 'one-fourth', true); ?>><?php _e('One Fourth', MTS_THEME_TEXTDOMAIN); ?></option>
            <option value="one-third" <?php selected($width, 'one-third', true); ?>><?php _e('One Third', MTS_THEME_TEXTDOMAIN); ?></option>
            <option value="one-half" <?php selected($width, 'one-half', true); ?>><?php _e('One Half', MTS_THEME_TEXTDOMAIN); ?></option>
            <option value="two-third" <?php selected($width, 'two-third', true); ?>><?php _e('Two Third', MTS_THEME_TEXTDOMAIN); ?></option>
            <option value="three-fourth" <?php selected($width, 'three-fourth', true); ?>><?php _e('Three Fourth', MTS_THEME_TEXTDOMAIN); ?></option>
            <option value="full-width" <?php selected($width, 'full-width', true); ?>><?php _e('Full Width', MTS_THEME_TEXTDOMAIN); ?></option>
        </select>
    </p>

    <p>
        <input type="checkbox" id="<?php echo $this->get_field_id('first'); ?>" name="<?php echo $this->get_field_name('first'); ?>"<?php checked( $first ); ?> />
        <label for="<?php echo $this->get_field_id('first'); ?>"><?php _e( 'First ad in a row?', MTS_THEME_TEXTDOMAIN ); ?></label><br />
    </p>

    <p>
        <label for="<?php echo $this->get_field_id( 'link' ); ?>"><?php _e( 'Link:',MTS_THEME_TEXTDOMAIN ); ?></label> 
        <input class="widefat" id="<?php echo $this->get_field_id( 'link' ); ?>" name="<?php echo $this->get_field_name( 'link' ); ?>" type="text" value="<?php echo esc_attr( $link ); ?>" />
    </p>

    <p>
        <label for="<?php echo $this->get_field_id( 'top_text' ); ?>"><?php _e( 'Top Text:',MTS_THEME_TEXTDOMAIN ); ?></label> 
        <input class="widefat" id="<?php echo $this->get_field_id( 'top_text' ); ?>" name="<?php echo $this->get_field_name( 'top_text' ); ?>" type="text" value="<?php echo esc_attr( $top_text ); ?>" />
    </p>

    <p>
        <label for="<?php echo $this->get_field_id( 'middle_text' ); ?>"><?php _e( 'Middle Text:',MTS_THEME_TEXTDOMAIN ); ?></label> 
        <input class="widefat" id="<?php echo $this->get_field_id( 'middle_text' ); ?>" name="<?php echo $this->get_field_name( 'middle_text' ); ?>" type="text" value="<?php echo esc_attr( $middle_text ); ?>" />
    </p>

    <p>
        <label for="<?php echo $this->get_field_id( 'bottom_text' ); ?>"><?php _e( 'Bottom Text:',MTS_THEME_TEXTDOMAIN ); ?></label> 
        <input class="widefat" id="<?php echo $this->get_field_id( 'bottom_text' ); ?>" name="<?php echo $this->get_field_name( 'bottom_text' ); ?>" type="text" value="<?php echo esc_attr( $bottom_text ); ?>" />
    </p>

    <p>
        <label for="<?php echo $this->get_field_id( 'button_label' ); ?>"><?php _e( 'Button Label:',MTS_THEME_TEXTDOMAIN ); ?></label> 
        <input class="widefat" id="<?php echo $this->get_field_id( 'button_label' ); ?>" name="<?php echo $this->get_field_name( 'button_label' ); ?>" type="text" value="<?php echo esc_attr( $button_label ); ?>" />
    </p>

    <p>
        <label for="<?php echo $this->get_field_id('horizontal_position'); ?>"><?php _e('Content Horizontal Position:', MTS_THEME_TEXTDOMAIN); ?></label>
        <select id="<?php echo $this->get_field_id('horizontal_position'); ?>" name="<?php echo $this->get_field_name('horizontal_position'); ?>">
            <option value="left" <?php selected($horizontal_position, 'left', true); ?>><?php _e('Left', MTS_THEME_TEXTDOMAIN); ?></option>
            <option value="center" <?php selected($horizontal_position, 'center', true); ?>><?php _e('Center', MTS_THEME_TEXTDOMAIN); ?></option>
            <option value="right" <?php selected($horizontal_position, 'right', true); ?>><?php _e('Right', MTS_THEME_TEXTDOMAIN); ?></option>
        </select>
    </p>

    <p>
        <label for="<?php echo $this->get_field_id('vertical_position'); ?>"><?php _e('Content Vertical Position:', MTS_THEME_TEXTDOMAIN); ?></label>
        <select id="<?php echo $this->get_field_id('vertical_position'); ?>" name="<?php echo $this->get_field_name('vertical_position'); ?>">
            <option value="top" <?php selected($vertical_position, 'top', true); ?>><?php _e('Top', MTS_THEME_TEXTDOMAIN); ?></option>
            <option value="middle" <?php selected($vertical_position, 'middle', true); ?>><?php _e('Middle', MTS_THEME_TEXTDOMAIN); ?></option>
            <option value="bottom" <?php selected($vertical_position, 'bottom', true); ?>><?php _e('Bottom', MTS_THEME_TEXTDOMAIN); ?></option>
        </select>
    </p>

    <p>
        <input type="checkbox" class="checkbox ad-widget-mother-checkbox" id="<?php echo $this->get_field_id('text_colors'); ?>" name="<?php echo $this->get_field_name('text_colors'); ?>"<?php checked( $text_colors ); ?> />
        <label for="<?php echo $this->get_field_id('text_colors'); ?>"><?php _e( 'Change colors?', MTS_THEME_TEXTDOMAIN ); ?></label><br />
    </p>

    <p class="mother-checkbox-<?php echo $this->get_field_id('text_colors'); ?>">
        <label for="<?php echo $this->get_field_id('top_text_color'); ?>"><?php _e('Top Text Color:', MTS_THEME_TEXTDOMAIN); ?></label><br />
        <input type="text" class="ad-widget-color-picker" id="<?php echo $this->get_field_id('top_text_color'); ?>" name="<?php echo $this->get_field_name('top_text_color'); ?>" value="<?php echo $top_text_color; ?>" />
    </p>

    <p class="mother-checkbox-<?php echo $this->get_field_id('text_colors'); ?>">
        <label for="<?php echo $this->get_field_id('middle_text_color'); ?>"><?php _e('Middle Text Color:', MTS_THEME_TEXTDOMAIN); ?></label><br />
        <input type="text" class="ad-widget-color-picker" id="<?php echo $this->get_field_id('middle_text_color'); ?>" name="<?php echo $this->get_field_name('middle_text_color'); ?>" value="<?php echo $middle_text_color; ?>" />
    </p>

    <p class="mother-checkbox-<?php echo $this->get_field_id('text_colors'); ?>">
        <label for="<?php echo $this->get_field_id('bottom_text_color'); ?>"><?php _e('Bottom Text Color:', MTS_THEME_TEXTDOMAIN); ?></label><br />
        <input type="text" class="ad-widget-color-picker" id="<?php echo $this->get_field_id('bottom_text_color'); ?>" name="<?php echo $this->get_field_name('bottom_text_color'); ?>" value="<?php echo $bottom_text_color; ?>" />
    </p>

    <p class="mother-checkbox-<?php echo $this->get_field_id('text_colors'); ?>">
        <label for="<?php echo $this->get_field_id('btn_color'); ?>"><?php _e('Button Color:', MTS_THEME_TEXTDOMAIN); ?></label><br />
        <input type="text" class="ad-widget-color-picker" id="<?php echo $this->get_field_id('btn_color'); ?>" name="<?php echo $this->get_field_name('btn_color'); ?>" value="<?php echo $btn_color; ?>" />
    </p>

    <p class="mother-checkbox-<?php echo $this->get_field_id('text_colors'); ?>">
        <label for="<?php echo $this->get_field_id('btn_bg_color'); ?>"><?php _e('Button Background Color:', MTS_THEME_TEXTDOMAIN); ?></label><br />
        <input type="text" class="ad-widget-color-picker" id="<?php echo $this->get_field_id('btn_bg_color'); ?>" name="<?php echo $this->get_field_name('btn_bg_color'); ?>" value="<?php echo $btn_bg_color; ?>" />
    </p>

<?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['image_uri']     = strip_tags( $new_instance['image_uri'] );
        $instance['attachment_id'] = abs($new_instance['attachment_id']);
        $instance['width']         = $new_instance['width'];
        $instance['first']         = ! empty( $new_instance['first'] ) ? 1 : 0;
        $instance['link']          = strip_tags( $new_instance['link'] );

        $instance['top_text']     = strip_tags( $new_instance['top_text'] );
        $instance['middle_text']  = strip_tags( $new_instance['middle_text'] );
        $instance['bottom_text']  = strip_tags( $new_instance['bottom_text'] );
        $instance['button_label'] = strip_tags( $new_instance['button_label'] );
        
        $instance['horizontal_position'] = $new_instance['horizontal_position'];
        $instance['vertical_position']   = $new_instance['vertical_position'];

        $instance['text_colors']     = ! empty( $new_instance['text_colors'] ) ? 1 : 0;

        $instance['top_text_color']    = $new_instance['top_text_color'];
        $instance['middle_text_color'] = $new_instance['middle_text_color'];
        $instance['bottom_text_color'] = $new_instance['bottom_text_color'];
        $instance['btn_color']         = $new_instance['btn_color'];
        $instance['btn_bg_color']      = $new_instance['btn_bg_color'];
        
        return $instance;
    }

    function widget($args, $instance) {
        extract($args);

        $image_uri     = esc_url( $instance['image_uri'] );
        $attachment_id = $instance['attachment_id'];
        $width         = !empty( $instance['width']) ? ' '.$instance['width'] : '';
        $first         = ! empty( $instance['first'] ) ? true : false;
        $link          = esc_url( $instance['link'] );

        $top_text     = $instance['top_text'];
        $middle_text  = $instance['middle_text'];
        $bottom_text  = $instance['bottom_text'];
        $button_label = $instance['button_label'];
        
        $horizontal_position = $instance['horizontal_position'];
        $vertical_position   = $instance['vertical_position'];

        $text_colors     = ! empty( $instance['text_colors'] ) ? true : false;

        $top_text_color    = $instance['top_text_color'];
        $middle_text_color = $instance['middle_text_color'];
        $bottom_text_color = $instance['bottom_text_color'];
        $btn_color         = $instance['btn_color'];
        $btn_bg_color      = $instance['btn_bg_color'];

        $first_class     = ( $first ) ? ' first' : '';

        $top_text_inline_style    = ( !empty( $top_text_color ) ) ? ' style="color:'.$top_text_color.'"' : '';
        $middle_text_inline_style = ( $text_colors && !empty( $middle_text_color ) ) ? ' style="color:'.$middle_text_color.'"' : '';
        $bottom_text_inline_style = ( $text_colors && !empty( $bottom_text_color ) ) ? ' style="color:'.$bottom_text_color.'"' : '';

        $btn_inline_style = '';
        if ( ( $btn_color && !empty( $btn_color ) ) || ( $btn_bg_color && !empty( $btn_bg_color ) ) ) {
            $btn_inline_style .= ' style="';
            if ( $btn_color && !empty( $btn_color ) ) $btn_inline_style .= 'color:'.$btn_color.';';
            if ( $btn_bg_color && !empty( $btn_bg_color ) ) $btn_inline_style .= 'background-color:'.$btn_bg_color.';';
            $btn_inline_style .= '"';
        }

        $h_class = ' text-'.$horizontal_position;
        $v_class = ' vertical-align-'.$vertical_position;

        ?>

        <div id="<?php echo $widget_id; ?>" class="mts-ad<?php echo $width.$first_class.$h_class.$v_class; ?>">
            <div class="mts-ad-inner">
                <?php if(!empty($link)) { ?>
                <a href="<?php echo $link; ?>">
                <?php }
                    if ( $image_uri != '' ) { ?>
                        <img src="<?php echo $image_uri; ?>" />
                    <?php } ?>
                    <div class="banner-content-container">
                        <div class="banner-content">
                            <div class="banner-content-inner">
                                <?php if ( !empty( $top_text ) ) { ?><div class="medium-heading"<?php echo $top_text_inline_style; ?>><?php echo $top_text; ?></div><?php } ?>
                                <?php if ( !empty( $middle_text ) ) { ?><div class="large-heading"<?php echo $middle_text_inline_style; ?>><?php echo $middle_text; ?></div><?php } ?>
                                <?php if ( !empty( $bottom_text ) ) { ?><div class="medium-heading"<?php echo $bottom_text_inline_style; ?>><?php echo $bottom_text; ?></div><?php } ?>
                                <?php if ( !empty( $button_label ) ) { ?>
                                    <div class="ad-button">
                                        <span class="button readMore"<?php echo $btn_inline_style; ?>><?php echo $button_label; ?></span>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                <?php if(!empty($link)) { ?>
                </a>
                <?php } ?>
            </div>
        </div>
        <?php
        
        ?>

        <?php
    }
}

// add admin scripts
add_action('admin_enqueue_scripts', 'mts_advanced_script');
function mts_advanced_script() {
    $screen = get_current_screen();
    $screen_id = $screen->id;

    if ( 'widgets' == $screen_id ) {
        wp_enqueue_media();
        wp_enqueue_script('ads_script', get_template_directory_uri() . '/js/adwidget.js', array( 'jquery', 'media-upload', 'media-views' ), '1.0', true);
        wp_localize_script( 'ads_script', 'adWidget', array(
            'frame_title' => __( 'Select an Image', MTS_THEME_TEXTDOMAIN ),
            'button_title' => __( 'Insert Into Widget', MTS_THEME_TEXTDOMAIN ),
        ) );
    }
}

// register widget
add_action('widgets_init', 'mts_advanced_ads_widget');
function mts_advanced_ads_widget() {
    register_widget( 'mts_advanced_ads' );
}
?>