<?php
/**
 * Category + Tag Lists
 *
 * @package Bricks_Child
 */

defined( 'ABSPATH' ) || exit;

$bricks_child_taxonomies     = bricks_child_get_archive_taxonomies();
$bricks_child_queried_object = get_queried_object();
$bricks_child_current_tax_id = get_queried_object_id();
$bricks_child_current_tax    = $bricks_child_queried_object->taxonomy ?? '';

if ( empty( $bricks_child_taxonomies ) ) {
	return;
}

$bricks_child_categories     = isset( $bricks_child_taxonomies['category'] ) ? bricks_child_get_archive_terms( $bricks_child_taxonomies['category']['taxonomy'] ) : array();
$bricks_child_tags           = isset( $bricks_child_taxonomies['tag'] ) ? bricks_child_get_archive_terms( $bricks_child_taxonomies['tag']['taxonomy'] ) : array();
$bricks_child_is_tag_archive = is_tag() || ( is_tax() && $bricks_child_current_tax === ( $bricks_child_taxonomies['tag']['taxonomy'] ?? '' ) );
$bricks_child_is_cat_archive = is_category() || ( is_tax() && $bricks_child_current_tax === ( $bricks_child_taxonomies['category']['taxonomy'] ?? '' ) );
?>

<nav class="archive-taxonomies" aria-label="<?php echo esc_attr_x( 'Archive Taxonomies', 'navigation landmark', 'bricks-child' ); ?>">
	<?php if ( ! empty( $bricks_child_categories ) && ! is_wp_error( $bricks_child_categories ) && ! $bricks_child_is_tag_archive ) : ?>
		<section class="archive-categories">
			<h2 class="ft-visually-hidden">
				<?php echo esc_html( $bricks_child_taxonomies['category']['label'] ); ?>
			</h2>
			<ul class="archive-categories-list">
				<?php foreach ( $bricks_child_categories as $bricks_child_cat ) : ?>
					<li class="archive-categories-item">
						<?php if ( $bricks_child_cat->term_id === $bricks_child_current_tax_id ) : ?>
							<span class="archive-categories-item-current">
								<?php echo esc_html( $bricks_child_cat->name ); ?>
							</span>
						<?php else : ?>
							<a
									class="archive-categories-item-link"
									href="<?php echo esc_url( get_term_link( $bricks_child_cat ) ); ?>"
							>
								<?php echo esc_html( $bricks_child_cat->name ); ?>
							</a>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
		</section>
	<?php endif; ?>

	<?php if ( ! empty( $bricks_child_tags ) && ! is_wp_error( $bricks_child_tags ) && ! $bricks_child_is_cat_archive ) : ?>
		<section class="archive-tags">
			<h2 class="ft-visually-hidden">
				<?php echo esc_html( $bricks_child_taxonomies['tag']['label'] ); ?>
			</h2>
			<ul class="archive-tags-list">
				<?php foreach ( $bricks_child_tags as $bricks_child_tag ) : ?>
					<?php $bricks_child_prefix = $bricks_child_taxonomies['tag']['prefix'] ?? ''; ?>
					<li class="archive-tags-item">
						<?php if ( $bricks_child_tag->term_id === $bricks_child_current_tax_id ) : ?>
							<span class="archive-tags-item-current">
								<?php echo esc_html( $bricks_child_prefix . $bricks_child_tag->name ); ?>
							</span>
						<?php else : ?>
							<a
									class="archive-tags-item-link"
									href="<?php echo esc_url( get_term_link( $bricks_child_tag ) ); ?>"
							>
								<?php echo esc_html( $bricks_child_prefix . $bricks_child_tag->name ); ?>
							</a>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
		</section>
	<?php endif; ?>
</nav>