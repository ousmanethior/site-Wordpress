<?php
/**
 * Single Product Thumbnails
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-thumbnails.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce/Templates
 * @version     3.5.1
 */

defined( 'ABSPATH' ) || exit;


global $post, $product, $woo_config;

$sizes = array($woo_config['full']['shop_thumbnail']['width'], $woo_config['full']['shop_thumbnail']['height']);
$hover_icon=theme_get_option('advanced','woocommerce_single_hover_icon');
if ($hover_icon=='zoom') $hover_icon='image_icon_'.$hover_icon; else $hover_icon='';

$attachment_ids = $product->get_gallery_image_ids();

if ( $attachment_ids && has_post_thumbnail() ) {
	echo '<div class="woocommerce-product-thumbnails-wrapper">';
	foreach ( $attachment_ids as $attachment_id ) {
		$full_size_image = wp_get_attachment_image_src( $attachment_id, 'full' );
		$image_title = get_post_field( 'post_title', $attachment_id );

		$follow_crop = $woo_config['full']['shop_thumbnail']['crop'];
		if ($follow_crop==1) {
			$sizes[1]=$sizes[0];
		}
		
		$props       = wc_get_product_attachment_props( $attachment_id, $post );
		if (empty($image_title)) $image_title = esc_attr( $props['title'] );
		$image_src   = theme_get_image_src(array('type'=>'attachment_id','value'=>$attachment_id), $sizes);
		$srcset=theme_get_retina_srcset( $image_src );
		$image_caption 	= esc_attr( $props['caption'] );
		if (empty($image_caption)) $image_caption=$image_title;
		$image_alt 		= esc_attr( $props['alt'] );
		if (empty($image_alt)) $image_alt=$image_title;

		$image = '<img class="attachment-shop_single size-shop_single product-thumbnail image-overlay" width="'.$sizes[0].'" height="'.$sizes[1].'" data-thumbnail="'.$attachment_id.'" src="'.$image_src.'"'.$srcset.' title="'.$image_title.'" alt="'.$image_alt.'" />';

		$html  = '<div class="woocommerce-product-gallery__image thumbnail  woo-image-overlay"><a class="'.$hover_icon.'" href="' . esc_url( $full_size_image[0] ) . '" rel="prettyPhoto[product-gallery]" title="'.$image_title.'">';
		$html  .= $image;
 		$html .= '</a></div>';
		echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $attachment_id );
		//echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', wc_get_gallery_image_html( $attachment_id  ), $attachment_id );
	}
	echo '</div>';
}
