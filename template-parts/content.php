<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package MG\Theme
 */

if ( is_single() ) :
?>
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<header class="entry-header">
<?php 
	the_title( str_repeat( "\t", 4 ) . '<h1 class="entry-title">', '</h1>' . PHP_EOL );
else :
?>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<header class="entry-header">
<?php 
	the_title( str_repeat( "\t", 4 ) . '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' . PHP_EOL );
endif;

if ( 'post' === get_post_type() ) : 
	$is_modified = \get_the_time( 'U' ) !== \get_the_modified_time( 'U' )
?>
				<div class="entry-meta">
					<span class="posted-on"><?php esc_html_e('Posted on', THEME_TEXT_DOMAIN); echo ' '; ?><time class="entry-date published<?php if ( !$is_modified ) echo ' updated'; ?>" datetime="<?php esc_attr_e( get_the_time( 'c' ) ); ?>"><?php esc_html_e( get_the_time('F j, Y') ); ?></time><?php if ( $is_modified ) : ?><time class="updated" datetime="<?php esc_attr_e( get_the_modified_time( 'c' ) ); ?>"><?php esc_html_e( get_the_modified_time('F j, Y') ); ?></time><?php endif;  echo PHP_EOL; ?>
					<span class="byline"><?php esc_html_e('by'); echo ' '; ?><a class="url fn n" href="<?php esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" title="<?php esc_attr_e( 'Author :', THEME_TEXT_DOMAIN ); esc_attr_e( get_the_author() ); ?>"><span class="author vcard"><?php esc_html_e( get_the_author() ); ?></span></a></span>
				</div>
<?php 
endif; 
?>
			</header>
			<div class="entry-content">

<?php
add_filter( THEME_TEXT_DOMAIN . '-markup-indent', function() { return 4; }, 4 );
the_content( sprintf(
	/* translators: %s: Name of current post. */
	wp_kses( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', THEME_TEXT_DOMAIN ), array( 'span' => array( 'class' => array() ) ) ),
	the_title( '<span class="screen-reader-text">"', '"</span>', false )
) );
wp_link_pages( array(
	'before' => '<div class="page-links">' . esc_html__( 'Pages:', THEME_TEXT_DOMAIN ),
	'after'  => '</div>',
) );
remove_all_filters( THEME_TEXT_DOMAIN . '-markup-indent', 4 );
?>

			</div>
			<footer class="entry-footer">
<?php 
// MG\Theme\Entry\Render::footer(); 
?>
			</footer>
<?php 
if ( is_single() ) :
?>
		</div>

<?php 
else :
?>
		</article>
<?php
endif;
