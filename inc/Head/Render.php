<?php 
/**
 * Core HTML Rendered objects for theme.
 *
 * @package MG\Theme
 * @package Head
 */
namespace MG\Theme\Head;
use MG\Theme\Functions;

class Render
{
	/**
	 * Custom Head Function
	 */
	public static function head() {
		$indent = str_repeat( "\t", \apply_filters( THEME_TEXT_DOMAIN . '-markup-indent', 0 ) );
		// there is no way around using ob_start unless Wordpress changes its standards for hooking into the wp_head function
		\ob_start();
		\wp_head();
		$wp_head_html = \ob_get_contents();
		\ob_end_clean();
		echo $indent . \preg_replace("/\n/", "\n$indent", \rtrim( $wp_head_html ) ) . "\n";
	}
}

