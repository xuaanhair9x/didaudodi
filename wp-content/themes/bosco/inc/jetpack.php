<?php
/**
 * Jetpack Compatibility File
 * See: http://jetpack.me/
 *
 * @package bosco
 */

/**
 * Add support for Jetpack features.
 */
function bosco_jetpack_setup() {
	/**
	 * Add theme support for Infinite Scroll.
	 * See: http://jetpack.me/support/infinite-scroll/
	 */
	add_theme_support( 'infinite-scroll', array(
		'container'      => 'main',
		'footer'         => 'page',
		'footer_widgets' => array(
			'sidebar-1',
			'sidebar-2',
			'sidebar-3',
		),
	) );

	/**
	 * Add theme support for Responsive Videos.
	 */
	add_theme_support( 'jetpack-responsive-videos' );
}
add_action( 'after_setup_theme', 'bosco_jetpack_setup' );
