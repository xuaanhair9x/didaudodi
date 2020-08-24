<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

include_once LSCF_PLUGIN_PATH . '_models/main_model.php';
include LSCF_PLUGIN_PATH . '_models/custom_fields_model.php';

/**
 * Class LscfLiteCustomFieldsController controller for post's custom fields
 *
 * @category Controller
 * @package  LscfLiteCustomFieldsController
 * @author   PIXOLETTE
 * @license  http://www.pixollete.com
 * @link     http://www.pixollete.com
 **/
class LscfLiteCustomFieldsController {

	/**
	 * Model of custom fields db queries
	 *
	 * Methods:
	 *		get_post_custom_fields(postid = int),
	 *		update_post_custom_fields(postid, data)
	 *
	 * @var Class|Object
	 */
	public $model;


	/**
	 * Model of main db queries. Plugin's Main Model
	 *
	 * Methods:
	 *		update_plugin_settings,
	 *		get_post_categories,
	 *		get_post_type_custom_fields,
	 *		fetch_posts_type_list,
	 *		update_custom_fields_options
	 *
	 * @var Class|Object
	 */
	public $main_model;

	/**
	 * The Post ID. Used in Models to get postmeta data
	 *
	 * @access protected
	 * @var Integer
	 */
	protected $post_id;

	/**
	 * Post's processed custom fields data.
	 *
	 * @access public
	 * @var array
	 */
	public $post_custom_fields_data = array();

	/**
	 * Constructor function. Load the LscfLiteCustomFieldsModel and init the Wordpress action hooks
	 *
	 * @param int $id post id.
	 * @var function constructor
	 */
	function __construct( $id = null ) {

		$this->post_id = $id;


		$this->model = new LscfLiteCustomFieldsModel();

		$this->init_hooks();
	}

	/**
	 * Initialize the Wordpress action hooks.
	 *
	 * Hooks:
	 *		save_post
	 *
	 * @access public
	 * @var function
	 */
	public function init_hooks() {

		add_action( 'save_post', array( __CLASS__, 'save_custom_fields' ) );
	}

	/**
	 * Load the custom fields to page or post section if available.
	 *
	 * @access public
	 * @var function
	 */
	public function display_custom_fields_meta_box() {

		global $pagenow;

		$model = $this->model;

		$main_model = new LscfLitePluginMainModel();

		$this->main_model = $main_model;

		$data_fields = $main_model->data_option;


		if ( false === $data_fields || ! is_array( $data_fields ) ) {
			return;
		};

		if ( 'post.php' === $pagenow || 'post-new.php' === $pagenow ) {

			$active_posts_type = array();

			foreach ( $data_fields as $post_type => $data ) {

				$active_posts_type[] = $post_type;

			}

			switch ( $pagenow ) {

				case 'post.php':

					$post_id = ( isset( $_GET['post'] ) ? (int) $_GET['post'] : 0 );

					$this->post_id = $post_id;

					$post_type = get_post_type( $post_id );

					break;

				case 'post-new.php':

					if ( isset( $_GET['post_type'] ) ) {
						$post_type = sanitize_key( wp_unslash( @$_GET['post_type'] ) );
					}

				break;

			}

			if ( isset( $post_type ) && in_array( $post_type, $active_posts_type, true ) ) {

				$post_fields_data = $model->get_post_custom_fields( $this->post_id );

				$data_fields = $data_fields[ $post_type ];

				if ( false !== $post_fields_data ) {
					foreach ( $data_fields as $key => $fields ) {


						foreach ( $fields as $array_key => $field ) {

							foreach ( $post_fields_data as $group_fields ) {

								if ( ! is_array( $group_fields ) ) {
									continue;
								}

								foreach ( $group_fields as $single_field ) {
									if ( $single_field['ID'] === $array_key ) {

										if ( isset( $single_field['value'] ) ) {
											$data_fields[ $key ][ $array_key ]['dataValue'] = $single_field['value'];
										};
										if ( isset( $single_field['data'] ) ) {
											$data_fields[ $key ][ $array_key ]['data'] = $single_field['data'];
										}
										if ( isset( $single_field['post-display'] ) ) {
											$data_fields[ $key ][ $array_key ]['post-display'] = (int) $single_field['post-display'];
										}
									}
								}
							}
						}
					}
				}

				$this->post_custom_fields_data = $data_fields;

				add_meta_box( 'lscf-custom-fields', 'LSCF Custom Fields', array( $this, 'display_custom_fields' ), $post_type, 'normal', 'low' );

			}
		}
	}
	/**
	 * Display custom fields inside each post or page from wp-backend, if available...
	 *
	 * @access public
	 * @var function
	 */
	public function display_custom_fields() {

		$model = $this->model;
		$post_id = $this->post_id;
		$fields_data = $this->post_custom_fields_data;

		$post_fields = ( null !== $post_id  ? $model->get_post_custom_fields( $post_id ) : null );

		include LSCF_PLUGIN_PATH . '_views/backend/post-customFields.php' ;

	}

	/**
	 * Save Post's Custom Fields Values into DB
	 *
	 * @access public
	 * @var function
	 */
	public static function save_custom_fields() {

		if ( isset( $_POST['save_px_post_fields'] ) ) {

			$model = new LscfLiteCustomFieldsModel();

			$fields = array();

			$count = 0;

			foreach ( $_POST as $key => $value ) :

				if ( preg_match( '/(.+?)__(pxid_[0-9a-z]+_[0-9]{1,3})_-_px-opt_(.+)$/i', $key, $matches ) ) {

					$opt_slug = sanitize_text_field( $matches[3] );

					$id = explode( '_-_', $key );
					$id = array_shift( $id );
					$val_key = $id . '___pxopt-icon_' . $opt_slug;

					$icon_url = '';

					if ( isset( $_POST[ $val_key ] ) ) {
						$icon_url = sanitize_text_field( wp_unslash( $_POST[ $val_key ] ) );
					}

					$value = sanitize_text_field( wp_unslash( $_POST[ $key ] ) );

					$fields[ $id ]['value'][] = $value;
					$fields[ $id ]['ivalue'][] = array(
						'opt' => $value,
						'icon' => $icon_url,
					);

					$count++;

				}

				if ( preg_match( '/(.+?)__(pxid_[0-9a-z]+_[0-9]{1,3})___([a-z_]+?)_-_name$/i', $key, $matches ) ) {

					$id = explode( '___', $key );
					$id = array_shift( $id );

					$value_key = str_replace( '_-_name', '', $key );

					$post_display = ( isset( $_POST[ $value_key . '_-_display' ] ) ? (int) $_POST[ $value_key .'_-_display' ] : 0 );

					if ( 'px_icon_check_box' != $matches[3] ) {

						if ( ! is_array( $_POST[ $value_key ] ) ) {

							$fields[ $id ]['value'] =  wp_unslash( $_POST[ $value_key ] );

						} else {
							foreach ( $_POST[ $value_key ] as $option ) {
								$fields[ $id ]['value'][] = sanitize_text_field( wp_unslash( $option ) );
							}
						}
					}

					$fields[ $id ]['name'] = $value;
					$fields[ $id ]['ID'] = sanitize_text_field( $id );
					$fields[ $id ]['field_type'] = sanitize_text_field( $matches[3] );
					$fields[ $id ]['post-display'] = $post_display;
					$count++;
				}

			endforeach;

			$data = lscf_wordpress_escape_unicode_slash( wp_json_encode( wp_slash( $fields ) ) );

			$model->update_posts_custom_fields( (int) $_POST['post_ID'], $data );

		}
	}
}
