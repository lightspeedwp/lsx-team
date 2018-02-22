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
