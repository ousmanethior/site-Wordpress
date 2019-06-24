<?php
if(!function_exists('theme_shortcode_video')){
function theme_shortcode_video($atts){
	if(isset($atts['type']) && !empty($atts['type'])){
		switch($atts['type']){
			case 'html5':
				return theme_video_html5($atts);
				break;
			case 'flash':
				return theme_video_flash($atts);
				break;
			case 'youtube':
				return theme_video_youtube($atts);
				break;
			case 'vimeo':
				return theme_video_vimeo($atts);
				break;
			case 'dailymotion':
				return theme_video_dailymotion($atts);
				break;
		}
	} else {
		if(function_exists('wp_video_shortcode')){
			return wp_video_shortcode($atts);
		}
	}
	return '';
}
}
add_shortcode('video', 'theme_shortcode_video');
add_shortcode('tvideo', 'theme_shortcode_video');

if(!function_exists('theme_video_html5_print_scripts')){
function theme_video_html5_print_scripts(){
	$library = apply_filters( 'wp_video_shortcode_library', 'mediaelement' );
	if ( 'mediaelement' === $library && did_action( 'init' ) ) {
		wp_enqueue_style( 'wp-mediaelement' );
		wp_enqueue_script( 'wp-mediaelement' );
	}
}
}

if(!function_exists('theme_video_html5')){
function theme_video_html5($atts){
	extract(shortcode_atts(array(
		'mp4' => '',
		'webm' => '',
		'ogg' => '',
		'wmv' => '',
		'flv' => '',
		'poster' => '',
		'width' => false,
		'height' => false,
		'download' => false,
		'preload' => false,
		'autoplay' => false,
		"loop" => false,
		'alwaysshowcontrols'=> '',
		'hidevideocontrolsonload'=> '',
	        'clicktoplaypause'=> '',
	        'ipadusenativecontrols'=>'',
	        'iphoneusenativecontrols'=>'',
	        'androidusenativecontrols'=>'',
		// captions
		'captions' => '',
		'captionslang' => 'en',
		'class' => '',
	), $atts));

	add_action('wp_footer', 'theme_video_html5_print_scripts');
	
	if ($height && !$width) $width = intval($height * 16 / 9);
	if (!$height && $width) $height = intval($width * 9 / 16);
	if (!$height && !$width){
		$height = theme_get_option('media','html5_height');
		$width = theme_get_option('media','html5_width');
	}
	
	$width = intval($width);
	$height = intval($height);
	
	if (!$download){
		$download = theme_get_option('media','html5_download');
	}else{
		if($download==='true'){
			$download = true;
		}else{
			$download = false;
		}
	}
	if (!$preload){
		$preload = theme_get_option('media','html5_preload');
	}else{
		if($preload==='true'){
			$preload = true;
		}else{
			$preload = false;
		}
	}
	if (!$autoplay){
		$autoplay = theme_get_option('media','html5_autoplay');
	}else{
		if($autoplay==='true'){
			$autoplay = true;
		}else{
			$autoplay = false;
		}
	}

	if (!$alwaysshowcontrols){
		$alwaysshowcontrols = theme_get_option('media','html5_alwaysShowControls');
	}else{
		if($alwaysshowcontrols==='true'){
			$alwaysshowcontrols = true;
		}else{
			$alwaysshowcontrols = false;
		}
	}
	if (!$hidevideocontrolsonload){
		$hidevideocontrolsonload = theme_get_option('media','html5_hideVideoControlsOnLoad');
	}else{
		if($hidevideocontrolsonload==='true'){
			$hidevideocontrolsonload = true;
		}else{
			$hidevideocontrolsonload = false;
		}
	}
	if (!$clicktoplaypause){
		$clicktoplaypause = theme_get_option('media','html5_clickToPlayPause');
	}else{
		if($clicktoplaypause==='true'){
			$clicktoplaypause = true;
		}else{
			$clicktoplaypause = false;
		}
	}

	if (!$ipadusenativecontrols){
		$ipadusenativecontrols = theme_get_option('media','html5_iPadUseNativeControls');
	}else{
		if($ipadusenativecontrols==='true'){
			$ipadusenativecontrols = true;
		}else{
			$ipadusenativecontrols = false;
		}
	}
	if (!$iphoneusenativecontrols){
		$iphoneusenativecontrols = theme_get_option('media','html5_iPhoneUseNativeControls');
	}else{
		if($iphoneusenativecontrols==='true'){
			$iphoneusenativecontrols = true;
		}else{
			$iphoneusenativecontrols = false;
		}
	}
	if (!$androidusenativecontrols){
		$androidusenativecontrols = theme_get_option('media','html5_androidUseNativeControls');
	}else{
		if($androidusenativecontrols==='true'){
			$androidusenativecontrols = true;
		}else{
			$androidusenativecontrols = false;
		}
	}

	// flv source supplied
	$flv_source = '';
	if ($flv) {
		$flv_source = '<source type="video/flv" src="'.htmlspecialchars($flv).'" />';
		$flv_link = '<a href="'.$flv.'">Flv</a>';
	}
	
	// wmv source supplied
	$wmv_source = '';
	if ($wmv) {
		$wmv_source = '<source type="video/wmv" src="'.htmlspecialchars($wmv).'" />';
		$wmv_link = '<a href="'.$wmv.'">Wmv</a>';
	}

	// Ogg source supplied
	$ogg_source = '';
	if ($ogg) {
		$ogg_source = '<source type="video/ogg" src="'.htmlspecialchars($ogg).'" />';
		$ogg_link = '<a href="'.$ogg.'">Ogg</a>';
	}

	// WebM Source Supplied
	$webm_source = '';
	if ($webm) {
		$webm_source = '<source type="video/webm" src="'.htmlspecialchars($webm).'" />';
		$webm_link = '<a href="'.$webm.'">WebM</a>';
	}

	// MP4 Source Supplied
	$mp4_source = '';
	if ($mp4) {
		$mp4_source = '<source type="video/mp4" src="'.htmlspecialchars($mp4).'" />';
		$mp4_link = '<a href="'.$mp4.'">MP4</a>';
	}
	
	
	$captions_source = '';
	if ($captions) {
		$captions_source = '<track src="'.$captions.'" kind="subtitles" srclang="'.$captionslang.'" />';
	}
	
	$poster_attribute = '';
	$image_fallback = '';
	$poster_var = '';
	if ($poster) {
		$poster_attribute = 'poster="'.htmlspecialchars($poster).'"';
		$poster_var = 'poster='.htmlspecialchars($poster).'&amp;';
		$image_fallback = <<<_end_
			<img src="$poster" alt="Poster Image" title="No video playback capabilities." />
_end_;
	}
	
	if ($preload) {
		$preload_attribute = 'preload="auto"';
	} else {
		$preload_attribute = 'preload="none"';
	}
	
	$autoplay_var = '';
	if ($autoplay) {
		$poster_var = 'autoplay=true&amp;';
		$autoplay_attribute = "autoplay";
	} else {
		$autoplay_attribute = "";
	}
	$js_options = array();
	if ($loop) {
		$js_options[] = 'loop: ' . $loop;
	}
	if ($width) {
		$js_options[] = 'defaultVideoWidth: ' . $width;
	}
	if ($height) {
		$js_options[] = 'defaultVideoHeight: ' . $height;
	}

	if ($alwaysshowcontrols){
		$js_options[] = 'alwaysShowControls: true';
	} else {
		$js_options[] = 'alwaysShowControls: false';
	}
	if ($hidevideocontrolsonload){
		$js_options[] = 'hideVideoControlsOnLoad: true';
	} else {
		$js_options[] = 'hideVideoControlsOnLoad: false';
	}
	if ($clicktoplaypause){
		$js_options[] = 'clickToPlayPause: true';
	} else {
		$js_options[] = 'clickToPlayPause: false';
	}

	if ($ipadusenativecontrols){
		$js_options[] = 'iPadUseNativeControls: true';
	} else {
		$js_options[] = 'iPadUseNativeControls: false';
	}
	if ($iphoneusenativecontrols){
		$js_options[] = 'iPhoneUseNativeControls: true';
	} else {
		$js_options[] = 'iPhoneUseNativeControls: false';
	}
	if ($androidusenativecontrols){
		$js_options[] = 'androidUseNativeControls: true';
	} else {
		$js_options[] = 'androidUseNativeControls: false';
	}
	
	$js_options[] = 'enableAutosize: true';
	$js_options_string = implode(",", $js_options);
	
	$uri = THEME_URI;
	
	if($download){
		$download_string = <<<HTML
<p class="vjs-no-video"><strong>Download Video:</strong>
		{$mp4_link}
		{$webm_link}
		{$ogg_link}
		{$flv}
		{$wmv}
</p>
HTML;
	} else {
		$download_string = '';
	}
	$id = rand(100,1000);
	$uri = THEME_URI;
	$ratio = round($width/$height,2);
	$detect = new Mobile_Detect;
	if($detect->isMobile()){
		$init_mediaelement = 'false';
	}else{
		$init_mediaelement = 'true';
	}
	if($class){
		$class = ' '.$class;
	}
	return <<<HTML
<div class="video_frame{$class}" style="width:{$width}px;" data-ratio="{$ratio}">
<video id="html5_video_{$id}" {$poster_attribute} style="width: 100%; height: 100%;" controls="controls" {$preload_attribute} {$autoplay_attribute} width="{$width}" height="{$height}">
{$mp4_source}{$webm_source}{$ogg_source}{$flv_source}{$wmv_source}{$captions_source}
</video>
</div>
<script type="text/javascript">
jQuery(document).ready(function($) {
	if({$init_mediaelement}){
		jQuery("#html5_video_{$id}").mediaelementplayer({{$js_options_string}});
	}
});
</script>
HTML;
}
}

if(!function_exists('theme_video_flash')){
function theme_video_flash($atts) {
	extract(shortcode_atts(array(
		'src' 	=> '',
		'id' 	=> '',
		'width' 	=> false,
		'height' 	=> false,
		'play'		=> 'false',
		'flashvars' => '',
	), $atts));
	
	if($id!=''){
		$id = ' id="'.$id.'"';
	}
	if ($height && !$width) $width = intval($height * 16 / 9);
	if (!$height && $width) $height = intval($width * 9 / 16);
	if (!$height && !$width){
		$height = theme_get_option('media','flash_height');
		$width = theme_get_option('media','flash_width');
	}

	$uri = THEME_URI;
	$ratio = round($width/$height,2);
	if (!empty($src)){
		return <<<HTML
<div class="video_frame" data-ratio="{$ratio}" style="width:{$width}px;height:{$height}px">
<object{$id} class="flash" style="width:100%;height:100%;" type="application/x-shockwave-flash" data="{$src}">
	<param name="movie" value="{$src}" />
	<param name="allowFullScreen" value="true" />
	<param name="allowscriptaccess" value="always" />
	<param name="expressInstaller" value="{$uri}/swf/expressInstall.swf"/>
	<param name="play" value="{$play}"/>
	<param name="wmode" value="transparent" />
	<embed src="$src" type="application/x-shockwave-flash" wmode="transparent" allowscriptaccess="always" allowfullscreen="true" width="{$width}" height="{$height}" />
</object>
</div>
HTML;
	}
}
}


if(!function_exists('theme_video_vimeo')){
function theme_video_vimeo($atts) {
	extract(shortcode_atts(array(
		'clip_id' 	=> '',
		'width' => false,
		'height' => false,
		'byline'    => false,
		'title'     => false,
		'portrait'  => false,
		'autoplay'  => false,
		'loop'      => false,
	), $atts));

	if ($height && !$width) $width = intval($height * 16 / 9);
	if (!$height && $width) $height = intval($width * 9 / 16);
	if (!$height && !$width){
		$height = theme_get_option('media','vimeo_height');
		$width = theme_get_option('media','vimeo_width');
	}
	if (!$byline){
		$byline = (theme_get_option('media','vimeo_byline') == true)?1:0;
	}else{
		if($byline==='true'){
			$byline = '1';
		}else{
			$byline = '0';
		}
	}
	if (!$title){
		$title = (theme_get_option('media','vimeo_title') == true)?1:0;
	}else{
		if($title==='true'){
			$title = '1';
		}else{
			$title = '0';
		}
	}
	if (!$portrait){
		$portrait = (theme_get_option('media','vimeo_portrait') == true)?1:0;
	}else{
		if($portrait==='true'){
			$portrait = '1';
		}else{
			$portrait = '0';
		}
	}
	if (!$autoplay){
		$autoplay = (theme_get_option('media','vimeo_autoplay') == true)?1:0;
	}else{
		if($autoplay==='true'){
			$autoplay = '1';
		}else{
			$autoplay = '0';
		}
	}
	if (!$loop){
		$loop = (theme_get_option('media','vimeo_loop') == true)?1:0;
	}else{
		if($loop==='true'){
			$loop = '1';
		}else{
			$loop = '0';
		}
	}
	$ratio = round($width/$height,2);
	$urlprefix="https";
	if (!empty($clip_id) && is_numeric($clip_id)){
		return "<div class='video_frame' data-ratio='{$ratio}' style='width:{$width}px;height:{$height}px'><iframe class='vimeo' style='height:100%;width:100%' src='{$urlprefix}://player.vimeo.com/video/$clip_id?title={$title}&amp;byline={$byline}&amp;portrait={$portrait}&amp;autoplay={$autoplay}&amp;loop={$loop}' width='100%' height='100%' frameborder='0' webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></div>";
	} else {
		return "";
	}
}
}

if(!function_exists('theme_video_youtube')){
function theme_video_youtube($atts, $content=null) {
	extract(shortcode_atts(array(
		'clip_id' 	=> '',
		'width' 	=> false,
		'height' 	=> false,
		'autohide'  => false,
		'autoplay'  => false,
		'controls'  => false,
		'disablekb' => false,
		'fs'        => false,
		'loop'      => false,
		'rel'       => false,
		'showinfo'  => false,
		'start'     => '0',
		'modestbranding' => false,
		'theme'     => 'default',
	), $atts));
	
	if ($height && !$width) $width = intval($height * 16 / 9);
	if (!$height && $width) $height = intval($width * 9 / 16) + 25;
	if (!$height && !$width){
		$height = theme_get_option('media','youtube_height');
		$width = theme_get_option('media','youtube_width');
	}
	if (!$autohide){
		$autohide = theme_get_option('media','youtube_autohide');
	}
	if (!$autoplay){
		$autoplay = (theme_get_option('media','youtube_autoplay') == true)?1:0;
	}else{
		if($autoplay==='true'){
			$autoplay = '1';
		}else{
			$autoplay = '0';
		}
	}
	if($autoplay!='1'){
		$autoplay = '';
	}else{
		$autoplay = '&amp;autoplay=1';
	}
	if (!$controls){
		$controls = (theme_get_option('media','youtube_controls') == true)?1:0;
	}else{
		if($controls==='true'){
			$controls = '1';
		}else{
			$controls = '0';
		}
	}
	if (!$disablekb){
		$disablekb = (theme_get_option('media','youtube_disablekb') == true)?1:0;
	}else{
		if($disablekb==='true'){
			$disablekb = '1';
		}else{
			$disablekb = '0';
		}
	}
	if ($theme === 'default'){
		$theme = theme_get_option('media','youtube_theme');
	}
	if (!$fs){
		$fs = (theme_get_option('media','youtube_fs') == true)?1:0;
	}else{
		if($fs==='true'){
			$fs = '1';
		}else{
			$fs = '0';
		}
	}
	
	if (!$loop){
		$loop = (theme_get_option('media','youtube_loop') == true)?'1':'0';
	}else{
		if($loop==='true'){
			$loop = '1';
		}else{
			$loop = '0';
		}
	}
	if($loop === '1'){
		$loop = '1&amp;playlist='.$clip_id;
	}
	if (!$rel){
		$rel = (theme_get_option('media','youtube_rel') == true)?1:0;
	}else{
		if($rel==='true'){
			$rel = '1';
		}else{
			$rel = '0';
		}
	}
	if (!$showinfo){
		$showinfo = (theme_get_option('media','youtube_showinfo') == true)?1:0;
	}else{
		if($showinfo==='true'){
			$showinfo = '1';
		}else{
			$showinfo = '0';
		}
	}

	if (!$modestbranding){
		$modestbranding = (theme_get_option('media','youtube_modestbranding') == true)?1:0;
	}else{
		if($modestbranding==='true'){
			$modestbranding = '1';
		}else{
			$modestbranding = '0';
		}
	}
	$ratio = round($width/$height,2);
	$urlprefix="https";
	if (!empty($clip_id)){
		return "<div class='video_frame' data-ratio='{$ratio}' style='height:{$height}px;width:{$width}px'><iframe class='youtube' style='height:100%;width:100%' src='{$urlprefix}://www.youtube.com/embed/{$clip_id}?autohide={$autohide}{$autoplay}&amp;controls={$controls}&amp;disablekb={$disablekb}&amp;fs={$fs}&amp;start={$start}&amp;loop={$loop}&amp;rel={$rel}&amp;showinfo={$showinfo}&amp;theme={$theme}&amp;modestbranding={$modestbranding}&amp;wmode=transparent' width='100%' height='100%' frameborder='0' webkitAllowFullScreen mozallowfullscreen allowfullscreen></iframe></div>";
	}
}
}

if(!function_exists('theme_video_dailymotion')){
function theme_video_dailymotion($atts, $content=null) {
	extract(shortcode_atts(array(
		'clip_id' 	=> '',
		'width' 	=> false,
		'height' 	=> false,
		'related'   => false,
		'autoplay'  => false,
		'chromeless'=> false,
	), $atts));
	
	if ($height && !$width) $width = intval($height * 16 / 9);
	if (!$height && $width) $height = intval($width * 9 / 16);
	if (!$height && !$width){
		$height = theme_get_option('media','dailymotion_height');
		$width = theme_get_option('media','dailymotion_width');
	}
	if (!$related){
		$related = (theme_get_option('media','dailymotion_related') == true)?1:0;
	}else{
		if($related==='true'){
			$related = '1';
		}else{
			$related = '0';
		}
	}
	if (!$autoplay){
		$autoplay = (theme_get_option('media','dailymotion_autoplay') == true)?1:0;
	}else{
		if($autoplay==='true'){
			$autoplay = '1';
		}else{
			$autoplay = '0';
		}
	}
	if (!$chromeless){
		$chromeless = (theme_get_option('media','dailymotion_chromeless') == true)?1:0;
	}else{
		if($chromeless==='true'){
			$chromeless = '1';
		}else{
			$chromeless = '0';
		}
	}
//&additionalInfos=0
//&hideInfos=0
	$ratio = round($width/$height,2);
	if (!empty($clip_id)){
		$urlprefix="https";
		return "<div class='video_frame' data-ratio='{$ratio}' style='height:{$height}px;width:{$width}px'><iframe class='dailymotion' style='height:100%;width:100%' src='{$urlprefix}://www.dailymotion.com/embed/video/$clip_id?width=$width&amp;autoPlay={$autoplay}&amp;related={$related}&amp;chromeless={$chromeless}&amp;theme=none&amp;foreground=%23F7FFFD&amp;highlight=%23FFC300&amp;background=%23171D1B&amp;iframe=1&amp;wmode=transparent' width='100%' height='100%' frameborder='0' webkitAllowFullScreen mozallowfullscreen allowfullscreen></iframe></div>";
	}
}
}

if(!function_exists('theme_audio')){
function theme_audio( $attr, $content = '' ) {
	$post_id = get_post() ? get_the_ID() : 0;

	static $instance = 0;
	$instance++;

	/**
	 * Filters the default audio shortcode output.
	 *
	 * If the filtered output isn't empty, it will be used instead of generating the default audio template.
	 *
	 * @since 3.6.0
	 *
	 * @param string $html     Empty variable to be replaced with shortcode markup.
	 * @param array  $attr     Attributes of the shortcode. @see wp_audio_shortcode()
	 * @param string $content  Shortcode content.
	 * @param int    $instance Unique numeric ID of this audio shortcode instance.
	 */
	$override = apply_filters( 'wp_audio_shortcode_override', '', $attr, $content, $instance );
	if ( '' !== $override ) {
		return $override;
	}

	$audio = null;

	$default_types = wp_get_audio_extensions();
	$defaults_atts = array(
		'mp3' => '',
		'ogg' => '',
		'wav' => '',
		'width' => false,
		'height' => false,
		'src'      => '',
		'loop'     => false,
		'autoplay' => false,
		'preload'  => false,
		'class' => '',
		'wpclass'    => 'wp-audio-shortcode',
		'style'    => 'width: 100%;',
	);
	foreach ( $default_types as $type ) {
		$defaults_atts[$type] = '';
	}

	$atts = shortcode_atts( $defaults_atts, $attr, 'audio' );
	
	if (!$atts['width']){
		!$atts['width'] = theme_get_option('media','audio_width');
	}
	if (!$atts['height']){
		!$atts['height'] = theme_get_option('media','audio_height');
	}
	
	if (!$atts['preload']){
		$atts['preload'] = theme_get_option('media','audio_preload');
	}
	if($atts['preload']==='true'){
		$atts['preload'] = "auto";
	}else{
		$atts['preload'] = "none";
	}
	
	if (!$atts['autoplay']){
		$atts['autoplay'] = theme_get_option('media','audio_autoplay');
	}else{
		if($atts['autoplay']==='true'){
			$atts['autoplay'] = true;
		}else{
			$atts['autoplay'] = false;
		}
	}

	if (!$atts['loop']){
		$atts['loop'] = theme_get_option('media','audio_loop');
	}
	if($atts['loop']===false){
		$atts['loop'] = 'false';
	}else{
		$atts['loop'] = 'true';
	}
	
	if($atts['class']){
		$atts['class'] = ' '.$atts['class'];
	}
	
	$primary = false;
	if ( ! empty( $atts['src'] ) ) {
		$type = wp_check_filetype( $atts['src'], wp_get_mime_types() );
		if ( ! in_array( strtolower( $type['ext'] ), $default_types ) ) {
			return sprintf( '<a class="wp-embedded-audio" href="%s">%s</a>', esc_url( $atts['src'] ), esc_html( $atts['src'] ) );
		}
		$primary = true;
		array_unshift( $default_types, 'src' );
	} else {
		foreach ( $default_types as $ext ) {
			if ( ! empty( $atts[ $ext ] ) ) {
				$type = wp_check_filetype( $atts[ $ext ], wp_get_mime_types() );
				if ( strtolower( $type['ext'] ) === $ext ) {
					$primary = true;
				}
			}
		}
	}

	if ( ! $primary ) {
		$audios = get_attached_media( 'audio', $post_id );
		if ( empty( $audios ) ) {
			return;
		}

		$audio = reset( $audios );
		$atts['src'] = wp_get_attachment_url( $audio->ID );
		if ( empty( $atts['src'] ) ) {
			return;
		}

		array_unshift( $default_types, 'src' );
	}

	/**
	 * Filters the media library used for the audio shortcode.
	 *
	 * @since 3.6.0
	 *
	 * @param string $library Media library used for the audio shortcode.
	 */
	 $library = apply_filters( 'wp_audio_shortcode_library', 'mediaelement' );
	 if ( 'mediaelement' === $library && did_action( 'init' ) ) {
		wp_enqueue_style( 'wp-mediaelement' );
		wp_enqueue_script( 'wp-mediaelement' );
	}
	
	/**
	 * Filters the class attribute for the audio shortcode output container.
	 *
	 * @since 3.6.0
	 *
	 * @param string $class CSS class or list of space-separated classes.
	 */
	$atts['wpclass'] = apply_filters( 'wp_audio_shortcode_class', $atts['wpclass'] );
	
	$height='height:'.$atts["height"].'px;';
	$width='width:'.$atts["width"].'px;';
	
	$atts['style'].=$atts['style'].$height;

	$html_atts = array(
		'class'    => $atts['wpclass'],
		'id'       => sprintf( 'audio-%d-%d', $post_id, $instance ),
		'loop'     => wp_validate_boolean( $atts['loop'] ),
		'autoplay' => wp_validate_boolean( $atts['autoplay'] ),
		'preload'  => $atts['preload'],
		'style'    => $atts['style'],
	);

	// These ones should just be omitted altogether if they are blank
	foreach ( array( 'loop', 'autoplay', 'preload' ) as $a ) {
		if ( empty( $html_atts[$a] ) ) {
			unset( $html_atts[$a] );
		}
	}

	$attr_strings = array();
	foreach ( $html_atts as $k => $v ) {
		$attr_strings[] = $k . '="' . esc_attr( $v ) . '"';
	}
	$html = '<div class="audio_frame'.$atts["class"].'" style="'.$height.$width.'">';
	if ( 'mediaelement' === $library && 1 === $instance ) {
		$html .= "<!--[if lt IE 9]><script>document.createElement('audio');</script><![endif]-->\n";
	}
	$html .= sprintf( '<audio %s controls="controls">', join( ' ', $attr_strings ) );

	$fileurl = '';
	$source = '<source type="%s" src="%s" />';
	foreach ( $default_types as $fallback ) {
		if ( ! empty( $atts[ $fallback ] ) ) {
			if ( empty( $fileurl ) ) {
				$fileurl = $atts[ $fallback ];
			}
			$type = wp_check_filetype( $atts[ $fallback ], wp_get_mime_types() );
			$url = add_query_arg( '_', $instance, $atts[ $fallback ] );
			$html .= sprintf( $source, $type['type'], esc_url( $url ) );
		}
	}

	if ( 'mediaelement' === $library ) {
		$html .= wp_mediaelement_fallback( $fileurl );
	}
	$html .= '</audio></div>';

	/**
	 * Filters the audio shortcode output.
	 *
	 * @since 3.6.0
	 *
	 * @param string $html    Audio shortcode HTML output.
	 * @param array  $atts    Array of audio shortcode attributes.
	 * @param string $audio   Audio file.
	 * @param int    $post_id Post ID.
	 * @param string $library Media library used for the audio shortcode.
	 */
	return apply_filters( 'wp_audio_shortcode', $html, $atts, $audio, $post_id, $library );
}
}
add_shortcode('taudio', 'theme_audio');
add_shortcode('audio', 'theme_audio');

// taken from media.php wordpress add_shortcode( 'audio', 'wp_audio_shortcode' );