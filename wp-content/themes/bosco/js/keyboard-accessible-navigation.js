/**
 * Makes the main navigation keyboard accessible.
 */

jQuery( document ).ready( function( $ ) {
	$( '.main-navigation' ).find( 'a' ).on( 'focus.bosco blur.bosco', function() {
		$( this ).parents().toggleClass( 'focus' );
	} );
} );
