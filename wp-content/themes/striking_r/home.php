<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} 
$post_id = theme_get_queried_object_id();
if(is_blog()){
	$template=theme_get_template_path('template_blog.php');
	if (!empty($template)) return load_template($template);
}elseif(empty($post_id) || $post_id != get_option( 'page_for_posts' )){
	$template=theme_get_template_path('front-page.php');
	if (!empty($template)) return load_template($template);
}
$layout=theme_get_option('blog','layout');
$content_width = ($layout === 'full')? 960: 630;
$blog_page_date = &get_page($post_id);
$content = $blog_page_date->post_content;

get_header(); 
echo theme_generator('introduce',$post_id);?>
<div id="page">
	<div class="inner <?php if($layout=='right'):?>right_sidebar<?php endif;?><?php if($layout=='left'):?>left_sidebar<?php endif;?>">
		<?php echo theme_generator('breadcrumbs',$post_id);?>
		<div id="main">
			<div class="content">
				<?php echo apply_filters('the_content', stripslashes( $content ));?>
				<div class="clearboth"></div>
			</div>
		</div>
		<?php if($layout != 'full') get_sidebar(); ?>
		<div class="clearboth"></div>
	</div>
</div>
<?php get_footer(); ?>
