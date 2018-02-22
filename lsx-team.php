<?php
/*
 * Plugin Name: LSX Team
 * Plugin URI:  https://www.lsdev.biz/product/lsx-team/
 * Description: The LSX Team Extension provides a custom post type that allows you to easily show off the people that make up your business.
 * Version:     1.1.1
 * Author:      LightSpeed
 * Author URI:  https://www.lsdev.biz/
 * License:     GPL3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: lsx-team
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'LSX_TEAM_PATH', plugin_dir_path( __FILE__ ) );
define( 'LSX_TEAM_CORE', __FILE__ );
define( 'LSX_TEAM_URL', plugin_dir_url( __FILE__ ) );
define( 'LSX_TEAM_VER', '1.1.1' );

/* ======================= The API Classes ========================= */

if ( ! class_exists( 'LSX_API_Manager' ) ) {
	require_once( 'classes/class-lsx-api-manager.php' );
}

/**
 * Run when the plugin is active, and generate a unique password for the site instance.
 */
function lsx_team_activate_plugin() {
	$lsx_to_password = get_option( 'lsx_api_instance', false );

	if ( false === $lsx_to_password ) {
		update_option( 'lsx_api_instance', LSX_API_Manager::generatePassword() );
	}
}
register_activation_hook( __FILE__, 'lsx_team_activate_plugin' );

/**
 *	Grabs the email and api key from the LSX Currency Settings.
 */
function lsx_team_options_pages_filter( $pages ) {
	$pages[] = 'lsx-settings';
	$pages[] = 'lsx-to-settings';
	return $pages;
}
add_filter( 'lsx_api_manager_options_pages', 'lsx_team_options_pages_filter', 10, 1 );

function lsx_team_api_admin_init() {
	global $lsx_team_api_manager;

	if ( function_exists( 'tour_operator' ) ) {
		$options = get_option( '_lsx-to_settings', false );
	} else {
		$options = get_option( '_lsx_settings', false );

		if ( false === $options ) {
			$options = get_option( '_lsx_lsx-settings', false );
		}
	}

	$data = array(
		'api_key' => '',
		'email'   => '',
	);

	if ( false !== $options && isset( $options['api'] ) ) {
		if ( isset( $options['api']['lsx-team_api_key'] ) && '' !== $options['api']['lsx-team_api_key'] ) {
			$data['api_key'] = $options['api']['lsx-team_api_key'];
		}

		if ( isset( $options['api']['lsx-team_email'] ) && '' !== $options['api']['lsx-team_email'] ) {
			$data['email'] = $options['api']['lsx-team_email'];
		}
	}

	$instance = get_option( 'lsx_api_instance', false );

	if ( false === $instance ) {
		$instance = LSX_API_Manager::generatePassword();
	}

	$api_array = array(
		'product_id' => 'LSX Team',
		'version'    => '1.1.1',
		'instance'   => $instance,
		'email'      => $data['email'],
		'api_key'    => $data['api_key'],
		'file'       => 'lsx-team.php',
	);

	$lsx_team_api_manager = new LSX_API_Manager( $api_array );
}
add_action( 'admin_init', 'lsx_team_api_admin_init' );

/* ======================= Below is the Plugin Class init ========================= */

// Post Type and Custom Fields
require_once( LSX_TEAM_PATH . '/classes/class-lsx-team-admin.php' );

// Frontend resources
require_once( LSX_TEAM_PATH . '/classes/class-lsx-team-frontend.php' );

// Shortcode
require_once( LSX_TEAM_PATH . '/classes/class-lsx-team.php' );

// Widget
require_once( LSX_TEAM_PATH . '/classes/class-lsx-team-widget.php' );

// Template Tag and functions
require_once( LSX_TEAM_PATH . '/includes/functions.php' );

// Post reorder
require_once( LSX_TEAM_PATH . '/includes/class-lsx-team-scpo-engine.php' );
