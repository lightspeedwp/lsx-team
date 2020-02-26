<?php
/**
 * Functions
 *
 * @package   LSX Team
 * @author    LightSpeed
 * @license   GPL3
 * @link
 * @copyright 2016 LightSpeed
 */

/**
 * Add our action to init to set up our vars first.
 */
function lsx_team_load_plugin_textdomain() {
	load_plugin_textdomain( 'lsx-team', false, basename( LSX_TEAM_PATH ) . '/languages' );
}
add_action( 'init', 'lsx_team_load_plugin_textdomain' );

/**
 * Wraps the output class in a function to be called in templates
 */
function lsx_team( $args ) {
	$lsx_team = new LSX_Team;
	echo wp_kses_post( $lsx_team->output( $args ) );
}

/**
 * Shortcode
 */
function lsx_team_shortcode( $atts ) {
	$lsx_team = new LSX_Team;
	return $lsx_team->output( $atts );
}
add_shortcode( 'lsx_team', 'lsx_team_shortcode' );

/**
 * Creates the feature images sizes for the REST API responses
 *
 * @param [type] $object team.
 * @param [type] $field_name name.
 * @param [type] $request request.
 */
function ws_get_images_urls( $object, $field_name, $request ) {
	$medium     = wp_get_attachment_image_src( get_post_thumbnail_id( $object->id ), 'medium' );
	$medium_url = $medium['0'];

	$large     = wp_get_attachment_image_src( get_post_thumbnail_id( $object->id ), 'large' );
	$large_url = $large['0'];

	return array(
		'medium' => $medium_url,
		'large'  => $large_url,
	);
}

/**
 * Modify REST API responses to get better media urls for the blocks.
 *
 * @return void
 */
function ws_register_images_field() {
	register_rest_field(
		'team',
		'images',
		array(
			'get_callback'    => 'ws_get_images_urls',
			'update_callback' => null,
			'schema'          => null,
		)
	);
}
add_action( 'rest_api_init', 'ws_register_images_field' );
