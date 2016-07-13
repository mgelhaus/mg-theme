<?php 
/**
 * Widget API: MG\Theme\Archive\Widget class
 *
 * @package MG\Theme
 * @subpackage Archive
 */
namespace MG\Theme\Archive;
use \WP_Widget;

/**
 * Theme class used to implement a Archives widget.
 *
 * @see WP_Widget
 */
class Widget extends WP_Widget
{
	/**
	 * Sets up a new Archives widget instance.
	 *
	 * @access public
	 */
	public function __construct() {
		$widget_ops = array(
			'classname' => strtolower( str_replace( '\\', '-', __CLASS__ ) ),
			'description' => \__( 'A monthly archive of your site&#8217;s Posts.', THEME_TEXT_DOMAIN ),
			'customize_selective_refresh' => true,
		);
		parent::__construct( strtolower( str_replace( '\\', '-', __NAMESPACE__ ) ), \__( __CLASS__ ), $widget_ops);
	}

	/**
	 * Outputs the content for the current Archives widget instance.
	 *
	 * @access public
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current Archives widget instance.
	 */
	public function widget( $args, $instance ) {
		$c = !empty( $instance['count'] ) ? '1' : '0';
		$d = !empty( $instance['dropdown'] ) ? '1' : '0';
		$indent_c = \apply_filters( THEME_TEXT_DOMAIN . '-markup-indent', 0 );
		$output = '';

		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title = \apply_filters( 'widget_title', 
			empty( $instance['title'] ) ? 
				\__( 'Archives', THEME_TEXT_DOMAIN ) : 
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
			$output .= \str_repeat( "\t", $indent_c ) . '<select id="' . esc_attr( $dropdown_id ) . '" name="archive-dropdown" onchange="document.location.href=this.options[this.selectedIndex].value;">' . PHP_EOL;
			/**
			 * Filter the arguments for the Archives widget drop-down.
			 *
			 * @since 2.8.0
			 *
			 * @see wp_get_archives()
			 *
			 * @param array $args An array of Archives widget drop-down arguments.
			 */
			$dropdown_args = apply_filters( 'widget_archives_dropdown_args', array(
				'type'				=> 'monthly',
				'format'			=> 'option',
				'show_post_count' 	=> $c,
				'echo'				=> false
			) );

			switch ( $dropdown_args['type'] ) {
				case 'yearly':
					$label = __( 'Select Year' );
					break;
				case 'monthly':
					$label = __( 'Select Month' );
					break;
				case 'daily':
					$label = __( 'Select Day' );
					break;
				case 'weekly':
					$label = __( 'Select Week' );
					break;
				default:
					$label = __( 'Select Post' );
					break;
			}
			$output .= \str_repeat( "\t", $indent_c+1 ) . '<option value="">' . esc_attr( $label ) . '</option>' . PHP_EOL;
			$output .= wp_get_archives( $dropdown_args );
			$output .= \str_repeat( "\t", $indent_c ) . '</select>' . PHP_EOL;
		} 
		else {
			$output .= \str_repeat( "\t", $indent_c ) . '<ul>' . PHP_EOL;
			/**
			 * Filter the arguments for the Archives widget.
			 *
			 * @since 2.8.0
			 *
			 * @see wp_get_archives()
			 *
			 * @param array $args An array of Archives option arguments.
			 */
			$output .= wp_get_archives( apply_filters( 'widget_archives_args', array(
				'type'				=> 'monthly',
				'show_post_count'	=> $c,
				'echo'				=> false
			) ) );
			$output .= \str_repeat( "\t", $indent_c) . '</ul>' . PHP_EOL;
		}
		\remove_all_filters( THEME_TEXT_DOMAIN . '-markup-indent', $indent_c+1 );

		if ( !empty( $args['after_widget'] ) ) {
			$indent_c--;
			$output .= \str_repeat( "\t", $indent_c) . \rtrim( $args['after_widget'] ) . PHP_EOL;
		}
		echo $output;
	}

	/**
	 * Handles updating settings for the current Archives widget instance.
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget_Archives::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Updated settings to save.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$new_instance = wp_parse_args( (array) $new_instance, array( 'title' => '', 'count' => 0, 'dropdown' => '') );
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		$instance['count'] = $new_instance['count'] ? 1 : 0;
		$instance['dropdown'] = $new_instance['dropdown'] ? 1 : 0;

		return $instance;
	}

	/**
	 * Outputs the settings form for the Archives widget.
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'count' => 0, 'dropdown' => '') );
		$title = sanitize_text_field( $instance['title'] );
		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
		<p>
			<input class="checkbox" type="checkbox"<?php checked( $instance['dropdown'] ); ?> id="<?php echo $this->get_field_id('dropdown'); ?>" name="<?php echo $this->get_field_name('dropdown'); ?>" /> <label for="<?php echo $this->get_field_id('dropdown'); ?>"><?php _e('Display as dropdown'); ?></label>
			<br/>
			<input class="checkbox" type="checkbox"<?php checked( $instance['count'] ); ?> id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" /> <label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Show post counts'); ?></label>
		</p>
		<?php
	}
}
