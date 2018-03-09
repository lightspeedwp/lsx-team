<?php
/**
 * @package lsx-team
 */
?>

<?php
	global $lsx_team;

	$thumbnail = $lsx_team->get_thumbnail( get_the_ID(), 'lsx-team-archive' );

	$job_title = get_post_meta( get_the_ID(), 'lsx_job_title', true );
?>

<?php lsx_entry_before(); ?>

<div class="col-xs-12 col-sm-6 col-md-3">
	<article class="lsx-team-slot">
		<figure class="lsx-team-avatar">
			<?php if ( empty( $lsx_team->options['display'] ) || empty( $lsx_team->options['display']['team_disable_single'] ) ) : ?>
				<a href="<?php the_permalink(); ?>"><?php echo wp_kses_post( $thumbnail ); ?></a>
			<?php else : ?>
				<?php echo wp_kses_post( $thumbnail ); ?>
			<?php endif; ?>
		</figure>

		<h5 class="lsx-team-name">
			<?php if ( empty( $lsx_team->options['display'] ) || empty( $lsx_team->options['display']['team_disable_single'] ) ) : ?>
				<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			<?php else : ?>
				<?php the_title(); ?>
			<?php endif; ?>
		</h5>

		<?php if ( ! empty( $job_title ) ) : ?>
			<small class="lsx-team-job-title"><?php echo wp_kses_post( $job_title ); ?></small>
		<?php endif; ?>

		<?php if ( empty( $lsx_team->options['display'] ) || empty( $lsx_team->options['display']['team_disable_single'] ) ) : ?>
		<?php	/* translators: %s: search term */ ?>
			<a href="<?php the_permalink(); ?>" class="lsx-team-show-more"><?php printf( esc_html__( ' More about %s', 'lsx-team' ), esc_html( strtok( get_the_title(), ' ' ) ) ); ?> <i class="fa fa-long-arrow-right"></i></a>
		<?php endif; ?>
	</article>
</div>

<?php
	lsx_entry_after();
