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
		$this->load_classes();

		add_action( 'init', array( $this, 'post_type_setup' ) );
		add_action( 'init', array( $this, 'taxonomy_setup' ) );

		add_action( 'cmb2_admin_init', array( $this, 'details_metabox' ) );
		add_action( 'cmb2_admin_init', array( $this, 'projects_details_metabox' ) );
		add_action( 'cmb2_admin_init', array( $this, 'services_details_metabox' ) );
		add_action( 'cmb2_admin_init', array( $this, 'testimonials_details_metabox' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'assets' ) );

		add_filter( 'type_url_form_media', array( $this, 'change_attachment_field_button' ), 20, 1 );
		add_filter( 'enter_title_here', array( $this, 'change_title_text' ) );
	}

	/**
	 * Loads the admin subclasses
	 */
	private function load_classes() {
		require_once LSX_TEAM_PATH . 'classes/admin/class-settings.php';
		$this->settings = Settings::get_instance();

		require_once LSX_TEAM_PATH . 'classes/admin/class-settings-theme.php';
		$this->settings_theme = Settings_Theme::get_instance();
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
			'show_in_rest'          => true,
			'rest_base'             => 'teamrole',
			'rest_controller_class' => 'WP_REST_Terms_Controller',
		);

		register_taxonomy( 'team_role', array( 'team' ), $args );
	}

	/**
	 * Define the metabox and field configurations.
	 */
	public function details_metabox() {

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
				'name'  => $user->display_name,
				'value' => $user->ID,
			);
		}

		$cmb = new_cmb2_box(
			array(
				'id'           => $prefix . '_team',
				'title'        => esc_html__( 'Team Member Details', 'lsx-team' ),
				'object_types' => 'team',
				'context'      => 'normal',
				'priority'     => 'low',
				'show_names'   => true,
			)
		);

		$cmb->add_field(
			array(
				'name'         => esc_html__( 'Featured:', 'lsx-team' ),
				'id'           => $prefix . 'featured',
				'type'         => 'checkbox',
				'value'        => 1,
				'default'      => 0,
				'show_in_rest' => true,
			)
		);

		$cmb->add_field(
			array(
				'name'         => esc_html__( 'Site User', 'lsx-team' ),
				'id'           => $prefix . 'site_user',
				'allow_none'   => true,
				'type'         => 'select',
				'options'      => $user_array,
				'show_in_rest' => true,
			)
		);

		$cmb->add_field(
			array(
				'name'         => esc_html__( 'Job Title:', 'lsx-team' ),
				'id'           => $prefix . 'job_title',
				'type'         => 'text',
				'show_in_rest' => true,
			)
		);

		$cmb->add_field(
			array(
				'name'         => esc_html__( 'Location:', 'lsx-team' ),
				'id'           => $prefix . 'location',
				'type'         => 'text',
				'show_in_rest' => true,
			)
		);

		$cmb->add_field(
			array(
				'name'         => esc_html__( 'Contact Email Address:', 'lsx-team' ),
				'id'           => $prefix . 'email_contact',
				'type'         => 'text',
				'show_in_rest' => true,
			)
		);

		$cmb->add_field(
			array(
				'name'         => esc_html__( 'Gravatar Email Address:', 'lsx-team' ),
				'desc'         => esc_html__( 'Used for Gravatar if a featured image is not set', 'lsx-team' ),
				'id'           => $prefix . 'email_gravatar',
				'type'         => 'text',
				'show_in_rest' => true,
			)
		);

		$cmb->add_field(
			array(
				'name'         => esc_html__( 'Telephone Number:', 'lsx-team' ),
				'id'           => $prefix . 'tel',
				'type'         => 'text',
				'show_in_rest' => true,
			)
		);

		$cmb->add_field(
			array(
				'name'         => esc_html__( 'Skype Name:', 'lsx-team' ),
				'id'           => $prefix . 'skype',
				'type'         => 'text',
				'show_in_rest' => true,
			)
		);

		$cmb->add_field(
			array(
				'name'         => esc_html__( 'Facebook URL', 'lsx-team' ),
				'id'           => $prefix . 'facebook',
				'type'         => 'text_url',
				'show_in_rest' => true,
			)
		);

		$cmb->add_field(
			array(
				'name'         => esc_html__( 'Twitter URL', 'lsx-team' ),
				'id'           => $prefix . 'twitter',
				'type'         => 'text_url',
				'show_in_rest' => true,
			)
		);

		$cmb->add_field(
			array(
				'name'         => esc_html__( 'LinkedIn URL', 'lsx-team' ),
				'id'           => $prefix . 'linkedin',
				'type'         => 'text_url',
				'show_in_rest' => true,
			)
		);

		$cmb->add_field(
			array(
				'name'         => esc_html__( 'Github URL', 'lsx-team' ),
				'id'           => $prefix . 'github',
				'type'         => 'text_url',
				'show_in_rest' => true,
			)
		);

		$cmb->add_field(
			array(
				'name'         => esc_html__( 'WordPress URL', 'lsx-team' ),
				'id'           => $prefix . 'wordpress',
				'type'         => 'text_url',
				'show_in_rest' => true,
			)
		);
	}

	/**
	 * Define the metabox and field configurations.
	 */
	public function projects_details_metabox() {

		if ( class_exists( 'LSX_Projects' ) ) {

			$prefix = 'lsx_';

			$cmb = new_cmb2_box(
				array(
					'id'           => $prefix . '_team',
					'context'      => 'normal',
					'priority'     => 'low',
					'show_names'   => true,
					'object_types' => array( 'team' ),
				)
			);
			$cmb->add_field(
				array(
					'name'       => __( 'Projects:', 'lsx-team' ),
					'id'         => 'project_to_team',
					'type'       => 'post_search_ajax',
					'limit'      => 15,
					'sortable'   => true,
					'query_args' => array(
						'post_type'      => array( 'project' ),
						'post_status'    => array( 'publish' ),
						'posts_per_page' => -1,
					),
				)
			);
		}
	}

	/**
	 * Define the metabox and field configurations.
	 */
	public function services_details_metabox() {

		if ( class_exists( 'LSX_Services' ) ) {

			$prefix = 'lsx_';

			$cmb = new_cmb2_box(
				array(
					'id'           => $prefix . '_team',
					'context'      => 'normal',
					'priority'     => 'low',
					'show_names'   => true,
					'object_types' => array( 'team' ),
				)
			);
			$cmb->add_field(
				array(
					'name'       => __( 'Services:', 'lsx-team' ),
					'id'         => 'service_to_team',
					'type'       => 'post_search_ajax',
					'limit'      => 15,
					'sortable'   => true,
					'query_args' => array(
						'post_type'      => array( 'service' ),
						'post_status'    => array( 'publish' ),
						'posts_per_page' => -1,
					),
				)
			);
		}
	}

	/**
	 * Define the metabox and field configurations.
	 */
	public function testimonials_details_metabox() {

		if ( class_exists( 'LSX_Testimonials' ) ) {

			$prefix = 'lsx_';

			$cmb = new_cmb2_box(
				array(
					'id'           => $prefix . '_team',
					'context'      => 'normal',
					'priority'     => 'low',
					'show_names'   => true,
					'object_types' => array( 'team' ),
				)
			);
			$cmb->add_field(
				array(
					'name'       => __( 'Testimonials:', 'lsx-team' ),
					'id'         => 'testimonial_to_team',
					'type'       => 'post_search_ajax',
					'limit'      => 15,
					'sortable'   => true,
					'query_args' => array(
						'post_type'      => array( 'testimonial' ),
						'post_status'    => array( 'publish' ),
						'posts_per_page' => -1,
					),
				)
			);
		}

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
