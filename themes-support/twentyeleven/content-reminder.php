<?php
/**
 * Template for displaying reminder
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */
?>

	<article id="reminder-<?php the_ID(); ?>" itemscope itemtype="http://schema.org/Event">
		<header class="entry-header">
			
			<h1 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark" itemprop="name"><?php the_title(); ?></a></h1>
			
			<div class="entry-meta">
				<?php
				    the_simdiaw_meta_date();
				    the_simdiaw_date();
				    if (has_simdiaw_start_time()) the_simdiaw_time();
                ?>
			</div><!-- .entry-meta -->
			
			<?php if ( comments_open()): ?>
			<div class="comments-link">
				<?php comments_popup_link( '<span class="leave-reply">' . __( 'Reply', 'simple-diary' ) . '</span>', _x( '1', 'comments number', 'simple-diary' ), _x( '%', 'comments number', 'simple-diary' ) ); ?>
			</div>
			<?php endif; ?>
		</header><!-- .entry-header -->

		<div class="entry-content">
		    <?php the_simdiaw_location();the_simdiaw_link(); ?>
		</div><!-- .entry-content -->

		<footer class="entry-meta">
            <?php if ( comments_open() ) : ?>
			<span class="comments-link"><?php comments_popup_link( '<span class="leave-reply">' . __( 'Leave a reply', 'simple-diary' ) . '</span>', __( '<b>1</b> Reply', 'simple-diary' ), __( '<b>%</b> Replies', 'simple-diary' ) ); ?></span>
			<?php endif; // End if comments_open() ?>
            <?php edit_post_link( __( 'Edit', 'simple-diary' ), '<span class="edit-link">', '</span>' ); ?>
		</footer><!-- .entry-meta -->
	</article><!-- #reminder-<?php the_ID(); ?> -->
