<?php
/**
 * The template for displaying Diary
 *
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

get_header(); ?>

	<section id="primary" class="site-content">
		<div id="content" role="main">

		<?php if ( have_posts() ) : ?>
			<header class="archive-header">
				<h1 class="archive-title"><?php get_simdiaw_title(true); ?></h1>
			</header><!-- .archive-header -->

			<?php /* Start the Loop */
				the_simdiaw_reminders_query();
				while ( have_posts() ) : the_post();
				
				get_template_part( 'content-reminder', get_post_format() );

			    endwhile;

			// Navigation below
            global $wp_query;

            if ( $wp_query->max_num_pages > 1 ) : ?>
                <nav id="nav-below" class="navigation" role="navigation">
                    <h3 class="assistive-text"><?php _e( 'Reminder navigation', 'simple-diary' ); ?></h3>
                    <div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older reminders', 'simple-diary' ) ); ?></div>
                    <div class="nav-next"><?php previous_posts_link( __( 'Newer reminders <span class="meta-nav">&rarr;</span>', 'simple-diary' ) ); ?></div>
                </nav><!-- #nav-below .navigation -->
            <?php endif; ?>

		<?php else : ?>
			<?php get_template_part( 'content', 'none' ); ?>
		<?php endif; ?>

		</div><!-- #content -->
	</section><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>