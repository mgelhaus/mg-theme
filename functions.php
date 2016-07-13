<?php
/**
 * MG\Theme functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package MG\Theme
 */
namespace MG\Theme;

if ( ! \defined('THEME_TEXT_DOMAIN') ) define( 'THEME_TEXT_DOMAIN', strtolower( str_replace( '\\', '-', __NAMESPACE__ ) ) );

/**
 * PSR-4 Autoloader for all them functionality.
 *
 * After registering this autoload function with SPL, the following line
 * would cause the function to attempt to load the \MG\THeme\Baz\Qux class
 * from {theme-dir}/inc/Baz/Qux.php:
 *
 *	  new MG\Theme\Baz\Qux;
 *
 * @param string $class The fully-qualified class name.
 * @return void
 */
\spl_autoload_register(function ($class) {

	// project-specific namespace prefix
	$prefix = __NAMESPACE__;

	// base directory for the namespace prefix
	$base_dir = \get_template_directory() . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR;

	// does the class use the namespace prefix?
	$len = \strlen( $prefix );
	if ( \strncmp( $prefix, $class, $len ) !== 0 ) {
		// no, move to the next registered autoloader
		return;
	}

	// get the relative class name
	$relative_class = \substr( $class, $len );

	// replace the namespace prefix with the base directory, replace namespace
	// separators with directory separators in the relative class name, append
	// with .php
	$file = $base_dir . \str_replace( '\\', DIRECTORY_SEPARATOR, $relative_class ) . '.php';

	// if the file exists, require it
	if ( \file_exists($file) ) {
		require $file;
	}
});

namespace\Core::initialize();
