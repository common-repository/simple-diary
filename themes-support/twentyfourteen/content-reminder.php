<?php
/**
 * The template for displaying reminder
 *
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
?>

<article id="reminder-<?php the_ID(); ?>" itemscope itemtype="http://schema.org/Event">
	<?php twentyfourteen_post_thumbnail(); ?>

	<header class="entry-header">
		<?php

			if ( is_single() ) :
				the_title( '<h1 class="entry-title" itemprop="name">', '</h1>' );
			else :
				the_title( '<h1 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark" itemprop="name">', '</a></h1>' );
			endif;
		?>

		<div class="entry-meta">
			<?php
				    the_simdiaw_meta_date();
				    the_simdiaw_date();
				    if (has_simdiaw_start_time()) the_simdiaw_time();
                ?>
			<br><span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'simple-diary' ), __( '1 Comment', 'simple-diary' ), __( '% Comments', 'simple-diary' ) ); ?></span>
			<?php edit_post_link( __( 'Edit', 'simple-diary' ), '<span class="edit-link">', '</span>' ); ?>
		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->

	<div class="entry-content">
		 <?php the_simdiaw_location();the_simdiaw_link(); ?>
	</div><!-- .entry-content -->

	<?php the_tags( '<footer class="entry-meta"><span class="tag-links">', '', '</span></footer>' ); ?>
</article><!-- reminder-<?php the_ID(); ?> -->
