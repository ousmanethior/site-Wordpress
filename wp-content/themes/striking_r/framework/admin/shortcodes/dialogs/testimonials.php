<?php
$init_script = <<<HTML
	jQuery('[name="number"]').on("change",function(){
		var tabs_number = jQuery(this).val();
		//tabs_number++;
		jQuery('#theme-dialog-options ul.theme-dialog-tabs li').each(function(i){
			 if (i>tabs_number) jQuery(this).hide();
			 else jQuery(this).show();
		});
	}).trigger("change");
HTML;
$custom_script = <<<HTML
	var number = attrs['number'].value;	
	var duration = attrs['duration'].value;
	var autoplay = attrs['autoplay'].value;

	
	if(autoplay === 'false'){
		autoplay = ' autoplay="'+autoplay+'"';
	}else{
		autoplay = '';
	}
	
	if(duration !== '4000'){
		duration = ' duration="'+duration+'"';
	} else {
		duration = '';
	}

	var ret = '\\n[testimonials'+autoplay+duration+']\\n';
	var author = '', avatar = '', meta = '', link = '', content = '';
	for(var i=1;i<=number;i++){
		if(attrs['author_'+i].value){
			author = attrs['author_'+i].value;
			avatar = jQuery.parseJSON(attrs['avatar_'+i].value);
			meta = attrs['meta_'+i].value;
			link = attrs['link_'+i].value;
			content = attrs['content_'+i].value;

			if(meta){
				meta = ' meta="'+meta+'"';
			}else {
				meta = '';
			}
			if(link){
				link = ' link="'+link+'"';
			}else {
				link = '';
			}
			if(avatar){
				ret +='  [testimonial author="'+author+'" avatar_type="'+avatar.type+'" avatar_value="'+avatar.value+'"'+meta+link+']'+content+'[/testimonial]\\n';
			} else {
				ret +='  [testimonial author="'+author+'"'+meta+link+']'+content+'[/testimonial]\\n';
			}			
		}
	}
	ret +='[/testimonials]\\n';
	return ret;
HTML;
return array(
	"title" => __("Testimonials",'theme_admin'),
	"type" => 'custom',
	"contentOption" => 'content_1',
	"options" => array(
	'tabs' => true,
		array(
			"name" => __("General",'theme_admin'),
			"options" => array(
				array (
					"name" => __("Autoplay",'theme_admin'),
					"id" => "autoplay",
					"default" => true,
					"type" => "toggle"
				),
				array (
					"name" => __("Auto Play Duration",'theme_admin'),
					"id" => "duration",
					"min" => "1000",
					"max" => "30000",
					"step" => "100",
					"default" => "4000",
					"unit" => "ms",
					"type" => "range"
				),
				array(
					"name" => __("Number of Testimonials",'theme_admin'),
					"id" => "number",
					"min" => "1",
					"max" => "10",
					"step" => "1",
					"default" => "3",
					"type" => "range"
				),
			),
		),
		array(
			"name" => __("Tm 1",'theme_admin'),
			"options" => array(
				array(
					"name" =>sprintf(__("Testimonial %d Author Name",'theme_admin'),1),
					"id" => "author_1",
					"default" => "",
					"type" => "text"
				),
				array(
					"name" => sprintf(__("Testimonial %d Author Avatar",'theme_admin'),1),
					"id" => "avatar_1",
					"size" => 30,
					"default" => "",
					"type" => "upload",
				),
				array(
					"name" => sprintf(__("Testimonial %d Author Meta Info",'theme_admin'),1),
					"id" => "meta_1",
					"default" => "",
					"type" => "text"
				),
				array(
					"name" =>sprintf(__("Testimonial %d Author Meta Info link (Optional)&#x200E;",'theme_admin'),1),
					"id" => "link_1",
					"default" => "",
					"type" => "text"
				),
				array(
					"name" =>sprintf(__("Testimonial %d Content",'theme_admin'),1),
					"id" => "content_1",
					"default" => "",
					"type" => "textarea"
				),
			),
		),
		array(
			"name" => __("Tm 2",'theme_admin'),
			"options" => array(
				array(
					"name" =>sprintf(__("Testimonial %d Author Name",'theme_admin'),2),
					"id" => "author_2",
					"default" => "",
					"type" => "text"
				),
				array(
					"name" => sprintf(__("Testimonial %d Author Avatar",'theme_admin'),2),
					"id" => "avatar_2",
					"size" => 30,
					"default" => "",
					"type" => "upload",
				),
				array(
					"name" => sprintf(__("Testimonial %d Author Meta Info",'theme_admin'),2),
					"id" => "meta_2",
					"default" => "",
					"type" => "text"
				),
				array(
					"name" =>sprintf(__("Testimonial %d Author Meta Info link (Optional)&#x200E;",'theme_admin'),2),
					"id" => "link_2",
					"default" => "",
					"type" => "text"
				),
				array(
					"name" =>sprintf(__("Testimonial %d Content",'theme_admin'),2),
					"id" => "content_2",
					"default" => "",
					"type" => "textarea"
				),
			),
		),
		array(
			"name" => __("Tm 3",'theme_admin'),
			"options" => array(
				array(
					"name" =>sprintf(__("Testimonial %d Author Name",'theme_admin'),3),
					"id" => "author_3",
					"default" => "",
					"type" => "text"
				),
				array(
					"name" => sprintf(__("Testimonial %d Author Avatar",'theme_admin'),3),
					"id" => "avatar_3",
					"size" => 30,
					"default" => "",
					"type" => "upload",
				),
				array(
					"name" => sprintf(__("Testimonial %d Author Meta Info",'theme_admin'),3),
					"id" => "meta_3",
					"default" => "",
					"type" => "text"
				),
				array(
					"name" =>sprintf(__("Testimonial %d Author Meta Info link (Optional)&#x200E;",'theme_admin'),3),
					"id" => "link_3",
					"default" => "",
					"type" => "text"
				),
				array(
					"name" =>sprintf(__("Testimonial %d Content",'theme_admin'),3),
					"id" => "content_3",
					"default" => "",
					"type" => "textarea"
				),
			),
		),
		array(
			"name" => __("Tm 4",'theme_admin'),
			"options" => array(
				array(
					"name" =>sprintf(__("Testimonial %d Author Name",'theme_admin'),4),
					"id" => "author_4",
					"default" => "",
					"type" => "text"
				),
				array(
					"name" => sprintf(__("Testimonial %d Author Avatar",'theme_admin'),4),
					"id" => "avatar_4",
					"size" => 40,
					"default" => "",
					"type" => "upload",
				),
				array(
					"name" => sprintf(__("Testimonial %d Author Meta Info",'theme_admin'),4),
					"id" => "meta_4",
					"default" => "",
					"type" => "text"
				),
				array(
					"name" =>sprintf(__("Testimonial %d Author Meta Info link (Optional)&#x200E;",'theme_admin'),4),
					"id" => "link_4",
					"default" => "",
					"type" => "text"
				),
				array(
					"name" =>sprintf(__("Testimonial %d Content",'theme_admin'),4),
					"id" => "content_4",
					"default" => "",
					"type" => "textarea"
				),
			),
		),
		array(
			"name" => __("Tm 5",'theme_admin'),
			"options" => array(
				array(
					"name" =>sprintf(__("Testimonial %d Author Name",'theme_admin'),5),
					"id" => "author_5",
					"default" => "",
					"type" => "text"
				),
				array(
					"name" => sprintf(__("Testimonial %d Author Avatar",'theme_admin'),5),
					"id" => "avatar_5",
					"size" => 50,
					"default" => "",
					"type" => "upload",
				),
				array(
					"name" => sprintf(__("Testimonial %d Author Meta Info",'theme_admin'),5),
					"id" => "meta_5",
					"default" => "",
					"type" => "text"
				),
				array(
					"name" =>sprintf(__("Testimonial %d Author Meta Info link (Optional)&#x200E;",'theme_admin'),5),
					"id" => "link_5",
					"default" => "",
					"type" => "text"
				),
				array(
					"name" =>sprintf(__("Testimonial %d Content",'theme_admin'),5),
					"id" => "content_5",
					"default" => "",
					"type" => "textarea"
				),
			),
		),
		array(
			"name" => __("Tm 6",'theme_admin'),
			"options" => array(
				array(
					"name" =>sprintf(__("Testimonial %d Author Name",'theme_admin'),6),
					"id" => "author_6",
					"default" => "",
					"type" => "text"
				),
				array(
					"name" => sprintf(__("Testimonial %d Author Avatar",'theme_admin'),6),
					"id" => "avatar_6",
					"size" => 60,
					"default" => "",
					"type" => "upload",
				),
				array(
					"name" => sprintf(__("Testimonial %d Author Meta Info",'theme_admin'),6),
					"id" => "meta_6",
					"default" => "",
					"type" => "text"
				),
				array(
					"name" =>sprintf(__("Testimonial %d Author Meta Info link (Optional)&#x200E;",'theme_admin'),6),
					"id" => "link_6",
					"default" => "",
					"type" => "text"
				),
				array(
					"name" =>sprintf(__("Testimonial %d Content",'theme_admin'),6),
					"id" => "content_6",
					"default" => "",
					"type" => "textarea"
				),
			),
		),
		array(
			"name" => __("Tm 7",'theme_admin'),
			"options" => array(
				array(
					"name" =>sprintf(__("Testimonial %d Author Name",'theme_admin'),7),
					"id" => "author_7",
					"default" => "",
					"type" => "text"
				),
				array(
					"name" => sprintf(__("Testimonial %d Author Avatar",'theme_admin'),7),
					"id" => "avatar_7",
					"size" => 70,
					"default" => "",
					"type" => "upload",
				),
				array(
					"name" => sprintf(__("Testimonial %d Author Meta Info",'theme_admin'),7),
					"id" => "meta_7",
					"default" => "",
					"type" => "text"
				),
				array(
					"name" =>sprintf(__("Testimonial %d Author Meta Info link (Optional)&#x200E;",'theme_admin'),7),
					"id" => "link_7",
					"default" => "",
					"type" => "text"
				),
				array(
					"name" =>sprintf(__("Testimonial %d Content",'theme_admin'),7),
					"id" => "content_7",
					"default" => "",
					"type" => "textarea"
				),
			),
		),
		array(
			"name" => __("Tm 8",'theme_admin'),
			"options" => array(
				array(
					"name" =>sprintf(__("Testimonial %d Author Name",'theme_admin'),8),
					"id" => "author_8",
					"default" => "",
					"type" => "text"
				),
				array(
					"name" => sprintf(__("Testimonial %d Author Avatar",'theme_admin'),8),
					"id" => "avatar_8",
					"size" => 80,
					"default" => "",
					"type" => "upload",
				),
				array(
					"name" => sprintf(__("Testimonial %d Author Meta Info",'theme_admin'),8),
					"id" => "meta_8",
					"default" => "",
					"type" => "text"
				),
				array(
					"name" =>sprintf(__("Testimonial %d Author Meta Info link (Optional)&#x200E;",'theme_admin'),8),
					"id" => "link_8",
					"default" => "",
					"type" => "text"
				),
				array(
					"name" =>sprintf(__("Testimonial %d Content",'theme_admin'),8),
					"id" => "content_8",
					"default" => "",
					"type" => "textarea"
				),
			),
		),
		array(
			"name" => __("Tm 9",'theme_admin'),
			"options" => array(
				array(
					"name" =>sprintf(__("Testimonial %d Author Name",'theme_admin'),9),
					"id" => "author_9",
					"default" => "",
					"type" => "text"
				),
				array(
					"name" => sprintf(__("Testimonial %d Author Avatar",'theme_admin'),9),
					"id" => "avatar_9",
					"size" => 90,
					"default" => "",
					"type" => "upload",
				),
				array(
					"name" => sprintf(__("Testimonial %d Author Meta Info",'theme_admin'),9),
					"id" => "meta_9",
					"default" => "",
					"type" => "text"
				),
				array(
					"name" =>sprintf(__("Testimonial %d Author Meta Info link (Optional)&#x200E;",'theme_admin'),9),
					"id" => "link_9",
					"default" => "",
					"type" => "text"
				),
				array(
					"name" =>sprintf(__("Testimonial %d Content",'theme_admin'),9),
					"id" => "content_9",
					"default" => "",
					"type" => "textarea"
				),
			),
		),
		array(
			"name" => __("Tm 10",'theme_admin'),
			"options" => array(
				array(
					"name" =>sprintf(__("Testimonial %d Author Name",'theme_admin'),10),
					"id" => "author_10",
					"default" => "",
					"type" => "text"
				),
				array(
					"name" => sprintf(__("Testimonial %d Author Avatar",'theme_admin'),10),
					"id" => "avatar_10",
					"size" => 100,
					"default" => "",
					"type" => "upload",
				),
				array(
					"name" => sprintf(__("Testimonial %d Author Meta Info",'theme_admin'),10),
					"id" => "meta_10",
					"default" => "",
					"type" => "text"
				),
				array(
					"name" =>sprintf(__("Testimonial %d Author Meta Info link (Optional)&#x200E;",'theme_admin'),10),
					"id" => "link_10",
					"default" => "",
					"type" => "text"
				),
				array(
					"name" =>sprintf(__("Testimonial %d Content",'theme_admin'),10),
					"id" => "content_10",
					"default" => "",
					"type" => "textarea"
				),
			),
		),
	),
	"custom" => $custom_script,
	"init" => $init_script,
);
