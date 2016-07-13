<?php
/**
 * Custom filters for the Body HTML Elements.
 * 
 * @package MG\Theme
 * @subpackage Document
 * @subpackage Body
 *
 * @link https://developer.wordpress.org/reference/functions/body_class/
 */
namespace MG\Theme\Document\Body;

/**
 * Core class used to implement Body Filter functions.
 */
class Filters
{
	/**
	 * Adds custom html class attribute names to the html body element.
	 *
	 * @param array $class_attrs class attibute names for the body element.
	 * @return array
	 */
	public static function addClassAttributes( $class_attrs ) {
		// Adds a class of group-blog to blogs with more than 1 published author.
		if ( is_multi_author() ) {
			$class_attrs[] = 'multi-author';
		}

		// Adds a class of hfeed to non-singular pages.
		if ( ! is_singular() ) {
			$class_attrs[] = 'list';
		}

		return $class_attrs;
	}
}

