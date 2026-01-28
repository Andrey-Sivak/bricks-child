<?php
/**
 * Archive Template
 *
 * @package Bricks_Child
 */

defined( 'ABSPATH' ) || exit;

$bricks_child_blog_title = esc_html_x( 'Blog', 'page title', 'bricks-child' );
$bricks_child_blog_desc  = get_bloginfo( 'description' );

if ( is_category() ) {
	$bricks_child_blog_title = single_cat_title( '', false );
	$bricks_child_blog_desc  = category_description();
} elseif ( is_tag() ) {
	$bricks_child_blog_title = single_tag_title( '', false );
	$bricks_child_blog_desc  = tag_description();
}

$bricks_child_blog_desc = $bricks_child_blog_desc
		? wp_kses_post( $bricks_child_blog_desc )
		: esc_html_x(
			'Latest articles and insights.',
			'blog archive description',
			'bricks-child'
		);
?>

<div class="ft-archive">
	<div class="ft-archive-container">
		<header class="archive-header">
			<h1 class="archive-title">
				<?php echo esc_html( $bricks_child_blog_title ); ?>
			</h1>
			<?php if ( $bricks_child_blog_desc ) : ?>
				<div class="archive-description">
					<?php echo wp_kses_post( $bricks_child_blog_desc ); ?>
				</div>
			<?php endif; ?>
		</header>

		<?php
		get_template_part( 'template-parts/archive-taxonomies' );
		?>

		<section class="posts-list" aria-label="<?php esc_attr( $bricks_child_blog_title ); ?>">
			<?php
			$bricks_child_paged = max( 1, get_query_var( 'paged' ) );

			$bricks_child_args = array(
				'post_type'      => 'post',
				'posts_per_page' => 12,
				'paged'          => $bricks_child_paged,
			);

			if ( is_category() ) {
				$bricks_child_args['cat'] = get_queried_object_id();
			}
			if ( is_tag() ) {
				$bricks_child_args['tag_id'] = get_queried_object_id();
			}

			$bricks_child_query = new WP_Query( $bricks_child_args );

			if ( $bricks_child_query->have_posts() ) :
				while ( $bricks_child_query->have_posts() ) :
					$bricks_child_query->the_post();
					get_template_part( 'template-parts/loop-post-card' );
				endwhile;
			else :
				printf(
					'<p>%s</p>',
					esc_html_x( 'No posts found.', 'empty state message', 'bricks-child' )
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
