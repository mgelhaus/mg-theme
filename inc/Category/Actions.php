<?php
/**
 * Custom actions for manipulating the Wordpress Category module.
 *
 * @package MG\Theme
 * @subpackage Category
 * 
 * @link https://developer.wordpress.org/themes/basics/categories-tags-custom-taxonomies/
 */
namespace MG\Theme\Category;

/**
 * class of Static Methods that are centric to manipulating the Default Wordpress Category Functionality
 */
class Actions
{
	/**
	 * Flush out the transients used in Category\Functions\hasCategories.
	 */
	public static function transientFlusher() {
		if ( \defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		// Like, beat it. Dig?
		\delete_transient( \strtolower( \str_replace( '\\', '-', __NAMESPACE__ ) ) . '-functions-has-categories' );
	}
}
