<?php 
/**
 * MG\Theme\Category\Render class
 *
 * @package MG\Theme
 * @subpackage Category
 */
namespace MG\Theme\Category\Item;

/**
 * Theme class used to render Category HTML Elements.
 */
class Render
{
	/**
	 * Renders a list element for the category
	 *
	 * @uses self::link
	 *
	 * @param boolean $echo flag to switch output types
	 * @return string | null
	 */
	public static function list( $args = array() ) {
		$defaults = array(
			'echo'		=> 1
		);
		$r = \wp_parse_args( $args, $defaults );
		$indent_c = \apply_filters( THEME_TEXT_DOMAIN . '-markup-indent', 0 );

		// @TODO: complete
		if ( !$r['echo'] ) return $output;
		echo $output;
	}
	/**
	 * Renders a link element for the category
	 *
	 * @uses self::byline
	 * @uses self::datetime
	 *
	 * @param array $args flag to switch output types
	 * @return string | null
	 */
	public static function link( $args = array() ) {
		$defaults = array(
			'echo'		=> 1
		);
		$r = \wp_parse_args( $args, $defaults );
		
		// @TODO: complete
		if ( !$r['echo'] ) return $output;
		echo $output;
	}
}
