<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package MG\Theme
 */
use MG\Theme\Functions;

get_header(); ?>

		<section class="error-404 not-found">
			<header class="page-header">
				<h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', THEME_TEXT_DOMAIN ); ?></h1>
			</header>
			<div class="page-content">
				<p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', THEME_TEXT_DOMAIN ); ?></p>
			</div>
			<aside id="secondary" class="widget-area" role="complementary">
				<h1 class="screen-reader-text"><?php esc_html_e( 'Links', THEME_TEXT_DOMAIN );?></h1>
<?php 
	\add_filter( THEME_TEXT_DOMAIN . '-markup-indent', function() { return 4; }, 4 );
	dynamic_sidebar( 'help-links-404' );
	\remove_all_filters( THEME_TEXT_DOMAIN . '-markup-indent', 4 );
?>
			</aside>
		</section>

<?php
get_footer();
