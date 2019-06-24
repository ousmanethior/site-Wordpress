<?php
if(!function_exists('theme_shortcode_blog')){
function theme_shortcode_blog($atts, $content = null, $code) {
	global $wp_filter;
	$the_content_filter_backup = $wp_filter['the_content'];
	extract(shortcode_atts(array(
		'count' => 3,
		'cat' => '',
		'author' => '',
		'posts' => '',
		'grid'	=> 'true',
		'image' => 'true',
		'imagetype' => 'default',
		'title' => 'default',
		'title_length' => '',
		'title_fontsize' => '',
		'meta' => 'default',
		'desc' => 'default',
		'desc_length' => 'default',
		'full' => 'false',
		'strip_shortcode_excerpt' => 'default',
		'nopaging' => 'true',
		'paged' => '',
		'column' => 1,
		'width' => '',
		'height' => '',
		'offset' => 0,
		'effect' => 'default', //icon,grayscale,zoom,blur,rotate,morph,tilt,none
		'frame' => 'default',
		'frame_bgcolor' => '',
		'frame_bordercolor'=> '',
		'frame_borderthickness' => '1',
		'divider_color' =>'',
		'read_more' => 'default',
		'link_excerpt' => 'default',
		'read_more_text' => '',
		'read_more_button' => 'default',
		'readmorecolor' => '',
		'readmorehovercolor' => '',
		'readmorebuttoncolor' => '',
		'readmorebuttonhovercolor' => '',
		'excerptcolor'=>'',
		'excerpthovercolor'=>'',
		'category__and' =>'',
		'category__not_in' => '',
		'order'=> 'DESC', //ASC, DESC
		'orderby'=> 'date', //none, ID, author, title, name, date, modified, parent, rand, comment_count
	), $atts));
	
	$query = array(
		'posts_per_page' => (int)$count,
		'post_type'=>'post',
	);
	if($paged){
		$query['paged'] = $paged;
	}
	if($cat){
		$query['cat'] = $cat;
	}
	if($category__and){
		$query['category__and'] = explode(',',$category__and);
	}
	if($category__not_in){
		$query['category__not_in'] = explode(',',$category__not_in);
	}
	if($author){
		$query['author'] = $author;
	}
	if($posts){
		$query['post__in'] = explode(',',$posts);
	}
	if($order){
		$query['order'] = $order;
	}
	if($orderby){
		$query['orderby'] = $orderby;
	}
	if ($nopaging == 'false') {
		global $wp_version;
		if((is_front_page() || is_home() ) && version_compare($wp_version, "3.1", '>=')){//fix wordpress 3.1 paged query 
			$paged = (get_query_var('paged')) ?get_query_var('paged') : ((get_query_var('page')) ? get_query_var('page') : 1);
		}else{
			$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		}
		$query['paged'] = $paged;
	} else {
		$query['showposts'] = $count;
	}
	if((int)$offset != 0){
		$query['offset'] = (int)$offset;

		if ($nopaging == 'false') {
			$how_many_posts_past = $query['posts_per_page'] * ($query['paged'] - 1);
			
			$query['offset'] = (int)$offset + (($query['paged'] > 1) ? $how_many_posts_past : 0);
			$query['showposts'] = $count;
		}
	}
	$column = (int)$column;
	if($column > 6){
		$column = 6;
	}elseif($column < 1){
		$column = 1;
	}

	if($image == 'true'){
		if($imagetype == 'default'){
			$featured_image_type = theme_get_option('blog', 'featured_image_type');
		}else{
			$featured_image_type = $imagetype;
		}
		if(empty($width)){
			if($featured_image_type != 'left' && $featured_image_type != 'right'){
				$page_id = theme_get_queried_object_id();
				if(is_front_page()){
					$layout = theme_get_option('homepage', 'layout');
				} elseif(is_blog()) {
					$layout = theme_get_option('blog', 'layout');
				} else {
					$layout = theme_get_inherit_option($page_id, '_layout', 'general','layout');
				}
				$template = get_page_template_slug();
				if($template == 'template_fullwidth.php') {
					$layout = 'full';
				}
				if($column > 1){
					if($layout == 'full'){
						$width = floor((958-38*($column-1))/$column);
					}else{
						$width = floor((628-25*($column-1))/$column);
					}
				} else {
					if($layout == 'full'){
						$width = '960';
					} else {
						$width = '630';
					}
				}
			}
		}  
	}else{
		$featured_image_type = 'full';
	}
	if($title == 'default'){
		$title =  theme_get_option('blog','title');
	}elseif($title == 'true'){
		$title = true;
	}else{
		$title = false;
	}
	if($meta == 'default'){
		$meta =  theme_get_option('blog','meta');
	}elseif($meta == 'true'){
		$meta = true;
	}else{
		$meta = false;
	}
	if($strip_shortcode_excerpt == 'default' || empty($strip_shortcode_excerpt)){
		$strip_shortcode_excerpt =  theme_get_option('blog','strip_shortcode_excerpt');
	}elseif($strip_shortcode_excerpt == 'true'){
		$strip_shortcode_excerpt = true;
	}else{
		$strip_shortcode_excerpt = false;
	}
	if($desc == 'default'){
		$desc =  theme_get_option('blog','desc');
	}elseif($desc == 'true'){
		$desc = true;
	}else{
		$desc = false;
	}
	if($frame == 'default'){
		$frame =  theme_get_option('blog','frame');
	}elseif($frame == 'true'){
		$frame = true;
	}else{
		$frame = false;
	}
	if($link_excerpt == 'default'){
		$link_excerpt =  theme_get_option('blog','link_excerpt');
	}elseif($link_excerpt == 'true'){
		$link_excerpt = true;
	}else{
		$link_excerpt = false;
	}
	if($read_more == 'default'){
		$read_more =  theme_get_option('blog','read_more');
	}elseif($read_more == 'true'){
		$read_more = true;
	}else{
		$read_more = false;
	}	
	if($read_more_button == 'default'){
		$read_more_button =  theme_get_option('blog','read_more_button');
	}elseif($read_more_button == 'true'){
		$read_more_button = true;
	}else{
		$read_more_button = false;
	}

	if($effect == 'default'){
		$effect = theme_get_option('blog','effect');
	}
	$r = new WP_Query($query);
	$column = (int)$column;
	if($column > 6){
		$column = 6;
	}elseif($column < 1){
		$column = 1;
	}
	$posts_per_column = ceil($query['posts_per_page']/$column);
	
	$atts = array(
		'featured_image_type' => $featured_image_type,
		'posts_per_column' => $posts_per_column,
		'posts_per_page' => (int)$count,
		'desc' => $desc,
		'full' => $full,
		'strip_shortcode_excerpt'=>$strip_shortcode_excerpt,
		'title' => $title,
		'title_length' => $title_length,
		'title_fontsize' => $title_fontsize,
		'meta' => $meta,
		'width' => $width,
		'height' => $height,
		'image' => $image,
		'column' => $column,
		'grid'	=> $grid,
		'frame' => $frame,
		'divider_color' => $divider_color,
		'frame_bgcolor' => $frame_bgcolor,
		'frame_bordercolor' => $frame_bordercolor,
		'frame_borderthickness' => $frame_borderthickness,
		'read_more'=>$read_more,
		'link_excerpt'=>$link_excerpt,
		'read_more_text'=>$read_more_text,
		'read_more_button' =>$read_more_button,
		'effect' => $effect,
	);
	
	if($desc_length != 'default'){
		$excerpt_constructor = new Theme_The_Excerpt_Length_Constructor($desc_length);
		add_filter( 'excerpt_length', array($excerpt_constructor,'get_length'));
	}
	
	$column = ($grid == 'true') ? 1 : $column;
	
	$output = '';
	
	if($column != 1){
		$colclass = array('half','third','fourth','fifth','sixth');
		$css = $colclass[$column-2];
		
		for($i=1; $i<=$column; $i++){
			if ($i%$column !== 0) {
				$output .= "<div class=\"one_{$css}\">".theme_shortcode_blog_column_posts($r,$atts,$i)."</div>";
			} else {
				$output .= "<div class=\"one_{$css} last\">".theme_shortcode_blog_column_posts($r,$atts,$i)."</div>";
			}
		}
	}else{
		$output .= theme_shortcode_blog_column_posts($r,$atts,1);
	}
	
	if($desc_length != 'default'){
		remove_filter( 'excerpt_length', array($excerpt_constructor,'get_length'));
	}
	
	if ($nopaging == 'false') {
		ob_start();
		theme_blog_pagenavi('', '', $r, $paged);
		$output .= ob_get_clean();
	}

	if($atts['column'] > 1){
		$clearboth='<div class="clearboth"></div>';
	} else $clearboth='';

	wp_reset_postdata();
	$wp_filter['the_content'] = $the_content_filter_backup;
	$blog_id = 'blog_'.rand(10, 1000);
		
	if($title_fontsize|| $readmorebuttonhovercolor || $readmorehovercolor || $readmorecolor || $readmorebuttoncolor || $excerptcolor || $excerpthovercolor) {
		$styles = <<<STYLES
<style type="text/css">
STYLES;
if ($title_fontsize) {
	$styles .= <<<STYLES
#{$blog_id} .entry_title {
	font-size: {$title_fontsize}px;
}
STYLES;
}
if ($readmorecolor) {
	$styles .= <<<STYLES

#{$blog_id} .read_more_link.theme_button span,
#{$blog_id} .read_more_link.button span,
#page #{$blog_id} .read_more_wrap a,
#page #{$blog_id}.read_more_wrap a:visited,
#{$blog_id} .read_more_wrap a, 
#{$blog_id} .read_more_wrap a:visited {
    color: {$readmorecolor};
}
STYLES;
}
if ($readmorehovercolor) {
	$styles .= <<<STYLES

#{$blog_id} .read_more_link.theme_button.hover span,
#{$blog_id} .read_more_link.theme_button:hover span,
#{$blog_id} .read_more_link.button.hover span,
#{$blog_id} .read_more_link.button:hover span,
#page #{$blog_id} .read_more_wrap a:active,
#page #{$blog_id} .read_more_wrap a:hover,
#{$blog_id} .read_more_wrap a:hover, 
#{$blog_id} .read_more_wrap a:active {
    color: {$readmorehovercolor};
}
STYLES;
}
if ($readmorebuttoncolor && $read_more_button) {
	$styles .= <<<STYLES

#{$blog_id} .read_more_link.theme_button,
#{$blog_id} .read_more_link.button {
    background-color: {$readmorebuttoncolor};
}
STYLES;
}
if ($readmorebuttonhovercolor && $read_more_button) {
	$styles .= <<<STYLES
#{$blog_id} .read_more_link.theme_button:hover,
#{$blog_id} .read_more_link.theme_button.hover,
#{$blog_id} .read_more_link.button:hover,
#{$blog_id} .read_more_link.button.hover {
    background-color: {$readmorebuttonhovercolor};
}
STYLES;
}

if ($excerptcolor && $link_excerpt) {
	$styles .= <<<STYLES
#{$blog_id} a.linked_excerpt,
#{$blog_id} a.linked_excerpt:visited,
#page #{$blog_id} a.linked_excerpt,
#page #{$blog_id} a.linked_excerpt:visited {
    color: {$excerptcolor};
}
STYLES;
}

if ($excerpthovercolor && $link_excerpt) {
	$styles .= <<<STYLES
#{$blog_id} a.linked_excerpt:hover,
#{$blog_id} a.linked_excerpt:active,
#page #{$blog_id} a.linked_excerpt:hover,
#page #{$blog_id} a.linked_excerpt:active {
    color: {$excerpthovercolor};
}
STYLES;
}

$styles .= <<<STYLES
</style>
STYLES;
	} else {
		$styles = '';
	}
	return '<div id="'.$blog_id.'">'.$output.'</div>'.$clearboth.$styles;
}
}
add_shortcode('blog','theme_shortcode_blog');

if(!function_exists('theme_shortcode_blog_column_posts')){
function theme_shortcode_blog_column_posts(&$r, $atts, $current) {
	extract($atts);
	if($read_more_text == ''){
		$read_more_text = wpml_t(THEME_NAME, 'Blog Post Read More Button Text',stripslashes(theme_get_option('blog','read_more_text')));
	}
	if ($grid == 'true') {
		$class = array('','half','third','fourth','fifth','sixth');
		$css = $class[$column-1];
	} else {
		$start = ($current-1) * $posts_per_column +1;
		$end = $current * $posts_per_column;
		if( $r->post_count < $start){
			return '';
		}
	}

	if($frame){
		$frame_css = ' entry_frame';
	}else{
		$frame_css = '';
	}
	
	$between = false;
	if ($featured_image_type=='between') {
		$featured_image_type='below';
		$between=true;
	}
	
	$output = '';
	$meta_icons =  theme_get_option('blog','meta_info_icons');
	$allowed_tags= array(
		'p'=> array(),
		'br' => array(),
		'em' => array(),
		'strong' => array(),
	);
		
	$i = 0;
	if ($r->have_posts()):
		while ($r->have_posts()) : 
			$i++;
			
			if ($grid == 'false') {
				if($i < $start) continue;
				if($i > $end) break;
			}
						
			$r->the_post();
			
			if ($grid == 'true' && $column != 1) {
				if ($i%$column !== 0) {
					$output .= "<div class=\"one_{$css}\">";
				} else {
					$output .= "<div class=\"one_{$css} last\">";
				}
			}
			$frame_styles = array();

			if($frame && $frame_bgcolor){
				$frame_styles[]='background-color:'.$frame_bgcolor;
			}
			if($frame && $frame_bordercolor){
				$frame_styles[]='border-color:'.$frame_bordercolor;
			}
			if($frame && $frame_borderthickness!='1'){
				$frame_styles[]='border-width:'.$frame_borderthickness.'px';
				$frame_borderthickness=intval($frame_borderthickness);
				$frame = 30 + 2*$frame_borderthickness;
			}
			if(!empty($frame_styles)){
				$style = ' style="'.implode(';', $frame_styles).'"';
			} else{
				$style = '';
			}
			

			if($divider_color){
				$divider_style = ' style="border-color:'.$divider_color.'"';
			}else{
				$divider_style = '';
			}
			

			$output .= '<article id="post-'.get_the_ID().'" class="hentry entry entry_'.$featured_image_type.$frame_css.'"'.$style.'>';
			if($image == 'true' && $featured_image_type!=='below'){
				$output .= theme_generator('blog_featured_image',$featured_image_type,$width,$height,$frame,$effect);
			}
			if($title === true || $meta === true){
				$output .= '<div class="entry_info">';
				if($title === true){
					$post_title = get_the_title();
					if($title_length){
						$post_title  = theme_strcut( $post_title, $title_length, '...' );
					}
					$output .= '<h2 class="entry_title entry-title"><a href="'.get_permalink().'" rel="bookmark" title="'.sprintf( __("Permanent Link to %s", 'striking-r'), get_the_title() ).'">'.$post_title.'</a></h2>';
				}
				if($meta === true && $between===false){
					$output .= '<div class="entry_meta"'.$divider_style.'>';
					$output .= theme_generator('blog_meta','',$meta_icons);
					$output .= '</div>';
				}
				$output .= '</div>';
			}
			if($image == 'true' && $featured_image_type=='below'){
				$output .= theme_generator('blog_featured_image',$featured_image_type,$width,$height,$frame,$effect);
			}
			if($meta === true && $between===true){
					$output .= '<div class="entry_info">';
					$output .= '<div class="entry_meta"'.$divider_style.'>';
					$output .= theme_generator('blog_meta','',$meta_icons);
					$output .= '</div>';
					$output .= '</div>';
			}
			if($desc === true){
				$output .= '<div class="entry_content entry-content">';

				if($full == 'true'){
					global $more;
					$more = 0;
					$content = get_the_content($read_more_text,false);
					$content = apply_filters('the_content', $content);
					$content = str_replace(']]>', ']]&gt;', $content);
					$content = apply_filters('theme_blog_shortcode_content',$content,get_the_ID());
					$output .= $content;
				}else{
					if ($strip_shortcode_excerpt) $content = apply_filters('the_excerpt', strip_shortcodes(get_the_excerpt()));
					else $content = apply_filters('the_excerpt', get_the_excerpt());

					if ($link_excerpt && !$read_more) {
						$content=wp_kses($content,$allowed_tags);
						$content = '<a class="linked_excerpt" href="'. get_permalink() .'" rel="bookmark" title="'.sprintf( __("Permanent Link to %s", 'striking-r'), get_the_title() ).'">'.$content.'</a>';
					}
					
					$content = apply_filters('theme_blog_shortcode_excerpt',$content,get_the_ID());
					$output .= '<div>'.$content.'</div>';
					if($read_more){
						$output .= '<div class="read_more_wrap">';
						if($read_more_button){
							$output .= '<a class="read_more_link '.apply_filters( 'theme_css_class', 'button' ).' small" href="'.get_permalink().'" rel="nofollow"><span>'.$read_more_text.'</span></a>';
						}else{
							$output .= '<a class="read_more_link" href="'.get_permalink().'" rel="nofollow">'.$read_more_text.'</a>';
						}
						$output .= '</div>';
					}
				}
				$output .= '</div>';
			} else {
				if($read_more){
					$output .= '<div class="read_more_wrap">';
					if($read_more_button){
						$output .= '<a class="read_more_link '.apply_filters( 'theme_css_class', 'button' ).' small" href="'.get_permalink().'" rel="nofollow"><span>'.$read_more_text.'</span></a>';
					}else{
						$output .= '<a class="read_more_link" href="'.get_permalink().'" rel="nofollow">'.$read_more_text.'</a>';
					}
					$output .= '</div>';
				}
			}
			
			$output .= '</article>';
			
			if ($grid == 'true' && $column != 1) {
				$output .= '</div>';
				if ($i%$column === 0) {
					$output .= "<div class=\"clearboth\"></div>";
				}
			}
		endwhile;
	endif;
		
	return $output;
}
}

if(!function_exists('theme_blog_pagenavi')){
function theme_blog_pagenavi($before = '', $after = '', $blog_query, $paged) {
	global $wpdb, $wp_query;
	
	// if (is_single())
	// 	return;

	$options = array(
		//'pages_text' => __('Page %CURRENT_PAGE% of %TOTAL_PAGES%','striking-r'),
		'pages_text' => '',
		'current_text' => '%PAGE_NUMBER%',
		'page_text' => '%PAGE_NUMBER%',
		'first_text'=> __('<i class="icon icon-angle-double-left"></i>','striking-r'),
		'last_text'=> __('<i class="icon icon-angle-double-right"></i>','striking-r'),
		'next_text'=> __('<i class="icon icon-angle-right"></i>','striking-r'),
		'prev_text'=> __('<i class="icon icon-angle-left"></i>','striking-r'),
		'first_text_title'=> __('&laquo; First Page','striking-r'),
		'last_text_title'=> __('Last Page &raquo;','striking-r'),
		'next_text_title'=>__('&raquo; Next Page','striking-r'),
		'prev_text_title'=>__('&laquo; Previous Page','striking-r'),
		'dotright_text' => __('...','striking-r'),
		'dotleft_text' => __('...','striking-r'),
		'style' => 1,
		'num_pages' => 4,
		'always_show' => 0,
		'num_larger_page_numbers' => 3,
		'larger_page_numbers_multiple' => 10,
		'use_pagenavi_css' => 0,
	);
	
	
	$request = $blog_query->request;
	$posts_per_page = intval($blog_query->query_vars['posts_per_page']);
	global $wp_version;
	if((is_front_page() || is_home() ) && version_compare($wp_version, "3.1", '>=')){//fix wordpress 3.1 paged query 
		$paged = (get_query_var('paged')) ?intval(get_query_var('paged')) : intval(get_query_var('page'));
	}else{
		$paged = intval(get_query_var('paged'));
	}
	
	$numposts = $blog_query->found_posts;


	$total_pages = intval($blog_query->max_num_pages);

	/* fix offset issue */
	if(isset($blog_query->query['offset'])){	
		if($paged == 0){
			$offset = $blog_query->query['offset'];
		} else {
			$offset = $blog_query->query['offset'] - $posts_per_page * ($paged-1 );
		}
		$numposts = $numposts - $offset;	
		$total_pages = $total_pages - ceil($offset/$posts_per_page);		
	}
	/* end fix */
	if (empty($paged) || $paged == 0)
		$paged = 1;

	$pages_to_show = absint( $options['num_pages'] );
	$larger_page_to_show = absint( $options['num_larger_page_numbers'] );
	$larger_page_multiple = absint( $options['larger_page_numbers_multiple'] );
	$pages_to_show_minus_1 = $pages_to_show - 1;
	$half_page_start = floor( $pages_to_show_minus_1/2 );
	$half_page_end = ceil( $pages_to_show_minus_1/2 );
	$start_page = $paged - $half_page_start;
	
	if ($start_page <= 0)
		$start_page = 1;
	
	$end_page = $paged + $half_page_end;
	if (($end_page - $start_page) != $pages_to_show_minus_1) {
		$end_page = $start_page + $pages_to_show_minus_1;
	}
	
	if ($end_page > $total_pages) {
		$start_page = $total_pages - $pages_to_show_minus_1;
		$end_page = $total_pages;
	}
	
	if ($start_page < 1)
		$start_page = 1;
	
	$class_names = array(
		'pages' => apply_filters( 'wp_pagenavi_class_pages', 'pages'),
		'first' => apply_filters( 'wp_pagenavi_class_first', 'first' ),
		'previouspostslink' => apply_filters( 'wp_pagenavi_class_previouspostslink', 'previouspostslink' ),
		'extend' => apply_filters( 'wp_pagenavi_class_extend', 'extend' ),
		'smaller' => apply_filters( 'wp_pagenavi_class_smaller', 'smaller' ),
		'page' => apply_filters( 'wp_pagenavi_class_page', 'page' ),
		'current' => apply_filters( 'wp_pagenavi_class_current', 'current'),
		'larger' => apply_filters( 'wp_pagenavi_class_larger', 'larger' ),
		'nextpostslink' => apply_filters( 'wp_pagenavi_class_nextpostslink', 'nextpostslink'),
		'last' => apply_filters( 'wp_pagenavi_class_last', 'last'),
	);
	
	if ($total_pages > 1 || intval($options['always_show'])) {
		$pages_text = str_replace("%CURRENT_PAGE%", number_format_i18n($paged), $options['pages_text']);
		$pages_text = str_replace("%TOTAL_PAGES%", number_format_i18n($total_pages), $pages_text);
		echo $before . '<div class="wp-pagenavi">' . "\n";
		switch(intval($options['style'])){
			// Normal
			case 1:
				if ( !empty( $options['pages_text'] ) ) {
					$pages_text = str_replace(
						array( "%CURRENT_PAGE%", "%TOTAL_PAGES%" ),
						array( number_format_i18n( $paged ), number_format_i18n( $total_pages ) ),
						$options['pages_text']);
					echo '<span class="'. $class_names["pages"].'">'. $pages_text. '</span>';
				}
				if ($start_page >= 2 && $pages_to_show < $total_pages) {
					$first_page_text = str_replace( '%TOTAL_PAGES%', number_format_i18n( $total_pages ), $options['first_text'] );
					echo '<a href="' . esc_url(get_pagenum_link()) . '" class="'.$class_names['first'].'" title="' . $options['first_text_title'] . '">' . $first_page_text . '</a>';
				}

				// Previous
				if ( $paged > 1 ) {
					$prevpage = intval($paged) - 1;
					if ( $prevpage < 1 ){
						$prevpage = 1;
					}
					echo '<a class="previouspostslink" rel="prev" href="' . esc_url(get_pagenum_link($prevpage)) . '" title="' . $options['prev_text_title'] . '">'.$options['prev_text'].'</a>';
				}

				if ( $start_page >= 2 && $pages_to_show < $total_pages ) {
					if ( !empty( $options['dotleft_text'] ) )
						echo '<span class="'. $class_names["extend"].'">'.$options["dotleft_text"].'</span>';
				}
				
				// Smaller pages
				$larger_pages_array = array();
				if ( $larger_page_multiple )
					for ( $i = $larger_page_multiple; $i <= $total_pages; $i+= $larger_page_multiple )
						$larger_pages_array[] = $i;

				$larger_page_start = 0;
				foreach($larger_pages_array as $larger_page) {
					if ($larger_page < $start_page && $larger_page_start < $larger_page_to_show) {
						$page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($larger_page), $options['page_text']);
						echo '<a href="' . esc_url(get_pagenum_link($larger_page)) . '" class="'.$class_names["page"].'" title="' . $page_text . '">' . $page_text . '</a>';
						$larger_page_start++;
					}
				}
				
				if ( $larger_page_start && ((absint($page_text)+1)<$start_page) ) {
					echo '<span class="'.$class_names["extend"].'">'.$options["dotleft_text"].'</span>';
				}
				
				// Page numbers
				$timeline = 'smaller';

				foreach ( range( $start_page, $end_page ) as $i ) {
					if ( $i == $paged && !empty( $options['current_text'] ) ) {
						$current_page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $options['current_text']);
						echo '<span class="'.$class_names["current"].'">' . $current_page_text . '</span>';
						$timeline = 'larger';
					} else {
						$page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $options['page_text']);
						echo '<a href="' . esc_url(get_pagenum_link($i)) . '" class="'.$class_names["page"] .' '. $class_names[$timeline].'" title="' . $page_text . '">' . $page_text . '</a>';
					}
				}

				// Large pages
				$larger_page_end = 0;
				$larger_page_out = '';
				foreach($larger_pages_array as $larger_page) {
					if ($larger_page > $end_page && $larger_page_end < $larger_page_to_show) {
						$larger_page_out = str_replace("%PAGE_NUMBER%", number_format_i18n($larger_page), $options['page_text']);
						$larger_page_out = '<a href="' . esc_url(get_pagenum_link($larger_page)) . '" class="'. $class_names["larger"] .' '. $class_names["page"].'" title="' . $larger_page_out . '">' . $larger_page_out . '</a>';
						$larger_page_end++;
					}
				}
				
				if ( $larger_page_out  && (($end_page+1) < $larger_page )) {
					echo  '<span class="'. $class_names["extend"].'">'.$options["dotright_text"].'</span>';
				}
				echo $larger_page_out;

				if ($end_page < $total_pages) {
					if (! empty($options['dotright_text'])) {
						echo '<span class="'.$class_names['extend'].'">' . $options['dotright_text'] . '</span>';
					}
				}

				//Next Page
				$nextpage = intval($paged) + 1;
				if ( $nextpage <= $total_pages ) {
					echo '<a class="nextpostslink" rel="next" href="' . esc_url(get_pagenum_link($nextpage)) . '" title="' . $options['next_text_title'] . '">'.$options['next_text'].'</a>';
				}

				if ( $end_page < $total_pages ) {
					$last_page_text = str_replace("%TOTAL_PAGES%", number_format_i18n($total_pages), $options['last_text']);
					echo '<a href="' . esc_url(get_pagenum_link($total_pages)) . '" class="'. $class_names['last'].'" title="' .$options['last_text_title'] . '">' . $last_page_text . '</a>';
				}
				break;
			// Dropdown
			case 2:
				echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '" method="get">' . "\n";
				echo '<select size="1" onchange="document.location.href = this.options[this.selectedIndex].value;">' . "\n";
				for($i = 1; $i <= $total_pages; $i++) {
					$page_num = $i;
					if ($page_num == 1) {
						$page_num = 0;
					}
					if ($i == $paged) {
						$current_page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $options['current_text']);
						echo '<option value="' . esc_url(get_pagenum_link($page_num)) . '" selected="selected" class="'.$class_names["current"].'">' . $current_page_text . "</option>\n";
					} else {
						$page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $options['page_text']);
						echo '<option value="' . esc_url(get_pagenum_link($page_num)) . '">' . $page_text . "</option>\n";
					}
				}
				echo "</select>\n";
				echo "</form>\n";
				break;
		}
		echo '</div>' . $after . "\n";
	}
}
}
