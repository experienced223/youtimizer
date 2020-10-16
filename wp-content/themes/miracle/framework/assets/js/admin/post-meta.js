/*
 * Show/hide Post Format meta boxes as needed
 */

jQuery(document).ready(function($) {

	// Post
	var quoteOptions	= $('#miracle-metabox-quote');
	var quoteTrigger	= $('#post-format-quote');

	var videoOptions	= $('#miracle-metabox-video');
	var videoTrigger	= $('#post-format-video');

	var audioOptions	= $('#miracle-metabox-audio');
	var audioTrigger	= $('#post-format-audio');

	var postFormatGroup = $('#post-formats-select input');

	quoteOptions.hide();
	videoOptions.hide();
	audioOptions.hide();

	postFormatGroup.change(function() {
		quoteOptions.hide();
		videoOptions.hide();
		audioOptions.hide();
		if ($(this).val() == 'quote') {
			quoteOptions.show();
		} else if ($(this).val() == 'video') {
			videoOptions.show();
		} else if ($(this).val() == 'audio') {
			audioOptions.show();
		}
	});
	if (quoteTrigger.is(':checked')) {
		quoteOptions.show();
	}
	if (videoTrigger.is(':checked')) {
		videoOptions.show();
	}
	if (audioTrigger.is(':checked')) {
		audioOptions.show();
	}

	// Page
	var pageHeaderStyle = $("#miracle-metabox-page-settings [name='_miracle_header_style']:checked");
	if ( pageHeaderStyle.val() != 'map' ) {
		$("#miracle-metabox-page-settings .header-style-map").css({'height': '0', 'overflow': 'hidden'});
		$("#miracle-metabox-page-settings .header-style-map").addClass("no-margin");
	}
	$('#miracle-metabox-page-settings div[class^="rwmb-field rwmb-"]').each(function() {
		$(this).filter(
			function(){ return this.className.match(/header-style-(.*)-hidden/i); }
		).show();
	});
	$("#miracle-metabox-page-settings .header-style-" + pageHeaderStyle.val() + "-hidden").hide();
	$('#miracle-metabox-page-settings div[class^="rwmb-field rwmb-"]').each(function() {
		$(this).filter(
			function(){ return this.className.indexOf("-map-") == -1 && this.className.match(/header-style-(.*)-visible/i); }
		).hide();
	});
	$("#miracle-metabox-page-settings .header-style-" + pageHeaderStyle.val() + "-visible").show();

	$("#miracle-metabox-page-settings [name='_miracle_header_style']").click(function() {
		if ( $(this).val() == 'map' ) {
			$("#miracle-metabox-page-settings .header-style-map").css({'height': 'auto', 'overflow': 'visible'});
			$("#miracle-metabox-page-settings .header-style-map").removeClass("no-margin");
		} else {
			$("#miracle-metabox-page-settings .header-style-map").css({'height': '0', 'overflow': 'hidden'});
			$("#miracle-metabox-page-settings .header-style-map").addClass("no-margin");
		}
		$('#miracle-metabox-page-settings div[class^="rwmb-field rwmb-"]').each(function() {
			$(this).filter(
				function(){ return this.className.match(/header-style-(.*)-hidden/i); }
			).show();
		});
		$("#miracle-metabox-page-settings .header-style-" + $(this).val() + "-hidden").hide();
		$('#miracle-metabox-page-settings div[class^="rwmb-field rwmb-"]').each(function() {
			$(this).filter(
				function(){ return this.className.match(/header-style-(.*)-visible/i); }
			).hide();
		});
		$("#miracle-metabox-page-settings .header-style-" + $(this).val() + "-visible").show();

		var headerCaptionStyleVal = $("#miracle-metabox-page-settings #_miracle_header_caption").val();
		$("#miracle-metabox-page-settings .header-caption-" + headerCaptionStyleVal + "-hidden").hide();
	});

	var headerCaptionStyle = $("#miracle-metabox-page-settings #_miracle_header_caption");
	$("#miracle-metabox-page-settings .header-caption-" + headerCaptionStyle.val() + "-hidden").hide();
	headerCaptionStyle.change(function() {
		$('#miracle-metabox-page-settings div[class^="rwmb-field rwmb-"]').each(function() {
			$(this).filter(
				function(){ return this.className.match(/header-caption-(.*)-hidden/i); }
			).show();
		});
		$("#miracle-metabox-page-settings .header-caption-" + $(this).val() + "-hidden").hide();
	});

	if ($("#miracle-metabox-page-sidebar [name='_miracle_sidebar_position']:checked").val() == 'disabled') {
		$("#_miracle_sidebar_widget_area").closest(".rwmb-field").hide();
	}
	$("#miracle-metabox-page-sidebar [name='_miracle_sidebar_position']").click(function() {
		if ($(this).val() == 'disabled') {
			$("#_miracle_sidebar_widget_area").closest(".rwmb-field").hide();
		} else {
			$("#_miracle_sidebar_widget_area").closest(".rwmb-field").show();
		}
	});


	// Page - portfolio settings
	var pageTemplateGroup = $('#page_template');

	var portfolioOptions = $('#miracle-meta-box-portfolio-page');
	var portfolioTrigger = $('#page_template option[value="template-portfolio.php"]');

	if ( portfolioTrigger.is(':checked') ) {
		portfolioOptions.css('display', 'block');
	} else {
		portfolioOptions.css('display', 'none');
	}

	pageTemplateGroup.change( function() {
	if ( portfolioTrigger.is(':checked') ) {
		portfolioOptions.css('display', 'block');
	} else {
		portfolioOptions.css('display', 'none');
	}
	});


	// Portfolio - Portfolio Item Settings
	var portfolioVideoGroup = $("#miracle-metabox-portfolio-video");
	var portfolioMediaTypeTrigger = $("[name='_miracle_portfolio_item_media_type']");
	var portfolioMediaTypeChecked = $("[name='_miracle_portfolio_item_media_type']:checked");
	if (portfolioMediaTypeChecked.val() == 'video') {
		portfolioVideoGroup.show();
	} else {
		portfolioVideoGroup.hide();
	}

	if (portfolioMediaTypeChecked.val() == 'gallery') {
		$("[for='_miracle_portfolio_item_gallery_view_style']").closest(".rwmb-field").show();
	} else {
		$("[for='_miracle_portfolio_item_gallery_view_style']").closest(".rwmb-field").hide();
	}
	if (portfolioMediaTypeChecked.val() == 'gallery' &&
		$("[name='_miracle_portfolio_item_gallery_view_style']:checked").val().indexOf('gallery') === 0) {
		$("[for='_miracle_portfolio_item_gallery_columns']").closest(".rwmb-field").show();
	} else {
		$("[for='_miracle_portfolio_item_gallery_columns']").closest(".rwmb-field").hide();
	}
	portfolioMediaTypeTrigger.click(function() {
		if ($(this).val() == 'gallery') {
			$("[for='_miracle_portfolio_item_gallery_view_style']").closest(".rwmb-field").show();
			if ($("[name='_miracle_portfolio_item_gallery_view_style']:checked").val().indexOf('gallery') === 0) {
				$("[for='_miracle_portfolio_item_gallery_columns']").closest(".rwmb-field").show();
			} else {
				$("[for='_miracle_portfolio_item_gallery_columns']").closest(".rwmb-field").hide();
			}
		} else {
			$("[for='_miracle_portfolio_item_gallery_view_style']").closest(".rwmb-field").hide();
			$("[for='_miracle_portfolio_item_gallery_columns']").closest(".rwmb-field").hide();
		}

		if ($(this).val() == 'video') {
			portfolioVideoGroup.show();
		} else {
			portfolioVideoGroup.hide();
		}
	});
	$("[name='_miracle_portfolio_item_gallery_view_style']").click(function() {
		if ($(this).val().indexOf('gallery') === 0) {
			$("[for='_miracle_portfolio_item_gallery_columns']").closest(".rwmb-field").show();
		} else {
			$("[for='_miracle_portfolio_item_gallery_columns']").closest(".rwmb-field").hide();
		}
	});

	// Page Settings

});