<?php
/**
 * The template for displaying archive pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package MG\Theme
 */

get_header(); ?>

<?php 
if ( have_posts() ) : 
?>
		<header class="page-header">
<?php
	$markupindent = \str_repeat( "\t", 3);
	the_archive_title( $markupindent . '<h1 class="page-title">', '</h1>' . PHP_EOL );
	the_archive_description( $markupindent . '<div class="taxonomy-description">', '</div>' . PHP_EOL );
?>
		</header>

<?php
	$add_newline = null;
	/* Start the Loop */
	while ( have_posts() ) : the_post();
		// add newline to separate entry markup
		if ( $add_newline ) echo PHP_EOL;
		if ( null === $add_newline ) $add_newline = true;
		/*
		 * Include the Post-Format-specific template for the content.
		 * If you want to override this in a child theme, then include a file
		 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
		 */
		get_template_part( 'template-parts/content', get_post_format() );
	endwhile;
	add_filter( THEME_TEXT_DOMAIN . '-markup-indent', function() { return 2; }, 2 );
	the_posts_navigation();
	remove_all_filters( THEME_TEXT_DOMAIN . '-markup-indent', 2 );
else :
	get_template_part( 'template-parts/content', 'none' );
endif; 

get_sidebar();
get_footer();
