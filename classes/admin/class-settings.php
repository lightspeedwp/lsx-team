<?php
/**
 * Contains the settings class for LSX
 *
 * @package lsx-team
 */

class Settings {

	/**
	 * Holds class instance
	 *
	 * @since 1.0.0
	 *
	 * @var      object \lsx_team\classes\admin\Settings()
	 */
	protected static $instance = null;

	/**
	 * Option key, and option page slug
	 *
	 * @var string
	 */
	protected $screen_id = 'lsx_team_settings';

	/**
	 * An array of the post types for the Global Downloads field.
	 *
	 * @var array
	 */
	public $download_types = array();

	/**
	 * An array of the post types for the Global Defaults field.
	 *
	 * @var array
	 */
	public $default_types = array();

	/**
	 * An array of the endpoints for the Endpoint Translation field.
	 *
	 * @var array
	 */
	public $endpoints = array();

	/**
	 * Contructor
	 */
	public function __construct() {
		add_action( 'cmb2_admin_init', array( $this, 'register_settings_page' ) );
		add_action( 'lsx_team_settings_page', array( $this, 'general_settings' ), 1, 1 );
		add_action( 'lsx_team_settings_page', array( $this, 'global_defaults' ), 3, 1 );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return    object \lsx_team\classes\admin\Settings()    A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Hook in and register a submenu options page for the Page post-type menu.
	 */
	public function register_settings_page() {
		$cmb = new_cmb2_box(
			array(
				'id'           => $this->screen_id,
				'title'        => esc_html__( 'Settings', 'lsx-team' ),
				'object_types' => array( 'options-page' ),
				'option_key'   => 'lsx_team_options', // The option key and admin menu page slug.
				'parent_slug'  => 'edit.php?post_type=team', // Make options page a submenu item of the themes menu.
				'capability'   => 'manage_options', // Cap required to view options-page.
			)
		);
		do_action( 'lsx_team_settings_page', $cmb );
	}

	/**
	 * Registers the general settings.
	 *
	 * @param object $cmb new_cmb2_box().
	 * @return void
	 */
	public function general_settings( $cmb ) {
		$cmb->add_field(
			array(
				'id'      => 'settings_general_title',
				'type'    => 'title',
				'name'    => __( 'General', 'lsx-team' ),
				'default' => __( 'General', 'lsx-team' ),
			)
		);
		$cmb->add_field(
			array(
				'name'        => __( 'Disable Single Posts', 'lsx-team' ),
				'id'          => 'team_disable_single',
				'type'        => 'checkbox',
				'value'       => 1,
				'default'     => 0,
				'description' => __( 'Disable Single Posts.', 'lsx-health-plan' ),
			)
		);

		$cmb->add_field(
			array(
				'name'        => __( 'Group By Role', 'lsx-team' ),
				'id'          => 'group_by_role',
				'type'        => 'checkbox',
				'value'       => 1,
				'default'     => 0,
				'description' => __( 'Groups the Team on the Team archive by the role assigned.', 'lsx-health-plan' ),
			)
		);
		$cmb->add_field( array(
			'name'    => 'Placeholder',
			'desc'    => __( 'Upload an image.', 'lsx-health-plan' ),
			'id'      => 'team_placeholder_id',
			'type'    => 'file',
			'options' => array(
				'url' => false, // Hide the text input for the url.
			),
			'text'    => array(
				'add_upload_file_text' => 'Choose Image',
			),
		) );
		$cmb->add_field(
			array(
				'id'   => 'settings_general_closing',
				'type' => 'tab_closing',
			)
		);
	}

	/**
	 * Registers the global default settings.
	 *
	 * @param object $cmb new_cmb2_box().
	 * @return void
	 */
	public function global_defaults( $cmb ) {
		$cmb->add_field(
			array(
				'id'      => 'global_defaults_title',
				'type'    => 'title',
				'name'    => __( 'Careers CTA', 'lsx-team' ),
				'default' => __( 'Careers CTA', 'lsx-team' ),
			)
		);

		$cmb->add_field(
			array(
				'name'        => __( 'Enable careers CTA', 'lsx-team' ),
				'id'          => 'team_careers_cta_enable',
				'type'        => 'checkbox',
				'value'       => 1,
				'default'     => 0,
				'description' => __( 'Displays careers CTA mystery man on team archive.', 'lsx-health-plan' ),
			)
		);

		$cmb->add_field(
			array(
				'name' => __( 'Title', 'lsx-team' ),
				'id'   => 'team_careers_cta_title',
				'type' => 'text',
			)
		);

		$cmb->add_field(
			array(
				'name' => __( 'Tagline', 'lsx-team' ),
				'id'   => 'team_careers_cta_tagline',
				'type' => 'text',
			)
		);

		$cmb->add_field(
			array(
				'name' => __( 'Link Text', 'lsx-team' ),
				'id'   => 'team_careers_cta_link_text',
				'type' => 'text',
			)
		);

		$cmb->add_field(
			array(
				'name' => __( 'Careers page link', 'lsx-team' ),
				'id'   => 'team_careers_cta_link',
				'type' => 'text',
			)
		);

		$cmb->add_field(
			array(
				'id'   => 'settings_global_defaults_closing',
				'type' => 'tab_closing',
			)
		);
	}

}
