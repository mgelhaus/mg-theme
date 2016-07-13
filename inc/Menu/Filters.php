<?php
/**
 * Custom filters for Nav Menu HTML Elements.
 *
 * @package MG\Theme
 * @subpackage Menu
 *
 * @link https://developer.wordpress.org/reference/functions/wp_nav_menu/
 */
namespace MG\Theme\Menu;
use MG\Theme\Menu\Walker;

/**
 * Core class used to implement Menu Filter functions.
 */
class Filters
{
	/**
	 * limits all core html class attributes for outer Menu DOM element to those listed in the array below.
	 *
	 * @see nav_menu_css_class for wordpress function hook 
	 *
	 * @param array $classses Classes for the outer Menu DOM element.
	 * @return array
	 */
	public static function limitClassAttributes( $classses = array(), $item, $args, $depth ) {
		return is_array( $classses ) ? array_intersect( $classses, 
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
	 * @see nav_menu_css_class for wordpress function hook 
	 *
	 * @param string $html HTML markup for the Menu Object
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
	 * @param string $html HTML markup for the Menu Object
	 * @return string
	 */
	public static function removeEmptyClassAttributes( $html, $args ) {
		return \preg_replace( '/ class=""/', '', $html );
	}

	/**
	 * Cleansing of the Menu HTML Markup structure to tab indented. Add left indentation to fit the DOM tree tab level
	 *
	 * @param string $html HTML markup for the Menu Object
	 * @return string
	 */
	public static function restructureMarkup( $html, $args ) {
		$indent = \str_repeat( "\t", \apply_filters( THEME_TEXT_DOMAIN . '-markup-indent', 0 ) );
		$patterns = array( '/ class="menu">/', '/\n/' );
		$replaces = array( " class=\"menu\">\n", "\n$indent" );
		$output = $indent . \preg_replace( $patterns, $replaces, $html );
		return $output;
	}

	/**
	 * Overrides Arguments for the Nav Menu to use a custom Walker, remove the container, and remove the Fallbak wp_page_nav function
	 *
	 * @param array $args An array of setting for wp_nav_menu_args
	 * @return array
	 */
	public static function addThemeWalker( $args ) {
		$defaults = array( 
			'walker' => new Walker, 
			'container' => false, 
			'fallback_cb' => false
		);
		return array_merge( $args, $defaults );
	}
}
