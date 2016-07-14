<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package MG\Theme
 */

get_header();
while ( have_posts() ) : the_post();
	get_template_part( 'template-parts/content', get_post_format() );
	add_filter( THEME_TEXT_DOMAIN . '-markup-indent', function() { return 2; }, 2 );
	the_post_navigation();
	remove_all_filters( THEME_TEXT_DOMAIN . '-markup-indent', 2 );
	// If comments are open or we have at least one comment, load up the comment template.
	if ( comments_open() || get_comments_number() ) :
		comments_template();
	endif;
endwhile; // End of the loop.
get_sidebar();
get_footer();
