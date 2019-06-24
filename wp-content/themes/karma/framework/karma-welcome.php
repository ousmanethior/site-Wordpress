<?php
$wordpress_version = get_bloginfo('version');
$karma_theme = wp_get_theme();
$karma_theme_version = $karma_theme->get( 'Version' );
?>
<div class="wrap about-wrap">

<h1><?php printf( __( 'Welcome to Karma&nbsp;%s' ), $karma_theme_version ); ?></h1>

<div class="about-text">Congratulations! Karma is successfully installed! Here are some helpful resources to get you started on your new website.</div>

<div class="wp-badge"><?php //printf( $karma_theme_version ); ?></div>

<!-- <a href="<?php echo esc_attr( admin_url( 'themes.php?page=siteoptions' ) ) ?>" class="button button-primary" style="margin: 5px 0 25px 0;">Site Options</a> -->

<!-- <a href="#">Online Training Videos <span class="dashicons dashicons-external"></span></a> -->

<h3 class="nav-tab-wrapper">
	<a href="?page=karma-welcome" class="nav-tab nav-tab-active">Getting Started</a>
	<!-- <a href="?page=karma-welcome&amp;section=support" class="nav-tab">Support</a> -->
</h3>

<?php // if($_GET['section']==''): ?>


<div class="karma_welcome-feature karma-feature-section">

	<div class="three-col">
		<span class="dashicons dashicons-admin-plugins"></span>
		<h3>1. Install Plugins</h3>
		<p>Power up Karma by installing the included plugins such as Visual Composer, Karma Builder, Demo Importer, Revolution Slider, LayerSlider and more.</p>
		<p><a href="<?php echo esc_attr( admin_url( 'themes.php?page=tgmpa-install-plugins' ) ) ?>" class="button button-primary">Install Plugins</a></p>
	</div>

	<div class="three-col">
		<span class="dashicons dashicons-download"></span>
		<h3>2. Import Demo Data</h3>
		<!-- <p>Get a headstart on your website by importing the demo content. Simply install the Karma Demo Content Plugin and click the link below.</p> -->
		<p>Get a headstart on your website by importing the demo data. The <em>One Click Demo Import Plugin</em> provided in Step 1 must first be installed before proceeding.</p>
		<p><a href="<?php echo esc_attr( admin_url( 'themes.php?page=pt-one-click-demo-import' ) ) ?>" class="button button-primary">Import Demo Data</a></p>
	</div>

	<div class="three-col.last">
		<span class="dashicons dashicons-admin-settings"></span>
		<h3>3. Configure Karma</h3>
		<p>Choose a color scheme, upload your company logo and configure the entire website in Karma's Site Options Panel.</p>
		<p><a href="<?php echo esc_attr( admin_url( 'themes.php?page=siteoptions' ) ) ?>" class="button button-primary">Appearance > Site Options</a></p>
	</div>

</div>

<div style="width: 44%;float:left;">
	<br />
	<h3 style="line-height:15px;text-align:left;"><span class="dashicons dashicons-format-video" style="margin-right:5px;"></span> Tutorial Videos</h3>
	<p>A complete set of instructional training videos to learn every aspect of using the Karma theme.</p>
	<p><a href="http://vimeopro.com/truethemes/karma-4" target="_blank" class="button button-secondary" style="box-shadow:0 1px 3px rgba(0, 0, 0, 0.2);">Tutorial Videos</a></p>
</div>

<div style="width: 44%;float:right;">
	<br />
	<h3 style="line-height:15px;text-align:left;"><span class="dashicons dashicons-admin-users" style="margin-right:5px;"></span> Help Center</h3>
	<p>Need help? Please visit our secure Help Center, we would be delighted to help you!</p>
	<p><a href="https://help.truethemes.net" target="_blank" class="button button-secondary" style="box-shadow:0 1px 3px rgba(0, 0, 0, 0.2);">https://help.truethemes.net</a></p>
</div>

<br style="clear: both;" />

<div style="float: left; border: 1px solid rgb(221, 221, 221); border-radius: 3px; background: rgb(255, 255, 255) none repeat scroll 0% 0%;padding:1.2%;margin-top:2%;box-shadow:0 1px 3px rgba(0, 0, 0, 0.2);">
	<h3 style="line-height:15px;text-align:left;"><span class="dashicons dashicons-editor-help"  style="margin-right:5px;"></span> Did you know</h3>
	<p>This page can be accessed anytime <a href="<?php echo esc_attr( admin_url( 'themes.php?page=karma-welcome' ) ) ?>">Appearance > Karma Welcome</a></p>
</div>


<?php //endif; ?>


<?php
/* if($_GET['section']=='support'):

require_once(TRUETHEMES_GLOBAL   . '/karma-welcome/karma-support.php');

endif; */
?>

</div><!-- /.wrap -->