<?php
/**
 * Custom filters for the Entry Content HTML Elements.
 * 
 * @package MG\Theme
 * @subpackage Entry
 * @subpackage Content
 *
 * @link https://developer.wordpress.org/reference/functions/body_class/
 */
namespace MG\Theme\Entry\Content;

class Filters
{
	public static function fixMarkup( $html ) {
		$indent = \str_repeat( "\t", \apply_filters( THEME_TEXT_DOMAIN . '-markup-indent', 0 ) );
		// Convert to string to array of lines
		$html_lines = \explode( PHP_EOL, $html );
		$orig_indent = null;
		foreach ($html_lines as $row => $line ) {
			// Remove Empty Rows
			if ( '' === trim($line) ) {
				unset( $html_lines[$row] );
				continue;
			}
			// Build Original Indent
			if ( null === $orig_indent ) {
				// Create original indent
				$orig_indent = \str_repeat( "\t", \strspn( \rtrim($line), "\t" ) );				
			}
			// Swap Indention
			$html_lines[$row] = \preg_replace( '/'.\preg_quote($orig_indent, '/').'/', $indent, $line, 1 );
		}
		// Repack rows into string
		$html = \implode( PHP_EOL, $html_lines ) . PHP_EOL;
		return $html;
	}
}
