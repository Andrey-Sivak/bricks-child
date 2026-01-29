<?php
/**
 * Archive Portfolio Template
 *
 * @package Bricks_Child
 */

defined( 'ABSPATH' ) || exit;

$bricks_child_term            = get_queried_object();
$bricks_child_portfolio_title = esc_html_x( 'Portfolio', 'portfolio page title', 'bricks-child' );
$bricks_child_portfolio_desc  = esc_html_x(
	'A collection of cherished memories and meaningful moments',
	'portfolio page description',
	'bricks-child'
);

if ( is_tax() ) {
	$bricks_child_portfolio_title = $bricks_child_term->name;
	$bricks_child_portfolio_desc  = $bricks_child_term->description;

	if ( is_category() && empty( $bricks_child_portfolio_desc ) ) {
		$bricks_child_portfolio_desc = sprintf(
		/* translators: %s: category name */
			esc_html_x( 'Selected works from %s', 'default category description', 'bricks-child' ),
			strtolower( $bricks_child_portfolio_title )
		);
	} elseif ( is_tag() && empty( $bricks_child_portfolio_desc ) ) {
		$bricks_child_portfolio_desc = sprintf(
		/* translators: %s: tag name */
			esc_html_x( 'Selected works from %s', 'default tag description', 'bricks-child' ),
			strtolower( $bricks_child_portfolio_title )
		);
	}
}
?>

<div class="ft-archive">
	<div class="ft-archive-container">
		<header class="archive-header">
			<h1 class="archive-title">
				<?php echo esc_html( $bricks_child_portfolio_title ); ?>
			</h1>
			<?php if ( $bricks_child_portfolio_desc ) : ?>
				<div class="archive-description">
					<?php echo wp_kses_post( $bricks_child_portfolio_desc ); ?>
				</div>
			<?php endif; ?>
		</header>

		<?php
		get_template_part( 'template-parts/archive-taxonomies' );
		?>

		<section class="posts-list" aria-label="<?php esc_attr( $bricks_child_portfolio_title ); ?>">
			<?php
			$bricks_child_paged = max( 1, get_query_var( 'paged' ) );

			$bricks_child_args = array(
				'post_type'      => 'ft_portfolio',
				'posts_per_page' => 12,
				'paged'          => $bricks_child_paged,
			);

			if ( is_category() || is_tag() || is_tax() ) {
				$bricks_child_queried_object = get_queried_object();

				$bricks_child_args['tax_query'] = array(
					array(
						'taxonomy' => $bricks_child_queried_object->taxonomy,
						'field'    => 'term_id',
						'terms'    => $bricks_child_queried_object->term_id,
					),
				);
			}

			$bricks_child_query = new WP_Query( $bricks_child_args );

			if ( $bricks_child_query->have_posts() ) :
				while ( $bricks_child_query->have_posts() ) :
					$bricks_child_query->the_post();
					get_template_part( 'template-parts/loop-post-card' );
				endwhile;
			else :
				printf(
					'<p class="ft-archive-not-found">%s</p>',
					esc_html_x( 'No items found.', 'empty state message', 'bricks-child' )
				);
			endif;
			wp_reset_postdata();
			?>
		</section>

		<?php
		get_template_part( 'template-parts/pagination' );
		?>

	</div>
</div>
