<?php 
/**
 * MG\Theme\Document\Element\Render class
 *
 * @package MG\Theme
 * @subpackage Document
 * @subpackage Element
 */
namespace MG\Theme\Document\Element;

/**
 * Theme class used to render class attributes.
 */
class Render
{
	/**
	 * Render the class attribute for an HTML Element.
	 *
	 * @uses apply_filters
	 *
	 * @param string $method_name the
	 * @return string
	 */
	public static function classAttribute( $args = array() ) {
		$defaults = array(
			'classes'		=> array(),
			'filter_domain'	=> '',
			'echo'			=> false
		);
		$r = \wp_parse_args( $args, $defaults );
		if ( empty( $r['filter_domain'] ) || !is_string( $r['filter_domain'] ) ) {
			$bt = \debug_backtrace();
			$bt = $bt[2];
			var_dump($bt);
			$r['filter_domain'] = THEME_TEXT_DOMAIN . '-classes';
		}
		$class_names = \apply_filters( $filter_domain, $r['classes'] );
		$output = '';
		if ( !empty( $class_names ) ) {
			$class_names = is_array( $class_names ) ) ?
				implode( ' ', array_map( 'sanitize_html_class', $class_names ) ) :
				implode( ' ', array_map( 'sanitize_html_class', explode( ' ', $class_names ) ) );
			$output = sprintf( ' class="%1$s"', $class_names );
		}
		if ( !$r['echo'] ) return $output;
		echo $output;
	}
}