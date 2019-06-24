<?php

/*------------------------------------------*/
/*	Theme constants
/*------------------------------------------*/
$curr_theme = wp_get_theme();
$theme_version = trim($curr_theme->get('Version'));
if(!$theme_version) $theme_version = "1.0";

define ('MOM_URI' , get_template_directory_uri());
define ('MOM_DIR' , get_template_directory());
define ('MOM_JS' , MOM_URI . '/js');
define ('MOM_CSS' , MOM_URI . '/css');
define ('MOM_IMG' , MOM_URI . '/images');
define ('MOM_FW' , MOM_DIR . '/framework');
define ('MOM_CHILD_FW' , get_stylesheet_directory() . '/framework');
define ('MOM_PLUGINS', MOM_FW . '/plugins');
define ('MOM_FUN', MOM_FW . '/functions');
define ('MOM_CHILD_FUN', MOM_CHILD_FW . '/functions');
define ('MOM_WIDGETS', MOM_FW . '/widgets');
define ('MOM_TYPE', MOM_FW . '/types');
define ('MOM_SC', MOM_FW . '/shortcodes');
define ('MOM_TINYMCE', MOM_FW . '/tinymce');
define ('MOM_HELPERS', MOM_URI . '/framework/helpers');
define ('MOM_AJAX', MOM_FW . '/ajax');
define ('MOM_ADDON', MOM_FW . '/addons');
define ('MOM_ADDON_URI', MOM_URI . '/framework/addons');

//text domain
define ('MOM_TEXTDOMAIN', 'theme');

//theme data
define ('MOM_THEME_NAME', $curr_theme->get('Name'));
define ('MOM_THEME_VERSION', $theme_version);

define ('MOM_THEME_ID', 1150692);


define ('MOM_THEME_SLUG', 'goodnews'); // use this to handle activation

include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); // for use is_plugin_active functions
require_once MOM_FW. '/inc/Mobile_Detect.php';


/*------------------------------------------*/
/*	Theme Translation
/*------------------------------------------*/
if (file_exists(get_stylesheet_directory().'/languages')) {
	load_theme_textdomain( 'theme', get_stylesheet_directory().'/languages' );
	load_theme_textdomain( 'framework', get_stylesheet_directory().'/languages' );


	$locale = get_locale();
	$locale_file = get_stylesheet_directory()."/languages/$locale.php";
	if ( is_readable($locale_file) )
		require_once($locale_file);
} else {
	load_theme_textdomain( 'theme', get_template_directory().'/languages' );
	load_theme_textdomain( 'framework', get_template_directory().'/languages' );
	$locale = get_locale();
	$locale_file = get_stylesheet_directory()."/languages/$locale.php";
	if ( is_readable($locale_file) )
		require_once($locale_file);
}

/*------------------------------------------*/
/*	Mega menus
/*------------------------------------------*/
if ( file_exists( MOM_FW . '/menus/menu.php' ) ) {
	require_once( MOM_FW . '/menus/menu.php' );
}

/*------------------------------------------*/
/*	Theme dashboard
/*------------------------------------------*/
if (is_admin()) { require_once  MOM_FW . '/addons/addons.php'; }

/*------------------------------------------*/
/*	Theme Admin
/*------------------------------------------*/
if ( !class_exists( 'ReduxFramework' ) && file_exists( MOM_FW . '/options/redux/ReduxCore/framework.php' ) ) {
require_once( MOM_FW . '/options/redux/ReduxCore/framework.php' );
//require_once( MOM_FW . '/options/redux/sample/sample-config.php' );
}
if ( file_exists( MOM_FW . '/options/theme_options.php' ) ) {
require_once( MOM_FW . '/options/theme_options.php' );
}

function mom_option($option, $arr=null)
{
	if(defined('ICL_LANGUAGE_CODE')) {
		$lang = ICL_LANGUAGE_CODE;
		global $opt_name;
		global ${$opt_name};

		if($arr) {
		    if(isset(${$opt_name}[$option][$arr])) {
			return ${$opt_name}[$option][$arr];
		    }
		    } else {
		     if(isset(${$opt_name}[$option])) {
		   return ${$opt_name}[$option];
		     }
		    }

	} else {
			global $mom_options;
		if($arr) {
		    if(isset($mom_options[$option][$arr])) {
			return $mom_options[$option][$arr];
		    }
		    } else {
		     if(isset($mom_options[$option])) {
		   return $mom_options[$option];
		     }
		    }
	}

}


/*------------------------------------------*/
/*	Theme Widgets
/*------------------------------------------*/
    foreach ( glob( MOM_WIDGETS . '/*.php' ) as $file )
	{
		require_once $file;
	}

/*------------------------------------------*/
/*	Theme Plugins
/*------------------------------------------*/
require_once  MOM_FW . '/inc/sidebar_generator.php';
require_once  MOM_FW . '/inc/breadcrumbs-plus/breadcrumbs-plus.php';


/*------------------------------------------*/
/*	Theme Shortcodes
/*------------------------------------------*/
    foreach ( glob( MOM_SC . '/*.php' ) as $file )
	{
		require_once $file;
}
/*------------------------------------------*/
/*	Theme TinyMCE
/*------------------------------------------*/
if (is_admin()) {
    foreach ( glob( MOM_TINYMCE . '/*.php' ) as $file )
	{
		require_once $file;
	}
}
/*------------------------------------------*/
/*	Theme Ajax
/*------------------------------------------*/
require_once MOM_AJAX . '/ajax-full.php';

if (is_admin()) {
if (file_exists(MOM_FW . '/demo/init.php')) {
require_once MOM_FW . '/demo/init.php';
}
}

/*------------------------------------------*/
/*	Tiny MCE Custom Styles
/*------------------------------------------*/
/*

add_filter( 'mce_buttons_2', 'my_mce_buttons_2' );

function my_mce_buttons_2( $buttons ) {
    array_unshift( $buttons, 'styleselect' );
    return $buttons;
}
add_filter( 'tiny_mce_before_init', 'my_mce_before_init' );

function my_mce_before_init( $settings ) {

    $style_formats = array(

        array(
        	'title' => 'Main Title',
        	'block' => 'div',
        	'classes' => 'main_title',
        	'wrapper' => true
        ),
        array(
        	'title' => 'Sub Title',
        	'block' => 'div',
        	'classes' => 'sub_title',
        	'wrapper' => true
        ),
        array(
        	'title' => 'Main Image Frame',
        	'block' => 'div',
        	'classes' => 'main_img_frame',
        	'wrapper' => true
        ),


        array(
        	'title' => 'White Text',
        	'inline' => 'span',
		'styles' => array(
			'color' =>  '#ffffff',
			'text-shadow' => '0 1px 0 #9d9b98'
			)
		),


        array(
        	'title' => 'Dark Text Shadow',
        	'inline' => 'span',
		'styles' => array(
        		'textShadow' => '0 2px 0 #000',
			)
		),

        array(
        	'title' => 'Light Text Shadow',
        	'inline' => 'span',
		'styles' => array(
        		'textShadow' => '0 1px 0 rgba(255,255,255,0.3)',
			)
		),

        array(
        	'title' => 'Upper Case Text',
        	'inline' => 'span',
		'styles' => array(
        		'textTransform' => 'uppercase',
			)
		),
    );

    $settings['style_formats'] = json_encode( $style_formats );

    return $settings;

}
*/
/*------------------------------------------*/
/*	Tiny MCE Custom font-sizes
/*------------------------------------------*/

add_filter( 'mce_buttons', 'my_mce_buttons_1' );
function my_mce_buttons_1( $buttons ) {
    array_unshift( $buttons, 'fontsizeselect' );
    return $buttons;
}

function mom_customize_text_sizes($initArray){
   $initArray['theme_advanced_font_sizes'] = "10px,11px,12px,13px,14px,15px,16px,17px,18px,19px,20px,21px,22px,23px,24px,25px,26px,27px,28px,29px,30px,32px,48px";
   return $initArray;
}

// Assigns customize_text_sizes() to "tiny_mce_before_init" filter
add_filter('tiny_mce_before_init', 'mom_customize_text_sizes');

/*------------------------------------------*/
/*	Tiny MCE Custom Column dropdown
/*------------------------------------------*/

global $wp_version;
if ( $wp_version < 3.9 ) {
function register_momcols_dropdown( $buttons ) {
   array_push( $buttons, "momcols" );
   return $buttons;
}

function add_momcols_dropdown( $plugin_array ) {
   $plugin_array['momcols'] = get_template_directory_uri() . '/framework/shortcodes/js/cols.js';
   return $plugin_array;
}

function momcols_dropdown() {

   if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') ) {
      return;
   }

   if ( get_user_option('rich_editing') == 'true' ) {
      add_filter( 'mce_external_plugins', 'add_momcols_dropdown' );
      add_filter( 'mce_buttons_2', 'register_momcols_dropdown' );
   }

}

add_action('admin_init', 'momcols_dropdown');

} else {

add_action('admin_head', 'mom_sc_cols_list');
function mom_sc_cols_list() {
    global $typenow;
    // check user permissions
    if ( !current_user_can('edit_posts') && !current_user_can('edit_pages') ) {
   	return;
    }
    // verify the post type
    if( ! in_array( $typenow, array( 'post', 'page', 'portfolio' ) ) )
        return;
	// check if WYSIWYG is enabled
	if ( get_user_option('rich_editing') == 'true') {
		add_filter("mce_external_plugins", "mom_cols_add_tinymce_plugin");
		add_filter('mce_buttons', 'mom_cols_register_my_tc_button');
	}
}
function mom_cols_add_tinymce_plugin($plugin_array) {
   	$plugin_array['columns'] = MOM_URI . '/framework/shortcodes/js/cols-list.js';
   	return $plugin_array;
}
function mom_cols_register_my_tc_button($buttons) {
   array_push($buttons, 'columns');
   return $buttons;
}

}

/*------------------------------------------*/
/*	Theme Functions
/*------------------------------------------*/
   foreach ( glob( MOM_FUN . '/*.php' ) as $file )
	{
			if ( MOM_FUN . '/functions.php' === $file) {} else {
				require_once $file;
			}
	}

/*------------------------------------------*/
/*	Post Types
/*------------------------------------------*/
    foreach ( glob( MOM_TYPE . '/*.php' ) as $file )
	{
		require_once $file;
	}


/*------------------------------------------*/
/*	Woocommerce
/*------------------------------------------*/
if (class_exists( 'woocommerce' ) ) {
	if (file_exists(get_stylesheet_directory(). '/framework/woocommerce/woocommerce.php')) {
		include_once get_stylesheet_directory(). '/framework/woocommerce/woocommerce.php';
	} else {
		include_once MOM_FW . '/woocommerce/woocommerce.php';
	}
}
/*------------------------------------------*/
/*	Visual composer
/*------------------------------------------*/
/*
if (function_exists('vc_map') && file_exists( MOM_FW . '/organized-VCUI/organized.php' )) {
	include_once MOM_FW . '/organized-VCUI/organized.php';
}
*/
/*------------------------------------------*/
/*	Theme Translation
/*------------------------------------------*/
if (file_exists(get_stylesheet_directory().'/languages')) {
	load_theme_textdomain( 'theme', get_stylesheet_directory().'/languages' );
	load_theme_textdomain( 'framework', get_stylesheet_directory().'/languages' );


	$locale = get_locale();
	$locale_file = get_stylesheet_directory()."/languages/$locale.php";
	if ( is_readable($locale_file) )
		require_once($locale_file);
} else {
	load_theme_textdomain( 'theme', get_template_directory().'/languages' );
	load_theme_textdomain( 'framework', get_template_directory().'/languages' );
	$locale = get_locale();
	$locale_file = get_stylesheet_directory()."/languages/$locale.php";
	if ( is_readable($locale_file) )
		require_once($locale_file);
}


/*------------------------------------------*/
/*	Theme Menus
/*------------------------------------------*/
if ( function_exists( 'register_nav_menu' ) ) {
  register_nav_menus(
   array(
    'main'   => __('Main Menu', 'theme'),
    'topnav'   => __('Top Menu', 'theme'),
    'footer'   => __('Footer Menu', 'theme'),
    'mobile_main'   => __('Mobile main menu', 'theme'),
    'mobile_top'   => __('Mobile top menu', 'theme'),
   )
  );
 }

/*------------------------------------------*/
/*	Theme Support
/*------------------------------------------*/
add_action( 'after_setup_theme', 'mom_goodnews_setup' );

function mom_goodnews_setup() {
	// Adds RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );
	add_theme_support('post-thumbnails');

	add_theme_support( 'post-formats', array( 'image', 'video', 'audio', 'gallery','chat' ) );
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}

/*------------------------------------------*/
/*	Theme Sidebars
/*------------------------------------------*/
// content wrap for widgets

if ( function_exists('register_sidebar') ) {

      register_sidebar(array(
	'name' => __('Right sidebar', 'framewrok'),
        'id' => 'right-sidebar',
	'description' => __('Default right sidebar.', 'theme'),
	'before_widget' => '<div class="widget %2$s">',
	'after_widget' => '</div>',
	'before_title' => '<div class="widget-head"><h3 class="widget-title"><span>',
	'after_title' => '</span></h3></div>'
      ));

      register_sidebar(array(
	'name' => __('Left sidebar', 'framewrok'),
        'id' => 'left-sidebar',
	'description' => __('Default left sidebar.', 'theme'),
	'before_widget' => '<div class="widget %2$s">',
	'after_widget' => '</div>',
	'before_title' => '<div class="widget-head"><h3 class="widget-title"><span>',
	'after_title' => '</span></h3></div>'
      ));



// footers
      register_sidebar(array(
	'name' => __('Footer 1', 'framewrok'),
        'id' => 'footer1',
	'description' => 'footer widget.',
	'before_widget' => '<div class="widget %2$s">',
	'after_widget' => '</div>',
	'before_title' => '<div class="widget-head"><h3 class="widget-title"><span>',
	'after_title' => '</span></h3></div>'
      ));

      register_sidebar(array(
	'name' => __('Footer 2', 'framewrok'),
        'id' => 'footer2',
	'description' => 'footer widget.',
	'before_widget' => '<div class="widget %2$s">',
	'after_widget' => '</div>',
	'before_title' => '<div class="widget-head"><h3 class="widget-title"><span>',
	'after_title' => '</span></h3></div>'
      ));

      register_sidebar(array(
	'name' => __('Footer 3', 'framewrok'),
        'id' => 'footer3',
	'description' => 'footer widget.',
	'before_widget' => '<div class="widget %2$s">',
	'after_widget' => '</div>',
	'before_title' => '<div class="widget-head"><h3 class="widget-title"><span>',
	'after_title' => '</span></h3></div>'
      ));

      register_sidebar(array(
	'name' => __('Footer 4', 'framewrok'),
        'id' => 'footer4',
	'description' => 'footer widget.',
	'before_widget' => '<div class="widget %2$s">',
	'after_widget' => '</div>',
	'before_title' => '<div class="widget-head"><h3 class="widget-title"><span>',
	'after_title' => '</span></h3></div>'
      ));

      register_sidebar(array(
	'name' => __('Footer 5', 'framewrok'),
        'id' => 'footer5',
	'description' => 'footer widget.',
	'before_widget' => '<div class="widget %2$s">',
	'after_widget' => '</div>',
	'before_title' => '<div class="widget-head"><h3 class="widget-title"><span>',
	'after_title' => '</span></h3></div>'
      ));

      register_sidebar(array(
	'name' => __('Footer 6', 'framewrok'),
        'id' => 'footer6',
	'description' => 'footer widget.',
	'before_widget' => '<div class="widget %2$s">',
	'after_widget' => '</div>',
	'before_title' => '<div class="widget-head"><h3 class="widget-title"><span>',
	'after_title' => '</span></h3></div>'
      ));

 }

/*------------------------------------------*/
/*	Theme Metaboxes
/*------------------------------------------*/
require_once  MOM_FW . '/metaboxes/meta-box.php';
if ( current_user_can('moderate_comments') ) {
require_once  MOM_FW . '/metaboxes/theme.php';
}
include_once MOM_FW . '/metaboxes/momizat-class/MetaBox.php';
include_once MOM_FW . '/metaboxes/momizat-class/MediaAccess.php';

// global styles for the meta boxes
if (is_admin()) add_action('admin_enqueue_scripts', 'metabox_style');

function metabox_style() {
	wp_enqueue_style('wpalchemy-metabox', get_template_directory_uri() . '/framework/metaboxes/momizat-class/meta.css');
}
$wpalchemy_media_access = new MomizatMB_MediaAccess();

//include_once MOM_FW . '/metaboxes/momizat-class/slider-spec.php';
//include_once MOM_FW . '/metaboxes/momizat-class/portfolio-spec.php';
include_once MOM_FW . '/metaboxes/momizat-class/posts-spec.php';

/*------------------------------------------*/
/*	Review system
/*------------------------------------------*/
//Backend
if ( current_user_can('moderate_comments') ) {
include_once MOM_FW . '/review/review-spec.php';
}
//user rate
include_once MOM_FW . '/review/user_rate.php';
//frontend
include_once MOM_FW . '/review/review-system.php';

/*------------------------------------------*/
/*	Ads system
/*------------------------------------------*/
//Backend
if (!mom_option('disable_ads_system')) {
	include_once MOM_FW . '/e3lanat/ads-spec.php';
	include_once MOM_FW . '/e3lanat/ads-type.php';
	include_once MOM_FW . '/e3lanat/ads-widget.php';

	//frontend
	include_once MOM_FW . '/e3lanat/ads-system.php';
}

/*------------------------------------------*/
/*	Theme Enhancments
/*------------------------------------------*/
//shortcodes in widgets
add_filter( 'widget_text', 'shortcode_unautop');
add_filter( 'widget_text', 'do_shortcode', 11);

if(basename( $_SERVER['PHP_SELF']) == "widgets.php" ) {
    add_action( 'admin_head', 'mom_widget_scripts' );
}

function mom_widget_scripts()
{
        wp_enqueue_script(  'mom_widget_js', MOM_URI. '/framework/widgets/js/widgets.js');
}

// Add RSS feed links to <head> for posts and comments.
add_theme_support( 'automatic-feed-links' );

// This theme uses its own gallery styles.
add_filter( 'use_default_gallery_style', '__return_false' );


/*------------------------------------------*/
/*	Google Fonts
/*------------------------------------------*/
function mom_google_fonts () {
//$url = get_template_directory_uri().'/framework/webfonts.json';
//$path = get_template_directory().'/framework/webfonts.json';
//$data = file_get_contents($path);
//$json_a = json_decode($data,  true);
//
//$items = $json_a['items'];
//$i = 0;
//$faces = array();
//if (is_array($items))
//{
//foreach ($items as $item) {
//    $i++;
//    $str = $item['family'];
//    $faces[$str] = $str;
//
//}
//}
$safe_fonts = array(
			'' => 'Default',
			'arial'=>'Arial',
			'georgia' =>'Georgia',
			'arial'=>'Arial',
			'verdana'=>'Verdana, Geneva',
			'trebuchet'=>'Trebuchet',
			'times'=>'Times New Roman',
			'tahoma'=>'Tahoma, Geneva',
			'palatino'=>'Palatino',
			'helvetica'=>'Helvetica',
			'play'=>'Play',
			);

return $safe_fonts;

}


/*------------------------------------------*/
/*	Portfolio Columns
/*------------------------------------------*/

add_filter( 'manage_edit-portfolio_columns', 'add_type' );
function add_type($columns) {
    $columns['cat'] = 'Categories';
    $columns['image'] = 'Feature Image';
    return $columns;
}

add_action( 'manage_posts_custom_column', 'art_type' );
function art_type($column) {
    global $post;

    switch($column) {
        case 'cat' :
			$terms = get_the_terms( $post->ID, 'portfolio_category' );
			if ( !empty( $terms ) ) {
				$out = array();
				foreach ( $terms as $term ) {
					$out[] = sprintf( '<a href="%s">%s</a>',
						esc_url( add_query_arg( array( 'post_type' => $post->post_type, 'portfolio_category' => $term->slug ), 'edit.php' ) ),
						esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, 'portfolio_category', 'display' ) )
					);
				}
				echo join( ', ', $out );
			}
			else {
				_e( 'No Categories' );
			}
	break;

        case 'image' :
                echo '<span style="padding:2px; padding-bottom:0; margin-bottom:2px; background:#fff; border:1px solid #DFDFDF; display:inline-block;">'.get_the_post_thumbnail($post->ID, array(100,60)).'</span>';
        break;
    }
}
/*------------------------------------------*/
/*	When Theme Activated
/*------------------------------------------*/

if (isset($_GET['activated'])) {
if ($_GET['activated']){
    //wp_redirect(admin_url("themes.php?page=optionsframework"));
}

}

/* ==========================================================================
 *                Modal Box
   ========================================================================== */
// Modal box Wrap
add_action( 'admin_head', 'mom_admin_modal_box' );
function mom_admin_modal_box() { ?>
	<div class="mom_modal_box">
		<div class="mom_modal_header"><h1><?php _e('Select Icon', 'theme'); ?></h1><a class="media-modal-close" id="mom_modal_close" href="#"><span class="media-modal-icon"></span></a></div>
		<div class="mom_modal_content"><span class="mom_modal_loading"></span></div>
		<div class="mom_modal_footer"><a class="mom_modal_save button-primary" href="#"><?php _e('Save', 'theme'); ?></a></div>
	</div>
	<div class="mom_media_box_overlay"></div>
<?php }
add_action( 'admin_enqueue_scripts', 'mom_admin_global_scripts' );
function mom_admin_global_scripts () {
//Load our custom javascript file
	$Lpage = '';
	if (isset($_GET['page']) && $_GET['page'] == 'codestyling-localization/codestyling-localization.php') {
		$Lpage = true;
	}
	if ($Lpage == false) {
		wp_enqueue_script( 'mom-admin-global-script', get_template_directory_uri() . '/framework/helpers/js/admin.js' );
		wp_localize_script( 'mom-admin-global-script', 'MomCats', array(
			'url' => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( 'ajax-nonce' ),
			)
		);
	}

}
// ajax Action
add_action( 'wp_ajax_mom_loadIcon', 'mom_icon_container' );

/* ==========================================================================
 *                Wp title
   ========================================================================== */
function mom_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() ) {
		return $title;
	}

	// Add the site name.
	$title .= get_bloginfo( 'name', 'display' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) ) {
		$title = "$title $sep $site_description";
	}

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 ) {
		$title = "$title $sep " . sprintf( __( 'Page %s', 'theme' ), max( $paged, $page ) );
	}

	return $title;
}
add_filter( 'wp_title', 'mom_wp_title', 10, 2 );

/* ==========================================================================
 *                Bbpress
   ========================================================================== */
add_action( 'wp_print_styles', 'deregister_bbpress_styles', 15 );
function deregister_bbpress_styles() {
 wp_deregister_style( 'bbp-default' );
}

/* ==========================================================================
 *                Shortcodes content filter
   ========================================================================== */
/**
 * Removes mismatched </p> and <p> tags from a string
 *
 * @author Jason Lengstorf <jason@copterlabs.com>
 */
function mom_remove_crappy_markup( $string )
{
    $patterns = array(
        '#^\s*</p>#',
        '#<p>\s*$#'
    );

    return preg_replace($patterns, '', $string);
}
/* ==========================================================================
 *                Visual composer as theme
   ========================================================================== */
add_action( 'vc_before_init', 'mom_vcSetAsTheme' );
function mom_vcSetAsTheme() {
vc_set_as_theme($disable_updater = true);
}

add_action( 'vc_after_init_base', 'mom_vc_custom_rows' );
function mom_vc_custom_rows() {
    global $vc_row_layouts;
		$vc_row_layouts[] = array('cells' => '68_39', 'mask' => '226', 'title' => 'sidebar right', 'icon_class' => 'rs');
		$vc_row_layouts[] = array('cells' => '88_77', 'mask' => '230', 'title' => 'sidebar left', 'icon_class' => 'ls');
		$vc_row_layouts[] = array('cells' => '48_28_39', 'mask' => '334', 'title' => 'Both sidebars right', 'icon_class' => 'bsr');
		$vc_row_layouts[] = array('cells' => '88_99_77', 'mask' => '348', 'title' => 'Both sidebars left', 'icon_class' => 'bsl');
		$vc_row_layouts[] = array('cells' => '99_48_39', 'mask' => '342', 'title' => 'Both sidebars', 'icon_class' => 'both');
}
/* ==========================================================================
 *                Buddypress
   ========================================================================== */
if (class_exists('BP_Core_Members_Widget')) {
    function mom_unregister_pb_wp_widgets() {
        unregister_widget('BP_Core_Members_Widget');
        unregister_widget('BP_Groups_Widget');
        unregister_widget('BP_Core_Friends_Widget');
    }
    add_action( 'widgets_init', 'mom_unregister_pb_wp_widgets' );
}

if (function_exists('is_buddypress')) {
define ( 'BP_AVATAR_THUMB_WIDTH', 100 );
define ( 'BP_AVATAR_THUMB_HEIGHT', 100 );
define ( 'BP_AVATAR_FULL_WIDTH', 100 );
define ( 'BP_AVATAR_FULL_HEIGHT', 100 );

/**
 * Your theme callback function
 *
 * @see bp_legacy_theme_cover_image() to discover the one used by BP Legacy
 */
function goodnews_cover_image_callback( $params = array() ) {
    if ( empty( $params ) ) {
        return;
    }

    return '
        /* Cover image - Do not forget this part */
        #buddypress #header-cover-image {
            height: ' . $params["height"] . 'px;
            background-image: url(' . $params['cover_image'] . ');
        }
    ';
}

function goodnews_cover_image_css( $settings = array() ) {
    /**
     * If you are using a child theme, use bp-child-css
     * as the theme handel
     */
    $theme_handle = 'bp-parent-css';

    $settings['theme_handle'] = $theme_handle;

    /**
     * Then you'll probably also need to use your own callback function
     * @see the previous snippet
     */
     $settings['callback'] = 'goodnews_cover_image_callback';


    return $settings;
}
add_filter( 'bp_before_xprofile_cover_image_settings_parse_args', 'goodnews_cover_image_css', 10, 1 );
add_filter( 'bp_before_groups_cover_image_settings_parse_args', 'goodnews_cover_image_css', 10, 1 );
}
/* ==========================================================================
 *                Remove authore meta box if authors more than 1000
   ========================================================================== */

$users_count = count_users();
$users_count = $users_count['total_users'];
if ($users_count > 2000) {
function mom_remove_page_fields() {
 remove_meta_box( 'authordiv' , 'page' , 'normal' ); //removes author
 remove_meta_box( 'authordiv' , 'post' , 'normal' ); //removes author
}
add_action( 'admin_menu' , 'mom_remove_page_fields' );
}
/* ==========================================================================
 *                Remove hentry From post calsses
   ========================================================================== */
function mom_remove_from_posts_class( $classes ) {
    $classes = array_diff( $classes, array( "hentry" ) );
    return $classes;
}
add_filter( 'post_class', 'mom_remove_from_posts_class' );

/*------------------------------------------*/
/*	clear all transient if ?
/*------------------------------------------*/
function mom_clear_transients () {
	global $wpdb;
	$sql = 'DELETE FROM ' . $wpdb->options . ' WHERE option_name LIKE "_transient_%"';
	$wpdb->query($sql);
}
if(defined('ICL_LANGUAGE_CODE')) {
	add_action( 'template_redirect', 'mom_clear_transients');
}
add_action( 'save_post', 'mom_clear_transients');
add_action( 'deleted_post', 'mom_clear_transients');
add_action( 'switch_theme', 'mom_clear_transients');
add_action( 'wp_update_nav_menu', 'mom_clear_transients');
add_action( 'wp_insert_comment', 'mom_clear_transients');
add_action( 'edit_category', 'mom_clear_transients');
add_action( 'delete_category', 'mom_clear_transients');
add_action( 'add_category', 'mom_clear_transients');
add_action( 'wp_trash_post', 'mom_clear_transients');
add_action( 'untrash_post', 'mom_clear_transients');
add_action( 'delete_post', 'mom_clear_transients');

//clear transients when save or reset options
add_action('redux/options/mom_options/saved', 'mom_clear_transients');

//after update the theme
add_action('upgrader_process_complete', 'mom_clear_transients');

if (!function_exists('mom_is_woocommerce_page')) {
function mom_is_woocommerce_page () {
        if(  function_exists ( "is_woocommerce" ) && is_woocommerce()){
                return true;
        }
        $woocommerce_keys   =   array ( "woocommerce_shop_page_id" ,
                                        "woocommerce_terms_page_id" ,
                                        "woocommerce_cart_page_id" ,
                                        "woocommerce_checkout_page_id" ,
                                        "woocommerce_pay_page_id" ,
                                        "woocommerce_thanks_page_id" ,
                                        "woocommerce_myaccount_page_id" ,
                                        "woocommerce_edit_address_page_id" ,
                                        "woocommerce_view_order_page_id" ,
                                        "woocommerce_change_password_page_id" ,
                                        "woocommerce_logout_page_id" ,
                                        "woocommerce_lost_password_page_id" ) ;
        foreach ( $woocommerce_keys as $wc_page_id ) {
                if ( get_the_ID () == get_option ( $wc_page_id , 0 ) ) {
                        return true ;
                }
        }
        return false;
}
}
