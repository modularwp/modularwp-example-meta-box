<?php
/*
Plugin Name: ModularWP Example Meta Box
Plugin URI: http://modularwp.com/
Description: Simple example of a WordPress post meta box
Version: 1.0
Author: Alex Mansfield
Author URI: http://modularwp.com/
License: GPLv2 or later
Text Domain: examplemetabox
*/


/**
 * Adds a meta box to the post editing screen
 */
function mdlrwp_example_meta_box() {
    add_meta_box( 'mdlrwp_example_meta_box', __( 'Example Meta Box', 'examplemetabox' ), 'mdlrwp_example_meta_box_callback', 'post' );
}
add_action( 'add_meta_boxes', 'mdlrwp_example_meta_box' );


/**
 * Outputs the content of the meta box
 */
function mdlrwp_example_meta_box_callback( $post ) {
	wp_nonce_field( basename( __FILE__ ), 'mdlrwp_nonce' );
	$mdlrwp_stored_meta = get_post_meta( $post->ID );
	?>

	<p>
		<label for="meta-text" class="prfx-row-title"><?php _e( 'Example Text Input', 'examplemetabox' )?></label>
		<input type="text" name="meta-text" id="meta-text" value="<?php if ( isset ( $mdlrwp_stored_meta['meta-text'] ) ) echo $mdlrwp_stored_meta['meta-text'][0]; ?>" />
	</p>

	<?php
}
/**
 * Saves the custom meta input
 */
function mdlrwp_example_meta_box_save( $post_id ) {

	// Checks save status
	$is_autosave = wp_is_post_autosave( $post_id );
	$is_revision = wp_is_post_revision( $post_id );
	$is_valid_nonce = ( isset( $_POST[ 'mdlrwp_nonce' ] ) && wp_verify_nonce( $_POST[ 'mdlrwp_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';

	// Exits script depending on save status
	if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
		return;
	}

	// Checks for input and sanitizes/saves if needed
	if( isset( $_POST[ 'meta-text' ] ) ) {
		update_post_meta( $post_id, 'meta-text', sanitize_text_field( $_POST[ 'meta-text' ] ) );
	}
}
add_action( 'save_post', 'mdlrwp_example_meta_box_save' );
