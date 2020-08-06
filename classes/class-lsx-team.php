<?php
/**
 * LSX Team Main Class
 *
 * @package   LSX Team
 * @author    LightSpeed
 * @license   GPL3
 * @link
 * @copyright 2018 LightSpeed
 */
class LSX_Team {

	public $options;

	public function __construct() {
		$this->options = team_get_option();

		add_action( 'init', array( $this, 'custom_image_sizes' ) );
		add_filter( 'lsx_banner_allowed_post_types', array( $this, 'lsx_banner_allowed_post_types' ) );

	}

	/**
	 * Enable project custom post type on LSX Banners.
	 */
	public function custom_image_sizes( $post_types ) {
		add_image_size( 'lsx-team-archive', 170, 170, true );
		add_image_size( 'lsx-team-single', 320, 320, true );
	}

	/**
	 * Enable project custom post type on LSX Banners.
	 */
	public function lsx_banner_allowed_post_types( $post_types ) {
		$post_types[] = 'team';
		return $post_types;
	}

	/**
	 * Return the team thumbnail.
	 */
	public function get_thumbnail( $post_id, $size ) {
		add_filter( 'lsx_placeholder_url', array( $this, 'placeholder' ), 10, 1 );
		add_filter( 'lsx_to_placeholder_url', array( $this, 'placeholder' ), 10, 1 );

		if ( is_numeric( $size ) ) {
			$thumb_size = array( $size, $size );
		} else {
			$thumb_size = $size;
		}

		$thumbnail_class = 'img-responsive';

		if ( ! empty( get_the_post_thumbnail( $post_id ) ) || ! empty( get_post_meta( $post_id, 'lsx_email_gravatar', true ) ) ) {
			if ( ! empty( get_the_post_thumbnail( $post_id ) ) ) {
				$thumbnail = get_the_post_thumbnail( $post_id, $thumb_size, array(
					'class' => $thumbnail_class,
				) );
			} else {
				$thumbnail = get_avatar( get_post_meta( $post_id, 'lsx_email_gravatar', true ), $size, $this->options['display']['team_placeholder'], false, array(
					'class' => $thumbnail_class,
				) );
			}
		}

		if ( empty( $thumbnail ) ) {
			if ( $this->options['display'] && ! empty( $this->options['display']['team_placeholder'] ) ) {
				$thumbnail = '<img class="img-responsive wp-post-image" src="' . $this->options['display']['team_placeholder'] . '" width="' . $size . '" />';
			} else {
				$thumbnail = '<img class="img-responsive wp-post-image" src="https://www.gravatar.com/avatar/none?d=mm&s=' . $size . '" width="' . $size . '" />';
			}
		}

		remove_filter( 'lsx_placeholder_url', array( $this, 'placeholder' ), 10, 1 );
		remove_filter( 'lsx_to_placeholder_url', array( $this, 'placeholder' ), 10, 1 );

		return $thumbnail;
	}

	/**
	 * Replaces the widget with Mystery Man
	 */
	public function placeholder( $image ) {
		$image = array(
			LSX_TEAM_URL . 'assets/img/mystery-man-square.png',
			512,
			512,
			true,
		);

		return $image;
	}

	/**
	 * Returns the shortcode output markup
	 */
	public function output( $atts ) {
		extract( shortcode_atts(array(
			'columns' => 4,
			'orderby' => 'name',
			'order' => 'ASC',
			'role' => '',
			'limit' => '99',
			'include' => '',
			'display' => 'excerpt',
			'size' => 'lsx-team-archive',
			'show_link' => false,
			'show_email' => false,
			'show_image' => true,
			'show_roles' => false,
			'show_job_title' => true,
			'show_desc' => true,
			'show_social' => true,
			'carousel' => true,
			'featured' => false,
		), $atts ) );

		$output = '';

		if ( ! empty( $include ) ) {
			$include = explode( ',', $include );

			$args = array(
				'post_type' => 'team',
				'posts_per_page' => $limit,
				'post__in' => $include,
				'orderby' => 'post__in',
				'order' => $order,
			);
		} else {
			$args = array(
				'post_type' => 'team',
				'posts_per_page' => $limit,
				'orderby' => $orderby,
				'order' => $order,
			);

			if ( 'true' === $featured || true === $featured ) {
				$args['meta_key'] = 'lsx_featured';
				$args['meta_value'] = 1;
			}
		}

		if ( ! empty( $role ) ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'team_role',
					'field' => 'id',
					'terms' => $role,
				),
			);
		}

		$team = new \WP_Query( $args );

		if ( $team->have_posts() ) {
			global $post;

			$count = 0;
			$count_global = 0;

			$column_size = intval( 12 / $columns );

			$carousel = true === $carousel || 'true' === $carousel ? true : false;

			if ( $carousel ) {
				$output .= "<div class='lsx-team-shortcode' id='lsx-team-slider' data-slick='{\"slidesToShow\": $columns, \"slidesToScroll\": $columns }'>";
			} else {
				$output .= "<div class='lsx-team-shortcode'><div class='row'>";
			}

			while ( $team->have_posts() ) {
				$team->the_post();

				// Count
				$count++;
				$count_global++;

				$member_name = apply_filters( 'the_title', $post->post_title );
				$member_roles = '';
				$member_description = '';
				$member_avatar = '';
				$member_socials = '';
				$member_job_title = '';
				$member_email = '';
				$bottom_link = '';
				$facebook = get_post_meta( $post->ID, 'lsx_facebook', true );
				$twitter = get_post_meta( $post->ID, 'lsx_twitter', true );
				$linkedin = get_post_meta( $post->ID, 'lsx_linkedin', true );

				// Link to single
				if ( ( true === $show_link || 'true' === $show_link ) && ( empty( team_get_option( 'team_disable_single' ) ) ) ) {
					$bottom_link = '<a href="' . get_permalink( $post->ID ) . '" class="lsx-team-show-more">More about ' . strtok( $member_name, ' ' ) . '<i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>';
				}

				if ( true === $show_email || 'true' === $show_email ) {
					$email = get_post_meta( $post->ID, 'lsx_email_contact', true );

					$member_email = '<a href="mailto:' . sanitize_email( $email ) . '" class="lsx-team-email">' . sanitize_email( $email ) . '</a>';
				}

				if ( ( true === $show_link || 'true' === $show_link ) && ( empty( team_get_option( 'team_disable_single' ) ) ) ) {
					$member_name = '<h5 class="lsx-team-name"><a href="' . get_permalink() . '">' . $member_name . '</a></h5>';
				} else {
					$member_name = '<h5 class="lsx-team-name">' . $member_name . '</h5>';
				}

				// Member roles
				if ( true === $show_roles || 'true' === $show_roles ) {
					$roles = '';
					$terms = get_the_terms( $post->ID, 'team_role' );

					if ( $terms && ! is_wp_error( $terms ) ) {
						$roles = array();

						foreach ( $terms as $term ) {
							$roles[] = $term->name;
						}

						$roles = join( ', ', $roles );
					}

					$member_roles = '' !== $roles ? "<small class='lsx-team-roles'>$roles</small>" : '';
				}

				if ( true === $show_job_title || 'true' === $show_job_title ) {
					$job_title = get_post_meta( $post->ID, 'lsx_job_title', true );
					$member_job_title = ! empty( $job_title ) ? "<small class='lsx-team-job-title'>$job_title</small>" : '';
				}

				// Member description
				if ( true === $show_desc || 'true' === $show_desc ) {
					if ( 'full' === $display ) {
						$member_description = apply_filters( 'the_content', get_the_content( esc_html__( 'Read More', 'lsx-team' ) ) );
						$member_description = str_replace( ']]>', ']]&gt;', $member_description );
					} elseif ( 'excerpt' === $display ) {
						$member_description = apply_filters( 'the_excerpt', get_the_excerpt() );
					}

					$member_description = ! empty( $member_description ) ? "<div class='lsx-team-description'>$member_description</div>" : '';
				}

				// Member avatar
				if ( true === $show_image || 'true' === $show_image ) {
					$member_avatar = $this->get_thumbnail( $post->ID, $size );

					if ( ( true === $show_link || 'true' === $show_link ) && ( empty( $this->options['display'] ) || empty( team_get_option( 'team_disable_single' ) ) ) ) {
						$member_avatar = "<figure class='lsx-team-avatar'><a href='" . get_permalink() . "'>$member_avatar</a></figure>";
					} else {
						$member_avatar = "<figure class='lsx-team-avatar'>$member_avatar</figure>";
					}
				}

				// Member socials
				if ( true === $show_social || 'true' === $show_social ) {
					$links = array(
						'facebook' => $facebook,
						'twitter' => $twitter,
						'linkedin' => $linkedin,
					);

					foreach ( $links as $sm => $sm_link ) {
						if ( ! empty( $sm_link ) ) {
							$member_socials .= "<li><a href='$sm_link' target='_blank'><i class='fa fa-$sm' aria-hidden='true'></i></a></li>";
						}
					}

					$member_socials = ! empty( $member_socials ) ? "<ul class='lsx-team-socials list-inline'>$member_socials</ul>" : '';
				}

				if ( ! $carousel ) {
					$output .= "<div class='col-xs-12 col-md-$column_size'>";
				}

				$output .= "
					<div class='lsx-team-slot'>
						$member_avatar
						$member_name
						$member_job_title
						$member_roles
						$member_description
						$member_socials
						$member_email
						$bottom_link
					</div>
				";

				if ( ! $carousel ) {
					$output .= '</div>';

					if ( $count == $columns && $team->post_count > $count_global ) {
						$output .= '</div>';
						$output .= '<div class="row">';
						$count = 0;
					}
				}

				wp_reset_postdata();
			}

			if ( ! $carousel ) {
				$output .= '</div>';
			}

			$output .= '</div>';

			return $output;
		}
	}

}

global $lsx_team;
$lsx_team = new LSX_Team();
