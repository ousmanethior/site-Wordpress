<?php
global $woo_config;

if( ! function_exists("theme_woocommerce_set_image_sizes") ){
	function theme_woocommerce_set_image_sizes(){
		global $woo_config;
		$woo_settings=theme_get_option('advanced');
		$woo_config = array(
			'full' => array(
				'shop_thumbnail'=> array('width'=>$woo_settings['woocommerce_thumbnail_image_width'], 'height'=>$woo_settings['woocommerce_thumbnail_image_height'] , 'crop' =>$woo_settings['woocommerce_thumbnail_image_crop']),
				'shop_catalog'  => array('width'=>$woo_settings['woocommerce_shop_image_width'], 'height'=>$woo_settings['woocommerce_shop_image_height'] , 'crop' =>$woo_settings['woocommerce_shop_image_crop']),
				'shop_single'   => array('width'=>$woo_settings['woocommerce_single_image_width'], 'height'=>$woo_settings['woocommerce_single_image_height'] , 'crop' =>$woo_settings['woocommerce_single_image_crop']),
			),
			'related_columns' => 4,
			'related_count'   => $woo_settings['woocommerce_related_products_number'],
			'single_product_layout'=> $woo_settings['woocommerce_spi_layout'],
			'overlay_icon'=> $woo_settings['woocommerce_global_hover_icon'],
			'show_shop_title'=> $woo_settings['woocommerce_shop_title'],
		);
	}
}
theme_woocommerce_set_image_sizes();


function theme_add_woocommerce_image_sizes() {
	if(!class_exists( 'Woocommerce' )) return;
	if (is_admin()) {
		global $woocommerce;
		global $woo_config;
		$wooversion=$woocommerce->version;
 
		$shop_catalog=array();
		$shop_catalog['width']= $woo_config['full']['shop_catalog']['width'];
		$shop_catalog['height']= $woo_config['full']['shop_catalog']['height'];
		$shop_catalog['crop']= $woo_config['full']['shop_catalog']['crop'];
		
		$shop_single=array();
		$shop_single['width']= $woo_config['full']['shop_single']['width'];
		$shop_single['height']= $woo_config['full']['shop_single']['height'];
		$shop_single['crop']= $woo_config['full']['shop_single']['crop'];

		$shop_thumbnail=array();
		$shop_thumbnail['width']= $woo_config['full']['shop_thumbnail']['width'];
		$shop_thumbnail['height']= $woo_config['full']['shop_thumbnail']['height'];
		$shop_thumbnail['crop']= $woo_config['full']['shop_thumbnail']['crop'];
		
		if(version_compare($wooversion, "3.3.0", '>=')){
			add_image_size( 'shop_catalog', $shop_catalog['width'], $shop_catalog['height'], $shop_catalog['crop'] );
			if (!$woo_config['single_product_layout']) {
				add_image_size( 'shop_single', $shop_single['width'], $shop_single['height'], $shop_single['crop'] );
				add_image_size( 'shop_thumbnail', $shop_thumbnail['width'], $shop_thumbnail['height'], $shop_thumbnail['crop'] );
				add_image_size( 'woocommerce_gallery_thumbnail', absint($shop_thumbnail['width']*1), absint($shop_thumbnail['height']*1), $shop_thumbnail['crop'] );
				add_image_size( 'woocommerce_single', $shop_single['width'], $shop_single['height'], $shop_single['crop'] );
			}
			add_image_size( 'woocommerce_thumbnail', $shop_catalog['width'], $shop_catalog['height'], $shop_catalog['crop'] );
		}

	}
}

	// This is the shop catalog image size !
add_filter( 'woocommerce_get_image_size_thumbnail' , 'theme_woo_get_image_sizes_thumbnail',10);
function theme_woo_get_image_sizes_thumbnail( $size ){
	global $woocommerce;
	global $woo_config;
	$wooversion=$woocommerce->version;
	$new_size=array();
	if(version_compare($wooversion, "3.3.0", '>=')){
		$new_size['width']=$woo_config['full']['shop_catalog']['width'];
		$new_size['height']=$woo_config['full']['shop_catalog']['height'];
		$new_size['crop']=$woo_config['full']['shop_catalog']['crop'];
	} else $new_size=$size;
	return($new_size);
}


	// This is the single image size !
add_filter( 'woocommerce_get_image_size_single' , 'theme_woo_get_image_sizes_single',10);
function theme_woo_get_image_sizes_single( $size ){
	global $woocommerce;
	global $woo_config;
	$wooversion=$woocommerce->version;
	$new_size=array();
	if(version_compare($wooversion, "3.3.0", '>=')){
		$new_size['width']=$woo_config['full']['shop_single']['width'];
		$new_size['height']=$woo_config['full']['shop_single']['height'];
		$new_size['crop']=$woo_config['full']['shop_single']['crop'];
	} else $new_size=$size;
	return($new_size);
}

	// This is the thumbnail gallery image size !
add_filter( 'woocommerce_get_image_size_gallery_thumbnail' , 'theme_woo_get_image_sizes_gallery_thumbnail',10);
function theme_woo_get_image_sizes_gallery_thumbnail( $size ){
	global $woocommerce;
	global $woo_config;
	$wooversion=$woocommerce->version;
	
	$new_size=array();
	if(version_compare($wooversion, "3.3.0", '>=')){
		$new_size['width']=absint($woo_config['full']['shop_thumbnail']['width']*2);
		$new_size['height']=absint($woo_config['full']['shop_thumbnail']['height']*2);
		$new_size['crop']=$woo_config['full']['shop_thumbnail']['crop'];
	} else $new_size=$size;
	return($new_size);
}

add_filter('loop_shop_columns', 'theme_shop_loop_columns');
if (!function_exists('theme_shop_loop_columns')) {
	function theme_shop_loop_columns() {
		return 4; // 4 products per row
	}
}

add_action('admin_init', 'theme_woocommerce_first_activation' , 45 );
function theme_woocommerce_first_activation() {
	if(!is_admin()) return;
	if(!class_exists( 'Woocommerce' )) return;
	
	theme_add_woocommerce_image_sizes();
	
	$theme_name = THEME_SLUG;
	
	if(get_option("{$theme_name}_woo_settings_enabled")) return;
	theme_set_option('advanced', 'complex_class', true);
	update_option("{$theme_name}_woo_settings_enabled", '1');
	
	theme_woocommerce_set_defaults();
}

add_action( 'theme_activation', 'theme_woocommerce_set_defaults', 10);
function theme_woocommerce_set_defaults() {
	global $woocommerce;
	$wooversion=$woocommerce->version;
	
	if(version_compare($wooversion, "3.3.0", '<')){

		$set_yes = array('woocommerce_frontend_css','woocommerce_single_image_crop');
		foreach ($set_yes as $option) { 
			update_option($option, 'yes'); 
		}

		$set_no = array('woocommerce_enable_lightbox');
		foreach ($set_no as $option) { 
			update_option($option, 'no'); 
		}
	}
}

add_action('theme_print_styles', 'theme_woocommerce_styles',12);
function theme_woocommerce_styles(){
	if((is_admin() && !is_shortcode_preview()) || 'wp-login.php' == basename($_SERVER['PHP_SELF'])){
		return;
	}
	wp_enqueue_style('theme-woocommerce', THEME_CSS.'/woocommerce.min.css', false, false, 'all');

	if(theme_get_option('advanced','responsive') && !is_shortcode_preview()){
		wp_enqueue_style('theme-woocommerce-responsive', THEME_CSS.'/woocommerce_responsive.min.css', false, false, 'all');
	}

	$wc_version =  defined( 'WC_VERSION' ) && WC_VERSION ? WC_VERSION : null;
	if ( version_compare( $wc_version, '2.3', '>' ) && ! class_exists( 'WooCommerce_Quantity_Increment')) {
		if (theme_get_option('advanced','woocommerce_spinners') ){
			wp_enqueue_style('theme-woocommerce-quantity-spinner', THEME_CSS.'/woocommerce-quantity-increment.min.css', false, false, 'all');
			wp_enqueue_script( 'theme-quantity-spinner-init' );
		}
	}

	if(is_rtl()){
		wp_enqueue_style('theme-woocommerce-rtl', THEME_CSS.'/woocommerce-rtl.min.css', false, false, 'all');
	}
}

remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
// add_action( 'woocommerce_before_main_content', 'theme_woocommerce_output_content_wrapper', 10);
// add_action( 'woocommerce_after_main_content', 'theme_woocommerce_output_content_wrapper_end', 10);
function theme_woocommerce_output_content_wrapper() {
	$id = theme_get_queried_object_id();
	if(is_product()){
		$layout = theme_get_inherit_option($id, '_layout', 'advanced','woocommerce_product_layout');
	}else{
		$layout = theme_get_inherit_option($id, '_layout', 'advanced','woocommerce_layout');
	}

	if(is_archive() && !theme_is_enabled(theme_get_option('advanced','woocommerce_introduce'), theme_get_option('general','introduce'))){

	}else{
		echo theme_generator('introduce',$id,true);
	}
?>
<div id="page">
	<div class="inner <?php if($layout=='right'):?>right_sidebar<?php endif;?><?php if($layout=='left'):?>left_sidebar<?php endif;?>">
		<div id="main">
<?php
}

function theme_woocommerce_output_content_wrapper_end() {
	$id = theme_get_queried_object_id();
	
	if(is_product()){
		$layout = theme_get_inherit_option($id, '_layout', 'advanced','woocommerce_product_layout');
	}else{
		$layout = theme_get_inherit_option($id, '_layout', 'advanced','woocommerce_layout');
	}
?>
		</div>
		<?php if($layout != 'full') get_sidebar(); ?>
		<div class="clearboth"></div>
	</div>
</div>
<?php
}

remove_action( 'woocommerce_before_subcategory_title', 'woocommerce_subcategory_thumbnail', 10 );
add_action( 'woocommerce_before_subcategory_title', 'theme_woocommerce_subcategory_thumbnail', 10);
function theme_woocommerce_subcategory_thumbnail( $category ) {
	global $woo_config;
	global $post;
	$size = 'shop_catalog';
	
	if(function_exists('is_shop') && is_shop()){
		$id = wc_get_page_id( 'shop' );
	} else {
		$id = theme_get_queried_object_id();
	}

	$sizes = array($woo_config['full'][$size]['width'], $woo_config['full'][$size]['height']);
	
	if ($woo_config['full'][$size]['crop']==1) $sizes[1]=$sizes[0];

	$thumbnail_id  	= get_woocommerce_term_meta( $category->term_id, 'thumbnail_id', true  );
	if ($woo_config['overlay_icon']!='none') $overlay_icon='image_icon_'.$woo_config['overlay_icon']; else $overlay_icon='';
	
	echo '<div class="product-thumbnail-wrap woo-image-overlay">';
	if ( $thumbnail_id ){
		$image_src = theme_get_image_src(array('type'=>'attachment_id','value'=>$thumbnail_id), $sizes);
		$srcset=theme_get_retina_srcset( $image_src );
		echo '<span class="'.$overlay_icon.'">';
		echo '<img class="product-thumbnail image-overlay" width="'.$sizes[0].'" height="'.$sizes[1].'" data-thumbnail="'.$thumbnail_id.'" src="'.$image_src.'"'.$srcset.' alt="'.get_the_title().'" />';
		echo '</span>';
	}	
	elseif ( wc_placeholder_img_src() ) {
		echo '<span class="'.$overlay_icon.'">';
		echo '<img class="image-overlay" src="' . theme_get_image_src(array('type'=>'url','value'=>wc_placeholder_img_src()), $sizes) . '" alt="' . esc_attr__( 'Placeholder', 'woocommerce' ) . '" width="' . esc_attr( $sizes['0'] ) . '" class="woocommerce-placeholder wp-post-image" height="' . esc_attr( $sizes['1'] ) . '" />';
		//echo wc_placeholder_img( $size );
		echo '</span>';
	}
	echo '</div>';
}


remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
add_action( 'woocommerce_before_shop_loop_item_title', 'theme_woocommerce_thumbnail', 10);
function theme_woocommerce_thumbnail(){
	global $woo_config;
	global $post;
	$size = 'shop_catalog';

	if(function_exists('is_shop') && is_shop()){
		$id = wc_get_page_id( 'shop' );
	} else {
		$id = theme_get_queried_object_id();
	}
	
	$sizes = array($woo_config['full'][$size]['width'], $woo_config['full'][$size]['height']);

	if ($woo_config['full'][$size]['crop']==1) $sizes[1]=$sizes[0];
	if ($woo_config['overlay_icon']!='none') $overlay_icon='image_icon_'.$woo_config['overlay_icon']; else $overlay_icon='';
	
	echo '<div class="product-thumbnail-wrap woo-image-overlay">';
	if ( has_post_thumbnail() ){
		$thumbnail_id = get_post_thumbnail_id();
		
		$hover = get_post_meta( get_the_id(), '_product_hover', true );
		
		$image_src = theme_get_image_src(array('type'=>'attachment_id','value'=>$thumbnail_id), $sizes);
		$srcset=theme_get_retina_srcset( $image_src );
		if($hover == 'zoom' || $hover === 'rotate'){
			echo '<img class="product-thumbnail effect-'.$hover.'" width="'.$sizes[0].'" height="'.$sizes[1].'" data-thumbnail="'.$thumbnail_id.'" src="'.$image_src.'" alt="'.get_the_title().'" />';
		} else {
			if (empty($hover) || $hover=='false') echo '<span class="'.$overlay_icon.'">';
			echo '<img class="product-thumbnail image-overlay" width="'.$sizes[0].'" height="'.$sizes[1].'" data-thumbnail="'.$thumbnail_id.'" src="'.$image_src.'"'.$srcset.' alt="'.get_the_title().'" />';
			if (empty($hover)  || $hover=='false') echo '</span>';
		}
		echo theme_woocommerce_thumbnail_hover(get_the_id(), $sizes);
	}	
	elseif ( wc_placeholder_img_src() ) {
		echo '<span class="'.$overlay_icon.'">';
		echo '<img class="image-overlay" src="' . theme_get_image_src(array('type'=>'url','value'=>wc_placeholder_img_src()), $sizes) . '" alt="' . esc_attr__( 'Placeholder', 'woocommerce' ) . '" width="' . esc_attr( $sizes['0'] ) . '" class="woocommerce-placeholder wp-post-image" height="' . esc_attr( $sizes['1'] ) . '" />';
		//echo wc_placeholder_img( $size );
		echo '</span>';
	}
	echo '</div>';
}

function theme_woocommerce_thumbnail_hover($id, $sizes){
	$hover = get_post_meta( $id, '_product_hover', true );

	if($hover === 'true'){
		$product_gallery = get_post_meta( $id, '_product_image_gallery', true );
		
		if(!empty($product_gallery)){
			$gallery	= explode(',',$product_gallery);
			$image_id 	= $gallery[0];
			$image_src = theme_get_image_src(array('type'=>'attachment_id','value'=>$image_id), $sizes);
			$srcset=theme_get_retina_srcset( $image_src );
			return '<img class="product-thumbnail product-thumbnail-hover" width="'.$sizes[0].'" height="'.$sizes[1].'" data-thumbnail="'.$image_id.'" src="'.$image_src.'"'.$srcset.' />';
		}
	}
}

remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products',20);
remove_action( 'woocommerce_after_single_product', 'woocommerce_output_related_products',10);
add_action( 'woocommerce_after_single_product_summary', 'theme_woocommerce_output_related_products', 20);

function theme_woocommerce_output_related_products(){
	$related = theme_get_option('advanced', 'woocommerce_product_related');
	if($related === 'disable'){
		return '';
	}
	if($related === 'carousel'){
		wc_get_template( 'single-product/related-carousel.php');
	}else{
		global $woo_config;
		$output = "";
		$args = array(
			'posts_per_page' => $woo_config['related_count'],
			'columns' => $woo_config['related_columns'],
			'orderby' => 'rand'
		); 
		ob_start();
		woocommerce_related_products(apply_filters( 'woocommerce_output_related_products_args', $args )); 
		$output = ob_get_clean();
		
		echo $output;
	}
}

function theme_woocommerce_breadcrumb_defaults($args){
	return wp_parse_args( array(
		'delimiter'   => ' <span class="separator">&#187;</span> ',
		'wrap_before' => '<section id="breadcrumbs" itemprop="breadcrumb">',
		'wrap_after'  => '</section>',
		'before'      => '',
		'after'       => '',
		'home'        => _x( 'Home', 'breadcrumb', 'woocommerce' ),
	), $args);
}
add_filter('woocommerce_breadcrumb_defaults', 'theme_woocommerce_breadcrumb_defaults');


function theme_woocommerce_loop_add_to_cart_link($content){
	$content = str_replace(' button ', ' ', $content);
	$content = preg_replace('|(<a.*?class=")(.+?)(".*?>)(.+)?(</a>)|i', '<div class="product-actions">$1theme_button white $2$3<span class="product-action-button" >$4</span>$5</div>',$content);
	return $content;
}

add_filter('woocommerce_loop_add_to_cart_link', 'theme_woocommerce_loop_add_to_cart_link');

add_action( 'woocommerce_before_shop_loop_item_title', 'theme_woocommerce_product_wrap_meta_div', 20);
function theme_woocommerce_product_wrap_meta_div(){
	echo "<div class='product-meta-wrap'>";
}

add_action( 'woocommerce_after_shop_loop_item_title',  'theme_woocommerce_div_close', 1000);

function theme_woocommerce_div_close(){
	echo "</div>";
}

add_action( 'woocommerce_before_single_product_summary', 'theme_woocommerce_add_image_wrap_div', 2);
add_action( 'woocommerce_before_single_product_summary',  'theme_woocommerce_div_close', 20);
function theme_woocommerce_add_image_wrap_div() {
	echo "<div class='one_third single-product-main-image'>";
}

function theme_woocommerce_div_close_with_clear() {
	echo "</div>";
	echo '<div class="clearboth"></div>';
}

add_action( 'woocommerce_before_single_product_summary', 'theme_woocommerce_add_summary_wrap_div', 25);
add_action( 'woocommerce_after_single_product_summary',  'theme_woocommerce_div_close_with_clear', 3);
function theme_woocommerce_add_summary_wrap_div(){
	echo "<div class='two_third last single-product-summary'>";
}

add_action('wp_head', 'theme_woocommerce_product_desciption_position') ;
function theme_woocommerce_product_desciption_position(){
	if(is_product()){
		$position = theme_get_option('advanced','woocommerce_desciption_Position');
		if($position === 'aside'){
			remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
			add_action(    'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 1 );
		}
	}
}

function theme_woocommerce_checkout_fields( $fields ) {
    $fields['order']['order_comments']['custom_attributes'] = array('cols' => 20, 'rows' => 9);

    return $fields;
}
add_filter( 'woocommerce_checkout_fields' , 'theme_woocommerce_checkout_fields' );

function theme_wp_title_parts($title_array){
	if( function_exists('is_woocommerce') && is_woocommerce()){
		if(function_exists('is_shop') && is_shop()){
			$shop_id = wc_get_page_id( 'shop' );

			$title = get_the_title( $shop_id );

			return array($title);
		}
	}

	return $title_array;
}
add_filter('wp_title_parts', 'theme_wp_title_parts');

// remove the filter 
remove_filter( 'woocommerce_product_thumbnails_columns', 'filter_woocommerce_product_thumbnails_columns', 10, 1 ); 

function theme_filter_woocommerce_product_thumbnails_columns($int){
	$int=3;
	return $int;
}
// add the filter 
add_filter( 'woocommerce_product_thumbnails_columns', 'theme_filter_woocommerce_product_thumbnails_columns', 10, 1 );

if ( ! function_exists( 'theme_supports_woo_spi_layout' ) ) {
 /* Add WooCommerce Gallery Support - 3.0 */
	function theme_supports_woo_spi_layout() {
		$use_lightbox=theme_get_option('advanced','woocommerce_use_lightbox');
		add_theme_support( 'wc-product-gallery-zoom' );
		if ($use_lightbox) add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
	}
}

if ($woo_config['single_product_layout']) {
	theme_supports_woo_spi_layout();
}else {
// define the woocommerce_available_variation callback 
function theme_filter_woocommerce_available_variation( $image_srcset, $instance, $variation ) { 
	if (isset($image_srcset['image']['srcset'])) unset($image_srcset['image']['srcset']);
	if (isset($image_srcset['image']['sizes'])) unset($image_srcset['image']['sizes']);
	if (isset($image_srcset['image']["full_src"])) unset($image_srcset['image']["full_src"]);
	if (isset($image_srcset['image']["thumb_src"])) unset($image_srcset['image']["thumb_src"]);
	if (isset($image_srcset['image']["full_src_w"])) unset($image_srcset['image']["full_src_w"]);
	if (isset($image_srcset['image']["full_src_h"])) unset($image_srcset['image']["full_src_h"]);
	if (isset($image_srcset['image']["thumb_src_w"])) unset($image_srcset['image']["thumb_src_w"]);
	if (isset($image_srcset['image']["thumb_src_h"])) unset($image_srcset['image']["thumb_src_h"]);
	if (isset($image_srcset['image']["src_w"])) unset($image_srcset['image']["src_w"]);
	if (isset($image_srcset['image']["src_h"])) unset($image_srcset['image']["src_h"]);
	$image_srcset['image']["alt"]='variable_image_thumbnail_id=['.$image_srcset['image_id'].']'.$image_srcset['image']["alt"];
	return $image_srcset; 
}; 
// add the filter 
add_filter( 'woocommerce_available_variation', 'theme_filter_woocommerce_available_variation', 10, 3 );
}

if (!$woo_config['show_shop_title']) add_filter( 'woocommerce_show_page_title' , 'woo_hide_page_title' );
function woo_hide_page_title() {
	return false;
}

add_filter('woocommerce_style_smallscreen_breakpoint','theme_set_woo_smallscreen_breakpoint');

function theme_set_woo_smallscreen_breakpoint($breakpoint) {
  $breakpoint = '767px';
  return $breakpoint;
}


remove_action( 'woocommerce_archive_description', 'woocommerce_product_archive_description', 10 );
add_action( 'woocommerce_archive_description', 'theme_woocommerce_product_archive_description', 10 );

function theme_woocommerce_product_archive_description() { 
	// Don't display the description on search results page. 
	if ( is_search() ) { 
		return; 
	}

	if ( is_post_type_archive( 'product' ) ) { 
		$shop_page = get_post( wc_get_page_id( 'shop' ) ); 
		if ( $shop_page ) { 
			$show_content_on_all_paged_pages=theme_get_option('advanced','woocommerce_show_shop_content_all');
			if ($show_content_on_all_paged_pages || 0 === absint( get_query_var( 'paged' )) || 1 === absint( get_query_var( 'paged' ))){
				$description = wc_format_content( $shop_page->post_content ); 
				if ( $description ) { 
					echo '<div class="page-description">' . $description . '</div>'; // WPCS: XSS ok. 
				} 
			}
		}
	}
}
