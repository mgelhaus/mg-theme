<?php
/**
 * The template for displaying search results pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package MG\Theme
 */

get_header(); ?>

<?php
if ( have_posts() ) : 
?>
		<header class="page-header">
			<h1 class="page-title"><?php printf( esc_html__( 'Search Results for: %s', THEME_TEXT_DOMAIN ), '<span>' . get_search_query() . '</span>' ); ?></h1>
		</header>

<?php
	$add_newline = null;
	/* Start the Loop */
	while ( have_posts() ) : the_post();
		// add newline to separate entry markup
		if ( $add_newline ) echo PHP_EOL;
		if ( null === $add_newline ) $add_newline = true;
		/**
		 * Run the loop for the search to output the results.
		 * If you want to overload this in a child theme then include a file
		 * called content-search.php and that will be used instead.
		 */
		get_template_part( 'template-parts/content', 'search' );
	endwhile;
	add_filter( THEME_TEXT_DOMAIN . '-markup-indent', function() { return 2; }, 2 );
	the_posts_navigation();
	remove_all_filters( THEME_TEXT_DOMAIN . '-markup-indent', 2 );
else :
	get_template_part( 'template-parts/content', 'none' );
endif;

get_sidebar();
get_footer();
