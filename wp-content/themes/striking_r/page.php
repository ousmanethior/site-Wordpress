<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if(is_blog()){
	$template=theme_get_template_path('template_blog.php');
	if (!empty($template)) return load_template($template);
}elseif(is_front_page()){
	$template=theme_get_template_path('front-page.php');
	if (!empty($template)) return load_template($template);
}

$post_id = theme_get_queried_object_id();
$layout = theme_get_inherit_option($post_id, '_layout', 'general','layout');
$content_width = ($layout === 'full')? 960: 630;
get_header(); 
echo theme_generator('introduce',$post_id);?>
<div id="page">
	<div class="inner <?php if($layout=='right'):?>right_sidebar<?php endif;?><?php if($layout=='left'):?>left_sidebar<?php endif;?>">
		<?php echo theme_generator('breadcrumbs',$post_id);?>
		<div id="main">
<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
			<?php get_template_part('content','page'); ?>
<?php endwhile; // end of the loop.?>
			<div class="clearboth"></div>
		</div>
		<?php if($layout != 'full') get_sidebar(); ?>
		<div class="clearboth"></div>
	</div>
</div>
<?php get_footer(); ?>