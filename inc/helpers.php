<?php
/**
 * Helper functions.
 *
 * @package Bricks_Child
 */

defined( 'ABSPATH' ) || exit;

/**
 * Get taxonomies configuration for archive navigation
 *
 * @param string $post_type Post type slug.
 * @return array Taxonomies configuration or empty array.
 */
function bricks_child_get_archive_taxonomies( $post_type = '' ) {
	if ( empty( $post_type ) ) {
		$post_type = get_post_type();

		if ( is_post_type_archive() ) {
			$post_type = get_query_var( 'post_type' );
		} elseif ( is_tax() || is_category() || is_tag() ) {
			$queried_object = get_queried_object();
			$taxonomy       = $queried_object->taxonomy ?? '';

			$taxonomy_map = array(
				'category'            => 'post',
				'post_tag'            => 'post',
				'ft_portfolio_cat'    => 'ft_portfolio',
				'ft_portfolio_tag'    => 'ft_portfolio',
				'ft_service_category' => 'ft_service',
				'ft_service_tag'      => 'ft_service',
			);

			$post_type = $taxonomy_map[ $taxonomy ] ?? 'post';
		}
	}

	$config = array(
		'post'         => array(
			'category' => array(
				'taxonomy' => 'category',
				'label'    => _x( 'Categories', 'section heading', 'bricks-child' ),
				'prefix'   => '',
			),
			'tag'      => array(
				'taxonomy' => 'post_tag',
				'label'    => _x( 'Tags', 'section heading', 'bricks-child' ),
				'prefix'   => '#',
			),
		),
		'ft_portfolio' => array(
			'category' => array(
				'taxonomy' => 'ft_portfolio_cat',
				'label'    => _x( 'Portfolio Categories', 'section heading', 'bricks-child' ),
				'prefix'   => '',
			),
			'tag'      => array(
				'taxonomy' => 'ft_portfolio_tag',
				'label'    => _x( 'Portfolio Tags', 'section heading', 'bricks-child' ),
				'prefix'   => '#',
			),
		),
		'ft_service'   => array(
			'category' => array(
				'taxonomy' => 'ft_service_category',
				'label'    => _x( 'Service Categories', 'section heading', 'bricks-child' ),
				'prefix'   => '',
			),
			'tag'      => array(
				'taxonomy' => 'ft_service_tag',
				'label'    => _x( 'Service Tags', 'section heading', 'bricks-child' ),
				'prefix'   => '#',
			),
		),
	);

	return $config[ $post_type ] ?? array();
}

/**
 * Get terms for archive taxonomy navigation
 *
 * @param string $taxonomy Taxonomy slug.
 * @return array|WP_Error Array of term objects or WP_Error.
 */
function bricks_child_get_archive_terms( $taxonomy ) {
	return get_terms(
		array(
			'taxonomy'   => $taxonomy,
			'orderby'    => 'name',
			'order'      => 'ASC',
			'hide_empty' => true,
		)
	);
}
