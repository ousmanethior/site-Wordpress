<?php

if(!function_exists('theme_shortcode_masonry')){
function theme_shortcode_masonry($atts, $content = null, $code) {
	global $wp_filter;
	$the_content_filter_backup = $wp_filter['the_content'];
	extract(shortcode_atts(array(
		'number' => 10,
		'post_type' => 'post',
		'taxonomy' => false,
		'terms' => false,
		'column' => 3,
		'title' => 'true',
		'desc' => 'false',
		'desc_length' => 'default',
		'paging' => 'true',
		'lightbox' => 'false',
		'lightbox_fittoview' => '',
		'random' => 'false',
		'class' => '',
	), $atts));

	wp_enqueue_script( 'jquery-isotope');


	if($title === 'true'){
		$title = true;
	}else{
		$title = false;
	}
	if($desc === 'true'){
		$desc = true;
	}else{
		$desc = false;
	}

	if($lightbox == 'true'){
		if($lightbox_fittoview != ''){	
			$fittoview = ($lightbox_fittoview == 'false')?' data-fittoview="false"':' data-fittoview="true"';
		}else{
			$fittoview = '';
		}
	}

	$query = array(
		'posts_per_page' => (int)$number,
		'post_type'=>$post_type,
		'showposts' => $number
	);
	if($random === 'true'){
		$query['orderby'] = 'rand';
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

	if($desc_length != 'default'){
		$excerpt_constructor = new Theme_The_Excerpt_Length_Constructor($desc_length);
		add_filter( 'excerpt_length', array($excerpt_constructor,'get_length'));
	}

	if ($paging === 'true') {
		global $wp_version;
		if((is_front_page() || is_home() ) && version_compare($wp_version, "3.1", '>=')){//fix wordpress 3.1 paged query 
			$paged = (get_query_var('paged')) ?get_query_var('paged') : ((get_query_var('page')) ? get_query_var('page') : 1);
		}else{
			$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		}
		$query['paged'] = $paged;
	}

	$r = new WP_Query($query);

	$output = '<ul class="masonry_items">';
	if ($r->have_posts()){
		while ($r->have_posts()){
			$r->the_post();
			$output .= '<li class="masonry_item">';
			$image_source = false;
			$masonry_image = get_post_meta(get_the_ID(), '_masonry_image', true);
			if(is_array($masonry_image) && isset($masonry_image['value'])){
				$image_source = $masonry_image;
			} else if(has_post_thumbnail()){
				$image_source = array('type'=>'attachment_id','value'=>get_post_thumbnail_id());
			}

			$href = get_permalink();

			$link_target = '';
			if($post_type == 'portfolio' && 'link' === get_post_meta(get_the_id(), '_type', true)) {
				$link = get_post_meta(get_the_ID(), '_link', true);
				$href = theme_get_superlink($link);
				$link_target = get_post_meta(get_the_ID(), '_link_target', true);
				if($link_target){
					$link_target = ' target="'.$link_target.'"';
				}
			}
			
			if($image_source){
				$image_src = theme_get_image_src($image_source, array(470));
				if($lightbox == 'true'){
					$output .= '<div class="masonry_item_image"><a class="fancybx"'.$fittoview.' href="'.theme_get_image_src($image_source, 'full').'" title="'.get_the_title().'"><img src="'.$image_src.'" alt="'.get_the_title().'" /><span class="masonry_item_image_overlay"></span></a></div>';
				} else {
					$output .= '<div class="masonry_item_image"><a href="'.$href.'"'.$link_target.' title="'.sprintf( __("Permanent Link to %s", 'striking-r'), get_the_title() ).'"><img src="'.$image_src.'" alt="'.get_the_title().'" /><span class="masonry_item_image_overlay"></span></a></div>';
				}
			}
			if($title){
				$output .= '<h4 class="masonry_item_title"><a href="'.$href.'"'.$link_target.' rel="bookmark" title="'.sprintf( __("Permanent Link to %s", 'striking-r'), get_the_title() ).'">'.get_the_title().'</a></h4>';
			}
			if($desc){
				$output .= '<div class="masonry_item_desc">'.apply_filters('the_excerpt', get_the_excerpt()).'</div>';
			}
			
			$output .= '</li>';
		}
	}
	$output .= '</ul>';
	$id = 'masonry_'.rand(10, 1000);

	if ($paging === 'true') {
		ob_start();
		theme_masonry_pagenavi('', '', $r, $paged);
		$output .= ob_get_clean();
	}

	wp_reset_postdata();
	$wp_filter['the_content'] = $the_content_filter_backup;

	if($desc_length != 'default'){
		remove_filter( 'excerpt_length', array($excerpt_constructor,'get_length'));
	}

	$output .= <<<HTML
<script>
	jQuery(document).ready(function($){
		var container = $('#{$id}').addClass('masonry_isotope');

		container.imagesLoaded(function(){
			container.children('ul').isotope({
				itemSelector: '.masonry_item',
				layoutMode: 'masonry',
				masonry: {
					gutter: 20
				}
			});
		});
	});
</script>
HTML;

	$class = 'masonry_column_'.$column;
	if(!$title && !$desc){
		$class .= ' masonry_only_image';
	} elseif($desc && $title){
		$class .= ' masonry_with_title_desc';
	} elseif($title){
		$class .= ' masonry_with_title';
	} elseif($desc){
		$class .= ' masonry_with_desc';
	}


	return '<div id="'.$id.'" class="masonry_container '.$class.'">'.$output.'</div>';
}
}
add_shortcode('masonry','theme_shortcode_masonry');

if(!function_exists('theme_masonry_pagenavi')){
function theme_masonry_pagenavi($before = '', $after = '', $masonry_query, $paged) {
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
	
	$request = $masonry_query->request;
	$posts_per_page = intval(get_query_var('posts_per_page'));
	global $wp_version;
	if((is_front_page() || is_home() ) && version_compare($wp_version, "3.1", '>=')){//fix wordpress 3.1 paged query 
		$paged = (get_query_var('paged')) ?intval(get_query_var('paged')) : intval(get_query_var('page'));
	}else{
		$paged = intval(get_query_var('paged'));
	}
	
	$numposts = $masonry_query->found_posts;
	$total_pages = intval($masonry_query->max_num_pages);

	/* fix offset issue */
	if(isset($masonry_query->query['offset'])){	
		if($paged == 0){
			$offset = $masonry_query->query['offset'];
		} else {
			$offset = $masonry_query->query['offset'] - $posts_per_page * ($paged-1 );
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

				//Previous
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

				//Smaller Pages
				$larger_pages_array = array();
				if ( $larger_page_multiple )
					for ( $i = $larger_page_multiple; $i <= $total_pages; $i+= $larger_page_multiple )
						$larger_pages_array[] = $i;

				$larger_page_start = 0;
				foreach($larger_pages_array as $larger_page) {
					if ($larger_page < $start_page && $larger_page_start < $larger_page_to_show) {
						$page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($larger_page), $options['page_text']);
						echo '<a href="' . esc_url(get_pagenum_link($larger_page)) . '" class="'. $class_names["page"] .'" title="' . $page_text . '">' . $page_text . '</a>';
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