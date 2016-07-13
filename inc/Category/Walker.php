<?php 
/**
 * Custom Walker Class for Category HTML Objects.
 *
 * @package MG\Theme
 * @subpackage Category
 *
 * @link https://developer.wordpress.org/reference/functions/wp_nav_menu/
 */
namespace MG\Theme\Category;
use \Walker_Category;

/**
 * Core class used to implement Category HTML markup Walker.
 *
 * @see \Walker_Category
 */
class Walker extends Walker_Category
{
	/**
	 * Starts the list before the elements are added.
	 *
	 * @since 2.1.0
	 * @access public
	 *
	 * @see Walker::start_lvl()
	 *
	 * @param string $output Used to append additional content. Passed by reference.
	 * @param int    $depth  Optional. Depth of category. Used for tab indentation. Default 0.
	 * @param array  $args   Optional. An array of arguments. Will only append content if style argument
	 *                       value is 'list'. See wp_list_categories(). Default empty array.
	 */
	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		if ( 'list' != $args['style'] )
			return;
		$padding = \apply_filters( THEME_TEXT_DOMAIN . '-markup-indent', 0 );
		$indent = str_repeat("\t", $padding+($depth*2) );
		$depth_class = (bool) $depth ? ' depth-' . $depth : '';
		$output .= PHP_EOL . $indent . '<ul class="children' . $depth_class . '">' . PHP_EOL;
	}

	/**
	 * Ends the list of after the elements are added.
	 *
	 * @since 2.1.0
	 * @access public
	 *
	 * @see Walker::end_lvl()
	 *
	 * @param string $output Used to append additional content. Passed by reference.
	 * @param int    $depth  Optional. Depth of category. Used for tab indentation. Default 0.
	 * @param array  $args   Optional. An array of arguments. Will only append content if style argument
	 *                       value is 'list'. See wp_list_categories(). Default empty array.
	 */
	public function end_lvl( &$output, $depth = 0, $args = array() ) {
		if ( 'list' != $args['style'] )
			return;
		$padding = \apply_filters( THEME_TEXT_DOMAIN . '-markup-indent', 0 );
		$indent = \str_repeat("\t", $padding+($depth*2) );
		$output = $indent . '</ul>' . PHP_EOL;
		\remove_all_filters( $filter_domain, $depth+2 );
	}

	/**
	 * Starts the element output.
	 *
	 * @since 2.1.0
	 * @access public
	 *
	 * @see Walker::start_el()
	 *
	 * @param string $output   Passed by reference. Used to append additional content.
	 * @param object $category Category data object.
	 * @param int    $depth    Optional. Depth of category in reference to parents. Default 0.
	 * @param array  $args     Optional. An array of arguments. See wp_list_categories(). Default empty array.
	 * @param int    $id       Optional. ID of the current category. Default 0.
	 */
	public function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 ) {
		/** This filter is documented in wp-includes/category-template.php */
		$cat_name = apply_filters(
			'list_cats',
			esc_attr( $category->name ),
			$category
		);

		// Don't generate an element if the category name is empty.
		if ( ! $cat_name ) {
			return;
		}

		$padding = \apply_filters( THEME_TEXT_DOMAIN . '-markup-indent', 0 );
		$indent_c = $padding+($depth*2)+1;
		$has_feed = !empty( $args['feed_image'] ) || !empty( $args['feed'] );
		$link = '';

		if ( $has_feed ) {
			$indent_c++;
			$link .= PHP_EOL . str_repeat( "\t", $indent_c );
		}

		$link = '<a href="' . esc_url( get_term_link( $category ) ) . '" ';
		if ( $args['use_desc_for_title'] && ! empty( $category->description ) ) {
			/**
			 * Filter the category description for display.
			 *
			 * @since 1.2.0
			 *
			 * @param string $description Category description.
			 * @param object $category    Category object.
			 */
			$link .= 'title="' . esc_attr( strip_tags( apply_filters( 'category_description', $category->description, $category ) ) ) . '"';
		}
		$link .= '>' . $cat_name . '</a>';

		if ( $has_feed ) {
			$link .= PHP_EOL . str_repeat( "\t", $indent_c );

			if ( empty( $args['feed_image'] ) ) {
				$link .= '(';
			}

			$link .= '<a href="' . esc_url( get_term_feed_link( $category->term_id, $category->taxonomy, $args['feed_type'] ) ) . '"';

			$name = '';
			if ( empty( $args['feed'] ) ) {
				$alt = ' alt="' . sprintf(__( 'Feed for all posts filed under %s' ), $cat_name ) . '"';
			} else {
				$alt = ' alt="' . $args['feed'] . '"';
				$name = $args['feed'];
				$link .= empty( $args['title'] ) ? '' : $args['title'];
			}
			$link .= '>';

			if ( empty( $args['feed_image'] ) ) {
				$link .= $name;
			} else {
				$link .= '<img src="' . $args['feed_image'] . $alt . ' />';
			}
			$link .= '</a>';

			if ( empty( $args['feed_image'] ) ) {
				$link .= ')';
			}
			$indent_c--;
		}

		if ( ! empty( $args['show_count'] ) ) {
			$link .= ' (' . number_format_i18n( $category->count ) . ')';
		}
		if ( 'list' == $args['style'] ) {
			$output .= str_repeat( "\t", $indent_c ) . '<li';
			$css_classes = array(
				'cat-item',
				'cat-item-' . $category->term_id,
			);

			if ( ! empty( $args['current_category'] ) ) {
				// 'current_category' can be an array, so we use `get_terms()`.
				$_current_terms = get_terms( $category->taxonomy, array(
					'include' => $args['current_category'],
					'hide_empty' => false,
				) );

				foreach ( $_current_terms as $_current_term ) {
					if ( $category->term_id == $_current_term->term_id ) {
						$css_classes[] = 'current-cat';
					} elseif ( $category->term_id == $_current_term->parent ) {
						$css_classes[] = 'current-cat-parent';
					}
					while ( $_current_term->parent ) {
						if ( $category->term_id == $_current_term->parent ) {
							$css_classes[] =  'current-cat-ancestor';
							break;
						}
						$_current_term = get_term( $_current_term->parent, $category->taxonomy );
					}
				}
			}

			/**
			 * Filter the list of CSS classes to include with each category in the list.
			 *
			 * @since 4.2.0
			 *
			 * @see wp_list_categories()
			 *
			 * @param array  $css_classes An array of CSS classes to be applied to each list item.
			 * @param object $category    Category data object.
			 * @param int    $depth       Depth of page, used for padding.
			 * @param array  $args        An array of wp_list_categories() arguments.
			 */
			$css_classes = implode( ' ', apply_filters( 'category_css_class', $css_classes, $category, $depth, $args ) );

			$output .=  ' class="' . $css_classes . '">' . $link;
			if ( $has_feed ) $output .= PHP_EOL;
		} elseif ( isset( $args['separator'] ) ) {
			$output .= str_repeat( "\t", $indent_c ) . $link . $args['separator'] . PHP_EOL;
		} else {
			$output .= str_repeat( "\t", $indent_c ) . $link . '<br />' . PHP_EOL;
		}
	}

	/**
	 * Ends the element output, if needed.
	 *
	 * @since 2.1.0
	 * @access public
	 *
	 * @see Walker::end_el()
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $page   Not used.
	 * @param int    $depth  Optional. Depth of category. Not used.
	 * @param array  $args   Optional. An array of arguments. Only uses 'list' for whether should append
	 *                       to output. See wp_list_categories(). Default empty array.
	 */
	public function end_el( &$output, $page, $depth = 0, $args = array() ) {
		$padding = \apply_filters( THEME_TEXT_DOMAIN . '-markup-indent', 0 );
		if ( 'list' != $args['style'] )	return;
		if ("\n" === $output[(strlen($output)-1)]) $output .= str_repeat("\t", $padding+($depth*2)+1 );
		$output .= '</li>' . PHP_EOL;
	}

}