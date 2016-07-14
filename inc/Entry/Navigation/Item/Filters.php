<?php
/**
 * Custom filters for the Entry Navigation HTML Elements.
 * 
 * @package MG\Theme
 * @subpackage Navigation
 * @subpackage Item
 */
namespace MG\Theme\Entry\Navigation\Item;

class Filters
{
	/**
	 * Indents the Entry Navigation Item HTML Markup.
	 *
	 * @see wp_link_pages_link wordpress function for hook info 
	 * @param string $html html markup for an archive item (option or list element)
	 * @param int    $num  Page number for paginated posts' page links.
	 * @return array
	 */
	public static function fixMarkup( $html, $format, $link, $post, $adjacent ) {
		$indent = str_repeat( "\t", ( \apply_filters( THEME_TEXT_DOMAIN . '-markup-indent', 0 ) +2 ) );
		$html = PHP_EOL . $indent . trim($html);
		return $html;
	}
}
