<?php
if(!function_exists('theme_section_blog_meta')){
/**
 * The default template for displaying blog meta in the pages
 */
function theme_section_blog_meta($single = false, $metaicons=true){
	global $post;
	if(get_post_type(get_the_ID())=='page'){
		return '';
	}
	$output = '';
	if($single){
		$meta_items = theme_get_option('blog','single_meta_items');
	}else{
		$meta_items = theme_get_option('blog','meta_items');
	}

	if(!empty($meta_items)){
		if (is_rtl()) $meta_items=array_reverse($meta_items);
		$outputs = array();
		foreach($meta_items as $item){
			switch($item){
				case 'category':
					if (is_rtl()) $list_separator=' ,'; else $list_separator=', ';
					$content = get_the_category_list($list_separator);
					if(!empty($content)){
						if ($metaicons) {
							$the_icon='icon-folder';
							$the_icon= apply_filters('theme_meta_category_icon',$the_icon);
							$meta_icon_before=$meta_icon_after='';
							$meta_icon='<i class="icon '.$the_icon.' meta-icon"></i>';
							if (is_rtl()) $meta_icon_after=$meta_icon; else $meta_icon_before=$meta_icon;
							$outputs[] = '<span class="categories">'.$meta_icon_before.$content.$meta_icon_after.'</span>';
						} else 
						$outputs[] = '<span class="categories">'.__('Posted in: ', 'striking-r').$content.'</span>';
					}
					break;
				case 'tags':
					if (is_rtl()) $list_separator=' ,'; else $list_separator=', ';
					if ($metaicons) {
						$tags = get_tags();
						if (is_array($tags)) {
							$count=count($tags);
							if ($count>1) $the_icon= apply_filters('theme_meta_tags_icon','icon-tags'); else $the_icon= apply_filters('theme_meta_tag_icon','icon-tag');
							$meta_icon='<i class="icon '.$the_icon.' meta-icon"></i>';
							$meta_icon_before=$meta_icon_after='';
							if (is_rtl()) $meta_icon_after=$meta_icon; else $meta_icon_before=$meta_icon;
							$content = get_the_tag_list('',$list_separator,'');
							if (!empty($content)) $content = $meta_icon_before.$content.$meta_icon_after;
						}
					} else $content = get_the_tag_list(__('Tags: ', 'striking-r'),$list_separator,'');
					if(!empty($content)){
						$outputs[] = '<span class="tags">'.$content.'</span>';
					}
					break;
				case 'author':
					global $authordata;
					if(!$authordata){
						$authordata = get_userdata($post->post_author);
					}
					switch(theme_get_option('blog','author_link_to_website')){
						case 'website':
							$author = get_the_author_link();
							break;
						case 'archive':
							$author = get_the_author_posts_link();
							break;
						case 'none':
						default:
							$author = get_the_author();
					}
					if ($metaicons) {
						$the_icon='icon-user';
						$the_icon= apply_filters('theme_meta_author_icon',$the_icon);
						$meta_icon='<i class="icon '.$the_icon.' meta-icon"></i>';
						$meta_icon_before=$meta_icon_after='';
						if (is_rtl())$meta_icon_after=$meta_icon; else $meta_icon_before=$meta_icon;
						$outputs[] = '<span class="author vcard">'.$meta_icon_before.'<span class="fn">'.$author.'</span>'.$meta_icon_after.'</span>';
					} else
					$outputs[] = '<span class="author vcard">'.__('By: ', 'striking-r').'<span class="fn">'.$author.'</span></span>';
					
					break;
				case 'date':
					$the_icon='icon-calendar';
					$the_icon= apply_filters('theme_meta_calendar_icon',$the_icon);
					if ($metaicons) $meta_icon='<i class="icon '.$the_icon.' meta-icon meta-icon-calendar"></i>'; else $meta_icon='';
					$meta_icon_before=$meta_icon_after='';
					if (is_rtl())$meta_icon_after=$meta_icon; else $meta_icon_before=$meta_icon;
					$outputs[] = '<time class="published updated" datetime="'.get_the_time('Y-m-d').'"><a href="'.get_month_link(get_the_time('Y'), get_the_time('m')).'">'.$meta_icon_before.get_the_date().$meta_icon_after.'</a></time>';
					break;
				/*
				case 'comment':
					if(($post->comment_count > 0 || comments_open())){
						ob_start();
						comments_popup_link(__('No Comments','striking-r'), __('1 Comment','striking-r'), __('% Comments','striking-r'),'');
						$outputs[] = '<span class="comments">'.ob_get_clean().'</span>';
					}
					break;
				*/
			}
		}
		$output = implode('<span class="separater">|</span>',$outputs);
		$output .= get_edit_post_link( __( 'Edit', 'striking-r' ), '<span class="separater">|</span> <span class="edit-link">', '</span>' );
		if(in_array('comment',$meta_items) && ($post->comment_count > 0 || comments_open() && $post->comment_count > 0)){
			ob_start();
			if ($metaicons) {
				$the_icon='icon-comments';
				$the_icon= apply_filters('theme_meta_comments_icon',$the_icon);
				$meta_icon='<i class="icon '.$the_icon.' meta-icon meta-icon-comments"></i>';
				$the_icon_single='icon-comment';
				$the_icon_single= apply_filters('theme_meta_comments_icon_single',$the_icon_single);
				$meta_icon_single='<i class="icon '.$the_icon_single.' meta-icon meta-icon-comments"></i>';
				comments_popup_link('0 '.$meta_icon,'1 '.$meta_icon_single,'% '.$meta_icon,'');
			} else comments_popup_link(__('No Comments','striking-r'), __('1 Comment','striking-r'), __('% Comments','striking-r'),'');
			$output .= '<span class="comments">'.ob_get_clean().'</span>';
		}
	}

	return $output;
}
}