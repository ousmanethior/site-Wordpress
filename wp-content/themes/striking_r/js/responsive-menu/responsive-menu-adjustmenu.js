(function($) {
    'use strict';
	
	$.fn.hasParentRpm = function(objs) {
	  // ensure that objs is a jQuery array
	  objs = $(objs); var found = false;
	  $(this[0]).parents().andSelf().each(function() {
		if ($.inArray(this, objs) != -1) {
		  found = true;
		  return false; // stops the each...
		}
	  });
	  return found;
	}

    $(document).ready(function() {
			function checkAdminBarHeight() {
			// if adminbar exist (should check for visible?) then add margin to our navbar
			$('button#responsive-menu-pro-button').css('top','');
			$('#responsive-menu-pro-container').css('margin-top','');
			$('#responsive-menu-pro-header').css('top','');
			
			$('button#responsive-menu-button').css('top','');
			$('#responsive-menu-container').css('margin-top','');
			$('#responsive-menu-header').css('top','');
			
			var $wpAdminBar = $('#wpadminbar');

			if ($wpAdminBar.length) {
				var $adminBar=$wpAdminBar.height();
				
				var $responsiveMenuButton = $('button#responsive-menu-pro-button');
				if (!$responsiveMenuButton.length) $responsiveMenuButton=$('button#responsive-menu-button');
				
				if ($responsiveMenuButton.length) {
					var menuButtonHasHeaderParent = $responsiveMenuButton.hasParentRpm('#header');
					var $menuButtonContainer = $('#responsive-menu-pro-container');
					if (!$menuButtonContainer.length) $menuButtonContainer=$('#responsive-menu-container');
					var $menuButtonContainerPos=parseInt($menuButtonContainer.css('top'));
					var $menuButton=parseInt($responsiveMenuButton.offset().top);
					var $buttonPosition= $responsiveMenuButton.css('position');
					if ($buttonPosition=='absolute' && menuButtonHasHeaderParent==false||$buttonPosition=='fixed' ) {
						if ($menuButton<=$adminBar) {
							$('button#responsive-menu-pro-button').css('top', $wpAdminBar.height());
							$('button#responsive-menu-button').css('top', $wpAdminBar.height());
						}
					}
					if ($menuButtonContainerPos<=$adminBar) {
						$('#responsive-menu-pro-container').css('margin-top', $wpAdminBar.height());
						$('#responsive-menu-container').css('margin-top', $wpAdminBar.height());
					}
				}

				var $responsiveHeaderBar = $('#responsive-menu-pro-header')
				if (!$responsiveHeaderBar.length) $responsiveHeaderBar=$('#responsive-menu-header');
				if ($responsiveHeaderBar.length) {
					var headerBarHasHeaderParent = $responsiveHeaderBar.hasParentRpm('#header');
					var $headerBarPosition= $responsiveHeaderBar.css('position');
					if ($headerBarPosition=='absolute' && headerBarHasHeaderParent==false||$headerBarPosition=='fixed' ) {
						//var $headertop = parseInt($('#responsive-menu-pro-header').css('top'));
						//var $responsiveHeaderBar = $('#responsive-menu-pro-header');
						var $headertop=parseInt($responsiveHeaderBar.offset().top)
						if ($headertop <= $adminBar) {
							$('#responsive-menu-pro-header').css('top', $wpAdminBar.height());
							$('#responsive-menu-header').css('top', $wpAdminBar.height());
						}
					}
				}

			}
		}

		if (typeof responsive_menu_location!='undefined'){
			if (responsive_menu_location!= 'manual') {
				var $responsiveMenuButton = $('button#responsive-menu-pro-button');
				if (!$responsiveMenuButton.length) $responsiveMenuButton=$('button#responsive-menu-button');
				if ($responsiveMenuButton.length) {
					var menuButtonHasHeaderParent = $responsiveMenuButton.hasParentRpm('#header');
					if (!$responsiveMenuButton.hasParentRpm('#header')) {
						var $menuButtonContainer = $('#responsive-menu-pro-container');
						if (!$menuButtonContainer.length) $menuButtonContainer=$('#responsive-menu-container');
						var $responsiveMenuMask = $('#responsive-menu-pro-mask');
						if (!$responsiveMenuMask.length) $responsiveMenuMask=$('#responsive-menu-mask');

						if (responsive_menu_location=='header') {
							var hasLocation = $('#header');
							if (!hasLocation.length) responsive_menu_location='body';
						}

						switch (responsive_menu_location) {
							case 'body':
								if ($responsiveMenuMask.length) $responsiveMenuMask.prependTo("body");
								$menuButtonContainer.prependTo("body");
								$responsiveMenuButton.prependTo("body");
							break;
							case 'header':
								if ($responsiveMenuMask.length) $responsiveMenuMask.prependTo("#header");
								$menuButtonContainer.prependTo("#header");
								$responsiveMenuButton.prependTo("#header");
							break;
						}
					}
				}
			}
		} 
		checkAdminBarHeight();
		$(window).scroll(function() {
			var wpAdminBarPosition = $('#wpadminbar').css('position');
			if (wpAdminBarPosition != 'fixed') checkAdminBarHeight();
		});
		$(window).resize(function() {
			checkAdminBarHeight();
		});
    });

})(jQuery);