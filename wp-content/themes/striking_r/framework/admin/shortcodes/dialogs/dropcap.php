<?php
return array(
	"title" => __("Drop Cap", "theme_admin"),
	"shortcode" => 'dropcap',
	"type" => 'enclosing',
	"options" => array(
		array(
			"name" => __("Style",'theme_admin'),
			"id" => "style",
			"default" => 'dropcap1',
			"options" => array(
				"dropcap1" => 'dropcap1',
				"dropcap2" => 'dropcap2',
				"dropcap3" => 'dropcap3',
				"dropcap4" => 'dropcap4',
			),
			"type" => "select",
		),
		array(
			"name" => __("Color (Optional)&#x200E;",'theme_admin'),
			"id" => "color",
			"default" => "",
			"prompt" => __("Choose one..",'theme_admin'),
			"options" => array(
				"black" => 'Black',
				"gray" => 'Gray',
				"red" => 'Red',
				"yellow" => 'Yellow',
				"blue" => 'Blue',
				"pink" => 'Pink',
				"green" => 'Green',
				"rosy" => 'Rosy',
				"orange" => 'Orange',
				"magenta" => 'Magenta',
			),
			"type" => "select",
		),
		array(
			"name" => __("DropCap Letter Color (Optional)&#x200E;",'theme_admin'),
			"desc" => __("If set to a color this color will change the color of the drop cap letter. Note: The border of dropcap4 will also follow the color set in here.",'theme_admin'),
			"id" => "letterColor",
			"default" => '',
			"type" => "color"
		),
		array (
			"name" => __("DropCap Letter Top Position&#x200E;",'theme_admin'),
			"desc" => __("Depending on the Kerning of the font family used one might want to move the DropCap letter position within the dropcap from the top to center the letter.",'theme_admin'),
			"id" => "letterTop",
			"default" => '0',
			"min" => -10,
			"max" => 10,
			"step" => "1",
			"type" => "range",
			"unit" => 'px'
		),
		array (
			"name" => __("DropCap Letter Left Position&#x200E;",'theme_admin'),
			"desc" => __("Depending on the Kerning of the font family used one might want to move the DropCap letter position within the dropcap from the left to center the letter.",'theme_admin'),
			"id" => "letterLeft",
			"default" => '0',
			"min" => -10,
			"max" => 10,
			"step" => "1",
			"type" => "range",
			"unit" => 'px'
		),
		array(
			"name" => __("Bold DropCap Letter",'theme_admin'),
			"desc" => __("If set to ON the font weight will be bold if the font supports it.",'theme_admin'),
			"id" => "bold",
			"default" => "false",
			"type" => "toggle"
		),
		array(
			"name" => __("Content",'theme_admin'),
			"id" => "content",
			"default" => "",
			"type" => "text"
		),
	),
	"custom" => '',
);