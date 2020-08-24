<?php 

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if ( ! class_exists( 'PxWpQuery' ) ) {
	include_once LSCF_PLUGIN_PATH . '_helpers/query_helper.php';
}

include_once LSCF_PLUGIN_PATH . '_controllers/main_controller.php';
include_once LSCF_PLUGIN_PATH . '_controllers/import_export_controller.php';
include_once LSCF_PLUGIN_PATH . '_controllers/custom_fields_controller.php';
include_once LSCF_PLUGIN_PATH . '_controllers/http_requests_controller.php';
include_once LSCF_PLUGIN_PATH . 'settings/functions.php';

$lscf_main_controller = new LscfLitePluginMainController();
$lscf_export_controller = new LSCFexportImportController();

add_action( 'admin_init', array( new LscfLiteCustomFieldsController, 'display_custom_fields_meta_box' ) );

add_action( 'admin_menu', 'lscf_lite_plugin_page' );

/**
 * Init plugin admin page
 *
 * @var function
 */
function lscf_lite_plugin_page() {

	global $lscf_icon_url, $lscf_main_controller, $lscf_export_controller;


	add_menu_page( 'LSCF Lite', 'LSCF Lite', 'manage_options', 'pxLF_plugin', array( $lscf_main_controller, 'plugin_frontend_init' ), $lscf_icon_url );
	add_submenu_page( 'pxLF_plugin', 'Export/Import', 'Export/Import', 'manage_options', 'lscf_export', array( $lscf_export_controller, 'init' ) );
}

add_action( 'init', array( $lscf_main_controller, 'generate_custom_post_type' ) );
add_action( 'init', array( $lscf_main_controller, 'plugin_backend_init' ) );
add_action( 'admin_init', array( $lscf_export_controller, 'export_custom_fields' ) );
add_action( 'admin_init', array( $lscf_export_controller, 'export_custom_posts' ) );
add_action( 'admin_init', array( $lscf_export_controller, 'import_custom_posts' ) );
add_action( 'admin_init', array( $lscf_export_controller, 'import_custom_fields' ) );





/**
 * Enqueue styles and script - backend only
 *
 * @var function
 */
function lscf_lite_enqueue_plugin_scripts_snd_styles_backend() {

	 wp_enqueue_script( 'jquery-ui-core' );

	 wp_enqueue_script( 'jquery-ui-widget' );
	 wp_enqueue_script( 'jquery-ui-datepicker' );
	 wp_enqueue_script( 'jquery-ui-draggable' );
	 wp_enqueue_script( 'jquery-ui-droppable' );
	 wp_enqueue_script( 'jquery-ui-mouse' );
	 wp_enqueue_script( 'jquery-ui-sortable' );


	wp_register_script( 'wp_custom_functions', LSCF_PLUGIN_URL . 'assets/js/wp_functions.js', '', '', true );
	wp_enqueue_script( 'wp_custom_functions' );

	wp_register_script( 'px_custom_select_box', LSCF_PLUGIN_URL . 'assets/js/lib/custom-select-box.js', false, 1.0 );
	wp_enqueue_script( 'px_custom_select_box', array( 'jquery' ), '1.0', true );

	wp_register_script( 'px_source', LSCF_PLUGIN_URL . 'assets/source.js', false, 1.1 );
	wp_enqueue_script( 'px_source',  LSCF_PLUGIN_URL . 'assets/source.js', array( 'jquery' ), '1.0', true );
	wp_localize_script( 'px_source', 'adminData', array( 'lscf_url' => LSCF_PLUGIN_URL, 'ajaxURL' => admin_url( 'admin-ajax.php' ) ) );

	wp_register_script( 'px_vendor', LSCF_PLUGIN_URL . 'assets/vendor.js', false, 1.0 );
	wp_enqueue_script( 'px_vendor' );


	wp_register_style( 'px_plugin_style', LSCF_PLUGIN_URL . 'assets/css/wpbackend.css', false, 1.0 );
	wp_enqueue_style( 'px_plugin_style' );
	wp_register_style( 'slick', LSCF_PLUGIN_URL . 'assets/vendor/slick/slick.css', false, 1.0 );
	wp_enqueue_style( 'slick' );
	wp_register_style( 'jquery-ui-style', LSCF_PLUGIN_URL . 'assets/css/jquery-ui.min.css', false, 1.0 );
	wp_enqueue_style( 'jquery-ui-style' );

	wp_enqueue_media();

}

add_action( 'wp_enqueue_scripts', array( $lscf_main_controller, 'px_enqueue_plugin_scripts_and_styles_frontend' ), 11 );
add_action( 'admin_enqueue_scripts', 'lscf_lite_enqueue_plugin_scripts_snd_styles_backend' );

// setting ajax request POST action type.
add_action( 'wp_ajax_nopriv_px-plugin-ajax',  array( $lscf_main_controller, 'px_plugin_lf_ajax_request' ) );
add_action( 'wp_ajax_px-plugin-ajax', array( $lscf_main_controller, 'px_plugin_lf_ajax_request' ) );

// setting ajax request POST action type.
add_action( 'wp_ajax_nopriv_lscf-administrator-ajax',  array( $lscf_main_controller, 'administrator_ajax_requests' ) );
add_action( 'wp_ajax_lscf-administrator-ajax', array( $lscf_main_controller, 'administrator_ajax_requests' ) );


// setting http angular requests.
add_action( 'wp_ajax_nopriv_px-ang-http',  array( new LscfLiteHttpRequestsController, 'init_http_requests' ) );
add_action( 'wp_ajax_px-ang-http', array( new LscfLiteHttpRequestsController, 'init_http_requests' ) );


add_action( 'after_setup_theme', array( $lscf_main_controller, 'theme_supports' ) );


// Single page - fields view.
add_filter( 'the_content', 'lscf_lite_load_custom_fields_view' );

/**
 * Display custom fields values inside Post or Page.
 *
 * @param string $content The Post's content injected by add_filter hook.
 * @var function
 */
function lscf_lite_load_custom_fields_view( $content ) {

	if ( ! is_single() || is_feed() || ! in_the_loop() ) {
		return $content;
	}

	global $post;

	$custom_fields_model = new LscfLiteCustomFieldsModel();

	$data = $custom_fields_model->get_post_custom_fields( $post->ID );

	ob_start();

	include_once LSCF_PLUGIN_PATH . '_views/frontend/custom-fields-single-page.php';

	return $content . ob_get_clean();

}


$lscf_sidebar_args = array(
	'name'          => __( 'LSCF Sidebar', 'lscf_sidebar' ),
	'id'            => 'lscf_custom_sidebar',
	'description'   => '',
	'class'         => 'lscf-custom-sidebar',
	'before_widget' => '<li id="%1$s" class="widget %2$s">',
	'after_widget'  => '</li>',
	'before_title'  => '<h2 class="widgettitle">',
	'after_title'   => '</h2>',
);

register_sidebar( $lscf_sidebar_args );
