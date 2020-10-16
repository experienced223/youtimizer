/*
 * Title:   Miracle | Responsive Multi-Purpose Wordpress Theme - Main Javascript
 * Author:  http://themeforest.net/user/soaptheme
 */


"use strict";

var stGlobals = {};
stGlobals.isMobile  = (/(Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini|windows phone)/.test(navigator.userAgent));
stGlobals.isSafari = (/(Safari)/.test(navigator.userAgent)) && (!(/(Chrome)/.test(navigator.userAgent)));
stGlobals.isMobileWebkit = /WebKit/.test(navigator.userAgent) && /Mobile/.test(navigator.userAgent);

String.prototype.lpad = function(padString, length) {
    var str = this;
    if (typeof str == "undefined" || typeof length == "undefined") {
        return;
    }
    while (str.length < length)
        str = padString + str;
    return str;
}

sjq(document).ready(function($) {

    // back to top
    $("body").on("click", ".back-to-top", function(e) {
        e.preventDefault();
        $("html,body").animate({scrollTop: 0}, 800);
    });
    
    // parallax
    if (!stGlobals.isMobileWebkit && $(".parallax").length > 0 && $(".parallax-elem").length < 1) {
        $.stellar({
            responsive: true,
            horizontalScrolling: false
        });
    }

    // parallax for wekbit mobile
    if (stGlobals.isMobileWebkit) {
        $(".parallax").css("background-attachment", "scroll");
    }

    // Fullscreen Header
    function miracleResizeFullSCreenHeader() {
        if ($(".page-title-container.header-fullscreen").length > 0) {
            var windowHeight = $(window).height();
            if ($(".page-title-container.header-fullscreen .page-title").length > 0) {
            $(".page-title-container.header-fullscreen .page-title").css("height", windowHeight - $("#wpadminbar").outerHeight() - $(".page-title-container.header-fullscreen .breadcrumbs").height() + "px");
            } else if ($(".page-title-container.header-fullscreen .banner").length > 0) {
                $(".page-title-container.header-fullscreen .banner").css("height", windowHeight - $("#wpadminbar").outerHeight() - $(".page-title-container.header-fullscreen .breadcrumbs").height() + "px");
            }
        }
    }
    miracleResizeFullSCreenHeader();

    // tooltip
    if ( $.fn.tooltip ) {
        $("[data-toggle=tooltip]").tooltip();
    }

    // alert
    $("body").on("click", ".alert > .close", function() {
        $(this).parent().fadeOut(300);
    });

    // accordion & toggles
    $(".panel-group .collapse").on("show.bs.collapse", function(e) {
        $(this).closest(".panel-group").find("[href='#" + $(this).attr("id") + "']").addClass("active");
    });
    $(".panel-group .collapse").on("hide.bs.collapse", function(e) {
        $(this).closest(".panel-group").find("[href='#" + $(this).attr("id") + "']").removeClass("active");
    });
    function initSoapToggle(parentObj) {
        $(parentObj).find(".panel-group .collapse").each(function() {
            if ($(this).hasClass("in")) {
                var contentId = $(this).attr("id");
                if (typeof contentId != "undefined") {
                    $(this).closest(".panel-group").find("[href='#" + contentId + "']").addClass("active");
                }
            }
        });
    }
    initSoapToggle("body");

    // main search
    $("body").on("click", function(e) {
        var target = $(e.target);
        if($("#header .mini-search .main-nav-search-form").hasClass("active") && !target.is("#header .mini-search form *")) {
            $("#header .mini-search .main-nav-search-form").fadeOut();
            $("#header .mini-search .main-nav-search-form").removeClass("active");
        }
    });
    $("#header .mini-search > a").on("click", function(e) {
        e.preventDefault();
        $(this).parent().children(".main-nav-search-form").fadeIn("fast", function() {
            $(this).addClass("active");
        });
        $(this).parent().children(".main-nav-search-form").find("input[type=text]").focus();
    });

    if ( $.fn.magnificPopup ) {
        $(".soap-gallery.metro-style").magnificPopup({ 
            delegate: 'a.image, a.soap-mfp-popup',
            type: 'image',
            gallery: { enabled:true }
            // other options
        });
        $(".post-slider").each(function() {
            $(this).magnificPopup({ 
                delegate: 'a.soap-mfp-popup',
                type: 'image',
                gallery: { enabled:true }
                // other options
            });
        });
        $(".post-wrapper .iso-container, .post-wrapper.iso-container").magnificPopup({ 
            delegate: 'a.soap-mfp-popup',
            type: 'image',
            gallery: { enabled:true }
            // other options
        });
        /*$(".iso-container").magnificPopup({ 
            delegate: 'a.soap-mfp-popup',
            type: 'image',
            gallery: { enabled:true }
            // other options
        });*/
        /*$(".flickr-feeds").magnificPopup({ 
            delegate: 'li a',
            type: 'image',
            gallery: {enabled:true}
            // other options
        });*/
        $(".soap-gallery:not(.metro-style)").magnificPopup({ 
            delegate: 'a.image.sgImg, a.soap-mfp-popup',
            type: 'image',
            gallery: {enabled:true}
            // other options
        });
    }

    // menu position to left
    $("body").on("mouseenter", "#nav .menu-item-has-children", function() {
        if ($(this).hasClass("mega-menu-item") || $(this).closest(".mega-menu-item").length > 0) {
            return;
        }
        $(this).children(".sub-nav").removeClass("position-left");
        try {
            if ($(this).children(".sub-nav").offset().left + $(this).children(".sub-nav").width() > $("body").width()) {
                $(this).children(".sub-nav").addClass("position-left");
            }
        } catch (e) { }
    });
    // main menu
    function resizeMainMenuItemHeight() {
        if ($("#nav .hidden-mobile").is(":hidden")) {
            return;
        }
        var childrenItemWidth = 0;
        if ($("#nav .hidden-mobile").height() > $("#nav .hidden-mobile > li").first().height() + 10 ) {
            $("#nav").addClass("many-columns");
        } else {
            $("#nav").removeClass("many-columns");
        }
    }
    $(window).resize(function() {
        resizeMainMenuItemHeight();
    });
    resizeMainMenuItemHeight();

    // mobile menu
    $(".mobile-nav .open-subnav").on("click", function() {
        $(this).parent().toggleClass("opened");
        $(this).parent().children(".sub-nav").stop().slideToggle(400);
    });

    $("#nav, .mobile-nav").find("a[href='#']").click(function(e) {
        e.preventDefault();
    });

    // sticky menu
    var sticky_header_offset_top = ($("#header .header-inner").length > 0 ? $("#header .header-inner:first").offset().top : 0),
        adminbarHeight = $("#wpadminbar").outerHeight();
    if (sticky_header_offset_top <= 0) {
        sticky_header_offset_top = adminbarHeight;
    }
    function miracle_sticky_header() {
        if ($("body").hasClass("no-sticky-menu")) {
            $("#header").removeClass("header-sticky");
            $("#header").css('top', '0');
            return;
        }
        if (stGlobals.isMobile || $("#content").length < 1 || $("#header #nav").length < 1) {
            $("#header").removeClass("header-sticky");
            $("#header").css('top', '0');
            return;
        }
        if (Modernizr.mq('only all and (max-width: 991px)')) {
            $("#header").removeClass("header-sticky");
            $("#header").css('top', '0');
            return;
        }
        if ($("body").height() <= $(window).height() + 30) {
            $("#header").removeClass("header-sticky");
            $("#header").css('top', '0');
            return;
        }
        
        if ($(window).scrollTop() > sticky_header_offset_top) {
            $("#header").addClass("header-sticky");
            $("#header").css('top', adminbarHeight + 'px');
        } else {
            $("#header").removeClass("header-sticky");
            $("#header").css('top', '0');
        }
    }
    if (!$("body").hasClass("no-sticky-menu")) {
        $(window).scroll(function() {
            miracle_sticky_header();
        });
        miracle_sticky_header();
    }

    // tab active event
    $('[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        var contentId = $(e.target).attr("href");
        // add collapsed class on each toggle
        initSoapToggle(contentId);

    });

    // custom select 
    //$(".woocommerce .variations select:not(.selector)").addClass("selector");
    if ($.fn.customSelect) {
        if (stGlobals.isSafari) {
            $("select.selector").each(function() {
                if ($(this).outerWidth() < $(this).parent().width() - 45) {
                    $(this).width($(this).width() + 45); //plus extra width
                }
            });
        }
        $("select.selector").customSelect();
    }
    // checkbox
    $(".checkbox input[type='checkbox'], .radio input[type='radio']").each(function() {
        if ($(this).is(":checked")) {
            $(this).closest(".checkbox").addClass("checked");
            $(this).closest(".radio").addClass("checked");
        }
    });
    $(".checkbox input[type='checkbox']").bind("change", function() {
        if ($(this).is(":checked")) {
            $(this).closest(".checkbox").addClass("checked");
        } else {
            $(this).closest(".checkbox").removeClass("checked");
        }
    });
    // radio
    $("body").on("change", ".radio input[type='radio']", function(event, ui) {
        if ($(this).is(":checked")) {
            var name = $(this).prop("name");
            if (typeof name != "undefined") {
                $(".radio input[name='" + name + "']").closest('.radio').removeClass("checked");
            }
            $(this).closest(".radio").addClass("checked");
        }
    });
    // placeholder for ie8, 9
    try {
        $('input, textarea').placeholder();
    } catch (e) {}

    // star rating
    $(".star-rating").each(function() {
        var stars = $(this).children("span").data("stars");
        if (stars) {
            $(this).children("span").css("width", stars * 2 * 10 + "%");
        }
    });
    $(".input-star-rating input[type=radio]").each(function() {
        $("<span></span>").insertAfter($(this));
    });

    // hover effect
    $('.image.hover-style3').each( function() { $(this).hoverdir(); } );

    function css3animationEffect() {
        if($().waypoint && Modernizr.mq('only all and (min-width: 768px)')) {
            // animation effect
            $('.animated').waypoint(function() {
                var type = $(this).data("animation-type");
                if (typeof type == "undefined" || type == false) {
                    type = "fadeIn";
                }
                $(this).addClass(type);
                
                var duration = $(this).data("animation-duration");
                if (typeof duration == "undefined" || duration == false) {
                    duration = "1";
                }
                $(this).css("animation-duration", duration + "s");
                
                var delay = $(this).data("animation-delay");
                if (typeof delay != "undefined" && delay != false) {
                    $(this).css("animation-delay", delay + "s");
                }
                
                $(this).css("visibility", "visible");

                setTimeout(function() {
                  $.waypoints('refresh');
                }, 1000);
            }, {
                triggerOnce: true,
                offset: 'bottom-in-view'
            });
        }
    }
    css3animationEffect();

    // circular progress
    if ($(".circle-progress").length) {
        $(".circle-progress").circliful();
    }

    // progress bar
    $(".progress-bar-icons").each(function() {
        var number = $(this).data("number");
        if (number > $(this).children(".progress").length) {
            number = $(this).children(".progress").length;
        }
        if (typeof number == "undefined") {
            return;
        }
        if ($(this).hasClass("animate-progress")) {
            $(this).waypoint(function() {
                var progress = null, index = 0, $_this = $(this);
                progress = setInterval(function() {
                    if (index >= number) {
                        clearInterval(progress);
                        return;
                    }
                    $_this.children(".progress").eq(index).addClass("active");
                    index ++;
                }, 200);
                setTimeout(function() { $.waypoints('refresh'); }, 1000);
            }, {
                triggerOnce: true,
                offset: 'bottom-in-view'
            });
        } else {
            for (var i = 0; i < number; i++) {
                $(this).children(".progress").eq(i).addClass("active");
            }
        }
    });

    $(".progress-bar").each(function() {
        if ($(this).closest(".progress-bar-container").hasClass("style-vertical")) {
            $(this).find(".progress-inner").height($(this).find(".progress-inner").data("percent") + "%");
        } else {
            $(this).find(".progress-inner").width($(this).find(".progress-inner").data("width") + "%");
        }
    });
    function display_animate_progress() {
        $(".progress-bar.animate-progress .progress-percent > span").html('');
        $(".progress-bar.animate-progress").each(function() {
            if ($(this).closest(".progress-bar-container").hasClass("style-vertical")) {
                $(this).find(".progress-inner").height(0);
            } else {
                $(this).find(".progress-inner").width(0);
            }
        });

        if ( !$.fn.waypoint ) {
            return;
        }

        $(".progress-bar.animate-progress").waypoint(function() {
            if ($(this).find(".progress-inner").length > 0) {
                var innerObj = $(this).find(".progress-inner")
                    ,width;
                if ($(this).closest(".progress-bar-container").hasClass("style-vertical")) {
                    width = innerObj.data("percent");
                } else {
                    width = innerObj.data("width");
                }
                if (typeof width != "undefined") {
                    var $_this = $(this)
                        ,index = 0;
                    if ($_this.closest(".progress-bar-container").length > 0) {
                        index = $_this.index();
                    }
                    setTimeout(function() {
                        var current_progress = 0
                            ,progress = null;
                        progress = setInterval(function() {
                            if (current_progress >= width || current_progress >= 100) {
                                clearInterval(progress);
                                return;
                            }
                            current_progress += 1;
                            if (innerObj.closest(".progress-bar-container").hasClass("style-vertical")) {
                                innerObj.css("height", current_progress + "%");
                            } else {
                                innerObj.css("width", current_progress + "%");
                            }
                            $_this.find(".progress-percent > span").text(current_progress + "%");
                        }, 10);
                    }, 100 * index);
                }
            }
            setTimeout(function() { $.waypoints('refresh'); }, 1000);
        }, {
            triggerOnce: true,
            offset: 'bottom-in-view'
        });
    }
    if (!stGlobals.isMobile) {
        // display animate progress bar
        display_animate_progress();

        // display counter
        if ( $.fn.waypoint ) {
            $('.counters-box').waypoint(function() {
                $(this).find('.display-counter').each(function() {
                    var value = $(this).data('value');
                    $(this).countTo({from: 0, to: value, speed: 3000, refreshInterval: 10});
                });
                setTimeout(function() { $.waypoints('refresh'); }, 1000);
            }, {
                triggerOnce: true,
                offset: '100%'
            });
        }
    }

    // fit video
    if ($.fn.fitVids) {
        $('.video-container:not(.mejs-skin) .full-video').fitVids();
    }
    function initMediaElementPlayer() {
        if ($.fn.mediaelementplayer) {
            $('.video-container.mejs-skin video').each(function() {
                var videoFormat = $(this).data("video-format");
                if (typeof videoFormat == "undefined" || videoFormat == null) {
                    return;
                }
                var aspectRatio = parseFloat(parseInt(videoFormat.split(":")[1], 10) / parseInt(videoFormat.split(":")[0], 10)),
                obj = $(this), videoWidth = -1, videoHeight = -1;

                if ($(this).closest(".parallax-elem").length > 0) {
                    videoWidth = stGlobals.isMobile ? Math.max(window.screen.availWidth, window.screen.availHeight, 1000) : window.screen.availWidth,
                    videoHeight = parseInt(videoWidth * aspectRatio, 10);

                    if ($(this).closest(".parallax-elem").height() > videoHeight) {
                        videoHeight = $(this).closest(".parallax-elem").height();
                        videoWidth = parseInt(videoHeight / aspectRatio, 10);
                    }
                    if (stGlobals.isMobileWebkit) {
                        videoWidth = window.screen.availWidth;
                        videoHeight = parseInt(videoWidth * aspectRatio, 10);
                        //if ($(this).closest(".parallax-elem").length > 0) {
                            $(this).closest(".parallax-elem").css("min-height", videoHeight + "px");
                        //}
                        $(this).closest(".page-title-container").addClass("ios");
                        if ($(this).closest(".page-title-container").length > 0 && $(this).closest(".page-title-container").height() > videoHeight) {
                            $(this).closest(".page-title-container").find(".page-title").outerHeight(videoHeight - $(this).closest(".page-title-container").find(".breadcrumbs").height());
                        }
                    }
                } else if ($(this).closest(".full-video").length > 0) {
                    videoWidth = $(".video-container").width();
                    videoHeight = videoWidth * aspectRatio;
                }
                $(this).mediaelementplayer({
                    /*defaultVideoWidth: videoWidth,
                    defaultVideoHeight: videoHeight,*/
                    videoWidth: videoWidth,
                    videoHeight: videoHeight,
                    loop: true,
                    success: function(media, node, player) {
                        if (mejs.MediaFeatures.isiOS && (media.pluginType == "youtube" || media.pluginType == "vimeo")) {
                            $('.mejs-layers, .mejs-controls').hide();
                        }
                        var $this;
                        if (media.id) {
                            $this = $("#" + media.id);
                        } else {
                            $this = $(media);
                        }
                        if (media.pluginType == "youtube" || media.pluginType == "vimeo") {
                            $this = obj.closest(".mejs-container").find(".mejs-mediaelement iframe");
                            if ($this.length < 1 && media.id && $("#" + media.id + "_container").length > 0) {
                                $this = $("#" + media.id + "_container");
                            }
                        }

                        if ($this.prop("width")) {
                            $this.css("width", $this.prop("width") + "px");
                        }
                        if ($this.prop("height")) {
                            $this.css("height", $this.prop("height") + "px");
                        }

                        if (media.pluginType == "flash" || media.pluginType == "youtube" || media.pluginType == "vimeo") {
                            if (obj.data("stellar-ratio")) {
                                $this.attr("data-stellar-ratio", obj.data("stellar-ratio"));
                            }
                        } else {
                            $this.removeAttr("poster");
                            $this.css("visibility", "visible");
                            $this.width(videoWidth);
                            $this.height(videoHeight);
                        }
                        if (!stGlobals.isMobileWebkit && obj.data("stellar-ratio")) {
                            $.stellar({
                                responsive: true,
                                horizontalScrolling: false
                            });
                        }
                        media.addEventListener("ended", function(e) {
                            if ( !mejs.MediaFeatures.isiOS || (media.pluginType != "youtube" && media.pluginType != "vimeo") ) {
                                $this.closest(".mejs-container").find(".mejs-poster").show();
                            }
                        });
                        
                        resizeMediaElementPlayer(media);
                    }
                });
            });
        }
    }
    function resizeMediaElementPlayer(media) {
        if ($.fn.mediaelementplayer) {
            $('.video-container.mejs-skin').each(function() {
                var videoFormat = $(this).find("video").data("video-format");
                if (typeof videoFormat == "undefined" || videoFormat == null) {
                    return;
                }
                var aspectRatio = parseFloat(parseInt(videoFormat.split(":")[1], 10) / parseInt(videoFormat.split(":")[0], 10));
                if ($(this).closest(".parallax-elem").length > 0) {
                    var parallaxWidth = $(this).closest(".parallax-elem").width()
                        ,parallaxHeight = $(this).closest(".parallax-elem").height()
                        ,$media = $(this).find(".me-plugin").length > 0 ? $(this).find(".mejs-mediaelement object, .mejs-mediaelement embed, .mejs-mediaelement iframe") : $(this).find(".mejs-mediaelement video")
                        ,videoWidth ,videoHeight;
                    if (stGlobals.isMobileWebkit) {
                        $media.width(parallaxWidth);
                        $media.height(parallaxWidth * aspectRatio);
                        $(this).closest(".parallax-elem").css("min-height", parallaxWidth * aspectRatio + "px");
                        if ($(this).closest(".page-title-container").length > 0 && $(this).closest(".page-title-container").height() > parallaxWidth * aspectRatio) {
                            $(this).closest(".page-title-container").find(".page-title").outerHeight(parallaxWidth * aspectRatio - $(this).closest(".page-title-container").find(".breadcrumbs").height());
                        }
                        return;
                    }
                    if ($media.length > 0 && $media.width() > 0) {
                        videoWidth = $media.width();
                        videoHeight = $media.height();
                    } else {
                        videoWidth = parallaxWidth;
                        videoHeight = parallaxHeight;
                    }
                    if (videoHeight < parallaxHeight * 1.4) {
                        $media.addClass("no-parallax");
                    } else {
                        $media.removeClass("no-parallax");
                        $media.css("position", "absolute");
                        $media.css("left", "0");
                    }
                    if (stGlobals.isSafari && Math.abs(parallaxWidth - videoWidth) / 2 < 10) {
                        $media.css("margin-left", "0");
                    } else {
                        $media.css("margin-left", (parallaxWidth - videoWidth) / 2);
                    }
                    $media.css("margin-top", (parallaxHeight - videoHeight) / 2);
                } else if ($(this).children(".full-video").length > 0) {
                    //if (typeof media != "undefined" && media.pluginType == "native") {
                        var width = $(this).width();
                        $(this).height(width * aspectRatio);
                        $(this).addClass("mejs-success");
                    //}
                }
            });
        }
    }
    initMediaElementPlayer();

    $(".btn-go-back").on("click", function(e) {
        e.preventDefault();
        window.history.go(-1);
    });

	function miracleResizeSoapMapHeight() {
		if ($(".page-title-container.style-map").length > 0) {
			var diff = $(".page-title-container.style-map").height() - $(".page-title-container.style-map .page-title").outerHeight() + $("#wpadminbar").outerHeight();
			var ratio = stGlobals.isMobile ? 0.8 : 1;
			$(".page-title-container.style-map .page-title").css("max-height", $(window).height() * ratio - diff);
		}
	}
	miracleResizeSoapMapHeight();

    // woocommerce
    $("body").on("click", "#comments .btn-write-review", function(e) {
        e.preventDefault();
        $("#comments").hide();
        $("#review_form").show();
    });
    $("body").on("click", "#review_form .btn-back-reviews", function(e) {
        e.preventDefault();
        $("#review_form").hide();
        $("#comments").show();
    });
    $('.woocommerce .product-images .easyzoom').click(function(e) {
        e.preventDefault();
    });

    $(window).resize(function() {
        miracleResizeFullSCreenHeader();
        if ($.fn.customSelect) {
            $("select.selector").next("span.selector").remove();
            $("select.selector").removeClass("hasCustomSelect");
            $("select.selector").removeAttr("style");
            $("select.selector").customSelect();
        }
        miracle_sticky_header();
        css3animationEffect();
		miracleResizeSoapMapHeight();
        resizeMediaElementPlayer();
    });


    /* Woocommerce ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

    /*$(".woocommerce .btn-quick-view").magnificPopup({
        type: 'ajax',
        mainClass: 'soap-quick-view-lightbox'
    });*/
    $('body').on("click", ".product .btn-quick-view", function(e) {
        var product_id = $(this).data('id'),
            data = { action: 'miracle_ajax_product_quickview', productid: product_id },
            ajaxurl = miracleLocal.ajaxurl,
            $this = $(this).closest('.product');
        data.nonce = miracleLocal.ajaxNonce;
        if (ajaxurl == "" || ajaxurl == "#" || $this.hasClass("loading")) {
            return false;
        }
        $(".product.loading").removeClass("loading");
        $this.addClass('loading');
        $.post(ajaxurl, data, function(response) {
            if (response.success && $this.hasClass("loading")) {
                $.magnificPopup.open( {
                    mainClass: 'soap-quick-view-lightbox',
                    items: {
                        src: response.html,
                        type: 'inline'
                    },
                    callbacks: {
                        open: function() {
                            // Variation Form
                            var form_variation = $(".soap-quick-view-lightbox").find('form.variations_form');
                            if (form_variation.length > 0) {
                                form_variation.wc_variation_form();
                            }
                            if ( $.fn.tooltip ) {
                                $(".soap-quick-view-lightbox").find("[data-toggle=tooltip]").tooltip();
                            }
                            $(".soap-quick-view-lightbox .selector").customSelect();
                        },
                        change: function() {
                            
                        }
                    },
                    removalDelay: 300,
                });
            }
            $this.removeClass('loading');
        });
        e.preventDefault();
    });

    $(".ship-to-billing-address-checkbox").each(function() {
        if ( !$(this).children("input[type=checkbox]").is(":checked") ) {
            $(this).parent().parent().find(".shipping-address").hide();
        }
    });
    $(".ship-to-billing-address-checkbox input[type=checkbox]").on("click", function(e) {
        $(this).parent().parent().find(".shipping-address").slideToggle(400);
    });
});

// min height for post full
function initPostFullContentHeight() {
    sjq(".blog-posts .post-full").each(function() {
        if (sjq(this).find(".post-image").css("float") == "left") {
            var imgHeight = sjq(this).find(".post-image").height();
            sjq(this).find(".post-content").css("min-height", imgHeight);
        }
    });
    sjq(".products.layout-list .product").each(function() {
        if (sjq(this).find(".product-image").css("float") == "left") {
            var imgHeight = sjq(this).find(".product-image").outerHeight();
            sjq(this).find(".product-content").css("min-height", imgHeight - sjq(this).find(".product-action").outerHeight() - 1);
        }
    });
}


(function($) {
    $(window).load(function() {
        // slideshow
        function addOwlCarouselImageNav(elem, isInit) {
            if (!elem.hasClass("soap-gallery") || !elem.hasClass("style1") || elem.find(".owl-item").length < 2) {
                return;
            }
            if (typeof isInit == "undefined") {
                isInit = 0;
            }
            if (isInit) {
                elem.find(".owl-controls .owl-prev, .owl-controls .owl-next").append("<span class='imageholder'></span><span class='slide-index'></span>");
            }
            var owlData = elem.data('owlCarousel')
                ,nextItemIndex = isInit ? 1 : (owlData.currentItem + 1) % owlData.$owlItems.length
                ,nextItem = isInit ? elem.find(".owl-item").eq(nextItemIndex) : $(owlData.$owlItems[nextItemIndex])
                ,thumbSrc = nextItem.find("img").data("thumb");
            if (typeof thumbSrc == "undefined") {
                thumbSrc = nextItem.find("img").attr("src");
            }
            elem.find(".owl-controls .owl-next .imageholder").css("background-image", "url(" + thumbSrc + ")");
            elem.find(".owl-controls .owl-next .slide-index").text((nextItemIndex + 1 + "").lpad("0", 2) + " slide");

            var prevItemIndex = isInit ? elem.find(".owl-item").length -1 : (owlData.$owlItems.length + owlData.currentItem - 1) % owlData.$owlItems.length
                ,prevItem = isInit ? elem.find(".owl-item").eq(prevItemIndex) : $(owlData.$owlItems[prevItemIndex])
                ,thumbSrc = prevItem.data("thumb");
            if (typeof thumbSrc == "undefined") {
                thumbSrc = prevItem.find("img").attr("src");
            }
            elem.find(".owl-controls .owl-prev .imageholder").css("background-image", "url(" + thumbSrc + ")");
            elem.find(".owl-controls .owl-prev .slide-index").text((prevItemIndex + 1 + "").lpad("0", 2) + " slide");
        }

        function addOwlCarouselCaptionAnimated(elem, isInit) {
            if (typeof isInit == "undefined") {
                isInit = 0;
            }
            var owlData = null, currentObj = null;
            if (isInit) {
                currentObj = elem.find(".owl-item").eq(0);
            } else {
                owlData = elem.data('owlCarousel');
                elem.find(".owl-item").removeClass("active");
                currentObj = $(owlData.$owlItems[owlData.currentItem]);
            }
            currentObj.addClass("active");

            currentObj.find(".caption-animated").each(function() {
                var animationType = $(this).data("animation-type");
                if (typeof animationType == "undefined") {
                    return;
                }
                var animationDuration = $(this).data("animation-duration")
                    ,animationDelay = $(this).data("animation-delay");
                if (typeof animationDuration == "undefined") {
                    animationDuration = "1";
                }
                if (typeof animationDelay == "undefined") {
                    animationDelay = "0";
                }
                $(this).addClass(animationType);
                $(this).css("animation-duration", animationDuration + "s");
                $(this).css("animation-delay", animationDelay + "s");
                $(this).css("visibility", "visible");
            });
            if (isInit) {
                return;
            }

            var prevObj = $(owlData.$owlItems[owlData.prevItem]);
            prevObj.find(".caption-animated").each(function() {
                var animationType = $(this).data("animation-type");
                if (typeof animationType != "undefined") {
                    $(this).removeClass(animationType);
                    $(this).css("visibility", "hidden");
                }
            });
        }

        function initMiracleOwlCarousel(parentObj) {
            if ($.fn.owlCarousel) {
                parentObj.find(".owl-carousel").each(function() {
                    var transitionStyle = $(this).data("transitionstyle");
                    if (typeof transitionStyle == "undefined") {
                        transitionStyle = false;
                    }
                    var autoplay_val = $(this).data("autoplay");
                    if (typeof autoplay_val == "undefined") {
                        autoplay_val = false;
                    } else {
                        autoplay_val = parseInt(autoplay_val, 10);
                    }

                    var items = $(this).data("items")
                        ,isSingleItem = true;
                    if (typeof items == "undefined") {
                        items = 1;
                    } else {
                        items = parseInt(items, 10);
                        if ( items > 1 ) {
                        isSingleItem = false;
                    }
                    }
                    if (items > 1) {
                        $(this).addClass("multiple-items");
                    }
                    var options = {
                        items: items,
                        singleItem: isSingleItem,
                        slideSpeed: 700,
                        autoPlay: autoplay_val,
                        navigation: true,
                        navigationText: false,
                        pagination: true,
                        stopOnHover: true,
                        transitionStyle: transitionStyle,
                        beforeMove: addOwlCarouselImageNav,
                        afterMove: addOwlCarouselCaptionAnimated,
                        afterInit: function(elem) {
                            addOwlCarouselImageNav(elem, 1);
                            addOwlCarouselCaptionAnimated(elem, 1);
                            if (elem.hasClass("testimonials") || elem.hasClass("brand-slider")) {
                                elem.find(".owl-wrapper").equalHeights();
                            }
                            if (elem.hasClass("style6")) {
                                elem.find(".owl-controls").addClass("container");
                            }
                        },
                        afterUpdate: function(elem) {
                            if (elem.closest(".post-full").length > 0) {
                                $this = elem.closest(".post-full");
                                $this.find(".post-content").css("min-height", $this.find(".post-image").height());
                            }
                            if (elem.closest(".iso-container").length > 0) {
                                elem.closest(".iso-container").isotope("layout");
                            }
							if (elem.hasClass("testimonials") || elem.hasClass("brand-slider")) {
                                elem.find(".owl-wrapper").equalHeights();
                            }
                        }
                    };

                    var itemsCustom = $(this).data("itemsperdisplaywidth");
                    if (typeof itemsCustom != "undefined") {
                        options.itemsCustom = eval(itemsCustom);
                    } else if ( items < 5 && items >= 2 ) {
                        options.itemsCustom = [[0,2], [479, items]];
                    }
                    $(this).owlCarousel(options);
                });
            }
        }
        initMiracleOwlCarousel($("body"));

        function initSoapGalleryCarouselStyle1() {
            if ($.fn.carousel) {
                $(".soap-gallery.carousel-style1").each(function() {
                    var $this = $(this);
                    if ($this.next(".temp-carousel-style1").length > 0) {
                        $this = $this.next(".temp-carousel-style1");
                        $(this).remove();
                        $this.removeClass("temp-carousel-style1");
                        $this.show();
                    }
                    var frontWidth = $this.data("front-width")
                        ,frontHeight = $this.data("front-height");
                    if (typeof frontWidth == "undefined") {
                        frontWidth = 400;
                    } else {
                        frontWidth = parseInt(frontWidth, 10);
                    }
                    if (typeof frontHeight == "undefined") {
                        frontHeight = 300;
                    } else {
                        frontHeight = parseInt(frontHeight, 10);
                    }
                    var slidesCount = $this.data("slides")
                        ,hAlign = $this.data("halign")
                        ,vAlign = $this.data("valign");
                    if (typeof slidesCount == "undefined") {
                        slidesCount = 5;
                    } else {
                        slidesCount = parseInt(slidesCount, 10);
                    }
                    if (typeof hAlign == "undefined") {
                        hAlign = "center";
                    }
                    if (typeof vAlign == "undefined") {
                        vAlign = "center";
                    }
                    var containerWidth = 1170;
                    if (hAlign != "center") {
                        containerWidth = $(".container").width();
                        frontWidth = frontWidth / (1170 / containerWidth);
                        frontHeight = frontHeight / (1170 / containerWidth);
                    }

                    $this.clone().insertAfter($this).hide().addClass("temp-carousel-style1");
                    $this.carousel({
                        hAlign: hAlign,
                        vAlign: vAlign,
                        hMargin:0.95,
                        vMargin: 0.1,
                        backZoom: 0.8,
                        carouselWidth: containerWidth,
                        carouselHeight: frontHeight,
                        frontWidth: frontWidth,
                        frontHeight: frontHeight,
                        left: 0,
                        directionNav:false,
                        shadow:false,
                        slidesPerScroll: slidesCount,
                        reflection: false,
                        buttonNav:'none',
                    });
                    centerCarousel();
                });
            }
        }
        initSoapGalleryCarouselStyle1();

        if ($.fn.skyCarousel) {
            $(".testimonials.style2 .testimonial-carousel").skyCarousel({
                itemWidth: 92,
                itemHeight: 92,
                distance: 0,
                selectedItemDistance: 8,
                selectedItemZoomFactor: 1,
                unselectedItemZoomFactor: 0.8,
                unselectedItemAlpha: 0.5,
                motionStartDistance: 80,
                topMargin: 50,
                gradientStartPoint: 0.35,
                gradientOverlayColor: "#f5f5f5",
                gradientOverlaySize: 190,
                reflectionDistance: 1,
                reflectionAlpha: 0.3,
                reflectionVisible: true,
                reflectionSize: 50,
                selectByClick: true,

                gradientOverlayVisible: false,
                navigationButtonsVisible: false,
                showPreloader: false,
                autoSlideshow: true,
                autoSlideshowDelay: 10,
            });
        }
        function centerCarousel() {
            if (!$.fn.carousel) {
                return;
            }
            $(".soap-gallery.carousel-style1").each(function() {
                var hAlign = $(this).data("halign");
                if (typeof hAlign == "undefined") {
                    hAlign = "center";
                }
                if (hAlign != "center") {
                    return;
                }
                var containerWidth = $(".container").width();
                if ($(this).width() > containerWidth) {
                    var offsetLeft = (containerWidth - $(this).width()) / 2;
                    $(this).children(".slides").css("margin-left", offsetLeft);
                } else {
                    $(this).children(".slides").css("margin-left", 0);
                }
            });
        }

        function generateUID() {
            var chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz"
                ,today = new Date()
                ,result = today.valueOf().toString(16);
            result += chars.substr(Math.floor(Math.random() * chars.length), 1);
            result += chars.substr(Math.floor(Math.random() * chars.length), 1);
            return "sg" + result;
        }
        if ($.fn.carouFredSel) {
            $(".soap-gallery.style2").each(function() {
                var thumbSrcArr = new Array();
                $(this).children(".sgImg").each(function() {
                    var thumbSrc = $(this).data("thumb");
                    if (typeof thumbSrc == "undefined") {
                        thumbSrc = $(this).prop("tagName").toLowerCase() == "img" ? $(this).attr("src") : $(this).find("img").attr("src");
                    }
                    thumbSrcArr.push(thumbSrc);
                });
                var thumbSectionId = generateUID();
                var thumbHtml = "<div id='" + thumbSectionId + "' class='soap-gallery-thumb-wrapper'>";
                for(var i in thumbSrcArr) {
                    thumbHtml += "<a href='#'>";
                    thumbHtml += "<img alt='' src='" + thumbSrcArr[i] + "'>";
                    thumbHtml += "</a>";
                }
                thumbHtml += "</div>";
                var navHtml = '<a href="#" class="soap-gallery-prev"><span></span></a><a href="#" class="soap-gallery-next"><span></span></a>';
                $("<div class='soap-image-wrapper'></div>").appendTo(this);
                $(this).children().not(".soap-image-wrapper").appendTo($(this).children(".soap-image-wrapper"));
                $(navHtml).insertAfter($(this).children(".soap-image-wrapper"));
                $(thumbHtml).insertAfter(this);
                if ($.fn.imagesLoaded) {
                    $(this).children(".soap-image-wrapper").imagesLoaded(function() {
                        $(this).carouFredSel({
                            responsive: true,
                            items: 1,
                            scroll: {
                                fx: "crossfade"
                            },
                            prev: ".soap-gallery-prev",
                            next: ".soap-gallery-next",
                            pagination: {
                                container: "#" + thumbSectionId,
                                anchorBuilder: false
                            }
                        });
                        var $this = $(this);
                        setTimeout(function() {
                            if ($this.closest(".iso-container").length > 0) {
                                $this.closest(".iso-container").isotope("layout");
                            }
                        }, 200);
                    });
                }
            });
        }

        // portfolio
        function initBlogPostTimeline($isoContainer) {
            $isoContainer.removeClass("layout-responsive-single");
            if ($isoContainer.length > 0 && $isoContainer.parent().hasClass("layout-timeline") && $isoContainer.parent().hasClass("layout-fullwidth")) {
                setTimeout(function() {
                    var containerLeft = $isoContainer.offset().left;
                    var rightCount = 0, leftCount = 0;
                    $isoContainer.find(".iso-item").each(function() {
                        var offsetLeft = $(this).offset().left - containerLeft;
                        $(this).removeClass("col-left");
                        $(this).removeClass("col-right");
                        if (offsetLeft < 1) {
                            $(this).addClass("col-left");
							leftCount++;
                        } else {
                            $(this).addClass("col-right");
                            rightCount++;
                        }
                    });
                    if (rightCount == 0 && (leftCount > 1 || Modernizr.mq('only all and (max-width: 767px)'))) {
                        $isoContainer.addClass("layout-responsive-single");
                        $isoContainer.find(".iso-item").removeClass("col-left");
                        $isoContainer.find(".iso-item").addClass("col-right");
                    }
                }, 0);
            }
        }
        if ($.fn.soapPortfolioFilter) {
            var $isoContainer = $(".iso-container").soapPortfolioFilter();
            initBlogPostTimeline($isoContainer);
            $isoContainer.isotope( 'on', 'layoutComplete', function() {
                initBlogPostTimeline($isoContainer);
            });
        }

        var $scrollHeight;
        function initPortfolioSingleInfo() {
            var $window = $(window);
            var $this = $(".single-m_portfolio .portfolio-follow");
            if ($this.length > 0) {
                var offset = $this.offset()
                    ,$scrollOffset = $this.closest(".post-wrapper").offset();
                $scrollHeight = $this.closest(".post-wrapper").height();
                $window.scroll(function() {
                    var extraHeight = $("#header.header-sticky").length > 0 ? $("#header.header-sticky").height() : 0;
                    if ($("#wpadminbar").length > 0) {
                        extraHeight += $("#wpadminbar").height();
                    }
                    if ($this.parent().css("float") == "left") {
                        if ($window.scrollTop() + 3 + extraHeight > offset.top) {
                            if ($window.scrollTop() + $this.height() + 20 + extraHeight < $scrollOffset.top + $scrollHeight) {
                                $this.stop().animate({
                                    marginTop: $window.scrollTop() - offset.top + 20 + extraHeight
                                });
                            } else {
                                $this.stop().animate({
                                    marginTop: $scrollHeight - $this.height() - 20
                                });
                            }
                        } else {
                            $this.stop().animate({
                                marginTop: 0
                            });
                        }
                    } else {
                        $this.css("margin-top", 0);
                    }
                });
            }
        }
        setTimeout(function() { initPortfolioSingleInfo(); }, 1000);

        // make child elements to have same height
        $(".same-height, .testimonials .owl-wrapper, .brand-slider .owl-wrapper").equalHeights();
        initPostFullContentHeight();
        $(document).on('ready yith-wcan-ajax-filtered', function() {
            initPostFullContentHeight();
        });

        // responsive section
        $(".responsive-section .responsive-button a").on("click", function(e) {
            e.preventDefault();
            $(this).parent().children("a").removeClass("active");
            $(this).addClass("active");
			var index = $(this).index();
            $(this).closest(".responsive-section").find(".callout-image img.active").removeClass("active");
			$(this).closest(".responsive-section").find(".callout-image img").eq(index).addClass("active");
        });

        // google map
        $(".soap-google-map").each(function() {
            var map_code = $(this).data("map-code");
            var map_zoom = parseInt($(this).data("zoom"), 10);
            var map_marker_icon = $(this).data("marker-icon");
            var marker_options = { draggable: false };
            if ( typeof map_marker_icon != "undefined" ) {
                marker_options.icon = map_marker_icon;
            }
            map_code = map_code.split(",");
            $(this).gmap3({
                map: {
                    options: {
                        center: map_code,
                        zoom: map_zoom,
						mapTypeControlOptions: {
							position: google.maps.ControlPosition.RIGHT_BOTTOM
						},
						zoomControlOptions: {
							position: google.maps.ControlPosition.LEFT_CENTER
						},
						panControlOptions: {
							position: google.maps.ControlPosition.LEFT_CENTER
						}
                    }
                },
                marker:{
                    values: [
                        {latLng: map_code, data:""}

                    ],
                    options: marker_options
                }
            });
        });

        // resize event
        var miracle_soap_gallery_carousel_style1_timer = null;
        $(window).resize(function() {
            $(".same-height").equalHeights();
            centerCarousel();
            initPostFullContentHeight();
            if (miracle_soap_gallery_carousel_style1_timer != null) {
                clearTimeout(miracle_soap_gallery_carousel_style1_timer);
            }
            miracle_soap_gallery_carousel_style1_timer = setTimeout(function() {
                initSoapGalleryCarouselStyle1();
            }, 200);
        });



        // ajax pagination
        var ajax_loaded = false;
        $("body").on("click", ".load-more, .post-pagination a", function(e) {
            var $this = $(this),
                loader = $.miracle_loading(),
                $container = null;
            if ($this.closest(".portfolio-container").length > 0) { //portfolio
                $container = $this.closest(".portfolio-container");
            } else {
                $container = $this.closest(".blog-posts").length > 0 ? $this.closest(".blog-posts") : $(".blog-posts");
            }
            if ($container == null || $container.length < 1 || typeof $container.data("pagination") == "undefined") {
                return true;
            }
            if (ajax_loaded) {
                return false;
            }
            e.preventDefault();

            ajax_loaded = true;
            var pagination_style = $container.data("pagination");
            function ajax_loaded_finished(new_items) {
                if ( new_items == null ) {
                    ajax_loaded = false;
                    loader.hide();
                } else {
                    new_items.each(function(i) {
                        var times = stGlobals.isMobile ? 0 : i * 100,
                            obj = $(this);
                        setTimeout(function() {
                            obj.css({visibility:"visible", opacity:0}).removeClass("miracle-ajax-post").animate({opacity:1}, 1500);
                            if (new_items.length - 1 == i) {
                                ajax_loaded = false;
                                loader.hide();
                            }
                        }, times);
                    });
                }
                $this.trigger("blur");
            }

            var params = $container.data();
            params.action = 'miracle_ajax_pagination';
            params.postID = miracleLocal.postID;
            params.nonce = miracleLocal.ajaxNonce;
            if ( pagination_style == 'ajax' ) {
                params.page_num = parseInt($this.data("page_num"), 10) - 1;
            }
            if ($this.closest(".portfolio-container").length > 0) { // portfolio
                params.post_type = 'portfolio';
                var paramObject = $.extend({}, params);
                if (typeof paramObject.isotope != "undefined") {
                    delete paramObject.isotope;
                }
                $.ajax({
                    url: miracleLocal.ajaxurl,
                    dataType: 'json',
                    type: 'post',
                    data: paramObject,
                    beforeSend: function() {
                        loader.show();
                    },
                    success: function(response) {
                        if (!response.success) {
                            ajax_loaded_finished(null);
                            return;
                        }
                        if (pagination_style == 'load_more') {
                            if (response.html == '') {
                                $this.remove();
                                ajax_loaded_finished(null);
                                return;
                            }
                            if (response.last_page) {
                                $this.remove();
                            } else {
                                $container.data("page_num", parseInt($container.data("page_num"), 10) + 1);
                            }
                        } else if (pagination_style == 'ajax' && response.pagination_html) {
                            $this.closest(".post-pagination").html($($.parseHTML(response.pagination_html)).children());
                        }
                        var new_items = $($.parseHTML(response.html));
                        new_items.each(function() {
                            $(this).addClass("miracle-ajax-post");
                        });
                        var items_container = $container.find(".iso-container");
                        if ( pagination_style == 'ajax' ) {
                            items_container.isotope("remove", items_container.find(".iso-item:not(.miracle-ajax-post)")).append( new_items ).isotope( 'appended', new_items );
                        } else {
                            items_container.isotope("insert", new_items);
                        }
                        miracleSetJavascriptActionForBlogPosts($container);
                        items_container.find(".miracle-ajax-post").imagesLoaded(function() {
                            setTimeout(function() { items_container.isotope("layout"); }, 150);
                            setTimeout(function() { ajax_loaded_finished(new_items); }, 800);
                        });
                    }
                });
            } else if ($container.hasClass("shortcode")) { // shortcode blog
                params.post_type = 'post';
                var paramObject = $.extend({}, params);
                if (typeof paramObject.isotope != "undefined") {
                    delete paramObject.isotope;
                }
                $.ajax({
                    url: miracleLocal.ajaxurl,
                    dataType: 'json',
                    type: 'post',
                    data: paramObject,
                    beforeSend: function() {
                        loader.show();
                    },
                    success: function(response) {
                        if (!response.success) {
                            ajax_loaded_finished(null);
                            return;
                        }
                        if (pagination_style == 'load_more') {
                            if (response.last_page) {
                                $this.remove();
                            } else {
                                $container.data("page_num", parseInt($container.data("page_num"), 10) + 1);
                            }
                        } else if (pagination_style == 'ajax' && response.pagination_html) {
                            $this.closest(".post-pagination").html($($.parseHTML(response.pagination_html)).children());
                        }
                        if (response.post_month) {
                            $container.data("post_month", response.post_month);
                        }
                        var new_items = $($.parseHTML(response.html));
                        new_items.each(function() {
                            $(this).addClass("miracle-ajax-post");
                        });
                        var items_container = $container.children(".iso-container").length > 0 ? $container.children(".iso-container") : $container;
                        if (items_container.hasClass("iso-container")) {
                            if (pagination_style == 'ajax') {
                                items_container.isotope("remove", items_container.find(".iso-item:not(.miracle-ajax-post)")).append( new_items ).isotope( 'appended', new_items );
                            } else {
                                items_container.isotope("insert", new_items);
                            }
                            miracleSetJavascriptActionForBlogPosts($container);
                            items_container.find(".miracle-ajax-post").imagesLoaded(function() {
                                setTimeout(function() { items_container.isotope("layout"); }, 150);
                                setTimeout(function() { ajax_loaded_finished(new_items); }, 800);
                            });
                        } else {
                            if (pagination_style == 'ajax') {
                                var tempCloneObj = '';
                                if (items_container.children(".load-more").length > 0) {
                                    tempCloneObj = items_container.children(".load-more").clone();
                                }
                                items_container.empty().append(new_items).append(tempCloneObj);
                            } else {
                                new_items.insertAfter(items_container.children(":not(.load-more)").last());
                            }
                            miracleSetJavascriptActionForBlogPosts($container);
                            items_container.find(".miracle-ajax-post").imagesLoaded(function() {
                                setTimeout(function() { ajax_loaded_finished(new_items); }, 150);
                            });
                        }
                    }
                });
            } else { // standard blog
                $.ajax({
                    url: $this.attr("href"),
                    type: 'GET',
                    beforeSend: function() {
                        loader.show();
                    },
                    success: function(response) {
                        var container = $($.parseHTML(response)).find(".blog-posts"),
                            btnLoad = $($.parseHTML(response)).find(".load-more"),
                            paginationObj = $($.parseHTML(response)).find(".post-pagination"),
                            layout = $container.data("layout");
                        if (container.length < 1) {
                            return;
                        }
                        var items_container = $container.children(".iso-container").length > 0 ? $container.children(".iso-container") : $container;
                        if (items_container.hasClass("iso-container")) {
                            var new_items = container.find(".iso-item").addClass("miracle-ajax-post");
                            if (pagination_style == 'ajax') {
                                items_container.isotope("remove", items_container.find(".iso-item:not(.miracle-ajax-post)")).append( new_items ).isotope( 'appended', new_items );
                            } else {
                                items_container.isotope("insert", new_items);
                            }
                            miracleSetJavascriptActionForBlogPosts($container);
                            items_container.find(".miracle-ajax-post").imagesLoaded(function() {
                                setTimeout(function() { items_container.isotope("layout"); }, 150);
                                setTimeout(function() { ajax_loaded_finished(new_items); }, 800);
                            });
                        } else {
                            var new_items = container.children(":not(.load-more)").addClass("miracle-ajax-post");
                            if (pagination_style == 'ajax') {
                                var tempCloneObj = '';
                                if (items_container.children(".load-more").length > 0) {
                                    tempCloneObj = items_container.children(".load-more").clone();
                                }
                                items_container.empty().append(new_items).append(tempCloneObj);
                            } else {
                                new_items.insertAfter(items_container.children(":not(.load-more)").last());
                            }
                            miracleSetJavascriptActionForBlogPosts($container);
                            items_container.find(".miracle-ajax-post").imagesLoaded(function() {
                                setTimeout(function() { ajax_loaded_finished(new_items); }, 150);
                            });
                        }
                        if (btnLoad.length > 0 && pagination_style == 'load_more') {
                            $this.attr("href", btnLoad.attr("href"));
                        } else if (pagination_style == 'load_more') {
                            $this.remove();
                        }
                        if (paginationObj.length > 0 && pagination_style == 'ajax') {
                            $this.closest(".post-pagination").html(paginationObj.children());
                        }
                    }
                })
            }
            return false;
        });
        function miracleSetJavascriptActionForBlogPosts(container) {
            if ($.fn.fitVids) {
                container.find('.miracle-ajax-post .video-container:not(.mejs-skin) .full-video').fitVids();
            }
            container.find(".miracle-ajax-post .audio-container audio").mediaelementplayer();
            container.find(".miracle-ajax-post .image.hover-style3").each( function() { $(this).hoverdir(); } );
            initMiracleOwlCarousel(container.find(".miracle-ajax-post"));
            if (container.find(".miracle-ajax-post .post-slider").length > 0) {
                container.find(".miracle-ajax-post .post-slider").each(function() {
                    $(this).magnificPopup({ 
                        delegate: 'a.soap-mfp-popup',
                        type: 'image',
                        gallery: { enabled:true }
                    });
                });
            }
        }
    });
})(sjq);


/* worpress version */
sjq(document).ready(function($) {
    // one page navigation
    var $body           = $('body');
    var $bodyHeight     = $body.outerHeight();
    var $adminbarHeight = $('#wpadminbar').outerHeight();
    var curHref         = window.location.href;
    var curHashIndex    = curHref.indexOf('#');
    var curHash         = curHref.substr(curHashIndex);
    var $headerHeight = $("#header").outerHeight();

    if ( (curHashIndex !== -1 && $(curHash).length) || ($("#header .miracle-scroll-nav").length > 0) ) {
        if (!$("body").hasClass("no-sticky-menu") && !$("#header").hasClass("header-sticky")) {
            var $header_cloned_obj = $("#header").clone();
            $header_cloned_obj.addClass("header-sticky").css("left", "-9999px").appendTo("body");
            $headerHeight = $header_cloned_obj.innerHeight();
            $header_cloned_obj.remove();
        }
    }
    $(window).load(function() {
        if ( curHashIndex !== -1 && $(curHash).length ) {
            $('html, body').animate({
                scrollTop: $(curHash).offset().top - $adminbarHeight - $headerHeight + 1
            }, 1, 'easeInOutExpo');
        }
    });

    if ($("#header .miracle-scroll-nav").length > 0) {
        $('.miracle-scroll-nav > li > a[href^="#"]').click(function(e) {
            e.preventDefault();
            var $contentPane = $(this).attr('href');
            if ($($contentPane).length > 0) {
                $('html, body').animate({
                    scrollTop: $($contentPane).offset().top - $adminbarHeight - $headerHeight + 1
                }, 880, 'easeInOutExpo');
            }
        });
        $body.scrollspy({
            target : '#nav',
            offset : $adminbarHeight + $headerHeight
        });

        $(window).resize(function() {
            $body.scrollspy('refresh');
        });

        var runCounter = 0;
        var interval = setInterval(function() {
            runCounter += 1;
            var newBodyHeight = $body.outerHeight();
            if (newBodyHeight !== $bodyHeight) {
                $body.scrollspy('refresh');
            }
            if (runCounter === 15) {
                clearInterval(interval);
            }
        }, 500);
    }

});