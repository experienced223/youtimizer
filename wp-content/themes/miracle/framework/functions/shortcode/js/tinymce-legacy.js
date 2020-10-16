/**
 * v3.X TinyMCE specific functions.
 */

(function() {
	tinymce.create('tinymce.plugins.miracleShortcodeMce', {

		init : function(ed, url){
			tinymce.plugins.miracleShortcodeMce.theurl = url;
		},

		createControl : function(btn, e) {
			if ( btn == 'miracle_shortcode_button' ) {
				var a = this;
				var btn = e.createSplitButton('miracle_shortcode_button', {
					title: 'Insert Shortcode',
					image: tinymce.plugins.miracleShortcodeMce.theurl + '/miracle.png',
					icons: false,
				});

				btn.onRenderMenu.add(function (c, b) {


					//
					// Layouts.
					//

					c = b.addMenu({title:'Layouts'});

					a.render( c, 'Row', 'row' );
					a.render( c, 'One Half', 'one_half' );
					a.render( c, 'One Third', 'one_third' );
					a.render( c, 'One Fourth', 'one_fourth' );
					a.render( c, 'Two Third', 'two_third' );
					a.render( c, 'Three Fourth', 'three_fourth' );
					a.render( c, 'Column', 'column' );
					a.render( c, 'Block', 'block' );
					a.render( c, 'Container', 'container' );


					//
					// Typography
					//

					c = b.addMenu({title:'Typography'});

					a.render( c, 'Dropcap', 'dropcap' );
					a.render( c, 'Blockquote', 'blockquote' );
					a.render( c, 'Headline', 'headline' );
					a.render( c, 'Highlight', 'highlight' );
					a.render( c, 'Divider', 'divider' );
					a.render( c, 'Icon Box', 'icon_box' );
					a.render( c, 'Social Links', 'social_links' );
					a.render( c, 'Bullet List', 'bullet_list' );
					a.render( c, 'Table Style', 'table' );
					a.render( c, 'Table Cell Style', 'td' );


					//
					// Content
					//

					c = b.addMenu({title:'Content'});

					a.render( c, 'Accordion & Toggles', 'toggles' );
					a.render( c, 'Alert Box', 'alert' );
					a.render( c, 'Animation', 'animation' );
					a.render( c, 'Banner', 'banner' );
					a.render( c, 'Button', 'button' );
					a.render( c, 'Blog Posts', 'blog_posts' );
					a.render( c, 'Callout Box', 'callout_box' );
					a.render( c, 'Contact Adress', 'contact_addresses' );
					a.render( c, 'Counter', 'counter' );
					a.render( c, 'Image Box', 'image_box' );
					a.render( c, 'Infographic Pie', 'infographic_pie' );
					a.render( c, 'Isotope', 'isotope' );
					a.render( c, 'Note', 'note' );
					a.render( c, 'Post Carousel', 'post_carousel' );
					a.render( c, 'Post Slider', 'post_slider' );
					a.render( c, 'Pricing Table Container', 'pricing_table_container' );
					a.render( c, 'Pricing Table', 'pricing_table' );
					a.render( c, 'Process', 'process' );
					a.render( c, 'Progress Bars', 'progress_bars' );
					a.render( c, 'Section with Title', 'section_with_title' );
					a.render( c, 'Tabs', 'tabs' );
					a.render( c, 'Team Member', 'team_member' );
					a.render( c, 'Testimonials', 'testimonials' );
					a.render( c, 'Testimonial', 'testimonial' );


					//
					// Media
					//

					c = b.addMenu({title:'Media'});

					a.render( c, 'Carousel', 'carousel' );
					a.render( c, 'Image', 'image' );
					a.render( c, 'Image Advertisement', 'image_ads' );
					a.render( c, 'Image Banner', 'image_banner' );
					a.render( c, 'Image Gallery', 'image_gallery' );
					a.render( c, 'Image Parallax', 'image_parallax' );
					a.render( c, 'Logo Slider', 'logo_slider' );
					a.render( c, 'Video Parallax', 'video_parallax' );

					
					//
					// Plugin Additions
					//

					c = b.addMenu({title:'Plugin Additions'});

					a.render( c, 'Masonry Products', 'masonry_products' );

				});
				return btn;
			}
			return null;
		},

		render : function(ed, title, id) {
			ed.add({
				title: title,
				onclick: function () {


					//
					// Layouts
					//

					if(id === 'row') {
						tinyMCE.activeEditor.selection.setContent('[row (add_clearfix="yes|no") (children_same_height="yes|no")]...[/row]');
					}
					if(id === 'one_half') {
						tinyMCE.activeEditor.selection.setContent('[one_half (offset="{0-6}") ]...[/one_half]');
					}
					if(id === 'one_third') {
						tinyMCE.activeEditor.selection.setContent('[one_third (offset="{0-8}") ]...[/one_third]');
					}
					if(id === 'one_fourth') {
						tinyMCE.activeEditor.selection.setContent('[one_fourth (offset="{0-9}") ]...[/one_fourth]');
					}
					if(id === 'two_third') {
						tinyMCE.activeEditor.selection.setContent('[two_third (offset="{0-4}") ]...[/two_third]');
					}
					if(id === 'three_fourth') {
						tinyMCE.activeEditor.selection.setContent('[three_fourth (offset="{0-3}") ]...[/three_forth]');
					}
					if(id === 'column') {
						tinyMCE.activeEditor.selection.setContent('[column (lg = "{1-12}") (md = "{1-12}") (sm = "{1-12}") (sms = "{1-12}") (xs = "{1-12}") (lgoff = "{0-12}") (mdoff = "{0-12}") (smoff = "{0-12}") (xsoff = "{0-12}") (lghide = "yes|no") (mdhide = "yes|no") (smhide = "yes|no") (smshide = "yes|no") (xshide = "yes|no") (lgclear = "yes|no") (mdclear = "yes|no") (smclear = "yes|no") (smsclear = "yes|no") (xsclear = "yes|no") ]...[/column]');
					}
					if(id === 'block') {
						tinyMCE.activeEditor.selection.setContent('[block (id="") (type="small|medium|large|x-large") ]...[/block]');
					}
					if(id === 'container') {
						tinyMCE.activeEditor.selection.setContent('[container]...[/container]');
					}


					//
					// Typography
					//
					if(id === 'dropcap') {
						tinyMCE.activeEditor.selection.setContent('[dropcap (style="style1|style2")]...[/dropcap]');
					}
					if(id === 'blockquote') {
						tinyMCE.activeEditor.selection.setContent('[blockquote (style="style1|style2|style3") (author="")]...[/blockquote]');
					}
					if(id === 'headline') {
						tinyMCE.activeEditor.selection.setContent('[headline level="h2" title="" title_class="" sub_title="" lg="" md="" sm=""]...[/headline]');
					}
					if(id === 'highlight') {
						tinyMCE.activeEditor.selection.setContent('[highlight]...[/highlight]');
					}
					if(id === 'divider') {
						tinyMCE.activeEditor.selection.setContent('[divider (style="solid|dotted|thick") (color="skin|heading|text|light1|light2")]');
					}
					if(id === 'icon_box') {
						tinyMCE.activeEditor.selection.setContent('[icon_box title="" icon_class="" style="centered1...centered6|side1...side7|boxed1...boxed4" (icon_color="default|blue")]...[/icon_box]');
					}
					if(id === 'social_links') {
						tinyMCE.activeEditor.selection.setContent('[social_links (style="style1|style2|style3") (size="normal|large|medium|small")][social_link icon_class="" link=""][/social_links]');
					}
					if(id === 'bullet_list') {
						tinyMCE.activeEditor.selection.setContent('[bullet_list style="arrow|arrow-circle|star|decimal-zero|disc" (size="small|medium") (has_hover_effect="yes|no")]...[/bullet_list]');
					}
					if(id === 'table') {
						tinyMCE.activeEditor.selection.setContent('[table]...[/table]');
					}
					if(id === 'td') {
						tinyMCE.activeEditor.selection.setContent('[td vertical_align="middle|top|bottom"]...[/td]');
					}


					//
					// Content
					//
					if(id === 'toggles') {
						tinyMCE.activeEditor.selection.setContent('[toggles (toggle_type="toggle|accordion") (style="style1...style6|faqs")][toggle title="" (active="yes|no")][toggle title="" (active="yes|no")][/toggles]');
					}
					if(id === 'alert') {
						tinyMCE.activeEditor.selection.setContent('[alert type="general|notice|success|error|help"]...[/alert]');
					}
					if(id === 'animation') {
						tinyMCE.activeEditor.selection.setContent('[animation type="" duration="1" delay="0"]...[/animation]');
					}
					if(id === 'banner') {
						tinyMCE.activeEditor.selection.setContent('[banner post_type="post|portfolio" columns="{1-4}" (ids="") (category="") (count="3") (orderby="date,ID,author,title,modified...") (order="DESC|ASC") (style="animated|standard")]');
					}
					if(id === 'button') {
						tinyMCE.activeEditor.selection.setContent('[button href="#" title="" style="style1...style4" (target="_blank") (size="sm|md|lg|xl")]...[/button]');
					}
					if(id === 'blog_posts') {
						tinyMCE.activeEditor.selection.setContent('[blog_posts (post_type="blog|portfolio") (ids="") (category="") (count="") (orderby="date,ID,author,title,modified...") (order="DESC|ASC") (pagination="yes|no") (author_id="") (style="masonry|grid|full|classic|timeline") (columns="{1-4}") (load_style="default|ajax|load_more")]');
					}
					if(id === 'callout_box') {
						tinyMCE.activeEditor.selection.setContent('[callout_box style="style1...style5" title="" button1_text="" button1_href="" (message="") (button1_target="_blank") (button2_text="") (button2_href="") (button2_target="_blank") (img_position="left|right") (img_src="") (img_width="") (img_height="") (img_alt="") (bgcolor="") (img_animation_type="") (img_animation_delay="") (content_animation_type="") (content_animation_delay="") (img2_src="") (img2_width="") (img2_height="") (img2_alt="")]...[/callout_box]');
					}
					if(id === 'contact_addresses') {
						tinyMCE.activeEditor.selection.setContent('[contact_addresses style="style1|style2"][contact_address icon_class="" title=""]...[/contact_address][contact_address icon_class="" title=""]...[/contact_address][/contact_addresses]');
					}
					if(id === 'counter') {
						tinyMCE.activeEditor.selection.setContent('[counter style="style1|style2" label="" number="" (img_src="") (img_alt="") (img_width="") (img_height)]');
					}
					if(id === 'image_box') {
						tinyMCE.activeEditor.selection.setContent('[image_box title="" img_src="" (img_width="") (img_height="") (img_alt="")]...[/image_box]');
					}
					if(id === 'infographic_pie') {
						tinyMCE.activeEditor.selection.setContent('[infographic_pie title="" desc="" bgcolor="#edf6ff" fgcolor="#1b4268" percent="{0-100}" percent_text="90%" dimension="" bordersize="" fontsize="" (fontcolor="default|blue") (borderstyle="default|outline") (fill_borderwidth="") (startdegree="") (style="style1|style2|style3")]');
					}
					if(id === 'isotope') {
						tinyMCE.activeEditor.selection.setContent('[isotope columns="{1-6}" (has_column_width="yes|no")][iso_item (has_double_width="no|yes")]...[/iso_item]...[/isotope]');
					}
					if(id === 'note') {
						tinyMCE.activeEditor.selection.setContent('[note style="style1...style4"]...[/note]');
					}
					if(id === 'post_carousel') {
						tinyMCE.activeEditor.selection.setContent('[post_carousel (post_type="post|portfolio") (ids="") (category="") (count="") (orderby="date,ID,author,title,modified...") (order="DESC|ASC") (style="style1|style2") (columns="{3-5}") title="" autoplay="5000"]');
					}
					if(id === 'post_slider') {
						tinyMCE.activeEditor.selection.setContent('[post_slider (ids="") (category="") (count="") (orderby="date,ID,author,title,modified...") (order="DESC|ASC") (style="style1...style6") autoplay="5000"]');
					}
					if(id === 'pricing_table_container') {
						tinyMCE.activeEditor.selection.setContent('[pricing_table_container columns="4"]...[/pricing_table_container]');
					}
					if(id === 'pricing_table') {
						tinyMCE.activeEditor.selection.setContent('[pricing_table style="style1|style2" (active="true|false") currency_symbol="$" price="" unit_text="per month" pricing_type="" desc="" btn_title="" btn_url="" btn_target=""]...[/pricing_table]');
					}
					if(id === 'process') {
						tinyMCE.activeEditor.selection.setContent('[process style="simple|creative" (has_animate="yes|no") (animate_type="fadeInLeft")][process_item title="" desc="" icon_class="" img_src="" img_alt="" (img_width="") (img_height="") (is_active="yes|no") (is_asset="yes|no")][process_item title="" desc="" icon_class="" img_src="" img_alt="" (img_width="") (img_height="") (is_asset="yes|no")][/process]');
					}
					if(id === 'progress_bars') {
						tinyMCE.activeEditor.selection.setContent('[progress_bars style="default|skill-meter|icons|colored|vertical"][progress_bar label="" percent="{0-100}" icon_class="" total_numbers="" active_numbers="" (color_style="default|blue") (bar_color_code="#0ab596") (has_animate="yes|no")][progress_bar label="" percent="{0-100}" icon_class="" total_numbers="" active_numbers="" (color_style="default|blue") (bar_color_code="#0ab596") (has_animate="yes|no")][/progress_bars]');
					}
					if(id === 'section_with_title') {
						tinyMCE.activeEditor.selection.setContent('[section_with_title title=""]...[/section_with_title]');
					}
					if(id === 'tabs') {
						tinyMCE.activeEditor.selection.setContent('[tabs (style="style1|style2|vertical-tab|vertical-tab-1|transparent-tab") active_tab_index="1" (has_full_width="yes|no") (img_src="") (img_width="") (img_height="") (img_alt="")][tab title="" (icon_class="")]...[/tab][tab title="" (icon_class="")]...[/tab][/tabs]');
					}
					if(id === 'team_member') {
						tinyMCE.activeEditor.selection.setContent('[team_member style="default|colored" name="" job="" desc="" photo_url="" photo_alt="" (photo_width="") (photo_height="")][social_links style="style1" size="small"][social_link icon_class="" link=""][/social_links][/team_member]');
					}
					if(id === 'testimonials') {
						tinyMCE.activeEditor.selection.setContent('[testimonials title="" style="style1...style4" (author_img_size="90") (font_size="normal|large") (columns="3")][testimonial author_name="" author_job="" author_link="" author_img_url=""]...[/testimonial][testimonial author_name="" author_job="" author_link="" author_img_url=""]...[/testimonial][/testimonials]');
					}
					if(id === 'testimonial') {
						tinyMCE.activeEditor.selection.setContent('[testimonial author_name="" author_job="" author_link="" author_img_url="" (author_img_size="90") (font_size="normal|large")]...[/testimonial]');
					}


					//
					// Media
					//
					if(id === 'carousel') {
						tinyMCE.activeEditor.selection.setContent('[carousel columns="{1-6}" autoplay="5000" (show_on_header="yes|no") (insert_container="yes|no")][slide][/slide]...[/carousel]');
					}
					if(id === 'image') {
						tinyMCE.activeEditor.selection.setContent('[image src="" alt="" (is_fullwidth="yes|no")]');
					}
					if(id === 'image_ads') {
						tinyMCE.activeEditor.selection.setContent('[image_ads link_url="#" caption_text="" (caption_style="default|style1|style2") (hover_style="style2|style1|style3")][image src="" alt=""][/image_ads]');
					}
					if(id === 'image_banner') {
						tinyMCE.activeEditor.selection.setContent('[image_banner (bg_img="") (bg_color="")][banner_caption (position="left|right|middle|full")]...[/banner_caption]...[/image_banner]');
					}
					if(id === 'image_gallery') {
						miracle_media_uploader_popup(tinyMCE.activeEditor.selection, '[image_gallery ids="{ids}" mode="{mode}" is_thumb_full="{is_thumb_full}" columns="{columns}"]');
					}
					if(id === 'image_parallax') {
						tinyMCE.activeEditor.selection.setContent('[image_parallax src="image_url" ratio="{0-1}" (height="")]...[/image_parallax]');
					}
					if(id === 'logo_slider') {
						tinyMCE.activeEditor.selection.setContent('[logo_slider columns="{1-6}" (style="style1|style2)" autoplay="5000"][logo src="" alt="" (url="")][logo src="" alt="" (url="")]...[/logo_slider]');
					}
					if(id === 'video_parallax') {
						tinyMCE.activeEditor.selection.setContent('[video_parallax src="video_url(youtube, vimeo, mp4 file url)" ratio="{0-1}" video_ratio="16:9|4:3|5:4|5:3" poster=""][video_caption]...[/video_caption]...[/video_parallax]');
					}


					//
					// Plugin Additions
					//
					if(id === 'masonry_products') {
						tinyMCE.activeEditor.selection.setContent('[masonry_products columns="{1-6}" count="8" pagination="yes|no"]');
					}


					return false;

				}
			});
		}
	
	});

	tinymce.PluginManager.add('miracle_shortcode', tinymce.plugins.miracleShortcodeMce);

	var miracle_file_frame = new Array();
	function miracle_media_uploader_popup(editor, template) {

		var frame_key = _.random(0, 999999999999999999);

		if ( miracle_file_frame[frame_key] ) {
			miracle_file_frame[frame_key].open();
			return;
		}
		
		wp.media.is_shortcode = true;

		miracle_file_frame[frame_key] = wp.media.frames.file_frame = wp.media({
			frame:	 'post',
			state:	 'gallery-library',
			library: { type: 'image' },
			button:	{ text: 'Add Images' },
			multiple: true
		});
		
		miracle_file_frame[frame_key].on( 'select update insert', function() {
			attachment = miracle_file_frame[frame_key].state().get('library').toJSON();
			var ids = "";
			for (var i = 0; i < attachment.length; i++) {
				if (i > 0) {
					ids += ",";
				}
				ids += attachment[i].id;
			}
			template = template.replace("{ids}", ids).replace("{mode}", jQuery(miracle_file_frame[frame_key].el).find("select[data-setting='mode']").val());
			var mode = jQuery(miracle_file_frame[frame_key].el).find("select[data-setting='mode']").val();
			if (mode == "gallery2") {
				var is_thumb_full = jQuery(miracle_file_frame[frame_key].el).find("input[data-setting='thumb-full']").is(":checked") ? "yes" : "no";
				template = template.replace(' columns="{columns}"', '').replace('{is_thumb_full}', is_thumb_full);
			} else if (mode == "metro1" || mode == "metro2") {
				var columns = jQuery(miracle_file_frame[frame_key].el).find("select[data-setting='columns']").val();
				template = template.replace(' is_thumb_full="{is_thumb_full}"', '').replace('{columns}', columns);
			} else if (mode == "carousel") {
				var frontWidth = jQuery(miracle_file_frame[frame_key].el).find(".miracle-image-carousel-settings").find("[data-setting='front-width']").val(),
						frontHeight = jQuery(miracle_file_frame[frame_key].el).find(".miracle-image-carousel-settings").find("[data-setting='front-height']").val(),
						slideCount = jQuery(miracle_file_frame[frame_key].el).find(".miracle-image-carousel-settings").find("[data-setting='slide-count']").val(),
						hAlign = jQuery(miracle_file_frame[frame_key].el).find(".miracle-image-carousel-settings").find("[data-setting='halign']").val(),
						vAlign = jQuery(miracle_file_frame[frame_key].el).find(".miracle-image-carousel-settings").find("[data-setting='valign']").val();
				template = '[image_gallery ids="' + ids + '"';
				template += ' mode="carousel"';
				template += ' front_width="' + frontWidth + '"';
				template += ' front_height="' + frontHeight + '"';
				template += ' slide_count="' + slideCount + '"';
				template += ' halign="' + hAlign + '"';
				template += ' valign="' + vAlign + '"';
				template += ']';
			} else {
				template = template.replace(' is_thumb_full="{is_thumb_full}" columns="{columns}"', '');
			}
			editor.setContent(template);
		});
		// Finally, open the modal
		miracle_file_frame[frame_key].open();
		jQuery(miracle_file_frame[frame_key].el).on("change", "select[data-setting='mode']", function() {
			if (jQuery(this).val() == "metro1" || jQuery(this).val() == "metro2" || jQuery(this).val() == "slider") {
				jQuery(miracle_file_frame[frame_key].el).find("select[data-setting='columns']").parent().show();
			} else {
				jQuery(miracle_file_frame[frame_key].el).find("select[data-setting='columns']").parent().hide();
			}
			if (jQuery(this).val() == "gallery2") {
				jQuery(miracle_file_frame[frame_key].el).find("input[data-setting='thumb-full']").parent().show();
			} else {
				jQuery(miracle_file_frame[frame_key].el).find("input[data-setting='thumb-full']").parent().hide();
			}
			if (jQuery(this).val() == "carousel") {
				jQuery(miracle_file_frame[frame_key].el).find(".miracle-image-carousel-settings").show();
			} else {
				jQuery(miracle_file_frame[frame_key].el).find(".miracle-image-carousel-settings").hide();
			}
		});
	}

})();