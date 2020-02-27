<?php
/**
 * LSX Team Admin Class
 *
 * @package   LSX Team
 * @author    LightSpeed
 * @license   GPL3
 * @link
 * @copyright 2018 LightSpeed
 */
class LSX_Team_Admin {

	public function __construct() {
		if ( ! class_exists( 'CMB_Meta_Box' ) ) {
			require_once( LSX_TEAM_PATH . '/vendor/Custom-Meta-Boxes/custom-meta-boxes.php' );
		}

		if ( function_exists( 'tour_operator' ) ) {
			$this->options = get_option( '_lsx-to_settings', false );
		} else {
			$this->options = get_option( '_lsx_settings', false );

			if ( false === $this->options ) {
				$this->options = get_option( '_lsx_lsx-settings', false );
			}
		}

		add_action( 'init', array( $this, 'post_type_setup' ) );
		add_action( 'init', array( $this, 'taxonomy_setup' ) );
		add_filter( 'cmb_meta_boxes', array( $this, 'field_setup' ) );
		add_action( 'cmb_save_custom', array( $this, 'post_relations' ), 3, 20 );
		add_action( 'admin_enqueue_scripts', array( $this, 'assets' ) );

		add_action( 'init', array( $this, 'create_settings_page' ), 100 );
		add_filter( 'lsx_framework_settings_tabs', array( $this, 'register_tabs' ), 100, 1 );

		add_filter( 'type_url_form_media', array( $this, 'change_attachment_field_button' ), 20, 1 );
		add_filter( 'enter_title_here', array( $this, 'change_title_text' ) );
	}

	public function post_type_setup() {
		$labels = array(
			'name'               => esc_html_x( 'Team Members', 'post type general name', 'lsx-team' ),
			'singular_name'      => esc_html_x( 'Team Member', 'post type singular name', 'lsx-team' ),
			'add_new'            => esc_html_x( 'Add New', 'post type general name', 'lsx-team' ),
			'add_new_item'       => esc_html__( 'Add New Team Member', 'lsx-team' ),
			'edit_item'          => esc_html__( 'Edit Team Member', 'lsx-team' ),
			'new_item'           => esc_html__( 'New Team Member', 'lsx-team' ),
			'all_items'          => esc_html__( 'All Team Members', 'lsx-team' ),
			'view_item'          => esc_html__( 'View Team Member', 'lsx-team' ),
			'search_items'       => esc_html__( 'Search Team Members', 'lsx-team' ),
			'not_found'          => esc_html__( 'No team members found', 'lsx-team' ),
			'not_found_in_trash' => esc_html__( 'No team members found in Trash', 'lsx-team' ),
			'parent_item_colon'  => '',
			'menu_name'          => esc_html_x( 'Team Members', 'admin menu', 'lsx-team' ),
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'menu_icon'          => 'dashicons-groups',
			'query_var'          => true,
			'rewrite'            => array(
				'slug' => 'team',
			),
			'capability_type'    => 'post',
			'has_archive'        => 'team',
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array(
				'title',
				'editor',
				'excerpt',
				'thumbnail',
			),
			'show_in_rest'          => true,
			'rest_base'             => 'team',
			'rest_controller_class' => 'WP_REST_Posts_Controller',
		);

		register_post_type( 'team', $args );
	}

	public function taxonomy_setup() {
		$labels = array(
			'name'              => esc_html_x( 'Roles', 'taxonomy general name', 'lsx-team' ),
			'singular_name'     => esc_html_x( 'Role', 'taxonomy singular name', 'lsx-team' ),
			'search_items'      => esc_html__( 'Search Roles', 'lsx-team' ),
			'all_items'         => esc_html__( 'All Roles', 'lsx-team' ),
			'parent_item'       => esc_html__( 'Parent Role', 'lsx-team' ),
			'parent_item_colon' => esc_html__( 'Parent Role:', 'lsx-team' ),
			'edit_item'         => esc_html__( 'Edit Role', 'lsx-team' ),
			'update_item'       => esc_html__( 'Update Role', 'lsx-team' ),
			'add_new_item'      => esc_html__( 'Add New Role', 'lsx-team' ),
			'new_item_name'     => esc_html__( 'New Role Name', 'lsx-team' ),
			'menu_name'         => esc_html__( 'Roles', 'lsx-team' ),
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array(
				'slug' => 'team-role',
			),
		);

		register_taxonomy( 'team_role', array( 'team' ), $args );
	}

	public function field_setup( $meta_boxes ) {
		//$prefix = 'lsx_team_';
		$prefix = 'lsx_';

		$users = get_transient( 'lsx_team_users' );
		if ( false === $users || '' === $users ) {
			$users = get_users( array(
				'role__in' => array( 'administrator', 'editor', 'author' ),
			) );
			set_transient( 'lsx_team_users', $users, 5 * 60 );
		}

		foreach ( $users as $user ) {
			$user_array[] = array(
				'name' => $user->display_name,
				'value' => $user->ID,
			);
		}

		$fields = array(
			array(
				'name' => esc_html__( 'Featured:', 'lsx-projects' ),
				'id'   => $prefix . 'featured',
				'type' => 'checkbox',
				'show_in_rest' => true,
			),
			array(
				'name' => esc_html__( 'Site User', 'lsx-team' ),
				'id' => $prefix . 'site_user',
				'allow_none' => true,
				'type' => 'select',
				'options' => $user_array,
				'show_in_rest' => true,
			),
			array(
				'name' => esc_html__( 'Job Title:', 'lsx-team' ),
				'id'   => $prefix . 'job_title',
				'type' => 'text',
				'show_in_rest' => true,
			),
			array(
				'name' => esc_html__( 'Location:', 'lsx-team' ),
				'id'   => $prefix . 'location',
				'type' => 'text',
			),
			array(
				'name' => esc_html__( 'Contact Email Address:', 'lsx-team' ),
				'id'   => $prefix . 'email_contact',
				'type' => 'text',
			),
			array(
				'name' => esc_html__( 'Gravatar Email Address:', 'lsx-team' ),
				'desc' => esc_html__( 'Used for Gravatar if a featured image is not set', 'lsx-team' ),
				'id'   => $prefix . 'email_gravatar',
				'type' => 'text',
			),
			array(
				'name' => esc_html__( 'Telephone Number:', 'lsx-team' ),
				'id'   => $prefix . 'tel',
				'type' => 'text',
			),
			/*array(
				'name' => esc_html__( 'Mobile Number:', 'lsx-team' ),
				'id'   => $prefix . 'mobile',
				'type' => 'text',
			),
			array(
				'name' => esc_html__( 'Fax Number:', 'lsx-team' ),
				'id'   => $prefix . 'fax',
				'type' => 'text',
			),*/
			array(
				'name' => esc_html__( 'Skype Name:', 'lsx-team' ),
				'id'   => $prefix . 'skype',
				'type' => 'text',
			),
			array(
				'name' => esc_html__( 'Facebook URL', 'lsx-team' ),
				'id'   => $prefix . 'facebook',
				'type' => 'text_url',
			),
			array(
				'name' => esc_html__( 'Twitter URL', 'lsx-team' ),
				'id'   => $prefix . 'twitter',
				'type' => 'text_url',
			),
			array(
				'name' => esc_html__( 'LinkedIn URL', 'lsx-team' ),
				'id'   => $prefix . 'linkedin',
				'type' => 'text_url',
			),
		);

		array_map( 'absint', $fields );

		if ( class_exists( 'LSX_Projects' ) ) {
			$fields[] = array(
				'name' => esc_html__( 'Projects:', 'lsx-team' ),
				'id' => 'project_to_team',
				'type' => 'post_select',
				'use_ajax' => false,
				'query' => array(
					'post_type' => 'project',
					'nopagin' => true,
					'posts_per_page' => '50',
					'orderby' => 'title',
					'order' => 'ASC',
				),
				'repeatable' => true,
				'allow_none' => true,
				'cols' => 12,
			);
		}

		if ( class_exists( 'LSX_Services' ) ) {
			$fields[] = array(
				'name' => esc_html__( 'Services:', 'lsx-team' ),
				'id' => 'service_to_team',
				'type' => 'post_select',
				'use_ajax' => false,
				'query' => array(
					'post_type' => 'service',
					'nopagin' => true,
					'posts_per_page' => '50',
					'orderby' => 'title',
					'order' => 'ASC',
				),
				'repeatable' => true,
				'allow_none' => true,
				'cols' => 12,
			);
		}

		if ( class_exists( 'LSX_Testimonials' ) ) {
			$fields[] = array(
				'name' => esc_html__( 'Testimonials:', 'lsx-team' ),
				'id' => 'testimonial_to_team',
				'type' => 'post_select',
				'use_ajax' => false,
				'query' => array(
					'post_type' => 'testimonial',
					'nopagin' => true,
					'posts_per_page' => '50',
					'orderby' => 'title',
					'order' => 'ASC',
				),
				'repeatable' => true,
				'allow_none' => true,
				'cols' => 12,
			);
		}

		$meta_boxes[] = array(
			'title'  => esc_html__( 'Team Member Details', 'lsx-team' ),
			'pages'  => 'team',
			'fields' => $fields,
		);

		return $meta_boxes;
	}

	/**
	 * Sets up the "post relations".
	 */
	public function post_relations( $post_id, $field, $value ) {
		$connections = array(
			'team_to_testimonial',
			'testimonial_to_team',

			'team_to_project',
			'project_to_team',

			'team_to_service',
			'service_to_team',
		);

		if ( in_array( $field['id'], $connections ) ) {
			$this->save_related_post( $connections, $post_id, $field, $value );
		}
	}

	/**
	 * Save the reverse post relation.
	 */
	public function save_related_post( $connections, $post_id, $field, $value ) {
		$ids = explode( '_to_', $field['id'] );
		$relation = $ids[1] . '_to_' . $ids[0];

		if ( in_array( $relation, $connections ) ) {
			$previous_values = get_post_meta( $post_id, $field['id'], false );

			if ( ! empty( $previous_values ) ) {
				foreach ( $previous_values as $v ) {
					delete_post_meta( $v, $relation, $post_id );
				}
			}

			if ( is_array( $value ) ) {
				foreach ( $value as $v ) {
					if ( ! empty( $v ) ) {
						add_post_meta( $v, $relation, $post_id );
					}
				}
			}
		}
	}

	public function assets() {
		//wp_enqueue_media();
		wp_enqueue_script( 'media-upload' );
		wp_enqueue_script( 'thickbox' );
		wp_enqueue_style( 'thickbox' );

		wp_enqueue_script( 'lsx-team-admin', LSX_TEAM_URL . 'assets/js/lsx-team-admin.min.js', array( 'jquery' ), LSX_TEAM_VER );
		wp_enqueue_style( 'lsx-team-admin', LSX_TEAM_URL . 'assets/css/lsx-team-admin.css', array(), LSX_TEAM_VER );
	}

	/**
	 * Returns the array of settings to the UIX Class
	 */
	public function create_settings_page() {
		if ( is_admin() ) {
			if ( ! class_exists( '\lsx\ui\uix' ) && ! function_exists( 'tour_operator' ) ) {
				include_once LSX_TEAM_PATH . 'vendor/uix/uix.php';
				$pages = $this->settings_page_array();
				$uix = \lsx\ui\uix::get_instance( 'lsx' );
				$uix->register_pages( $pages );
			}

			if ( function_exists( 'tour_operator' ) ) {
				add_action( 'lsx_to_framework_display_tab_content', array( $this, 'display_settings' ), 11 );
			} else {
				add_action( 'lsx_framework_display_tab_content', array( $this, 'display_settings' ), 11 );
			}
		}
	}

	/**
	 * Returns the array of settings to the UIX Class
	 */
	public function settings_page_array() {
		$tabs = apply_filters( 'lsx_framework_settings_tabs', array() );

		return array(
			'settings'  => array(
				'page_title'  => esc_html__( 'Theme Options', 'lsx-team' ),
				'menu_title'  => esc_html__( 'Theme Options', 'lsx-team' ),
				'capability'  => 'manage_options',
				'icon'        => 'dashicons-book-alt',
				'parent'      => 'themes.php',
				'save_button' => esc_html__( 'Save Changes', 'lsx-team' ),
				'tabs'        => $tabs,
			),
		);
	}

	/**
	 * Register tabs
	 */
	public function register_tabs( $tabs ) {
		$default = true;

		if ( false !== $tabs && is_array( $tabs ) && count( $tabs ) > 0 ) {
			$default = false;
		}

		if ( ! function_exists( 'tour_operator' ) ) {
			if ( ! array_key_exists( 'display', $tabs ) ) {
				$tabs['display'] = array(
					'page_title'        => '',
					'page_description'  => '',
					'menu_title'        => esc_html__( 'Display', 'lsx-team' ),
					'template'          => LSX_TEAM_PATH . 'includes/settings/display.php',
					'default'           => $default,
				);

				$default = false;
			}

			if ( ! array_key_exists( 'api', $tabs ) ) {
				$tabs['api'] = array(
					'page_title'        => '',
					'page_description'  => '',
					'menu_title'        => esc_html__( 'API', 'lsx-team' ),
					'template'          => LSX_TEAM_PATH . 'includes/settings/api.php',
					'default'           => $default,
				);

				$default = false;
			}
		}

		return $tabs;
	}

	/**
	 * Outputs the display tabs settings
	 *
	 * @param $tab string
	 * @return null
	 */
	public function display_settings( $tab = 'general' ) {
		if ( 'team' === $tab ) {
			$this->disable_single_post_field();
			$this->group_by_role_checkbox();
			$this->placeholder_field();
			$this->careers_cta_post_fields();
		}
	}

	/**
	 * Outputs the Display flags checkbox
	 */
	public function disable_single_post_field() {
		?>
		<tr class="form-field">
			<th scope="row">
				<label for="team_disable_single"><?php esc_html_e( 'Disable Single Posts', 'lsx-team' ); ?></label>
			</th>
			<td>
				<input type="checkbox" {{#if team_disable_single}} checked="checked" {{/if}} name="team_disable_single" />
				<small><?php esc_html_e( 'Disable Single Posts.', 'lsx-team' ); ?></small>
			</td>
		</tr>
		<?php
	}

	/**
	 * Outputs the Display flags checkbox
	 */
	public function group_by_role_checkbox() {
		?>
		<tr class="form-field">
			<th scope="row">
				<label for="group_by_role"><?php esc_html_e( 'Group By Role', 'lsx-team' ); ?></label>
			</th>
			<td>
				<input type="checkbox" {{#if group_by_role}} checked="checked" {{/if}} name="group_by_role" />
				<small><?php esc_html_e( 'Groups the Team on the Team archive by the role assigned.', 'lsx-team' ); ?></small>
			</td>
		</tr>
		<?php
	}

	/**
	 * Outputs the flag position field
	 */
	public function placeholder_field() {
		?>
		<tr class="form-field">
			<th scope="row">
				<label for="banner"> <?php esc_html_e( 'Placeholder', 'lsx-team' ); ?></label>
			</th>
			<td>
				<input class="input_image_id" type="hidden" {{#if team_placeholder_id}} value="{{team_placeholder_id}}" {{/if}} name="team_placeholder_id" />
				<input class="input_image" type="hidden" {{#if team_placeholder}} value="{{team_placeholder}}" {{/if}} name="team_placeholder" />
				<div class="thumbnail-preview">
					{{#if team_placeholder}}<img src="{{team_placeholder}}" width="150" />{{/if}}
				</div>
				<a {{#if team_placeholder}}style="display:none;"{{/if}} class="button-secondary lsx-thumbnail-image-add" data-slug="team_placeholder"><?php esc_html_e( 'Choose Image', 'lsx-team' ); ?></a>
				<a {{#unless team_placeholder}}style="display:none;"{{/unless}} class="button-secondary lsx-thumbnail-image-delete" data-slug="team_placeholder"><?php esc_html_e( 'Delete', 'lsx-team' ); ?></a>
			</td>
		</tr>
		<?php
	}

	/**
	 * Outputs the careers CTA post fields.
	 */
	public function careers_cta_post_fields() {
		?>
		<tr class="form-field">
			<th scope="row" colspan="2">
				<h2><?php esc_html_e( 'Careers CTA', 'lsx-team' ); ?></h2>
			</th>
		</tr>
		<tr class="form-field">
			<th scope="row">
				<label for="team_careers_cta_enable"><?php esc_html_e( 'Enable careers CTA', 'lsx-team' ); ?></label>
			</th>
			<td>
				<input type="checkbox" {{#if team_careers_cta_enable}} checked="checked" {{/if}} name="team_careers_cta_enable" />
				<small><?php esc_html_e( 'Displays careers CTA mystery man on team archive.', 'lsx-team' ); ?></small>
			</td>
		</tr>
		<tr class="form-field">
			<th scope="row">
				<label for="team_careers_cta_title"><?php esc_html_e( 'Title', 'lsx-team' ); ?></label>
			</th>
			<td>
				<input type="text" {{#if team_careers_cta_title}} value="{{team_careers_cta_title}}" {{/if}} name="team_careers_cta_title" />
			</td>
		</tr>
		<tr class="form-field">
			<th scope="row">
				<label for="team_careers_cta_tagline"><?php esc_html_e( 'Tagline', 'lsx-team' ); ?></label>
			</th>
			<td>
				<input type="text" {{#if team_careers_cta_tagline}} value="{{team_careers_cta_tagline}}" {{/if}} name="team_careers_cta_tagline" />
			</td>
		</tr>
		<tr class="form-field">
			<th scope="row">
				<label for="team_careers_cta_link_text"><?php esc_html_e( 'Link text', 'lsx-team' ); ?></label>
			</th>
			<td>
				<input type="text" {{#if team_careers_cta_link_text}} value="{{team_careers_cta_link_text}}" {{/if}} name="team_careers_cta_link_text" />
			</td>
		</tr>
		<tr class="form-field">
			<th scope="row">
				<label for="team_careers_cta_link"><?php esc_html_e( 'Careers page link', 'lsx-team' ); ?></label>
			</th>
			<td>
				<input type="text" {{#if team_careers_cta_link}} value="{{team_careers_cta_link}}" {{/if}} name="team_careers_cta_link" />
			</td>
		</tr>
		<?php
	}

	/**
	 * Change the "Insert into Post" button text when media modal is used for feature images
	 */
	public function change_attachment_field_button( $html ) {
		if ( isset( $_GET['feature_image_text_button'] ) ) {
			$html = str_replace( 'value="Insert into Post"', sprintf( 'value="%s"', esc_html__( 'Select featured image', 'lsx-team' ) ), $html );
		}

		return $html;
	}

	public function change_title_text( $title ) {
		$screen = get_current_screen();

		if ( 'team' === $screen->post_type ) {
			$title = esc_attr__( 'Enter team member name', 'lsx-team' );
		}

		return $title;
	}
}

$lsx_team_admin = new LSX_Team_Admin();
