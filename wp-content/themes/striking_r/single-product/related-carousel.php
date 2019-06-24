<?php
/**
 * Related Carousel Products
 * @version     5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $product,$woo_config;
$product_id = $product->get_id();
$related = wc_get_related_products($product_id, theme_get_option('advanced','woocommerce_related_products_number'));

if ( sizeof( $related ) === 0 ) return;

$related_ids = implode(',', $related);
$icon=theme_get_option('advanced','woocommerce_global_hover_icon');
if ($icon=='none') $icon='';
$height= absint(200 * ($woo_config['full']['shop_catalog']['height']/$woo_config['full']['shop_catalog']['width']));
$sizes = array('200', $height);
$follow_crop = $woo_config['full']['shop_catalog']['crop'];
if ($follow_crop==1) {
	$sizes[1]=$sizes[0];
}
?>


<div class="related products">
	<?php echo do_shortcode('[product_carousel title="<h2>'.__( 'Related Products', 'woocommerce' ).'</h2>" ids="'.$related_ids.'" nav="true" icon="'.$icon.'" width="'.$sizes[0].'" height="'.$sizes[1].'"]');?>
</div>
