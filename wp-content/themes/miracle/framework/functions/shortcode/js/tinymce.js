/**
* v4.X TinyMCE specific functions. (from wordpress 3.9)
*/

(function() {

  tinymce.PluginManager.add('miracle_shortcode', function(editor, url) {

    editor.addButton('miracle_shortcode_button', {

      type  : 'menubutton',
      title  : 'Miracle Shortcode',
      /*image : url + '/miracle.png',*/
      style : 'background-image: url("' + url + '/miracle.png' + '"); background-repeat: no-repeat; background-position: 3px 1px;"',
      icon  : true,
      menu  : [
        { text: 'Layouts',
          menu : [
             { text : 'Row', onclick: function() {editor.insertContent('[row (add_clearfix="yes|no") (children_same_height="yes|no")]...[/row]');} },
             { text : 'One Half', onclick: function() {editor.insertContent('[one_half (offset="{0-6}") ]...[/one_half]');} },
             { text : 'One Third', onclick: function() {editor.insertContent('[one_third (offset="{0-8}") ]...[/one_third]');} },
             { text : 'One Fourth', onclick: function() {editor.insertContent('[one_fourth (offset="{0-9}") ]...[/one_fourth]');} },
             { text : 'Two Third', onclick: function() {editor.insertContent('[two_third (offset="{0-4}") ]...[/two_third]');} },
             { text : 'Three Fourth', onclick: function() {editor.insertContent('[three_fourth (offset="{0-3}") ]...[/three_forth]');} },
             { text : 'Column', onclick: function() {editor.insertContent('[column (lg = "{1-12}") (md = "{1-12}") (sm = "{1-12}") (sms = "{1-12}") (xs = "{1-12}") (lgoff = "{0-12}") (mdoff = "{0-12}") (smoff = "{0-12}") (xsoff = "{0-12}") (lghide = "yes|no") (mdhide = "yes|no") (smhide = "yes|no") (smshide = "yes|no") (xshide = "yes|no") (lgclear = "yes|no") (mdclear = "yes|no") (smclear = "yes|no") (smsclear = "yes|no") (xsclear = "yes|no") ]...[/column]');} },
             { text : 'Block', onclick: function() {editor.insertContent('[block (id="") (type="small|medium|large|x-large") ]...[/block]');} },
             { text : 'Container', onclick: function() {editor.insertContent('[container]...[/container]');} },
          ]
        },
        { text: 'Typography',
          menu : [
             { text : 'Dropcap', onclick: function() {editor.insertContent('[dropcap (style="style1|style2")]...[/dropcap]');} },
             { text : 'Blockquote', onclick: function() {editor.insertContent('[blockquote (style="style1|style2|style3") (author="")]...[/blockquote]');} },
             { text : 'Headline', onclick: function() {editor.insertContent('[headline level="h2" title="" title_class="" sub_title="" lg="" md="" sm=""]...[/headline]');} },
             { text : 'Highlight', onclick: function() {editor.insertContent('[highlight]...[/highlight]');} },
             { text : 'Divider', onclick: function() {editor.insertContent('[divider (style="solid|dotted|thick") (color="skin|heading|text|light1|light2")]');} },
             { text : 'Icon Box', onclick: function() {editor.insertContent('[icon_box title="" icon_class="" style="centered1...centered6|side1...side7|boxed1...boxed4" (icon_color="default|blue")]...[/icon_box]');} },
             { text : 'Social Links', onclick: function() {editor.insertContent('[social_links (style="style1|style2|style3") (size="normal|large|medium|small")][social_link icon_class="" link=""][/social_links]');} },
             { text : 'Bullet List', onclick: function() {editor.insertContent('[bullet_list style="arrow|arrow-circle|star|decimal-zero|disc" (size="small|medium") (has_hover_effect="yes|no")]...[/bullet_list]');} },
             { text : 'Table Style', onclick: function() {editor.insertContent('[table]...[/table]');} },
             { text : 'Table Cell Style', onclick: function() {editor.insertContent('[td vertical_align="middle|top|bottom"]...[/td]');} },

          ]
        },
        { text: 'Content',
          menu : [
             { text : 'Accordion & Toggles', onclick: function() {editor.insertContent('[toggles (toggle_type="toggle|accordion") (style="style1...style6|faqs")][toggle title="" (active="yes|no")][toggle title="" (active="yes|no")][/toggles]');} },
             { text : 'Alert Box', onclick: function() {editor.insertContent('[alert type="general|notice|success|error|help"]...[/alert]');} },
             { text : 'Animation', onclick: function() {editor.insertContent('[animation type="" duration="1" delay="0"]...[/animation]');} },
             { text : 'Banner', onclick: function() {editor.insertContent('[banner post_type="post|portfolio" columns="{1-4}" (ids="") (category="") (count="3") (orderby="date,ID,author,title,modified...") (order="DESC|ASC") (style="animated|standard")]');} },
             { text : 'Button', onclick: function() {editor.insertContent('[button href="#" title="" style="style1...style4" (target="_blank") (size="sm|md|lg|xl")]...[/button]');} },
             { text : 'Blog Posts', onclick: function() {editor.insertContent('[blog_posts (post_type="post|portfolio") (ids="") (category="") (count="") (orderby="date,ID,author,title,modified...") (order="DESC|ASC") (pagination="yes|no") (author_id="") (style="masonry|grid|full|classic|timeline") (columns="{1-4}") (load_style="default|ajax|load_more")]');} },
             { text : 'Callout Box', onclick: function() {editor.insertContent('[callout_box style="style1...style5" title="" button1_text="" button1_href="" (message="") (button1_target="_blank") (button2_text="") (button2_href="") (button2_target="_blank") (img_position="left|right") (img_src="") (img_width="") (img_height="") (img_alt="") (bgcolor="") (img_animation_type="") (img_animation_delay="") (content_animation_type="") (content_animation_delay="") (img2_src="") (img2_width="") (img2_height="") (img2_alt="")]...[/callout_box]');} },
             { text : 'Contact Adress', onclick: function() {editor.insertContent('[contact_addresses style="style1|style2"][contact_address icon_class="" title=""]...[/contact_address][contact_address icon_class="" title=""]...[/contact_address][/contact_addresses]');} },
             { text : 'Counter', onclick: function() {editor.insertContent('[counter style="style1|style2" label="" number="" (img_src="") (img_alt="") (img_width="") (img_height)]');} },
             { text : 'Image Box', onclick: function() {editor.insertContent('[image_box title="" img_src="" (img_width="") (img_height="") (img_alt="")]...[/image_box]');} },
             { text : 'Infographic Pie', onclick: function() {editor.insertContent('[infographic_pie title="" desc="" bgcolor="#edf6ff" fgcolor="#1b4268" percent="{0-100}" percent_text="90%" dimension="" bordersize="" fontsize="" (fontcolor="default|blue") (borderstyle="default|outline") (fill_borderwidth="") (startdegree="") (style="style1|style2|style3")]');} },
             { text : 'Isotope', onclick: function() {editor.insertContent('[isotope columns="{1-6}" (has_column_width="yes|no")][iso_item (has_double_width="no|yes")]...[/iso_item]...[/isotope]');} },

             { text : 'Note', onclick: function() {editor.insertContent('[note style="style1...style4"]...[/note]');} },
             { text : 'Post Carousel', onclick: function() {editor.insertContent('[post_carousel (post_type="post|portfolio") (ids="") (category="") (count="") (orderby="date,ID,author,title,modified...") (order="DESC|ASC") (style="style1|style2") (columns="{3-5}") title="" autoplay="5000"]');} },
             { text : 'Post Slider', onclick: function() {editor.insertContent('[post_slider (ids="") (category="") (count="") (orderby="date,ID,author,title,modified...") (order="DESC|ASC") (style="style1...style6") autoplay="5000"]');} },
             { text : 'Pricing Table Container', onclick: function() {editor.insertContent('[pricing_table_container columns="4"]...[/pricing_table_container]');} },
             { text : 'Pricing Table', onclick: function() {editor.insertContent('[pricing_table style="style1|style2" (active="true|false") currency_symbol="$" price="" unit_text="per month" pricing_type="" desc="" btn_title="" btn_url="" btn_target=""]...[/pricing_table]');} },
             { text : 'Process', onclick: function() {editor.insertContent('[process style="simple|creative" (has_animate="yes|no") (animate_type="fadeInLeft")][process_item title="" desc="" icon_class="" img_src="" img_alt="" (img_width="") (img_height="") (is_active="yes|no") (is_asset="yes|no")][process_item title="" desc="" icon_class="" img_src="" img_alt="" (img_width="") (img_height="") (is_asset="yes|no")][/process]');} },
             { text : 'Progress Bars', onclick: function() {editor.insertContent('[progress_bars style="default|skill-meter|icons|colored|vertical"][progress_bar label="" percent="{0-100}" icon_class="" total_numbers="" active_numbers="" (color_style="default|blue") (bar_color_code="#0ab596") (has_animate="yes|no")][progress_bar label="" percent="{0-100}" icon_class="" total_numbers="" active_numbers="" (color_style="default|blue") (bar_color_code="#0ab596") (has_animate="yes|no")][/progress_bars]');} },

             { text : 'Section with Title', onclick: function() {editor.insertContent('[section_with_title title=""]...[/section_with_title]');} },

             { text : 'Tabs', onclick: function() {editor.insertContent('[tabs (style="style1|style2|vertical-tab|vertical-tab-1|transparent-tab") active_tab_index="1" (has_full_width="yes|no") (img_src="") (img_width="") (img_height="") (img_alt="")][tab title="" (icon_class="")]...[/tab][tab title="" (icon_class="")]...[/tab][/tabs]');} },
             { text : 'Team Member', onclick: function() {editor.insertContent('[team_member style="default|colored" name="" job="" desc="" photo_url="" photo_alt="" (photo_width="") (photo_height="")][social_links style="style1" size="small"][social_link icon_class="" link=""][/social_links][/team_member]');} },
             { text : 'Testimonials', onclick: function() {editor.insertContent('[testimonials title="" style="style1...style4" (author_img_size="90") (font_size="normal|large") (columns="3")][testimonial author_name="" author_job="" author_link="" author_img_url=""]...[/testimonial][testimonial author_name="" author_job="" author_link="" author_img_url=""]...[/testimonial][/testimonials]');} },
             { text : 'Testimonial', onclick: function() {editor.insertContent('[testimonial author_name="" author_job="" author_link="" author_img_url="" (author_img_size="90") (font_size="normal|large")]...[/testimonial]');} },

          ]
        },
        { text: 'Media',
          menu : [
             { text : 'Carousel', onclick: function() {editor.insertContent('[carousel columns="{1-6}" autoplay="5000" (show_on_header="yes|no") (insert_container="yes|no")][slide][/slide]...[/carousel]');} },
             { text : 'Image', onclick: function() {editor.insertContent('[image src="" alt="" (is_fullwidth="yes|no")]');} },
             { text : 'Image Advertisement', onclick: function() {editor.insertContent('[image_ads link_url="#" caption_text="" (caption_style="default|style1|style2") (hover_style="style2|style1|style3")][image src="" alt=""][/image_ads]');} },
             { text : 'Image Banner', onclick: function() {editor.insertContent('[image_banner (bg_img="") (bg_color="")][banner_caption (position="left|right|middle|full")]...[/banner_caption]...[/image_banner]');} },
             { text : 'Image Gallery', classes : 'miracle-image-uploader', onclick: function() {
                miracle_media_uploader_popup(editor, '[image_gallery ids="{ids}" mode="{mode}" is_thumb_full="{is_thumb_full}" columns="{columns}"]');
              }
             },
             { text : 'Image Parallax', onclick: function() {editor.insertContent('[image_parallax src="image_url" ratio="{0-1}" (height="")]...[/image_parallax]');} },
             { text : 'Logo Slider', onclick: function() {editor.insertContent('[logo_slider columns="{1-6}" (style="style1|style2)" autoplay="5000"][logo src="" alt="" (url="")][logo src="" alt="" (url="")]...[/logo_slider]');} },
             { text : 'Video Parallax', onclick: function() {editor.insertContent('[video_parallax src="video_url(youtube, vimeo, mp4 file url)" ratio="{0-1}" video_ratio="16:9|4:3|5:4|5:3" poster=""][video_caption]...[/video_caption]...[/video_parallax]');} },
          ]
        },
        { text: 'Plugin Additions',
          menu : [
             { text : 'Masonry Products', onclick: function() {editor.insertContent('[masonry_products columns="{1-6}" count="8" pagination="yes|no"]');} },
          ]
        },
      ]

    });

  });
  
  var miracle_file_frame = new Array();
  function miracle_media_uploader_popup(editor, template) {

      var frame_key = _.random(0, 999999999999999999);

      if ( miracle_file_frame[frame_key] ) {
          miracle_file_frame[frame_key].open();
          return;
      }
      
      wp.media.is_shortcode = true;

      miracle_file_frame[frame_key] = wp.media.frames.file_frame = wp.media({
          frame:   'post',
          state:   'gallery-library',
          library: { type: 'image' },
          button:  { text: 'Add Images' },
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
          if (mode == "slider") {
            var columns = jQuery(miracle_file_frame[frame_key].el).find("select[data-setting='columns']").val();
            template = template.replace(' is_thumb_full="{is_thumb_full}"', '').replace('{columns}', columns);
          } else if (mode == "gallery2") {
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
          editor.insertContent(template);
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