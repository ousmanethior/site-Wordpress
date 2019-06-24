<?php
/*
 * Thank to Shortcode Empty Paragraph Fix (http://www.johannheyne.de/wordpress/shortcode-empty-paragraph-fix/)
 */
function theme_shortcode_paragraph_insertion_fix($content) {  
    $tagregexp = '(?:lightbox|button|icon|icon_link|highlight)';
    $pattern =
		  '/'
		. '<p>'                              // Opening paragraph
		. '\\s*+'                            // Optional leading whitespace
		. '('                                // 1: The shortcode
		.     '\\['                          // Opening bracket
		.     "($tagregexp)"                 // 2: Shortcode name
		.     '\\b'                          // Word boundary
		                                     // Unroll the loop: Inside the opening shortcode tag
		.     '[^\\]\\/]*'                   // Not a closing bracket or forward slash
		.     '(?:'
		.         '\\/(?!\\])'               // A forward slash not followed by a closing bracket
		.         '[^\\]\\/]*'               // Not a closing bracket or forward slash
		.     ')*?'
		.     '(?:'
		.         '\\/\\]'                   // Self closing tag and closing bracket
		.     '|'
		.         '\\]'                      // Closing bracket
		.         '(?:'                      // Unroll the loop: Optionally, anything between the opening and closing shortcode tags
		.             '[^\\[]*+'             // Not an opening bracket
		.             '(?:'
		.                 '\\[(?!\\/\\2\\])' // An opening bracket not followed by the closing shortcode tag
		.                 '[^\\[]*+'         // Not an opening bracket
		.             ')*+'
		.             '\\[\\/\\2\\]'         // Closing shortcode tag
		.         ')?'
		.     ')'
		. ')'
		. '\\s*+'                            // optional trailing whitespace
		. '<\\/p>'                           // closing paragraph
		. '/s';

	$content = preg_replace( $pattern, '<p> $1 </p>', $content );
	$pattern =
		  '/'
		. '('                                // 1: The shortcode
		.     '\\['                          // Opening bracket
		.     "($tagregexp)"                 // 2: Shortcode name
		.     '\\b'                          // Word boundary
		                                     // Unroll the loop: Inside the opening shortcode tag
		.     '[^\\]\\/]*'                   // Not a closing bracket or forward slash
		.     '(?:'
		.         '\\/(?!\\])'               // A forward slash not followed by a closing bracket
		.         '[^\\]\\/]*'               // Not a closing bracket or forward slash
		.     ')*?'
		.     '(?:'
		.         '\\/\\]'                   // Self closing tag and closing bracket
		.     '|'
		.         '\\]'                      // Closing bracket
		.         '(?:'                      // Unroll the loop: Optionally, anything between the opening and closing shortcode tags
		.             '[^\\[]*+'             // Not an opening bracket
		.             '(?:'
		.                 '\\[(?!\\/\\2\\])' // An opening bracket not followed by the closing shortcode tag
		.                 '[^\\[]*+'         // Not an opening bracket
		.             ')*+'
		.             '\\[\\/\\2\\]'         // Closing shortcode tag
		.         ')?'
		.     ')'
		. ')'
		. '\\s*+'                            // optional trailing whitespace
		. '<br \\/>'                           // closing paragraph
		. '/s';

	$content = preg_replace( $pattern, '$1 <br />', $content );

	$array = array (
        '<p>[' => '[', 
        ']</p>' => ']', 
        ']<br />' => ']'
    );
    $content = strtr($content, $array);

    return $content;
}
add_filter('the_content', 'theme_shortcode_paragraph_insertion_fix');

if(theme_get_option('blog','excerpt_shortcode')){
	add_filter('get_the_excerpt','do_shortcode');
}

function theme_more_link($more_link, $more_link_text=NULL) {
	if(theme_get_option('blog','read_more_button')){
		$more_link='<a class="read_more_link '.apply_filters( 'theme_css_class', 'button' ).' small" href="'.get_permalink().'"><span>'.wpml_t(THEME_NAME, 'Blog Post Read More Button Text',stripslashes(theme_get_option('blog','read_more_text'))).'</span></a>';
	}
	return '<div class="read_more_wrap">'.str_replace('more-link', 'read_more_link', $more_link).'</div>';
}
add_filter('the_content_more_link', 'theme_more_link', 10, 2);

function theme_excerpt_more($excerpt) {
	return str_replace(array('[&hellip;]', '[...]'), '...', $excerpt);
}
add_filter('wp_trim_excerpt', 'theme_excerpt_more');
add_filter('excerpt_more', 'theme_excerpt_more');


// function theme_trim_excerpt($text, $raw_excerpt){
// 	if($text == $raw_excerpt){
// 		$excerpt_length = apply_filters('excerpt_length', 55);
// 		$excerpt_more = apply_filters('excerpt_more', ' ' . '[&hellip;]');
// 		$text = wp_trim_words( $text, $excerpt_length, $excerpt_more );
// 	}
// 	return $text;
// }
// add_filter('wp_trim_excerpt', 'theme_trim_excerpt', 10, 2);

function theme_exclude_category_feed() {
	$exclude_cats = theme_get_option('blog','exclude_categorys');
	if(is_array($exclude_cats)){
		foreach ($exclude_cats as $key => $cat) {
			$exclude_cats[$key] = -$cat;
		}
		if ( is_feed() ) {
			set_query_var("cat", implode(",",$exclude_cats));
		}
	}
}
add_filter('nav_menu_css_class' , 'theme_nav_add_has_children_class' , 10 , 3);

function theme_nav_add_has_children_class($classes, $item,$args = null){
	if ( (is_object($args) && isset($args->has_children) && $args->has_children) ||
		(is_array($args) && isset($args['has_children']) && $args['has_children']) ) {
		$classes[] = "has-children";
     }
     return $classes;
}
add_filter('page_css_class' , 'theme_page_add_has_children_class' , 10 , 4);
function theme_page_add_has_children_class($classes, $item,$depth  = null, $args = null){
	if (is_array($args) && isset($args['has_children']) && $args['has_children'] ) {
		$classes[] = "has-children";
     }
     return $classes;
}
add_filter('pre_get_posts', 'theme_exclude_category_feed');
if( theme_get_option('blog','show_post_thumbnail_on_feed')){
	function theme_show_post_thumbnail_on_feeds($content) {
		global $post;
		if(has_post_thumbnail($post->ID)) {
			$content =  '<div><a href="' . get_permalink($post->ID) . '">' . get_the_post_thumbnail($post->ID, 'thumbnail') .'</a></div>'.  $content ;
		}
		return $content;
	}
	add_filter('the_excerpt_rss', 'theme_show_post_thumbnail_on_feeds');
	add_filter('the_content_feed', 'theme_show_post_thumbnail_on_feeds');
}
/*
 * Remove Blog categories from category widget
 */
function theme_exclude_category_widget($cat_args)
{
	$exclude_cats = theme_get_option('blog','exclude_categorys');

	if(is_array($exclude_cats)){
		$cat_args['exclude'] = implode(",",$exclude_cats);
	}
 	return $cat_args;
}
add_filter('widget_categories_args', 'theme_exclude_category_widget');

function theme_exclude_archives_widget($args)
{
	$exclude_cats = theme_get_option('blog','exclude_categorys');

	if(is_array($exclude_cats)){
		$args['exclude'] = $exclude_cats;
	}

 	return $args;
}
add_filter('widget_archives_args', 'theme_exclude_archives_widget');
add_filter('widget_archives_dropdown_args', 'theme_exclude_archives_widget');

function theme_exclude_archive_where($where,$args){
	global $wpdb;

	if(isset($args['exclude']) && !empty($args['exclude'])){
		$where .= $wpdb->prepare(" AND ID NOT IN (SELECT DISTINCT object_id FROM {$wpdb->term_relationships} WHERE term_taxonomy_id IN ('%s'))", join("', '", $args['exclude'] ));
	}
	return $where;
}
add_filter('getarchives_where', 'theme_exclude_archive_where',10,2);

function theme_exclude_the_categorys($thelist,$separator=' ') {
	if(!defined('WP_ADMIN') && !empty($separator)) {
		//Category IDs to exclude
		$exclude = theme_get_option('blog','exclude_categorys');

		$exclude2 = array();
		foreach($exclude as $c) {
			$exclude2[] = get_cat_name($c);
		}

		$cats = explode($separator,$thelist);
		$newlist = array();
		foreach($cats as $cat) {
			$catname = trim(strip_tags($cat));
			if(!in_array($catname,$exclude2))
				$newlist[] = $cat;
		}
		return implode($separator,$newlist);
	} else {
		return $thelist;
	}
}
add_filter('the_category','theme_exclude_the_categorys',10,2);

/*
 * add a span element for style in the page
 */
// function theme_comment_style($return) {
// 	return str_replace($return, "<span></span>$return", $return);
// }
// add_filter('get_comment_author_link', 'theme_comment_style');

function theme_widget_title_remove_space($return){
	$return = trim($return);
	if('&nbsp;' == $return){
		return '';	
	}else{
		return $return;
	}
}
add_filter('widget_title', 'theme_widget_title_remove_space');

// Allow Shortcodes in Sidebar Widgets
add_filter('widget_text', 'do_shortcode');

if(theme_get_option('advanced','complex_class')){
	function theme_complex_css_class($class){
		return 'theme_'.$class;
	}

	add_filter('theme_css_class', 'theme_complex_css_class');
}

function theme_mimes_add_ico($mime_types){
	$mime_types['ico'] = 'image/x-icon'; 
	return $mime_types;
}
add_filter('upload_mimes', 'theme_mimes_add_ico');

global $wp_version;
if(version_compare($wp_version, "3.1", '<')){
	/*
	 * Thank to Bob Sherron.
	 * http://stackoverflow.com/questions/1155565/query-multiple-custom-taxonomy-terms-in-wordpress-2-8/2060777#2060777
	 */
	function multi_tax_terms($where) {
		global $wp_query;
		global $wpdb;
		if (isset($wp_query->query_vars['term']) && (strpos($wp_query->query_vars['term'], ',') !== false && strpos($where, "AND 0") !== false) ) {
			// it's failing because taxonomies can't handle multiple terms
			//first, get the terms
			$term_arr = explode(",", $wp_query->query_vars['term']);
			foreach($term_arr as $term_item) {
				$terms[] = get_terms($wp_query->query_vars['taxonomy'], array('slug' => $term_item));
			}

			//next, get the id of posts with that term in that tax
			foreach ( $terms as $term ) {
				$term_ids[] = $term[0]->term_id;
			}

			$post_ids = get_objects_in_term($term_ids, $wp_query->query_vars['taxonomy']);

			if ( !is_wp_error($post_ids) && count($post_ids) ) {
				// build the new query
				$new_where = " AND $wpdb->posts.ID IN (" . implode(', ', $post_ids) . ") ";
				// re-add any other query vars via concatenation on the $new_where string below here

				// now, sub out the bad where with the good
				$where = str_replace("AND 0", $new_where, $where);
			} else {
				// give up
			}
		}
		return $where;
	}
	add_filter("posts_where", "multi_tax_terms");
}

/*
 * add menu order support for Single Portfolio Item Previous & Next Navigation
 */
$order = theme_get_option('portfolio','single_navigation_order');
if($order == 'menu_order'){
	function get_previous_portfolio_menu_order_where($where){
		global $post, $wpdb;
		if($post->post_type == 'portfolio'){
			$current_menu_order = $post->menu_order;
			$where = $wpdb->prepare("WHERE p.menu_order < %s AND p.post_type = 'portfolio' AND p.post_status = 'publish'", $current_menu_order);
		}
		return $where;
	}
	function get_next_portfolio_menu_order_where($where){
		global $post, $wpdb;
		if($post->post_type == 'portfolio'){
			$current_menu_order = $post->menu_order;
			$where = $wpdb->prepare("WHERE p.menu_order > %s AND p.post_type = 'portfolio' AND p.post_status = 'publish'", $current_menu_order);
		}
		return $where;
	}
	add_filter("get_previous_post_where", "get_previous_portfolio_menu_order_where");
	add_filter("get_next_post_where", "get_next_portfolio_menu_order_where");

	function get_previous_portfolio_menu_order_sort($sort){
		global $post;
		if($post->post_type == 'portfolio'){
			$sort = "ORDER BY p.menu_order DESC LIMIT 1";
		}
		return $sort;
	}
	function get_next_portfolio_menu_order_sort($sort){
		global $post;
		if($post->post_type == 'portfolio'){
			$sort = "ORDER BY p.menu_order ASC LIMIT 1";	
		}
		return $sort;
	}

	add_filter("get_previous_post_sort", "get_previous_portfolio_menu_order_sort");
	add_filter("get_next_post_sort", "get_next_portfolio_menu_order_sort");
}

/*
 * Single Portfolio Item Document Type Navigation
 */
if(theme_get_option('portfolio','single_doc_navigation')){
	function get_adjacent_doc_portfolio_join($join){
		global $post, $wpdb;
		if($post->post_type == 'portfolio'){
			$join .= " JOIN $wpdb->postmeta ON (p.ID = $wpdb->postmeta.post_id) ";
		}
		return $join;	
	}
	add_filter("get_previous_post_join", "get_adjacent_doc_portfolio_join");
	add_filter("get_next_post_join", "get_adjacent_doc_portfolio_join");

	function get_adjacent_doc_portfolio_where($where){
		global $post, $wpdb;
		if($post->post_type == 'portfolio'){
			$where .= $wpdb->prepare(" AND $wpdb->postmeta.meta_key = %s ", '_type');
			$where .= $wpdb->prepare("AND $wpdb->postmeta.meta_value = %s ", 'doc');
		}
		return $where;
	}
	add_filter("get_previous_post_where", "get_adjacent_doc_portfolio_where");
	add_filter("get_next_post_where", "get_adjacent_doc_portfolio_where");
}
/*
 * Single Portfolio Item Category Navigation
 */
if(theme_get_option('portfolio','single_navigation_category')){
	function get_adjacent_category_portfolio_join($join){
		global $post, $wpdb;
		if($post->post_type == 'portfolio'){
			$join .= " INNER JOIN $wpdb->term_relationships AS tr ON p.ID = tr.object_id INNER JOIN $wpdb->term_taxonomy tt ON tr.term_taxonomy_id = tt.term_taxonomy_id";

			$cat_array = wp_get_object_terms($post->ID, 'portfolio_category', array('fields' => 'ids'));
			$join .= " AND tt.taxonomy = 'portfolio_category' AND tt.term_id IN (" . implode(',', $cat_array) . ")";
			//$join .= " JOIN $wpdb->postmeta ON (p.ID = $wpdb->postmeta.post_id) ";
		}
		return $join;	
	}
	add_filter("get_previous_post_join", "get_adjacent_category_portfolio_join");
	add_filter("get_next_post_join", "get_adjacent_category_portfolio_join");
}

function theme_exclude_category_post_join($join){
	global $post, $wpdb;
	if($post->post_type == 'post'){
		$exclude_cats = theme_get_option('blog','exclude_categorys');
		if(is_array($exclude_cats) && !empty($exclude_cats)){
			$join .= " INNER JOIN $wpdb->term_relationships AS tr ON p.ID = tr.object_id INNER JOIN $wpdb->term_taxonomy tt ON tr.term_taxonomy_id = tt.term_taxonomy_id";
			$join .= " AND tt.taxonomy = 'category' AND tt.term_id NOT IN (" . implode(',', $exclude_cats) . ")";
			if(theme_get_option('blog','single_navigation_category')){
				$cat_array = wp_get_object_terms($post->ID, 'category', array('fields' => 'ids'));
				$join .= " AND tt.term_id IN (" . implode(',', $cat_array) . ")";
			}
		}else{
			if(theme_get_option('blog','single_navigation_category')){
				$join .= " INNER JOIN $wpdb->term_relationships AS tr ON p.ID = tr.object_id INNER JOIN $wpdb->term_taxonomy tt ON tr.term_taxonomy_id = tt.term_taxonomy_id";
				$cat_array = wp_get_object_terms($post->ID, 'category', array('fields' => 'ids'));
				$join .= " AND tt.taxonomy = 'category' AND tt.term_id IN (" . implode(',', $cat_array) . ")";
			}
		}
	}
	return $join;
}
add_filter("get_previous_post_join", "theme_exclude_category_post_join");
add_filter("get_next_post_join", "theme_exclude_category_post_join");

if(theme_get_option('general','lightbox_rel_replace')){
	add_filter('the_content', 'theme_add_lightbox_rel_replace');
	function theme_add_lightbox_rel_replace ($content){
		global $post;
		$pattern = "/<a([^>]*?)href=('|\")([^\\2>]*?)\.(bmp|gif|jpeg|jpg|png)\\2(.*?)>/i";
		$replacement = '<a$1href=$2$3.$4$2 class="wp_lightbox" rel="post_%LIGHTID%"$5>';
		$content = preg_replace($pattern, $replacement, $content);
		if(isset($post->ID)){
			$content = str_replace("%LIGHTID%", $post->ID, $content);
		} else {
			$content = str_replace("%LIGHTID%", 'noid', $content);
		}
		return $content;
	}
}

if(theme_get_option('advanced','shortcode_comment')){
	add_filter('comment_text', 'do_shortcode');
}
add_filter('body_class','theme_add_body_class');
function theme_add_body_class($classes){
	global $polylang;
	$detect = new Mobile_Detect;
	if($detect->isMobile()){
		$classes[] = 'isMobile';
	}

	if(theme_get_option('general','scroll_to_top')){
		$classes[] = 'scroll-to-top';
		$style = theme_get_option('general','scroll_to_top_style');
		if($style){
			$classes[] = 'scroll-to-top-'.$style;
		}
	}
	if(theme_get_option('advanced','responsive')){
		$classes[] = 'responsive';
	} else {
		$classes[] = 'no-responsive';
	}
	if(theme_get_option('advanced','no_fancybox')){
		$classes[] = 'no_fancybox';	
	}
	if(theme_get_option('general','enable_box_layout')){
		$classes[] = 'box-layout';
	}
	if('none' !== theme_get_option('color','has_shadow')){
		$classes[] = 'has-shadow';

		if(theme_get_option('color','has_shadow') === 'dark'){
			$classes[] = 'shadow-dark';
		}
	}else{
		$classes[] = 'no-shadow';
	}
	if(theme_get_option('color','has_gradient')){
		$classes[] = 'has-gradient';
	}else{
		$classes[] = 'no-gradient';
	}
	if(function_exists('icl_get_languages') && !isset($polylang)) {
		$classes[] =  'current-language-'.strtolower(ICL_LANGUAGE_NAME_EN);
	} else if(isset($polylang)){
		$classes[] =  'current-language-'.pll_current_language();
	}

	$post_id = theme_get_queried_object_id();
	if (!$post_id==0) {
		$type = get_post_meta($post_id, '_introduce_text_type', true);

		if (empty($type)) {
			$type = 'default';
		} 
		if (!theme_get_option('general','introduce') && $type=='default' || $type == 'disable'){
			$classes[] =  ' no-featured-header';
		}
	} else { //if is the home page check for slideshow has been set
		if( is_home() || is_front_page()) {
			if (theme_get_option('homepage', 'disable_slideshow')) {
				$classes[] =  ' no-featured-header';
			}
		} else if(!theme_get_option('general','introduce') ) { //if is a customposttype slugpage like bbpress with postid=0 check if global featured header is set
			$classes[] =  ' no-featured-header';
		}             
	}
	return $classes;
}


function theme_search_parse_query($query = false){
	if($query->is_search){
		$exclude_cats = theme_get_option('blog','exclude_categorys');
		foreach ($exclude_cats as $key => $value) {
			$exclude_cats[$key] = -$value;
		}
		if (isset($query->query_vars["cat"])) {
			$cat = $query->query_vars["cat"];
			if(!empty($cat) && '0' != $cat){
				$cat = ''.urldecode($cat).'';
				$cat = addslashes_gpc($cat );
				$cat_array = preg_split('/[,\s]+/', $cat);
				$req_cats = array();
				foreach ( (array) $cat_array as $c ) {
					$c = intval($c);
					if(!in_array($c, $exclude_cats))
					$req_cats[] = $c;
				}
				$exclude_cats = array_merge($exclude_cats,$req_cats);
			}
		}
		$query->set("cat",  implode(",",$exclude_cats));
	}
	return $query;
}
add_filter('parse_query', 'theme_search_parse_query');


function theme_nav_menu_css_class($classes,$item){
	if (is_search()||is_404()) {
		$blog_page = theme_get_option('blog','blog_page');
		if(!empty($blog_page)) $classes = array_diff($classes, array('current_page_parent'));
	}
	if(is_home() && !is_blog() && $item->object_id == get_option( 'page_for_posts' )) {
		$classes = array_diff($classes, array('current_page_parent'));
	}
	if(!is_home() && get_post_type() == 'post' && isset($item->object_id) ){
		$blog_page = theme_get_option('blog','blog_page');
		if(!empty($blog_page) && $item->object_id == $blog_page){
			$classes[] = 'current_page_parent';
		}
	}
	return $classes;
}
add_filter('nav_menu_css_class', 'theme_nav_menu_css_class',10,2);

function theme_page_css_class($classes,$page){
	if(!is_home() && get_post_type() == 'post'){
		$blog_page = theme_get_option('blog','blog_page');
		if(!empty($blog_page) && $page->ID == $blog_page){
			$classes[] = 'current_page_parent';
		}
	}
	return $classes;
}
add_filter('page_css_class', 'theme_page_css_class',10,2);

if (!theme_get_option('advanced','bbpress_breadcrumbs')) {
	function theme_bbp_no_breadcrumb ($param) {
		return true;
	}
	add_filter ('bbp_no_breadcrumb', 'theme_bbp_no_breadcrumb');
}
/**
* Preserves two spaces (when present) between sentences and anywhere else for display as HTML.
*/
function theme_extra_sentence_space( $text ) {
	$punctuation = preg_quote( apply_filters( 'theme_extra_sentence_space_punctuation', '.!?' ), '/' );

	return preg_replace( "/([$punctuation][\'\"]?)([ ]{2,})/imsU", "$1&nbsp; ", $text );
}

//add_filter( 'theme_extra_sentence_space', 'theme_extra_sentence_space' );
add_filter( 'comment_text', 'theme_extra_sentence_space', 9 );
add_filter( 'the_title',    'theme_extra_sentence_space', 9 );
add_filter( 'the_content',  'theme_extra_sentence_space', 9 );
add_filter( 'the_excerpt',  'theme_extra_sentence_space', 9 );
add_filter( 'widget_text',  'theme_extra_sentence_space', 9 );

global $wp_version;
if ( function_exists( '_wp_render_title_tag' ) && version_compare(preg_replace("/[^0-9\.]/","",$wp_version), '4.4', '>=') ) {
	function theme_document_title_seperator() {
		return '|';
	}
	add_filter ('document_title_separator', 'theme_document_title_seperator');

	function theme_document_title_parts($title) {
		global $page, $paged;
		if (isset($title['page'])) {
			unset ($title['page']);
			if ( $paged >= 2 || $page >= 2 ) {
				$keys=array_keys($title);
				$last=$title[$keys[sizeof($keys) - 1]]; 
				if (!empty($last)) $delimiter=' | '; else $delimiter='';
				$output = $delimiter.sprintf( __( 'Page %s', 'striking-r' ), max( $paged, $page ) );
				$title[$keys[sizeof($keys) - 1]].=$output;
			}
		}
		if(function_exists('is_shop') && is_shop()){
			$woo_show_shop_title = theme_get_option('advanced','woocommerce_shop_title_in_browser_tab');
			if ($woo_show_shop_title) {
				$id=wc_get_page_id( 'shop' );
				if (!empty($id)) {
					$shop_title=wp_strip_all_tags(get_the_title($id));
					if (isset($title['title']) && $title['title']!=$shop_title && !empty($shop_title)) $title['title']=$shop_title;
				}
			}
		}
		return $title;
	}

	add_filter ('document_title_parts', 'theme_document_title_parts');
}

if (!function_exists('theme_set_pre_get_posts_vars')) {
	function theme_set_pre_get_posts_vars( $query ) {
		if (is_admin()) {
			return;
		}

		if ( $query->is_home() && $query->is_main_query() && !is_author() || $query->is_search() && $query->is_main_query()) {
			if (is_home() || is_search()) {
				$object_id = get_queried_object();
				if (isset($object_id->ID) || is_search()) {
					//when $object_id->ID is set we 100% sure we are the static blog page!				
					$exclude_cats=array();
					$exclude='';
					$exclude_cats = theme_get_option('blog','exclude_categorys');
					if (is_home()) {
						$exclude_cats_for_blog_page = theme_get_option('blog','exclude_categorys_for_blog_page');
						$exclude_cats = array_merge($exclude_cats, $exclude_cats_for_blog_page);
					}
					if (!empty($exclude_cats) && is_array($exclude_cats)) {
						foreach ($exclude_cats as $key => $value) {
							if(function_exists('icl_get_languages')){
								$value=apply_filters( 'wpml_object_id', $value, 'category', TRUE  );
							}
							if (!empty($value)) {
								$exclude_cats[$key] = -$value;
							}
						}
						if(is_array($exclude_cats)){
							$exclude = implode(",",$exclude_cats);
						}
					}
					global $wp_version;
					if((is_home() ) && version_compare($wp_version, "3.1", '>=')){//fix wordpress 3.1 paged query 
						$paged = (get_query_var('paged')) ?intval(get_query_var('paged')) : intval(get_query_var('page'));
					}else{
						$paged = intval(get_query_var('paged'));
					}
					if (!empty($exclude)) {
						$query->set( 'cat', $exclude );
					}
					$query->set( '&paged', $paged );
				} else {
					if (is_home() && ! isset($object_id->ID)) {
						// For non static homepage just make sure the pagination is lower then 
						// possible pagination used in the shortcode and trick the wordpress system.
						$query->set( 'posts_per_page', 1 );
					}
				}
			}
		}
		
		if (is_archive()) {
			$exclude_cats=array();
			$exclude_cats = theme_get_option('blog','exclude_categorys');
			if (!empty($exclude_cats) && is_array($exclude_cats)) {
				foreach ($exclude_cats as $key => $value) {
					if(function_exists('icl_get_languages')){
						$value=apply_filters( 'wpml_object_id', $value, 'category', TRUE  );
					}
					if (!empty($value)) {
						$exclude_cats[$key] = -$value;
					}
				}
				if(is_array($exclude_cats)){
					$exclude = implode(",",$exclude_cats);
				}
				if (!empty($exclude)) {
					$query->set( 'cat', $exclude );
				}
			}
		}
	}

	add_action( 'pre_get_posts', 'theme_set_pre_get_posts_vars');
}

if (!function_exists('theme_set_pre_get_calendar_vars')) {
	function theme_set_pre_get_calendar_vars( $query ) {
		$exclude_cats=array();
		$exclude_cats = theme_get_option('blog','exclude_categorys');
		if (!empty($exclude_cats) && is_array($exclude_cats)) {
			foreach ($exclude_cats as $key => $value) {
				if(function_exists('icl_get_languages')){
					$value=apply_filters( 'wpml_object_id', $value, 'category', TRUE  );
				}
				if (!empty($value)) {
					$exclude_cats[$key] = -$value;
				}
			}
			if(is_array($exclude_cats)){
				$exclude = implode(",",$exclude_cats);
			}
			if (!empty($exclude)) {
				$query->set( 'cat', $exclude );
			}
		}
	}
}

add_filter('img_caption_shortcode_width','theme_set_caption_image_width',10,2);

if (!function_exists('theme_set_caption_image_width')) {
	function theme_set_caption_image_width($width,$atts) {
		extract(shortcode_atts(array(
			'id'	  => '',
			'align'	  => 'alignnone',
			'width'	  => '',
			'caption' => '',
			'class'   => '',
		), $atts));
	   $caption_width = theme_get_option('image','wp_caption_width');
	   if ($align=='alignleft'||$align=='alignright') {
		   $caption_width = theme_get_option('image','wp_caption_width');
		   if (!empty($caption_width) && $caption_width!=0) {
				if ($width>$caption_width) $width=$caption_width;
		   }
	   }
	   return $width;
	}
}

if (!function_exists('theme_disable_gutenberg')) {
	function theme_disable_gutenberg() {
		$gutenberg = false;
		$block_editor = false;
		$replace = true;

		if ( has_filter( 'replace_editor', 'gutenberg_init' ) ) {
			// Gutenberg is installed and activated.
			$gutenberg = true;
		}

		if ( version_compare( $GLOBALS['wp_version'], '5.0-beta', '>' ) ) {
			// Block editor.
			$block_editor = true;
		}

		if ( ! $gutenberg && ! $block_editor ) {
			return; // Nothing to do :)
		}

		if ( $block_editor && ( $replace || isset( $_GET['classic-editor'] ) ) ) {
			// TODO: consider disabling other block editor functionality.
			add_filter( 'use_block_editor_for_post_type', '__return_false', 100 );
		}

		if ( $gutenberg && ( $replace || isset( $_GET['classic-editor'] ) ) ) {

			remove_action( 'admin_menu', 'gutenberg_menu' );
			remove_action( 'admin_notices', 'gutenberg_build_files_notice' );
			remove_action( 'admin_notices', 'gutenberg_wordpress_version_notice' );
			remove_action( 'admin_init', 'gutenberg_redirect_demo' );

			remove_filter( 'replace_editor', 'gutenberg_init' );

			// lib/client-assets.php
			remove_action( 'wp_enqueue_scripts', 'gutenberg_register_scripts_and_styles', 5 );
			remove_action( 'admin_enqueue_scripts', 'gutenberg_register_scripts_and_styles', 5 );
			remove_action( 'wp_enqueue_scripts', 'gutenberg_common_scripts_and_styles' );
			remove_action( 'admin_enqueue_scripts', 'gutenberg_common_scripts_and_styles' );

			// lib/compat.php
			remove_filter( 'wp_refresh_nonces', 'gutenberg_add_rest_nonce_to_heartbeat_response_headers' );

			// lib/rest-api.php
			remove_action( 'rest_api_init', 'gutenberg_register_rest_routes' );
			remove_action( 'rest_api_init', 'gutenberg_add_taxonomy_visibility_field' );

			remove_filter( 'rest_request_after_callbacks', 'gutenberg_filter_oembed_result' );
			remove_filter( 'registered_post_type', 'gutenberg_register_post_prepare_functions' );
			remove_filter( 'register_post_type_args', 'gutenberg_filter_post_type_labels' );

			// lib/meta-box-partial-page.php
			remove_action( 'do_meta_boxes', 'gutenberg_meta_box_save', 1000 );
			remove_action( 'submitpost_box', 'gutenberg_intercept_meta_box_render' );
			remove_action( 'submitpage_box', 'gutenberg_intercept_meta_box_render' );
			remove_action( 'edit_page_form', 'gutenberg_intercept_meta_box_render' );
			remove_action( 'edit_form_advanced', 'gutenberg_intercept_meta_box_render' );

			remove_filter( 'redirect_post_location', 'gutenberg_meta_box_save_redirect' );
			remove_filter( 'filter_gutenberg_meta_boxes', 'gutenberg_filter_meta_boxes' );

			// gutenberg.php
			remove_action( 'admin_init', 'gutenberg_add_edit_link_filters' );
			remove_action( 'admin_print_scripts-edit.php', 'gutenberg_replace_default_add_new_button' );

			remove_filter( 'body_class', 'gutenberg_add_responsive_body_class' );
			remove_filter( 'admin_url', 'gutenberg_modify_add_new_button_url' );

			// lib/compat.php
			remove_action( 'admin_enqueue_scripts', 'gutenberg_check_if_classic_needs_warning_about_blocks' );

			// lib/register.php
			remove_action( 'edit_form_top', 'gutenberg_remember_classic_editor_when_saving_posts' );

			remove_filter( 'redirect_post_location', 'gutenberg_redirect_to_classic_editor_when_saving_posts' );
			remove_filter( 'get_edit_post_link', 'gutenberg_revisions_link_to_editor' );
			remove_filter( 'wp_prepare_revision_for_js', 'gutenberg_revisions_restore' );
			remove_filter( 'display_post_states', 'gutenberg_add_gutenberg_post_state' );

			// lib/plugin-compat.php
			remove_filter( 'rest_pre_insert_post', 'gutenberg_remove_wpcom_markdown_support' );
		}
	}
}

$use_gutenberg_editor = theme_get_option('advanced','use_gutenberg_editor');
if ($use_gutenberg_editor==false) {
	global $wp_version;
	$check_WP   = '4.9.8';
	if (version_compare($wp_version, $check_WP, '<=')) {
		theme_disable_gutenberg();
		// Remove the "Try Gutenberg" dashboard widget.
		remove_action( 'try_gutenberg_panel', 'wp_try_gutenberg_panel' );
	} else 	{
		add_filter( 'use_block_editor_for_post_type', '__return_false', 100 );
	}
}
