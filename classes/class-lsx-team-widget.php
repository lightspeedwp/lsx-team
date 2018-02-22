<?php
/**
 * LSX Team Widget Class
 *
 * @package   LSX Team
 * @author    LightSpeed
 * @license   GPL3
 * @link
 * @copyright 2016 LightSpeed
 */
class LSX_Team_Widget extends WP_Widget {

	public function __construct() {
		$widget_ops = array(
			'classname' => 'lsx-team',
		);

		parent::__construct( 'LSX_Team_Widget', esc_html__( 'LSX Team Members', 'lsx-team' ), $widget_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );

		$title = $instance['title'];
		$title_link = $instance['title_link'];
		$tagline = $instance['tagline'];
		$columns = $instance['columns'];
		$orderby = $instance['orderby'];
		$order = $instance['order'];
		$role = $instance['role'];
		$limit = $instance['limit'];
		$include = $instance['include'];
		$display = $instance['display'];
		$size = $instance['size'];
		$show_link = $instance['show_link'];
		$show_image = $instance['show_image'];
		$show_roles = $instance['show_roles'];
		$show_job_title = $instance['show_job_title'];
		$show_desc = $instance['show_desc'];
		$show_social = $instance['show_social'];
		$button_text = $instance['button_text'];
		$carousel = $instance['carousel'];
		$featured = $instance['featured'];

		// If limit not set, display 99 posts
		if ( empty( $limit ) ) {
			$limit = '99';
		}

		// If specific posts included, display 99 posts
		if ( ! empty( $include ) ) {
			$limit = '99';
		}

		// Disregard specific ID setting if specific role is defined
		if ( 'all' !== $role ) {
			$include = '';
		} else {
			$role = '';
		}

		$show_link = '1' == $show_link ? 'true' : 'false';
		$show_image = '1' == $show_image ? 'true' : 'false';
		$show_roles = '1' == $show_roles ? 'true' : 'false';
		$show_job_title = '1' == $show_job_title ? 'true' : 'false';
		$show_desc = '1' == $show_desc ? 'true' : 'false';
		$show_social = '1' == $show_social ? 'true' : 'false';
		$carousel = '1' == $carousel ? 'true' : 'false';
		$featured = '1' == $featured ? 'true' : 'false';

		if ( $title_link ) {
			//$link_open = '<a href="' . $title_link . '">';
			$link_open = '';
			$link_btn_open = '<a href="' . $title_link . '" class="btn border-btn">';
			//$link_close = '</a>';
			$link_close = '';
			$link_btn_close = '</a>';
		} else {
			$link_open = '';
			$link_btn_open = '';
			$link_close = '';
			$link_btn_close = '';
		}

		echo wp_kses_post( $before_widget );

		if ( $title ) {
			echo wp_kses_post( $before_title . $link_open . $title . $link_close . $after_title );
		}

		if ( $tagline ) {
			echo '<p class="tagline text-center">' . esc_html( $tagline ) . '</p>';
		}

		if ( class_exists( 'LSX_Team' ) ) {
			lsx_team( array(
				'columns' => $columns,
				'orderby' => $orderby,
				'order' => $order,
				'role' => $role,
				'limit' => $limit,
				'include' => $include,
				'display' => $display,
				'size' => $size,
				'show_link' => $show_link,
				'show_image' => $show_image,
				'show_roles' => $show_roles,
				'show_job_title' => $show_job_title,
				'show_desc' => $show_desc,
				'show_social' => $show_social,
				'carousel' => $carousel,
				'featured' => $featured,
			) );
		};

		if ( $button_text && $title_link ) {
			echo wp_kses_post( '<p class="text-center lsx-team-archive-link-wrap"><span class="lsx-team-archive-link">' . $link_btn_open . $button_text . ' <i class="fa fa-angle-right"></i>' . $link_btn_close . '</span></p>' );
		}

		echo wp_kses_post( $after_widget );
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title'] 				= wp_kses_post( force_balance_tags( $new_instance['title'] ) );
		$instance['title_link'] 		= strip_tags( $new_instance['title_link'] );
		$instance['tagline'] 			= strip_tags( $new_instance['tagline'] );
		$instance['columns'] 			= strip_tags( $new_instance['columns'] );
		$instance['orderby'] 			= strip_tags( $new_instance['orderby'] );
		$instance['order'] 				= strip_tags( $new_instance['order'] );
		$instance['role'] 				= strip_tags( $new_instance['role'] );
		$instance['limit'] 				= strip_tags( $new_instance['limit'] );
		$instance['include'] 			= strip_tags( $new_instance['include'] );
		$instance['display'] 			= strip_tags( $new_instance['display'] );
		$instance['size'] 				= strip_tags( $new_instance['size'] );
		$instance['show_link'] 			= strip_tags( $new_instance['show_link'] );
		$instance['show_image'] 		= strip_tags( $new_instance['show_image'] );
		$instance['show_roles'] 		= strip_tags( $new_instance['show_roles'] );
		$instance['show_job_title'] 	= strip_tags( $new_instance['show_job_title'] );
		$instance['show_desc'] 			= strip_tags( $new_instance['show_desc'] );
		$instance['show_social'] 		= strip_tags( $new_instance['show_social'] );
		$instance['button_text'] 	    = strip_tags( $new_instance['button_text'] );
		$instance['carousel'] 			= strip_tags( $new_instance['carousel'] );
		$instance['featured']           = strip_tags( $new_instance['featured'] );

		return $instance;
	}

	function form( $instance ) {
		$defaults = array(
			'title' => 'Team Members',
			'title_link' => '',
			'tagline' => '',
			'columns' => '1',
			'orderby' => 'name',
			'order' => 'ASC',
			'role' => '',
			'limit' => '',
			'include' => '',
			'display' => 'excerpt',
			'size' => 'lsx-team-archive',
			'show_link' => 0,
			'show_image' => 1,
			'show_roles' => 0,
			'show_job_title' => 1,
			'show_desc' => 1,
			'show_social' => 1,
			'button_text' => '',
			'carousel' => 1,
			'featured' => 0,
		);

		$instance = wp_parse_args( (array) $instance, $defaults );

		$title 				= esc_attr( $instance['title'] );
		$title_link			= esc_attr( $instance['title_link'] );
		$tagline 			= esc_attr( $instance['tagline'] );
		$columns 			= esc_attr( $instance['columns'] );
		$orderby 			= esc_attr( $instance['orderby'] );
		$order 				= esc_attr( $instance['order'] );
		$role 				= esc_attr( $instance['role'] );
		$limit  			= esc_attr( $instance['limit'] );
		$include  			= esc_attr( $instance['include'] );
		$display  			= esc_attr( $instance['display'] );
		$size  				= esc_attr( $instance['size'] );
		$show_link 			= esc_attr( $instance['show_link'] );
		$show_image 		= esc_attr( $instance['show_image'] );
		$show_roles 		= esc_attr( $instance['show_roles'] );
		$show_job_title 	= esc_attr( $instance['show_job_title'] );
		$show_desc 			= esc_attr( $instance['show_desc'] );
		$show_social 		= esc_attr( $instance['show_social'] );
		$button_text 	    = esc_attr( $instance['button_text'] );
		$carousel 			= esc_attr( $instance['carousel'] );
		$featured           = esc_attr( $instance['featured'] );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'lsx-team' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title_link' ) ); ?>"><?php esc_html_e( 'Page Link:', 'lsx-team' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title_link' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title_link' ) ); ?>" type="text" value="<?php echo esc_attr( $title_link ); ?>" />
			<small><?php esc_html_e( 'Link the widget to a page', 'lsx-team' ); ?></small>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'tagline' ) ); ?>"><?php esc_html_e( 'Tagline:', 'lsx-team' ); ?></label>
			<textarea class="widefat" rows="8" cols="20" id="<?php echo esc_attr( $this->get_field_id( 'tagline' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'tagline' ) ); ?>"><?php echo esc_html( $tagline ); ?></textarea>
			<small><?php esc_html_e( 'Tagline to display below the widget title', 'lsx-team' ); ?></small>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'columns' ) ); ?>"><?php esc_html_e( 'Columns:', 'lsx-team' ); ?></label>
			<select name="<?php echo esc_attr( $this->get_field_name( 'columns' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'columns' ) ); ?>" class="widefat layout">
			<?php
				$options = array( '1', '2', '3', '4' );

				foreach ( $options as $option ) {
					echo '<option value="' . lcfirst( esc_attr( $option ) ) . '" id="' . esc_attr( $option ) . '"', lcfirst( $option ) == $columns ? ' selected="selected"' : '', '>', esc_html( $option ), '</option>';
				}
			?>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>"><?php esc_html_e( 'Order By:', 'lsx-team' ); ?></label>
			<select name="<?php echo esc_attr( $this->get_field_name( 'orderby' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>" class="widefat">
			<?php
				$options = array(
					esc_html__( 'None', 'lsx-team' ) => 'none',
					esc_html__( 'ID', 'lsx-team' ) => 'ID',
					esc_html__( 'Name', 'lsx-team' ) => 'name',
					esc_html__( 'Date', 'lsx-team' ) => 'date',
					esc_html__( 'Modified Date', 'lsx-team' ) => 'modified',
					esc_html__( 'Random', 'lsx-team' ) => 'rand',
					esc_html__( 'Menu (WP dashboard order)', 'lsx-team' ) => 'menu_order',
				);

				foreach ( $options as $name => $value ) {
					echo '<option value="' . esc_attr( $value ) . '" id="' . esc_attr( $value ) . '"', $orderby == $value ? ' selected="selected"' : '', '>', esc_html( $name ), '</option>';
				}
			?>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>"><?php esc_html_e( 'Order:', 'lsx-team' ); ?></label>
			<select name="<?php echo esc_attr( $this->get_field_name( 'order' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>" class="widefat">
			<?php
				$options = array(
					esc_html__( 'Ascending', 'lsx-team' ) => 'ASC',
					esc_html__( 'Descending', 'lsx-team' ) => 'DESC',
				);

				foreach ( $options as $name => $value ) {
					echo '<option value="' . esc_attr( $value ) . '" id="' . esc_attr( $value ) . '"', $order == $value ? ' selected="selected"' : '', '>', esc_html( $name ), '</option>';
				}
			?>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'role' ) ); ?>"><?php esc_html_e( 'Role:', 'lsx-team' ); ?></label>
			<select name="<?php echo esc_attr( $this->get_field_name( 'role' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'role' ) ); ?>" class="widefat">
			<?php
				$options = get_terms( 'team_role' );
			?>
			<option value="all" id="all">
				<?php esc_html_e( 'All Roles', 'lsx-team' ); ?>
			</option>
			<?php
				foreach ( $options as $option ) {
					echo '<option value="' . esc_attr( $option->slug ) . '" id="' . esc_attr( $option->slug ) . '"', $role == $option->slug ? ' selected="selected"' : '', '>', esc_html( $option->name ), '</option>';
				}
			?>
			</select>
			<small><?php esc_html_e( 'Display team members within a specific role', 'lsx-team' ); ?></small>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>"><?php esc_html_e( 'Maximum amount:', 'lsx-team' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'limit' ) ); ?>" type="text" value="<?php echo esc_attr( $limit ); ?>" />
			<small><?php esc_html_e( 'Leave empty to display all', 'lsx-team' ); ?></small>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'include' ) ); ?>"><?php esc_html_e( 'Specify Team Members by ID:', 'lsx-team' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'include' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'include' ) ); ?>" type="text" value="<?php echo esc_attr( $include ); ?>" />
			<small><?php esc_html_e( 'Comma separated list, overrides limit setting', 'lsx-team' ); ?></small>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'display' ) ); ?>"><?php esc_html_e( 'Display:', 'lsx-team' ); ?></label>
			<select name="<?php echo esc_attr( $this->get_field_name( 'display' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'display' ) ); ?>" class="widefat">
			<?php
				$options = array(
					esc_html__( 'Excerpt', 'lsx-team' ) => 'excerpt',
					esc_html__( 'Full Content', 'lsx-team' ) => 'full',
				);

				foreach ( $options as $name => $value ) {
					echo '<option value="' . esc_attr( $value ) . '" id="' . esc_attr( $value ) . '"', $display == $value ? ' selected="selected"' : '', '>', esc_html( $name ), '</option>';
				}
			?>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'size' ) ); ?>"><?php esc_html_e( 'Image size:', 'lsx-team' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'size' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'size' ) ); ?>" type="text" value="<?php echo esc_attr( $size ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'button_text' ) ); ?>"><?php esc_html_e( 'Button "view all" text:', 'lsx-team' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'button_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'button_text' ) ); ?>" type="text" value="<?php echo esc_attr( $button_text ); ?>" />
			<small><?php esc_html_e( 'Leave empty to not display the button', 'lsx-team' ); ?></small>
		</p>
		<p>
			<input id="<?php echo esc_attr( $this->get_field_id( 'show_link' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_link' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $show_link ); ?> />
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_link' ) ); ?>"><?php esc_html_e( 'Link to Single', 'lsx-team' ); ?></label>
		</p>
		<p>
			<input id="<?php echo esc_attr( $this->get_field_id( 'show_image' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_image' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $show_image ); ?> />
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_image' ) ); ?>"><?php esc_html_e( 'Show Images', 'lsx-team' ); ?></label>
		</p>
		<p>
			<input id="<?php echo esc_attr( $this->get_field_id( 'show_roles' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_roles' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $show_roles ); ?> />
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_roles' ) ); ?>"><?php esc_html_e( 'Show Roles', 'lsx-team' ); ?></label>
		</p>
		<p>
			<input id="<?php echo esc_attr( $this->get_field_id( 'show_job_title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_job_title' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $show_job_title ); ?> />
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_job_title' ) ); ?>"><?php esc_html_e( 'Show Job Title', 'lsx-team' ); ?></label>
		</p>
		<p>
			<input id="<?php echo esc_attr( $this->get_field_id( 'show_desc' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_desc' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $show_desc ); ?> />
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_desc' ) ); ?>"><?php esc_html_e( 'Show Description', 'lsx-team' ); ?></label>
		</p>
		<p>
			<input id="<?php echo esc_attr( $this->get_field_id( 'show_social' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_social' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $show_social ); ?> />
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_social' ) ); ?>"><?php esc_html_e( 'Show Social Icons', 'lsx-team' ); ?></label>
		</p>
		<p>
			<input id="<?php echo esc_attr( $this->get_field_id( 'carousel' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'carousel' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $carousel ); ?> />
			<label for="<?php echo esc_attr( $this->get_field_id( 'carousel' ) ); ?>"><?php esc_html_e( 'Carousel', 'lsx-team' ); ?></label>
		</p>
		<p>
			<input id="<?php echo esc_attr( $this->get_field_id( 'featured' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'featured' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $featured ); ?> />
			<label for="<?php echo esc_attr( $this->get_field_id( 'featured' ) ); ?>"><?php esc_html_e( 'Featured posts', 'lsx-team' ); ?></label>
		</p>
		<?php
	}

}

add_action( 'widgets_init', create_function( '', 'return register_widget( "LSX_Team_Widget" );' ) );
