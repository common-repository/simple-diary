<?php
/**
 * The template for displaying Diary
 *
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

		<?php if ( have_posts() ) : ?>
			<header class="archive-header">
				<h1 class="archive-title"><?php get_simdiaw_title(true); ?></h1>
			</header><!-- .archive-header -->

			<?php /* Start the Loop */
            the_simdiaw_reminders_query();
            while ( have_posts() ) : the_post(); ?>
            
				<?php get_template_part( 'content-reminder', get_post_format() ); ?>
				
			<?php endwhile; ?>

			<?php global $wp_query;

	        // Don't print empty markup if there's only one page.
	        if ( $wp_query->max_num_pages < 2 )
	        	return;
	        ?>
	        <nav class="navigation paging-navigation" role="navigation">
	        	<h1 class="screen-reader-text"><?php _e( 'Reminders navigation', 'simple-diary' ); ?></h1>
	        	<div class="nav-links">
            
	        		<?php if ( get_next_posts_link() ) : ?>
	        		<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older reminders', 'simple-diary' ) ); ?></div>
	        		<?php endif; ?>
            
	        		<?php if ( get_previous_posts_link() ) : ?>
	        		<div class="nav-next"><?php previous_posts_link( __( 'Newer reminders <span class="meta-nav">&rarr;</span>', 'simple-diary' ) ); ?></div>
	        		<?php endif; ?>
            
	        	</div><!-- .nav-links -->
	        </nav><!-- .navigation -->

		<?php else : ?>
			<?php get_template_part( 'content', 'none' ); ?>
		<?php endif; ?>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>