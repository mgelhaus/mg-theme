<?php
/**
 * Custom filters for SearchForm HTML Objects.
 *
 * @package MG\Theme
 * @subpackage Markup
 * @subpackage Searchform
 *
 * @link https://developer.wordpress.org/reference/functions/get_search_form/
 */
namespace MG\Theme\Markup\Searchform;

/**
 * Core class used to implement Searchform Filter functions.
 */
class Filters
{
	/** 
	 * Fixes formating issues
	 *
	 * Authors Note: this is not necessary #OCDProblems 
	 */
	public static function restructureMarkup( $html = '' ) {
		$indent = \str_repeat( "\t", \apply_filters( THEME_TEXT_DOMAIN . '-markup-indent', 0 ) );
		$patterns = array( "/\n\t{3}/", "/\n/" );
		$replaces = array( "\n", "\n$indent" );
		$output = $indent . \preg_replace( $patterns, $replaces, $html ) . "\n";
		return $output;
	}
}
