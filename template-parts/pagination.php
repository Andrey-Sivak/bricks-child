<?php
/**
 * Numeric pagination.
 *
 * @package Bricks_Child
 */

defined( 'ABSPATH' ) || exit;

global $wp_query;

$big   = 999999999; // need an unlikely integer
$pages = paginate_links(
	array(
		'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
		'format'    => '?paged=%#%',
		'current'   => max( 1, get_query_var( 'paged' ) ),
		'total'     => $wp_query->max_num_pages ?? 1,
		'type'      => 'array',
		'prev_text' => '&laquo;',
		'next_text' => '&raquo;',
	)
);

if ( is_array( $pages ) ) {
	echo '<nav class="pagination" aria-label="' . esc_attr__( 'Posts Pagination', 'bricks-child' ) . '"><ul>';
	foreach ( $pages as $page ) {
		echo '<li>' . wp_kses_post( $page ) . '</li>';
	}
	echo '</ul></nav>';
}
