/*
 * Title:   Miracle | Responsive Multi-Purpose Wordpress Theme
 * Description: This file contains main javascript code to implement miracle mega menu
 * Author:  http://themeforest.net/user/soaptheme
 */

(function($) {
	var miracle_mega_menu = {

		recalcTimeout: false,

		binds: function() {
			var megmenuActivator = '.menu-item-miracle-megamenu, #menu-to-edit';

			jQuery('#menu-to-edit').on('click', '.menu-item-miracle-enable-mega-menu', function(e) {
				var checkbox = $(this),
					container = checkbox.parents('.menu-item:eq(0)');

				if(checkbox.is(':checked')) {
					container.addClass('field-miracle-mega-menu-enabled');
				} else {
					container.removeClass('field-miracle-mega-menu-enabled');
				}

				miracle_mega_menu.recalc();

			});
		},

		recalcInit: function() {
			$(document).on('mouseup', '.menu-item-bar', function(e, ui) {
				if(!$(e.target).is('a')) {
					clearTimeout(miracle_mega_menu.recalcTimeout);
					miracle_mega_menu.recalcTimeout = setTimeout(miracle_mega_menu.recalc, 700);
				}
			});
		},


		recalc : function() {
			var menuItems = $('.menu-item','#menu-to-edit');

			menuItems.each(function(i) {
				var item = $(this),
					checkbox = $('.menu-item-miracle-enable-mega-menu', this);

				if(!item.is('.menu-item-depth-0')) {
					var checkItem = menuItems.filter(':eq('+(i-1)+')');
					if(checkItem.is('.field-miracle-mega-menu-enabled')) {
						item.addClass('field-miracle-mega-menu-enabled');
						checkbox.attr('checked', 'checked');
					} else {
						item.removeClass('field-miracle-mega-menu-enabled');
						checkbox.attr('checked', '');
					}
				}

			});

		}

	};


	$(function() {
		miracle_mega_menu.binds();
		miracle_mega_menu.recalcInit();
		miracle_mega_menu.recalc();
	});

})(jQuery);