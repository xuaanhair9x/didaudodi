<?php 

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
/**
 * Class PxWpQuery A Helper Class that extends WP_QUERY
 *
 * @category Helper
 * @package  PxWpQuery
 * @author   PIXOLETTE
 * @license  http://www.pixollete.com
 * @link     http://www.pixollete.com
 **/
class PxWpQuery extends WP_Query {

	/**
	 * Custom fields meta key name.
	 *
	 * @access public
	 * @var string
	 */
	public $lscf_postmeta = 'pxpostmeta';

	/**
	 * WooCommerce price postmeta alias.
	 *
	 * @access public
	 * @var string
	 */
	public $woo_price_postmeta = 'woo_price';

	/**
	 * WooCommerce stock inventory postmeta alias.
	 *
	 * @access public
	 * @var string
	 */
	public $woo_stock_inventory_postmeta = 'woo_stock_inventory';

	/**
	 * Array of custom fields by which the search would be make
	 *
	 * @access public
	 * @var array
	 */
	public $px_custom_fields = array();

	/**
	 * The post meta data by which the search will be make
	 *
	 * @access public
	 * @var array
	 */
	public $px_custom_post_meta = array();

	/**
	 * Array of custom fields by which the search would be made
	 *
	 * @param array $args The args data for PxWpQuery and WP_QUERY.
	 * @var function|Class constructor
	 */
	function __construct( $args, $called = false ) {

		if ( isset( $args['px_postmeta'] ) ) {

			$this->px_custom_post_meta = $args['px_postmeta'];

			add_action( 'posts_join', array( $this, 'px_join_postmeta' ) );

		}

		if ( isset( $args['px_custom_fields'] ) && false === $called ) {

			$this->px_custom_fields = $args['px_custom_fields'];

			$custom_fields = $args['px_custom_fields'];

			foreach ( $custom_fields as $custom_field ) {

				if ( 'woocommerce-instock' == $custom_field->ID ) {

					add_action( 'posts_join', array( $this, 'woo_postmeta_join' ) );

					continue;
				}

				if ( 'px-woocommerce-price' == $custom_field->ID ) {
					add_action( 'posts_join', array( $this, 'woo_price_postmeta_join' ) );
					continue;
				}

				if ( 'px-woocommerce-inventory' == $custom_field->ID ) {
					add_action( 'posts_join', array( $this, 'woo_stock_inventory_postmeta_join' ) );
					continue;
				}
			}

			add_action( 'posts_where', array( $this, 'px_cap_search_fields' ) );
			add_action( 'posts_join', array( $this, 'px_join_postmeta' ) );

		}

		parent::__construct( $args );

		if ( isset( $args['px_postmeta'] ) ) {

			$this->px_get_posts();
			return $this->posts;

		}
	}

	/**
	 * Makes a Join on post_meta and posts
	 * Loaded via add_action 'posts_join'
	 *
	 * @param string $join A param passed by add_action 'posts_join' hook.
	 * @access public
	 * @var function|Class method
	 */
	public function px_join_postmeta( $join ) {

		global $wpdb;

		$join .= " LEFT JOIN $wpdb->postmeta as $this->lscf_postmeta ON $wpdb->posts.ID = $this->lscf_postmeta.post_id";

		return $join;
	}

	/**
	 * Makes a Join on post_meta and posts
	 * Loaded via add_action 'posts_join'
	 *
	 * @param string $join A param passed by add_action 'posts_join' hook.
	 * @access public
	 * @var function|Class method
	 */
	public function woo_price_postmeta_join( $join ) {

		global $wpdb;

		$join .= " LEFT JOIN $wpdb->postmeta as $this->woo_price_postmeta ON $wpdb->posts.ID = $this->woo_price_postmeta.post_id";

		return $join;
	}

	/**
	 * Makes a Join on post_meta and posts
	 * Loaded via add_action 'posts_join'
	 *
	 * @param string $join A param passed by add_action 'posts_join' hook.
	 * @access public
	 * @var function|Class method
	 */
	public function woo_stock_inventory_postmeta_join( $join ) {

		global $wpdb;

		$join .= " LEFT JOIN $wpdb->postmeta as $this->woo_stock_inventory_postmeta ON $wpdb->posts.ID = $this->woo_stock_inventory_postmeta.post_id";

		return $join;
	}


	/**
	 * Makes a Join on post_meta and posts for WooCommerce
	 * Loaded via add_action 'posts_join'
	 *
	 * @param string $join A param passed by add_action 'posts_join' hook.
	 * @access public
	 * @var function|Class method
	 */
	public function woo_postmeta_join( $join ) {

		global $wpdb;

		$join .= " LEFT JOIN $wpdb->postmeta woo_postmeta ON $wpdb->posts.ID = woo_postmeta.post_id ";

		return $join;
	}

	/**
	 * Search and Filter the custom DB query.
	 * Loaded via add_action 'posts_where'
	 * Search by custom Fields
	 *
	 * @param string $where A param passed by add_action 'posts_where' hook.
	 * @access public
	 * @var function|Class method
	 */
	public function px_cap_search_fields( $where ) {

		global $wpdb;

		$has_custom_fields = false;
		$has_range_fields = false;
		$range_where = '';
		$has_di_fields = false;
		$has_taxonomies = false;
		$sort_by_meta_value = false;

		$px_where = '';
		$regex = array();

		$regex_or_operator = array();

		$custom_fields = $this->query_vars['px_custom_fields'];

		foreach ( $custom_fields as $custom_field ) {

			$type = ( isset( $custom_field->filter_as ) ? $custom_field->filter_as : $custom_field->type );

			switch ( $type ) {

				case 'date-interval':

					$has_di_fields = true;

					break;

				case 'main-search':

					if ( 'ajax-product-sku-search' == $custom_field->ID && '' != $custom_field->value ) {

						$px_where .= " AND ( $this->lscf_postmeta.meta_key='_sku' AND $this->lscf_postmeta.meta_value LIKE '%" . sanitize_text_field( wp_unslash( $custom_field->value ) ) . "%' )";

					}


					break;

				case 'range':

					$has_range_field = true;

					if ( 'px-woocommerce-price' == $custom_field->ID ) {

						$px_where .= " AND ( $this->woo_price_postmeta.meta_key='_price' AND ( $this->woo_price_postmeta.meta_value >= " . (int) $custom_field->value->min . " AND $this->woo_price_postmeta.meta_value <= " . (int) $custom_field->value->max . ' ) )';

					}

					if ( 'px-woocommerce-inventory' == $custom_field->ID ) {
						$px_where .= "AND ( $this->woo_stock_inventory_postmeta.meta_key='_stock' AND ( $this->woo_stock_inventory_postmeta.meta_value >= " . (int) $custom_field->value->min . " AND $this->woo_stock_inventory_postmeta.meta_value <= " . (int) $custom_field->value->max . ' ) )';
					}

					break;

				case 'checkbox_post_terms':

					$has_taxonomies = true;

					break;

			}


			if ( 'range' == $type || 'date-interval' == $type || 'checkbox_post_terms' == $type || 'main-search' == $type || 'order-posts' == $type || 'default_filter' == $type ) {

				if ( preg_match( '/(.+?)__pxid_(.+?)_([0-9]+)/', $custom_field->value ) && 'order-posts' == $type ) {
					$sort_by_meta_value = true;
				}

				if ( 'default_filter' == $type ) {
					$has_taxonomies = true;
				}

				continue;
			}


			$id = esc_sql( lscf_wordpress_escape_unicode_slash( preg_replace( '/^"|"$/', '',  json_encode( $custom_field->ID ) ) ) );

			if ( 'woocommerce-instock' == $id ) {

				if ( ! is_array( $custom_field->value ) ) {

					$value = sanitize_text_field( wp_unslash( $custom_field->value ) );

					$px_where .= " AND woo_postmeta.meta_key = '_stock_status' AND woo_postmeta.meta_value='$value'";

					continue;
				}
			}

			if ( ! is_array( $custom_field->value ) ) {

				$value = sanitize_text_field( wp_unslash( preg_replace( '/^"|"$/', '',  json_encode( $custom_field->value ) ) ) );

				$regex_value = lscf_sql_regex_escape( lscf_wordpress_escape_unicode_slash( $value ) );

				$regex[] = "\"$id\s*\":\s*\{[^>]*\s*\"value\"\s*:\s*\"$regex_value\s*\"\s*[^>]+\"field_type";

				$has_custom_fields = true;

			} else {

				$regex_val = '';
				$c = 0;

				$value = $custom_field->value;
				foreach ( $value as $val ) {

					if ( ( count( $value ) - 1 ) == $c ) {
						$regex_val .= lscf_sql_regex_escape( lscf_wordpress_escape_unicode_slash( sanitize_text_field( wp_unslash( preg_replace( '/^"|"$/', '',  json_encode( $val ) ) ) ) ) );
					} else {
						$regex_val .= lscf_sql_regex_escape( lscf_wordpress_escape_unicode_slash( sanitize_text_field( wp_unslash( preg_replace( '/^"|"$/', '',  json_encode( $val ) ) ) ) ) ) . '|';
					}


					$c++;
				}


				if ( 'px_icon_check_box' == $type  ) {

					$regex[] = "\"$id\s*\":\s*\{[^>]*\s*\"value\"\s*:\s*[^>]*\"\s*($regex_val)\s*\"\s*[^>]*,\"ivalue";

				} else {

					$regex[] = "\"$id\s*\":\s*\{[^>]*\s*\"value\"\s*:\s*[^>]*\"\s*($regex_val)\s*\"\s*[^>]*,\"field_type";

				}

				$has_custom_fields = true;
			}
		}


		if ( true === $has_taxonomies && false === $has_range_fields && false === $has_custom_fields ) {

			return $where;

		} elseif ( true === $has_range_fields  && false === $has_taxonomies && false === $has_custom_fields ) {


			return $where . " GROUP BY $wpdb->posts.ID";

		}

		if ( true === $has_custom_fields ) {

			$px_where .= " AND $this->lscf_postmeta.meta_key = 'px-custom_fields' ";

			foreach ( $regex as $reg_f ) {

				$px_where .= " AND $this->lscf_postmeta.meta_value REGEXP '$reg_f'";

			}
		}

		if ( false === $has_taxonomies && false === $sort_by_meta_value ) {

			return $where . $px_where . " GROUP BY $wpdb->posts.ID";

		} else {

			return $where . $px_where;

		}

	}


	/**
	 * Search by custom post meta.
	 * Loaded via add_action 'posts_where'
	 *
	 * @param string $where A param passed by add_action 'posts_where' hook.
	 * @access public
	 * @var function|Class method
	 */
	public function px_custom_postmeta( $where ) {

		global $wpdb;

		$in_clause = '';
		$count = 0;

		foreach ( $this->px_custom_post_meta as $meta_data ) {

			if ( count( $this->px_custom_post_meta ) !== ( $count + 1 ) ) {

				$in_clause .= "'" . esc_sql( $meta_data['key'] ) . "', ";

			} else {
				$in_clause .= "'" . esc_sql( $meta_data['key'] ) . "'";
			}


			$count++;
		}

		$where .= " AND $wpdb->postmeta.meta_key IN ($in_clause)";

		return $where;

	}

	/**
	 * Get posts and show all post_meta data into results
	 * Return array of posts and post_meta values for each post
	 *
	 * @access public
	 * @var function|Class method
	 */
	public function px_get_posts() {

		global $wpdb;

		$request = preg_replace( '/^select/i', "SELECT $wpdb->postmeta.meta_key, $wpdb->postmeta.meta_value, ", $this->request );

		$data_posts = array();

		$posts = wp_cache_get( md5( $request ), 'px_custom_query' );

		if ( false === $posts ) {

			$posts = $wpdb->get_results( $request );
			wp_cache_add( md5( $request ), 'px_custom_query' );
		}

		foreach ( $posts as $post ) {

			$data_posts[ $post->ID ]['postmeta'][ $post->meta_key ] = $post->meta_value;
			$data_posts[ $post->ID ]['data'] = $post;
		}

		$this->posts = array_values( $data_posts );

	}
}
