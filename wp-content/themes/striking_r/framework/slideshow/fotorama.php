<?php
class Theme_Slideshow_Fotorama {
	public $add_script = false ;


	public function __construct(){
		add_action('wp_footer', array($this, 'header'));
	}
	
	public function header() {
		if($this->add_script){
			wp_enqueue_script('fotorama-init');
			wp_enqueue_style('fotorama-css');
		}
	}
	public function render($images, $options, $shortcode = false) {
		if(empty($images))
			return '';

		$this->add_script = true;

		if(!isset($options['width'])){
			$options['width'] = 960;
		}
		$width = (int)$options['width'];


		$slide_id = md5(rand(6, 10000).time().serialize($images));//md5(serialize($images));
		$caption = $options['captions'];
		$captionpos = $options['captionposition'];
		
		$data_options = '';
		foreach($options as $key=>$value){
			if($value === true){
				$value = 'true';
			}
			if($value === false){
				$value = 'false';
			}

			if(in_array($key, array('width','minwidth','height','minheight','maxheight','ratio')) && $value === 0){
				continue;
			}


			$data_options .= ' data-'.$key.'="'.$value.'"';
		}

		$output = <<<HTML
<div id="fotorama{$slide_id}" class="fotorama" data-width="100%" data-auto="false"{$data_options}>
HTML;
		$i = 1;
		foreach($images as $image) {
			
			$image_src = theme_get_image_src($image['source'], 'full');
			
			if (isset($image['title'])) $img_title= $image['title']; else $img_title='';
			if (isset($image['alt'])) $img_alt= $image['alt']; else $img_alt=$title;
			
			if($caption && isset($image['title'])) {
				$output .= '<img src="'.$image_src.'" title="'.$img_title.'" alt="'.$img_alt.'" data-caption="'.$image['title'].'" />';
			}else{
				$output .= '<img src="'.$image_src.'" title="'.$img_title.'" alt="'.$img_alt.'" />';
			}
			$i++;
		}
	switch($captionpos){
		case 'bottomleft':
			$captionposition = 'bottom:0;text-align:left;';
			break;
		case 'bottomright':
			$captionposition = 'bottom:0;text-align:right;';
			break;
		case 'bottomcenter':
			$captionposition = 'bottom:0;text-align:center;';
			break;
		case 'topleft':
			$captionposition = 'top:0;text-align:left;';
			break;
		case 'topright':
			$captionposition = 'top:0;text-align:right;';
			break;
		case 'topcenter':
			$captionposition = 'top:0;text-align:center;';
			break;
		default:
			$captionposition = 'bottom:0;text-align:left;';
	}
		$output .= <<<HTML
</div>
HTML;

$fotorama_css = <<<HTML
#fotorama{$slide_id}.fotorama .fotorama__caption {
	{$captionposition}
}
#fotorama{$slide_id} img {
	max-width: {$options['width']}px;
HTML;
		if(isset($options['height']) && !empty($options['height'])){
			$fotorama_css .= <<<HTML

	max-height: {$options['height']}px;
HTML;
		}
		$fotorama_css .= <<<HTML
}

HTML;
if (isset($options['captionfullwidth']) && !empty($options['captionfullwidth']) || isset($options['fotorama_caption_bg']) && !empty($options['fotorama_caption_bg']) || isset($options['fotorama_caption_text']) && !empty($options['fotorama_caption_text'])) {
			$fotorama_css .= <<<HTML
#fotorama{$slide_id}.fotorama .fotorama__caption__wrap{
HTML;
if (isset($options['captionfullwidth']) && $options['captionfullwidth']=='true'){
	$fotorama_css .= <<<HTML

width:100%;
HTML;
}
if (isset($options['fotorama_caption_bg']) && !empty($options['fotorama_caption_bg'])) {
	$fotorama_css .= <<<HTML

background-color:{$options['fotorama_caption_bg']} !important;
HTML;
}
if (isset($options['fotorama_caption_text']) && !empty($options['fotorama_caption_text'])) {
	$fotorama_css .= <<<HTML

color:{$options['fotorama_caption_text']}!important;
HTML;
}
	$fotorama_css .= <<<HTML

}

HTML;
}
		if (!empty($fotorama_css)) theme_add_css_to_footer($fotorama_css);
		return $output;
	}
}