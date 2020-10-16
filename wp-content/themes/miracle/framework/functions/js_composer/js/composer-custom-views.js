/**
 * Visual Composer ViewModel for miracle
 */

(function ($) {
	var Shortcodes = vc.shortcodes;
	window.VcAnimationView = vc.shortcode_view.extend({
		
		initialize:function (options) {
			window.VcAnimationView.__super__.initialize.call(this, options);
		},
		ready:function (e) {
			window.VcAnimationView.__super__.ready.call(this, e);
			return this;
		},
		render:function () {
			window.VcAnimationView.__super__.render.call(this);
			//$('<div class="wpb_column_container vc_container_for_children vc_empty-container ui-droppable ui-sortable" style="margin-top: 15px;"></div>').appendTo(this.$el.find('.wpb_element_wrapper'));
			this.setEmpty();
			return this;
		},
		setContent:function () {
			this.$content = this.$el.find('.wpb_element_wrapper > .vc_container_for_children');
		},
		setEmpty:function () {
		},
		addToEmpty:function (e) {
			e.preventDefault();
			if ($(e.target).hasClass('vc_empty-container')) this.addElement(e);
		},
	});

})(window.jQuery);