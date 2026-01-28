<?php
/**
 * Single Template.
 *
 * @package Bricks_Child
 */

defined( 'ABSPATH' ) || exit;

get_header();

if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();

		$bricks_child_post_id   = get_the_ID();
		$bricks_child_post_type = get_post_type();

		if ( 'post' === $bricks_child_post_type ) {
			get_template_part( 'template-parts/post' );
		}
	}
}

get_footer();
