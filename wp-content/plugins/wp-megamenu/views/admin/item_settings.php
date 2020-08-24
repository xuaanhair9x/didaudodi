<?php
$menu_item_id = (int) sanitize_text_field($_POST['menu_item_id']);
$menu_item_depth = (int) sanitize_text_field($_POST['menu_item_depth']);

$get_menu_settings = get_post_meta($menu_item_id, 'wpmm_layout', true);
$wpmm_layout = get_post_meta($menu_item_id, 'wpmm_layout', true);

$menu_type = '';
if ( ! empty($get_menu_settings['menu_type'])){
    $menu_type = $get_menu_settings['menu_type'];
}

$menu_strees_row = '';
if ( ! empty($get_menu_settings['menu_strees_row'])){
    $menu_strees_row = $get_menu_settings['menu_strees_row'];
}
$item_width = '';
if ( ! empty( $get_menu_settings['options']['width'] )){
    $item_width =  $get_menu_settings['options']['width'];
}
$item_strees_row_width = '';
if ( ! empty( $get_menu_settings['options']['strees_row_width'] )){
    $item_strees_row_width =  $get_menu_settings['options']['strees_row_width'];
}
//Widgets Manager
$widgets_manager = new wp_megamenu_widgets();
$widgets = $widgets_manager->get_all_registered_widget();
//Get Menu Name
?>

<div class="wpmm-item-settings-top-bar">

    <div class="wpmm-item-settings-title">
        <h1><i class="fa fa-bars"></i> <span class="wpmm-item-settings-heading"></span></h1>
    </div>

    <div class="wpmm-onoff-wrap">
        <h1><?php _e('Mega Menu', 'wp-megamenu'); ?></h1>

        <div class="wpmm-stylish-checkbox"> <!-- Custom Checkbox -->
            <input id="wpmm-onoff" type="checkbox" value="1" <?php checked($menu_type, 'wpmm_mega_menu') ; ?> >
            <label for="wpmm-onoff">
                <span>
                    <span></span>
                    <strong class="custom-checkbox-1"><?php _e('YES', 'wp-megamenu'); ?></strong>
                    <strong class="custom-checkbox-2"><?php _e('NO', 'wp-megamenu'); ?></strong>
                </span>
            </label>
        </div> <!-- //.custom-checkbox -->

        <div class="wpmm-width-stress">
            <label>
                <select name="wpmm_strees_row">
                    <option value=""><?php _e('-- Select Stretch --'); ?></option>

                    <option value="wpmm-strees-default" <?php selected($menu_strees_row, 'wpmm-strees-default') ; ?> ><?php _e('Stretch Default', 'wp-megamenu'); ?></option>
                    <option value="wpmm-strees-row" <?php selected($menu_strees_row, 'wpmm-strees-row') ; ?> ><?php _e('Stretch Row', 'wp-megamenu'); ?></option>
                    <option value="wpmm-strees-row-and-content" <?php selected($menu_strees_row, 'wpmm-strees-row-and-content') ; ?> ><?php _e('Stretch Row and Content', 'wp-megamenu'); ?></option>
                </select>
            </label>

            <label id="wpmm_stress_row_width_label" style="display: <?php echo ($menu_strees_row ===
            'wpmm-strees-row' || $menu_strees_row === 'wpmm-strees-default' ) ? 'inline-block': 'none'; ?>;">
                <?php _e('Width', 'wp-megamenu'); ?>
                <input id="wpmm_stress_row_width" type="number" name="wpmm_stress_row_width" size="10" value="<?php echo $item_strees_row_width; ?>" placeholder="<?php _e('ex: 1170', 'wp-megamenu'); ?>" />
            </label>

            <!--<label>
                <?php /*_e('Width', 'wp-megamenu'); */?>
                <input id="wpmm_item_row_width" type="text" name="wpmm_width" size="10" value="<?php /*echo $item_width; */?>" placeholder="<?php /*_e('ex: 500px', 'wp-megamenu'); */?>" />
            </label>-->
        </div>

        <!--<div class="builder-refresh">
            <a href="javascript:;" id="refresh_wpmm_builder"><i class="fa fa-refresh"></i></a>
        </div>-->

    </div>
    <a href="javascript:;" class="wpmm-isp-close-btn"><i class="fa fa-window-close"></i> </a>
    <a href="javascript:;" class="wpmm-saving-indecator" style="display: none;"><?php _e('Saving...', 'wp-megamenu'); ?></a>
    <div class="clear"></div>
</div>
<div class="clear"></div>

<div id="wpmm-item-settings-tabs" class="wpmm-item-settings-panel wpmm-menu-builder-settings-panel" data-id="<?php echo $menu_item_id; ?>">

    <div class="wpmm-tabs-menu wpmm-tabs-menu-builder">
        <ul>
            <li class="active wpmmWidgetListLi" <?php if ($menu_type != 'wpmm_mega_menu'){ echo ' style="display: none;" '; } ?> >
                <a href="#wpmm-builder"> <i class="fa fa-th"></i> <?php _e
                    ('WP Mega Menu', 'wp-megamenu');
                    ?></a>
                <?php
                if ($menu_item_depth == 0){
                    ?>
                    <ul class="wpmm-widget-lists">
                        <li><?php _e('Widgets', 'wp-megamenu'); ?></li>
                        <li>
                            <div id="wpmm_widget_search">
                                <input type="text" placeholder="Search Here">
                            </div>
                            <div class="wmmDraggableWidgetLists innerLi">
                                <?php
                                if (count($widgets)){
                                    foreach ($widgets as $key => $value) {
                                        echo '<div class="draggableWidget" data-widget-id-base="' . $value['id_base'] . '" data-type="outsideWidget"> ' . $value['name'] . ' <span class="widgetsDragBtn"><i class="fa fa-arrows"></i> '.__('Drag', 'wp-megamenu').'</span></div>';
                                    }
                                }
                                ?>
                            </div>
                        </li>
                    </ul>
                <?php } ?>
            </li>
            <li><a href="#wpmm-options"> <i class="fa fa-gear"></i> <?php _e('Options', 'wp-megamenu'); ?></a></li>
            <li><a href="#wpmm-icons"> <i class="fa fa-cube"></i> <?php _e('Icon', 'wp-megamenu'); ?></a></li>
        </ul>
    </div>

    <div class="wpmm-tabs-content">
        <div id="wpmm-builder">
            <?php
            if ($menu_item_depth == 0){
                ?>
                <div class="wpmmDraggableWidgetArea <?php if ($menu_type != 'wpmm_mega_menu'){ echo ' disabled '; } ?> ">
                    <div class="shortable item-widgets-wrap wpmm-limit-height">
                        <?php
                        /**
                         * Get layout
                         */
                        echo '<div id="wpmm_item_layout_wrap">';
                        if ( count($wpmm_layout['layout']) ){
                            foreach ($wpmm_layout['layout'] as $layout_key => $layout_value){
                                echo '<div class="wpmm-row" data-row-id="'.$layout_key.'">';

                                echo '<div class="wpmm-row-actions">';
                                    echo '<p class="wpmm-row-left wpmmRowSortingIcon"> <i class="fa fa-sort"></i> '.__('Row', 'wp-megamenu').'  </p>';
                                    echo '<p class="wpmm-row-right"> <span class="wpmmRowDeleteIcon"><i class="fa fa-trash-o"></i> </span>  </p>';
                                echo '<div class="clear"></div>';
                                echo '</div>';

                                foreach ($layout_value['row'] as $col_key => $layout_col){
                                    echo '<div class="wpmm-col wpmm-col-'.$layout_col['col'].'" data-col-id="'.$col_key.'">';

                                    echo '<div class="wpmm-item-wrap">';
                                        echo '<div class="wpmm-column-actions">';
                                        echo '<span class="wpmmColSortingIcon"><i class="fa fa-arrows"></i> '.__('Column', 'wp-megamenu').' 
                                    </span>';
                                        echo '</div>';

                                    //echo '<p>'.__('Drop here', 'wp-megamenu').'</p>';

                                    foreach ($layout_col['items'] as $key => $value){
                                        if ($value['item_type'] == 'widget'){
                                            wp_megamenu_widgets()->widget_items($value['widget_id'], $get_menu_settings, $key);
                                        }else{
                                            wp_megamenu_widgets()->menu_items($value, $key);
                                        }
                                    }

                                    echo '</div>';

                                    echo '</div>';
                                }
                                echo '</div>';
                            }
                        }
                        echo '</div>';

                        echo '<div class="wpmm-addrow-btn-wrap">';
                        echo '<button id="choose_layout" class="choose_layout" name="choose_layout"> <i class="fa fa-plus-circle"></i> '.__('Add Row', 'wp-megamenu').' </button>';

                        $builderLayout = '<div class="wpmm-modal in" id="layout-modal" style="display: none;">
                            <ul class="menu-layout-list clearfix">
                                <li><a href="#" class="layout12" data-layout="12" data-design="layout12"><div class="first-grid last-grid grid-design"></div></a></li>
                                
                                <li><a href="#" class="layout66" data-layout="6,6" data-design="layout66"><div class="first-grid middle-grid grid-design grid-design33"></div> <div class="last-grid grid-design grid-design33"></div></a></li>
                                
                                <li><a href="#" class="layout444" data-layout="4,4,4" data-design="layout444"><div class="first-grid grid-design grid-design444"></div> <div class="grid-design middle-grid grid-design444"></div> <div class="last-grid grid-design grid-design444"></div></a></li>
                                
                                <li><a href="#" class="layout3333" data-layout="3,3,3,3" data-design="layout3333"><div class="first-grid grid-design grid-design3333"></div> <div class="grid-design middle-grid grid-design3333"></div> <div class="grid-design middle-grid-left grid-design3333"></div> <div class="last-grid grid-design grid-design3333"></div></a></li>
                                                                
                                <li><a href="#" class="layout222222" data-layout="2,2,2,2,2,2" data-design="layout222222"><div class="first-grid grid-design grid-design6"></div> <div class="grid-design middle-grid grid-design6"></div> <div class="grid-design middle-grid-left grid-design6"></div> <div class="grid-design middle-grid-left grid-design6"></div> <div class="grid-design middle-grid-left grid-design6"></div> <div class="last-grid grid-design grid-design6"></div></a></li>
                                
                                <li><a href="#" class="layout48" data-layout="4,8" data-design="layout48"><div class="first-grid middle-grid grid-design grid-design24"></div> <div class="last-grid grid-design grid-design24"></div></a></li>
                                
                                <li><a href="#" class="layout84" data-layout="8,4" data-design="layout84"><div class="first-grid middle-grid grid-design grid-design42"></div> <div class="last-grid grid-design grid-design42"></div></a></li>                                
                                <li><a href="#" class="layout210" data-layout="2,10" data-design="layout210"><div class="first-grid middle-grid grid-design grid-design15"></div> <div class="last-grid grid-design grid-design15"></div></a></li>                                
                                <li><a href="#" class="layout102" data-layout="10,2" data-design="layout102"><div class="first-grid middle-grid grid-design grid-design51"></div> <div class="last-grid grid-design grid-design51"></div></a></li>
                            </ul>
                        </div>';
                        echo $builderLayout;

                        echo '</div>';
                        ?>
                    </div>
                </div>
            <?php
            }else{
                echo '<p> '._e('WP Megamenu will be work only in top level menu', 'wp-megamenu').' </p>';
            } ?>
        </div>

        <div id="wpmm-options">

            <form method="post" action="options.php" class="wpmm_item_options_form">
                <?php //wp_nonce_field('wpmm_nonce_action','wpmm_nonce_field'); ?>
 
                <table class="wpmm-item-options">

                    <?php if ($menu_item_depth == 0){ ?>
                        <tr>
                            <td><?php _e('Upload Background Image', 'wp-megamenu'); ?></td>
                            <td>
                                <div class="wpmm-image-upload-wrap">
                                    <?php $brand_logo = wpmm_get_item_settings($menu_item_id, 'menu_bg_image'); ?>
                                    <input type="button" class="wpmm_upload_image_button button" value="<?php _e( 'Upload image', 'wp-megamenu' ); ?>" /> <br />
                                    <div class="wpmm_upload_image_preview_wrap">
                                        <?php
                                            if ( ! empty($brand_logo)){
                                                echo '<img src="'.$brand_logo.'" class="wpmm_upload_image_preview" >';
                                                echo '<a href="javascript:;" class="wpmm_img_delete"><i class="fa fa-trash-o"></i> </a>';
                                            }
                                        ?>
                                    </div>
                                    <input type="text" class="wpmm_upload_image_field" name="options[menu_bg_image]" value="<?php echo $brand_logo; ?>" />
                                    <p class="field-description"><?php _e('Menu background image', 'wp-megamenu'); ?></p>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>

                    <tr>
                        <td><?php _e('Logged in only', 'wp-megamenu'); ?></td>
                        <td>
                            <input name="options[logged_in_only]" value="false" type="checkbox" <?php checked
                            (wpmm_get_item_settings($menu_item_id, 'logged_in_only'), 'false' ) ?> >
                        </td>
                    </tr>

                    <tr>
                        <td><?php _e('Hide Text', 'wp-megamenu'); ?></td>
                        <td><input name="options[hide_text]" value="true" type="checkbox" <?php checked
                            (wpmm_get_item_settings($menu_item_id, 'hide_text'), 'true' ) ?> ></td>
                    </tr>

                    <tr>
                        <td><?php _e('Hide Arrow', 'wp-megamenu'); ?></td>
                        <td><input name="options[hide_arrow]" value="true" type="checkbox" <?php checked
                            (wpmm_get_item_settings($menu_item_id, 'hide_arrow'), 'true' ) ?> ></td>
                    </tr>

                    <tr>
                        <td><?php _e('Disable Link', 'wp-megamenu'); ?></td>
                        <td><input name="options[disable_link]" value="true" type="checkbox"  <?php checked
                            (wpmm_get_item_settings($menu_item_id, 'disable_link'), 'true' ) ?> ></td>
                    </tr>

                    <tr>
                        <td><?php _e('Hide Item on Mobile', 'wp-megamenu'); ?></td>
                        <td><input name="options[hide_item_on_mobile]" value="true" type="checkbox" <?php checked
                            (wpmm_get_item_settings($menu_item_id, 'hide_item_on_mobile'), 'true' ) ?> ></td>
                    </tr>

                    <tr>
                        <td><?php _e('Hide Item on Desktop', 'wp-megamenu'); ?></td>
                        <td><input name="options[hide_item_on_desktop]" value="true" type="checkbox" <?php checked
                            (wpmm_get_item_settings($menu_item_id, 'hide_item_on_desktop'), 'true' ) ?> ></td>
                    </tr>

                    <tr>
                        <td><?php _e('Menu Item Alignment', 'wp-megamenu'); ?></td>
                        <td>
                            <select name="options[item_align]" >
                                <option value="left" <?php selected
                                (wpmm_get_item_settings($menu_item_id, 'item_align'), 'left' ) ?> ><?php _e('Left', 'wp-megamenu'); ?></option>
                                <option value="center" <?php selected
                                (wpmm_get_item_settings($menu_item_id, 'item_align'), 'center' ) ?> ><?php _e('Center', 'wp-megamenu'); ?></option>
                                <option value="right" <?php selected
                                (wpmm_get_item_settings($menu_item_id, 'item_align'), 'right' ) ?> ><?php _e('Right', 'wp-megamenu'); ?></option>
                            </select>
                        </td>
                    </tr>


                    <tr>
                        <td><?php _e('Dropdown alignment', 'wp-megamenu'); ?></td>
                        <td>
                            <select name="options[dropdown_alignment]" >
                                <option value="right" <?php selected(wpmm_get_item_settings($menu_item_id, 'dropdown_alignment'), 'right' ) ?> ><?php _e('Right', 'wp-megamenu'); ?></option>
                                <option value="left" <?php selected(wpmm_get_item_settings($menu_item_id, 'dropdown_alignment'), 'left' ) ?> ><?php _e('Left', 'wp-megamenu'); ?></option>
                            </select>
                        </td>
                    </tr>


                    <tr>
                        <td><?php _e('Icon Position', 'wp-megamenu'); ?></td>
                        <td>
                            <select name="options[icon_position]">
                                <option value="left" <?php selected
                                (wpmm_get_item_settings($menu_item_id, 'icon_position'), 'left' ) ?> ><?php _e('Left', 'wp-megamenu'); ?></option>
                                <option value="top" <?php selected
                                (wpmm_get_item_settings($menu_item_id, 'icon_position'), 'top' ) ?> ><?php _e('Top', 'wp-megamenu'); ?></option>
                                <option value="right" <?php selected
                                (wpmm_get_item_settings($menu_item_id, 'icon_position'), 'right' ) ?> ><?php _e('Right', 'wp-megamenu'); ?></option>
                            </select>
                        </td>
                    </tr>

                    <tr class="badge_text_style wpmm-field wpmm-field-group gap">
                        <td><?php _e('Badge Text', 'wp-megamenu'); ?></td>
                        <td>
                            <input type="text" name="options[badge_text]" value="<?php echo wpmm_get_item_settings($menu_item_id, 'badge_text'); ?>" placeholder="<?php _e('Badge Text', 'wp-megamenu'); ?>">
                            <select name="options[badge_style]">
                                <option value="default" <?php selected(wpmm_get_item_settings($menu_item_id, 'badge_style'), 'default' ) ?> ><?php _e('Default', 'wp-megamenu'); ?></option>
                                <option value="primary" <?php selected(wpmm_get_item_settings($menu_item_id, 'badge_style'), 'primary' ) ?> ><?php _e('Primary', 'wp-megamenu'); ?></option>
                                <option value="success" <?php selected(wpmm_get_item_settings($menu_item_id, 'badge_style'), 'success' ) ?> ><?php _e('Success', 'wp-megamenu'); ?></option>
                                <option value="info" <?php selected(wpmm_get_item_settings($menu_item_id, 'badge_style'), 'info' ) ?> ><?php _e('Info', 'wp-megamenu'); ?></option>
                                <option value="warning" <?php selected(wpmm_get_item_settings($menu_item_id, 'badge_style'), 'warning' ) ?> ><?php _e('Warning', 'wp-megamenu'); ?></option>
                                <option value="danger" <?php selected(wpmm_get_item_settings($menu_item_id, 'badge_style'), 'danger' ) ?> ><?php _e('Danger', 'wp-megamenu'); ?></option>
                            </select>
                        </td>
                    </tr>

                    <?php if ($menu_item_depth == 0){ ?> 
                        <tr class="wpmm-field wpmm-field-group">
                            <td><br><?php _e('Padding', 'wp-megamenu'); ?></td>
                            <td>
                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php _e('Top', 'wp-megamenu'); ?></p>
                                        <?php 
                                            $wp_megamenu_submenu_menu_padding_top = wpmm_get_item_settings($menu_item_id,'wp_megamenu_submenu_menu_padding_top');
                                            if( ! $wp_megamenu_submenu_menu_padding_top){ $wp_megamenu_submenu_menu_padding_top = ''; } 
                                        ?>
                                        <input type='text' name='options[wp_megamenu_submenu_menu_padding_top]' value="<?php echo $wp_megamenu_submenu_menu_padding_top; ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php _e('Right', 'wp-megamenu'); ?></p>
                                        <?php
                                            $wp_megamenu_submenu_menu_padding_right = wpmm_get_item_settings($menu_item_id,'wp_megamenu_submenu_menu_padding_right');
                                            if( ! $wp_megamenu_submenu_menu_padding_right){ $wp_megamenu_submenu_menu_padding_right = ''; }
                                        ?>
                                        <input type='text' name='options[wp_megamenu_submenu_menu_padding_right]' value="<?php echo $wp_megamenu_submenu_menu_padding_right; ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php _e('Bottom', 'wp-megamenu'); ?></p>
                                        <?php
                                            $wp_megamenu_submenu_menu_padding_bottom = wpmm_get_item_settings($menu_item_id,'wp_megamenu_submenu_menu_padding_bottom');
                                            if( ! $wp_megamenu_submenu_menu_padding_bottom){ $wp_megamenu_submenu_menu_padding_bottom = ''; }
                                        ?>
                                        <input type='text' name='options[wp_megamenu_submenu_menu_padding_bottom]' value="<?php echo $wp_megamenu_submenu_menu_padding_bottom; ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php _e('Left', 'wp-megamenu'); ?></p>
                                        <?php
                                            $wp_megamenu_submenu_menu_padding_left = wpmm_get_item_settings($menu_item_id,'wp_megamenu_submenu_menu_padding_left');
                                            if( ! $wp_megamenu_submenu_menu_padding_left){
                                                $wp_megamenu_submenu_menu_padding_left = '';
                                            }
                                        ?>
                                        <input type='text' name='options[wp_megamenu_submenu_menu_padding_left]' value="<?php echo $wp_megamenu_submenu_menu_padding_left; ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <p class="field-description"><?php _e('Set padding to menu bar.', 'wp-megamenu'); ?></p>
                            </td>
                        </tr>



                        <!-- Margin -->
                        <tr class="wpmm-field wpmm-field-group">
                            <td><br><?php _e('Margin', 'wp-megamenu'); ?></td>
                            <td>
                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php _e('Top', 'wp-megamenu'); ?></p>
                                        <?php 
                                            $single_menu_margin_top = wpmm_get_item_settings($menu_item_id,'single_menu_margin_top');
                                            if( ! $single_menu_margin_top){ $single_menu_margin_top = ''; } 
                                        ?>
                                        <input type='text' name='options[single_menu_margin_top]' value="<?php echo $single_menu_margin_top; ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php _e('Right', 'wp-megamenu'); ?></p>
                                        <?php
                                            $single_menu_margin_right = wpmm_get_item_settings($menu_item_id,'single_menu_margin_right');
                                            if( ! $single_menu_margin_right){ $single_menu_margin_right = ''; }
                                        ?>
                                        <input type='text' name='options[single_menu_margin_right]' value="<?php echo $single_menu_margin_right; ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php _e('Bottom', 'wp-megamenu'); ?></p>
                                        <?php
                                            $single_menu_margin_bottom = wpmm_get_item_settings($menu_item_id,'single_menu_margin_bottom');
                                            if( ! $single_menu_margin_bottom){ $single_menu_margin_bottom = ''; }
                                        ?>
                                        <input type='text' name='options[single_menu_margin_bottom]' value="<?php echo $single_menu_margin_bottom; ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php _e('Left', 'wp-megamenu'); ?></p>
                                        <?php
                                            $single_menu_margin_left = wpmm_get_item_settings($menu_item_id,'single_menu_margin_left');
                                            if( ! $single_menu_margin_left){
                                                $single_menu_margin_left = '';
                                            }
                                        ?>
                                        <input type='text' name='options[single_menu_margin_left]' value="<?php echo $single_menu_margin_left; ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <p class="field-description"><?php _e('Set padding to menu bar.', 'wp-megamenu'); ?></p>
                            </td>
                        </tr>

                    <?php } ?>


                    <!-- upload icons -->
                    <tr>
                        <td><?php _e('Upload/Choose Icon', 'wp-megamenu'); ?></td>


                        <?php if (function_exists('wpmm_pro_init')){ ?>
                            <td>
                                <div class="wpmm-image-upload-wrap">
                                    <?php $menu_icon = wpmm_get_item_settings($menu_item_id, 'menu_icon_image'); ?>
                                    <input type="button" class="wpmm_upload_image_button button" value="<?php _e( 'Upload image', 'wp-megamenu' ); ?>" /> <br />
                                    <div class="wpmm_upload_image_preview_wrap">
                                        <?php
                                            if ( ! empty($menu_icon)) {
                                                echo '<img src="'.$menu_icon.'" class="wpmm_upload_image_preview" >';
                                                echo '<a href="javascript:;" class="wpmm_img_delete"><i class="fa fa-trash-o"></i> </a>';
                                            }
                                        ?>
                                    </div>
                                    <input type="hidden" class="wpmm_upload_image_field" name="options[menu_icon_image]" value="<?php echo $menu_icon; ?>" />
                                    <p class="field-description"><?php _e('Menu icon upload', 'wp-megamenu'); ?></p>
                                </div>
                            </td>
                        <?php } else { ?>
                            <td class="wpmm-pro-install">
                                <div class="wpmm-image-upload-wrap">
                                    <input type="button" class="wpmm_upload_image_go_pro button" value="<?php _e( 'Upload image', 'wp-megamenu' ); ?>" />
                                    <a href="https://www.themeum.com/product/wp-megamenu/#pricing?utm_source=wp_mm&utm_medium=wordpress_sidebar&utm_campaign=go_premium" target="_blank">Go Premium</a>
                                </div>
                            </td>
                        <?php } ?>
                    </tr>
                                
                             
                    <?php if ($menu_item_depth != 0){ ?>
                        <tr class="wpmm-field item-font-color">
                            <td>
                                <?php _e('Font Color', 'wp-megamenu'); ?>
                            </td>
                            <td>
                                <input type="text" name="options[single_item_text_color]" value="<?php echo wpmm_get_item_settings($menu_item_id, 'single_item_text_color'); ?>" class="wpmmColorPicker" data-alpha="true" />
                            </td>
                        </tr>

                        <tr>
                            <td><?php _e('Font Size', 'wp-megamenu'); ?></td>
                            <td>
                                <input type="text" name="options[single_menu_font_size]" value="<?php echo wpmm_get_item_settings($menu_item_id, 'single_menu_font_size'); ?>" placeholder="<?php _e('e.g: 14', 'wp-megamenu'); ?>">
                            </td>
                        </tr>

                        <tr>
                            <td><?php _e('Font weight', 'wp-megamenu'); ?></td>
                            <td>
                                <select name="options[single_menu_font_weight]">
                                    <option value="300" <?php selected
                                    (wpmm_get_item_settings($menu_item_id, 'single_menu_font_weight'), '300' ) ?> ><?php _e('300', 'wp-megamenu'); ?></option>
                                    <option value="400" <?php selected
                                    (wpmm_get_item_settings($menu_item_id, 'single_menu_font_weight'), '400' ) ?> ><?php _e('400', 'wp-megamenu'); ?></option>
                                    <option value="500" <?php selected
                                    (wpmm_get_item_settings($menu_item_id, 'single_menu_font_weight'), '500' ) ?> ><?php _e('500', 'wp-megamenu'); ?></option>
                                    <option value="600" <?php selected
                                    (wpmm_get_item_settings($menu_item_id, 'single_menu_font_weight'), '600' ) ?> ><?php _e('600', 'wp-megamenu'); ?></option>
                                    <option value="700" <?php selected
                                    (wpmm_get_item_settings($menu_item_id, 'single_menu_font_weight'), '700' ) ?> ><?php _e('700', 'wp-megamenu'); ?></option>
                                    <option value="800" <?php selected
                                    (wpmm_get_item_settings($menu_item_id, 'single_menu_font_weight'), '800' ) ?> ><?php _e('800', 'wp-megamenu'); ?></option>
                                    <option value="900" <?php selected
                                    (wpmm_get_item_settings($menu_item_id, 'single_menu_font_weight'), '900' ) ?> ><?php _e('900', 'wp-megamenu'); ?></option>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td><?php _e('Line Height', 'wp-megamenu'); ?></td>
                            <td>
                                <input type="text" name="options[single_menu_line_height]" value="<?php echo wpmm_get_item_settings($menu_item_id, 'single_menu_line_height'); ?>" placeholder="<?php _e('e.g: 20', 'wp-megamenu'); ?>">
                            </td>
                        </tr>

                        <tr class="badge_text_style">
                            <td>
                                <?php _e('Item Border Separator', 'wp-megamenu'); ?>
                            </td>
                            <td>
                                <label>
                                    <input type='text' name='options[single_menu_item_border_separator_width]' value="<?php echo wpmm_get_item_settings( $menu_item_id, 'single_menu_item_border_separator_width' ); ?>" placeholder="1px" />
                                </label>
                                
                                <label>
                                    <select name="options[single_menu_item_border_separator_type]">
                                        <?php $single_menu_item_border_separator_type =  wpmm_get_item_settings($menu_item_id, 'single_menu_item_border_separator_type' ); ?>
                                        <option value="none" <?php selected($single_menu_item_border_separator_type, 'none') ?> > <?php _e('None', 'wp-megamenu'); ?> </option>
                                        <option value="solid" <?php selected($single_menu_item_border_separator_type, 'solid'); ?> > <?php _e('Solid', 'wp-megamenu'); ?> </option>
                                        <option value="dashed" <?php selected($single_menu_item_border_separator_type, 'dashed'); ?> > <?php _e('Dashed', 'wp-megamenu'); ?> </option>
                                        <option value="dotted" <?php selected($single_menu_item_border_separator_type, 'dotted'); ?> > <?php _e('Dotted', 'wp-megamenu'); ?> </option>
                                        <option value="double" <?php selected($single_menu_item_border_separator_type, 'double'); ?> > <?php _e('Double', 'wp-megamenu'); ?> </option>
                                        <option value="groove" <?php selected($single_menu_item_border_separator_type, 'groove'); ?> > <?php _e('Groove', 'wp-megamenu'); ?> </option>
                                        <option value="ridge" <?php selected($single_menu_item_border_separator_type, 'ridge'); ?> > <?php _e('Ridge', 'wp-megamenu'); ?> </option>
                                        <option value="inset" <?php selected($single_menu_item_border_separator_type, 'inset'); ?> > <?php _e('Inset', 'wp-megamenu'); ?> </option>
                                        <option value="outset" <?php selected($single_menu_item_border_separator_type, 'outset'); ?> > <?php _e('Outset', 'wp-megamenu'); ?> </option>
                                    </select>    
                                </label>
                            
                                <label>
                                    <input type="text" name="options[single_menu_item_border_separator_color]" value="<?php echo wpmm_get_item_settings($menu_item_id, 'single_menu_item_border_separator_color'); ?>" class="wpmmColorPicker" data-alpha="true" />
                                </label>

                                <p class="field-description"> <?php _e('Set border separator width and color, ex. <strong>1px solid #dddddd</strong>', 'wp-megamenu'); ?></p>
                            </td>
                        </tr>

                        <!-- Single Item Padding -->
                        <tr class="wpmm-field wpmm-field-group">
                            <td><br><?php _e('Padding', 'wp-megamenu'); ?></td>
                            <td>
                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php _e('Top', 'wp-megamenu'); ?></p>
                                        <?php 
                                            $single_menu_padding_top = wpmm_get_item_settings($menu_item_id,'single_menu_padding_top');
                                            if( ! $single_menu_padding_top){ $single_menu_padding_top = ''; } 
                                        ?>
                                        <input type='text' name='options[single_menu_padding_top]' value="<?php echo $single_menu_padding_top; ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php _e('Right', 'wp-megamenu'); ?></p>
                                        <?php
                                            $single_menu_padding_right = wpmm_get_item_settings($menu_item_id,'single_menu_padding_right');
                                            if( ! $single_menu_padding_right){ $single_menu_padding_right = ''; }
                                        ?>
                                        <input type='text' name='options[single_menu_padding_right]' value="<?php echo $single_menu_padding_right; ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php _e('Bottom', 'wp-megamenu'); ?></p>
                                        <?php
                                            $single_menu_padding_bottom = wpmm_get_item_settings($menu_item_id,'single_menu_padding_bottom');
                                            if( ! $single_menu_padding_bottom){ $single_menu_padding_bottom = '0'; }
                                        ?>
                                        <input type='text' name='options[single_menu_padding_bottom]' value="<?php echo $single_menu_padding_bottom; ?>" placeholder="0px" />
                                    </label>
                                </div> 

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php _e('Left', 'wp-megamenu'); ?></p>
                                        <?php
                                            $single_menu_padding_left = wpmm_get_item_settings($menu_item_id,'single_menu_padding_left');
                                            if( ! $single_menu_padding_left){
                                                $single_menu_padding_left = '';
                                            }
                                        ?>
                                        <input type='text' name='options[single_menu_padding_left]' value="<?php echo $single_menu_padding_left; ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <p class="field-description"><?php _e('Set padding to menu bar.', 'wp-megamenu'); ?></p>
                            </td>
                        </tr>
                    <?php } ?>


                    <?php do_action('menu_item_settings', $menu_item_id); ?>
                    <tr>
                        <td>&af;</td>
                        <td class="wpmm-save-btn"><?php submit_button(); ?></td>
                    </tr>

                </table>

            </form>
        </div>

        <div id="wpmm-icons">

            <div class="wpmm-icons-wrap">
                <div class="wpmm-icons-menu">

                    <div class="wpmm-icons-topbar-left">
                        <ul>
                            <li><a href="#icons-tabs-1"><?php _e('Dashicons', 'wp-megamenu'); ?></a></li>
                            <li><a href="#icons-tabs-2"><?php _e('Font Awesome', 'wp-megamenu'); ?></a></li>
                            <li><a href="#icons-tabs-3"><?php _e('IcoFont', 'wp-megamenu'); ?></a></li>
                            <!-- <li>
                                <a href="#icons-tabs-4">
                                    <?php //_e('Upload Icons', 'wp-megamenu'); ?>
                                </a>
                            </li> -->
                        </ul>
                    </div>

                    <div class="wpmm-icons-topbar-right">
                        <div class="wpmm-icon-search-wrap">
                            <input id="wpmm_icons_search" type="text" value="" placeholder="<?php _e('Search...', 'wp-megamenu'); ?>">
                            <i class="fa fa-search"></i>
                        </div>
                    </div>

                    <div class="clear"></div>
                </div>

                <div class="wpmm-icons-tab-content wpmm-limit-height">

                    <div id="icons-tabs-1">
                        <?php
                            $dashicons = wpmm_dashicons();
                            $current_icon = '';
                            if ( ! empty($get_menu_settings['options']['icon'])){
                                $current_icon = $get_menu_settings['options']['icon'];
                            }
                            echo "<a href='javascript:;' class='wpmm-icons' data-icon='' title=''>&nbsp;</a>";
                            foreach ($dashicons as $dikey => $diname){
                                $selected_icon = ($current_icon == 'dashicons '.$dikey) ? 'wpmm-icon-selected' :'';
                                echo "<a href='javascript:;' class='wpmm-icons {$selected_icon} ' data-icon='dashicons {$dikey}' title='{$diname}'>
                                <i class='dashicons {$dikey}'></i></a>";
                            }
                        ?>
                    </div>

                    <div id="icons-tabs-2">
                        <?php
                            $font_awesome = wpmm_font_awesome();

                            $current_icon = '';
                            if ( ! empty($get_menu_settings['options']['icon'])){
                                $current_icon = $get_menu_settings['options']['icon'];
                            }
                            echo "<a href='javascript:;' class='wpmm-icons' data-icon='' title=''>&nbsp;</a>";
                            foreach ($font_awesome as $dikey => $diname){
                                $selected_icon = ($current_icon == 'fa '.$diname) ? 'wpmm-icon-selected' :'';
                                echo "<a href='javascript:;' class='wpmm-icons {$selected_icon} ' data-icon='fa {$diname}' title='{$diname}'>
                                <i class='fa {$diname}'></i></a>";
                            }
                        ?>
                    </div>

                    <div id="icons-tabs-3">
                        <?php 
                            $icofont = wpmm_icofont();
                            $current_icon = '';
                            if ( ! empty($get_menu_settings['options']['icon'])){
                                $current_icon = $get_menu_settings['options']['icon'];
                            }
                            echo "<a href='javascript:;' class='wpmm-icons' data-icon='' title=''>&nbsp;</a>";
                            foreach ($icofont as $diname) {
                                $selected_icon = ($current_icon == 'icofont-'.$diname) ? 'wpmm-icon-selected' :'';
                                echo "<a href='javascript:;' class='wpmm-icons {$selected_icon} ' data-icon='icofont-{$diname}' title='{$diname}'>
                                <i class='icofont-{$diname}'></i></a>";
                            }
                        ?>
                    </div> <!-- #icons-tabs-3 -->

                </div>
            </div>


        </div>
    </div>

    <div class="clear"></div>
</div>
