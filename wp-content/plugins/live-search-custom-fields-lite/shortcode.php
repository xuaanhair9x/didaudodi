<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
/**
 * The plugin shorcode function.
 *
 * @param array $atts Injected by add_shorcode hook.
 * @var function
 */
function lscf_lite_filter_shortcode( $atts ) {

	$active_user = wp_get_current_user();
	$is_administrator = false;

	if ( in_array( 'administrator', $active_user->roles, true ) ) {

		$is_administrator = true;

		$lscf_main_controller = new LscfLitePluginMainController();
		$lscf_main_controller->init_custom_templates();
	}

	if ( isset( LscfLitePluginMainController::$plugin_settings['options'] ) ) {
		$lscf_settings = LscfLitePluginMainController::$plugin_settings['options'];
	} else {
		$lscf_settings = null;
	}

	$shortcode_attributes = shortcode_atts(
		array(
			'fields_ids' 	  				  => '',
			'id'			  				  => '',
			'post_type'		  				  => '',
			'featured_label'  				  => '',
			'only_posts_show' 				  => '',
			'filter_type' 	  				  => '',
			'view_type'		  				  => '',
			'lscf-demo-frontend-editor'		  => '',
			),
		$atts
	);

	$fields_ids = explode( ',', $shortcode_attributes['fields_ids'] );

	$filter_id = $shortcode_attributes['id'];

	$filter_data = get_option( LscfLitePluginMainModel::$meta_name_plugin_settings, true );

	$filter_data = json_decode( $filter_data, true );

	$options = array();

	$writing = array(
		'load_more' => 'Load more',
		'view'		=> 'View',
		'any'		=> 'Any',
		'select'	=> 'Select',
		'filter'	=> 'Filter',
		'see_less'	=> 'See Less',
		'see_more'	=> 'See More',
		'add_to_cart' => 'Add to cart',
	);

	if ( isset( $lscf_settings['writing'] ) ) {
		$writing = $lscf_settings['writing'];
		if ( ! isset( $writing['see_less'] ) ) { $writing['see_less'] = 'See Less'; }
	}

	$options['writing'] = $writing;

	if ( isset( $shortcode_attributes['only_posts_show'] ) && '1' === $shortcode_attributes['only_posts_show'] ) {

		$filter_data = array();

		$filter_data['only_posts_show'] = 1;
		$filter_data['view_type'] = $shortcode_attributes['view_type'];

		$posts_per_page = ( isset( $lscf_settings['posts_per_page']['posts_only'] ) ? (int) $lscf_settings['posts_per_page']['posts_only'] : 16 );

		$theme_settings = ( isset( $filter_data['settings'] ) ? $filter_data['settings'] : array() );

	} else {

		if ( ! isset( $filter_data['filterList'] ) ) {
			return;
		}
		if ( ! isset( $filter_data['filterList'][ $filter_id ] ) ) {
			return;
		}


		$filter_data = $filter_data['filterList'][ $filter_id ];

		$posts_per_page = ( isset( $lscf_settings['posts_per_page']['posts_only'] ) ? (int) $lscf_settings['posts_per_page']['filter'] : 15 );


		$options['reset_button'] = ( isset( $lscf_settings['reset_button'] ) ?  $lscf_settings['reset_button'] : 0 );
		$options['grid_view'] = ( isset( $lscf_settings['block_view'] ) ? (int) $lscf_settings['block_view'] : 0 );
		$options['see_more'] = ( ! isset( $lscf_settings['see_more']['writing'] ) ? 'See More' : sanitize_text_field( $lscf_settings['see_more']['writing'] ) );


		$theme_settings = ( isset( $filter_data['settings'] ) ? $filter_data['settings'] : array() );

		$posts_per_page = ( isset( $theme_settings['posts-per-page'] ) ? (int) $theme_settings['posts-per-page'] : $posts_per_page );

		$theme_settings['is_administrator'] = ( true === $is_administrator || 1 == ( int ) $shortcode_attributes['lscf-demo-frontend-editor'] ? 1 : 0 );

	}



	$options['run_shortcodes'] = ( isset( $filter_data['options']['run_shortcodes'] ) ? (int) $filter_data['options']['run_shortcodes']  : 0 );
	$options['disable_empty_option_on_filtering'] = ( isset( $filter_data['options']['disable_empty_option_on_filtering'] ) ? (int) $filter_data['options']['disable_empty_option_on_filtering'] : 0 );
	$options['order_by'] = ( isset( $filter_data['options']['order_by'] ) ?  $filter_data['options']['order_by']  : array( 'items' => '' ) );

	ob_start();

	?>
	<script type='text/javascript'>
		/* <![CDATA[ */
		var capfData = {
			'ID':'<?php echo esc_attr( $filter_id ); ?>',
			'postType':'<?php echo esc_attr( $shortcode_attributes['post_type'] );?>',
			'post_per_page':'<?php echo (int) $posts_per_page; ?>',
			'plugin_url':'<?php echo esc_url( LSCF_PLUGIN_URL ); ?>',
			'site_url':'<?php echo esc_url( site_url() )?>',
			'ajax_url':'<?php echo esc_url( admin_url( 'admin-ajax.php' ) ) ?>',
			'options':<?php echo wp_json_encode( $options );?>,
			'settings':<?php echo wp_json_encode( $theme_settings ); ?>
		};
	/* ]]> */
	</script>

	<?php
	include_once LSCF_PLUGIN_PATH . '_views/frontend/filter.php';

	return ob_get_clean();
}
add_shortcode( 'px_filter', 'lscf_lite_filter_shortcode' );

add_action( 'wp', 'lscf_init_shortcode_styles' );
/**
 * Init the dynamic style after the shortcode is loaded.
 *
 * @var function
 */
function lscf_init_shortcode_styles() {

	global $post, $lscf_main_controller;

	if ( ! isset( $post->post_content ) ) {
		return;
	}

	if ( preg_match( '/\[px_filter\s*id=\"(.+?)\"(.+?)\]/', $post->post_content, $matches ) ) {

		$filter_id = $matches[1];
		$dynamic_css = $lscf_main_controller->generate_style_dynamic_color_css( $filter_id );

		wp_enqueue_style( 'px_base', LSCF_PLUGIN_URL . 'assets/css/base.css', false );
		wp_add_inline_style( 'px_base', $dynamic_css );

	} else {
		return;
	}
}

