<?php
/**
 * Single Portfolio Template.
 *
 * @package Bricks_Child
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

	<main class="single-post single-portfolio">
		<header class="single-post-header">
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

			<div class="single-post-content-meta">
				<?php
				$bricks_child_post_type = get_post_type();

				$bricks_child_taxonomies = get_object_taxonomies( $bricks_child_post_type, 'objects' );

				$bricks_child_category_taxonomies = array();
				$bricks_child_tag_taxonomies      = array();

				foreach ( $bricks_child_taxonomies as $bricks_child_taxonomy ) {
					if ( $bricks_child_taxonomy->name === 'post_format' ) {
						continue;
					}

					if ( $bricks_child_taxonomy->hierarchical ) {
						$bricks_child_category_taxonomies[] = $bricks_child_taxonomy->name;
					} else {
						$bricks_child_tag_taxonomies[] = $bricks_child_taxonomy->name;
					}
				}

				if ( ! empty( $bricks_child_category_taxonomies ) ) :
					?>
					<div class="single-post-content-categories">
					<span>
						<?php echo esc_html_x( 'Categories: ', 'post meta label', 'bricks-child' ); ?>
					</span>
						<?php foreach ( $bricks_child_category_taxonomies as $bricks_child_category_taxonomy ) : ?>
							<?php
							$bricks_child_terms = get_the_terms( get_the_ID(), $bricks_child_category_taxonomy );
							if ( ! empty( $bricks_child_terms ) && ! is_wp_error( $bricks_child_terms ) ) :
								$bricks_child_term_links = array_map(
									function ( $term ) use ( $bricks_child_category_taxonomy ) {
										return '<a href="' . esc_url( get_term_link( $term, $bricks_child_category_taxonomy ) ) . '">' . esc_html( $term->name ) . '</a>';
									},
									$bricks_child_terms
								);
								echo implode( ', ', $bricks_child_term_links );
								?>
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
				<?php if ( $bricks_child_tag_taxonomies ) : ?>
					<div class="single-post-content-tags">
					<span>
						<?php echo esc_html_x( 'Tags: ', 'post meta label', 'bricks-child' ); ?>
					</span>
						<?php foreach ( $bricks_child_tag_taxonomies as $bricks_child_tag_taxonomy ) : ?>
							<?php
							$bricks_child_terms = get_the_terms( get_the_ID(), $bricks_child_tag_taxonomy );
							if ( ! empty( $bricks_child_terms ) && ! is_wp_error( $bricks_child_terms ) ) :
								$bricks_child_term_links = array_map(
									function ( $term ) use ( $bricks_child_tag_taxonomy ) {
										return '<a href="' . esc_url( get_term_link( $term, $bricks_child_tag_taxonomy ) ) . '">#' . esc_html( $term->name ) . '</a>';
									},
									$bricks_child_terms
								);
								echo implode( ', ', $bricks_child_term_links );
								?>
							<?php endif; ?>
						<?php endforeach; ?>
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
	</main>

<?php
get_footer();
