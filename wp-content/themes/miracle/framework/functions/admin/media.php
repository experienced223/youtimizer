<?php

add_action( 'print_media_templates', 'miracle_print_media_templates' );

function miracle_print_media_templates() {
?>
    <script type="text/html" id="tmpl-gallery-type-setting-mode">
        <label class="setting">
            <span><?php _e('Mode', LANGUAGE_ZONE); ?></span>
            <select data-setting="mode">
                <option value=""> Default </option>
                <option value="metro"> Metro Slider </option>
                <option value="slideshow" selected="selected"> Slideshow </option>
            </select>
        </label>
    </script>

    <script type="text/html" id="tmpl-miracle-gallery-setting-mode">
        <h3>Gallery Settings</h3>
        <label class="setting">
            <span><?php _e('Mode', LANGUAGE_ZONE); ?></span>
            <select data-setting="mode">
                <option value="slider"> Simple slider </option>
                <option value="gallery1"> Gallery 1 </option>
                <option value="gallery2"> Gallery 2 </option>
                <option value="frame"> Frame slider </option>
                <option value="metro1"> Metro 1 </option>
                <option value="metro2"> Metro 2 </option>
                <option value="carousel"> Carousel </option>
            </select>
        </label>
        <label class="setting">
            <span><?php _e('Columns', LANGUAGE_ZONE); ?></span>
            <select data-setting="columns">
                <option value="1"> 1 </option>
                <option value="2"> 2 </option>
                <option value="3" selected="selected"> 3 </option>
                <option value="4"> 4 </option>
                <option value="5"> 5 </option>
            </select>
        </label>
        <label class="setting" style="display: none">
            <span><?php _e('Thumbnails have full width?', LANGUAGE_ZONE); ?></span>
            <input type="checkbox" data-setting="thumb-full">
        </label>
        <div class="miracle-image-carousel-settings" style="display: none">
            <label class="setting">
                <span><?php _e('Front Width', LANGUAGE_ZONE); ?></span>
                <input type="text" data-setting="front-width">
            </label>
            <label class="setting">
                <span><?php _e('Front Height', LANGUAGE_ZONE); ?></span>
                <input type="text" data-setting="front-height">
            </label>
            <label class="setting">
                <span><?php _e('Slide Count', LANGUAGE_ZONE); ?></span>
                <input type="text" data-setting="slide-count">
            </label>
            <label class="setting">
                <span><?php _e('Horizontal Align', LANGUAGE_ZONE); ?></span>
                    <select data-setting="halign">
                    <option value="left"> Left </option>
                    <option value="right"> Right </option>
                    <option value="center" selected="selected"> Center </option>
                </select>
            </label>
            <label class="setting">
                <span><?php _e('Vertical Align', LANGUAGE_ZONE); ?></span>
                    <select data-setting="valign">
                    <option value="top"> Top </option>
                    <option value="bottom"> Bottom </option>
                    <option value="center" selected="selected"> Center </option>
                </select>
            </label>
        </div>
    </script>

    <script>
        jQuery(document).ready(function() {

            // add your shortcode attribute and its default value to the
            // gallery settings list; $.extend should work as well...
            _.extend(wp.media.gallery.defaults, {
                mode: '',
                columns: '3'
            });
            // merge default gallery settings template with yours
            wp.media.view.Settings.Gallery = wp.media.view.Settings.Gallery.extend({
                template: function(view){
                    if (wp.media.is_shortcode) {
                        delete wp.media.is_shortcode;
                        return wp.media.template('miracle-gallery-setting-mode')(view);
                    }
                    return wp.media.template('gallery-settings')(view)
                        + wp.media.template('gallery-type-setting-mode')(view);
                }
            });
        });
    </script>
<?php
}