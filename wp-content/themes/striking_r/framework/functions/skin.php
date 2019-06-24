<?php 
	/* general settings */
	$logo_bottom = theme_get_option('general','logo_bottom');
	$header_height = theme_get_option('general','header_height');
	if(theme_get_option('advanced','complex_class')){
		$complex_prefix='theme_';
	} else $complex_prefix='';
	/* font settings */
	$font = theme_get_option('font');
	$font['font_family']=stripslashes($font['font_family']);
	if($font['link_underline']){
		$font['link_underline']='underline';
	}else{
		$font['link_underline']='none';
	}

	/* fontface */
	$fontface_css = '';
	if(theme_get_option('font','fontface_enabled')){
		$fontface_used = theme_get_option('font','fontface_used');
		$fontface_default = theme_get_option('font','fontface_default');
		if(is_array($fontface_used)){
			foreach ($fontface_used as $font_str){
				if(is_array($font_str)){
					$font_name = $font_str['name'];
					$font_folder = $font_str['folder'];
					if(file_exists($font_str['dir'])){
						$file_content = file_get_contents($font_str['dir']);
						if( preg_match("/@font-face\s*{[^}]*?font-family\s*:\s*('|\")$font_name\\1.*?}/is", $file_content, $match) ){
							if(false === stripos($font_str['url'],get_stylesheet_directory_uri())){
								$uri = str_replace('stylesheet.css','',$font_str['url']);
								$uri = preg_replace("(^https?:)", "", $uri );
								$fontface_css .= preg_replace("/url\s*\(\s*['|\"]\s*/is","\\0$uri",$match[0])."\n";
							}else{
								$uri = THEME_FONTFACE_URI;
								$uri = preg_replace("(^https?:)", "", $uri );
								$fontface_css .= preg_replace("/url\s*\(\s*['|\"]\s*/is","\\0".$uri."/$font_folder/",$match[0])."\n";
							}
						}
					}
			
				}else{
					$font_info = explode("|", $font_str);
					$font_name = $font_info[1];
					$stylesheet = THEME_FONTFACE_DIR.'/'.$font_info[0].'/stylesheet.css';
					if(file_exists($stylesheet)){
						$file_content = file_get_contents($stylesheet);
						if( preg_match("/@font-face\s*{[^}]*?font-family\s*:\s*('|\")$font_info[1]\\1.*?}/is", $file_content, $match) ){
							if(defined('THEME_CHILD_NAME')){
								$uri = get_template_directory_uri();
								$uri = preg_replace("(^https?:)", "", $uri );
								$fontface_css .= preg_replace("/url\s*\(\s*['|\"]\s*/is","\\0".$uri."/fontfaces/$font_info[0]/",$match[0])."\n";
							}else{
								$uri = THEME_FONTFACE_URI;
								$uri = preg_replace("(^https?:)", "", $uri );
								$fontface_css .= preg_replace("/url\s*\(\s*['|\"]\s*/is","\\0".$uri."/$font_info[0]/",$match[0])."\n";
							}
						}
					}elseif(defined('THEME_CHILD_FONTFACE_DIR') && file_exists(THEME_CHILD_FONTFACE_DIR.'/'.$font_info[0].'/stylesheet.css')){
						$stylesheet = THEME_CHILD_FONTFACE_DIR.'/'.$font_info[0].'/stylesheet.css';
						$file_content = file_get_contents($stylesheet);
						if( preg_match("/@font-face\s*{[^}]*?font-family\s*:\s*('|\")$font_info[1]\\1.*?}/is", $file_content, $match) ){
							$uri = THEME_CHILD_FONTFACE_URI;
							$uri = preg_replace("(^https?:)", "", $uri );
							$fontface_css .= preg_replace("/url\s*\(\s*['|\"]\s*/is","\\0".$uri."/$font_info[0]/",$match[0])."\n";
						}
					}
					if($fontface_default == $font_str){
						$fontface_default_name = $font_name;
					}
				}
			}
		}
		
		if(isset($fontface_default_name)){
			$default_code =  <<<CSS
#site_name, #site_description, 
.kwick_title, .kwick_detail h3, 
#navigation a, 
.portfolio_title, 
.dropcap1, .dropcap2, .dropcap3, .dropcap4, 
h1,h2,h3,h4,h5,h6, .slogan_text,
.carousel_title, .milestone_number, .milestone_subject, .process_step_title, .pie_progress, .progress-meter,
.roundabout-title, 
#feature h1, .feature-introduce, 
#footer h3, #copyright {
	font-family: '{$fontface_default_name}';
}

CSS;
			$fontface_css .= $default_code;
		}		
	}
	$fontface_css .= stripslashes(theme_get_option('font','fontface_code'));
	/* google font */
	$gfont_css = '';
	$used_gfont = theme_get_option('font','gfont_used');
	if(!empty($used_gfont)){
		$custom_code = stripslashes(theme_get_option('font','gfont_code'));
		$default = theme_get_option('font','gfont_default');
		if(in_array($default,$used_gfont)){
			$pos = strpos($default, ':');
			$font_weight = '';
			$font_style = '';
			if($pos !== false){
				$font_family = substr($default, 0, $pos);
				$font_variant = substr($default, $pos+1);
				$font_weight = "font-weight: ".str_replace('italic', '', $font_variant).";";
			}else{
				$font_family = $default;
			}
			
			if(strpos($default, 'italic') !== false ){
				$font_style = "font-style: italic;";
			}
			$gfont_css .=  <<<CSS
#site_name, #site_description, 
.kwick_title, .kwick_detail h3, 
#navigation a, 
.portfolio_title, 
.dropcap1, .dropcap2, .dropcap3, .dropcap4, 
h1,h2,h3,h4,h5,h6, .slogan_text,
.carousel_title, .milestone_number, .milestone_subject, .process_step_title, .pie_progress, .progress-meter, 
.roundabout-title,
#feature h1, .feature-introduce, 
#footer h3, #copyright {
	font-family: '{$font_family}';
	{$font_weight}
	{$font_style}
}
CSS;
		}
		$gfont_css .= $custom_code;
	}
	
	/* color settings */
	$color = theme_get_option('color');
	if($color['menu_sub_current']==''){
		$color['menu_sub_current']=$color['menu_sub'];
	}
	if($color['menu_sub_current_background']==''){
		$color['menu_sub_current_background']=$color['menu_sub_background'];
	}
	if($color['page_h1']==''){
		$color['page_h1']=$color['page_header'];
	}
	if($color['page_h2']==''){
		$color['page_h2']=$color['page_header'];
	}
	if($color['page_h3']==''){
		$color['page_h3']=$color['page_header'];
	}
	if($color['page_h4']==''){
		$color['page_h4']=$color['page_header'];
	}
	if($color['page_h5']==''){
		$color['page_h5']=$color['page_header'];
	}
	if($color['page_h6']==''){
		$color['page_h6']=$color['page_header'];
	}

	// menu settings
	$menu_css = '';
	if($color['menu_top_active_background']){
		$menu_css .= <<<CSS
#navigation .menu > li.hover > a,
#navigation .menu > li.hover > a:active,
#navigation .menu > li.hover > a:visited {
CSS;
		$menu_css .= theme_color_fallback('background-color',$color['menu_top_active_background']);
		if($color['menu_top_active']){
			$menu_css .= theme_color_fallback('color',$color['menu_top_active']);
		} else {
			$menu_css .= theme_color_fallback('color',$color['primary']);
		}
		$menu_css .= <<<CSS
}
#navigation .menu > li.hover > a > i {
CSS;
	if($color['menu_top_active']){
		$menu_css .= theme_color_fallback('color',$color['menu_top_active']);
	} else {
		$menu_css .= theme_color_fallback('color',$color['primary']);
	}
	$menu_css .= <<<CSS
}

CSS;

	}else{
		$menu_css .= <<<CSS
#navigation .menu > li.hover > a,
#navigation .menu > li.hover > a:active,
#navigation .menu > li.hover > a:visited,
#navigation .menu > li.hover > a > i {
CSS;
		if($color['menu_top_active']){
			$menu_css .= theme_color_fallback('color',$color['menu_top_active']);
		} else {
			$menu_css .= theme_color_fallback('color',$color['primary']);
		}
		$menu_css .= <<<CSS
}

CSS;
	}
	if(empty($color['menu_top_current_background']) && !empty($color['menu_top_active_background']) ){
		if(empty($color['menu_top_background'])){
			$color['menu_top_current_background'] = 'transparent';
		}else{
			$color['menu_top_current_background'] = $color['menu_top_background'];
		}
	}
	if($color['menu_top_current_background']){
		$menu_css .= <<<CSS
#navigation .menu > li.current-menu-item > a,
#navigation .menu > li.current-menu-item > a:visited,
#navigation .menu > li.current-menu-ancestor > a,
#navigation .menu > li.current-menu-ancestor > a:visited,
#navigation .menu > li.current_page_item > a,
#navigation .menu > li.current_page_item > a:visited,
#navigation .menu > li.current_page_ancestor > a,
#navigation .menu > li.current_page_ancestor > a:visited,
#navigation .menu > li.current_page_parent > a,
#navigation .menu > li.current_page_parent > a:visited,
#navigation .menu > li.current-page-item > a,
#navigation .menu > li.current-page-item > a:visited,
#navigation .menu > li.current-page-ancestor > a,
#navigation .menu > li.current-page-ancestor > a:visited {
CSS;
		$menu_css .= theme_color_fallback('background-color',$color['menu_top_current_background']);
		if($color['menu_top_current']){
			$menu_css .= theme_color_fallback('color',$color['menu_top_current']);
		} else {
			$menu_css .= theme_color_fallback('color',$color['primary']);
		}
		
		$menu_css .= <<<CSS
}
#navigation .menu > li.current-menu-item > a > i,
#navigation .menu > li.current-menu-ancestor > a > i,
#navigation .menu > li.current_page_item > a > i,
#navigation .menu > li.current_page_ancestor > a > i,
#navigation .menu > li.current-page-item > a > i,
#navigation .menu > li.current-page-ancestor > a > i {
CSS;
		if($color['menu_top_current']){
			$menu_css .= theme_color_fallback('color',$color['menu_top_current']);
		} else {
			$menu_css .= theme_color_fallback('color',$color['primary']);
		}
		$menu_css .= <<<CSS
}
CSS;
	}else{
		$menu_css .= <<<CSS
#navigation .menu > li.current-menu-item > a,
#navigation .menu > li.current-menu-item > a:visited,
#navigation .menu > li.current-menu_item > a,
#navigation .menu > li.current-menu_item > a:visited,
#navigation .menu > li.current-menu-ancestor > a,
#navigation .menu > li.current-menu-ancestor > a:visited,
#navigation .menu > li.current_page_item > a,
#navigation .menu > li.current_page_item > a:visited,
#navigation .menu > li.current_page_ancestor > a,
#navigation .menu > li.current_page_ancestor > a:visited,
#navigation .menu > li.current-page-item > a ,
#navigation .menu > li.current-page-item > a:visited,
#navigation .menu > li.current-page-ancestor > a,
#navigation .menu > li.current-page-ancestor > a:visited,
#navigation .menu > li.current-menu-item > a > i,
#navigation .menu > li.current-menu-ancestor > a > i,
#navigation .menu > li.current_page_item > a > i,
#navigation .menu > li.current_page_ancestor > a > i,
#navigation .menu > li.current-page-item > a > i,
#navigation .menu > li.current-page-ancestor > a > i {
CSS;
		if($color['menu_top_current']){
			$menu_css .= theme_color_fallback('color',$color['menu_top_current']);
		} else {
			$menu_css .= theme_color_fallback('color',$color['primary']);
		}
		$menu_css .= <<<CSS
}
CSS;
	}
	$nav_button = theme_get_option('general','nav_button');
	if($nav_button){
		$menu_css .= <<<CSS
#navigation > ul > li {
	height: 60px;
}
#navigation > ul > li > a {
	height:auto;
	line-height: 100%;
	padding: 10px 15px;
	margin: 10px 5px 0 0;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;
}
.rtl #navigation > ul > li > a {
	margin: 10px 0 0 5px;
}
CSS;
	}
	if(theme_get_option('general','nav_arrow')){
		if(!theme_get_option('general','enable_nav_subtitle')){
			$menu_css .= <<<CSS
#navigation > ul > li.has-children > a:after {
	content: ' ';
	display: inline-block;
	width: 0;
	height: 0;
	margin-left: 0.5em;
	border-left: 4px solid transparent;
	border-right: 4px solid transparent;
	border-top: 5px solid;
CSS;
			$menu_css .= theme_color_fallback('border-top-color',$color['menu_top']);
			$menu_css .= <<<CSS
	border-bottom: 2px solid transparent;
}
#navigation > ul > li.has-children.current-menu-item > a:after,
#navigation > ul > li.has-children.current-menu-ancestor > a:after,
#navigation > ul > li.has-children.current-page-item > a:after,
#navigation > ul > li.has-children.current-page-ancestor > a:after,
#navigation > ul > li.has-children.current_page_item > a:after,
#navigation > ul > li.has-children.current_page_ancestor > a:after,
#navigation > ul > li.has-children.current_page_parent > a:after {
CSS;
			if($color['menu_top_current']){
				$menu_css .= theme_color_fallback('border-top-color',$color['menu_top_current']);
			} else {
				$menu_css .= theme_color_fallback('border-top-color',$color['primary']);
			}
			$menu_css .= <<<CSS
}
#navigation > ul > li.has-children.hover > a:after {
CSS;
			if($color['menu_top_active']){
				$menu_css .= theme_color_fallback('border-top-color',$color['menu_top_active']);
			} else {
				$menu_css .= theme_color_fallback('border-top-color',$color['primary']);
			}
			$menu_css .= <<<CSS
}
CSS;
		}
	}
	if(theme_get_option('general','nav_arrow_sub')){
		$menu_css .= <<<CSS
#navigation ul ul .has-children > a:after {
	content: ' ';
	display: inline-block;
	width: 0;
	height: 0;
	float: right;
	margin-top: 6px;
	border-top: 5px solid transparent;
	border-bottom: 5px solid transparent;
	border-left: 5px solid;
CSS;
		$menu_css .= theme_color_fallback('border-left-color',$color['menu_sub']);
		$menu_css .= <<<CSS
}
#navigation ul ul li.has-children.current-menu-item > a:after,
#navigation ul ul li.has-children.current-menu-ancestor > a:after,
#navigation ul ul li.has-children.current-page-item > a:after,
#navigation ul ul li.has-children.current-page-ancestor > a:after
#navigation ul ul li.has-children.current_page_item > a:after,
#navigation ul ul li.has-children.current_page_ancestor > a:after ,
#navigation ul ul li.has-children.current_page_parent > a:after {
CSS;
		$menu_css .= theme_color_fallback('border-left-color',$color['menu_sub_current']);
		$menu_css .= <<<CSS
}
#navigation ul ul li.has-children a:hover:after {
CSS;
		$menu_css .= theme_color_fallback('border-left-color',$color['menu_sub_active']);
		$menu_css .= <<<CSS
}
CSS;
	}

	if(theme_get_option('general','enable_nav_subtitle')){
		$menu_top_padding = (60 - $font['menu_top'] - $font['menu_top_sub'] - 5)/2;
		$menu_top_height = 60 - 2* $menu_top_padding;
		$menu_top_lineheight = $menu_top_padding * 2 + $font['menu_top'];
		$nav_subtitle_align = theme_get_option('general','nav_subtitle_align');
		if($nav_button){
			$relocate_navigation='#navigation { bottom:20px;}';
		} else $relocate_navigation='';
		$menu_css .= <<<CSS
{$relocate_navigation}
#navigation .menu > li > a {
	text-align: center;

	padding-top: {$menu_top_padding}px;
	padding-bottom: {$menu_top_padding}px;

	height: {$menu_top_height}px;
	line-height: 1em;
}
.menu-subtitle {
	font-size: {$font['menu_top_sub']}px;
	margin-top: 5px;
	text-align: {$nav_subtitle_align};
CSS;
		$menu_css .= theme_color_fallback('color',$color['menu_top_subtitle']);
		$menu_css .= <<<CSS
}
#navigation .menu > li.hover > a .menu-subtitle {
CSS;
		$menu_css .= theme_color_fallback('color',$color['menu_top_subtitle_active']);
		$menu_css .= <<<CSS
}
#navigation .menu > li.current-menu-item > a .menu-subtitle,
#navigation .menu > li.current-menu-ancestor > a .menu-subtitle,
#navigation .menu > li.current_page_item > a .menu-subtitle,
#navigation .menu > li.current_page_ancestor > a .menu-subtitle,
#navigation .menu > li.current_page_parent > a .menu-subtitle,
#navigation .menu > li.current-page-item > a .menu-subtitle,
#navigation .menu > li.current-page-ancestor > a .menu-subtitle {
CSS;
		$menu_css .= theme_color_fallback('color',$color['menu_top_subtitle_current']);
		$menu_css .= <<<CSS
}
CSS;
	}

	/* background settings */
	$background = theme_get_option('background');

	$box_layout_border_css = '';
	$box_layout_css ='';
	if (!empty($color['box_bg'])) {
		$box_layout_css = theme_color_fallback('background-color',$color['box_bg']);
	}
	$pattern = theme_get_option('background','box_layout_pattern');
	if(!empty($background['box_image'])){
			$background['box_image'] = theme_get_image_src($background['box_image']);
			$box_layout_css .= <<<CSS
	background-image: url('{$background['box_image']}');
	background-repeat: {$background['box_repeat']};
	background-position: {$background['box_position_x']} {$background['box_position_y']};
	background-attachment: {$background['box_attachment']};
	-webkit-background-size: {$background['box_size']};
	-moz-background-size: {$background['box_size']};
	-o-background-size: {$background['box_size']};
	background-size: {$background['box_size']};
CSS;
		} elseif(!empty($pattern)){
			$pattern_url=THEME_URI.'/images/patterns/'.$pattern.'.png';
			$box_layout_css .= <<<CSS

	background: url('{$pattern_url}') repeat scroll 0 0;
CSS;
		}
	if(theme_get_option('general','enable_box_layout')){
			$box_layout_border_css .= <<<CSS
body.box-layout .body-warp {
	border: 1px solid rgba(0, 0, 0, 0.05);
}
CSS;

		}

	/* blog settings */
	$posts_gap = theme_get_option('blog','posts_gap');
	$blog_left_image_width = theme_get_option('blog', 'left_width');
	$custom_css =  stripslashes(theme_get_option('general','custom_css'));
		
	if(!$color['selection_bg']){
		$color['selection_bg'] = $color['primary'];
	}

	$css = <<<CSS
body {
	font-family: {$font['font_family']};
	line-height: {$font['line_height']}px;
{$box_layout_css}
}
{$box_layout_border_css}
{$fontface_css}
{$gfont_css}
#header .inner {
	height: {$header_height}px;
}
#header {
CSS;
	$css .= theme_color_fallback('background-color',$color['header_bg']);
	$css .= <<<CSS
}
::selection {
CSS;
	$css .= theme_color_fallback('color',$color['selection']);
	$css .= theme_color_fallback('background',$color['selection_bg']);
	$css .= <<<CSS
}
::-moz-selection {
CSS;
	$css .= theme_color_fallback('color',$color['selection']);
	$css .= theme_color_fallback('background',$color['selection_bg']);
	$css .= <<<CSS
}
::-webkit-selection {
CSS;
	$css .= theme_color_fallback('color',$color['selection']);
	$css .= theme_color_fallback('background',$color['selection_bg']);
	$css .= <<<CSS
}
#site_name {
CSS;
	$css .= theme_color_fallback('color',$color['site_name']);
	$css .= <<<CSS
	font-size: {$font['site_name']}px;
}
#site_description {
CSS;
	$css .= theme_color_fallback('color',$color['site_description']);
	$css .= <<<CSS
	font-size: {$font['site_description']}px;
}
#logo, #logo_text {
	bottom: {$logo_bottom}px;
}

CSS;
	$css .= $menu_css;
	$css .= <<<CSS

#navigation .menu > li > a, #navigation .menu > li > a:visited {
	font-size: {$font['menu_top']}px;
CSS;
	$css .= theme_color_fallback('background-color',$color['menu_top_background']);
	$css .= theme_color_fallback('color',$color['menu_top']);
	$css .= <<<CSS
}
#navigation .menu > li > a > i {
CSS;
	$css .= theme_color_fallback('color',$color['menu_top']);
	$css .= <<<CSS
}
#navigation ul li.hover ul li a, #navigation ul ul li a, #navigation ul ul li a:visited {
	font-size: {$font['menu_sub']}px;
CSS;
	$css .= theme_color_fallback('color',$color['menu_sub']);
	$css .= <<<CSS
}
#navigation ul li ul {
CSS;
	$css .= theme_color_fallback('background-color',$color['menu_sub_background']);
	$css .= <<<CSS
}
#navigation .sub-menu .current-menu-item > a,
#navigation .sub-menu .current-menu-item > a:visited,
#navigation .sub-menu .current-menu_item > a,
#navigation .sub-menu .current-menu_item > a:visited,
#navigation .sub-menu .current-menu-ancestor > a,
#navigation .sub-menu .current-menu-ancestor > a:visited,
#navigation .sub-menu .current-page-item > a,
#navigation .sub-menu .current-page-item > a:visited,
#navigation .sub-menu .current-page-ancestor > a,
#navigation .sub-menu .current-page-ancestor > a:visited,
#navigation .sub-menu .current_page_item > a,
#navigation .sub-menu .current_page_item > a:visited,
#navigation .sub-menu .current_page_ancestor > a,
#navigation .sub-menu .current_page_ancestor > a:visited  {
CSS;
	$css .= theme_color_fallback('background-color',$color['menu_sub_current_background']);
	$css .= theme_color_fallback('color',$color['menu_sub_current']);
	$css .= <<<CSS
}
#navigation ul ul li a:hover, #navigation ul ul li a:active,
#navigation ul li.hover ul li a:hover, #navigation ul li.hover ul li a:active {
CSS;
	$css .= theme_color_fallback('color',$color['menu_sub_active'], true);
	$css .= <<<CSS
}
#navigation ul li ul li a:hover, #navigation ul ul li a:hover {
CSS;
	$css .= theme_color_fallback('background-color',$color['menu_sub_hover_background'], true);
	$css .= <<<CSS
}
.nav2select {
	font-size: {$font['nav2select']}px;
}
a:hover {
	text-decoration:{$font['link_underline']};
}
.no-gradient #feature, .has-gradient #feature {
CSS;
	if($color['feature_bg']){
		$css .= theme_color_fallback('background-color',$color['feature_bg']);
	} else {
		$css .= theme_color_fallback('background-color',$color['primary']);
	}
	
	$css .= <<<CSS
}
#feature h1 {
CSS;
	$css .= theme_color_fallback('color',$color['feature_header']);
	$css .= <<<CSS
	font-size: {$font['feature_header']}px;
}
.feature-introduce .meta-icon,
.feature-introduce {
CSS;
	$css .= theme_color_fallback('color',$color['feature_introduce']);
	$css .= <<<CSS
	font-size: {$font['feature_introduce']}px;
}
.feature-introduce a {
CSS;
	$css .= theme_color_fallback('color',$color['feature_introduce']);
	$css .= <<<CSS
}
#page {
CSS;
	$css .= theme_color_fallback('background-color',$color['page_bg']);
	$css .= <<<CSS
	color: {$color['page']};
	font-size: {$font['page']}px;
}
.wp-pagenavi a {
	font-size: {$font['pagenavi']}px;
}
.wp-pagenavi a:hover {
	font-size: {$font['pagenavi_hover']}px;
}
.wp-pagenavi span.current {
	font-size: {$font['pagenavi_current']}px;
}
CSS;
if (!empty($font['pagenavi_icon'])) {
	$css .= <<<CSS
.wp-pagenavi .icon {
	font-size: {$font['pagenavi_icon']}px;
}
CSS;
}

if (!empty($font['pagenavi_icon_hover'])) {
	$css .= <<<CSS
.wp-pagenavi a:hover .icon {
	font-size: {$font['pagenavi_icon_hover']}px;
}
CSS;
}
	$css .= <<<CSS
ul.{$complex_prefix}mini_tabs li.current, ul.{$complex_prefix}mini_tabs li.current a {
CSS;
	if (!empty($color['minitab_current_bg'])) $css .= theme_color_fallback('background-color',$color['minitab_current_bg']);
	else if (!empty($color['minitab_bg'])) $css .= theme_color_fallback('background-color',$color['minitab_bg']);
	else $css .= theme_color_fallback('background-color',$color['page_bg']);
	$css .= <<<CSS
}
.tabs_container .{$complex_prefix}panes {
CSS;
	$css .= theme_color_fallback('background-color',$color['tab_content_bg']);
	$css .= theme_color_fallback('color',$color['tab_content_text']);
	$css .= <<<CSS
}
.divider.top a {
CSS;
	$css .= theme_color_fallback('background-color',$color['page_bg']);
	$css .= <<<CSS
}
#breadcrumbs {
	font-size: {$font['breadcrumbs']}px;
}
#page h1,#page h2,#page h3,#page h4,#page h5,#page h6{
CSS;
	$css .= theme_color_fallback('color',$color['page_header']);
	$css .= <<<CSS
}
#page h1 {
CSS;
	$css .= theme_color_fallback('color',$color['page_h1']);
	$css .= <<<CSS
}
#page h2 {
CSS;
	$css .= theme_color_fallback('color',$color['page_h2']);
	$css .= <<<CSS
}
#page h3 {
CSS;
	$css .= theme_color_fallback('color',$color['page_h3']);
	$css .= <<<CSS
}
#page h4 {
CSS;
	$css .= theme_color_fallback('color',$color['page_h4']);
	$css .= <<<CSS
}
#page h5 {
CSS;
	$css .= theme_color_fallback('color',$color['page_h5']);
	$css .= <<<CSS
}
#page h6 {
CSS;
	$css .= theme_color_fallback('color',$color['page_h6']);
	$css .= <<<CSS
}
#page a, #page a:visited {
CSS;
	$css .= theme_color_fallback('color',$color['page_link']);
	$css .= <<<CSS
}
#page a:hover, #page a:active {
CSS;
	if($color['page_link_active']){
		$css .= theme_color_fallback('color',$color['page_link_active']);
	} else {
		$css .= theme_color_fallback('color',$color['primary']);
	}
	
	$css .= <<<CSS
}
#page h1 a,#page h1 a:visited {
CSS;
	$css .= theme_color_fallback('color',$color['page_h1']);
	$css .= <<<CSS
}
#page h2 a,#page h2 a:visited {
CSS;
	$css .= theme_color_fallback('color',$color['page_h2']);
	$css .= <<<CSS
}
#page h3 a,#page h3 a:visited {
CSS;
	$css .= theme_color_fallback('color',$color['page_h3']);
	$css .= <<<CSS
}
#page h4 a,#page h4 a:visited {
CSS;
	$css .= theme_color_fallback('color',$color['page_h4']);
	$css .= <<<CSS
}
#page h5 a,#page h5 a:visited {
CSS;
	$css .= theme_color_fallback('color',$color['page_h5']);
	$css .= <<<CSS
}
#page h6 a,#page h6 a:visited {
CSS;
	$css .= theme_color_fallback('color',$color['page_h6']);
	$css .= <<<CSS
}
#page h1 a:hover, #page h1 a:active {
CSS;
	if($color['page_h1_link_active']){
		$css .= theme_color_fallback('color',$color['page_h1_link_active']);
	} elseif($color['page_link_active']) {
		$css .= theme_color_fallback('color',$color['page_link_active']);
	} else {
		$css .= theme_color_fallback('color',$color['primary']);
	}
	
	$css .= <<<CSS
}
#page h2 a:hover, #page h2 a:active {
CSS;
	if($color['page_h2_link_active']){
		$css .= theme_color_fallback('color',$color['page_h2_link_active']);
	} elseif($color['page_link_active']) {
		$css .= theme_color_fallback('color',$color['page_link_active']);
	} else {
		$css .= theme_color_fallback('color',$color['primary']);
	}
	
	$css .= <<<CSS
}
#page h3 a:hover, #page h3 a:active {
CSS;
	if($color['page_h3_link_active']){
		$css .= theme_color_fallback('color',$color['page_h3_link_active']);
	} elseif($color['page_link_active']) {
		$css .= theme_color_fallback('color',$color['page_link_active']);
	} else {
		$css .= theme_color_fallback('color',$color['primary']);
	}
	
	$css .= <<<CSS
}
#page h4 a:hover, #page h4 a:active {
CSS;
	if($color['page_h4_link_active']){
		$css .= theme_color_fallback('color',$color['page_h4_link_active']);
	} elseif($color['page_link_active']) {
		$css .= theme_color_fallback('color',$color['page_link_active']);
	} else {
		$css .= theme_color_fallback('color',$color['primary']);
	}
	
	$css .= <<<CSS
}
#page h5 a:hover, #page h5 a:active {
CSS;
	if($color['page_h5_link_active']){
		$css .= theme_color_fallback('color',$color['page_h5_link_active']);
	} elseif($color['page_link_active']) {
		$css .= theme_color_fallback('color',$color['page_link_active']);
	} else {
		$css .= theme_color_fallback('color',$color['primary']);
	}
	
	$css .= <<<CSS
}
#page h6 a:hover, #page h6 a:active {
CSS;
	if($color['page_h6_link_active']){
		$css .= theme_color_fallback('color',$color['page_h6_link_active']);
	} elseif($color['page_link_active']) {
		$css .= theme_color_fallback('color',$color['page_link_active']);
	} else {
		$css .= theme_color_fallback('color',$color['primary']);
	}
	
	$css .= <<<CSS
}
#page .portfolios.sortable header a {
CSS;
	$css .= theme_color_fallback('background-color',$color['portfolio_header_bg']);
	$css .= theme_color_fallback('color',$color['portfolio_header_text']);
	$css .= <<<CSS
}
#page .portfolios.sortable header a.current, #page .portfolios.sortable header a:hover {
CSS;
	if($color['portfolio_header_active_bg']){
		$css .= theme_color_fallback('background-color',$color['portfolio_header_active_bg']);
	} else {
		$css .= theme_color_fallback('background-color',$color['primary']);
	}
	
	$css .= theme_color_fallback('color',$color['portfolio_header_active_text']);
	$css .= <<<CSS
}
.portfolio_more_button .{$complex_prefix}button {
CSS;
	$css .= theme_color_fallback('background-color',$color['portfolio_read_more_bg']);
	$css .= <<<CSS
}
.portfolio_more_button .{$complex_prefix}button span {
CSS;
	$css .= theme_color_fallback('color',$color['portfolio_read_more_text']);
	$css .= <<<CSS
}
.portfolio_more_button .{$complex_prefix}button:hover, .portfolio_more_button .{$complex_prefix}button.hover {
CSS;
	$css .= theme_color_fallback('background-color',$color['portfolio_read_more_active_bg']);
	$css .= <<<CSS
}
.portfolio_more_button .{$complex_prefix}button:hover span, .portfolio_more_button .{$complex_prefix}button.hover span {
CSS;
	$css .= theme_color_fallback('color',$color['portfolio_read_more_active_text']);
	$css .= <<<CSS
}
.left_sidebar #sidebar_content {
CSS;
	$css .= theme_color_fallback('border-right-color',$color['sidebar_border']);
	$css .= <<<CSS
}
.right_sidebar #sidebar_content {
CSS;
	$css .= theme_color_fallback('border-left-color',$color['sidebar_border']);
	$css .= <<<CSS
}
#sidebar .widget a, #sidebar .widget a:visited {
CSS;
	$css .= theme_color_fallback('color',$color['sidebar_link']);
	$css .= <<<CSS
}
#sidebar .widget a:hover, #sidebar .widget a:active {
CSS;
	if($color['sidebar_link_active']){
		$css .= theme_color_fallback('color',$color['sidebar_link_active']);
	} else {
		$css .= theme_color_fallback('color',$color['primary']);
	}
	$css .= <<<CSS
}
#sidebar .widgettitle {
CSS;
	$css .= theme_color_fallback('color',$color['widget_title']);
	$css .= <<<CSS
	font-size: {$font['widget_title']}px;
}
#breadcrumbs {
CSS;
	$css .= theme_color_fallback('color',$color['breadcrumbs']);
	$css .= <<<CSS
}
#breadcrumbs a, #breadcrumbs a:visited {
CSS;
	$css .= theme_color_fallback('color',$color['breadcrumbs_link']);
	$css .= <<<CSS
}
#breadcrumbs a:hover, #breadcrumbs a:active {
CSS;
	if($color['breadcrumbs_active']){
		$css .= theme_color_fallback('color',$color['breadcrumbs_active']);
	} else {
		$css .= theme_color_fallback('color',$color['primary']);
	}
	$css .= <<<CSS
}
.portfolio_title, #page .portfolio_title a, #page .portfolio_title a:visited {
CSS;
	$css .= theme_color_fallback('color',$color['portfolio_title']);
	$css .= <<<CSS
	font-size: {$font['portfolio_title']}px;
}
.portfolio_desc {
	font-size: {$font['portfolio_desc']}px;
}
.masonry_item_title {
CSS;
	if($color['masonry_title_color']){
		$css .= theme_color_fallback('color',$color['masonry_title_color']);
	}
	$css .= <<<CSS
}
.masonry_item_desc {
CSS;
	if($color['masonry_desc_color']){
		$css .= theme_color_fallback('color',$color['masonry_desc_color']);
	}
	$css .= <<<CSS
}
.masonry_item_image_overlay {
CSS;
	$css .= theme_color_fallback('background-color',$color['masonry_overlay_bg_color']);
	$css .= <<<CSS
}
.masonry_item_image_overlay:before {
CSS;
	$css .= theme_color_fallback('color',$color['masonry_overlay_icon_color']);
	$css .= <<<CSS
}
.no-gradient #footer, .has-gradient #footer {
CSS;
	$css .= theme_color_fallback('background-color',$color['footer_bg']);
	$css .= <<<CSS
}
#footer {
CSS;
	$css .= theme_color_fallback('color',$color['footer_text']);
	$css .= <<<CSS
	font-size: {$font['footer_text']}px;
}
#footer .widget a, #footer .widget a:visited{
CSS;
	$css .= theme_color_fallback('color',$color['footer_link']);
	$css .= <<<CSS
}
#footer .widget a:active, #footer .widget a:hover{
CSS;
	if($color['footer_link_active']){
		$css .= theme_color_fallback('color',$color['footer_link_active']);
	} else {
		$css .= theme_color_fallback('color',$color['primary']);
	}
	$css .= <<<CSS
}
#footer h3.widgettitle {
CSS;
	$css .= theme_color_fallback('color',$color['footer_title']);
	$css .= <<<CSS
	font-size: {$font['footer_title']}px;
}
#footer_bottom {
CSS;
	$css .= theme_color_fallback('background-color',$color['sub_footer_bg']);
	if(!$color['sub_footer_gradient']){
		$css .= 'background-image: none;';
	}
	$css .= <<<CSS
}
#copyright {
CSS;
	$css .= theme_color_fallback('color',$color['copyright']);
	$css .= <<<CSS
	font-size: {$font['copyright']}px;
}
#footer_menu a {
	font-size: {$font['footer_menu']}px;
}
#footer_bottom a, #footer_bottom a:visited, #footer_bottom a:visited i {
CSS;
	$css .= theme_color_fallback('color',$color['footer_menu']);
	$css .= <<<CSS
}
#footer_bottom a:hover, #footer_bottom a:active, #footer_bottom a:active i {
CSS;
	if($color['footer_menu_active']){
		$css .= theme_color_fallback('color',$color['footer_menu_active']);
	} else {
		$css .= theme_color_fallback('color',$color['primary']);
	}
	$css .= <<<CSS
}
CSS;
if(!empty($color['fancybox_overlay_bg'])){
		$css .= <<<CSS
.fancybx-overlay {
CSS;
		$css .= theme_color_fallback('background-color',$color['fancybox_overlay_bg']);
		$css .= <<<CSS
}
CSS;
}
	$css .= <<<CSS
.entry_frame, .divider, .divider_line, .commentlist li,.entry .entry_meta,#sidebar .widget li,#sidebar .widget_pages ul ul,#about_the_author .author_content, 
.woocommerce ul.products li.product, .woocommerce-page ul.products li.product,
.product-thumbnail-wrap, .carousel_heading, .masonry_item,
.woocommerce div.product div.images img, .woocommerce-page div.product div.images img, .woocommerce .content div.product div.images img, .woocommerce-page .content div.product div.images img {
CSS;
	$css .= theme_color_fallback('border-color',$color['divider_line']);
	$css .= <<<CSS
}
h1 {
	font-size: {$font['h1']}px;
}
h2 {
	font-size: {$font['h2']}px;
}
h3 {
	font-size: {$font['h3']}px;
}
h4 {
	font-size: {$font['h4']}px;
}
h5 {
	font-size: {$font['h5']}px;
}
h6 {
	font-size: {$font['h6']}px;
}
[class^="icon-"],
[class*=" icon-"] {
CSS;
	$css .= theme_color_fallback('color',$color['iconfont_color']);
	$css .= <<<CSS
}
.icon-border {
CSS;
	$css .= theme_color_fallback('border-color',$color['iconfont_border_color']);
	$css .= theme_color_fallback('background-color',$color['iconfont_bg_color']);
	$css .= <<<CSS
}
.iconfont {
CSS;
	$css .= theme_color_fallback('background-color',$color['iconfont_bg_color']);
	$css .= <<<CSS
}
.iconfont:hover {
CSS;
	if($color['iconfont_active_bg_color']){
		$css .= theme_color_fallback('background-color',$color['iconfont_active_bg_color'], true);
	}
	if($color['iconfont_active_border_color']){
		$css .= theme_color_fallback('border-color',$color['iconfont_active_border_color'], true);
	}
	if($color['iconfont_active_color']){
		$css .= theme_color_fallback('color',$color['iconfont_active_color'], true);
	}
	
	$css .= <<<CSS
}
.nivo-caption {
	font-size: {$font['nivo_caption']}px;
CSS;
	$css .= theme_color_fallback('color',$color['nivo_caption_text']);
	$css .= theme_color_fallback('background',$color['nivo_caption_bg']);
	$css .= <<<CSS
}

.nivo-title {
CSS;
	$css .= theme_color_fallback('color',$color['nivo_caption_text'], true);
	$css .= <<<CSS
}

.unleash-slider-detail {
CSS;
	$css .= theme_color_fallback('background-color',$color['unleash_caption_bg'], true);
	$css .= <<<CSS
}
.unleash-slider-caption, 
.unleash-slider-caption a {
CSS;
	$css .= theme_color_fallback('color',$color['unleash_caption_text'],true);
	$css .= <<<CSS
	font-size: {$font['kwick_title']}px !important;
}
.unleash-slider-desc {
CSS;
	$css .= theme_color_fallback('color',$color['unleash_desc_text'],true);
	$css .= <<<CSS
	font-size: {$font['kwick_desc']}px !important;
}
.roundabout-item .roundabout-caption {
CSS;
	$css .= theme_color_fallback('background-color',$color['roundabout_caption_bg'],true);
	$css .= <<<CSS
}
.roundabout-title, .roundabout-title a {
CSS;
	$css .= theme_color_fallback('color',$color['roundabout_title_text'],true);
	$css .= <<<CSS
	font-size: {$font['roundabout_title']}px !important;
}
.roundabout-desc {
CSS;
	$css .= theme_color_fallback('color',$color['roundabout_desc_text'],true);
	$css .= <<<CSS
	font-size: {$font['roundabout_desc']}px !important;
}
.fotorama--fullscreen, .fullscreen, .fotorama--fullscreen .fotorama__stage, .fotorama--fullscreen .fotorama__nav {
CSS;
	$css .= theme_color_fallback('background-color',$color['fotorama_fullscreen_bg'],true);
	$css .= <<<CSS
}
.fotorama__caption__wrap {
CSS;
	$css .= theme_color_fallback('background-color',$color['fotorama_caption_bg'],true);
	$css .= theme_color_fallback('color',$color['fotorama_caption_text'],true);	
	$css .= <<<CSS
}
.kenburn-bg {
CSS;
	$css .= theme_color_fallback('background-color',$color['kenburner_bg'], true);
	$css .= <<<CSS
}
.ken-desc {
CSS;
	$css .= theme_color_fallback('color',$color['kenburner_desc_text'], true);
	$css .= theme_color_fallback('background-color',$color['kenburner_desc_bg'], true);
	$css .= <<<CSS
}
.ken-wrap .kenburn_thumb_container_bg {
CSS;
	$css .= theme_color_fallback('background-color',$color['kenburner_thumbnail_bg'], true);
	$css .= <<<CSS
}
.fotorama__thumb-border {
CSS;
	$css .= theme_color_fallback('border-color',$color['primary'], true);
	$css .= <<<CSS
}
.entry {
	margin-bottom: {$posts_gap}px;
}
.entry_title {
	font-size: {$font['entry_title']}px;
}
.entry_left .entry_image {
	width: {$blog_left_image_width}px;
}
.entry_frame {
CSS;
	$css .= theme_color_fallback('background-color',$color['blog_frame_bg']);
	if(!empty($color['blog_frame_border_color'])){
		$css .= theme_color_fallback('border-color',$color['blog_frame_border_color']);
	}
	$css .= <<<CSS
}
.entry .entry_meta {
CSS;
	if(!empty($color['blog_divider_color'])){
		$css .= theme_color_fallback('border-color',$color['blog_divider_color']);
	}
	$css .= <<<CSS
}
.read_more_link.{$complex_prefix}button {
CSS;
	$css .= theme_color_fallback('background-color',$color['read_more_bg']);
	$css .= <<<CSS
}
.read_more_link.{$complex_prefix}button span {
CSS;
	$css .= theme_color_fallback('color',$color['read_more_text']);
	$css .= <<<CSS
}
.read_more_link.{$complex_prefix}button:hover, .read_more_link.{$complex_prefix}button.hover {
CSS;
	$css .= theme_color_fallback('background-color',$color['read_more_active_bg']);
	$css .= <<<CSS
}
.read_more_link.{$complex_prefix}button:hover span, .read_more_link.{$complex_prefix}button.hover span {
CSS;
	$css .= theme_color_fallback('color',$color['read_more_active_text']);
	$css .= <<<CSS
}
#page .read_more_wrap a,
#page .read_more_wrap a:visited,
.read_more_wrap a:visited,
.read_more_wrap a {
CSS;
	$css .= theme_color_fallback('color',$color['read_more_text_color']);
	$css .= <<<CSS
}
#page .read_more_wrap a:hover,
.read_more_wrap a:hover,
#page .read_more_wrap a:active,
.read_more_wrap a:active {
CSS;
	$css .= theme_color_fallback('color',$color['read_more_active_text_color']);
	$css .= <<<CSS
}
#page .entry .entry_title a,
#page .entry .entry_title a:visited {
CSS;
	$css .= theme_color_fallback('color',$color['entry_title']);
	$css .= <<<CSS
}
#page .entry .entry_title a:hover,
#page .entry .entry_title a:active {
CSS;
	if($color['entry_title_active']){
		$css .= theme_color_fallback('color',$color['entry_title_active']);
	} else {
		$css .= theme_color_fallback('color',$color['primary']);
	}
	
	$css .= <<<CSS
}
#page .entry_meta .meta-icon {
CSS;
	$css .= theme_color_fallback('color',$color['blog_meta_icon']);
	$css .= <<<CSS
}
#page .entry_meta a, #page .entry_meta a:visited {
CSS;
	$css .= theme_color_fallback('color',$color['blog_meta_link']);
	$css .= <<<CSS
}
#page .entry_meta a:hover, #page .entry_meta a:active {
CSS;
	if($color['blog_meta_link_active']){
		$css .= theme_color_fallback('color',$color['blog_meta_link_active']);
	} else {
		$css .= theme_color_fallback('color',$color['primary']);
	}
	$css .= <<<CSS
}
CSS;
if ($color['excerpt_link_color']){
	$css .= <<<CSS
a.linked_excerpt,
a.linked_excerpt:visited,
#page a.linked_excerpt,
#page a.linked_excerpt:visited {
CSS;
	$css .= theme_color_fallback('color',$color['excerpt_link_color']);
	$css .= <<<CSS
}

CSS;
}
if ($color['excerpt_link_active']){
	$css .= <<<CSS
a.linked_excerpt:hover, 
a.linked_excerpt:active,
#page a.linked_excerpt:hover, 
#page a.linked_excerpt:active {
CSS;
	$css .= theme_color_fallback('color',$color['excerpt_link_active']);
	$css .= <<<CSS
}

CSS;
}
$css .= <<<CSS
#back-to-top.style-square {
CSS;
	$css .= theme_color_fallback('background-color',$color['scroll_to_top_bg']);
	$css .= <<<CSS
}
a:hover#back-to-top.style-square {
CSS;
	$css .= theme_color_fallback('background-color',$color['scroll_to_top_hover']);
	$css .= <<<CSS
}

ul.{$complex_prefix}tabs {
CSS;
	$css .= theme_color_fallback('border-bottom-color',$color['tab_border']);
	$css .= <<<CSS
}
ul.{$complex_prefix}tabs li {
CSS;
	$css .= theme_color_fallback('border-color',$color['tab_border']);
	$css .= theme_color_fallback('background-color', $color['tab_inner']);
	$css .= <<<CSS
}
.tabs_container .{$complex_prefix}panes {
CSS;
	$css .= theme_color_fallback('border-color',$color['tab_border']);
	$css .= <<<CSS
}
ul.{$complex_prefix}tabs li a {
CSS;
	$css .= theme_color_fallback('background-color',$color['tab_bg']);
	$css .= <<<CSS
}
#page ul.{$complex_prefix}tabs li a {
CSS;
	$css .= theme_color_fallback('color',$color['tab_text']);
	$css .= <<<CSS
}
ul.{$complex_prefix}tabs li a.current {
CSS;
	$css .= theme_color_fallback('background-color',$color['tab_current_bg']);
	$css .= theme_color_fallback('border-bottom-color',$color['tab_current_bg']);
	$css .= <<<CSS
}
#page ul.{$complex_prefix}tabs li a.current {
CSS;
	$css .= theme_color_fallback('color',$color['tab_current_text']);
	$css .= <<<CSS
}
ul.{$complex_prefix}mini_tabs li {
CSS;
	$css .= theme_color_fallback('border-color',$color['minitab_border']);
	$css .= theme_color_fallback('background-color', $color['minitab_inner']);
	$css .= <<<CSS
}
.mini_tabs_container .{$complex_prefix}panes {
CSS;
	$css .= theme_color_fallback('border-top-color',$color['minitab_border']);
	$css .= <<<CSS
}
ul.{$complex_prefix}mini_tabs li a {
CSS;
	$css .= theme_color_fallback('background-color',$color['minitab_bg']);
	$css .= <<<CSS
}
#page ul.{$complex_prefix}mini_tabs li a {
CSS;
	$css .= theme_color_fallback('color',$color['minitab_text']);
	$css .= <<<CSS
}
ul.{$complex_prefix}mini_tabs li a.current, ul.{$complex_prefix}mini_tabs a:hover {
CSS;
	if (!empty($color['minitab_current_bg'])) $css .= theme_color_fallback('background-color',$color['minitab_current_bg']);
	else if (!empty($color['minitab_bg'])) $css .= theme_color_fallback('background-color',$color['minitab_bg']);
	else $css .= theme_color_fallback('background-color',$color['page_bg']);
	$css .= <<<CSS
}
#page ul.{$complex_prefix}mini_tabs li a.current, 
ul.{$complex_prefix}mini_tabs li a:hover, 
#page ul.{$complex_prefix}mini_tabs li a:hover,
ul.{$complex_prefix}mini_tabs li a:hover i,
#page ul.{$complex_prefix}mini_tabs li a:hover i {
CSS;
	$css .= theme_color_fallback('color',$color['minitab_current_text']);
	$css .= <<<CSS
}
ul.{$complex_prefix}vertical_tabs li:first-child {
CSS;
	$css .= theme_color_fallback('border-color',$color['verticaltab_border']);
	$css .= <<<CSS
}
ul.{$complex_prefix}vertical_tabs li {
CSS;
	$css .= theme_color_fallback('border-color',$color['verticaltab_border']);
	//$css .= theme_color_fallback('background-color', $color['verticaltab_inner']);
	$css .= <<<CSS
}
.vertical_tabs_container .{$complex_prefix}panes {
CSS;
	$css .= theme_color_fallback('border-top-color',$color['verticaltab_border']);
	$css .= <<<CSS
}
ul.{$complex_prefix}vertical_tabs li a {
CSS;
	$css .= theme_color_fallback('background-color',$color['verticaltab_bg']);
	$css .= <<<CSS
}
#page ul.{$complex_prefix}vertical_tabs li a {
CSS;
	$css .= theme_color_fallback('color',$color['verticaltab_text']);
	$css .= <<<CSS
}
ul.{$complex_prefix}vertical_tabs li a.current, ul.{$complex_prefix}vertical_tabs a:hover  {
CSS;
	if (!empty($color['verticaltab_current_bg'])) $css .= theme_color_fallback('background-color',$color['verticaltab_current_bg']);
	else if (!empty($color['verticaltab_bg'])) $css .= theme_color_fallback('background-color',$color['verticaltab_bg']);
	else $css .= theme_color_fallback('background-color',$color['page_bg']);
	$css .= <<<CSS
}
#page ul.{$complex_prefix}vertical_tabs li a.current,
ul.{$complex_prefix}vertical_tabs li a:hover, 
#page ul.{$complex_prefix}vertical_tabs li a:hover,
ul.{$complex_prefix}vertical_tabs li a:hover i,
#page ul.{$complex_prefix}vertical_tabs li a:hover i {
CSS;
	$css .= theme_color_fallback('color',$color['verticaltab_current_text']);
	$css .= <<<CSS
}
.accordion {
CSS;
	$css .= theme_color_fallback('border-color',$color['accordion_border']);
	$css .= <<<CSS
}
.{$complex_prefix}accordion .{$complex_prefix}tab {
CSS;
	$css .= theme_color_fallback('border-color', $color['accordion_inner']);
	$css .= theme_color_fallback('border-bottom-color',$color['accordion_border']);
	$css .= theme_color_fallback('background-color',$color['accordion_bg']);
	$css .= <<<CSS
}
.{$complex_prefix}accordion .{$complex_prefix}tab, 
.{$complex_prefix}accordion .{$complex_prefix}tab a,
#page .{$complex_prefix}accordion .{$complex_prefix}tab, 
#page .{$complex_prefix}accordion .{$complex_prefix}tab a {
CSS;
	$css .= theme_color_fallback('color',$color['accordion_text']);
	$css .= <<<CSS
}
.{$complex_prefix}accordion .{$complex_prefix}pane {
CSS;
	$css .= theme_color_fallback('border-bottom-color',$color['accordion_border']);
	$css .= <<<CSS
}
.{$complex_prefix}accordion .{$complex_prefix}pane:last-child {
CSS;
	$css .= theme_color_fallback('border-top-color',$color['accordion_border']);
	$css .= <<<CSS
}
.{$complex_prefix}accordion .{$complex_prefix}tab.current {
CSS;
	$css .= theme_color_fallback('background-color',$color['accordion_current_bg']);
	$css .= <<<CSS
}
.{$complex_prefix}accordion .{$complex_prefix}tab.current, 
.{$complex_prefix}accordion .{$complex_prefix}tab.current a,
#page .{$complex_prefix}accordion .{$complex_prefix}tab.current, 
#page .{$complex_prefix}accordion .{$complex_prefix}tab.current a {
CSS;
	$css .= theme_color_fallback('color',$color['accordion_current_text']);
	$css .= <<<CSS
}
.{$complex_prefix}accordion .{$complex_prefix}tab i,
#page .{$complex_prefix}accordion .{$complex_prefix}tab i {
CSS;
	//$css .= theme_color_fallback('color',$color['accordion_current_text'], true);
	$css .= theme_color_fallback('color',$color['accordion_icon']);
	$css .= <<<CSS
}
.{$complex_prefix}accordion .{$complex_prefix}tab.current i,
#page .{$complex_prefix}accordion .{$complex_prefix}tab.current i {
CSS;
	//$css .= theme_color_fallback('color',$color['accordion_current_text'], true);
	$css .= theme_color_fallback('color',$color['accordion_current_icon']);
	$css .= <<<CSS
}
.toggle_title {
CSS;
	if($font['toggle_title']){
		$css .= 'font-size: '.$font['toggle_title'].'px';
	}
	$css .= <<<CSS
}
.toggle_icon {
CSS;
	if($color['toggle_icon_color']){
		$css .= 'opacity: 1;';
		$css .= theme_color_fallback('color',$color['toggle_icon_color']);
	}
	$css .= <<<CSS
}
.{$complex_prefix}button {
CSS;
	if($color['button_primary']){
		$css .= theme_color_fallback('background-color',$color['button_primary']);
	} else {
		$css .= theme_color_fallback('background-color',$color['primary']);
	}
	$css .= <<<CSS
}
.iconbox_icon i {
CSS;
	if($color['iconbox_color']){
		$css .= theme_color_fallback('color',$color['iconbox_color']);
	} else {
		$css .= theme_color_fallback('color',$color['primary']);
	}
	$css .= <<<CSS
}
.milestone_number {
CSS;
	if($color['milestone_number_color']){
		$css .= theme_color_fallback('color',$color['milestone_number_color']);
	} else {
		$css .= theme_color_fallback('color',$color['primary']);
	}
	$css .= <<<CSS
}
.milestone_icon .milestone_number {
CSS;
	if($color['milestone_icon_number_color']){
		$css .= theme_color_fallback('color',$color['milestone_icon_number_color']);
	} else {
		$css .= theme_color_fallback('color',$color['page']);
	}
	$css .= <<<CSS
}
.milestone_subject {
CSS;
	if($color['milestone_subject_color']){
		$css .= theme_color_fallback('color',$color['milestone_subject_color']);
	}
	$css .= <<<CSS
}
.milestone_icon i {
CSS;
	if($color['milestone_icon_color']){
		$css .= theme_color_fallback('color',$color['milestone_icon_color']);
	} else {
		$css .= theme_color_fallback('color',$color['primary']);
	}
	$css .= <<<CSS
}
.carousel_heading {
CSS;
	if($color['carousel_title_color']){
		$css .= theme_color_fallback('color',$color['carousel_title_color']);
	}
	$css .= <<<CSS
}
#page a.carousel_nav_prev, 
#page a.carousel_nav_next,
.carousel_nav_prev, 
.carousel_nav_next {
CSS;
	$css .= theme_color_fallback('color',$color['carousel_nav_color']);
	$css .= <<<CSS
}
#page a.carousel_nav_prev:hover, 
#page a.carousel_nav_next:hover,
.carousel_nav_prev:hover, 
.carousel_nav_next:hover {
CSS;
	$css .= theme_color_fallback('color',$color['carousel_nav_active_color']);
	$css .= <<<CSS
}
.carousel_image_caption {
CSS;
	$css .= theme_color_fallback('color',$color['carousel_caption_text']);
	$css .= <<<CSS
}
.carousel_image_caption {
CSS;
	$css .= theme_color_fallback('background-color',$color['carousel_caption_bg']);
	$css .= <<<CSS
}
.process_steps li:before {
CSS;
	$css .= theme_color_fallback('border-color',$color['process_step_border_color']);
	$css .= <<<CSS
}
.process_step_icon {
CSS;
	$css .= theme_color_fallback('border-color',$color['process_step_border_color']);
	$css .= theme_color_fallback('background-color',$color['process_step_icon_bg_color']);
	$css .= <<<CSS
}
.process_step_icon:hover {
CSS;
	if($color['process_step_active_border_color']){
		$css .= theme_color_fallback('border-color',$color['process_step_active_border_color']);
	} else {
		$css .= theme_color_fallback('border-color',$color['primary']);
	}
	if($color['process_step_icon_active_bg_color']){
		$css .= theme_color_fallback('background-color',$color['process_step_icon_active_bg_color']);
	} else {
		$css .= theme_color_fallback('background-color',$color['primary']);
	}
	$css .= <<<CSS
}
.process_step_icon i {
CSS;
	$css .= theme_color_fallback('color',$color['process_step_icon_color']);
	$css .= <<<CSS
}
.process_step_icon:hover i {
CSS;
	$css .= theme_color_fallback('color',$color['process_step_icon_active_color'],true);
	$css .= <<<CSS
}
.progress {
CSS;
	$css .= theme_color_fallback('background-color',$color['progress_track_color']);
	$css .= <<<CSS
}
.progress-meter {
CSS;
	$css .= theme_color_fallback('color',$color['progress_text_color']);
	if($color['progress_bar_color']){
		$css .= theme_color_fallback('background-color',$color['progress_bar_color']);
	} else {
		$css .= theme_color_fallback('background-color',$color['primary']);
	}
	
	$css .= <<<CSS
}
.pie_progress_icon {
CSS;
	$css .= theme_color_fallback('color',$color['pie_progress_icon_color']);
	$css .= <<<CSS
}
CSS;
	if(!empty($color['testimonials_border_color'])){
		$css .= <<<CSS
.testimonial_content {
CSS;
		$css .= theme_color_fallback('border-color',$color['testimonials_border_color']);
		$css .= <<<CSS
}
.testimonial_content:after {
CSS;
		$css .= 'border-color:'.$color['testimonials_border_color'].' transparent'.' transparent;';
		$css .= <<<CSS
}
CSS;
	}
	if(!empty($color['testimonials_content_bgcolor'])){
		$css .= <<<CSS
.testimonial_content {
CSS;
		$css .= theme_color_fallback('background-color',$color['testimonials_content_bgcolor']);
		$css .= <<<CSS
}
CSS;
	}
	if(!empty($color['testimonials_content_textcolor'])){
		$css .= <<<CSS
.testimonial_content {
CSS;
		$css .= theme_color_fallback('color',$color['testimonials_content_textcolor']);
		$css .= <<<CSS
}
CSS;
	}
	if(!empty($color['testimonials_authorname_color'])){
		$css .= <<<CSS
.testimonial_name {
CSS;
		$css .= theme_color_fallback('color',$color['testimonials_authorname_color']);
		$css .= <<<CSS
}
CSS;
	}
	if(!empty($color['testimonials_meta_color'])){
		$css .= <<<CSS
#page .testimonial_meta a, 
#page .testimonial_meta a:visited,
.testimonial_meta a,
.testimonial_meta {
CSS;
		$css .= theme_color_fallback('color',$color['testimonials_meta_color']);
		$css .= <<<CSS
}
CSS;
	}
	if(!empty($color['testimonials_meta_active_color'])){
		$css .= <<<CSS
#page .testimonial_meta a:hover, 
#page .testimonial_meta a:active,
.testimonial_meta a:hover,
.testimonial_meta a:active{
CSS;
		$css .= theme_color_fallback('color',$color['testimonials_meta_active_color']);
		$css .= <<<CSS
}
CSS;
	}
	if(!empty($color['testimonials_nav_color'])){
		$css .= <<<CSS
#page a.testimonial_previous, 
#page a.testimonial_previous:visited,
#page a.testimonial_next, 
#page a.testimonial_next:visited,
.testimonial_previous,
.testimonial_previous:visited,
.testimonial_next,
.testimonial_next:visited{
CSS;
		$css .= theme_color_fallback('color',$color['testimonials_nav_color']);
		$css .= <<<CSS
}
CSS;
	}
	if(!empty($color['testimonials_nav_active_color'])){
		$css .= <<<CSS
#page a.testimonial_previous:hover, 
#page a.testimonial_previous:active,
#page a.testimonial_next:hover, 
#page a.testimonial_next:active,
.testimonial_previous:hover,
.testimonial_previous:active,
.testimonial_next:hover,
.testimonial_next:active{
CSS;
		$css .= theme_color_fallback('color',$color['testimonials_nav_active_color']);
		$css .= <<<CSS
}
CSS;
	}
	$css .= <<<CSS
#page input[type="text"],
#page input[type="password"],
#page input[type="email"],
#page input[type="file"],
#page input[type="datetime"],
#page input[type="datetime-local"],
#page input[type="date"],
#page input[type="month"],
#page input[type="time"],
#page input[type="week"],
#page input[type="number"],
#page input[type="url"],
#page input[type="search"],
#page input[type="tel"],
#page input[type="color"],
#page textarea {
CSS;
	$css .= theme_color_fallback('color',$color['input_text']);
	$css .= <<<CSS
}
#footer input[type="text"],
#footer input[type="password"],
#footer input[type="email"],
#footer input[type="file"],
#footer input[type="datetime"],
#footer input[type="datetime-local"],
#footer input[type="date"],
#footer input[type="month"],
#footer input[type="time"],
#footer input[type="week"],
#footer input[type="number"],
#footer input[type="url"],
#footer input[type="search"],
#footer input[type="tel"],
#footer input[type="color"],
#footer textarea, 
#footer .text_input, 
#footer .textarea {
CSS;
	$css .= theme_color_fallback('color',$color['footer_text_field_color']);
	$css .= <<<CSS
}

CSS;

$responsive = theme_get_option('advanced','responsive');
if($responsive){
	$nav2select = theme_get_option('advanced','nav2select');
	$nav2select = $nav2select - 1;
	$css .= <<<CSS
@media only screen and (max-width: 767px) {
	.responsive #header .inner {
		height: auto;
	}
}
@media only screen and (max-width: {$nav2select}px) {
	.responsive #logo, .responsive #logo_text {
		position: relative;
		bottom: auto!important;
		margin-top: 20px;
		margin-bottom: 20px;
	}
	.responsive #header .inner {
		height: auto;
	}
	.responsive #navigation > ul {
		display: none;
	}
	.responsive #navigation {
		height: auto;
		right: auto;
		width: auto;
		position: relative;
		bottom: auto;
	}
	.responsive .nav2select {
		width: 100%;
		display: block;
		margin-bottom: 20px;
	}
}

CSS;
	$subfooter_responsive = theme_get_option('advanced','subfooter_responsive');
	$subfooter_responsive = $subfooter_responsive - 1;
	$css .= <<<CSS
@media only screen and (max-width: {$subfooter_responsive}px) {
	/* footer */
	.responsive #copyright {
		float: none;
		padding-top: .3em;
	}
	.responsive #footer_menu:before, #footer_right_area:before {
		position: absolute;
		width: 100%;
		height:1px;
		content: '';
		background: rgba(0, 0, 0, 0.1);
		bottom: 0;
		left: 0;
	}
	.responsive #footer_menu, #footer_right_area {
		float: none;
		position: relative;
		padding-bottom: .2em;
		border-bottom: 1px solid rgba(255, 255, 255, 0.1);
		text-align: left;
	}
	.responsive #footer_right_area .widget {
		margin-bottom: 0;
	}
	.responsive #footer_menu a {
		padding: 0 10px 0 0;
	}
}

CSS;

	$top_area_target = theme_get_option('advanced','top_area_target');
	if($top_area_target >= 320){
		$top_area_target = $top_area_target - 1;
		$css .= <<<CSS

@media only screen and (max-width: {$top_area_target}px) {
	.responsive #top_area {
		display:none;
	}
}
CSS;
	}
} else {
	$html = <<<CSS
html {
	min-width: 1000px;
}

CSS;
	$css = $html.$css;
}
$woosettings=theme_get_option('advanced');

if($woosettings['woocommerce_button_secondary_text_color']){
	$textcolor=$textcolor='color:'.$woosettings['woocommerce_button_secondary_text_color'].';';
}else $textcolor='';

if (!empty($textcolor)) {
	$css .= <<<CSS

.theme_button span.product-action-button,
.button span.product-action-button,
.woocommerce .content input.button, 
.woocommerce #respond input#submit, 
.woocommerce a.button, 
.woocommerce #main input.button, 
.woocommerce #main button.button, 
.woocommerce-page .content input.button, 
.woocommerce-page #respond input#submit, 
.woocommerce-page a.button, 
.woocommerce-page #main input.button,
.woocommerce-page #main button.button,
#page .woocommerce a.button, 
.woocommerce-page #page a.button {
CSS;
	$css .= $textcolor;
	$css .= <<<CSS
}
CSS;
}

if($woosettings['woocommerce_button_secondary_color']){
	$css .= <<<CSS

.theme_button span.product-action-button,
.button span.product-action-button,
.woocommerce .content input.button, 
.woocommerce #respond input#submit, 
.woocommerce a.button, 
.woocommerce #main input.button, 
.woocommerce #main button.button, 
.woocommerce-page .content input.button, 
.woocommerce-page #respond input#submit, 
.woocommerce-page a.button, 
.woocommerce-page #main input.button,
.woocommerce-page #main button.button,
#page .woocommerce a.button, 
.woocommerce-page #page a.button {
CSS;
	$css .= theme_color_fallback('background-color',$woosettings['woocommerce_button_secondary_color']);
	$css .= <<<CSS
}

CSS;
}

if(empty($woosettings['woocommerce_button_secondary_text_hover_color'])) {
	$woosettings['woocommerce_button_secondary_text_hover_color']=$woosettings['woocommerce_button_secondary_text_color'];
}
if(empty($woosettings['woocommerce_button_secondary_hover_color'])) {
	$woosettings['woocommerce_button_secondary_hover_color']=$woosettings['woocommerce_button_secondary_color'];
}

if($woosettings['woocommerce_button_secondary_text_hover_color']){
	$textcolor=$textcolor='color:'.$woosettings['woocommerce_button_secondary_text_hover_color'].';';
}else $textcolor='';
if (!empty($textcolor)) {
	$css .= <<<CSS

.theme_button span.product-action-button:hover,
.button span.product-action-button:hover,
.woocommerce-page #main .button.white:hover,
.woocommerce-page #main .theme_button.white:hover,
.woocommerce .content input.button:hover,
.woocommerce #respond input#submit:hover, 
.woocommerce a.button:hover, 
.woocommerce #main input.button:hover, 
.woocommerce #main button.button:hover,
.woocommerce-page .content input.button:hover, 
.woocommerce-page #respond input#submit:hover, 
.woocommerce-page a.button:hover, 
.woocommerce-page #main input.button:hover,
.woocommerce-page #main button.button:hover,
#page .woocommerce a.button:hover, 
.woocommerce-page #page a.button:hover {
CSS;
	$css .= $textcolor;
	$css .= <<<CSS
}
CSS;
}
if($woosettings['woocommerce_button_secondary_hover_color']){
	$css .= <<<CSS

.theme_button span.product-action-button:hover,
.button span.product-action-button:hover,
.woocommerce-page #main .button.white:hover,
.woocommerce-page #main .theme_button.white:hover,
.woocommerce .content input.button:hover,
.woocommerce #respond input#submit:hover, 
.woocommerce a.button:hover, 
.woocommerce #main input.button:hover, 
.woocommerce #main button.button:hover,
.woocommerce-page .content input.button:hover, 
.woocommerce-page #respond input#submit:hover, 
.woocommerce-page a.button:hover, 
.woocommerce-page #main input.button:hover,
.woocommerce-page #main button.button:hover,
#page .woocommerce a.button:hover, 
.woocommerce-page #page a.button:hover {
CSS;
	$css .= theme_color_fallback('background-color',$woosettings['woocommerce_button_secondary_hover_color']);
	$css .= <<<CSS
}
CSS;
}

if($woosettings['woocommerce_button_text_color']){
	$textcolor='color:'.$woosettings['woocommerce_button_text_color'].';';
}else $textcolor='';

if (!empty($textcolor)) {
	$css .= <<<CSS

.woocommerce button.button, 
.woocommerce-page button.button,
.woocommerce a.button.alt, 
.woocommerce-page a.button.alt, 
.woocommerce button.button.alt, 
.woocommerce-page button.button.alt, 
.woocommerce input.button.alt, 
.woocommerce-page input.button.alt, 
.woocommerce #respond input#submit.alt, 
.woocommerce-page #respond input#submit.alt, 
.woocommerce .content input.button.alt, 
.woocommerce-page .content input.button.alt,
#page .woocommerce a.button.alt, 
.woocommerce-page #page a.button.alt {
CSS;
	$css .= $textcolor;
	$css .= <<<CSS
}
CSS;
}

if(!$woosettings['woocommerce_button_color']){
	$woosettings['woocommerce_button_color'] = $color['primary'];
}
if($woosettings['woocommerce_button_color']){
$css .= <<<CSS

.woocommerce button.button, 
.woocommerce-page button.button,
.woocommerce a.button.alt, 
.woocommerce-page a.button.alt, 
.woocommerce button.button.alt, 
.woocommerce-page button.button.alt, 
.woocommerce input.button.alt, 
.woocommerce-page input.button.alt, 
.woocommerce #respond input#submit.alt, 
.woocommerce-page #respond input#submit.alt, 
.woocommerce .content input.button.alt, 
.woocommerce-page .content input.button.alt,
#page .woocommerce a.button.alt, 
.woocommerce-page #page a.button.alt {
CSS;
	$css .= theme_color_fallback('background-color',$woosettings['woocommerce_button_color']);
	$css .= <<<CSS
}
CSS;
}

if(empty($woosettings['woocommerce_button_text_hover_color'])) {
	$woosettings['woocommerce_button_text_hover_color']=$woosettings['woocommerce_button_text_color'];
}
if(empty($woosettings['woocommerce_button_hover_color'])) {
	$woosettings['woocommerce_button_hover_color']=$woosettings['woocommerce_button_color'];
}

if($woosettings['woocommerce_button_text_hover_color']){
	$textcolor='color:'.$woosettings['woocommerce_button_text_hover_color'].';';
}else $textcolor='';

if (!empty($textcolor)) {
	$css .= <<<CSS

.woocommerce button.button:hover, 
.woocommerce-page button.button:hover,
.woocommerce a.button.alt:hover, 
.woocommerce-page a.button.alt:hover, 
.woocommerce button.button.alt:hover, 
.woocommerce-page button.button.alt:hover, 
.woocommerce input.button.alt:hover, 
.woocommerce-page input.button.alt:hover, 
.woocommerce #respond input#submit.alt:hover, 
.woocommerce-page #respond input#submit.alt:hover, 
.woocommerce .content input.button.alt:hover, 
.woocommerce-page .content input.button.alt:hover,
#page .woocommerce a.button.alt:hover, 
.woocommerce-page #page a.button.alt:hover {
CSS;
	$css .= $textcolor;
	$css .= <<<CSS
}
CSS;
}

if(!$woosettings['woocommerce_button_hover_color']){
	$woosettings['woocommerce_button_hover_color'] = $color['primary'];
}
if($woosettings['woocommerce_button_hover_color']){
$css .= <<<CSS

.woocommerce button.button:hover, 
.woocommerce-page button.button:hover,
.woocommerce a.button.alt:hover, 
.woocommerce-page a.button.alt:hover, 
.woocommerce button.button.alt:hover, 
.woocommerce-page button.button.alt:hover, 
.woocommerce input.button.alt:hover, 
.woocommerce-page input.button.alt:hover, 
.woocommerce #respond input#submit.alt:hover, 
.woocommerce-page #respond input#submit.alt:hover, 
.woocommerce .content input.button.alt:hover, 
.woocommerce-page .content input.button.alt:hover,
#page .woocommerce a.button.alt:hover, 
.woocommerce-page #page a.button.alt:hover {
CSS;
	$css .= theme_color_fallback('background-color',$woosettings['woocommerce_button_hover_color']);
	$css .= <<<CSS
}
CSS;
}
$woocommerce_cross_sell_width = theme_get_option('advanced','woocommerce_cross_sell_width');
if ($woocommerce_cross_sell_width) {
$css .= <<<CSS

@media only screen and (min-width: 768px) {
.woocommerce .cart-collaterals .cart_totals, 
.woocommerce-page .cart-collaterals .cart_totals,
.woocommerce .cart-collaterals .cross-sells, 
.woocommerce-page .cart-collaterals .cross-sells {
	float : none;
	width:100%;
}
}
CSS;
}
$css .= <<<CSS

{$custom_css}

CSS;
$minify=theme_get_option('advanced','theme_minify');
if ($minify) $css=theme_minify_css_js($css);
return $css;
