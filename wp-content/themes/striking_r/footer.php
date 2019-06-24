<?php
/**
 * The template for displaying the footer.
 */
if(theme_get_option('footer','footer') || theme_get_option('footer','sub_footer')):
	wp_reset_query();
	if(function_exists('is_shop') && is_shop()){
		$the_post_id= wc_get_page_id( 'shop' );
	} else {
		$the_post_id = get_queried_object_id();
	}
	if(is_front_page()){
		$homepage_id = theme_get_option('homepage','home_page');
		if (!empty($homepage_id)) $the_post_id = wpml_get_object_id($homepage_id,'page');
	}
	if ($the_post_id > 0) {
		$footer_enabled = theme_get_inherit_option($the_post_id, '_footer', 'footer', 'footer');
		$subfooter_enabled = theme_get_inherit_option($the_post_id, '_subfooter', 'footer', 'sub_footer');
	} else {
		$footer_enabled = theme_get_option('footer','footer');
		$subfooter_enabled = theme_get_option('footer','sub_footer');
	}
?>
<footer id="footer">
<?php if($footer_enabled) :?>
	<div id="footer_shadow"></div>
	<div class="inner">
<?php
$footer_column = theme_get_option('footer','column');
if(is_numeric($footer_column)):
	switch ( $footer_column ):
		case 1:
			$class = '';
			break;
		case 2:
			$class = 'one_half';
			break;
		case 3:
			$class = 'one_third';
			break;
		case 4:
			$class = 'one_fourth';
			break;
		case 5:
			$class = 'one_fifth';
			break;
		case 6:
			$class = 'one_sixth';
			break;
	endswitch;
	for( $i=1; $i<=$footer_column; $i++ ):
		switch ( $i ) {
		case 1:
			$footer_column_id='footer_widget_area_one';
			break;
		case 2:
			$footer_column_id='footer_widget_area_two';
			break;
		case 3:
			$footer_column_id='footer_widget_area_three';
			break;
		case 4:
			$footer_column_id='footer_widget_area_four';
			break;
		case 5:
			$footer_column_id='footer_widget_area_five';
			break;
		case 6:
			$footer_column_id='footer_widget_area_six';
			break;
		}
		if($i == $footer_column):
?>
			<div id="<?php echo $footer_column_id; ?>" class="<?php echo $class; ?> last"><?php theme_generator('footer_sidebar'); ?></div>
<?php else:?>
			<div id="<?php echo $footer_column_id; ?>" class="<?php echo $class; ?>"><?php theme_generator('footer_sidebar'); ?></div>
<?php endif;		
	endfor;
else:
	switch($footer_column):
		case 'third_sub_third':
?>
		<div id='footer_widget_area_one' class="one_third"><?php theme_generator('footer_sidebar'); ?></div>
		<div id='footer_widget_area_two' class="two_third last">
			<div id='footer_widget_area_sub_one' class="one_third"><?php theme_generator('footer_sidebar'); ?></div>
			<div id='footer_widget_area_sub_two' class="one_third"><?php theme_generator('footer_sidebar'); ?></div>
			<div id='footer_widget_area_sub_three' class="one_third last"><?php theme_generator('footer_sidebar'); ?></div>
		</div>
<?php
			break;
		case 'sub_third_third':
?>
		<div id='footer_widget_area_one' class="two_third">
			<div id='footer_widget_area_sub_one' class="one_third"><?php theme_generator('footer_sidebar'); ?></div>
			<div id='footer_widget_area_sub_two' class="one_third"><?php theme_generator('footer_sidebar'); ?></div>
			<div id='footer_widget_area_sub_three' class="one_third last"><?php theme_generator('footer_sidebar'); ?></div>
		</div>
		<div id='footer_widget_area_two' class="one_third last"><?php theme_generator('footer_sidebar'); ?></div>
<?php
			break;
		case 'third_sub_fourth':
?>
		<div id='footer_widget_area_one' class="one_third"><?php theme_generator('footer_sidebar'); ?></div>
		<div id='footer_widget_area_two' class="two_third last">
			<div id='footer_widget_area_sub_one' class="one_fourth"><?php theme_generator('footer_sidebar'); ?></div>
			<div id='footer_widget_area_sub_two' class="one_fourth"><?php theme_generator('footer_sidebar'); ?></div>
			<div id='footer_widget_area_sub_three' class="one_fourth"><?php theme_generator('footer_sidebar'); ?></div>
			<div id='footer_widget_area_sub_four' class="one_fourth last"><?php theme_generator('footer_sidebar'); ?></div>
		</div>
<?php
			break;
		case 'sub_fourth_third':
?>
		<div id='footer_widget_area_one' class="two_third">
			<div id='footer_widget_area_sub_one' class="one_fourth"><?php theme_generator('footer_sidebar'); ?></div>
			<div id='footer_widget_area_sub_two' class="one_fourth"><?php theme_generator('footer_sidebar'); ?></div>
			<div id='footer_widget_area_sub_three' class="one_fourth"><?php theme_generator('footer_sidebar'); ?></div>
			<div id='footer_widget_area_sub_four' class="one_fourth last"><?php theme_generator('footer_sidebar'); ?></div>
		</div>
		<div id='footer_widget_area_two' class="one_third last"><?php theme_generator('footer_sidebar'); ?></div>
<?php
			break;
		case 'half_sub_half':
?>
		<div id='footer_widget_area_one' class="one_half"><?php theme_generator('footer_sidebar'); ?></div>
		<div id='footer_widget_area_two' class="one_half last">
			<div id='footer_widget_area_sub_one' class="one_half"><?php theme_generator('footer_sidebar'); ?></div>
			<div id='footer_widget_area_sub_two' class="one_half last"><?php theme_generator('footer_sidebar'); ?></div>
		</div>
<?php
			break;
		case 'half_sub_third':
?>
		<div id='footer_widget_area_one' class="one_half"><?php theme_generator('footer_sidebar'); ?></div>
		<div id='footer_widget_area_two' class="one_half last">
			<div id='footer_widget_area_sub_one' class="one_third"><?php theme_generator('footer_sidebar'); ?></div>
			<div id='footer_widget_area_sub_two' class="one_third"><?php theme_generator('footer_sidebar'); ?></div>
			<div id='footer_widget_area_sub_three' class="one_third last"><?php theme_generator('footer_sidebar'); ?></div>
		</div>
<?php
			break;
		case 'sub_half_half':
?>
		<div id='footer_widget_area_one' class="one_half">
			<div id='footer_widget_area_sub_one' class="one_half"><?php theme_generator('footer_sidebar'); ?></div>
			<div id='footer_widget_area_sub_two' class="one_half last"><?php theme_generator('footer_sidebar'); ?></div>
		</div>
		<div id='footer_widget_area_two' class="one_half last"><?php theme_generator('footer_sidebar'); ?></div>
<?php
			break;
		case 'sub_third_half':
?>
		<div id='footer_widget_area_one' class="one_half">
			<div id='footer_widget_area_sub_one' class="one_third"><?php theme_generator('footer_sidebar'); ?></div>
			<div id='footer_widget_area_sub_two' class="one_third"><?php theme_generator('footer_sidebar'); ?></div>
			<div id='footer_widget_area_sub_three' class="one_third last"><?php theme_generator('footer_sidebar'); ?></div>
		</div>
		<div id='footer_widget_area_two' class="one_half last"><?php theme_generator('footer_sidebar'); ?></div>
<?php
			break;
		case 'sub_third_sub_half':
?>
		<div id='footer_widget_area_one' class="one_half">
			<div id='footer_widget_area_sub_one' class="one_third"><?php theme_generator('footer_sidebar'); ?></div>
			<div id='footer_widget_area_sub_two' class="one_third"><?php theme_generator('footer_sidebar'); ?></div>
			<div id='footer_widget_area_sub_three' class="one_third last"><?php theme_generator('footer_sidebar'); ?></div>
		</div>
		<div id='footer_widget_area_two' class="one_half last">
			<div id='footer_widget_area_sub_one' class="one_half"><?php theme_generator('footer_sidebar'); ?></div>
			<div id='footer_widget_area_sub_two' class="one_half last"><?php theme_generator('footer_sidebar'); ?></div>
		</div>
<?php
			break;
		case 'sub_half_sub_third':
?>
		<div id='footer_widget_area_one' class="one_half">
			<div id='footer_widget_area_sub_one' class="one_half"><?php theme_generator('footer_sidebar'); ?></div>
			<div id='footer_widget_area_sub_two' class="one_half last"><?php theme_generator('footer_sidebar'); ?></div>
		</div>
		<div id='footer_widget_area_two' class="one_half last">
			<div id='footer_widget_area_sub_one' class="one_third"><?php theme_generator('footer_sidebar'); ?></div>
			<div id='footer_widget_area_sub_two' class="one_third"><?php theme_generator('footer_sidebar'); ?></div>
			<div id='footer_widget_area_sub_three' class="one_third last"><?php theme_generator('footer_sidebar'); ?></div>
		</div>
<?php
			break;
	endswitch;
endif;
?>
		<div class="clearboth"></div>
	</div>
<?php endif;?>
<?php if($subfooter_enabled):?>
	<div id="footer_bottom">
		<div class="inner">			
<?php 
	$footer_right_area_type = theme_get_option('footer','footer_right_area_type');
	switch($footer_right_area_type){
		case 'html':
			echo '<div id="footer_right_area">';
			echo do_shortcode(wpml_t(THEME_NAME, 'Footer Right Area Html Code',stripslashes( theme_get_option('footer','footer_right_area_html') )));
			echo '</div>';
			break;
		case 'menu':
			echo theme_generator('menu_footer');
			break;
		case 'widget':
			echo '<div id="footer_right_area">';
			dynamic_sidebar(__('Sub Footer Widget Area','theme_admin'));
			echo '</div>';
			break;
	}
?>
			<div id="copyright"><?php echo wpml_t(THEME_NAME, 'Copyright Footer Text',stripslashes(theme_get_option('footer','copyright')))?></div>
			<div class="clearboth"></div>
		</div>
	</div>
<?php endif;?>
</footer>
<?php
endif;
	wp_footer();
?>
</div>
<?php
	//theme_add_cufon_code_footer();
	if(theme_get_option('general','analytics_position')=='bottom'){
		echo theme_google_analytics_code();
	}
	$custom_js=stripslashes(theme_get_option('general','custom_js'));
	if(!empty($custom_js)){
		$minify=theme_get_option('advanced','theme_minify_js');
		if ($minify) $custom_js=theme_minify_css_js($custom_js,true);
		echo $custom_js;
	}
?>
</body>
</html>