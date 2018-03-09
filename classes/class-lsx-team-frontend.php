<?php
/**
 * LSX Team Frontend Class
 *
 * @package   LSX Team
 * @author    LightSpeed
 * @license   GPL3
 * @link
 * @copyright 2018 LightSpeed
 */
class LSX_Team_Frontend {

	/**
	 * Holds the previous role, so we know when to output a new title.
	 */
	var $previous_role = '';

	public function __construct() {
		if ( function_exists( 'tour_operator' ) ) {
			$this->options = get_option( '_lsx-to_settings', false );
		} else {
			$this->options = get_option( '_lsx_settings', false );

			if ( false === $this->options ) {
				$this->options = get_option( '_lsx_lsx-settings', false );
			}
		}

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 999 );
		add_filter( 'wp_kses_allowed_html', array( $this, 'wp_kses_allowed_html' ), 10, 2 );
		add_filter( 'template_include', array( $this, 'single_template_include' ), 99 );
		add_filter( 'template_include', array( $this, 'archive_template_include' ), 99 );

		if ( ! empty( $this->options['display'] ) && ! empty( $this->options['display']['team_disable_single'] ) ) {
			add_action( 'template_redirect', array( $this, 'disable_single' ) );
		}

		add_action( 'pre_get_posts', array( $this, 'disable_pagination_on_archive' ) );

		if ( is_admin() ) {
			add_filter( 'lsx_customizer_colour_selectors_body', array( $this, 'customizer_body_colours_handler' ), 15, 2 );
		}

		add_filter( 'lsx_fonts_css', array( $this, 'customizer_fonts_handler' ), 15 );
		add_filter( 'lsx_banner_title', array( $this, 'lsx_banner_archive_title' ), 15 );
		add_filter( 'lsx_banner_title', array( $this, 'lsx_banner_single_title' ), 15 );

		add_filter( 'excerpt_more_p', array( $this, 'change_excerpt_more' ) );
		add_filter( 'excerpt_length', array( $this, 'change_excerpt_length' ) );
		add_filter( 'excerpt_strip_tags', array( $this, 'change_excerpt_strip_tags' ) );

		if ( ! empty( $this->options['display'] ) && ! empty( $this->options['display']['group_by_role'] ) ) {
			add_action( 'pre_get_posts', array( $this, 'pre_get_posts_order_by_role' ) );
			add_action( 'lsx_entry_before', array( $this, 'entry_before' ) );
		}
	}

	public function enqueue_scripts( $plugins ) {
		$has_slick = wp_script_is( 'slick', 'queue' );

		if ( ! $has_slick ) {
			wp_enqueue_style( 'slick', LSX_TEAM_URL . 'assets/css/vendor/slick.css', array(), LSX_TEAM_VER, null );
			wp_enqueue_script( 'slick', LSX_TEAM_URL . 'assets/js/vendor/slick.min.js', array( 'jquery' ), null, LSX_TEAM_VER, true );
		}

		wp_enqueue_script( 'lsx-team', LSX_TEAM_URL . 'assets/js/lsx-team.min.js', array( 'jquery', 'slick' ), LSX_TEAM_VER, true );

		$params = apply_filters( 'lsx_team_js_params', array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
		));

		wp_localize_script( 'lsx-team', 'lsx_team_params', $params );

		wp_enqueue_style( 'lsx-team', LSX_TEAM_URL . 'assets/css/lsx-team.css', array(), LSX_TEAM_VER );
		wp_style_add_data( 'lsx-team', 'rtl', 'replace' );
	}

	/**
	 * Allow data params for Slick slider addon.
	 */
	public function wp_kses_allowed_html( $allowedtags, $context ) {
		$allowedtags['div']['data-slick'] = true;
		return $allowedtags;
	}

	/**
	 * Single template.
	 */
	public function single_template_include( $template ) {
		if ( is_main_query() && is_singular( 'team' ) ) {
			if ( empty( locate_template( array( 'single-team.php' ) ) ) && file_exists( LSX_TEAM_PATH . 'templates/single-team.php' ) ) {
				$template = LSX_TEAM_PATH . 'templates/single-team.php';
			}
		}

		return $template;
	}

	/**
	 * Archive template.
	 */
	public function archive_template_include( $template ) {
		if ( is_main_query() && is_post_type_archive( 'team' ) ) {
			if ( empty( locate_template( array( 'archive-team.php' ) ) ) && file_exists( LSX_TEAM_PATH . 'templates/archive-team.php' ) ) {
				$template = LSX_TEAM_PATH . 'templates/archive-team.php';
			}
		}

		return $template;
	}

	/**
	 * Removes access to single team member posts.
	 */
	public function disable_single() {
		$queried_post_type = get_query_var( 'post_type' );

		if ( is_single() && 'team' === $queried_post_type ) {
			wp_redirect( home_url(), 301 );
			exit;
		}
	}

	/**
	 * Disable pagination.
	 */
	public function disable_pagination_on_archive( $query ) {
		if ( $query->is_main_query() && $query->is_post_type_archive( 'team' ) ) {
			$query->set( 'posts_per_page', -1 );
			$query->set( 'no_found_rows', true );
		}
	}

	/**
	 * Handle fonts that might be change by LSX Customiser.
	 */
	public function customizer_fonts_handler( $css_fonts ) {
		global $wp_filesystem;

		$css_fonts_file = LSX_TEAM_PATH . '/assets/css/lsx-team-fonts.css';

		if ( file_exists( $css_fonts_file ) ) {
			if ( empty( $wp_filesystem ) ) {
				require_once( ABSPATH . 'wp-admin/includes/file.php' );
				WP_Filesystem();
			}

			if ( $wp_filesystem ) {
				$css_fonts .= $wp_filesystem->get_contents( $css_fonts_file );
			}
		}

		return $css_fonts;
	}

	/**
	 * Handle body colours that might be change by LSX Customiser.
	 */
	public function customizer_body_colours_handler( $css, $colors ) {
		$css .= '
			@import "' . LSX_TEAM_PATH . '/assets/css/scss/customizer-team-body-colours";

			/**
			 * LSX Customizer - Body (LSX Team)
			 */
			@include customizer-team-body-colours (
				$bg:   		' . $colors['background_color'] . ',
				$breaker:   ' . $colors['body_line_color'] . ',
				$color:    	' . $colors['body_text_color'] . ',
				$link:    	' . $colors['body_link_color'] . ',
				$hover:    	' . $colors['body_link_hover_color'] . ',
				$small:    	' . $colors['body_text_small_color'] . '
			);
		';

		return $css;
	}

	/**
	 * Change the LSX Banners title for team archive.
	 */
	public function lsx_banner_archive_title( $title ) {
		if ( is_main_query() && is_post_type_archive( 'team' ) ) {
			$title = '<h1 class="page-title">' . esc_html__( 'Team', 'lsx-team' ) . '</h1>';
		}

		return $title;
	}

	/**
	 * Change the LSX Banners title for team single.
	 */
	public function lsx_banner_single_title( $title ) {
		if ( is_main_query() && is_singular( 'team' ) ) {
			$title = '<h1 class="page-title">' . esc_html__( 'Team', 'lsx-team' ) . '</h1>';
		}

		return $title;
	}

	/**
	 * Remove the "continue reading" when the single is disabled.
	 */
	public function change_excerpt_more( $excerpt_more ) {
		global $post;

		if ( 'team' === $post->post_type ) {
			if ( ! empty( $this->options['display'] ) && ! empty( $this->options['display']['team_disable_single'] ) ) {
				$excerpt_more = '';
			}
		}

		return $excerpt_more;
	}

	/**
	 * Change the word count when crop the content to excerpt (single team relations).
	 */
	public function change_excerpt_length( $excerpt_word_count ) {
		global $post;

		if ( is_singular( 'team' ) && ( 'project' === $post->post_type || 'testimonial' === $post->post_type ) ) {
			$excerpt_word_count = 20;
		}

		return $excerpt_word_count;
	}

	/**
	 * Change the allowed tags crop the content to excerpt (single team relations).
	 */
	public function change_excerpt_strip_tags( $allowed_tags ) {
		global $post;

		if ( is_singular( 'team' ) && ( 'project' === $post->post_type || 'testimonial' === $post->post_type ) ) {
			$allowed_tags = '<p>,<br>,<b>,<strong>,<i>,<u>,<ul>,<ol>,<li>,<span>';
		}

		return $allowed_tags;
	}

	/**
	 * @param $query \WP_Query()
	 *
	 * @return mixed
	 */
	public function pre_get_posts_order_by_role( $query ) {
		if ( ! is_admin() && $query->is_main_query() && $query->is_post_type_archive( 'team' ) ) {
			$post_ids = $this->order_by_role_query();
			if ( ! empty( $post_ids ) ) {
				$query->set( 'post__in', $post_ids );
				$query->set( 'orderby', 'post__in' );
			}
		}
		return $query;
	}

	/**
	 * Grabs the team members ordered by the Roles Slug and the title alphabetical
	 */
	public function order_by_role_query() {
		global $wpdb;
		$post_ids = array();

		$results = $wpdb->get_results( $wpdb->prepare("
			SELECT posts.ID, posts.post_title, terms.slug
			FROM {$wpdb->posts} AS posts
			INNER JOIN {$wpdb->term_relationships} as rels
			INNER JOIN {$wpdb->term_taxonomy} as tax
			INNER JOIN {$wpdb->terms} as terms
			WHERE posts.post_type = '%s'
			AND posts.post_status = '%s'
			AND posts.ID = rels.object_id
			AND rels.term_taxonomy_id = tax.term_taxonomy_id
			AND tax.taxonomy = '%s'
			AND tax.term_id = terms.term_id
			ORDER BY terms.lsx_team_term_order, posts.post_name
         ", 'team', 'publish', 'team_role' ) );

		if ( ! empty( $results ) ) {
			$post_ids = wp_list_pluck( $results, 'ID' );
		}
		return $post_ids;
	}


	/**
	 * Outputs the Role Title if its found
	 */
	public function entry_before() {
		$all_roles = wc_get_object_terms( get_the_ID(), 'team_role' );
		$this_role = '';
		$this_role_id = '';
		if ( ! empty( $all_roles ) ) {
			$this_role = $all_roles[0];
			$this_role_id = $this_role->term_id;
		}

		if ( '' === $this->previous_role || $this->previous_role !== $this_role_id ) {
			echo '<h2 class="role-title text-center col-xs-12 col-sm-12 col-md-12">' . wp_kses_post( $this_role->name ) . '</h2>';
			$this->previous_role = $this_role_id;
		}
	}

}

$lsx_team_frontend = new LSX_Team_Frontend();
