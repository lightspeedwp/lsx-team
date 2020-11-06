<?php
/**
 * @package lsx-team
 */
?>

<?php
	global $lsx_team;

	$thumbnail = $lsx_team->get_thumbnail( get_the_ID(), 'lsx-team-single' );

	$job_title = get_post_meta( get_the_ID(), 'lsx_job_title', true );

	$links = array(
		'facebook'  => get_post_meta( get_the_ID(), 'lsx_facebook', true ),
		'twitter'   => get_post_meta( get_the_ID(), 'lsx_twitter', true ),
		'linkedin'  => get_post_meta( get_the_ID(), 'lsx_linkedin', true ),
		'github'    => get_post_meta( get_the_ID(), 'lsx_github', true ),
		'wordpress' => get_post_meta( get_the_ID(), 'lsx_wordpress', true ),
	);

	foreach ( $links as $service => $link ) {
		if ( empty( $link ) ) {
			unset( $links[ $service ] );
		}
	}

	$email = get_post_meta( get_the_ID(), 'lsx_email_contact', true );
	$phone = get_post_meta( get_the_ID(), 'lsx_tel', true );
	$skype = get_post_meta( get_the_ID(), 'lsx_skype', true );

	$phone_attr = $phone;
	$phone_attr = str_replace( ' ', '', $phone_attr );
	$phone_attr = str_replace( '+', '', $phone_attr );
	$phone_attr = str_replace( '(', '', $phone_attr );
	$phone_attr = str_replace( ')', '', $phone_attr );
	$phone_attr = str_replace( '.', '', $phone_attr );

	// Tabs

	$tabs = array();

	// Tab Posts

	$site_user = get_post_meta( get_the_ID(), 'lsx_site_user', true );

	if ( ! empty( $site_user ) ) {
		if ( is_user_member_of_blog( $site_user ) ) {
			$user_posts = count_user_posts( $site_user, 'post' );

			if ( $user_posts > 0 ) {
				$params = array(
					'post_type' => 'post',
					'author' => $site_user,
					'posts_per_page' => 9,
					'order' => 'DESC',
					'orderby' => 'date',
					'fields' => 'ids',
					'tax_query' => array(
						array(
							'taxonomy' => 'post_format',
							'field' => 'slug',
							'terms' => array(
								'post-format-aside',
								'post-format-audio',
								'post-format-chat',
								'post-format-gallery',
								'post-format-image',
								'post-format-link',
								'post-format-quote',
								'post-format-status',
								'post-format-video',
							),
							'operator' => 'NOT IN',
						),
					),
				);

				$posts_query = new \WP_Query( $params );

				if ( $posts_query->have_posts() ) {
					$tab_post['post_type'] = 'post';
					$tab_post['title'] = esc_html__( 'Posts', 'lsx-team' );
					$tab_post['posts'] = $posts_query->posts;

					if ( ! empty( $tab_post['posts'] ) ) {
						$post_ids = join( ',', $tab_post['posts'] );
						$tab_post['shortcode'] = '[lsx_posts columns="3" limit="9" include="' . $post_ids . '"]';
						$tabs[] = $tab_post;
					}
				}
			}
		}
	}

	// Tab Projects

	$tab_project['post_type'] = 'project';
	$tab_project['title'] = esc_html__( 'Projects', 'lsx-team' );
	$tab_project['posts'] = get_post_meta( get_the_ID(), 'project_to_team', true );
	if ( is_plugin_active( 'lsx-projects/lsx-projects.php' ) && ( ! empty( $tab_project['posts'] ) ) ) {
		$post_ids = join( ',', $tab_project['posts'] );
		$tab_project['shortcode'] = '[lsx_projects columns="3" include="' . $post_ids . '"]';
		$tabs[] = $tab_project;
	}

	// Tab Services

	if ( is_plugin_active( 'lsx-services/lsx-services.php' ) ) {
		$tab_service['post_type'] = 'service';
		$tab_service['title'] = esc_html__( 'Services', 'lsx-team' );
		$tab_service['posts'] = get_post_meta( get_the_ID(), 'service_to_team', true );

		if ( ! empty( $tab_service['posts'] ) ) {
			$post_ids = join( ',', $tab_service['posts'] );
			$tab_service['shortcode'] = '[lsx_services columns="3" include="' . $post_ids . '"]';
			$tabs[] = $tab_service;
		}
	}

	// Tab Testimonials

	$tab_testimonial['post_type'] = 'testimonial';
	$tab_testimonial['title'] = esc_html__( 'Testimonials', 'lsx-team' );
	$tab_testimonial['posts'] = get_post_meta( get_the_ID(), 'testimonial_to_team', true );

	if ( is_plugin_active( 'lsx-testimonials/lsx-testimonials.php' ) && ( ! empty( $tab_testimonial['posts'] ) ) ) {
		if ( count( $tab_testimonial['posts'] ) <= 2 ) {
			$columns = count( $tab_testimonial['posts'] );
		} else {
			$columns = 2;
		}

		$post_ids = join( ',', $tab_testimonial['posts'] );
		$tab_testimonial['shortcode'] = '[lsx_testimonials columns="' . $columns . '" include="' . $post_ids . '" orderby="date" order="DESC" display="excerpt"]';
		$tabs[] = $tab_testimonial;
	}
?>

<?php lsx_entry_before(); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php lsx_entry_top(); ?>

	<div class="row">
		<div class="col-xs-12 col-sm-6 col-md-4">
			<figure class="lsx-team-avatar">
				<?php echo wp_kses_post( $thumbnail ); ?>
			</figure>

			<?php if ( count( $links ) > 0 ) : ?>
				<span class="lsx-team-socials-header"><?php echo esc_html__( 'Follow', 'lsx-team' ) . ':'; ?></span>

				<ul class="lsx-team-socials list-inline">
					<?php foreach ( $links as $service => $link ) : ?>
						<li><a href="<?php echo esc_html( $link ); ?>" target="_blank" rel="nofollow noreferrer noopener"><i class="fa fa-<?php echo esc_html( $service ); ?>" aria-hidden="true"></i></a></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>

		<div class="col-xs-12 col-sm-6 col-md-8">
			<h1 class="lsx-team-name"><?php the_title(); ?></h1>
			<h5 class="lsx-team-job-title"><?php echo esc_html( $job_title ); ?></h5>
			<div class="lsx-team-content"><?php the_content(); ?></div>

			<?php if ( ! empty( $email ) || ! empty( $phone ) || ! empty( $skype ) ) : ?>
				<ul class="lsx-team-contact list-inline">
					<?php if ( ! empty( $email ) ) : ?>
						<li><a href="mailto:<?php echo esc_attr( $email ); ?>"><i class="fa fa-envelope-o" aria-hidden="true"></i> <?php echo esc_attr( $email ); ?></a></li>
					<?php endif; ?>

					<?php if ( ! empty( $phone ) ) : ?>
						<li><a href="tel:<?php echo esc_attr( $phone_attr ); ?>"><i class="fa fa-phone" aria-hidden="true"></i> <?php echo esc_attr( $phone ); ?></a></li>
					<?php endif; ?>

					<?php if ( ! empty( $skype ) ) : ?>
						<li><i class="fa fa-skype" aria-hidden="true"></i> <?php echo esc_attr( $skype ); ?></li>
					<?php endif; ?>
				</ul>
			<?php endif; ?>
		</div>
	</div>

	<?php if ( count( $tabs ) > 0 ) : ?>
		<div class="entry-tabs">
			<ul class="nav nav-tabs">
				<?php foreach ( $tabs as $i => $tab ) : ?>
					<li<?php if ( 0 === $i ) echo ' class="active"'; ?>><a data-toggle="tab" href="#<?php echo esc_attr( sanitize_title( $tab['title'] ) ); ?>"><?php echo esc_html( $tab['title'] ); ?></a></li>
				<?php endforeach; ?>
			</ul>

			<div class="tab-content">
				<?php foreach ( $tabs as $i => $tab ) : ?>
					<div id="<?php echo esc_attr( sanitize_title( $tab['title'] ) ); ?>" class="tab-pane fade<?php if ( 0 === $i ) echo ' in active'; ?>">
						<?php echo do_shortcode( $tab['shortcode'] ); ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endif; ?>

	<?php lsx_entry_bottom(); ?>

</article><!-- #post-## -->

<?php lsx_entry_after();
