<?php 
/**
 * Custom actions for manipulating the Wordpress Category module.
 *
 * @package MG\Theme
 * @subpackage Category
 */
namespace MG\Theme\Category;

class Functions
{
	/**
	 * Returns true if a blog has more than 1 category.
	 *
	 * @return bool
	 */
	public static function hasCategories() {
		$filter_domain = strtolower( str_replace( '\\', '-', __CLASS__ ) ) . '-has-ceategories';
		if ( false === ( $categories_count = \get_transient( $filter_domain ) ) ) {
			// Create an array of all the categories that are attached to posts.
			$categories = \get_categories( array(
				'fields'	 => 'ids',
				'hide_empty' => 1,
				// We only need to know if there is more than one category.
				'number'	 => 2,
			) );

			// Count the number of categories that are attached to the posts.
			$categories_count = \count( $categories );

			\set_transient( $filter_domain, $categories_count );
		}

		return (bool) $categories_count;
	}
}