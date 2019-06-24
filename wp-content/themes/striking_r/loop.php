<?php
/**
 * The loop that displays posts.
 *
 * The loop displays the posts and the post content.  See
 * http://codex.wordpress.org/The_Loop to understand it and
 * http://codex.wordpress.org/Template_Tags to understand
 * the tags used in it.
 */
$featured_image = theme_get_option('blog', 'index_featured_image');
$featured_image_type = theme_get_option('blog', 'featured_image_type');
$between = false;
if ($featured_image_type=='between') {
	$featured_image_type='below';
	$between=true;
}
$display_full = theme_get_option('blog','display_full');
$strip_shortcode_excerpt =  theme_get_option('blog','strip_shortcode_excerpt');
if(is_search()){
	$layout = theme_get_option('advanced','search_layout');
	$display_full = theme_get_option('advanced','search_display_full');
	$strip_shortcode_excerpt=theme_get_option('advanced','search_strip_shortcode');
	if(theme_get_option('advanced','search_excerpt_shortcode')){
		add_filter('get_the_excerpt','do_shortcode');
	} else if(theme_get_option('blog','excerpt_shortcode')) remove_filter('get_the_excerpt','do_shortcode');
}
if(!isset($layout) || $layout=='default'){
	$layout = theme_get_option('blog','layout');
}
$width = '';
if($featured_image_type != 'left' && $featured_image_type != 'right'){
	if ($layout == 'full'){
		$width = 960;
	} else {
		$width = 630;
	}
}

$columns = theme_get_option('blog','columns');
$frame = theme_get_option('blog','frame');
$title = theme_get_option('blog', 'title');
$meta = theme_get_option('blog', 'meta');
$desc_length = theme_get_option('blog', 'desc_length');
if($desc_length != '0'){
	$excerpt_constructor = new Theme_The_Excerpt_Length_Constructor($desc_length);
	add_filter( 'excerpt_length', array($excerpt_constructor,'get_length'));
}

$columns = (int)$columns;
if($columns > 6){
	$columns = 6;
}elseif($columns < 1){
	$columns = 1;
}
if ($columns != 1) {
	if($featured_image_type != 'left' && $featured_image_type != 'right'){
		if($layout == 'full'){
			$width = floor((958-25*($columns-1))/$columns);
		}else{
			$width = floor((628-25*($columns-1))/$columns);
		}
	}
}


$class = array('','half','third','fourth','fifth','sixth');
$css = $class[$columns-1];
$i = 0;
if($frame){
	$frame_css = ' entry_frame';
}else{
	$frame_css = '';
}
if(is_search() && !have_posts() && $search_nothing_found = wpml_t(THEME_NAME, 'Search Nothing Found Text',theme_get_option('advanced','search_nothing_found'))) { 
   echo '<div class="search-no-results-message">'. do_shortcode(stripslashes($search_nothing_found)) .'</div>'; 
}
$meta_icons =  theme_get_option('blog','meta_info_icons');
$read_more  =  theme_get_option('blog','read_more');
$link_excerpt = theme_get_option('blog','link_excerpt');
$allowed_tags= array(
	'p'=> array(),
	'br' => array(),
	'em' => array(),
	'strong' => array(),
);
if ( have_posts() ) while ( have_posts() ) : the_post(); 
$i++;
if ($columns != 1) {
	if ($i%$columns !== 0) {
		echo "<div class=\"one_{$css}\">";
	} else {
		echo "<div class=\"one_{$css} last\">";
	}
}
?>
<article id="post-<?php the_ID(); ?>" class="hentry entry entry_<?php echo $featured_image_type;?><?php echo $frame_css;?>"> 
<?php if($featured_image && $featured_image_type!=='below'){echo theme_generator('blog_featured_image',$featured_image_type,$width,'',$frame);} 
if($title === true || $meta === true):?>
	<div class="entry_info">
		<?php if($title === true):?><h2 class="entry-title entry_title"><a href="<?php echo get_permalink() ?>" rel="bookmark" title="<?php printf( __("Permanent Link to %s", 'striking-r'), get_the_title() ); ?>"><?php the_title(); ?></a></h2><?php endif;?>
		<?php if($meta === true  && !$between):?><div class="entry_meta"><?php echo theme_generator('blog_meta',false,$meta_icons); ?></div><?php endif;?>
	</div>
<?php endif;?>
<?php if($featured_image && $featured_image_type=='below'){echo theme_generator('blog_featured_image',$featured_image_type,$width,'',$frame);} ?>
<?php if($meta === true  && $between):?>
	<div class="entry_info">
		<div class="entry_meta">
		<?php echo theme_generator('blog_meta',false,$meta_icons); ?>
		</div>
	</div>
<?php endif;?>
<?php
if(theme_get_option('blog','desc')):
?>
		<div class="entry_content entry-content">
<?php 
	if($display_full):
		global $more;
		$more = 0;
		
		// the_content(wpml_t(THEME_NAME, 'Blog Post Read More Button Text',stripslashes(theme_get_option('blog','read_more_text'))),false);
		$the_content = get_the_content(wpml_t(THEME_NAME, 'Blog Post Read More Button Text',stripslashes(theme_get_option('blog','read_more_text'))),false);
		$the_content = apply_filters('the_content', $the_content);
		$the_content = str_replace(']]>', ']]&gt;', $the_content);
		$the_content = apply_filters('theme_blog_loop_content',$the_content,get_the_ID());
		echo $the_content;
	else:
		if ($strip_shortcode_excerpt)  $the_excerpt =apply_filters('the_excerpt', strip_shortcodes(get_the_excerpt()));			
		else $the_excerpt = apply_filters('the_excerpt', get_the_excerpt());
		if ($link_excerpt && !$read_more) {
			$the_excerpt=wp_kses($the_excerpt,$allowed_tags);
			$the_excerpt = '<a class="linked_excerpt" href="'. get_permalink() .'" rel="bookmark" title="'.sprintf( __("Permanent Link to %s", 'striking-r'), get_the_title() ).'">'.$the_excerpt.'</a>';
		}
		$the_excerpt = apply_filters('theme_blog_loop_excerpt',$the_excerpt,get_the_ID());
		echo $the_excerpt;
		
		if($read_more):
			if(theme_get_option('blog','read_more_button')):?>
		<div class="read_more_wrap">
			<a class="read_more_link <?php echo apply_filters( 'theme_css_class', 'button' );?> small" href="<?php the_permalink(); ?>" rel="nofollow"><span><?php echo wpml_t(THEME_NAME, 'Blog Post Read More Button Text',stripslashes(theme_get_option('blog','read_more_text')));?></span></a>
		</div>
	<?php else: ?>
		<div class="read_more_wrap">
			<a class="read_more_link" href="<?php the_permalink(); ?>" rel="nofollow"><?php echo wpml_t(THEME_NAME, 'Blog Post Read More Button Text',stripslashes(theme_get_option('blog','read_more_text')));?></a>
		</div>
	<?php endif; 
		endif;
	endif;
?>
		
	</div>
<?php else: 
if($read_more):
			if(theme_get_option('blog','read_more_button')):?>
		<div class="read_more_wrap">
			<a class="read_more_link <?php echo apply_filters( 'theme_css_class', 'button' );?> small" href="<?php the_permalink(); ?>" rel="nofollow"><span><?php echo wpml_t(THEME_NAME, 'Blog Post Read More Button Text',stripslashes(theme_get_option('blog','read_more_text')));?></span></a>
		</div>
	<?php else: ?>
		<div class="read_more_wrap">
			<a class="read_more_link" href="<?php the_permalink(); ?>" rel="nofollow"><?php echo wpml_t(THEME_NAME, 'Blog Post Read More Button Text',stripslashes(theme_get_option('blog','read_more_text')));?></a>
		</div>
	<?php endif; 
		endif;
endif;?>
</article>
<?php

if ($columns != 1) {
	echo '</div>';
	if ($i%$columns === 0) {
		echo "<div class=\"clearboth\"></div>";
	}
}

endwhile;
if($desc_length != '0'){
	remove_filter( 'excerpt_length', array($excerpt_constructor,'get_length'));
}
?>