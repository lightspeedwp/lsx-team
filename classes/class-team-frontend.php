<?php
/**
 * LSX Team Frontend Class
 *
 * @package   LSX Team
 * @author    LightSpeed
 * @license   GPL3
 * @link
 * @copyright 2016 LightSpeed
 */
class LSX_Team_Frontend
{


    public function __construct()
    {
        if (function_exists('tour_operator')) {
            $this->options = get_option('_lsx-to_settings', false);
        } else {
            $this->options = get_option('_lsx_settings', false);

            if (false === $this->options) {
                $this->options = get_option('_lsx_lsx-settings', false);
            }
        }

        add_action('wp_enqueue_scripts', [ $this, 'enqueue_scripts' ], 999);
        add_filter('wp_kses_allowed_html', [ $this, 'wp_kses_allowed_html' ], 10, 2);
        add_filter('template_include', [ $this, 'single_template_include' ], 99);
        add_filter('template_include', [ $this, 'archive_template_include' ], 99);

        if (! empty($this->options['display']) && ! empty($this->options['display']['team_disable_single'])) {
            add_action('template_redirect', [ $this, 'disable_single' ]);
        }

        add_action('pre_get_posts', [ $this, 'disable_pagination_on_archive' ]);

        if (is_admin()) {
            add_filter('lsx_customizer_colour_selectors_body', [ $this, 'customizer_body_colours_handler' ], 15, 2);
        }

        add_filter('lsx_fonts_css', [ $this, 'customizer_fonts_handler' ], 15);
        add_filter('lsx_banner_title', [ $this, 'lsx_banner_archive_title' ], 15);
        add_filter('lsx_banner_title', [ $this, 'lsx_banner_single_title' ], 15);

        add_filter('excerpt_more_p', [ $this, 'change_excerpt_more' ]);
        add_filter('excerpt_length', [ $this, 'change_excerpt_length' ]);
        add_filter('excerpt_strip_tags', [ $this, 'change_excerpt_strip_tags' ]);

    }//end __construct()


    public function enqueue_scripts($plugins)
    {
        $has_slick = wp_script_is('slick', 'queue');

        if (! $has_slick) {
            wp_enqueue_style('slick', LSX_TEAM_URL.'assets/css/vendor/slick.css', [], LSX_TEAM_VER, null);
            wp_enqueue_script('slick', LSX_TEAM_URL.'assets/js/vendor/slick.min.js', [ 'jquery' ], null, LSX_TEAM_VER, true);
        }

        wp_enqueue_script('lsx-team', LSX_TEAM_URL.'assets/js/lsx-team.min.js', [ 'jquery', 'slick' ], LSX_TEAM_VER, true);

        $params = apply_filters(
            'lsx_team_js_params',
            [
                'ajax_url' => admin_url('admin-ajax.php'),
            ]
        );

        wp_localize_script('lsx-team', 'lsx_team_params', $params);

        wp_enqueue_style('lsx-team', LSX_TEAM_URL.'assets/css/lsx-team.css', [], LSX_TEAM_VER);
        wp_style_add_data('lsx-team', 'rtl', 'replace');

    }//end enqueue_scripts()


    /**
     * Allow data params for Slick slider addon.
     */
    public function wp_kses_allowed_html($allowedtags, $context)
    {
        $allowedtags['div']['data-slick'] = true;
        return $allowedtags;

    }//end wp_kses_allowed_html()


    /**
     * Single template.
     */
    public function single_template_include($template)
    {
        if (is_main_query() && is_singular('team')) {
            if (empty(locate_template([ 'single-team.php' ])) && file_exists(LSX_TEAM_PATH.'templates/single-team.php')) {
                $template = LSX_TEAM_PATH.'templates/single-team.php';
            }
        }

        return $template;

    }//end single_template_include()


    /**
     * Archive template.
     */
    public function archive_template_include($template)
    {
        if (is_main_query() && is_post_type_archive('team')) {
            if (empty(locate_template([ 'archive-team.php' ])) && file_exists(LSX_TEAM_PATH.'templates/archive-team.php')) {
                $template = LSX_TEAM_PATH.'templates/archive-team.php';
            }
        }

        return $template;

    }//end archive_template_include()


    /**
     * Removes access to single team member posts.
     */
    public function disable_single()
    {
        $queried_post_type = get_query_var('post_type');

        if (is_single() && 'team' === $queried_post_type) {
            wp_redirect(home_url(), 301);
            exit;
        }

    }//end disable_single()


    /**
     * Disable pagination.
     */
    public function disable_pagination_on_archive($query)
    {
        if ($query->is_main_query() && $query->is_post_type_archive('team')) {
            $query->set('posts_per_page', -1);
            $query->set('no_found_rows', true);
        }

    }//end disable_pagination_on_archive()


    /**
     * Handle fonts that might be change by LSX Customiser.
     */
    public function customizer_fonts_handler($css_fonts)
    {
        global $wp_filesystem;

        $css_fonts_file = LSX_TEAM_PATH.'/assets/css/lsx-team-fonts.css';

        if (file_exists($css_fonts_file)) {
            if (empty($wp_filesystem)) {
                include_once ABSPATH.'wp-admin/includes/file.php';
                WP_Filesystem();
            }

            if ($wp_filesystem) {
                $css_fonts .= $wp_filesystem->get_contents($css_fonts_file);
            }
        }

        return $css_fonts;

    }//end customizer_fonts_handler()


    /**
     * Handle body colours that might be change by LSX Customiser.
     */
    public function customizer_body_colours_handler($css, $colors)
    {
        $css .= '
			@import "'.LSX_TEAM_PATH.'/assets/css/scss/customizer-team-body-colours";

			/**
			 * LSX Customizer - Body (LSX Team)
			 */
			@include customizer-team-body-colours (
				$bg:   		'.$colors['background_color'].',
				$breaker:   '.$colors['body_line_color'].',
				$color:    	'.$colors['body_text_color'].',
				$link:    	'.$colors['body_link_color'].',
				$hover:    	'.$colors['body_link_hover_color'].',
				$small:    	'.$colors['body_text_small_color'].'
			);
		';

        return $css;

    }//end customizer_body_colours_handler()


    /**
     * Change the LSX Banners title for team archive.
     */
    public function lsx_banner_archive_title($title)
    {
        if (is_main_query() && is_post_type_archive('team')) {
            $title = '<h1 class="page-title">'.esc_html__('Team', 'lsx-team').'</h1>';
        }

        return $title;

    }//end lsx_banner_archive_title()


    /**
     * Change the LSX Banners title for team single.
     */
    public function lsx_banner_single_title($title)
    {
        if (is_main_query() && is_singular('team')) {
            $title = '<h1 class="page-title">'.esc_html__('Team', 'lsx-team').'</h1>';
        }

        return $title;

    }//end lsx_banner_single_title()


    /**
     * Remove the "continue reading" when the single is disabled.
     */
    public function change_excerpt_more($excerpt_more)
    {
        global $post;

        if ('team' === $post->post_type) {
            if (! empty($this->options['display']) && ! empty($this->options['display']['team_disable_single'])) {
                $excerpt_more = '';
            }
        }

        return $excerpt_more;

    }//end change_excerpt_more()


    /**
     * Change the word count when crop the content to excerpt (single team relations).
     */
    public function change_excerpt_length($excerpt_word_count)
    {
        global $post;

        if (is_singular('team') && ( 'project' === $post->post_type || 'testimonial' === $post->post_type )) {
            $excerpt_word_count = 20;
        }

        return $excerpt_word_count;

    }//end change_excerpt_length()


    /**
     * Change the allowed tags crop the content to excerpt (single team relations).
     */
    public function change_excerpt_strip_tags($allowed_tags)
    {
        global $post;

        if (is_singular('team') && ( 'project' === $post->post_type || 'testimonial' === $post->post_type )) {
            $allowed_tags = '<p>,<br>,<b>,<strong>,<i>,<u>,<ul>,<ol>,<li>,<span>';
        }

        return $allowed_tags;

    }//end change_excerpt_strip_tags()


}//end class

$lsx_team_frontend = new LSX_Team_Frontend();
