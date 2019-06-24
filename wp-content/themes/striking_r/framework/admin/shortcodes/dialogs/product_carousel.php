<?php
$init_script = <<<HTML
	jQuery('[name="post_type"]').on("change",function(){
		var val = jQuery(this).val();
		
		$.post(ajaxurl, {
			action:'theme-get-taxonomies',
			post_type: val,
			cookie: encodeURIComponent(document.cookie)
		}, function(data){
			jQuery('[name="taxonomy"]').html(data);
		});
		jQuery('[name="terms[]"]').html('');
	}).trigger("change");

	jQuery('[name="taxonomy"]').on("change", function(){
		var val = jQuery(this).val();
		
		$.post(ajaxurl, {
			action:'theme-get-terms',
			taxonomy: val,
			cookie: encodeURIComponent(document.cookie)
		}, function(data){
			jQuery('[name="terms[]"]').html(data);
		});
	});
HTML;
return array(
	"title" => __("Product Carousel",'theme_admin'),
	"shortcode" => 'product_carousel',
	"init" => $init_script,
	"type" => 'self-closing',
	"options" => array(
		array(
			"name" => __("Image Width",'theme_admin'),
			"id" => "width",
			"min" => "50",
			"max" => "500",
			"step" => "1",
			"default" => "200",
			"unit" => 'px',
			"type" => "range"
		),
		array(
			"name" => __("Image Height",'theme_admin'),
			"id" => "height",
			"min" => "50",
			"max" => "500",
			"step" => "1",
			"default" => "150",
			"unit" => 'px',
			"type" => "range"
		),
		array(
			"name" => __("Title (Optional)&#x200E;",'theme_admin'),
			"id" => "title",
			"default" => "",
			"type" => "text",
			"class" => 'full'
		),
		array (
			"name" => __("Nav",'theme_admin'),
			"id" => "nav",
			"default" => false,
			"type" => "toggle"
		),
		array (
			"name" => __("Autoplay",'theme_admin'),
			"id" => "autoplay",
			"default" => true,
			"type" => "toggle"
		),
		array (
			"name" => __("Circular",'theme_admin'),
			"id" => "circular",
			"default" => false,
			"type" => "toggle"
		),
		array (
			"name" => __("Lightbox",'theme_admin'),
			"desc" => __("If Lightbox is enabled this will enable lightbox support when a viewer clicks on the image. Note : the images will then not link to the single product anymore.",'theme_admin'),
			"id" => "lightbox",
			"default" => false,
			"type" => "toggle"
		),
		array (
			"name" => __("Group Lightbox",'theme_admin'),
			"desc" => __("If Lightbox is enabled and Group Lightbox is enabled then it is possible to walk through all other images in the lightbox popup. Note: Circular will be turned OFF.",'theme_admin'),
			"id" => "lightboxGroup",
			"default" => false,
			"type" => "toggle"
		),
		array(
			"name" => __("Effect",'theme_admin'),
			"desc" => __("The effect that occures when a cursor hovers over the image. An Icon can be used to imply a link to something, and the grayscale is a fancy black and white hover effect",'theme_admin'),
			"id" => "effect",
			"default" => 'icon',
			"options" => array(
				"icon" => __("Icon",'theme_admin'),
				"grayscale" => __("Grayscale",'theme_admin'),
				"blur" => __("Blur",'theme_admin'),
				"zoom" => __("Zoom",'theme_admin'),
				"rotate" => __("Rotate",'theme_admin'),
				"morph" => __("Morph",'theme_admin'),
				"tilt" => __("Tilt",'theme_admin'),
				"none" => __("None",'theme_admin'),
			),
			"type" => "select",
		),
		array(
			"name" => __("Icon (Optional)&#x200E;",'theme_admin'),
			"desc" => __("If you selected Icon above, here you select the type of icon you want to appear over the image on mouse hover",'theme_admin'),
			"id" => "icon",
			"default" => '',
			"prompt" => __("Choose one..",'theme_admin'),
			"options" => array(
				"zoom" => __('Zoom','theme_admin'),
				"play" => __('Play','theme_admin'),
				"doc" => __('Doc','theme_admin'),
				"link" => __('Link','theme_admin'),
			),
			"type" => "select",
		),
		array (
			"name" => __("Show Captions",'theme_admin'),
			"desc" => __("If Show Captions is enabled and the image has a caption set or title, that caption will show above, at the top, at the bottom or below the image in the carousel slider. The caption position can be set in the next setting.",'theme_admin'),
			"id" => "showCaptions",
			"default" => false,
			"type" => "toggle"
		),
		array(
			"name" => __("Caption Position",'theme_admin'),
			"desc" => __("If previous setting Show Captions is enabled Caption Position will give you the ability to set the location of the caption.",'theme_admin'),
			"id" => "captionPos",
			"default" => 'bottom',
			"options" => array(
				"top" => __('Top over Image','theme_admin'),
				"above" => __('Above Image','theme_admin'),
				"bottom" => __('Bottom over Image','theme_admin'),
				"below" => __('Below Image','theme_admin'),
			),
			"type" => "select",
		),
		array(
			"name" => __("Caption Margin",'theme_admin'),
			"desc" => __("Set the margin for the caption text to move it up or down depending on the Caption Position set.",'theme_admin'),
			"id" => "captionMargin",
			"min" => "0",
			"max" => "30",
			"step" => "1",
			'unit' => 'px',
			"default" => "0",
			"type" => "range"
		),
		array(
			"name" => __("Caption FontSize",'theme_admin'),
			"desc" => __("Set the Caption Font Size in pixels",'theme_admin'),
			"id" => "captionSize",
			"min" => "8",
			"max" => "60",
			"step" => "1",
			'unit' => 'px',
			"default" => "12",
			"type" => "range"
		),
		array (
			"name" => __("Captions Text Color",'theme_admin'),
			"desc" => __("Set a color for the caption Text",'theme_admin'),
			"id" => "textColor",
			"default" => '',
			"type" => "color"
		),
		array (
			"name" => __("Captions Background Color",'theme_admin'),
			"desc" => __("Set a Background color for the caption Text",'theme_admin'),
			"id" => "bgColor",
			"default" => '',
			"type" => "color"
		),
		array(
			"name" => __("Delay",'theme_admin'),
			"id" => "delay",
			"min" => "500",
			"max" => "20000",
			"step" => "100",
			'unit' => 'miliseconds',
			"default" => "4000",
			"type" => "range"
		),
		array(
			"name" => __("Speed",'theme_admin'),
			"id" => "speed",
			"min" => "500",
			"max" => "10000",
			"step" => "100",
			'unit' => 'miliseconds',
			"default" => "1000",
			"type" => "range"
		),
		array (
			"name" => __("Touch",'theme_admin'),
			"id" => "touch",
			"default" => true,
			"type" => "toggle"
		),
		array(
			"name" => __("Post Type",'theme_admin'),
			"id" => "post_type",
			"default" => '',
			"target" => 'thumbnail_custom_post_types',
			"type" => "select",
		),
		array(
			"name" => __("Taxonomy (Optional)&#x200E;",'theme_admin'),
			"id" => "taxonomy",
			"default" => '',
			"options" => array(),
			"type" => "select",
		),
		array(
			"name" => __("Taxonomy terms (Optional)&#x200E;",'theme_admin'),
			"id" => "terms",
			"default" => '',
			"options" => array(),
			"type" => "multiselect",
		),
		array(
			"name" => __("Number of images",'theme_admin'),
			"id" => "number",
			"min" => "0",
			"max" => "15",
			"step" => "1",
			"default" => "0",
			"type" => "range"
		),
		array (
			"name" => __("Random",'theme_admin'),
			"id" => "random",
			"default" => false,
			"type" => "toggle"
		),
		array(
			"name" => __("Link Target",'theme_admin'),
			"id" => "link_target",
			"default" => '_self',
			"options" => array(
				"_blank" => __('Load in a new window','theme_admin'),
				"_self" => __('Load in the same frame as it was clicked','theme_admin'),
				"_parent" => __('Load in the parent frameset','theme_admin'),
				"_top" => __('Load in the full body of the window','theme_admin'),
			),
			"type" => "select",
		),
	),
);
