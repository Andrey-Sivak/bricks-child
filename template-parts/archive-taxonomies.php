<?php
/**
 * Category + Tag Lists
 *
 * @package Bricks_Child
 */

defined( 'ABSPATH' ) || exit;

$bricks_child_categories = get_categories(
	array(
		'orderby' => 'name',
		'order'   => 'ASC',
	)
);

$bricks_child_tags = get_tags(
	array(
		'orderby' => 'name',
		'order'   => 'ASC',
	)
);

$bricks_child_tax = get_queried_object_id();
?>

<nav
	class="archive-taxonomies"
	aria-label="<?php echo esc_attr_x( 'Blog Taxonomies', 'navigation landmark', 'bricks-child' ); ?>"
>
	<?php if ( $bricks_child_categories && ! is_tag() ) : ?>
		<section class="archive-categories">
			<h2 class="ft-visually-hidden">
				<?php echo esc_html_x( 'Categories', 'section heading', 'bricks-child' ); ?>
			</h2>
			<ul class="archive-categories-list">
				<?php foreach ( $bricks_child_categories as $bricks_child_cat ) : ?>
					<?php if ( $bricks_child_cat->term_id === $bricks_child_tax ) : ?>
						<li class="archive-categories-item">
							<span class="archive-categories-item-current">
								<?php echo esc_html( $bricks_child_cat->name ); ?>
							</span>
						</li>
					<?php else : ?>
						<li class="archive-categories-item">
							<a
									class="archive-categories-item-link"
									href="<?php echo esc_url( get_category_link( $bricks_child_cat ) ); ?>"
							>
								<?php echo esc_html( $bricks_child_cat->name ); ?>
							</a>
						</li>
					<?php endif; ?>
				<?php endforeach; ?>
			</ul>
		</section>
	<?php endif; ?>

	<?php if ( $bricks_child_tags && ! is_category() ) : ?>
		<section class="archive-tags">
			<h2 class="ft-visually-hidden">
				<?php echo esc_html_x( 'Tags', 'section heading', 'bricks-child' ); ?>
			</h2>
			<ul class="archive-tags-list">
				<?php foreach ( $bricks_child_tags as $bricks_child_tag ) : ?>
					<?php if ( $bricks_child_tag->term_id === $bricks_child_tax ) : ?>
						<li class="archive-tags-item">
							<span>
								#<?php echo esc_html( $bricks_child_tag->name ); ?>
							</span>
						</li>
					<?php else : ?>
						<li class="archive-tags-item">
							<a href="<?php echo esc_url( get_tag_link( $bricks_child_tag ) ); ?>">
								#<?php echo esc_html( $bricks_child_tag->name ); ?>
							</a>
						</li>
					<?php endif; ?>
				<?php endforeach; ?>
			</ul>
		</section>
	<?php endif; ?>
</nav>
