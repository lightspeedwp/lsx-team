<?php
namespace lsx_team\classes;

/**
 * This class loads the other classes and function files
 *
 * @package lsx-team
 */
class Core {

	/**
	 * Holds class instance
	 *
	 * @since 1.0.0
	 *
	 * @var      object \lsx_team\classes\Core()
	 */
	protected static $instance = null;

	/**
	 * @var object \lsx_team\classes\Admin();
	 */
	public $admin;

	/**
	 * @var object \lsx_team\classes\Frontend();
	 */
	public $frontend;

	/**
	 * Contructor
	 */
	public function __construct() {

	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return    object \lsx_team\classes\Core()    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;

	}

	/**
	 * Returns the post types currently active
	 *
	 * @return void
	 */
	public function get_post_types() {
		$post_types = apply_filters( 'lsx_team_post_types', isset( $this->post_types ) );
		foreach ( $post_types as $index => $post_type ) {
			$is_disabled = \cmb2_get_option( 'lsx_team_options', $post_type . '_disabled', false );
			if ( true === $is_disabled || 1 === $is_disabled || 'on' === $is_disabled ) {
				unset( $post_types[ $index ] );
			}
		}
		return $post_types;
	}
}
