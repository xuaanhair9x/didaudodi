<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
/**
 * Class LscfLiteCustomFieldsModel Model for post's custom fields  DB quries
 *
 * @category Model
 * @package  LscfLiteCustomFieldsModel
 * @author   PIXOLETTE
 * @license  http://www.pixollete.com
 * @link     http://www.pixollete.com
 **/
class LscfLiteCustomFieldsModel {

	/**
	 * Post meta_key name for post's custom fields data
	 *
	 * @var string
	 */
	public static $meta_post_custom_fields = 'px-custom_fields';

	/**
	 * Post meta_key name for post's custom fields data
	 *
	 * @param int $post_id The POST ID, used to get post meta data.
	 * @access public
	 * @var function|method
	 */
	public function get_post_custom_fields( $post_id ) {

		$post_custom_fields = get_post_meta( (int) $post_id, self::$meta_post_custom_fields, true );

		$post_custom_fields = str_replace( '\'', '"', lscf_wordpress_add_unicode_slash( $post_custom_fields ) );
		$data = json_decode( $post_custom_fields, true );


		if ( ! empty( $data ) ) {

			$grouped_data = array();

			if ( is_array( $data ) ) {

				foreach ( $data as $id => $field ) :

					if ( ! isset( $field['field_type'] ) ) { continue; }

					switch ( $field['field_type'] ) {

						case 'px_icon_check_box':
						case 'px_check_box':
						case 'px_cf_relationship':

							$grouped_data['multiple_values'][] = $field;

						break;

						case 'px_text':
						case 'px_date':
						case 'px_radio_box':
						case 'px_select_box':


							$grouped_data['single_value'][] = $field;

						break;
					}

				endforeach;

			}

			if (  ( isset( $grouped_data['multiple_values'] ) && count( $grouped_data['multiple_values'] ) > 0 ) || ( isset( $grouped_data['single_value'] ) && count( $grouped_data['single_value'] ) > 0 ) ) {

				$single_values_group = ( isset( $grouped_data['single_value'] ) ? $grouped_data['single_value'] : '' );
				$multiples_values_group = ( isset( $grouped_data['multiple_values'] ) ? $grouped_data['multiple_values'] : '' );

				return array( 'single_value' => $single_values_group, 'multiple_values' => $multiples_values_group );
			}
		}

		return false;
	}

	/**
	 * Update the POST's custom fields values.
	 *
	 * @param int 	$post_id The POST ID, used to update post meta data.
	 * @param array $data The Post's custom fields values.
	 * @access public
	 * @var function|method
	 */
	public function update_posts_custom_fields( $post_id, $data ) {

		$custom_fields = json_decode( lscf_wordpress_add_unicode_slash( $data ) );

		foreach ( $custom_fields as $key => $custom_field ) {

			if ( ! 'px_text' == $custom_field->field_type ) { continue; }
			update_post_meta( (int) $post_id, $key, $custom_field->value );
		}

		if ( update_post_meta( (int) $post_id, self::$meta_post_custom_fields, $data ) ) {

			return true;
		}

		return false;

	}

}
