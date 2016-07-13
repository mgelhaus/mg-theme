<?php
/**
 * Custom filters for the Entry Navigation HTML Elements.
 * 
 * @package MG\Theme
 * @subpackage Markup
 * @subpackage Navigation
 *
 * @link https://developer.wordpress.org/reference/functions/body_class/
 */
namespace MG\Theme\Entry\Navigation;

class Filters
{
	public static function fixMarkup( $template, $class ) {
		$indent = \str_repeat( "\t", \apply_filters( THEME_TEXT_DOMAIN . '-markup-indent', 0 ) );
		$template = str_replace( '%3$s', PHP_EOL . "\t\t\t" . '%3$s' . PHP_EOL . "\t\t", $template );
		// Convert to string to array of lines
		$template_lines = \explode( PHP_EOL, $template );
		$orig_indent = null;
		foreach ($template_lines as $row => $line ) {
			// Remove Empty Rows
			if ( '' === trim($line) ) {
				unset( $template_lines[$row] );
				continue;
			}
			// Build Original Indent
			if ( null === $orig_indent ) {
				// Create original indent
				$orig_indent = \str_repeat( "\t", \strspn( \rtrim($line), "\t" ) );				
			}
			// Swap Indention
			$template_lines[$row] = \preg_replace( '/'.\preg_quote($orig_indent, '/').'/', $indent, $line, 1 );
		}
		// Repack rows into string
		$template = PHP_EOL . \implode( PHP_EOL, $template_lines );

		return $template;
	}
}
