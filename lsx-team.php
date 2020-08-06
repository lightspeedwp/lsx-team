<?php
/*
 * Plugin Name: LSX Team
 * Plugin URI:  https://www.lsdev.biz/product/lsx-team/
 * Description: The LSX Team extension provides a custom post type that allows you to easily show off the people that make up your business.
 * Version:     1.2.3
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
define( 'LSX_TEAM_VER', '1.2.3' );


/* ======================= Below is the Plugin Class init ========================= */

require_once LSX_TEAM_PATH . '/classes/class-lsx-team-core.php';

// Post Type and Custom Fields.
require_once LSX_TEAM_PATH . '/classes/class-lsx-team-admin.php';

// Frontend resources.
require_once LSX_TEAM_PATH . '/classes/class-lsx-team-frontend.php';

// Shortcode.
require_once LSX_TEAM_PATH . '/classes/class-lsx-team.php';

// Widget.
require_once LSX_TEAM_PATH . '/classes/class-lsx-team-widget.php';

// Template Tag and functions.
require_once LSX_TEAM_PATH . '/includes/functions.php';

// Post reorder.
require_once LSX_TEAM_PATH . '/includes/class-lsx-team-scpo-engine.php';
