<?php
if(!function_exists('theme_section_portfolio_featured_image')){
/**
 * The default template for displaying portfolio_featured_image in the pages
 */
function theme_section_portfolio_featured_image($layout='',$effect= '', $single = false){
	if (!has_post_thumbnail()){
		return;
	}
	if($layout == 'full'){
		$width = 958;
	}else{
		$width = 628;
	}
	$thumbnail_id = get_post_thumbnail_id();
	if($single == false){
		$list_image = get_post_meta(get_the_ID(), '_list_image', true);
		if(is_array($list_image) && isset($list_image['value'])){
			$thumbnail_id = $list_image['value'];
		}
	}
	$image_src_array = wp_get_attachment_image_src($thumbnail_id,'full', true);
	$adaptive_height = theme_get_option('portfolio', 'adaptive_height');

	if($adaptive_height){
		$height = floor($width*($image_src_array[2]/$image_src_array[1]));
	}else{
		$height = theme_get_option('portfolio', 'fixed_height');
	}
	$image_src = theme_get_image_src(array('type'=>'attachment_id','value'=>$thumbnail_id), array($width, $height));
	$srcset=theme_get_retina_srcset( $image_src );
	
	if(empty($effect)){
		$effect = theme_get_option('blog','effect');
	}
	$title = strip_tags(get_the_title());
	$output = '';
	$output .= '<div class="image_styled entry_image" style="width:'.($width+2).'px">';
	$output .= '<div class="image_frame effect-'.$effect.'" style="height:'.($height+2).'px"><div class="image_shadow_wrap">';
	if($single){
		if(theme_get_option('portfolio', 'featured_image_lightbox')){
			$fittoview = theme_get_option('portfolio', 'featured_image_lightbox_fitToView');
			
			if($fittoview !== ''){	
				$fittoview = ($fittoview == false)?' data-fittoview="false"':' data-fittoview="true"';
			} 
			if(theme_get_option('portfolio', 'featured_image_lightbox_gallery')){
				$post_id = get_queried_object_id();
				$output .= '<a class="image_icon_zoom lightbox" href="'.$image_src_array[0].'" data-fancybx-group="post-'.$post_id.'" title="'.$title.'"'.$fittoview.'>';
				$output .= '<img width="'.$width.'" height="'.$height.'" data-thumbnail="'.$thumbnail_id.'" src="'.$image_src.'"'.$srcset.' alt="'.$title.'" />';
				$output .= '</a>';

				$children = array(
					'post_parent' => $post_id,
					'post_status' => 'inherit',
					'post_type' => 'attachment',
					'post_mime_type' => 'image',
					'order' => 'ASC',
					'orderby' => 'menu_order ID',
					'numberposts' => -1,
					'offset' => ''
				);

				$type = get_post_meta(get_the_id(), '_type', true);
				$image_ids = array();
				if($type == 'gallery'){
					$image_ids_str = get_post_meta(get_the_id(), '_image_ids', true);
					
					if(!empty($image_ids_str)){
						$image_ids = explode(',',str_replace('image-','',$image_ids_str));
					}
				}

				/* Get image attachments. If none, return. */
				$attachments = get_children( $children );
				if(!empty($attachments)||!empty($image_ids)) {
					$output .= '<div class="hidden">';
					if($type == 'gallery'){
						$only_gallery_images=theme_get_option('portfolio', 'only_gallery_images');
					} else $only_gallery_images=false;
					if(!empty($attachments) && !$only_gallery_images){
						$post_thumbnail_id = get_post_thumbnail_id();
						foreach ( $attachments as $id => $attachment ) {
							if($id != $post_thumbnail_id && !in_array($id,$image_ids) ){
								$img_src = wp_get_attachment_image_src($id, 'full');
								$output .= '<a class="lightbox" href="'.$img_src[0].'" title="'.strip_tags(get_the_title()).'" data-fancybx-group="post-'.$post_id.'"'.$fittoview.'>'.$id.'</a>';
							}
						}
					}
					if(!empty($image_ids)){
						$post_thumbnail_id = get_post_thumbnail_id();
						foreach ( $image_ids as $id ) {
							if($id != $post_thumbnail_id){
								$img_src = wp_get_attachment_image_src($id, 'full');
								$output .= '<a class="lightbox" href="'.$img_src[0].'" title="'.strip_tags(get_the_title()).'" data-fancybx-group="post-'.$post_id.'"'.$fittoview.'>'.$id.'</a>';
							}
						}
					}
					$output .= '</div>';
				}
			}else{
				$output .= '<a class="image_icon_zoom lightbox" href="'.$image_src_array[0].'" title="'.$title.'"'.$fittoview.'>';
				$output .= '<img width="'.$width.'" height="'.$height.'" data-thumbnail="'.$thumbnail_id.'" src="'.$image_src.'"'.$srcset.' alt="'.$title.'" />';
				$output .= '</a>';
			}
		} else {
			if($effect!='none'){
				$output .= '<a class="image_icon_doc" href="#" title="'.$title.'"><img width="'.$width.'" height="'.$height.'" data-thumbnail="'.$thumbnail_id.'" src="'.$image_src.'"'.$srcset.' alt="'.$title.'" /></a>';
			}else{
				$output .= '<a class="image_no_link" href="#" title="'.$title.'"><img width="'.$width.'" height="'.$height.'" data-thumbnail="'.$thumbnail_id.'" src="'.$image_src.'"'.$srcset.' alt="'.$title.'" /></a>';
			}
		}
	} else {
		$output .= '<a class="image_icon_doc" href="'.get_permalink().'" title="">';
		$output .= '<img width="'.$width.'" height="'.$height.'" data-thumbnail="'.$thumbnail_id.'" src="'.$image_src.'"'.$srcset.' alt="'.$title.'" />';
		$output .= '</a>';
	}
	$output .= '</div></div>';
	$output .= '</div>';

	return $output;
}
}