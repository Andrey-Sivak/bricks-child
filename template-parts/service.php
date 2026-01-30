<?php
/**
 * Service Template.
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
		</div>
	</header>

	<section class="single-post-content">
		<?php the_content(); ?>
	</section>

	<?php bricks_child_display_related_posts( get_the_ID() ); ?>
</main>
