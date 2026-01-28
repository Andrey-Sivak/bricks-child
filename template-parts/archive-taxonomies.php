<?php
/**
 * Category + Tag Lists
 *
 * @package Bricks_Child
 */

$categories = get_categories(
	array(
		'orderby' => 'name',
		'order'   => 'ASC',
	)
);

$tags = get_tags(
	array(
		'orderby' => 'name',
		'order'   => 'ASC',
	)
);

$tax = get_queried_object_id();
?>

<nav class="archive-taxonomies" aria-label="<?php esc_attr_e( 'Blog Taxonomies', 'bricks-child' ); ?>">
	<?php if ( $categories && ! is_tag() ) : ?>
		<section class="archive-categories">
			<h2 class="ft-visually-hidden">
				<?php esc_html_e( 'Categories', 'bricks-child' ); ?>
			</h2>
			<ul class="archive-categories-list">
				<?php foreach ( $categories as $cat ) : ?>
					<?php if ( $cat->term_id === $tax ) : ?>
						<li class="archive-categories-item">
							<span class="archive-categories-item-current">
								<?php echo esc_html( $cat->name ); ?>
							</span>
						</li>
					<?php else : ?>
						<li class="archive-categories-item">
							<a
                                    class="archive-categories-item-link"
                                    href="<?php echo esc_url( get_category_link( $cat ) ); ?>"
                            >
								<?php echo esc_html( $cat->name ); ?>
							</a>
						</li>
					<?php endif; ?>
				<?php endforeach; ?>
			</ul>
		</section>
	<?php endif; ?>

	<?php if ( $tags && ! is_category() ) : ?>
		<section class="archive-tags">
			<h2 class="ft-visually-hidden">
				<?php esc_html_e( 'Tags', 'bricks-child' ); ?>
			</h2>
			<ul class="archive-tags-list">
				<?php foreach ( $tags as $tag ) : ?>
					<?php if ( $tag->term_id === $tax ) : ?>
						<li class="archive-tags-item">
							<span>
								#<?php echo esc_html( $tag->name ); ?>
							</span>
						</li>
					<?php else : ?>
						<li class="archive-tags-item">
							<a href="<?php echo esc_url( get_tag_link( $tag ) ); ?>">
								#<?php echo esc_html( $tag->name ); ?>
							</a>
						</li>
					<?php endif; ?>
				<?php endforeach; ?>
			</ul>
		</section>
	<?php endif; ?>
</nav>
