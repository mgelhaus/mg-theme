<?php 
/**
 * Widget API: MG\Theme\Category\Categories class
 *
 * @package MG\Theme
 * @subpackage Category
 */
namespace MG\Theme\Category;
use \WP_Widget;
// use MG\Theme\Category\Item\Render;

/**
 * Theme class used to implement a Categories widget.
 *
 * @see WP_Widget
 */
class Widget extends WP_Widget
{
	/**
	 * Sets up a new Categories widget instance.
	 *
	 * @access public
	 */
	public function __construct() {
		$widget_ops = array(
			'classname' => strtolower( str_replace( '\\', '-', __CLASS__ ) ),
			'description' => \__( 'A monthly category of your site&#8217;s Posts.', THEME_TEXT_DOMAIN ),
			'customize_selective_refresh' => true,
		);
		parent::__construct( strtolower( str_replace( '\\', '-', __CLASS__ ) ), \__( __CLASS__ ), $widget_ops);
	}

	/**
	 * Outputs the content for the current Categories widget instance.
	 *
	 * @access public
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current Categories widget instance.
	 */
	public function widget( $args, $instance ) {
		$c = !empty( $instance['count'] ) ? '1' : '0';
		$n = !empty( $instance['number'] ) && (bool)$instance['number'] ? $instance['number'] : 10;
		$d = !empty( $instance['dropdown'] ) ? '1' : '0';
		$h = !empty( $instance['hierarchical'] ) ? '1' : '0';
		$indent_c = \apply_filters( THEME_TEXT_DOMAIN . '-markup-indent', 0 );
		$output = '';

		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title = \apply_filters( 'widget_title', 
			empty( $instance['title'] ) ? 
				\__( 'Categories', THEME_TEXT_DOMAIN ) : 
				$instance['title'], 
			$instance, 
			$this->id_base 
		);

		if ( !empty( $args['before_widget'] ) ) {
			$output .= \str_repeat( "\t", $indent_c ) . rtrim( $args['before_widget'] ) . PHP_EOL;
			// echo \str_repeat( "\t", $indent_c ) . $args['before_widget'];
			$indent_c++;
		}

		if ( $title ) {
			$output .= \str_repeat( "\t", $indent_c ) . $args['before_title'] . $title . rtrim( $args['after_title'] ) . PHP_EOL;
		}
		\add_filter( THEME_TEXT_DOMAIN . '-markup-indent', function() use($indent_c) { return $indent_c+1; }, $indent_c+1 );
		if ( $d ) {
			$dropdown_id = "{$this->id_base}-dropdown-{$this->number}";
			$output .= \str_repeat( "\t", $indent_c ) . '<label class="screen-reader-text" for="' . esc_attr( $dropdown_id ) . '">' . $title . '</label>' . PHP_EOL;
			$output .= \str_repeat( "\t", $indent_c ) . '<select id="' . esc_attr( $dropdown_id ) . '" name="category-dropdown" onchange="document.location.href=this.options[this.selectedIndex].value;">' . PHP_EOL;
			/**
			 * Filter the arguments for the Categories widget drop-down.
			 *
			 * @see wp_dropdown_categories()
			 *
			 * @param array $args An array of Categories widget drop-down arguments.
			 */
			$output .= wp_dropdown_categories( apply_filters( 'widget_categories_dropdown_args', array(
				'id'				=> $dropdown_id,
				'show_option_none'	=> __( 'Select Category' ),
				'orderby'			=> 'count',
				'order'				=> 'DESC',
				'hierarchical'		=> $h,
				'show_count'		=> $c,
				'number'			=> $n,
				'hide_if_empty'     => true,
				'echo'				=> false
			) ) );

			$output .= \str_repeat( "\t", $indent_c ) . '</select>' . PHP_EOL;
		} 
		else {
			$output .= \str_repeat( "\t", $indent_c ) . '<ul>' . PHP_EOL;
			/**
			 * Filter the arguments for the Categories widget.
			 *
			 * @since 2.8.0
			 *
			 * @see wp_list_categories()
			 *
			 * @param array $args An array of Category option arguments.
			 */
			$list_cat_args = apply_filters( 'widget_categories_args', array(
				'orderby'			=> 'count',
				'order'				=> 'DESC',
				'title_li'			=> '',
				'depth'				=> 0,
				'hierarchical'		=> $h,
				'show_count'		=> $c,
				'number'			=> $n,
				'echo'				=> false
			) );
			$output .= \rtrim( wp_list_categories( $list_cat_args ) ) . PHP_EOL;
			$output .= \str_repeat( "\t", $indent_c) . '</ul>' . PHP_EOL;
		}
		\remove_filter( THEME_TEXT_DOMAIN . '-markup-indent', $indent_c+1 );

		if ( !empty( $args['after_widget'] ) ) {
			$indent_c--;
			$output .= \str_repeat( "\t", $indent_c) . rtrim( $args['after_widget'] ) . PHP_EOL;
		}
		echo $output;
	}

	/**
	 * Handles updating settings for the current Categories widget instance.
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            MG\Theme\Categories\Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Updated settings to save.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$new_instance = wp_parse_args( 
			(array) $new_instance, 
			array( 
				'title'			=> '', 
				'count'			=> 0, 
				'number'		=> 10, 
				'dropdown'		=> 0, 
				'hierarchical'	=> 0
			)
		);
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		$instance['count'] = $new_instance['count'] ? 1 : 0;
		$instance['number'] = (int)$new_instance['number'];
		$instance['dropdown'] = $new_instance['dropdown'] ? 1 : 0;
		$instance['hierarchical'] = $new_instance['hierarchical'] ? 1 : 0;

		return $instance;
	}

	/**
	 * Outputs the settings form for the Categories widget.
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {
		$instance = wp_parse_args( 
			(array) $instance, 
			array( 
				'title'			=> '', 
				'number'		=> 10, 
				'count'			=> 0, 
				'dropdown'		=> 0,
				'hierarchical'	=> 0
			) 
		);
		$title = sanitize_text_field( $instance['title'] );
		$number	= isset( $instance['number'] ) ? absint( $instance['number'] ) : 10;
?>
	<p>
		<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of categories to show:' ); ?></label>
		<input class="tiny-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" />
	</p>
	<p>
		<input class="checkbox" type="checkbox"<?php checked( $instance['dropdown'] ); ?> id="<?php echo $this->get_field_id('dropdown'); ?>" name="<?php echo $this->get_field_name('dropdown'); ?>" /> 
		<label for="<?php echo $this->get_field_id('dropdown'); ?>"><?php _e('Display as dropdown'); ?></label>
		<br />
		<input class="checkbox" type="checkbox"<?php checked( $instance['hierarchical'] ); ?> id="<?php echo $this->get_field_id('hierarchical'); ?>" name="<?php echo $this->get_field_name('hierarchical'); ?>" /> 
		<label for="<?php echo $this->get_field_id('hierarchical'); ?>"><?php _e('Display as hierarchical'); ?></label>
		<br />
		<input class="checkbox" type="checkbox"<?php checked( $instance['count'] ); ?> id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" />
		<label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Show post counts'); ?></label>
	</p>
		<?php
	}
}
