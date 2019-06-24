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
		$subfooter_enabled = theme_get_inherit_option($the_post_id, '_subfooter', 'footer', 'sub_footer');
	} else {
		$subfooter_enabled = theme_get_option('footer','sub_footer');
	}
?>
<footer id="footer">
<?php if($subfooter_enabled):?>
	<div id="footer_bottom">
		<div class="inner">
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