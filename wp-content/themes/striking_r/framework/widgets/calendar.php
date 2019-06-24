<?php
/**
 * Twitter Widget Class
 */
if (!class_exists('Theme_Widget_Calendar')) {
class Theme_Widget_Calendar extends WP_Widget {
	private static $instance = 0;
	public function __construct(){
		$widget_ops = array('classname' => 'widget_calendar', 'description' => __( 'Displays a Calendar.', 'theme_admin' ) );
		parent::__construct('theme_calendar', THEME_SLUG.' - '.__('Calendar', 'theme_admin'), $widget_ops);
	}

	public function widget( $args, $instance ) {
		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		$calendar_id = 'theme_calendar_wrap-'.md5(rand(10, 10000));

		echo $args['before_widget'];
		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}
		if ( 0 === self::$instance ) {
			echo '<div id="calendar_wrap" class="'.$calendar_id.'">';
		} else {
			echo '<div class="'.$calendar_id.'">';
		}
		add_action( 'pre_get_posts', 'theme_set_pre_get_calendar_vars');
		theme_get_calendar();
		remove_action( 'pre_get_posts', 'theme_set_pre_get_calendar_vars');
		echo '</div>';
		echo $args['after_widget'];

		self::$instance++;
	}

	/**
	 * Handles updating settings for the current Calendar widget instance.
	 *
	 * @since 2.8.0
	 * @access public
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Updated settings to save.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		return $instance;
	}

	/**
	 * Outputs the settings form for the Calendar widget.
	 *
	 * @since 2.8.0
	 * @access public
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
		$title = sanitize_text_field( $instance['title'] );
		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'theme_admin'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
		<?php
	}
}
}