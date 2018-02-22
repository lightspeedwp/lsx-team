<?php
/**
 * The template for displaying all single team members.
 *
 * @package lsx-team
 */

get_header(); ?>

<?php lsx_content_wrap_before(); ?>

<div id="primary" class="content-area <?php echo esc_attr(lsx_main_class()); ?>">

    <?php lsx_content_before(); ?>

    <main id="main" class="site-main">

        <?php lsx_content_top(); ?>

        <?php if (have_posts()) : ?>
            <div class="lsx-team-container">
                <div class="row row-flex">

                    <?php
                    while (have_posts()) {
                        the_post();
                        include LSX_TEAM_PATH.'/templates/content-archive-team.php';
                    }

                        include LSX_TEAM_PATH.'/templates/content-archive-team-careers-cta.php';
                    ?>

                </div>
            </div>

            <?php lsx_paging_nav(); ?>

        <?php else : ?>
            <?php get_template_part('partials/content', 'none'); ?>

        <?php endif; ?>

        <?php lsx_content_bottom(); ?>

    </main><!-- #main -->

    <?php lsx_content_after(); ?>

</div><!-- #primary -->

<?php lsx_content_wrap_after(); ?>

<?php get_sidebar(); ?>

<?php
get_footer();
