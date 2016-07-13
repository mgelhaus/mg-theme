<?php
/**
 * Custom actions to implement Wordpress JetPack Plugin functionality.
 *
 * @package MG\Theme
 * @subpackage Plugin
 * @subpackage JetPack
 *
 * @link https://wordpress.org/plugins/jetpack/developers/
 */
namespace MG\Theme\Plugin\JetPack;

class Actions
{
	/**
	 * Jetpack setup function.
	 *
	 * @link https://jetpack.com/support/infinite-scroll/
	 * @link https://jetpack.com/support/responsive-videos/
	 *
	 * @uses add_theme_support
	 */
	public static function addThemeSupport() {
		// Add theme support for Infinite Scroll.
		\add_theme_support( 'infinite-scroll', array(
			'container' => 'main',
			'render'	=> __CLASS__ .	'::renderInfiniteScroll',
			'footer'	=> 'page',
		) );

		// Add theme support for Responsive Videos.
		\add_theme_support( 'jetpack-responsive-videos' );
	}

	/**
	 * The infinite scroll renderer.
	 *
	 * @see __CLASS__::setup().
	 */
	public static function renderInfiniteScroll() {
		while ( \have_posts() ) {
			\the_post();
			if ( \is_search() ) \get_template_part( 'template-parts/content', 'search' );
			else \get_template_part( 'template-parts/content', \get_post_format() );
		}
	}
}

