<?php
/**
 * Custom template loader for custom post types
 *
 * @package Bricks_Child
 */

defined( 'ABSPATH' ) || exit;

/**
 * Load custom template for custom post types
 *
 * @param string $template Current template path.
 * @return string Modified template path.
 */
function bricks_child_custom_post_type_template( $template ) {
	// Get current post type.
	$post_type = get_post_type();

	// Define custom post types that need custom templates.
	// Add your custom post type slugs here.
	$custom_post_types = array(
		'portfolio',
		'service',
	);

	// Check if current post type needs custom template.
	if ( ! in_array( $post_type, $custom_post_types, true ) ) {
		return $template;
	}

	// Single post template.
	if ( is_singular( $custom_post_types ) ) {
		$custom_template = bricks_child_locate_template( "templates/single-{$post_type}.php" );
		if ( $custom_template ) {
			return $custom_template;
		}
	}

	// Archive template.
	if ( is_post_type_archive( $custom_post_types ) ) {
		$custom_template = bricks_child_locate_template( "templates/archive-{$post_type}.php" );
		if ( $custom_template ) {
			return $custom_template;
		}
	}

	// Taxonomy template.
	if ( is_tax() ) {
		$term     = get_queried_object();
		$taxonomy = $term->taxonomy;

		// Try taxonomy-term template.
		$custom_template = bricks_child_locate_template( "templates/taxonomy-{$taxonomy}-{$term->slug}.php" );
		if ( $custom_template ) {
			return $custom_template;
		}

		// Try taxonomy template.
		$custom_template = bricks_child_locate_template( "templates/taxonomy-{$taxonomy}.php" );
		if ( $custom_template ) {
			return $custom_template;
		}
	}

	return $template;
}
add_filter( 'template_include', 'bricks_child_custom_post_type_template', 99 );

/**
 * Locate template in child theme
 *
 * @param string $template_name Template name.
 * @return string|false Template path or false if not found.
 */
function bricks_child_locate_template( $template_name ) {
	$template_path = BRICKS_CHILD_DIR . '/' . $template_name;

	if ( file_exists( $template_path ) ) {
		return $template_path;
	}

	return false;
}

/**
 * Get template part for custom post types
 * Similar to WordPress get_template_part() but with args support
 *
 * @param string $slug Template slug.
 * @param string $name Template name (optional).
 * @param array  $args Arguments to pass to template (optional).
 * @return void
 */
function bricks_child_get_template_part( $slug, $name = '', $args = array() ) {
	// Extract args for use in template.
	if ( $args && is_array( $args ) ) {
        // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
		extract( $args );
	}

	$templates = array();

	if ( $name ) {
		$templates[] = "templates/{$slug}-{$name}.php";
	}
	$templates[] = "templates/{$slug}.php";

	// Try to locate template.
	$located = false;
	foreach ( $templates as $template ) {
		$template_path = BRICKS_CHILD_DIR . '/' . $template;
		if ( file_exists( $template_path ) ) {
			$located = $template_path;
			break;
		}
	}

	if ( $located ) {
		load_template( $located, false, $args );
	}
}

/**
 * Include template with args
 * Use this when you need to include a template and pass variables
 *
 * @param string $template_name Template name (relative to child theme root).
 * @param array  $args          Arguments to pass to template.
 * @param bool   $is_return        Whether to return output or echo it.
 * @return string|void Template output if $return is true.
 */
function bricks_child_include_template( $template_name, $args = array(), $is_return = false ) {
	$template_path = BRICKS_CHILD_DIR . '/' . $template_name;

	if ( ! file_exists( $template_path ) ) {
		return '';
	}

	// Extract args for use in template.
	if ( $args && is_array( $args ) ) {
        // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
		extract( $args );
	}

	if ( $is_return ) {
		ob_start();
		include $template_path;
		return ob_get_clean();
	}

	include $template_path;
}

/**
 * Add custom body class for custom post types
 *
 * @param array $classes Existing body classes.
 * @return array Modified body classes.
 */
function bricks_child_cpt_body_class( $classes ) {
	// Single custom post type.
	if ( is_singular() ) {
		$post_type = get_post_type();
		if ( 'post' !== $post_type && 'page' !== $post_type ) {
			$classes[] = 'single-' . sanitize_html_class( $post_type );
		}
	}

	// Custom post type archive.
	if ( is_post_type_archive() ) {
		$post_type = get_query_var( 'post_type' );
		if ( is_array( $post_type ) ) {
			$post_type = reset( $post_type );
		}
		$classes[] = 'archive-' . sanitize_html_class( $post_type );
	}

	// Custom taxonomy.
	if ( is_tax() ) {
		$term      = get_queried_object();
		$taxonomy  = $term->taxonomy;
		$classes[] = 'taxonomy-' . sanitize_html_class( $taxonomy );
		$classes[] = 'term-' . sanitize_html_class( $term->slug );
	}

	return $classes;
}
add_filter( 'body_class', 'bricks_child_cpt_body_class' );

/**
 * Add custom post class for custom post types
 *
 * @param array  $css_classes Post classes.
 * @param string $css_class   Additional class(es).
 * @param int    $post_id Post ID.
 * @return array Modified post classes.
 */
function bricks_child_cpt_post_class( $css_classes, $css_class, $post_id ) {
	$post_type = get_post_type( $post_id );

	if ( 'post' !== $post_type && 'page' !== $post_type ) {
		$css_classes[] = 'cpt-' . sanitize_html_class( $post_type );
	}

	return $css_classes;
}
add_filter( 'post_class', 'bricks_child_cpt_post_class', 10, 3 );

/**
 * Modify custom post type query
 * Example: Change posts per page for custom post type archives
 *
 * @param WP_Query $query The WordPress query object.
 * @return void
 */
function bricks_child_modify_cpt_query( $query ) {
	// Only modify main query on frontend.
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	// Example: Show 12 posts per page for portfolio archive.
	// if ( $query->is_post_type_archive( 'portfolio' ) ) {
	//  $query->set( 'posts_per_page', 12 );
	// }

	// Example: Custom ordering for testimonials.
	// if ( $query->is_post_type_archive( 'testimonial' ) ) {
	//  $query->set( 'orderby', 'menu_order' );
	//  $query->set( 'order', 'ASC' );
	// }

	// Example: Filter by custom taxonomy term.
	// if ( $query->is_tax( 'portfolio_category' ) ) {
	//  $query->set( 'posts_per_page', 9 );
	// }
}
add_action( 'pre_get_posts', 'bricks_child_modify_cpt_query' );

/**
 * Register custom template paths for Bricks Builder
 * Allows Bricks to find templates in child theme
 *
 * @param array $paths Existing template paths.
 * @return array Modified template paths.
 */
function bricks_child_add_template_paths( $paths ) {
	$paths[] = BRICKS_CHILD_DIR . '/templates/';
	return $paths;
}
add_filter( 'bricks/builder/template_paths', 'bricks_child_add_template_paths' );

/**
 * Get custom post type icon (if using custom icons)
 *
 * @param string $post_type Post type slug.
 * @return string Icon class or dashicon.
 */
function bricks_child_get_cpt_icon( $post_type ) {
	$icons = array(
		'portfolio'   => 'dashicons-portfolio',
		'testimonial' => 'dashicons-testimonial',
		'team'        => 'dashicons-groups',
	);

	return isset( $icons[ $post_type ] ) ? $icons[ $post_type ] : 'dashicons-admin-post';
}

/**
 * Custom breadcrumbs for custom post types
 *
 * @param string $post_type Post type slug.
 * @return array Breadcrumb items.
 */
function bricks_child_get_cpt_breadcrumbs( $post_type = '' ) {
	if ( ! $post_type ) {
		$post_type = get_post_type();
	}

	$breadcrumbs = array();

	// Home link.
	$breadcrumbs[] = array(
		'text' => _x( 'Home', 'breadcrumb', 'bricks-child' ),
		'url'  => home_url( '/' ),
	);

	// Post type archive link.
	$post_type_object = get_post_type_object( $post_type );
	if ( $post_type_object && $post_type_object->has_archive ) {
		$breadcrumbs[] = array(
			'text' => $post_type_object->labels->name,
			'url'  => get_post_type_archive_link( $post_type ),
		);
	}

	// Current post (if singular).
	if ( is_singular( $post_type ) ) {
		$breadcrumbs[] = array(
			'text' => get_the_title(),
			'url'  => '',
		);
	}

	return apply_filters( 'bricks_child_cpt_breadcrumbs', $breadcrumbs, $post_type );
}

/**
 * Display breadcrumbs
 *
 * @param string $post_type Post type slug.
 * @return void
 */
function bricks_child_display_breadcrumbs( $post_type = '' ) {
	$breadcrumbs = bricks_child_get_cpt_breadcrumbs( $post_type );

	if ( empty( $breadcrumbs ) ) {
		return;
	}

	printf(
		'<nav class="breadcrumbs" aria-label="%s">',
		esc_attr_x( 'Breadcrumb', 'navigation landmark', 'bricks-child' )
	);
	echo '<ol class="breadcrumb-list">';

	foreach ( $breadcrumbs as $index => $crumb ) {
		$is_last = ( count( $breadcrumbs ) - 1 === $index );

		echo '<li class="breadcrumb-item' . ( $is_last ? ' active' : '' ) . '">';

		if ( ! empty( $crumb['url'] ) ) {
			echo '<a href="' . esc_url( $crumb['url'] ) . '">';
			echo esc_html( $crumb['text'] );
			echo '</a>';
		} else {
			echo '<span>' . esc_html( $crumb['text'] ) . '</span>';
		}

		echo '</li>';

		// Add separator (except for last item).
		if ( ! $is_last ) {
			echo '<li class="breadcrumb-separator" aria-hidden="true">/</li>';
		}
	}

	echo '</ol>';
	echo '</nav>';
}

/**
 * Get related posts for custom post types
 *
 * @param int    $post_id       Current post ID.
 * @param string $taxonomy      Taxonomy to use for relation.
 * @param int    $posts_per_page Number of posts to retrieve.
 * @return WP_Query|false Query object or false if no results.
 */
function bricks_child_get_related_posts( $post_id = 0, $taxonomy = '', $posts_per_page = 3 ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	$post_type = get_post_type( $post_id );

	// Get terms from the specified taxonomy.
	$terms = get_the_terms( $post_id, $taxonomy );

	if ( empty( $terms ) || is_wp_error( $terms ) ) {
		return false;
	}

	$term_ids = wp_list_pluck( $terms, 'term_id' );

	$args = array(
		'post_type'      => $post_type,
		'posts_per_page' => $posts_per_page,
		'post__not_in'   => array( $post_id ),
		'orderby'        => 'rand',
		'tax_query'      => array(
			array(
				'taxonomy' => $taxonomy,
				'field'    => 'term_id',
				'terms'    => $term_ids,
			),
		),
	);

	$related_query = new WP_Query( $args );

	return $related_query->have_posts() ? $related_query : false;
}

/**
 * Display related posts
 *
 * @param int    $post_id       Current post ID.
 * @param string $taxonomy      Taxonomy to use for relation.
 * @param int    $posts_per_page Number of posts to display.
 * @return void
 */
function bricks_child_display_related_posts( $post_id = 0, $taxonomy = '', $posts_per_page = 3 ) {
	$related_query = bricks_child_get_related_posts( $post_id, $taxonomy, $posts_per_page );

	if ( ! $related_query ) {
		return;
	}

	?>
	<section class="related-posts">
		<h2 class="related-posts-title">
			<?php echo esc_html_x( 'Related Posts', 'section heading', 'bricks-child' ); ?>
		</h2>
		<div class="related-posts-grid">
			<?php
			while ( $related_query->have_posts() ) {
				$related_query->the_post();
				?>
				<article class="related-post-item">
					<?php if ( has_post_thumbnail() ) : ?>
						<a href="<?php the_permalink(); ?>" class="related-post-thumbnail">
							<?php the_post_thumbnail( 'medium' ); ?>
						</a>
					<?php endif; ?>
					<h3 class="related-post-title">
						<a href="<?php the_permalink(); ?>">
							<?php the_title(); ?>
						</a>
					</h3>
					<div class="related-post-excerpt">
						<?php echo esc_html( wp_trim_words( get_the_excerpt(), 20 ) ); ?>
					</div>
					<a href="<?php the_permalink(); ?>" class="related-post-link">
						<?php echo esc_html_x( 'Read More', 'link text', 'bricks-child' ); ?>
					</a>
				</article>
				<?php
			}
			wp_reset_postdata();
			?>
		</div>
	</section>
	<?php
}

/**
 * Get custom field value with fallback
 *
 * @param string $field_name Field name/key.
 * @param int    $post_id    Post ID.
 * @param mixed  $default_value    Default value if field is empty.
 * @return mixed Field value or default.
 */
function bricks_child_get_field( $field_name, $post_id = 0, $default_value = '' ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	// Check if ACF is active.
	if ( function_exists( 'get_field' ) ) {
		$value = get_field( $field_name, $post_id );
		return ! empty( $value ) ? $value : $default_value;
	}

	// Fallback to regular post meta.
	$value = get_post_meta( $post_id, $field_name, true );
	return ! empty( $value ) ? $value : $default_value;
}

/**
 * Display custom field
 *
 * @param string $field_name Field name/key.
 * @param int    $post_id    Post ID.
 * @param mixed  $default_value    Default value if field is empty.
 * @return void
 */
function bricks_child_the_field( $field_name, $post_id = 0, $default_value = '' ) {
	echo esc_html( bricks_child_get_field( $field_name, $post_id, $default_value ) );
}

/**
 * Check if custom post type has terms in taxonomy
 *
 * @param string $taxonomy Taxonomy slug.
 * @param int    $post_id  Post ID.
 * @return bool True if has terms, false otherwise.
 */
function bricks_child_has_terms( $taxonomy, $post_id = 0 ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	$terms = get_the_terms( $post_id, $taxonomy );

	return ! empty( $terms ) && ! is_wp_error( $terms );
}

/**
 * Display terms for custom post type
 *
 * @param string $taxonomy  Taxonomy slug.
 * @param int    $post_id   Post ID.
 * @param string $separator Separator between terms.
 * @param string $before    Text before terms.
 * @param string $after     Text after terms.
 * @return void
 */
function bricks_child_display_terms( $taxonomy, $post_id = 0, $separator = ', ', $before = '', $after = '' ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	$terms = get_the_terms( $post_id, $taxonomy );

	if ( empty( $terms ) || is_wp_error( $terms ) ) {
		return;
	}

	echo wp_kses_post( $before );

	$term_links = array();
	foreach ( $terms as $term ) {
		$term_links[] = sprintf(
			'<a href="%s" rel="tag">%s</a>',
			esc_url( get_term_link( $term ) ),
			esc_html( $term->name )
		);
	}

	echo implode( $separator, $term_links ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	echo wp_kses_post( $after );
}