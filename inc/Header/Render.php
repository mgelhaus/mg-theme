<?php
/**
 * MG\Theme\Header\Render
 *
 * @package MG\Theme
 * @subpackage Header
 *
 * @link https://developer.wordpress.org/themes/functionality/custom-headers/
 */
namespace MG\Theme\Header;

/**
 * Theme class used to implement Custom Header action functions.
 */
class Render
{
	/**
	 * A flag to prevent repeat style html objects from being added
	 */
	private static $custom_header_style_added = false;

	/**
	 * Styles the header image and text displayed on the blog.
	 *
	 * @see MG\Theme\Core::addThemeSupport().
	 */
	public static function style( $args = array() ) {
		/*
		 * If no custom options for text are set, let's bail.
		 * get_header_textcolor() options: Any hex value, 'blank' to hide text. Default: HEADER_TEXTCOLOR.
		 */
		$header_text_color = \get_header_textcolor();
		if ( HEADER_TEXTCOLOR === $header_text_color ) return;

		$defaults = array(
			'echo'		=> 1
		);
		$r = \wp_parse_args( $args, $defaults );

		if ( $r['echo'] ) {
			/*
			 * If already added, let's bail.
			 */
			if ( static::$custom_header_style_added ) return;
			// change flag to not add a second header
			static::$custom_header_style_added = true;
		}

		$output = '<style type="text/css">';
		// Has the text been hidden?
		if ( ! display_header_text() ) {
			$output .= '.site-title, .site-description {position: absolute;	clip: rect(1px, 1px, 1px, 1px);}';
		}
		// If the user has set a custom color for the text use that.
		else {
			$output .= '.site-title a, .site-description {color: #' . \esc_attr( $header_text_color ) . ';}';
		}
		$output .= '</style>';
		if ( !$r['echo'] ) return $output;
		echo $output;
	}
}

