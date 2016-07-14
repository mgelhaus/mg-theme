<?php
/**
 * Template part for displaying results in search pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package MG\Theme
 */
?>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<header class="entry-header">
				<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); echo PHP_EOL; ?>
<?php 
if ( 'post' === get_post_type() ) 
	get_template_part( 'template-parts/entry', 'meta' );
?>
			</header>
			<div class="entry-summary">
<?php 
add_filter( THEME_TEXT_DOMAIN . '-markup-indent', function() { return 4; }, 4 );
the_excerpt();
remove_all_filters( THEME_TEXT_DOMAIN . '-markup-indent', 4 );
?>
			</div>
			<footer class="entry-footer">
<?php 
// Render::footer(); 
?>
			</footer>
		</article>
<?php 
