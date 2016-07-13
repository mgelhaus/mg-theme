<?php
/**
 * Widget API: MG\Theme\Entry\Widget class
 *
 * @package MG\Theme
 * @subpackage Entry
 */
namespace MG\Theme\Entry;
use \WP_Widget;
use \WP_Query;

/**
 * Core class used to implement a Recent Posts widget.
 *
 * @see WP_Widget
 */
class Widget extends WP_Widget 
{
	/**
	 * Sets up a new Recent Posts widget instance.
	 *
	 * @access public
	 */
	public function __construct() {
		$widget_ops = array(
			'classname' => THEME_TEXT_DOMAIN . '-entry-widget',
			'description' => __( 'Your site&#8217;s most recent Posts.', THEME_TEXT_DOMAIN ),
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'recent-posts', __( __CLASS__ ), $widget_ops );
		$this->alt_option_name = THEME_TEXT_DOMAIN . '-entry-widget';
	}

	/**
	 * Outputs the content for the current Recent Posts widget instance.
	 *
	 * @access public
	 *
	 * @param array $args	 Display arguments including 'before_title', 'after_title',
	 *						'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current Recent Posts widget instance.
	 */
	public function widget( $args, $instance ) {
		$n = !empty( $instance['number'] ) && (bool)$instance['number'] ? \absint( $instance['number'] ) : 5;
		$show_date = isset( $instance['show_date'] ) ? $instance['show_date'] : false;
		$markup_indent_c = \apply_filters( THEME_TEXT_DOMAIN . '-markup-indent', 0 );
		$output = '';

		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title = \apply_filters( 'widget_title', 
			empty( $instance['title'] ) ? 
				\__( 'Recent Posts', THEME_TEXT_DOMAIN ) : 
				$instance['title'], 
			$instance, 
			$this->id_base 
		);

		/**
		 * Filter the arguments for the Recent Posts widget.
		 *
		 * @see WP_Query::get_posts()
		 *
		 * @param array $args An array of arguments used to retrieve the recent posts.
		 */
		$r = new WP_Query( \apply_filters( 'widget_posts_args', array(
			'posts_per_page'		=> $n,
			'no_found_rows'			=> true,
			'post_status'			=> 'publish',
			'ignore_sticky_posts'	=> true
		) ) );


		if ($r->have_posts()) {
			if ( !empty( $args['before_widget'] ) ) {
				$output .= \str_repeat( "\t", $markup_indent_c ) . rtrim( $args['before_widget'] ) . PHP_EOL;
				$markup_indent_c++;
			}

			if ( $title ) {
				$output .= \str_repeat( "\t", $markup_indent_c ) . $args['before_title'] . $title . rtrim( $args['after_title'] ) . PHP_EOL;
			}
			$output .= \str_repeat( "\t", $markup_indent_c ) . '<ul>' . PHP_EOL;
			$indent_onestep = \str_repeat( "\t", $markup_indent_c+1 );
			$indent_twostep = \str_repeat( "\t", $markup_indent_c+2 );
			while ( $r->have_posts() ) {
				$r->the_post();
			    $output .= $indent_onestep . '<li>';
			    if ( $show_date ) $output .= PHP_EOL . $indent_twostep;
			    $output .= '<a href="' . \get_the_permalink() . '">' . ( \get_the_title() ?: \get_the_ID() ) . '</a>';
				if ( $show_date ) $output .= PHP_EOL . $indent_twostep . '<span class="post-date">' . \get_the_date() . '</span>' . PHP_EOL . $indent_onestep;
				$output .= '</li>'. PHP_EOL;
			}
			$output .= \str_repeat( "\t", $markup_indent_c ) . '</ul>' . PHP_EOL;
			if ( !empty( $args['after_widget'] ) ) {
				$markup_indent_c--;
				$output .= \str_repeat( "\t", $markup_indent_c) . rtrim( $args['after_widget'] ) . PHP_EOL;
			}
			echo $output;
			// Reset the global $the_post as this query will have stomped on it
			\wp_reset_postdata();
		}
	}

	/**
	 * Handles updating the settings for the current Recent Posts widget instance.
	 *
	 * @access public
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *							WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Updated settings to save.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		$instance['number'] = (int) $new_instance['number'];
		$instance['show_date'] = isset( $new_instance['show_date'] ) ? (bool) $new_instance['show_date'] : false;
		return $instance;
	}

	/**
	 * Outputs the settings form for the Recent Posts widget.
	 *
	 * @access public
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {
		$title	 = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$number	= isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
		$show_date = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : false;
?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show:' ); ?></label>
		<input class="tiny-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" /></p>

		<p><input class="checkbox" type="checkbox"<?php checked( $show_date ); ?> id="<?php echo $this->get_field_id( 'show_date' ); ?>" name="<?php echo $this->get_field_name( 'show_date' ); ?>" />
		<label for="<?php echo $this->get_field_id( 'show_date' ); ?>"><?php _e( 'Display post date?' ); ?></label></p>
<?php
	}
}
