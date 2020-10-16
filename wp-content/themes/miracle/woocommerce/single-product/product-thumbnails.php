<?php
/**
 * WOOCOMMERCE/SINGLE-PRODUCT/PRODUCT_THUMBNAILS.PHP
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post, $product, $woocommerce;

$attachment_ids = $product->get_gallery_attachment_ids();

if ( $attachment_ids ) {
	$loop 		= 0;
	$columns 	= apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
	?>
	<div id="sync2_<?php echo get_the_ID(); ?>" class="owl-carousel post-slider style3 thumbnails" data-items="<?php echo esc_attr( $columns ); ?>"><?php

		foreach ( $attachment_ids as $attachment_id ) {

			$image_link = wp_get_attachment_url( $attachment_id );

			if ( ! $image_link )
				continue;

			$image       = wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_small_thumbnail_size', 'shop_thumbnail' ) );
      $image_class = 'item';
			$image_title = esc_attr( get_the_title( $attachment_id ) );

			echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', sprintf( '<div class="%s"><a href="%s">%s</a></div>', $image_class, $image_link, $image ), $attachment_id, $post->ID, $image_class );

			$loop++;
		}

	?></div>
  <script>
        sjq(document).ready(function($) {

            var sync1 = $("#sync1_<?php echo get_the_ID(); ?>");
            var sync2 = $("#sync2_<?php echo get_the_ID(); ?>");
             
            sync1.owlCarousel({
                singleItem : true,
                slideSpeed : 1000,
                navigation: false,
                pagination:false,
                afterAction : syncPosition,
                responsiveRefreshRate : 200,
            });
             
            sync2.owlCarousel({
                items : <?php echo esc_attr( $columns ); ?>,
                itemsDesktop : [1199,3],
                itemsDesktopSmall : [979,2],
                itemsTablet : [768,3],
                itemsMobile : [479,2],
                navigation: true,
                navigationText: false,
                pagination:false,
                responsiveRefreshRate : 100,
                afterInit : function(el){
                    el.find(".owl-item").eq(0).addClass("synced");
                    el.find(".owl-wrapper").equalHeights();
                }
            });
             
            function syncPosition(el){
                var current = this.currentItem;
                sync2
                    .find(".owl-item")
                    .removeClass("synced")
                    .eq(current)
                    .addClass("synced")
                if(sync2.data("owlCarousel") !== undefined){
                    center(current)
                }
            }
             
            sync2.on("click", ".owl-item", function(e){
                e.preventDefault();
                var number = $(this).data("owlItem");
                sync1.trigger("owl.goTo", number);
            });
             
            function center(number){
                var sync2visible = sync2.data("owlCarousel").owl.visibleItems;
                var num = number;
                var found = false;
                for(var i in sync2visible){
                    if(num === sync2visible[i]){
                        var found = true;
                    }
                }
             
                if(found===false){
                    if(num>sync2visible[sync2visible.length-1]){
                        sync2.trigger("owl.goTo", num - sync2visible.length+2)
                    }else{
                        if(num - 1 === -1){
                            num = 0;
                        }
                        sync2.trigger("owl.goTo", num);
                    }
                } else if(num === sync2visible[sync2visible.length-1]){
                    sync2.trigger("owl.goTo", sync2visible[1])
                } else if(num === sync2visible[0]){
                    sync2.trigger("owl.goTo", num-1)
                }
            }

            var $easyzoom = $('.product-images .easyzoom').easyZoom();
            var $easyzoomApi = $easyzoom.data('easyZoom');
        });
    </script>
	<?php
}
