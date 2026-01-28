<?php
/**
 * Single post card in loop.
 *
 * @package Bricks_Child
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-card' ); ?>>
	<a href="<?php the_permalink(); ?>" class="post-card-link">
		<?php if ( has_post_thumbnail() ) : ?>
			<figure class="post-card-thumbnail">
				<?php the_post_thumbnail( 'large', array( 'loading' => 'lazy' ) ); ?>
			</figure>
		<?php endif; ?>

		<div class="post-card-categories">
			<?php the_category( ', ' ); ?>
		</div>

		<header class="post-card-header">
			<a href="<?php the_permalink(); ?>">
				<h2 class="post-card-title"><?php the_title(); ?></h2>
			</a>
		</header>

		<div class="post-card-excerpt">
			<?php the_excerpt(); ?>
		</div>

		<div class="post-card-meta">
			<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
				<?php echo esc_html( get_the_date( 'j. F Y' ) ); ?>
			</time>
			<?php if ( get_the_author() ) : ?>
				<span class="post-card-author ft-visually-hidden">
					<?php echo esc_html( get_the_author() ); ?>
				</span>
			<?php endif; ?>
		</div>
	</a>
</article>
