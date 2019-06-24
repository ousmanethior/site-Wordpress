<?php
/**
 * Featured Image Widget Class
 */
if (!class_exists('Theme_Widget_Featured_Image')) {
class Theme_Widget_Featured_Image extends WP_Widget {
	private static $instance = 0;
	public function __construct(){
		$widget_ops = array('classname' => 'widget_featured_image', 'description' => __( 'Displays the Featured Image In a Widget.', 'theme_admin' ) );
		parent::__construct('theme_featured_image', THEME_SLUG.' - '.__('Featured Image', 'theme_admin'), $widget_ops);
	}

	public function widget( $args, $instance ) {

		if (is_single() || is_page()) {
			$thumbnail_id = get_post_thumbnail_id();
			if (!empty($thumbnail_id)) {
				$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
				if ( !$width = (int) $instance['width'] ){
					$width = 275;
				} else if ( $width < 1 ){
					$width = 275;
				}
				if ( !$height = (int) $instance['height'] ){
					$height = 275;
				} else if ( $height < 1 ){
					$height = 275;
				}
				echo $args['before_widget'];
				if ( $title ) {
					echo $args['before_title'] . $title . $args['after_title'];
				}
				$autoheight = $instance['autoheight'];
				$lightbox = $instance['lightbox'];
				if ($autoheight=='1') $autoheight='true'; else $autoheight='false';
				if ($lightbox=='1') {
					$lightbox='true';
					$lightbox_icon = ' effect="icon" icon="zoom"';
				} else {
					$lightbox='false';
					$lightbox_icon = '';
				}
				echo do_shortcode('[image source_type="attachment_id" source_value="'.$thumbnail_id .'" width="'.$width.'" height="'.$height.'" autoHeight="'.$autoheight.'" lightbox="'.$lightbox.'"'.$lightbox_icon.'] ');
				echo $args['after_widget'];
			}
		}
	}

	/**
	 * Handles updating settings for the current Featured Image widget instance.
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
		$instance['width'] = (int) $new_instance['width'];
		$instance['height'] = (int) $new_instance['height'];
		$instance['autoheight'] = !empty($new_instance['autoheight']) ? 1 : 0;
		$instance['lightbox'] = !empty($new_instance['lightbox']) ? 1 : 0;
		return $instance;
	}

	/**
	 * Outputs the settings form for the Featured Image widget.
	 *
	 * @since 2.8.0
	 * @access public
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
		$title = sanitize_text_field( $instance['title'] );
		if ( !isset($instance['width']) || !$width = (int) $instance['width'] ){
			$width = 275;
		}
		if ( !isset($instance['height']) || !$height = (int) $instance['height'] ){
			$height = 275;
		}
		$autoheight = isset( $instance['autoheight'] ) ? (bool) $instance['autoheight'] : true;
		$lightbox = isset( $instance['lightbox'] ) ? (bool) $instance['lightbox'] : true;
		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'theme_admin'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('width'); ?>"><?php _e('Width of image:', 'theme_admin'); ?></label>
		<input id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" type="text" value="<?php echo $width; ?>" size="3" /> px </p>

		<p><label for="<?php echo $this->get_field_id('height'); ?>"><?php _e('Height of image:', 'theme_admin'); ?></label>
		<input id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" type="text" value="<?php echo $height; ?>" size="3" /> px </p>

		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('autoheight'); ?>" name="<?php echo $this->get_field_name('autoheight'); ?>"<?php checked( $autoheight ); ?> />
		<label for="<?php echo $this->get_field_id('autoheight'); ?>"><?php _e( 'Auto Adjust Height?', 'theme_admin' ); ?></label></p>

		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('lightbox'); ?>" name="<?php echo $this->get_field_name('lightbox'); ?>"<?php checked( $lightbox ); ?> />
		<label for="<?php echo $this->get_field_id('lightbox'); ?>"><?php _e( 'Show Image In Lightbox?', 'theme_admin' ); ?></label></p>
		<?php
	}
}
}