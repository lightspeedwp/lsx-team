<?php
/**
 * Functions
 *
 * @package   LSX Team
 * @author    Squiz Pty Ltd <products@squiz.net>
 * @license   GPL3
 * @link
 * @copyright 2016 Squiz Pty Ltd (ABN 77 084 670 600)
 */


/**
 * Add our action to init to set up our vars first.
 */
function lsx_team_load_plugin_textdomain()
{
    load_plugin_textdomain('lsx-team', false, basename(LSX_TEAM_PATH).'/languages');

}//end lsx_team_load_plugin_textdomain()


add_action('init', 'lsx_team_load_plugin_textdomain');


/**
 * Wraps the output class in a function to be called in templates
 */
function lsx_team($args)
{
    $lsx_team = new LSX_Team;
    echo wp_kses_post($lsx_team->output($args));

}//end lsx_team()


/**
 * Shortcode
 */
function lsx_team_shortcode($atts)
{
    $lsx_team = new LSX_Team;
    return $lsx_team->output($atts);

}//end lsx_team_shortcode()


add_shortcode('lsx_team', 'lsx_team_shortcode');
