<?php
/*
 * Plugin Name: LSX Team
 * Plugin URI:  https://lsx.design/product/lsx-team/
 * Description: The LSX Team extension is a team post type plugin using blocks and block templates, which allow you to easily show off the people that make up your business.
 * Version:     1.4
 * Requires at least: 6.7-beta1
 * Requires PHP:      8.0
 * Author:      LightSpeed
 * Author URI:  https://www.lightspeedwp.agency/
 * License:     GPL3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: lsx-team
 * Domain Path: /languages
 */




declare( strict_types = 1 );

define( 'LSX_TEAM_PLUGIN_FILE', __FILE__ );
define( 'LSX_TEAM_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'LSX_TEAM_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

if ( ! function_exists( 'lsx_team_require_if_exists' ) ) {
    /**
     * Requires a file if it exists.
     *
     * @param string $file The file to require.
     */
    function lsx_team_require_if_exists( string $file ) {
        if ( file_exists( $file ) ) {
            require_once $file;
        }
    }
}


/**
 * Register Team Post Type Archive:
 * --------------------------------
 * This plugin registers a custom block template for displaying team members and provides functionality to unregister the template.
 *
 * Functions:
 * - lsx_team_register_template: Registers the custom block template using wp_register_block_template.
 * - lsx_team_unregister_template: Unregisters the custom block template using wp_unregister_block_template.
 *
 * Hooks:
 * - init: Calls lsx_team_register_template to register the template.
 * - init: Calls lsx_team_unregister_template to unregister the template.
 *
 */

// Register the template
add_action( 'init', 'lsx_team_register_template' );

function lsx_team_register_template() {
    // Add calls to wp_register_block_template() here.
}

wp_register_block_template( 'lsx_team//team-archive', [
   'title'       => __( 'LSX Team Archive', 'team-archive' ),
   'description' => __( 'Team Archive template for displaying all team members.', 'lsx_team' ),
   'content'     =>
   '<!-- wp:template-part {"slug":"header","area":"header","tagName":"header"} /-->
   <!-- wp:group {"tagName":"main"} -->
   <main class="wp-block-group">
       <!-- wp:group {"layout":{"type":"constrained"}} -->
       <div class="wp-block-group">
           <!-- wp:paragraph -->
           <p>This is a plugin-registered template.</p>
           <!-- /wp:paragraph -->
       </div>
       <!-- /wp:group -->
   </main>
   <!-- /wp:group -->
   <!-- wp:template-part {"slug":"footer","area":"footer","tagName":"footer"} /-->',
] );

// Deregister the template
add_action( 'init', 'lsx_team_unregister_template' );

function lsx_team_unregister_template() {
    wp_unregister_block_template( 'lsx_team//team-archive' );
}

?>
