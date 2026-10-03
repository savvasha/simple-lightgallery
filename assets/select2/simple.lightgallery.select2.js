/* global jQuery */
jQuery( document ).ready( function( $ ) {
	$( '#lightgallery_post_types' ).select2( { width: '200px' } );
	$( '#lightgallery_taxonomies' ).select2( { width: '200px' } );
	$( '#lightgallery_selectors' ).select2( { width: '200px', tags: true } );
} );
