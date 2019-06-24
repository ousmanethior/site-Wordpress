<?php

if(!function_exists('theme_shortcode_carousel')){
function theme_shortcode_carousel($atts, $content=null){
	global $wp_filter;
	$the_content_filter_backup = $wp_filter['the_content'];

	extract(shortcode_atts(array(
		'title' => false,
		'width' => '200',
		'height' => '150',
		'number' => -1,
		'autoplay'=>'true',
		'infinite' => 'true',
		'circular' => 'false',
		'delay' => '4000',
		'speed' => '1000',
		'direction' => 'right', // top, right, bottom, left
		'nav' => 'false',
		// 'mousewheel' => 'true',
		'touch' => 'true',
		'source' => '',
		'keyboard' => 'false',
		'lightbox' => 'false',
		'effect'=>'icon',//grayscale,icon
		'icon' => false,
		'showcaptions' => 'false',
		'textcolor'=> '',
		'bgcolor'=>'',
		'captionpos'=> 'bottom',
		'captionsize'=>'',
		'captionmargin'=> '',
		'lightboxgroup' => 'false',
		'link_target' => '_self',
		'class' => '',
	), $atts));
	
	wp_enqueue_script( 'carousel-init');
	if ($lightbox=='true' && $lightboxgroup=='true') {
		$lightboxgroup= ' data-fancybx-group="'.md5(serialize("theme_carousel").rand(10, 1000)). '"';
		$circular="false";
	} else $lightboxgroup='';
	$output = '<ul>';
	$images = array();
	if(!empty($source)){
		$images = SlideshowGenerator::get_images($source,$number,'full');
	}

	if (preg_match_all("/(.?)\[(carousel_image)\b(.*?)(?:(\/))?\](?:(.+?)\[\/carousel_image\])?(.?)/s", $content, $matches)) {
		for($i = 0; $i < count($matches[0]); $i++) {
			$options = theme_shortcode_parse_atts($matches[3][$i]);
			if(!isset($options['source_type']) || !isset($options['source_value'])){
				continue;
			}
			$image = array(
				'source' => array(
					'type' => $options['source_type'],
					'value' => $options['source_value'],
				)
			);

			if(isset($options['caption'])){
				$image['alt'] = $options['caption'];
			}
			if(isset($options['link'])){
				$image['link'] = $options['link'];
			}
			$images[] = $image;
		}
	}
	
	if (!in_array($captionpos, array('bottom','below','top','above'))) $captionpos='bottom';
	
	$caption_style='';

	if (!is_numeric($captionsize))$captionsize=preg_replace("/[^0-9]/","",$captionsize);
	if (!empty($captionsize)){
	  $caption_style.= 'font-size:'.$captionsize.'px;';
	}
	if (!empty($textcolor)) {
		$caption_style.= 'color:'. $textcolor.';';
	}
	if (!empty($bgcolor)) {
		$caption_style.= 'background-color:'. $bgcolor.';';
	}
	if ($captionpos=='below' || $captionpos=='above') { 
	   $caption_style.='position:relative;';
	}
	if ($captionpos=='top') { 
	   $caption_style.='bottom:initial;top:0;';
	}
	if (!is_numeric($captionmargin))$captionmargin=preg_replace("/[^0-9]/","",$captionmargin);
	if (!empty($captionmargin) && $captionmargin!=0) {
		if ($captionpos=='above' || $captionpos=='bottom') { 
		$caption_style.='margin-bottom:'.$captionmargin.'px;';
		} else	$caption_style.='margin-top:'.$captionmargin.'px;';
	}
	if (empty($effect)) $effect='icon';
	if ($effect=='icon' || $effect=='none') {
		if ($icon) { 
		  $icon=' image_icon_'.$icon;
		  $effect='';
		}
	} else {
		$icon='';
		$effect=' effect-'.$effect;
	}
	foreach ($images as $image) {
		$image_src = theme_get_image_src($image['source'], array($width, $height));
		if(!$image_src){
			continue;
		}
		$output .= '<li class="carousel-item"><div class="carousel_image_wrapper'.$effect.'">';
		
		$img_title='';
		
		if(isset($image['alt'])){
			$caption = $image['alt'];
			} else {
				$caption = '';
			}
		if(isset($image['title'])){
			$img_title = $image['title'];
			} else {
				$img_title = '';
		}
		if (empty($img_title)) {
			$img_title=$caption;
		} else if (empty($caption)) $caption=$img_title;
		
		$image_caption_after=$image_caption_before='';
		if ($showcaptions=='true' && !empty($caption)) {
			if ($captionpos=='bottom' ||$captionpos=='below') {
				$image_caption_after= '<div class="carousel_image_caption">'. $caption.'</div>';
			} else $image_caption_before= '<div class="carousel_image_caption">'. $caption.'</div>';
		}
		//$srcset=theme_get_retina_srcset( $image_src );
		//if (!empty($srcset)) $srcset= ' src="'.$image_src.'"'.$srcset;
		$data_srcset = theme_get_retina_image($image_src);
		if (!empty($data_srcset)) $data_srcset=' data-srcset="'.$data_srcset.'" ';
		$img = $image_caption_before.'<img class="carousel-image" data-src="'.$image_src.'"'.$data_srcset.' data-lazyload="true" alt="'.$caption.'" title="'.$img_title.'" />'.$image_caption_after;
				
		if($lightbox === 'true'){
			$output .= '<a class="lightbox'.$icon.'" href="'.theme_get_image_src($image['source'], 'full').'" alt="'.$caption.'" title="'.$img_title.'"'.$lightboxgroup.'>'.$img.'</a>';
		}elseif(isset($image['link']) && !empty($image['link'])){
			$output .= '<a class="'.$icon.'" href="'.$image['link'].'" alt="'.$caption.'" title="'.$img_title.'" target="'.$link_target.'">'.$img.'</a>';
		} else {
			$output .= $img;
		}
		$output .= '</div></li>';
	}
	$output .= '</ul>';

	$heading = '';
	$title_html = '';
	$nav_html = '';
	if($title){
		$title_html = '<div class="carousel_title">'.$title.'</div>';
	}
	if($nav === 'true'){
		$nav_html = '<div class="carousel_nav"><a class="carousel_nav_prev" href="#"> </a><a class="carousel_nav_next" href="#"> </a></div>';
	}
	if($title_html || $nav_html){
		$heading = '<div class="carousel_heading">'.$title_html.$nav_html.'</div>';
	}
	$id = md5(serialize($output).rand(10, 1000));

	wp_reset_postdata();
	$wp_filter['the_content'] = $the_content_filter_backup;
	if($circular === 'true') {
		$type = 'circular';
	}else{
		$type = 'basic';
	}
	if($class){
		$class = ' '.$class;
	}
	if ($captionpos=='below'|| $captionpos=='above') $height='auto;'; else $height=$height.'px;';
	$inline_css= <<<CSS
#carousel_{$id} > ul > li {
	width: {$width}px;
	height: {$height}
}
#carousel_{$id} .carousel_image_caption {
  {$caption_style}
}
CSS;
	theme_add_css_to_footer($inline_css);

	return <<<HTML
<div class="carousel_wrap{$class}">{$heading}
<div id="carousel_{$id}" data-autoplay="{$autoplay}" data-infinite="{$infinite}" data-type="{$type}" data-delay="{$delay}" data-speed="{$speed}" data-direction="{$direction}" data-touch="{$touch}" data-keyboard="{$keyboard}" class="carousel">{$output}</div>
</div>
HTML;
}
}
add_shortcode('carousel', 'theme_shortcode_carousel');

if(!function_exists('theme_shortcode_product_carousel')){
function theme_shortcode_product_carousel($atts, $content=null){
	global $wp_filter;
	$the_content_filter_backup = $wp_filter['the_content'];

	extract(shortcode_atts(array(
		'ids' => false,
		'title' => false,
		'width' => '200',
		'height' => '150',
		'number' => -1,
		'autoplay'=>'true',
		'infinite' => 'true',
		'circular' => 'false',
		'lightbox' => 'false',
		'lightboxgroup' => 'false',
		'effect'=>'icon',//grayscale,icon
		'icon' => false,
		'showcaptions' => 'false',
		'textcolor'=> '',
		'bgcolor'=>'',
		'captionpos'=> 'bottom',
		'captionsize'=>'',
		'captionmargin'=> '',
		'delay' => '4000',
		'speed' => '1000',
		'direction' => 'right', // top, right, bottom, left
		'nav' => 'false',
		// 'mousewheel' => 'true',
		'touch' => 'true',
		'post_type' => 'product',
		'taxonomy' => false,
		'terms' => false,
		'keyboard' => 'false',
		'random' => 'false',
		'link_target' => '_self',
		'class' => '',
	), $atts));
	
	if (!in_array($captionpos, array('bottom','below','top','above'))) $captionpos='bottom';
	$caption_style='';
	if (!is_numeric($captionsize))$captionsize=preg_replace("/[^0-9]/","",$captionsize);
	
	if (!empty($captionsize)){
	  $caption_style.= 'font-size:'.$captionsize.'px;';
	}
	if (!empty($textcolor)) {
		$caption_style.= 'color:'. $textcolor.';';
	}
	if (!empty($bgcolor)) {
		$caption_style.= 'background-color:'. $bgcolor.';';
	}
	if ($captionpos=='below' || $captionpos=='above') { 
	   $caption_style.='position:relative;';
	}
	if ($captionpos=='top') { 
	   $caption_style.='bottom:initial;top:0;';
	}
	if (!is_numeric($captionmargin))$captionmargin=preg_replace("/[^0-9]/","",$captionmargin);
	if (!empty($captionmargin) && $captionmargin!=0) {
		if ($captionpos=='above' || $captionpos=='bottom') { 
		$caption_style.='margin-bottom:'.$captionmargin.'px;';
		} else	$caption_style.='margin-top:'.$captionmargin.'px;';
	}
	if (empty($effect)) $effect='icon';
	if ($effect=='icon' || $effect=='none') {
		if ($icon) { 
		  $icon=' image_icon_'.$icon;
		  $effect='';
		}
	} else {
		$icon='';
		$effect=' effect-'.$effect;
	}
	wp_enqueue_script( 'carousel-init');
	if ($lightbox=='true' && $lightboxgroup=='true') {
		$lightboxgroup= ' data-fancybx-group="'.md5(serialize("theme_product_carousel").rand(10, 1000)). '"';
		$circular="false";
	} else $lightboxgroup='';
	$query = array(
		'posts_per_page' => (int)$number,
		'post_type'=>$post_type,
		'showposts' => $number
	);
	if($random === 'true'){
		$query['orderby'] = 'rand';
	}

	if($post_type === 'product'){
		if($post_type === 'product' && class_exists('Woocommerce')){
			//if ( get_option( 'woocommerce_hide_out_of_stock_items' ) == 'yes' ) {
				$query['meta_query'] = array(
					array(
						'key'       => '_stock_status',
						'value'     => 'instock',
						'compare'   => '='
					)
				);
			//}
		}
	}
	if($taxonomy && $terms){
		$query['tax_query'] = array(
			array(
				'taxonomy' => $taxonomy,
				'field' => 'slug',
				'terms' => explode(',',$terms)
			)
		);
	}

	if($ids){
		$query['post__in'] = explode(',', $ids);
	}

	$r = new WP_Query($query);

	$images = array();
	while ( $r->have_posts() ) : $r->the_post();
	$image_id = get_post_thumbnail_id();
	if(!empty($image_id)){
		$image_meta = $image = get_post($image_id);
		if (isset ($image_meta->post_content)) $img_caption=$image_meta->post_content; else $img_caption=get_the_title();
		$image_array = array(
			'source' => array(
				'type'=>'attachment_id',
				'value'=>$image_id
			),
			'post_id'=> get_the_ID(),
			'title' => get_the_title(),
			'alt'=>  get_post_meta( $image_id, '_wp_attachment_image_alt', true),
			'caption'=> $img_caption,
			'desc'  => get_the_excerpt(),
			'link' => get_permalink(),
			'target' => '_self'
		);
		$images[] = $image_array;
	}else {
		if(class_exists( 'Woocommerce' )){
			$image_array = array(
				'source' => array(
				'type'=>'url',
				'value'=>wc_placeholder_img_src()
				),
			'post_id'=> get_the_ID(),
			'title' => get_the_title(),
			'alt'=> get_the_title(),
			'desc'  => get_the_excerpt(),
			'link' => get_permalink(),
			'target' => '_self'
			);
			$images[] = $image_array;
		}
	}
	endwhile;

	$output = '<ul>';
	
	foreach ($images as $image) {
		$image_src = theme_get_image_src($image['source'], array($width, $height));
		if(!$image_src){
			continue;
		}
		$output .= '<li class="carousel-item"><div class="carousel_image_wrapper'.$effect.'">';

		if(isset($image['title'])){
			$img_title = $image['title'];
		} else {
			$img_title = '';
		}

		if(isset($image['caption'])){
			$caption = $image['caption'];
		} else {
			$caption = $image['title'];
		}
		
		if (empty($caption)) $caption=$img_title;

		if(isset($image['alt'])){
			$alt = $image['alt'];
		} else {
			$alt = $image['caption'];
		}
		
		if (empty($alt)) $alt=$caption;
		
		$alt = wp_strip_all_tags($alt);
		$img_title = wp_strip_all_tags($img_title);
		
		$image_caption_after=$image_caption_before='';

		if ($showcaptions=='true' && !empty($caption)) {
			if ($captionpos=='bottom' ||$captionpos=='below') {
				$image_caption_after= '<div class="carousel_image_caption">'. $caption.'</div>';
			} else $image_caption_before= '<div class="carousel_image_caption">'. $caption.'</div>';
		}
		//$srcset=theme_get_retina_srcset( $image_src );
		//if (!empty($srcset)) $srcset= ' src="'.$image_src.'"'.$srcset;
		$data_srcset = theme_get_retina_image($image_src);
		if (!empty($data_srcset)) $data_srcset=' data-srcset="'.$data_srcset.'" ';
		$img = $image_caption_before.'<img class="carousel-image" data-src="'.$image_src.'"'.$data_srcset.' data-lazyload="true" alt="'.$caption.'" title="'.$img_title.'"/>'.$image_caption_after;	

		if($lightbox === 'true'){
			$lightbox_src = wp_get_attachment_image_src($image['source']['value'], 'full');
			if (!$lightbox_src) $lightbox_src=$image_src;
			$output .= '<a class="lightbox'.$icon.'" href="'.$lightbox_src[0].'" alt="'.$alt.'" title="'.$img_title.'"'.$lightboxgroup.'>'.$img.'</a>';
		}elseif(isset($image['link'])){
			$output .= '<a  class="'.$icon.'" href="'.$image['link'].'" title="'.$img_title.'" alt="'.$alt.'" target="'.$link_target.'">'.$img.'</a>';
		} else {
			$output .= $img;
		}
		$output .= '</div></li>';
	}
	$output .= '</ul>';

	$heading = '';
	$title_html = '';
	$nav_html = '';
	if($title){
		$title_html = '<div class="carousel_title">'.$title.'</div>';
	}
	if($nav === 'true'){
		$nav_html = '<div class="carousel_nav"><a class="carousel_nav_prev" href="#"> </a><a class="carousel_nav_next" href="#"> </a></div>';
	}
	if($title_html || $nav_html){
		$heading = '<div class="carousel_heading">'.$title_html.$nav_html.'</div>';
	}
	$id = md5(serialize($output).rand(10, 1000));

	wp_reset_postdata();
	$wp_filter['the_content'] = $the_content_filter_backup;
	if($circular === 'true') {
		$type = 'circular';
	}else{
		$type = 'basic';
	}
	if($class){
		$class = ' '.$class;
	}
	if ($captionpos=='below'|| $captionpos=='above') $height='auto;'; else $height=$height.'px;';
	$inline_css= <<<CSS
#carousel_{$id} > ul > li {
	width: {$width}px;
	height: {$height}
}
#carousel_{$id} .carousel_image_caption {
  {$caption_style}
}
CSS;
	theme_add_css_to_footer($inline_css);

	return <<<HTML
<div class="carousel_wrap{$class}">{$heading}
<div id="carousel_{$id}" data-autoplay="{$autoplay}" data-infinite="{$infinite}" data-type="{$type}" data-delay="{$delay}" data-speed="{$speed}" data-direction="{$direction}" data-touch="{$touch}" data-keyboard="{$keyboard}" class="carousel">{$output}</div>
</div>
HTML;
}
}
add_shortcode('product_carousel', 'theme_shortcode_product_carousel');