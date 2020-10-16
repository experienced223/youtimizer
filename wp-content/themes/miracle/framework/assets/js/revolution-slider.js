/*
 * Title:   Miracle | Responsive Multi-Purpose Wordpress Theme - Homepage Revolution Slider
 * Author:  http://themeforest.net/user/soaptheme
 */

"use strict";

sjq(document).ready(function($) {

    function initRevolutionSliderOptions() {
        var width = $(window).width();
        //$(".forcefullwidth_wrapper_tp_banner").css("max-height", (width * 0.975) + "px");
		$("#miracle-custom-revstyle").text(".forcefullwidth_wrapper_tp_banner{max-height:" + (width * 0.975) + "px;}");
    }
    $("#slideshow").css("height", "auto");

	$('<style id="miracle-custom-revstyle"></style>').appendTo("head");
	initRevolutionSliderOptions();

    $(window).resize(function() {
        initRevolutionSliderOptions();
    });
});