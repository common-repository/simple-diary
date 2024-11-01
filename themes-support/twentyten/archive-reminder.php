<?php
/**
 * The template for displaying Diary
 *
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

get_header(); ?>

		<div id="container">
			<div id="content" role="main">

        <?php if ( have_posts() ) : ?>

			<h1 class="page-title"><?php get_simdiaw_title(true); ?></h1>

        <?php

             /* Start the Loop */
                the_simdiaw_reminders_query();
                get_template_part( 'loop', 'reminder' );
        endif;
        ?>

			</div><!-- #content -->
		</div><!-- #container -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
