<?php
/**
 * Archive Template.
 *
 * @package Bricks_Child
 */

defined( 'ABSPATH' ) || exit;

get_header();

if ( is_tax( array( 'ft_portfolio_cat', 'ft_portfolio_tag' ) ) ) {
	get_template_part( 'template-parts/content', 'archive-ft_portfolio' );
} else {
	get_template_part( 'template-parts/content', 'archive' );
}

get_footer();
