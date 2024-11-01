<?php
/**
 * The Template for displaying all single reminders
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">
			<?php
				// Start the Loop.
				while ( have_posts() ) : the_post();

					get_template_part( 'content-reminder', get_post_format() );

					// Previous/next post navigation.
					// Don't print empty markup if there's nowhere to navigate.
	                $previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	                $next     = get_adjacent_post( false, '', false );
                    
	                if ( ! $next && ! $previous ) {
	                	return;
	                }
                    
	                ?>
	                <nav class="navigation post-navigation" role="navigation">
	                	<h1 class="screen-reader-text"><?php _e( 'Reminder navigation', 'simple-diary' ); ?></h1>
	                	<div class="nav-links">
	                		<?php
	                			previous_post_link( '%link', __( '<span class="meta-nav">Previous Reminder</span>%title', 'simple-diary' ) );
	                			next_post_link( '%link', __( '<span class="meta-nav">Next Reminder</span>%title', 'simple-diary' ) );
	                		?>
	                	</div><!-- .nav-links -->
	                </nav><!-- .navigation -->;

					<?php // If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) {
						comments_template();
					}
				endwhile;
			?>
		</div><!-- #content -->
	</div><!-- #primary -->

<?php
get_sidebar( 'content' );
get_sidebar();
get_footer();
