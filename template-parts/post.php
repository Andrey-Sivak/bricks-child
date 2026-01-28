<?php
/**
 * Post Template.
 *
 * @package Bricks_Child
 */

defined( 'ABSPATH' ) || exit;
?>
<main class="single-post">
	<header class="single-post-header">
		<figure class="single-post-header-thumbnail">
			<?php the_post_thumbnail( 'full', array( 'loading' => 'lazy' ) ); ?>
		</figure>
		<div class="single-post-header-content">
			<h1 class="single-post-heading">
				<?php the_title(); ?>
			</h1>
			<div class="single-post-excerpt">
				<?php the_excerpt(); ?>
			</div>
			<div class="single-post-header-meta">
				<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
					<?php echo esc_html( get_the_date( 'j. F Y' ) ); ?>
				</time>
				<?php if ( get_the_author() ) : ?>
					<span class="post-card-author ft-visually-hidden">
					<?php echo esc_html( get_the_author() ); ?>
				</span>
				<?php endif; ?>
			</div>
		</div>
	</header>

	<section class="single-post-content">
		<?php the_content(); ?>

		<div class="single-post-content-meta">
			<?php if ( ! empty( get_the_category() && count( get_the_category() ) ) ) : ?>
				<div class="single-post-content-categories">
					<span>
						<?php echo esc_html_x( 'Categories: ', 'post meta label', 'bricks-child' ); ?>
					</span>
					<?php the_category( ', ' ); ?>
				</div>
			<?php endif; ?>
			<?php if ( ! empty( get_the_tags() ) && count( get_the_tags() ) ) : ?>
				<div class="single-post-content-tags">
					<span>
						<?php echo esc_html_x( 'Tags: ', 'post meta label', 'bricks-child' ); ?>
					</span>
					<?php the_tags( '#', ', #' ); ?>
				</div>
			<?php endif; ?>
			<div class="single-post-header-meta">
				<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
					<?php echo esc_html( get_the_date( 'j. F Y' ) ); ?>
				</time>
				<?php if ( get_the_author() ) : ?>
					<span class="post-card-author ft-visually-hidden">
					<?php echo esc_html( get_the_author() ); ?>
				</span>
				<?php endif; ?>
			</div>
		</div>
	</section>

	<?php bricks_child_display_related_posts( get_the_ID(), 'category', 3 ); ?>
</main>
