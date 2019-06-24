<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.5.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post, $product, $woo_config;

$sizes = array($woo_config['full']['shop_single']['width'], $woo_config['full']['shop_single']['height']);

$follow_crop = $woo_config['full']['shop_single']['crop'];
if ($follow_crop==1) {
	$sizes[1]=$sizes[0];
}

$columns           = apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
$post_thumbnail_id = get_post_thumbnail_id( $post->ID );
$wrapper_classes   = apply_filters( 'woocommerce_single_product_image_gallery_classes', array(
	'woocommerce-product-gallery',
	'woocommerce-product-gallery--' . (has_post_thumbnail() ? 'with-images' : 'without-images'),
	'woocommerce-product-gallery--columns-' . absint( $columns ),
	'images',
) );
$hover_icon=theme_get_option('advanced','woocommerce_single_hover_icon');
if ($hover_icon=='zoom') $hover_icon='image_icon_'.$hover_icon; else $hover_icon='';
?>
<div class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $wrapper_classes ) ) ); ?>" data-columns="<?php echo esc_attr( $columns ); ?>" style="opacity: 0; transition: opacity .25s ease-in-out;">
	<figure class="woocommerce-product-gallery__wrapper">
		<?php
		if ( has_post_thumbnail()) {
			//$html  = wc_get_gallery_image_html( $post_thumbnail_id, true );

			$full_size_image   = wp_get_attachment_image_src( $post_thumbnail_id, 'full' );
			$image_title       = get_post_field( 'post_title', $post_thumbnail_id );

			$attachment_count = count( $product->get_gallery_image_ids() );
			$gallery = $attachment_count > 0 ? '[product-gallery]' : '';
			$props = wc_get_product_attachment_props( $post_thumbnail_id, $post );
			$image_src = theme_get_image_src(array('type'=>'attachment_id','value'=>$post_thumbnail_id), $sizes);
			$srcset=theme_get_retina_srcset( $image_src );
			if (empty($image_title)) $image_title = esc_attr( $props['title'] );
			$image_alt = esc_attr( $props['alt'] );
			if (empty($image_alt)) $image_alt=$image_title;
			
			if ( $product->is_type( 'variable' ) ) { 
				$variable_img_class = ' on_the_fly_resize';
			} else $variable_img_class = '';

			$image       		= '<img class="attachment-shop_single wp-post-image image-overlay'.$variable_img_class.'" width="'.$sizes[0].'" height="'.$sizes[1].'" data-thumbnail="'.$post_thumbnail_id.'" data-thumbnail-default="'.$post_thumbnail_id.'" src="'.$image_src.'"'.$srcset.' title="'.$image_title.'" alt="'.$image_alt.'" />';

			$html  = '<div class="woocommerce-product-gallery__image woo-image-overlay"><a href="' . esc_url( $full_size_image[0] ) . '" itemprop="image" class="woocommerce-main-image '.$hover_icon.'" rel="prettyPhoto' . $gallery . '" title="'.$image_title.'">';
			$html  .= $image;
			$html .= '</a></div>';
		} else {
			$html  = '<div class="woocommerce-product-gallery__image--placeholder  woo-image-overlay">';
			if (!empty($hover_icon)) $html .= '<span class="'.$hover_icon.'">';
			$html .= sprintf( '<img class="image-overlay" src="%s" alt="%s" class="wp-post-image" />', theme_get_image_src(array('type'=>'url','value'=>wc_placeholder_img_src()), $sizes), esc_html__( 'Awaiting product image', 'woocommerce' ) );
			if (!empty($hover_icon)) $html .='</span>';
			$html .= '</div>';
		}

		echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, get_post_thumbnail_id( $post->ID ) );
		do_action( 'woocommerce_product_thumbnails' );
		?>
	</figure>
</div>
