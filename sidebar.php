<?php
/**
 * The sidebar containing the main widget area.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package MG\Theme
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}
?>

		<aside id="secondary" class="widget-area" role="complementary">
			<h1 class="screen-reader-text"><?php esc_html_e( 'Links', THEME_TEXT_DOMAIN );?></h1>
<?php 
	add_filter( THEME_TEXT_DOMAIN . '-markup-indent', function() { return 3; }, 3 );
	dynamic_sidebar( 'sidebar-1' );
	remove_all_filters( THEME_TEXT_DOMAIN . '-markup-indent', 3 );
?>
		</aside>
<?php 
