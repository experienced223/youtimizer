/*
 * Title:   Miracle | Responsive Multi-Purpose Wordpress Theme - Woocommerce Mini Cart JS
 * Author:  http://themeforest.net/user/soaptheme
 */

sjq(function($) {
	$('body').bind('added_to_cart', miracle_mini_cart_widget);

	$(".widget_product_categories .product-categories li.cat-parent").each(function() {
		$(this).click(function(e) {
			if ($(e.target).is($(this))) {
				e.preventDefault();
				var obj = this;
				$(this).children(".children").toggle(400, function() {
					$(obj).toggleClass("current-cat-parent");
				});
			}
		});
	});
});

function miracle_mini_cart_widget(event, parts, hash) {
	var miniCart = sjq('.mini-cart');

	if ( parts['div.widget_shopping_cart_content'] ) {

		var $cartContent = jQuery(parts['div.widget_shopping_cart_content']),
			$itemsList = $cartContent .find('.cart_list'),
			$total = $cartContent .find('.total'),
			$buttons = miniCart.find('.buttons').clone();

		miniCart.find('.cart-content').html('');
		miniCart.find('.cart-content').append($itemsList, $total, $buttons);
	}
}