<?php
/**
 * The Team Schema for LSX Team
 *
 * @package lsx-team
 */
/**
 * Returns schema Review data.
 *
 * @since 10.2
 */
class LSX_Team_Schema extends LSX_Schema_Graph_Piece {
	/**
	 * Constructor.
	 *
	 * @param \WPSEO_Schema_Context $context A value object with context variables.
	 */
	public function __construct( WPSEO_Schema_Context $context ) {
		$this->post_type = 'team';
		parent::__construct( $context );
	}
	/**
	 * Returns Review data.
	 *
	 * @return array $data Review data.
	 */
	public function generate() {
		$data = array(
			'@type'            => array(
				'Person',
			),
			'@id'              => $this->context->canonical . '#person',
			'name'             => $this->post->post_title,
			'description'      => wp_strip_all_tags( $this->post->post_content ),
			'url'              => $this->post_url,
			'mainEntityOfPage' => array(
				'@id' => $this->context->canonical . WPSEO_Schema_IDs::WEBPAGE_HASH,
			),
		);
		if ( $this->context->site_represents_reference ) {
			$data['worksFor'] = $this->context->site_represents_reference;
			$data['memberOf'] = $this->context->site_represents_reference;
		}
		$data = $this->add_custom_field( $data, 'jobTitle', 'lsx_job_title' );
		$data = $this->add_custom_field( $data, 'email', 'lsx_email_contact' );
		$data = $this->add_custom_field( $data, 'telephone', 'lsx_tel' );
		$data = LSX_Schema_Utils::add_image( $data, $this->context );
		return $data;
	}
	/**
	 * Adds the projects and testimonials under the 'owns' parameter
	 *
	 * @param array $data
	 * @return array
	 */
	public function add_products( $data ) {
		$connections_array = array();
		$connections_array = $this->add_project( $connections_array );
		$connections_array = $this->add_testimonial( $connections_array );
		if ( ! empty( $connections_array ) ) {
			$data['owns'] = $connections_array;
		}
		return $data;
	}
}
