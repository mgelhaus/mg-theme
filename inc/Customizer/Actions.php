<?php
/**
 * Custom actions for the Wordpress Theme Customizer functionality.
 *
 * @package MG\Theme
 * @subpackage Customizer
 * 
 * @link https://developer.wordpress.org/themes/advanced-topics/customizer-api/
 */
namespace MG\Theme\Customizer;

/**
 * Theme class used to implement Category action functions.
 */
class Actions
{
	/**
	 * Register postMessage support for site title and description for the Theme Customizer.
	 *
	 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
	 */
	public static function registerPostMessage( $wp_customize ) {
		$wp_customize->get_setting( 'blogname' )->transport			= 'postMessage';
		$wp_customize->get_setting( 'blogdescription' )->transport	= 'postMessage';
		$wp_customize->get_setting( 'header_textcolor' )->transport	= 'postMessage';
	}

	/**
	 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
	 *
	 * @uses wp_enqueue_script 
	 * @link https://developer.wordpress.org/reference/functions/wp_enqueue_script/
	 */
	public static function addPreviewJS() {
		\wp_enqueue_script( 
			THEME_TEXT_DOMAIN . '-customizer-js', \get_template_directory_uri() . '/js/customizer.js', 
			array( 'customize-preview' ),
			'20151215',
			true
		);
	}
}
