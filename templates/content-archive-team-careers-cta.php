<?php
/**
 * @package lsx-team
 */
?>

<?php
	global $lsx_team;
?>

<?php if ( isset( $lsx_team->options['display'] ) && ! empty( $lsx_team->options['display']['team_careers_cta_enable'] ) ) : ?>
	<?php
		$thumbnail = '<img class="img-responsive wp-post-image" src="https://www.gravatar.com/avatar/none?d=mm&s=170" width="170" />';
		$title = $lsx_team->options['display']['team_careers_cta_title'];
		$tagline = $lsx_team->options['display']['team_careers_cta_tagline'];
		$link_text = $lsx_team->options['display']['team_careers_cta_link_text'];
		$link = $lsx_team->options['display']['team_careers_cta_link'];
	?>

	<div class="col-xs-12 col-sm-6 col-md-3">
		<article class="lsx-team-slot">
			<figure class="lsx-team-avatar">
				<a href="<?php echo esc_attr( $link ); ?>"><?php echo wp_kses_post( $thumbnail ); ?></a>
			</figure>

			<h5 class="lsx-team-name">
				<a href="<?php echo esc_attr( $link ); ?>"><?php echo esc_html( $title ); ?></a>
			</h5>

			<?php if ( ! empty( $tagline ) ) : ?>
				<small class="lsx-team-job-title"><?php echo esc_html( $tagline ); ?></small>
			<?php endif; ?>

			<a href="<?php echo esc_attr( $link ); ?>" class="lsx-team-show-more"><?php echo esc_html( $link_text ); ?> <i class="fa fa-long-arrow-right"></i></a>
		</article>
	</div>
<?php endif; ?>
