<?php


if ( ! function_exists( 'lscf_lite_set_as_active' ) ) {

	/**
	 * Set Menu Nav as Active
	 *
	 * @param string $active_nav The current nav that should be active.
	 * @param string $nav_name The nav name.
	 * @param string $active_class_name The name of active class.
	 * @var function
	 */
	function lscf_lite_set_as_active( $active_nav, $nav_name, $active_class_name ) {

		$active_nav_names = explode( '|',  $nav_name );

		if ( in_array( $active_nav, $active_nav_names ) ) {
			echo esc_attr( $active_class_name );
		}

	}
}


/**
 * Add double slashes to Mysql regex special characters
 *
 * @param string $string Regex values that needs to be escaped.
 * @var function
 */
function lscf_sql_regex_escape( $string ) {

	$string = preg_replace( '/([\!\@\#\$\%\^\&\*\(\)\+\=\[\]\\\;\,\.\/\{\}\|\"\:\<\>\?\~\_\'\-])/', '\\_|_\\\$1', $string );

	return str_replace( '_|_', '', $string );
}

/**
 * Generate a random string by given length
 *
 * @param int $length The length of generated string.
 * @var function
 */
function lscf_lite_get_random_string( $length ) {

	$letters = array(
		'a',
		'b',
		'c',
		'd',
		'e',
		'f',
		'g',
		'h',
		'i',
		'j',
		'k',
		'l',
		'm',
		'n',
		'o',
		'p',
		'r',
		's',
		't',
		'u',
		'v',
		'w',
		'q',
		'y',
		'x',
		'z',
	);

	$word = '';

	for ( $i = 0; $i < $length; $i++ ) {

		$random = rand( 0, 25 );

		$word = $word . $letters[ $random ];
	};

	return $word;

}

/**
 * Return an array with all parents IDs hierarchical
 *
 * @param array $categories_object The wordpress categories list.
 * @var function
 */
function lscf_wp_return_parent_cat_ids( $categories_object, $current_category_parent_id ) {

	$parent_id = $current_category_parent_id;

	$parent_ids = array();

	$hierachical_ordered = array();

	if ( 0 != $parent_id ) {

		$parent_ids[] = $parent_id;

	}


	while ( 0 != $parent_id ) {

		$parent_id = $categories_object[ $parent_id ]->parent;

		$parent_ids[] = $parent_id;

	}

	for (  $i = ( count( $parent_ids ) - 2 ); $i >= 0; $i-- ) {

		$hierachical_ordered[] = $parent_ids[ $i ];

	}

	return $hierachical_ordered;

}

/**
 * Change caregories array key to category ID
 *
 * @param string $categories Categories array.
 * @var function
 */
function lscf_wp_reset_cat_key_to_id( $categories ) {

	$data = array();

	foreach ( $categories as $category ) {
		$data[ $category->term_id ] = $category;
	}

	return $data;

}

/**
 * Generate a random string by given length
 *
 * @param string $string The string that needs to be sanitized.
 * @var function
 */
function lscf_lite_sanitize( $string ) {
	$string = mb_strtolower( preg_replace( "/([\!\@\#\$\%\^\&\*\(\[\)\]\{\-\}\\ \/\:\;\+\=\.\<\,\>\?\~\`\'\"]+)/", '_', $string ) );
	return preg_replace( "/_$/", '', $string );
}

/**
 * Return filter's viewchanger class
 *
 * @param array $viewchanger_params Viewchange views type.
 * @var function
 */
function lscf_return_viewchanger_class( $viewchanger_params ) {

	$grid = ( isset( $viewchanger_params['grid'] ) ? (int) $viewchanger_params['grid'] : 0 );
	$list = ( isset( $viewchanger_params['list'] ) ? (int) $viewchanger_params['list'] : 0 );

	if ( 1 == $grid && 0 == $list ) {
		return 'pxfilter-grid-only';
	};

	if ( 0 == $grid && 1 == $list ) {
		return 'pxfilter-list-only';
	};

	return;
}

/**
 * Get the hierarchy parents of the term. Starts with imediate parent to main parent(parent=0)
 *
 * @param array $term_ids The terms array("term_id"=>"parent_id").
 * @var function
 */
function lscf_group_terms_by_parent( $term_ids ) {

	$results = array();

	foreach ( $term_ids as $term_id => $parent_id ) {

		if ( 0 == $parent_id ) {
			continue;
		}

		$parent = $parent_id;
		$results[ $term_id ][] = $parent_id;

		while ( 0 != $parent ) {

			if ( ! isset( $term_ids[ $parent ] ) || 0 == $term_ids[ $parent ] ) {
				break;
			}
			$parent = $term_ids[ $parent ];
			$results[ $term_id ][] = $parent;
		}
	}

	return $results;

}

/**
 * Sort create a hierarchy dependency on wp terms
 *
 * @param multidimensional array $terms_ids An array of terms.
 * @var function
 */
function lscf_hierarchy_terms( $terms_ids ) {

	$results = array();

	foreach ( $terms_ids as $term_id => $parent_id  ) {

		if ( 0 == $parent_id ) {
			$results[ $term_id ] = array(
				'subcategs' => array(),
				'parent'	=> 0,
				);
		} else {

			if ( ! in_array( $term_id, $results ) ) {

				$results[ $term_id ] = array(
					'subcategs' => array(),
					'parent'	=> $parent_id,
				);
			} else {
				$results[ $term_id ]['parent'] = $parent_id;
			}



			if ( isset( $results[ $parent_id ] ) ) {

				$results[ $parent_id ]['subcategs'][] = $term_id;

				$parent = $parent_id;

				while ( 0 != (int) $parent ) {

					$parent = $results[ $parent ]['parent'];

					if ( 0 != $parent ) {
						$results[ $parent ]['subcategs'][] = $term_id;
					}
				}
			} else {

				$parent = $terms_ids[ $parent_id ];

				$results[ $parent_id ] = array(
					'subcategs' => array( $term_id ),
					'parent'	=> $parent,
				);

				while ( 0 != $parent ) {

					$results[ $parent ]['subcategs'][] = $term_id;
					$results[ $parent ]['parent'][] = $term_ids[ $parent ];

					$parent = $term_ids[ $parent ];
				}
			}
		}
	}

	return $results;
}


function lscf_wordpress_escape_unicode_slash( $string ) {
	return preg_replace( '/(u[0-9a-fA-F]{4})/i', '#lscf-slash#$1', $string );
};
function lscf_wordpress_add_unicode_slash( $string ){
	return preg_replace( '/#lscf-slash#(u[0-9a-fA-F]{4})/i', '\\\$1', $string );
}