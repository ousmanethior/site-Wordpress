<?php
$theme_footer_js=$theme_footer_css='';
function theme_get_queried_object_id(){
	if(function_exists('is_shop') && is_shop()){
		return wc_get_page_id( 'shop' );
	} else {
		return get_queried_object_id();
	}
}

function theme_create_cache_files(){
	include_once( ABSPATH . 'wp-admin/includes/file.php' );

	$dirs = array(
		THEME_CACHE_DIR, THEME_CACHE_IMAGES_DIR
	);

	foreach ($dirs as $dir){
		if( !is_dir( $dir ) ){
			wp_mkdir_p($dir);
		}
	}

	$files = array(
		array(
			'base' 		=> THEME_CACHE_DIR,
			'file' 		=> 'index.html',
			'content' 	=> ''
		),
		array(
			'base' 		=> THEME_CACHE_IMAGES_DIR,
			'file' 		=> 'index.html',
			'content' 	=> ''
		)
	);

	foreach ( $files as $file ) {
		if ( wp_mkdir_p( $file['base'] ) && ! file_exists( trailingslashit( $file['base'] ) . $file['file'] ) ) {
			theme_write_file(trailingslashit( $file['base'] ) . $file['file'], $file['content']);
		}
	}
}

function theme_support_for_themecheck(){
	add_theme_support('custom-header', array());
	add_theme_support('custom-background', array());
}

function theme_generator($slug){
	do_action( "theme_generator_{$slug}", $slug);
	$slug = apply_filters("theme_generator_{$slug}",$slug);

	$template = "{$slug}.php";

	theme_load_section($template);

	$args = array_slice( func_get_args(), 1 );

	$function = "theme_section_{$slug}";

	return call_user_func_array($function, $args );
}

function theme_shortcode_parse_atts($text) {
	$text = str_replace(array('&#8220;', '&Prime;', '&#8221;', '&#8243;', '&#8217;', '&#8242;', '&nbsp;&raquo;', '&#187;','&quot;'), array('"','"','"', '"', '\'', '\'', '"', '"', '"'), $text);
	return shortcode_parse_atts($text);
}

if(!function_exists('theme_load_section')){
function theme_load_section($template_name){
	if( file_exists(THEME_SECTIONS.'/'.$template_name)){
		require_once(THEME_SECTIONS.'/'.$template_name);
	}
}
}

function theme_get_logo(){
	$logo=wpml_t(THEME_NAME, 'Logo Image Source', theme_get_option('general','logo'));
	if (!is_array($logo)) $logo=json_decode($logo);
	if(is_object($logo)){
		return (array)$logo;
	} else {
		return $logo;
	}
}

function theme_get_mobile_logo(){
	$logo=wpml_t(THEME_NAME, 'Logo Image Source For Mobile Devices', theme_get_option('general','mobile_logo'));
	if (!is_array($logo)) $logo=json_decode($logo);
	if(is_object($logo)){
		return (array)$logo;
	} else {
		return $logo;
	}
}

function theme_get_logo_2x(){
	$logo=wpml_t(THEME_NAME, 'Logo Image Source 2x', theme_get_option('general','logo_2x'));
	if (!is_array($logo)) $logo=json_decode($logo);
	if(is_object($logo)){
		return (array)$logo;
	} else {
		return $logo;
	}
}

function theme_get_mobile_logo_2x(){
	$logo=wpml_t(THEME_NAME, 'Logo Image Source For Mobile Devices 2x', theme_get_option('general','mobile_logo_2x'));
	if (!is_array($logo)) $logo=json_decode($logo);
	if(is_object($logo)){
		return (array)$logo;
	} else {
		return $logo;
	}
}
/**
 * Retrieve option value based on name of option.
 * 
 * If the option does not exist or does not have a value, then the return value will be false.
 * 
 * @param string $page page name
 * @param string $name option name
 */
function theme_get_option($page, $name = null) {
	global $theme_options;

	if($theme_options === null){
		return theme_get_option_from_db($page, $name);
	}

	if ($name == null) {
		if (isset($theme_options[$page])) {
			return $theme_options[$page];
		} else {
			return null;
		}
	} else {
		if (isset($theme_options[$page][$name])) {
			return $theme_options[$page][$name];
		} else {
			return null;
		}
	}
}

function theme_get_option_from_db($page, $name = null){
	$options = get_option('theme_' . $page);

	if($name == null){
		return $options;
	}else{
		if(is_array($options) && isset($options[$name])){
			return $options[$name];
		}
		return null;
	}
}

function theme_get_inherit_option($post_id, $meta_name, $page, $option_name) {
	$value = get_post_meta($post_id, $meta_name, true);

	if($value === 'false'){
		return false;
	}
	if($value===""|| $value == 'default'||empty($value)){
		$value=theme_get_option($page, $option_name);
	}
	return $value;
}

function theme_set_option($page, $name, $value) {
	global $theme_options;
	$theme_options[$page][$name] = $value;
	
	update_option('theme_' . $page, $theme_options[$page]);
}

function theme_get_sidebar_default(){
	if(theme_is_post_type('post')){
		return theme_get_option('sidebar','single_post');
	}
	if(theme_is_post_type('portfolio')){
		return theme_get_option('sidebar','single_portfolio');
	}
	if(theme_is_post_type('page')){
		return theme_get_option('sidebar','single_page');
	}
	return '';
}

function theme_get_sidebar_options(){
	$sidebars = theme_get_option_from_db('sidebar','sidebars');
	if(!empty($sidebars)){
		$sidebars_array = explode(',',$sidebars);
		
		$options = array();
		foreach ($sidebars_array as $sidebar){
			$options[$sidebar] = $sidebar;
		}
		return $options;
	}else{
		return array();
	}
}

function theme_enqueue_icon_set() {
	if($icons = theme_get_option('font','icons')){
		if(is_array($icons)){
			$icons = current($icons);
		}
		switch($icons){
			case 'awesome':
				wp_enqueue_style('theme-icons-awesome', THEME_ICONS_URI.'/awesome/css/font-awesome.min.css', false, false, 'all');
				break;
		}
	}
}
function theme_get_icon_sets(){
	$icons = theme_get_option('font', 'icons');
	$array = array();
	if(!empty($icons)){
		if(is_array($icons)){
			$icons = current($icons);
		}

		$sets = include(THEME_ICONS_DIR.'/'.$icons.'/sets.php');
		foreach($sets as $group => $items){
			$array[$group] = array();
			foreach ($items as $item) {
				$array[$group][$item] = $item;
			}
		}
	}
	return $array;
}

/**
 * It will return a boolean value.
 * If the value to be checked is empty, it will use default value instead of.
 * 
 * @param mixed $value
 * @param mixed $default
 */
function theme_is_enabled($value, $default = false) {
	if(is_bool($value)){
		return $value;
	}
	switch($value){
		case '1'://for theme compatibility
		case 'true':
			return true;
		case '-1'://for theme compatibility
		case 'false':
			return false;
		case '0':
		case '':
		default:
			return $default;
	}
}

function theme_get_excluded_pages(){
	$excluded_pages = theme_get_option('general', 'excluded_pages');
	$excluded_pages_with_childs = '';
	$home = theme_get_option('homepage','home_page');
	/* if('page' == get_option('show_on_front') ){
		$home = get_option('page_on_front');

		if(!$home){
			$home = get_option('page_for_posts');
		}
	}*/
	if (! empty($excluded_pages)) {
		//Exclude a parent and all of that parent's child Pages
		foreach($excluded_pages as $parent_page_to_exclude) {
			if ($excluded_pages_with_childs) {
				$excluded_pages_with_childs .= ',' . $parent_page_to_exclude;
			} else {
				$excluded_pages_with_childs = $parent_page_to_exclude;
			}
			$descendants = get_pages('child_of=' . $parent_page_to_exclude);
			if ($descendants) {
				foreach($descendants as $descendant) {
					$excluded_pages_with_childs .= ',' . $descendant->ID;
				}
			}
		}
		if($home){
			$excluded_pages_with_childs .= ',' .$home;
		}
	} else {
		$excluded_pages_with_childs = $home;
	}
	return $excluded_pages_with_childs;
}

if(!function_exists("get_queried_object_id")){
	/**
	* Retrieve ID of the current queried object.
	*/
	function get_queried_object_id(){
		global $wp_query;
		return $wp_query->get_queried_object_id();
	}
}
if(!function_exists("get_the_author_posts_link")){
	function get_the_author_posts_link(){
		return '<a href="' . get_author_posts_url(get_the_author_meta( 'ID' )) . '" title="' . esc_attr( sprintf(__('Visit %s&#8217;s all posts','striking-r'), get_the_author()) ) . '" rel="author">' . get_the_author() . '</a>';
	}
}
// use for template_blog.php
function is_blog() {
	global $is_blog;
	
	//bug fix for woo and translated wpml woo shop page sometimes returns true for is_blog
	if (function_exists('is_shop') && is_shop()) {return false;}
	
	if($is_blog == true){return true;}
	$blog_page_id = theme_get_option('blog','blog_page');
	
	if(empty($blog_page_id)){
		$blog_page_id = get_option( 'page_for_posts' );
		if (empty($blog_page_id)) return false; 
	}
	//polylang compatibility
	if(function_exists('pll_get_post')){
		if(pll_get_post($blog_page_id) == get_queried_object_id()){
			$is_blog = true;
			return true;
		}
	}
	if(function_exists('wpml_get_object_id')){
		if(wpml_get_object_id($blog_page_id,'page') == get_queried_object_id()){
			$is_blog = true;
			return true;
		}
	}
	
	return false;
}
function is_shortcode_dialog() {
	if(isset($_GET['action']) && $_GET['action']=='theme-shortcode-dialog'){
		return true;
	}else{
		return false;
	}
}
function is_shortcode_preview() {
	if(defined('DOING_AJAX') && isset($_GET['action']) && $_GET['action']=='theme-shortcode-preview'){
		return true;
	}else{
		return false;
	}
}

function is_slide_preview() {
	if(isset($_GET['layerpreview']) && $_GET['layerpreview']=='true' && isset($_GET['sliderid'])){
		return true;
	}else{
		return false;
	}
}
if(!function_exists("wp_basename")){
	function wp_basename( $path, $suffix = '' ) {
		return urldecode( basename( str_replace( '%2F', '/', urlencode( $path ) ), $suffix ) );
	}
}

function theme_color_fallback($rule, $color, $important=false){
	if($important){
		$important = ' !important';
	}else{
		$important = '';
	}
	if(preg_match('/rgba\(\s*(\d{1,3}%?)\s*,\s*(\d{1,3}%?)\s*,\s*(\d{1,3}%?)\s*,\s*(\d?(?:\.\d+)?)\s*\)/i',$color,$matches)){
		$rgb = 'rgb('.$matches[1].','.$matches[2].','.$matches[3].')';
		if($matches[4]==='1'){
			return <<<CSS
	{$rule}: {$rgb}{$important};
CSS;
		}else{
			return <<<CSS
	{$rule}: {$rgb}{$important};
	{$rule}: {$color}{$important};
CSS;
		}
	}elseif(empty($color)){
		return <<<CSS
	{$rule}: transparent{$important};
CSS;
	}else{
		return <<<CSS
	{$rule}: {$color}{$important};
CSS;
	}
}

function theme_google_analytics_code(){
	$analytics_id = theme_get_option('general','analytics_id');
	$analytics_code = '';
	if($analytics_id) {
		$analytics_code = <<<SCRIPT
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', '{$analytics_id}', 'auto');
  ga('send', 'pageview');

</script>
SCRIPT;
	} else if(theme_get_option('general','analytics')) {
		$analytics_code = stripslashes(theme_get_option('general','analytics'));
	}

	echo $analytics_code;
}

/**
 * Fix the image src for MultiSite
 * 
 * @param string $src the full path of image
 */
function get_image_src($src) {
	if(is_multisite()){
		global $blog_id;
		$upload_path = get_blog_option($blog_id,'upload_path');
		if(!empty($upload_path) && strpos($src, $upload_path) !== false){
			$url=site_url();
			return str_replace($url, '', $src);
		}
		if(is_subdomain_install()){
			if ( defined( 'DOMAIN_MAPPING' ) ){
				if(function_exists('get_original_url')){ //WordPress MU Domain Mapping
					if(false !== @strpos($src, str_replace(get_original_url('siteurl'),site_url(),get_blog_option($blog_id,'fileupload_url')))){
						return site_url().'/'.str_replace(str_replace(get_original_url('siteurl'),site_url(),get_blog_option($blog_id,'fileupload_url')),get_blog_option($blog_id,'upload_path'),$src);
					}
				}else { //VHOST and directory enabled Domain Mapping plugin
					global $dm_map;
					if(isset($dm_map)){
						static $orig_urls = array();
						if ( ! isset( $orig_urls[ $blog_id ] ) ) {
							remove_filter( 'pre_option_siteurl', array(&$dm_map, 'domain_mapping_siteurl') );
							$orig_url = site_url();
							$orig_urls[ $blog_id ] = $orig_url;
							add_filter( 'pre_option_siteurl', array(&$dm_map, 'domain_mapping_siteurl') );
						}
						if(false !== strpos($src, str_replace($orig_urls[$blog_id],site_url(),get_blog_option($blog_id,'fileupload_url')))){
							return site_url().'/'.str_replace(str_replace($orig_urls[$blog_id],site_url(),get_blog_option($blog_id,'fileupload_url')),get_blog_option($blog_id,'upload_path'),$src);
						}
					}
				}
			}
			if(false !== strpos($src, get_blog_option($blog_id,'fileupload_url'))){
				return site_url().'/'.str_replace(get_blog_option($blog_id,'fileupload_url'),get_blog_option($blog_id,'upload_path'),$src);
			}
		}else{
			if ( defined( 'DOMAIN_MAPPING' ) ){
				if(function_exists('get_original_url')){ //WordPress MU Domain Mapping
					if(false !== strpos($src, get_blog_option($blog_id,'fileupload_url'))){
						return site_url().'/'.str_replace(str_replace(get_original_url('siteurl'),site_url(),get_blog_option($blog_id,'fileupload_url')),get_blog_option($blog_id,'upload_path'),$src);
					}
				}
			}
			$curSite =  get_current_site(1);

			if(false !== strpos($src, get_blog_option($blog_id,'fileupload_url'))){
				return $curSite->path.str_replace(get_blog_option($blog_id,'fileupload_url'),get_blog_option($blog_id,'upload_path'),$src);
			}
		}
		if(defined('DOMAIN_CURRENT_SITE')){
			if(false !== strpos($src, DOMAIN_CURRENT_SITE)){
				$src = preg_replace('/^https?:\/\//i', '', $src);
				return str_replace(DOMAIN_CURRENT_SITE, '', $src);
			}
		}
	}else{
		if(0 === strpos($src,site_url())){
			return str_replace(site_url(), '', $src);
		}
	}
	return $src;
	
}

function theme_get_image_src($source, $size = 'full', $quality=''){
	$return = '';
	if(empty($source) || !isset($source['type'])){
		return '';
	}
	if(!is_array($size)){
		switch($source['type']){
			case 'attachment_id':
				if(empty($source['value'])){
					return '';
				}
				if(stripos($source['value'],'ngg-') !== false && class_exists('nggdb')) {
					$nggMeta = new nggMeta(str_replace('ngg-','',$source['value']));
					$return = $nggMeta->image->imageURL;
				}else{
					$src = wp_get_attachment_image_src($source['value'], 'full');
					if($src){
						$return = $src[0];
					}
				}
				break;
			case 'url':
			default:
				$return = $source['value'];
				break;
		}
	} else {
		switch($source['type']){
			case 'attachment_id':
				if(empty($source['value'])){
					return '';
				}
				$resizer = new ImageResizerByAttachmentId($source['value'], $size);
				
				$return =  $resizer->src();
				break;
			case 'url':
			default:
				$resizer = new ImageResizerByUrl($source['value'], $size);
				$return =  $resizer->src();
				break;
		}
	}

	if($return){
		if(is_ssl()){
			return preg_replace('/^http?:\/\//i', 'https://', $return);
		} else {
			return $return;
		}
	}
	return false;
}

class ThemeImageResizer {
	protected $width;
	protected $height;
	protected $src;
	protected $src2x;
	protected $cache_dir;
	protected $cache_uri;
	public function __construct($size, $quality = 90) {
		$this->width = $size[0];
		$this->height = $size[1];
		$this->cache_dir = THEME_CACHE_IMAGES_DIR.'/';
		$this->cache_uri = THEME_CACHE_IMAGES_URI.'/';
		$this->quality = $quality;

		if(!$this->cache_exists()){
			$this->resize();
		}
	}
	protected function get_file_basename($file, $suffix = ''){
		return wp_basename($file, $suffix);
	}
	protected function resize(){}
	protected function cache_exists(){}
	public function src(){}
	protected function resize_process($file,$width,$height,$suffix = null,$dest_path = null,$jpeg_quality = 90){
		global $wp_version;
		
		$image = imagecreatefromstring( file_get_contents( $file ) );
		
		if ( !is_resource( $image ) )
			return new WP_Error( 'error_loading_image', $image, $file );
		
		$size = @getimagesize( $file );
		if ( !$size )
			return new WP_Error('invalid_image', __('Could not read image size','striking-r'), $file);

		list($orig_w, $orig_h, $orig_type) = $size;

		if($height == ''){
			$height = round($orig_h * $width/$orig_w);
			if ( !$suffix )
			$suffix = "{$width}";
		}
		$dims = $this->resize_dimensions($orig_w, $orig_h, $width, $height);
		if ( !$dims )
			return new WP_Error( 'error_getting_dimensions', __('Could not calculate resized image dimensions','striking-r') );
		list($dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h) = $dims;

		$newimage = wp_imagecreatetruecolor( $width, $height );
		
		if ( IMAGETYPE_PNG == $orig_type || IMAGETYPE_GIF == $orig_type ){
			imagealphablending($newimage, false);
			$color = imagecolorallocatealpha ($newimage, 255, 255, 255, 127);
			imagefill ($newimage, 0, 0, $color);
			imagesavealpha($newimage, true);
		}
		
		imagecopyresampled( $newimage, $image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);

		// convert from full colors to index colors, like original PNG.
		if ( IMAGETYPE_PNG == $orig_type && function_exists('imageistruecolor') && !imageistruecolor( $image ) )
			imagetruecolortopalette( $newimage, false, imagecolorstotal( $image ) );
		
		// we don't need the original in memory anymore
		imagedestroy( $image );
		if ( !$suffix )
			$suffix = "{$width}x{$height}";
		
		$info = pathinfo($file);
		$dir = $info['dirname'];
		$ext = $info['extension'];
		$name = $this->get_file_basename($file, ".$ext");

		if ( !is_null($dest_path) and $_dest_path = realpath($dest_path) )
			$dir = $_dest_path;

		$destfilename = "{$dir}/{$name}-{$suffix}.{$ext}";

		if ( IMAGETYPE_GIF == $orig_type ) {
			if ( !imagegif( $newimage, $destfilename ) )
				return new WP_Error('resize_path_invalid', __('Resize path invalid','striking-r'));
		} elseif ( IMAGETYPE_PNG == $orig_type ) {
			if ( !imagepng( $newimage, $destfilename ) )
				return new WP_Error('resize_path_invalid', __('Resize path invalid','striking-r'));
		} else {
			// all other formats are converted to jpg
			$destfilename = "{$dir}/{$name}-{$suffix}.jpg";
			if ( !imagejpeg( $newimage, $destfilename, apply_filters( 'jpeg_quality', $jpeg_quality, 'image_resize' ) ) )
				return new WP_Error('resize_path_invalid', __('Resize path invalid','striking-r'));
		}

		imagedestroy( $newimage );

		// Set correct file permissions
		$stat = stat( dirname( $destfilename ));
		$perms = $stat['mode'] & 0000666; //same permissions as parent folder, strip off the executable bits
		@ chmod( $destfilename, $perms );

		return $destfilename;
	}

	protected function resize_dimensions($orig_w, $orig_h, $dest_w, $dest_h){
		if ($orig_w <= 0 || $orig_h <= 0)
			return false;
		// at least one of dest_w or dest_h must be specific
		if ($dest_w <= 0 && $dest_h <= 0)
			return false;
		$src_x=0;
		$src_y=0;
		$src_w = $orig_w;
		$src_h = $orig_h;

		$cmp_x = $orig_w / $dest_w;
		$cmp_y = $orig_h / $dest_h;
		if ($cmp_x > $cmp_y) {

			$src_w = round ($orig_w / $cmp_x * $cmp_y);
			$src_x = round (($orig_w - ($orig_w / $cmp_x * $cmp_y)) / 2);

		} else if ($cmp_y > $cmp_x) {

			$src_h = round ($orig_h / $cmp_y * $cmp_x);
			$src_y = round (($orig_h - ($orig_h / $cmp_y * $cmp_x)) / 2);

		}
		return array( 0, 0, $src_x,  $src_y, $dest_w,  $dest_h,  $src_w,  $src_h );
	}
}

class ImageResizerByAttachmentId extends ThemeImageResizer {
	protected $attachment_id;
	protected $metadata;
	protected $size_name;
	public function __construct($attachment_id, $size,$quality = 90) {
		if(empty($attachment_id)){
			return;
		}
		$this->attachment_id = $attachment_id;
		$this->metadata = wp_get_attachment_metadata($attachment_id);

		if($this->metadata === false || !is_array($this->metadata)){
			return;
		}

		if(isset($size[1])){
			$height = (int)$size[1];
			if($height < 0){
				unset($size[1]);
			}
		}
		if(empty($size[1])){
			$size[1] = floor(($this->metadata['height'] * $size[0])/$this->metadata['width']);
			
			$this->size_name = "{$size[0]}";
		}else{
			$this->size_name = "{$size[0]}x{$size[1]}";
		}
		
		if( isset($this->metadata['width']) && $this->metadata['width'] == $size[0] && isset($this->metadata['height']) && $this->metadata['height'] == $size[1]){
			$src_array = wp_get_attachment_image_src($attachment_id, 'full');
			$this->src = $src_array[0];
			return;
		}

		parent::__construct($size);
	}
	protected function get_file_basename($file, $suffix = ''){
		return $this->attachment_id.'_'.wp_basename($file, $suffix);
	}

	protected function resize(){
		if(stripos($this->attachment_id,'ngg-') !== false && class_exists('nggdb')) {
			$nggMeta = new nggMeta(str_replace('ngg-','',$this->attachment_id));
			$file = $nggMeta->image->imagePath;
		}else{
			if ( !preg_match('!^image/!', get_post_mime_type( $this->attachment_id ))) {
				return new WP_Error('attachment_is_not_image', __('Attachment is not image','striking-r'));
			}
			$file = get_attached_file($this->attachment_id);
		}
		
		
		$info = @getimagesize($file);
		if ( empty($info) || !in_array($info[2], array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG))) // only gif, jpeg and png images can reliably be displayed
			return new WP_Error('image_type_is_not_correctly', __('Image type is not correctly','striking-r'));
		
		$resized_file = $this->resize_process($file, $this->width, $this->height, $this->size_name, $this->cache_dir, $this->quality);
		// update attachment metadata to make it store custom sizes infos
		$this->metadata['custom_sizes'][$this->size_name] = array(
			'file' => wp_basename($resized_file),
			'width' => $this->width,
			'height' => $this->height,
		);
		wp_update_attachment_metadata($this->attachment_id, $this->metadata);

		$this->src = $this->cache_uri.$this->metadata['custom_sizes'][$this->size_name]['file'];

		$resized_file = $this->resize_process($file, $this->width*2, $this->height*2, $this->size_name.'@2x', $this->cache_dir, $this->quality);
		$this->src2x = $this->cache_uri . wp_basename($resized_file);
	}

	public function cache_exists(){
		if($this->src){
			return true;
		}

		if ( !is_array( $this->metadata ) )
			return false;
		if (isset($this->metadata['custom_sizes'][$this->size_name] )){
			$this->src = $this->cache_uri.$this->metadata['custom_sizes'][$this->size_name]['file'];
			//$this->file = $this->metadata['custom_sizes'][$this->size_name]['file'];
			return true;
		}
		if ( !empty($this->metadata['sizes']) ) {
			foreach ( $this->metadata['sizes'] as $_size => $data ) {
				// already cropped to width or height; so use this size
				if ( $data['width'] == $this->width && $data['height'] == $this->height ) {
					$src_array = wp_get_attachment_image_src($this->attachment_id, $_size);
					$srcset=theme_get_retina_srcset( $src_array[0] );
					if (!empty($srcset)) {
						$this->src = $src_array[0];
						//$this->file = $data['file'];
						return true;
					}
				}
			}
		}
		
		return false;
	}

	public function src(){
		if($this->src){
			return $this->src;
		}
		return false;
	}
}

class ImageResizerByUrl extends ThemeImageResizer {
	protected $path;
	protected $url;
	protected $external = false;
	protected $size_name;
	public function __construct($url, $size) {
		$this->url = $url;
		$url_info = parse_url($url);
		
		if(isset($url_info['host']) && preg_replace('/^www\./i', '', strtolower($url_info['host'])) != strtolower(preg_replace('/^www\./i', '', $_SERVER['HTTP_HOST']))){
			$this->external = true;
		}
		if($this->external){
			$this->src = $url;
		}else{
			$this->path = get_image_src($url);
			if($size[1] == ''){
				$this->size_name = "{$size[0]}";
			}else{
				$this->size_name = "{$size[0]}x{$size[1]}";
			}
		}
		parent::__construct($size);		
	}
	public function resize(){
		$path = ltrim($this->path, '/\\');
		$file = ABSPATH. $path;
		
		if(!is_file($file)){
			return new WP_Error('file_is_not_exists', __('File is not exists','striking-r'));
		}
		$resized_file = $this->resize_process($file, $this->width, $this->height,$this->size_name,$this->cache_dir,$this->quality);
		if ( is_wp_error($resized_file) ){
			return $resized_file;
		}
		$this->src =  $this->cache_uri . wp_basename($resized_file);
		$resized_file = $this->resize_process($file, $this->width*2, $this->height*2,$this->size_name.'@2x',$this->cache_dir,$this->quality);
		if ( is_wp_error($resized_file) ){
			return $resized_file;
		}
		$this->src2x =  $this->cache_uri . wp_basename($resized_file);
	}
	public function cache_exists(){
		if($this->external){
			return true;
		}
		if($this->src){
			return true;
		}
		if($this->path){
			$info = pathinfo($this->path);
			$ext = $info['extension'];
			$name = wp_basename($this->path, ".$ext");
			$filename = "{$name}-{$this->size_name}.{$ext}";
			$cached_file = $this->cache_dir . $filename;
			if(is_file($cached_file)){
				$this->src = $this->cache_uri . $filename;
				return true;
			}
		}
		return false;
	}
	public function src(){
		if($this->src){
			return $this->src;
		}
		return $this->url;
	}
}

function theme_add_cufon_code(){
	$code = stripslashes(theme_get_option('font','cufon_code'));
	//$fonts = theme_get_option('font','cufon_used');
	$default_font = theme_get_option('font','cufon_default');
	if(!empty($default_font)){
		$font_name='';
		$file_content='';
		if (defined('THEME_CHILD_FONT_DIR')) {
			if (file_exists(THEME_CHILD_FONT_DIR.'/'.$default_font)) {
				$file_content = file_get_contents(THEME_CHILD_FONT_DIR.'/'.$default_font);
			}
		}
		if (empty($file_content)) 
			$file_content = file_get_contents(THEME_FONT_DIR.'/'.$default_font);
		if(preg_match('/font-family":"(.*?)"/i',$file_content,$match)){
			$font_name = $match[1];
		}
		if($font_name){
			$default_code = <<<CODE
Cufon.replace("#site_name,#site_description,.kwick_title,.kwick_detail h3,#footer h3,#copyright,.dropcap1,.dropcap2,.dropcap3,.dropcap4,.carousel_title, .milestone_number, .milestone_subject, .process_step_title, .pie_progress, .progress-meter,.roundabout-title", {fontFamily : "{$font_name}"}); 
Cufon.replace("#feature h1,#introduce,.slogan_text",{fontFamily : "{$font_name}"});
Cufon.replace('.portfolio_title,h1,h2,h3,h4,h5,h6,#navigation a, .entry_title a', {
	hover: true,
	fontFamily : "{$font_name}"
});
CODE;
		}
	}else{
		$default_code = '';
	}
	
	
	echo <<<HTML
<script type='text/javascript'>
{$default_code}
{$code}
</script>
HTML;
}

function theme_add_cufon_code_footer(){
	echo <<<HTML
<script type='text/javascript'>
HTML;
if(theme_get_option('font','cufon_enabled')){
	echo <<<HTML
Cufon.now();
HTML;
}
	echo <<<HTML
if(typeof jQuery != 'undefined'){
if(jQuery.browser.msie && parseInt(jQuery.browser.version, 10)==8){
	jQuery(".jqueryslidemenu ul li ul").css({display:'block', visibility:'hidden'});
}
}
</script>
HTML;
}

function theme_get_superlink($link, $default=false){
	if(!empty($link)){
		$link_array = explode('||',$link);
		switch($link_array[0]){
			case 'page':
				return get_page_link($link_array[1]);
			case 'cat':
				return get_category_link($link_array[1]);
			case 'post':
				return get_permalink($link_array[1]);
			case 'portfolio':
				return get_permalink($link_array[1]);
			case 'manually':
				return $link_array[1];
		}
	}
	return $default;
}

function theme_portfolio_ajax_init(){
	if ( isset( $_SERVER['REQUEST_METHOD']) && 'POST' != $_SERVER['REQUEST_METHOD'] || !isset( $_POST['portfolioAjax'] ) ){
		return;
	}
	if($_POST['portfolioAjax'] != 'true'){
		return;
	}
	
	$options = array();
	if(isset($_POST['portfolioOptions']))
		$options =  $_POST['portfolioOptions'];
	
	if(isset($_POST['category']) && $_POST['category']!='all'){
		$options['cat'] = $_POST['category'];
	}
	if(isset($_POST['portfolioPage'])){
		$options['paged'] = intval($_POST['portfolioPage']);
	}

	if(isset($options['current'])){
		unset($options['current']);
	}
	if ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' ) {
		echo apply_filters('the_content',theme_generator('portfolio_list',$options));
	}
	exit();
}
add_action('wp_loaded', 'theme_portfolio_ajax_init');


function theme_responsive_image_init(){
	if ( isset( $_SERVER['REQUEST_METHOD']) && 'POST' != $_SERVER['REQUEST_METHOD'] || !isset( $_POST['imageAjax'] ) ){
		return;
	}
	if($_POST['imageAjax'] != 'true'){
		return;
	}
	if(!isset($_POST['thumbnail_id'])){
		return;
	}
	if(!isset($_POST['width'])){
		return;
	}
	
	$thumbnail_id = $_POST['thumbnail_id'];
	$width = intval($_POST['width']);
	$height = '';
	if(isset($_POST['height'])){
		$height = intval($_POST['height']);
	}
	
	if ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' ) {
		@header( 'Content-Type: application/html; charset=' . get_option( 'blog_charset' ) );
		$image_source = array('type'=>'attachment_id','value'=>$thumbnail_id);
		$size = array($width, $height);
		$image_src = theme_get_image_src($image_source, $size);
		echo $image_src;
	}
	exit();
}
add_action('wp_loaded', 'theme_responsive_image_init');

function theme_parse_query($query){
	if($query->is_home && 'posts' == get_option('show_on_front')){
		$query->is_paged = false;
	}
}
add_action('parse_query', 'theme_parse_query');

function theme_kenburn_video(){
	if(!isset($_GET['html5iframe']) || $_GET['html5iframe'] != 'true'){
		return;
	}
	if(!isset($_GET['sliderid'])){
		return;
	}

	include(get_template_directory().'/includes/html5video.php');
	
	exit();
}
add_action('init', 'theme_kenburn_video');

function theme_slide_preview(){
	if(!isset($_GET['layerpreview']) || $_GET['layerpreview'] != 'true'){
		return;
	}
	if(!isset($_GET['sliderid'])){
		return;
	}	
	include(get_template_directory().'/includes/sliderpreview.php');
	
	exit();
}

add_action('wp_loaded', 'theme_slide_preview');


function theme_maybe_process_contact_form(){
	$submit_contact_form = isset($_POST["theme_contact_form_submit"]) ? $_POST["theme_contact_form_submit"] : 0;
	if($submit_contact_form){
		require_once(THEME_FUNCTIONS.'/email.php');
		exit;
	}
}
add_action('wp', 'theme_maybe_process_contact_form', 9);

function theme_exclude_from_search(){
	global $wp_post_types;
	$post_types = theme_get_option('advanced','exclude_from_search');
	if(!empty($post_types)){
		foreach($post_types as $post_type){
			$wp_post_types[$post_type]->exclude_from_search = true;
		}
	}
}
add_action('wp_loaded', 'theme_exclude_from_search');

add_filter( 'wp_setup_nav_menu_item', 'theme_setup_nav_menu_itemu' );
function theme_setup_nav_menu_itemu($menu_item){
	$menu_item->icon = get_post_meta( $menu_item->ID, 'menu-item-icon' , true );
	if(!$menu_item->icon){
		$menu_item->icon = '';
	}
	$menu_item->icon_color = get_post_meta( $menu_item->ID, 'menu-item-icon-color' , true );
	if(!$menu_item->icon_color){
		$menu_item->icon_color = '';
	}
	$menu_item->not_show_in_mobile = get_post_meta( $menu_item->ID, 'not-show-in-mobile' , true );

	if(!is_admin() && $menu_item->not_show_in_mobile){
		$menu_item->classes[]='not_show_in_mobile';
	}

	return $menu_item;
}
class Theme_Walker_Nav_Menu extends Walker_Nav_Menu {
	function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output ) {
		if ( !$element )
			return;

		$id_field = $this->db_fields['id'];

		if ( is_object( $args[0] ) ) {
			$args[0]->has_children = ! empty( $children_elements[$element->$id_field] );
		}
		$args[0]->link_after ='';
		if(theme_get_option('general','enable_nav_subtitle')){
			$args[0]->link_after ='';
			if (!empty($element->description )&& ($element->post_type='nav_menu_item')&& ($element->menu_item_parent==0)){
				$description = '&nbsp;<span class="menu-subtitle">'.$element->description.'</span>';
				$args[0]->link_after = $description;
			}
		}
		$args[0]->link_before = '';
		if(isset($element->icon) && !empty($element->icon)){
			if(isset($element->icon_color) && !empty($element->icon_color)){
				$icon_color_style = ' style="color:'.$element->icon_color.'"';
			} else {
				$icon_color_style = '';
			}
			$args[0]->link_before = '<i class="icon-'.trim($element->icon).'"'.$icon_color_style.'></i>';
		}
		return parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
	}
}
class Theme_Walker_Nav_Menu_Footer extends Walker_Nav_Menu {
 	function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output ) {
		if ( !$element )
			return;

		$id_field = $this->db_fields['id'];

		if ( is_object( $args[0] ) ) {
			$args[0]->has_children = ! empty( $children_elements[$element->$id_field] );
		}
		$args[0]->link_before = '';
		if(isset($element->icon) && !empty($element->icon)){
			if(isset($element->icon_color) && !empty($element->icon_color)){
				$icon_color_style = ' style="color:'.$element->icon_color.'"';
			} else {
				$icon_color_style = '';
			}
			$args[0]->link_before = '<i class="menu-icon-footer icon-'.trim($element->icon).'"'.$icon_color_style.'></i>';
		}
		return parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
	}
}
class Theme_The_Excerpt_Length_Constructor {
	var $length;
	function __construct($length) {
		$this->length = $length;
	}
	function get_length(){
		return $this->length;
	}
	function trim($text){
		$excerpt_length = apply_filters('excerpt_length', 55);
		
		$excerpt_more = apply_filters('excerpt_more', ' ' . '...');
		$text = theme_strcut( $text, $excerpt_length, $excerpt_more );
		return $text;
	}
}


function theme_add_script_to_head(){
	if(theme_get_option('font','cufon_enabled')){
		theme_add_cufon_code();
	}
?>
<script type="text/javascript">
<?php
	$theme_js='';
	$sticky_header='';
	$sticky_footer='';
	$responsive_image=theme_get_option('advanced','responsive_resize');

	$image_responsive=$responsive_image?'true':'false';
	$image_url=THEME_IMAGES;
	$theme_url=THEME_URI;

	$fancybox_skin = theme_get_option('advanced','fancybox_skin');
	$fancybox_title_type = theme_get_option('advanced','fancybox_title_type');
	$fancybox_width = theme_get_option('advanced','fancybox_width');
	$fancybox_height = theme_get_option('advanced','fancybox_height');
	$fancybox_autoSize = theme_get_option('advanced','fancybox_autoSize')?'true':'false';
	$fancybox_autoWidth = theme_get_option('advanced','fancybox_autoWidth')?'true':'false';
	$fancybox_autoHeight = theme_get_option('advanced','fancybox_autoHeight')?'true':'false';
	$fancybox_fitToView = theme_get_option('advanced','fancybox_fitToView')?'true':'false';
	//$fancybox_fitToView_mode = theme_get_option('advanced','fancybox_fitToView_mode')?'true':'false';
	$fancybox_aspectRatio = theme_get_option('advanced','fancybox_aspectRatio')?'true':'false';
	$fancybox_arrows = theme_get_option('advanced','fancybox_arrows')?'true':'false';
	$fancybox_closeBtn = theme_get_option('advanced','fancybox_closeBtn')?'true':'false';
	$fancybox_closeClick = theme_get_option('advanced','fancybox_closeClick')?'true':'false';
	$fancybox_nextClick = theme_get_option('advanced','fancybox_nextClick')?'true':'false';
	$fancybox_autoPlay = theme_get_option('advanced','fancybox_autoPlay')?'true':'false';
	$fancybox_playSpeed = theme_get_option('advanced','fancybox_playSpeed');
	$fancybox_preload = theme_get_option('advanced','fancybox_preload');
	$fancybox_loop = theme_get_option('advanced','fancybox_loop')?'true':'false';
	$fancybox_thumbnail = theme_get_option('advanced','fancybox_thumbnail')?'true':'false';
	$fancybox_thumbnail_width = theme_get_option('advanced','fancybox_thumbnail_width');
	$fancybox_thumbnail_height = theme_get_option('advanced','fancybox_thumbnail_height');
	$fancybox_thumbnail_position = theme_get_option('advanced','fancybox_thumbnail_position');

	$theme_js.= <<<JS
var image_url='{$image_url}';
var theme_url='{$theme_url}';
var responsve_image_resize={$image_responsive};

JS;

	$theme_js.= <<<JS
var fancybox_options = {
	skin:'{$fancybox_skin}',
	title_type:'{$fancybox_title_type}',
	width:{$fancybox_width},
	height:{$fancybox_height},
	autoSize:{$fancybox_autoSize},
	autoWidth:{$fancybox_autoWidth},
	autoHeight:{$fancybox_autoHeight},
	fitToView:{$fancybox_fitToView},
	aspectRatio:{$fancybox_aspectRatio},
	arrows:{$fancybox_arrows},
	closeBtn:{$fancybox_closeBtn},
	closeClick:{$fancybox_closeClick},
	nextClick:{$fancybox_nextClick},
	autoPlay:{$fancybox_autoPlay},
	playSpeed:{$fancybox_playSpeed},
	preload:{$fancybox_preload},
	loop:{$fancybox_loop},
	thumbnail:{$fancybox_thumbnail},
	thumbnail_width:{$fancybox_thumbnail_width},
	thumbnail_height:{$fancybox_thumbnail_height},
	thumbnail_position:'{$fancybox_thumbnail_position}'
};

JS;
	$pie_progress_bar_color = theme_get_option('color', 'pie_progress_bar_color');
	if(!$pie_progress_bar_color){
		$pie_progress_bar_color = theme_get_option('color', 'primary');
	}
	$pie_progress_track_color = theme_get_option('color', 'pie_progress_track_color');

	$theme_js.=  <<<JS
var pie_progress_bar_color = "{$pie_progress_bar_color}",
	pie_progress_track_color = "{$pie_progress_track_color}";

JS;
	$gmap_api_key = theme_get_option('advanced', 'gmap_api_key');
	if (!empty($gmap_api_key)) {
	$theme_js.=  <<<JS
var gmap_api_key = "{$gmap_api_key}";

JS;
	}

	$nav2select_indentString = esc_attr(theme_get_option('advanced','nav2select_indentString'));
	$nav2select_defaultText = esc_attr(theme_get_option('advanced','nav2select_defaultText'));
	$theme_js.=  <<<JS
var nav2select_indentString = "{$nav2select_indentString}";
var nav2select_defaultText = "{$nav2select_defaultText}";

JS;
	$responsive_menu_location = esc_attr(theme_get_responsive_menu_header_location());
	$button_position_type=theme_get_option_from_responsive_menu('button_position_type');
	if(!empty($responsive_menu_location)){
		$theme_js.=  <<<JS
var responsive_menu_location = "{$responsive_menu_location}";
var responsive_menu_position = "{$button_position_type}";

JS;
	}
	if(theme_get_option('general','sticky_header')) {
		$sticky_header=theme_get_option('advanced','sticky_header_target');
	}
	if(!empty($sticky_header)){
		$theme_js.=  <<<JS
var sticky_header_target = "{$sticky_header}";

JS;
	}		
	if((is_front_page() && theme_get_option('footer','sticky_footer')) || (theme_get_inherit_option(get_queried_object_id(), '_sticky_footer', 'footer','sticky_footer'))) {
		$sticky_footer=theme_get_option('advanced','sticky_footer_target');
	}
	if(!empty($sticky_footer)) {
		$theme_js.=  <<<JS
var sticky_footer_target = "{$sticky_footer}";

JS;
	}
	$minify=theme_get_option('advanced','theme_minify_js');
	if ($minify) $theme_js=theme_minify_css_js($theme_js,true);
	echo $theme_js;
?>
</script>
<?php
	if(theme_get_option('general','analytics_position')=='header'){
		echo theme_google_analytics_code();
	}

	if(function_exists('is_shop') && is_shop()){
		$post_id = wc_get_page_id( 'shop' );
	} else {
		$post_id = get_queried_object_id();
	}
	//Global CSS
	$css='';
	$custom_js='';
	$background = theme_get_option('background');
	if(!empty($background['header_image'])){
		$background['header_image'] = theme_get_image_src($background['header_image']);
		if ($background['header_attachment']=='fixed') $background['header_attachment']=theme_reset_attachment_fixed_for_ios($background['header_attachment']);
		$header_image = <<<CSS
	background-image: url('{$background['header_image']}');
	background-repeat: {$background['header_repeat']};
	background-position: {$background['header_position_x']} {$background['header_position_y']};
	background-attachment: {$background['header_attachment']};
	-webkit-background-size: {$background['header_size']};
	-moz-background-size: {$background['header_size']};
	-o-background-size: {$background['header_size']};
	background-size: {$background['header_size']};
CSS;
		if($post_id){
			$bg_color = get_post_meta($post_id, '_header_background_color', true);
			if(!empty($bg_color)){
				if ($bg_color!='transparent') {
					$keep_global_background_image=get_post_meta($post_id, '_header_keep_global_image', true);
					if ($keep_global_background_image=='false') $header_image='';
				}
			}
		}
	}else{
		$header_image = '';
	}
	if(!empty($background['feature_image'])){
		$background['feature_image'] = theme_get_image_src($background['feature_image']);
		if ($background['feature_attachment']=='fixed') $background['feature_attachment']=theme_reset_attachment_fixed_for_ios($background['feature_attachment']);
		$feature_image = <<<CSS
	background-image: url('{$background['feature_image']}');
	background-repeat: {$background['feature_repeat']};
	background-position: {$background['feature_position_x']} {$background['feature_position_y']};
	background-attachment: {$background['feature_attachment']};
	-webkit-background-size: {$background['feature_size']};
	-moz-background-size: {$background['feature_size']};
	-o-background-size: {$background['feature_size']};
	background-size: {$background['feature_size']};
CSS;
		if($post_id){
			$bg_color = get_post_meta($post_id, '_introduce_background_color', true);
			if(!empty($bg_color)){
				if ($bg_color!='transparent') {
					$keep_global_background_image=get_post_meta($post_id, '_feature_keep_global_image', true);
					if ($keep_global_background_image=='false') $feature_image='';
				}
			}
		}
	}else{
		$feature_image = '';
	}
	if(!empty($background['page_image'])){
		$background['page_image'] = theme_get_image_src($background['page_image']);
		if ($background['page_attachment']=='fixed') $background['page_attachment']=theme_reset_attachment_fixed_for_ios($background['page_attachment']);
		$page_image = <<<CSS
	background-image: url('{$background['page_image']}');
	background-repeat: {$background['page_repeat']};
	background-position: {$background['page_position_x']} {$background['page_position_y']};
	background-attachment: {$background['page_attachment']};
	-webkit-background-size: {$background['page_size']};
	-moz-background-size: {$background['page_size']};
	-o-background-size: {$background['page_size']};
	background-size: {$background['page_size']};
CSS;
		$page_bottom_image = <<<CSS
#page_bottom{
	background:none;
}
CSS;
		if($post_id){
			$bg_color = get_post_meta($post_id, '_page_background_color', true);
			if(!empty($bg_color)){
				if ($bg_color!='transparent') {
					$keep_global_background_image=get_post_meta($post_id, '_page_keep_global_image', true);
					if ($keep_global_background_image=='false') {
						$page_bottom_image='';
						$page_image='';
					}
				}
			}
		}
	}else{
		$page_image = '';
		$page_bottom_image = '';
	}
	if(!empty($background['footer_image'])){
		$background['footer_image'] = theme_get_image_src($background['footer_image']);
		if ($background['footer_attachment']=='fixed') $background['footer_attachment']=theme_reset_attachment_fixed_for_ios($background['footer_attachment']);
		$footer_image = <<<CSS
	background-image: url('{$background['footer_image']}');
	background-repeat: {$background['footer_repeat']};
	background-position: {$background['footer_position_x']} {$background['footer_position_y']};
	background-attachment: {$background['footer_attachment']};
	-webkit-background-size: {$background['footer_size']};
	-moz-background-size: {$background['footer_size']};
	-o-background-size: {$background['footer_size']};
	background-size: {$background['footer_size']};
CSS;
		if($post_id){
			$bg_color = get_post_meta($post_id, '_footer_background_color', true);
			if(!empty($bg_color)){
				if ($bg_color!='transparent') {
					$keep_global_background_image=get_post_meta($post_id, '_footer_keep_global_image', true);
					if ($keep_global_background_image=='false') $footer_image='';
				}
			}
		}
	}else{
		$footer_image = '';
	}
	if(!empty($header_image)){
	$css .= <<<CSS

#header {
{$header_image}
}
CSS;
	}
	if(!empty($feature_image)){
	$css .= <<<CSS

.no-gradient #feature, .has-gradient #feature {
{$feature_image}
}
CSS;
	}
	if(!empty($page_image)){
	$css .= <<<CSS

#page {
{$page_image}
}
{$page_bottom_image}
CSS;
	}
	if(!empty($footer_image)){
	$css .= <<<CSS

.no-gradient #footer, .has-gradient #footer {
{$footer_image}
}
CSS;
	}
	
	if($post_id){
		//$css = '';
		if(theme_get_option('advanced','complex_class')){
			$complex_prefix='theme_';
		} else $complex_prefix='';
	
		$background = theme_get_option('background');
		
		$header_bg_color = get_post_meta($post_id, '_header_background_color', true);
		$header_css = '';
		if(!empty($header_bg_color)){
			$header_css .= theme_color_fallback('background-color',$header_bg_color);
		}
		if(!empty($header_css)) {
			$css .= <<<CSS
#header {
{$header_css}
}

CSS;
		}
		
		$page_css = '';
		$page_image = get_post_meta($post_id, '_page_background_image', true);
		$image_target = get_post_meta($post_id, '_page_background_target', true);
		$img_target=$image_target;
		if (empty($image_target)) $image_target='#page';
		if ($image_target=='#footer') $image_target= '.no-gradient #footer, .has-gradient #footer';
		if(!empty($page_image)){
			$page_image = theme_get_image_src($page_image);
			$page_repeat = get_post_meta($post_id, '_page_background_repeat', true);
			$page_position_x = get_post_meta($post_id, '_page_background_position_x', true);
			$page_position_y = get_post_meta($post_id, '_page_background_position_y', true);
			$page_attachment = get_post_meta($post_id, '_page_background_attachment', true);
			if ($page_attachment=='fixed') $page_attachment=theme_reset_attachment_fixed_for_ios($page_attachment);
			$page_size = get_post_meta($post_id, '_page_background_size', true);

			$page_css .= <<<CSS
	background-image: url('{$page_image}');
	background-repeat: {$page_repeat};
	background-position: {$page_position_x} {$page_position_y};
	background-attachment: {$page_attachment};
	-webkit-background-size: {$page_size};
	-moz-background-size: {$page_size};
	-o-background-size: {$page_size};
	background-size: {$page_size};
CSS;
		} 
	if(!empty($page_css)) {
			$css .= <<<CSS
$image_target {
{$page_css}
}

CSS;
		}

		$page_color_css="";		
		$page_color = get_post_meta($post_id, '_page_background_color', true);
		if(!empty($page_color)){
		$page_color_css .= theme_color_fallback('background-color',$page_color);
				$css .= <<<CSS
#page {
{$page_color_css}
}

CSS;
		}

		$feature_image = get_post_meta($post_id, '_feature_background_image', true);
		$feature_css = '';
		if(!empty($feature_image)){
			$feature_image = theme_get_image_src($feature_image);
			$feature_repeat = get_post_meta($post_id, '_feature_background_repeat', true);
			$feature_position_x = get_post_meta($post_id, '_feature_background_position_x', true);
			$feature_position_y = get_post_meta($post_id, '_feature_background_position_y', true);
			$feature_attachment = get_post_meta($post_id, '_feature_background_attachment', true);
			if ($feature_attachment=='fixed') $feature_attachment=theme_reset_attachment_fixed_for_ios($feature_attachment);
			$feature_size = get_post_meta($post_id, '_feature_background_size', true);

			$feature_css .= <<<CSS
	background-image: url('{$feature_image}');
	background-repeat: {$feature_repeat};
	background-position: {$feature_position_x} {$feature_position_y};
	background-attachment: {$feature_attachment};
	-webkit-background-size: {$feature_size};
	-moz-background-size: {$feature_size};
	-o-background-size: {$feature_size};
	background-size: {$feature_size};
CSS;
		}
		$feature_color = get_post_meta($post_id, '_introduce_background_color', true);
		if(!empty($feature_color)){
			$feature_css .= theme_color_fallback('background-color',$feature_color);
		}
		if(!empty($feature_css)) {
			$css .= <<<CSS
.no-gradient #feature, .has-gradient #feature {
{$feature_css}
}

CSS;
		}

		$footer_bg_color = get_post_meta($post_id, '_footer_background_color', true);
		$footer_css = '';
		if(!empty($footer_bg_color)){
			$footer_css .= theme_color_fallback('background-color',$footer_bg_color);
		}
		if(!empty($footer_css)) {
			$css .= <<<CSS
.no-gradient #footer, .has-gradient #footer {
{$footer_css}
}

CSS;
		}
		
$bg_color = get_post_meta($post_id, '_page_background_color', true);

if (!empty($bg_color)) {
		$css .= <<<CSS
ul.{$complex_prefix}vertical_tabs li a.current, ul.{$complex_prefix}vertical_tabs a:hover
CSS;
	if (!empty($color['verticaltab_current_bg'])) $css .= theme_color_fallback('background-color',$color['verticaltab_current_bg']);
	else if (!empty($color['verticaltab_bg'])) $css .= theme_color_fallback('background-color',$color['verticaltab_bg']);
	else $css .= theme_color_fallback('background-color',$bg_color);
$css .= <<<CSS
}
ul.{$complex_prefix}mini_tabs li a.current, ul.{$complex_prefix}mini_tabs a:hover {
CSS;
	if (!empty($color['minitab_current_bg'])) $css .= theme_color_fallback('background-color',$color['minitab_current_bg']);
	else if (!empty($color['minitab_bg'])) $css .= theme_color_fallback('background-color',$color['minitab_bg']);
	else $css .= theme_color_fallback('background-color',$bg_color);
$css .= <<<CSS
}
CSS;
}
		$page_inner_color = get_post_meta($post_id, '_page_inner_background_color', true);
		if(!empty($page_inner_color)){
			$css .= <<<CSS
#page .inner ul.{$complex_prefix}vertical_tabs li a.current, 
#page .inner ul.{$complex_prefix}vertical_tabs a:hover {
CSS;
	if (!empty($color['verticaltab_current_bg'])) $css .= theme_color_fallback('background-color',$color['verticaltab_current_bg']);
	else if (!empty($color['verticaltab_bg'])) $css .= theme_color_fallback('background-color',$color['verticaltab_bg']);
	else $css .= theme_color_fallback('background-color',$page_inner_color);
$css .= <<<CSS
}
#page .inner ul.{$complex_prefix}mini_tabs li a.current, 
#page .inner ul.{$complex_prefix}mini_tabs a:hover {
CSS;
	if (!empty($color['minitab_current_bg'])) $css .= theme_color_fallback('background-color',$color['minitab_current_bg']);
	else if (!empty($color['minitab_bg'])) $css .= theme_color_fallback('background-color',$color['minitab_bg']);
	else $css .= theme_color_fallback('background-color',$page_inner_color);
$css .= <<<CSS
}
#page .inner {
CSS;
			$css .= theme_color_fallback('background-color',$page_inner_color);
			$css .= <<<CSS
}

CSS;
		}
		$feature_title_color = get_post_meta($post_id, '_feature_title_color', true);
		if(!empty($feature_title_color) && $feature_title_color != "transparent"){
			$css .= <<<CSS
#feature h1 {
CSS;
			$css .= theme_color_fallback('color',$feature_title_color);
			$css .= <<<CSS
}

CSS;
		}

		$feature_introduce_color = get_post_meta($post_id, '_feature_introduce_color', true);
		if(!empty($feature_introduce_color) && $feature_introduce_color != "transparent"){
			$css .= <<<CSS
.feature-introduce .meta-icon,
#introduce, #introduce a {
CSS;
			$css .= theme_color_fallback('color',$feature_introduce_color);
			$css .= <<<CSS
}

CSS;
		}
		$page_color = get_post_meta($post_id, '_page_color', true);
		if(!empty($page_color) && $page_color != "transparent"){
			$css .= <<<CSS
#page {
CSS;
			$css .= theme_color_fallback('color',$page_color);
			$css .= <<<CSS
}

CSS;
		}

		$footer_color = get_post_meta($post_id, '_footer_color', true);
		if(!empty($footer_color) && $footer_color != "transparent"){
			$css .= <<<CSS
#footer {
CSS;
			$css .= theme_color_fallback('color',$footer_color);
			$css .= <<<CSS
}

CSS;
		}

		$footer_title_color = get_post_meta($post_id, '_footer_title_color', true);
		if(!empty($footer_title_color) && $footer_title_color != "transparent"){
			$css .= <<<CSS
#footer h3.widgettitle {
CSS;
			$css .= theme_color_fallback('color',$footer_title_color);
			$css .= <<<CSS
}

CSS;
		}
		
		$template_landing_page = is_page_template('template_land_page.php');
		if (!$template_landing_page) {
			$nav2select = theme_get_option('advanced','nav2select');
			$nav2select_min_one = $nav2select - 1;
			$template_landing_page = is_page_template('template_land_page.php');

			if ($nav2select > 768) {
				$header_height_mobile = get_post_meta($post_id, '_header_height_mobile', true);
				if ($header_height_mobile=='0') $header_height_mobile='auto';
				if ($header_height_mobile==-1) $header_height_mobile = theme_get_option('general','header_height_mobile');
				if ($header_height_mobile>0 || $header_height_mobile=='auto') {
					if (is_numeric($header_height_mobile)) $header_height_mobile=$header_height_mobile.'px';
					$css .= <<<CSS
/*  let us set the header height correctly to : {$header_height_mobile} */
@media only screen and (min-width:768px) and (max-width: {$nav2select_min_one}px) {
	.responsive #header .inner {
		height: {$header_height_mobile};
	}
}
CSS;
				}
			}
		}

		$custom_css = get_post_meta($post_id, '_custom_css', true);
		if(!empty($custom_css)){
			$css .= <<<CSS
{$custom_css}

CSS;
		}

		$custom_js = stripslashes(get_post_meta($post_id, '_custom_js', true));
	}
	if(!empty($css)){
		$minify=theme_get_option('advanced','theme_minify');
		if ($minify) $css=theme_minify_css_js($css);
		echo  <<<CSS
<style id="theme-dynamic-style" type="text/css">
{$css}
</style>

CSS;
		}
	if(!empty($custom_js)){
		$minify=theme_get_option('advanced','theme_minify_js');
		if ($minify) $custom_js=theme_minify_css_js($custom_js,true);
		echo stripslashes($custom_js);
	}
}
add_action( 'wp_head', 'theme_add_script_to_head');

if( ! function_exists("theme_add_scripts_to_footer") ){
	function theme_add_scripts_to_footer() {
		global $theme_footer_js,$theme_footer_css;
		if (!empty($theme_footer_css)) {
			$minify=theme_get_option('advanced','theme_minify');
			if ($minify) $theme_footer_css=theme_minify_css_js($theme_footer_css);
			echo '<style rel="stylesheet" id="theme-dynamic-footer-style" type="text/css" media="all">';
			echo  $theme_footer_css;
			echo '</style>
';
		}
		if (!empty($theme_footer_js)) {
			$minify=theme_get_option('advanced','theme_minify_js');
			if ($minify) $theme_footer_js=theme_minify_css_js($theme_footer_js,true);
			echo '<script type="text/javascript">';
			echo $theme_footer_js;
			echo '</script>';
		}
	}
}
add_action('wp_footer','theme_add_scripts_to_footer',100);

function theme_add_css_to_footer($css) {
	global $theme_footer_css;
	$theme_footer_css.=$css;
}

function theme_add_js_to_footer($js) {
	global $theme_footer_js;
	$theme_footer_js.=$js;
}

if('wp-signup.php' == basename($_SERVER['PHP_SELF'])){
	add_action( 'wp_head', 'theme_wpmu_signup_stylesheet',1 );
	function theme_wpmu_signup_stylesheet() {
		remove_action( 'wp_head', 'wpmu_signup_stylesheet');
		?>
		<style type="text/css">
			.mu_register { margin:0 auto; }
			.mu_register form { margin-top: 2em; }
			.mu_register .error,.mu_register .mu_alert { 
				-webkit-border-radius: 1px;
				-moz-border-radius: 1px;
				border-radius: 1px;
				border: 1px solid #bbb;
				padding:10px;
				margin-bottom: 20px;
			}
			.mu_register .error {
				background: #FDE9EA;
				color: #A14A40;
				border-color: #FDCED0;
			}
			.mu_register input[type="submit"],
				.mu_register #blog_title,
				.mu_register #user_email,
				.mu_register #blogname,
				.mu_register #user_name { width:100%; font-size: 24px; margin:5px 0; }
			.mu_register .prefix_address,
				.mu_register .suffix_address {font-size: 18px;display:inline; }
			.mu_register label { font-weight:700; font-size:15px; display:block; margin:10px 0; }
			.mu_register label.checkbox { display:inline; }
			.mu_register .mu_alert { 
				background: #FFF9CC;
				color: #736B4C;
				border-color: #FFDB4F;
			}
		</style>
		<?php
	}


	// before wp-signup.php and before get_header()
	add_action('before_signup_form', 'theme_before_signup_form');
	function theme_before_signup_form () {

		$output = '<div id="feature">';
		$output .= '<div class="top_shadow"></div>';
		$output .= '<div class="inner">';
		$output .= '<h1>'.__('Sign Up Now','striking-r').'</h1>';
		$output .= '</div>';
		$output .= '<div class="bottom_shadow"></div>';
		$output .= '</div>';
		
		$output .= '<div id="page">';
		$output .= '<div class="inner">';
		echo $output;
	}

	// after wp-signup.php and before get_footer()
	add_action('after_signup_form', 'theme_after_signup_form');
	function theme_after_signup_form () {
		echo '</div>';
		echo '</div>';
	}
}

function theme_strcut($str, $length, $extra = ''){
	if ( function_exists('mb_strlen') ) {
		if ( mb_strlen($str) > $length ) {
			return mb_substr($str, 0, $length).$extra;
		}
	} else {
		if ( strlen($str) > $length ) {
			return substr($str, 0, $length).$extra;
		}
	}
	return $str;
}



function theme_get_youtube_thumbnail_url( $id ) {
	$maxres = 'http://img.youtube.com/vi/' . $id . '/maxresdefault.jpg';
	$response = wp_remote_head( $maxres );
	if ( !is_wp_error( $response ) && $response['response']['code'] == '200' ) {
		$result = $maxres;
	} else {
		$result = 'http://img.youtube.com/vi/' . $id . '/0.jpg';
	}
	return $result = 'http://img.youtube.com/vi/' . $id . '/0.jpg';
	return $result;
}

function theme_get_vimeo_thumbnail_url($id){
	$request = "http://vimeo.com/api/oembed.json?url=http%3A//vimeo.com/$id";
	$response = wp_remote_get( $request, array( 'sslverify' => false ) );
	if( is_wp_error( $response ) ) {
		$result = false;
	} elseif ( $response['response']['code'] == 404 ) {
		$result = false;
	} elseif ( $response['response']['code'] == 403 ) {
		$result = false;
	} else {
		$result = json_decode( $response['body'] );
		$result = $result->thumbnail_url;
	}

	return $result;
}

function theme_get_dailymotion_thumbnail_url( $id ) {
	$request = "https://api.dailymotion.com/video/$id?fields=thumbnail_url";
	$response = wp_remote_get( $request, array( 'sslverify' => false ) );
	if( is_wp_error( $response ) ) {
		$result = false;
	} else {
		$result = json_decode( $response['body'] );
		$result = $result->thumbnail_url;
	}
	return $result;
}


function theme_get_video_provider($url){
	$youtube = '/(youtube\.com|youtu\.be|youtube-nocookie\.com)\/(watch\?v=|v\/|u\/|embed\/?)?(videoseries\?list=(.*)|[\w-]{11}|\?listType=(.*)&list=(.*)).*/i';
	$vimeo = '/(?:vimeo(?:pro)?.com)\/(?:[^\d]+)?(\d+)(?:.*)/';
	$dailymotion = '/dailymotion.com\/video\/(.*)\/?(.*)/';

	preg_match($youtube, $url, $matches);

	if(!empty($matches)){
		return array(
			'provider' => 'youtube',
			'id' => $matches[3],
		);
	}

	preg_match($vimeo, $url, $matches);

	if(!empty($matches)){
		return array(
			'provider' => 'vimeo',
			'id' => $matches[1],
		);
	}

	preg_match($dailymotion, $url, $matches);

	if(!empty($matches)){
		return array(
			'provider' => 'dailymotion',
			'id' => $matches[1],
		);
	}

	return false;
}

if( ! function_exists("theme_get_template_path") ){
function theme_get_template_path($template='') {
	if (is_child_theme()) {
		$child_path=get_stylesheet_directory();
		if (file_exists($child_path.'/'.$template)) {
			return $child_path . '/'.$template;
		}
	}
	if (file_exists(THEME_DIR.'/'.$template)) return THEME_DIR . "/".$template;
	return '';
}
}

if( ! function_exists("theme_reset_attachment_fixed_for_ios") ){
	function theme_reset_attachment_fixed_for_ios($bgattachment='scroll') {
		if ($bgattachment=='fixed') {
			if (class_exists('Mobile_Detect')) {
				$mobiledetect = new Mobile_Detect;
				if( $mobiledetect->isiOS() && $mobiledetect->isiPhone() || $mobiledetect->isiOS() && $mobiledetect->isiPad() ){
					$bgattachment='scroll';
				}
			}
		}
		return $bgattachment;
	}
}

if( ! function_exists("theme_minify_css_js") ){
	/**
	 * Minify css output
	 */
	function theme_minify_css_js( $css = "", $is_js=false ){
		/* remove comments */
		$theme_minified = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);

		// remove slashed comments
		if ($is_js)	$theme_minified = preg_replace("@\s*(?<!:)//.*?$@m",'',$theme_minified); 

		// Remove whitespace
		$theme_minified = preg_replace('/\s*([{}|:;,=])\s+/', '$1', $theme_minified);

		// Remove trailing whitespace at the start
		$theme_minified = preg_replace('/\s\s+(.*)/', '$1', $theme_minified);

		/* remove tabs, spaces, newlines, etc. */
		$theme_minified = str_replace(array("\r\n","\r","\n","\t",'  ','    ','     '), '', $theme_minified);

		/* remove other spaces before/after ; */
		//$theme_minified = preg_replace(array('(( )+{)','({( )+)'), '{', $theme_minified);
		//$theme_minified = preg_replace(array('(( )+})','(}( )+)'), '}', $theme_minified);
		if (!$is_js) $theme_minified = preg_replace(array('(;( )*})'), '}', $theme_minified);
		$theme_minified = preg_replace(array('(;( )+)','(( )+;)'), ';', $theme_minified);

		return $theme_minified;
	}
}

if( ! function_exists("theme_get_retina_image") ){
	function theme_get_retina_image( $img_url = '' ) {

		if (empty($img_url)) return '';

		$info = pathinfo($img_url);

		if (!isset($info['dirname']) && !isset($info['extension'])) {
			return '';
		}
		$dir=$ext='';
		if (isset($info['dirname'])) $dir = $info['dirname'];
		if (isset($info['extension'])) $ext = $info['extension'];
		$name = wp_basename($img_url, ".$ext");

		//$img_url_retina=$dir.'/'.$name.'@2x.'.$ext;

		//1st we try to find the retina file in our own cache folder.

		if (file_exists(THEME_CACHE_IMAGES_DIR. '/'.$name.'@2x.'.$ext)) {
			$img_url_retina=THEME_CACHE_IMAGES_URI.'/'.$name.'@2x.'.$ext;
			return $img_url_retina;
		}

		//2nd if not found we try to find the retina file in the image home folder as some retina plugin could have created it.

		$dir_found=false;
		if (is_multisite()) {
			$blogid=get_current_blog_id();
			if ($blogid>1) {
				// first search old blogs.dir multisite folder structure
				$search_dir='files'; 
				$dir_found = stripos($dir, $search_dir);
				
				// second if not found then search new multisite files/blogid folder structure
				if ($dir_found==false) {
					$search_dir='sites/'.$blogid;
					$dir_found = stripos($dir, $search_dir);
				}
			}
		}
		
		if ($dir_found==false) {
			$search_dir='uploads'; 
			$dir_found = stripos($dir, $search_dir);
		}

		if ($dir_found!==false) {
			$subdir=substr($dir,$dir_found+strlen($search_dir));
			if ($subdir=='/') $subdir='';
			$upload_dir = wp_upload_dir();
			$subdir=$upload_dir["basedir"].$subdir;
			if (file_exists($subdir. '/'.$name.'@2x.'.$ext)) {
				$img_url_retina=$dir.'/'.$name.'@2x.'.$ext;
				return $img_url_retina;
			}
		}

		return '';
	}
}

if( ! function_exists("theme_get_retina_srcset") ){
	function theme_get_retina_srcset( $img_url = '' ) {
		
		$img_url_retina=theme_get_retina_image($img_url);
		if (!empty($img_url_retina)) {
			//$srcset=' srcset="'.$img_url.', '.$img_url_retina.' 2x" ';
			$srcset=' srcset="'.$img_url_retina.' 2x" ';
		} else $srcset='';
		
		return $srcset;
	}
}

if( ! function_exists("theme_get_attachment_id_from_url") ){
	function theme_get_attachment_id_from_url( $attachment_url = '' ) {

		global $wpdb;
		$attachment_id = false;

		// If there is no url, return.
		if ( '' == $attachment_url )
			return $attachment_id;

		// Get the upload directory paths
		$upload_dir_paths = wp_upload_dir();

		//Make sure the upload path base directory exists in the attachment URL, to verify that we're working with a media library image
		if ( false !== strpos( $attachment_url, $upload_dir_paths['baseurl'] ) ) {
	 
			//If this is the URL of an auto-generated thumbnail, get the URL of the original image
			$attachment_url = preg_replace( '/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $attachment_url );
	 
			//Remove the upload path base directory from the attachment URL
			$attachment_url = str_replace( $upload_dir_paths['baseurl'] . '/', '', $attachment_url );
	 
			//Finally, run a custom database query to get the attachment ID from the modified attachment URL
			$attachment_id = $wpdb->get_var( $wpdb->prepare( "SELECT wposts.ID FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta WHERE wposts.ID = wpostmeta.post_id AND wpostmeta.meta_key = '_wp_attached_file' AND wpostmeta.meta_value = '%s' AND wposts.post_type = 'attachment'", $attachment_url ) );
	 
		}
		return $attachment_id;
	}
}

if( ! function_exists("theme_get_option_from_responsive_menu") ){
	function theme_get_option_from_responsive_menu($option_name='') {
		global $wpdb;
		$option_value= '';
		$table_db_name='';
		if (!empty($option_name)) {
			if (function_exists('check_responsive_menu_pro_php_version')) {
				$table_db_name = $wpdb->prefix . "responsive_menu_pro";
			} else
			if (function_exists('check_responsive_menu_php_version')) {
				$table_db_name = $wpdb->prefix . "responsive_menu";
			}
			if (!empty($table_db_name )) {
				if($wpdb->get_var("SHOW TABLES LIKE '$table_db_name'") == $table_db_name) {
					$option_from_db = $wpdb->get_results("SELECT * FROM $table_db_name where name='{$option_name}'");
					if (is_array($option_from_db) && !empty($option_from_db['0'])) {
						$option_value=$option_from_db['0']->value;
					}
				}
			}
		}
		return $option_value;
	}
}

if( ! function_exists("theme_get_responsive_menu_shortcode_setting") ){
	function theme_get_responsive_menu_shortcode_setting() {
		$shortcode=false;
		$db_value=theme_get_option_from_responsive_menu('shortcode');
		if ($db_value=='on') $shortcode=true;
		return $shortcode;
	}
}

if( ! function_exists("theme_get_responsive_menu_shortcode") ){
	function theme_get_responsive_menu_shortcode() {
		if (function_exists('check_responsive_menu_pro_php_version')) return '[responsive_menu_pro]';
		if (function_exists('check_responsive_menu_php_version'))return '[responsive_menu]';
		return '';
	}
}


if( ! function_exists("theme_get_responsive_menu_header_location") ){
function theme_get_responsive_menu_header_location() {
	if (function_exists('check_responsive_menu_pro_php_version')||function_exists('check_responsive_menu_php_version')) {
		$responsive_menu_header_location=theme_get_option('general','responsive_menu_header_location');
		if ($responsive_menu_header_location!='body' && $responsive_menu_header_location!='manual' && $responsive_menu_header_location!='header') $responsive_menu_header_location='body';
	} else $responsive_menu_header_location='manual';
	return $responsive_menu_header_location;
}
}



if (!function_exists( 'theme_meow_retina_plugin_default_settings' )) {
	function theme_meow_retina_plugin_default_settings() {
		if (class_exists('Meow_WR2X_Core')) {
			$hide_ads = get_option( 'meowapps_hide_ads', false );
			if (!$hide_ads=='1') {
				update_option( 'meowapps_hide_ads', '1');
			}
			$keep_src = get_option( 'wr2x_picturefill_keep_src', false );
			if (!$keep_src=='1') {
				update_option( 'wr2x_picturefill_keep_src', '1');
			}
		}
	}
	theme_meow_retina_plugin_default_settings();
}

if(class_exists( 'Woocommerce' )){
	add_filter( 'woocommerce_resize_images','__return_false' );
	add_filter( 'woocommerce_background_image_regeneration','__return_false' );
}

if( ! function_exists("theme_get_calendar") ){
	function theme_get_calendar( $initial = true, $echo = true ) {
		global $wpdb, $m, $monthnum, $year, $wp_locale, $posts;

		$key = md5( $m . $monthnum . $year );
		$cache = wp_cache_get( 'theme_get_calendar', 'theme_calendar' );

		if ( $cache && is_array( $cache ) && isset( $cache[ $key ] ) ) {
			/** This filter is documented in wp-includes/general-template.php */
			$output = apply_filters( 'theme_get_calendar', $cache[ $key ] );

			if ( $echo ) {
				echo $output;
				return;
			}

			return $output;
		}

		if ( ! is_array( $cache ) ) {
			$cache = array();
		}

		// Quick check. If we have no posts at all, abort!
		if ( ! $posts ) {
			$gotsome = (bool) get_posts(array(
				'fields'=> 'ids',
				'suppress_filters'=> false,
				'posts_per_page'=> 1,
			));
			if ( ! $gotsome ) {
				$nothing_found=__('No Calendar Posts Found !','striking-r');
				// Make $nothing_found empty to still show the calendar even if there are no posts
				$nothing_found = apply_filters( 'theme_get_calendar_nothing_found', $nothing_found );
				if (!empty($nothing_found)) {
					$cache[ $key ] = $nothing_found;
					wp_cache_set( 'theme_get_calendar', $cache, 'theme_calendar' );
					if ( $echo ) {
						echo $nothing_found;
						return;
					} else   return $nothing_found;
				}
			}
		}

		if ( isset( $_GET['w'] ) ) {
			$w = (int) $_GET['w'];
		}
		// week_begins = 0 stands for Sunday
		$week_begins = (int) get_option( 'start_of_week' );
		$ts = current_time( 'timestamp' );

		// Let's figure out when we are
		if ( ! empty( $monthnum ) && ! empty( $year ) ) {
			$thismonth = zeroise( intval( $monthnum ), 2 );
			$thisyear = (int) $year;
		} elseif ( ! empty( $w ) ) {
			// We need to get the month from MySQL
			$thisyear = (int) substr( $m, 0, 4 );
			//it seems MySQL's weeks disagree with PHP's
			$d = ( ( $w - 1 ) * 7 ) + 6;
			$thismonth = $wpdb->get_var("SELECT DATE_FORMAT((DATE_ADD('{$thisyear}0101', INTERVAL $d DAY) ), '%m')");
		} elseif ( ! empty( $m ) ) {
			$thisyear = (int) substr( $m, 0, 4 );
			if ( strlen( $m ) < 6 ) {
				$thismonth = '01';
			} else {
				$thismonth = zeroise( (int) substr( $m, 4, 2 ), 2 );
			}
		} else {
			$thisyear = gmdate( 'Y', $ts );
			$thismonth = gmdate( 'm', $ts );
		}

		$unixmonth = mktime( 0, 0 , 0, $thismonth, 1, $thisyear );
		$last_day = date( 't', $unixmonth );

		$previous_sheet_posts = get_posts( 
			array(
				'post_type' => 'post',
				'post_status' => 'publish',
				'orderby' => 'date',
				'order' => 'DESC',
				'posts_per_page' => 1,
				'suppress_filters' => false,
				'date_query'=> array(
					'before' => array(
						'month' =>$thismonth,
						'year'=>$thisyear,
					),
				),
			) 
		);
		$next_sheet_posts = get_posts( 
			array(
				'post_type' => 'post',
				'post_status' => 'publish',
				'orderby' => 'date',
				'order' => 'ASC',
				'posts_per_page' => 1,
				'suppress_filters' => false,
				'date_query'=> array(
					'after' => array(
						'month' =>$thismonth,
						'year'=>$thisyear,
					),
				),
			) 
		);
		/* translators: Calendar caption: 1: month name, 2: 4-digit year */
		$calendar_caption = _x('%1$s %2$s', 'calendar caption','');
		$calendar_output = '<table id="theme-wp-calendar">
		<caption class="theme-calendar-caption">' . sprintf(
			$calendar_caption,
			$wp_locale->get_month( $thismonth ),
			date( 'Y', $unixmonth )
		) . '</caption>
		<thead class="theme-calendar-head">
		<tr class="theme-calendar-days">';

		$myweek = array();

		for ( $wdcount = 0; $wdcount <= 6; $wdcount++ ) {
			$myweek[] = $wp_locale->get_weekday( ( $wdcount + $week_begins ) % 7 );
		}

		foreach ( $myweek as $wd ) {
			$day_name = $initial ? $wp_locale->get_weekday_initial( $wd ) : $wp_locale->get_weekday_abbrev( $wd );
			$wd = esc_attr( $wd );
			$calendar_output .= "\n\t\t<th class=\"theme-calendar-day\" scope=\"col\" title=\"$wd\">$day_name</th>";
		}

		$calendar_output .= '
		</tr>
		</thead>

		<tfoot class="theme-calendar-footer">
		<tr>';

		if ( (bool) $previous_sheet_posts ) {
			$previous = (object) date_parse( $previous_sheet_posts[0]->post_date );
			$calendar_output .= "\n\t\t".'<td  class="theme-calendar-prev-next" colspan="3" id="prev"><a href="' . get_month_link( $previous->year, $previous->month ) . '">&nbsp;&nbsp;&laquo; ' .
				$wp_locale->get_month_abbrev( $wp_locale->get_month( $previous->month ) ) .
			'</a></td>';
		} else {
			$calendar_output .= "\n\t\t".'<td colspan="3" id="prev" class="pad">&nbsp;</td>';
		}

		$calendar_output .= "\n\t\t".'<td class="pad">&nbsp;</td>';

		if ( (bool) $next_sheet_posts ) {
			$next = (object) date_parse( $next_sheet_posts[0]->post_date );
			$calendar_output .= "\n\t\t".'<td class="theme-calendar-prev-next" colspan="3" id="next"><a href="' . get_month_link( $next->year, $next->month ) . '">' .
				$wp_locale->get_month_abbrev( $wp_locale->get_month( $next->month ) ) .
			' &raquo;&nbsp;&nbsp;</a></td>';
		} else {
			$calendar_output .= "\n\t\t".'<td colspan="3" id="next" class="pad">&nbsp;</td>';
		}

		$calendar_output .= '
		</tr>
		</tfoot>

		<tbody class="theme-calendar-body">
		<tr>';

		$posts_of_month_titles = array();
		$posts_of_month = get_posts( 
			array(
				'post_type' => 'post',
				'post_status' => 'publish',
				'orderby' => 'date',
				'order' => 'ASC',
				'posts_per_page' => -1,
				'suppress_filters' => false,
				'date_query'=> array(
					'month' =>$thismonth,
					'year'=>$thisyear,
				),
			)
		);
		if ( (bool) $posts_of_month ) {		
			foreach ( $posts_of_month as $post ) {
				$post_date = date_parse($post->post_date);
				$post_day = $post_date['day'];

				/** This filter is documented in wp-includes/post-template.php */
				$post_title = esc_attr( apply_filters( 'the_title', $post->post_title, $post->ID ) );

				if ( ! isset( $posts_of_month_titles[ $post_day ] ) )
				$posts_of_month_titles[ $post_day ] = array();

				$posts_of_month_titles[ $post_day ][] = $post_title;
			}
		}
		// See how much we should pad in the beginning
		$pad = calendar_week_mod( date( 'w', $unixmonth ) - $week_begins );
		if ( 0 != $pad ) {
			$calendar_output .= "\n\t\t".'<td colspan="'. esc_attr( $pad ) .'" class="pad">&nbsp;</td>';
		}

		$newrow = false;
		$daysinmonth = (int) date( 't', $unixmonth );

		for ( $day = 1; $day <= $daysinmonth; ++$day ) {
			if ( isset($newrow) && $newrow ) {
				$calendar_output .= "\n\t</tr>\n\t<tr>\n\t\t";
			}
			$newrow = false;

			if ( $day == gmdate( 'j', $ts ) &&
				$thismonth == gmdate( 'm', $ts ) &&
				$thisyear == gmdate( 'Y', $ts ) ) {
				if ( array_key_exists( $day , $posts_of_month_titles ) ) {
					$calendar_output .= '<td id="today" class="day-has-post">';
				} else $calendar_output .= '<td id="today" class="theme-calendar-is-today">';
			} else {
				if ( array_key_exists( $day , $posts_of_month_titles ) ) {
					$calendar_output .= '<td  class="day-has-post">';
				} else $calendar_output .= '<td>';
			}

			if ( array_key_exists( $day , $posts_of_month_titles ) ) {
				// any posts today?
				$date_format = date( _x( 'F j, Y', 'daily archives date format','' ), strtotime( "{$thisyear}-{$thismonth}-{$day}" ) );
				/* translators: Post calendar label. 1: Date */
				$label = sprintf( __( 'Posts published on %s','' ), $date_format);
				$calendar_output .= sprintf(
					'<a href="%s" aria-label="%s">%s</a>',
					get_day_link( $thisyear, $thismonth, $day ),
					esc_attr( $label ),
					$day
				);
			} else {
				$calendar_output .= $day;
			}
			$calendar_output .= '</td>';

			if ( 6 == calendar_week_mod( date( 'w', mktime(0, 0 , 0, $thismonth, $day, $thisyear ) ) - $week_begins ) ) {
				$newrow = true;
			}
		}

		$pad = 7 - calendar_week_mod( date( 'w', mktime( 0, 0 , 0, $thismonth, $day, $thisyear ) ) - $week_begins );
		if ( $pad != 0 && $pad != 7 ) {
			$calendar_output .= "\n\t\t".'<td class="pad" colspan="'. esc_attr( $pad ) .'">&nbsp;</td>';
		}
		$calendar_output .= "\n\t</tr>\n\t</tbody>\n\t</table>";

		$cache[ $key ] = $calendar_output;
		wp_cache_set( 'theme_get_calendar', $cache, 'theme_calendar' );

		if ( $echo ) {
			/**
			 * Filters the HTML calendar output.
			 *
			 * @since 3.0.0
			 *
			 * @param string $calendar_output HTML output of the calendar.
			 */
			echo apply_filters( 'theme_get_calendar', $calendar_output );
			return;
		}
		/** This filter is documented in wp-includes/general-template.php */
		return apply_filters( 'theme_get_calendar', $calendar_output );
	}
}