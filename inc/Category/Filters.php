<?php
/**
 * Custom filters for Category HTML Elements.
 *
 * @package MG\Theme
 * @subpackage Markup
 * @subpackage Category
 *
 * @link https://developer.wordpress.org/reference/functions/wp_nav_menu/
 */
namespace MG\Theme\Markup\Category;
use MG\Theme\Markup\Category\Walker;

/**
 * Core class used to implement Category Filter functions.
 */
class Filters
{
	/**
	 * limits all core html class attributes for outer Category DOM element to those listed in the array below.
	 *
	 * @see category_css_class wordpress function for hook info 
	 * @param array $class_attrs Classes for the outer Category DOM element.
	 * @return array
	 */
	public static function limitClassAttributes( $class_attrs = array(), $category, $depth, $args ) {
		return is_array( $class_attrs ) ? array_intersect( $class_attrs, 
				array(
					//List of allowed menu classes
					'current_page_item',
					'current_page_parent',
					'current_page_ancestor',
					'first',
					'last',
					'vertical',
					'horizontal'
				)
			) : array();
	}

	/**
	 * Replaces core 'current-**' class naming with 'active'.
	 *
	 * @see wp_list_categories wordpress function for hook info 
	 * @param string $html HTML markup for the Category Object
	 * @return string
	 */
	public static function changeCurrentToActive( $html, $args ) {
		//List of menu item classes that should be changed to "active"
		$replace = array(
			'current_page_item' => 'active',
			'current_page_parent' => 'active',
			'current_page_ancestor' => 'active'
		);
		return \str_replace( \array_keys( $replace ), $replace, $html );
	}

	/**
	 * Removes empty html class attributes.
	 *
	 * @see wp_list_categories wordpress function for hook info 
	 * @param string $html HTML markup for the Category Object
	 * @return string
	 */
	public static function removeEmptyClassAttributes( $html, $args ) {
		return \preg_replace( '/ class=""/', '', $html );
	}

	/**
	 * Adds markup indentation
	 *
	 * @see wp_list_categories wordpress function for hook info 
	 * @param string $html HTML markup for the Category Object
	 * @return string
	 */
	public static function markupIndent( $html, $args ) {
		$indent = \str_repeat( "\t", \apply_filters( THEME_TEXT_DOMAIN . '-markup-indent', 0 ) );
		return $indent . \preg_replace( '/\n/',  "\n" . $indent, $html );
	}

	/**
	 * Remove erroneous newlines before all closing list item tags
	 *
	 * @see wp_list_categories wordpress function for hook info 
	 * @param string $html HTML markup for the Category Object
	 * @return string
	 */
	public static function removeErroneousMarkupNewlines( $html, $args ) {
		return \preg_replace( '/\s+<\/li>/', '</li>', $html );
	}

	/**
	 * Overrides Arguments for the Category to use a custom Walker, remove the container
	 *
	 * @see get_archives_link wordpress function for hook info 
	 * @param array $args An array of setting for get_terms_args
	 * @return array
	 */
	public static function addThemeWalker( $args ) {
		if ( isset( $args['taxonomy'] ) && 'category' == $args['taxonomy'] ) {
			$defaults = array( 
				'walker' => new Walker, 
				'container' => false
			);
			return array_merge( $args, $defaults );
		}
		return $args;
	}
}
