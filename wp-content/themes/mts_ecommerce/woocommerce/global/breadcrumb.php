<?php
/**
 * Shop breadcrumb
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.0
 * @see         woocommerce_breadcrumb()
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( $breadcrumb ) {

	echo $wrap_before;

	foreach ( $breadcrumb as $key => $crumb ) {

		echo $before;

		if ( ! empty( $crumb[1] ) && sizeof( $breadcrumb ) !== $key + 1 ) {
			echo '<span itemprop="itemListElement" itemscope
					itemtype="https://schema.org/ListItem"><a href="' . esc_url( $crumb[1] ) . '" itemprop="item"><span itemprop="name">' . esc_html( $crumb[0] ) . '</span><meta itemprop="position" content="' . ( (int) $key + 1 ) . '" /></a></span></span>';
		} else {
			echo '<span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
			echo '<span itemprop="name">' . $crumb[0] . '</span>';
			echo '<meta itemprop="position" content="' . sizeof( $breadcrumb ) . '" />';
			echo '</span>';
			//echo esc_html( $crumb[0] );
		}

		echo $after;

		if ( sizeof( $breadcrumb ) !== $key + 1 ) {
			echo $delimiter;
		}

	}

	echo $wrap_after;

}
