<?php
/**
 * Custom filters for the Archives Item HTML Elements.
 * 
 * @package MG\Theme
 * @subpackage Archive
 * @subpackage Item
 */
namespace MG\Theme\Archive\Item;

/**
 * Core class used to implement Archive Item Filter functions.
 */
class Filters
{
	/**
	 * Indents the Archive Item HTML Markup.
	 *
	 * @see get_archives_link wordpress function for hook info 
	 * @param string $html html markup for an archive item (option or list element)
	 * @return array
	 */
	public static function markupIndent( $html, $url, $text, $format, $before, $after ) {
		$indent = \str_repeat( "\t", \apply_filters( THEME_TEXT_DOMAIN . '-markup-indent', 0 ) );
		$orig_indent = \str_repeat( "\t", \strspn( \rtrim($html), "\t" ) );

		$html = \preg_replace( 
			'/'.\preg_quote($orig_indent, '/').'/', 
			$indent, 
			$html,
			1
		);
		return $html;
	}
}

