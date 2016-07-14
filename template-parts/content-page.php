<?php
/**
 * Template part for displaying page content in page.php.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package MG\Theme
 */
?>
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<header class="entry-header">
				<?php the_title( '<h1 class="entry-title">', "</h1>\n" );?>
			</header>
			<div class="entry-content">
<?php
	the_content();

	wp_link_pages( array(
		'before' => '<div class="page-links">' . esc_html__( 'Pages:', THEME_TEXT_DOMAIN ),
		'after'  => '</div>',
	) );
?>
			</div>
<?php 
	if ( get_edit_post_link() ) : 
?>
			<footer class="entry-footer">
<?php
	edit_post_link(
		sprintf(
			/* translators: %s: Name of current post */
			esc_html__( 'Edit %s', THEME_TEXT_DOMAIN ),
			the_title( '<span class="screen-reader-text">"', '"</span>', false )
		),
		'<span class="edit-link">',
		'</span>'
	);
?>
			</footer>
<?php 
	endif;
?>
		</div>
