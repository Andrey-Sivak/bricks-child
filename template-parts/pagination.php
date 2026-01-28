<?php
/**
 * Numeric pagination.
 *
 * @package Bricks_Child
 */

defined( 'ABSPATH' ) || exit;

global $wp_query;

$bricks_child_big   = 999999999; // need an unlikely integer
$bricks_child_pages = paginate_links(
	array(
		'base'      => str_replace( $bricks_child_big, '%#%', esc_url( get_pagenum_link( $bricks_child_big ) ) ),
		'format'    => '?paged=%#%',
		'current'   => max( 1, get_query_var( 'paged' ) ),
		'total'     => $wp_query->max_num_pages ?? 1,
		'type'      => 'array',
		'prev_text' => '&laquo;',
		'next_text' => '&raquo;',
	)
);

if ( is_array( $bricks_child_pages ) ) {
	printf(
		'<nav class="pagination" aria-label="%s"><ul>',
		esc_attr_x( 'Posts Pagination', 'navigation landmark', 'bricks-child' )
	);
	foreach ( $bricks_child_pages as $bricks_child_page ) {
		echo '<li>' . wp_kses_post( $bricks_child_page ) . '</li>';
	}
	echo '</ul></nav>';
}
