<?php 
/**
 * A Page Template for displaying posts index page
 */

$layout=theme_get_option('blog','layout');
$post_id = theme_get_queried_object_id();
$content_width = ($layout === 'full')? 960: 630;
get_header(); 
echo theme_generator('introduce',$post_id);?>
<div id="page">
	<div class="inner <?php if($layout=='right'):?>right_sidebar<?php endif;?><?php if($layout=='left'):?>left_sidebar<?php endif;?>">
		<?php echo theme_generator('breadcrumbs',$post_id);?>
		<div id="main">
			<div class="content">
				<?php 
					get_template_part( 'loop','blog');
				?>
				<div class="clearboth"></div>
			</div>
			<?php if(function_exists('wp_pagenavi')) { wp_pagenavi(); } ?>
		</div>
		<?php if($layout != 'full') get_sidebar(); ?>
		<div class="clearboth"></div>
	</div>
</div>
<?php get_footer(); ?>
