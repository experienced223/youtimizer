<?php
/**
 * Portfolio single template
 *
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

global $post;

$portfolio_id = $post->ID;
$project_link = get_post_meta( $portfolio_id, '_miracle_portfolio_item_project_link', true );
$media_type = get_post_meta( $portfolio_id, '_miracle_portfolio_item_media_type', true );
$view_options = get_post_meta ( $portfolio_id, '_miracle_portfolio_item_view_options', true );
$media_gallery_type_style = get_post_meta ( $portfolio_id, '_miracle_portfolio_item_gallery_view_style', true );
$media_gallery_type_columns = (int)get_post_meta( $portfolio_id, '_miracle_portfolio_item_gallery_columns', true );

$post_classes = array( 'post-wrapper' );
$media_content_classes = array( 'media-container' );
$portfolio_content_classes = array( 'portfolio-content-container' );
$portfolio_detail_classes = array( 'portfolio-detail' );

$is_vertical = ($view_options == 'vertical');
if ( $is_vertical ) {
	$post_classes[] = 'row';
	$media_content_classes[] = 'col-sm-8';
	$portfolio_content_classes[] = 'col-sm-4';
	$portfolio_detail_classes[] = 'portfolio-follow';

	$image_size = MiracleHelper::get_thumbnail_size( 1, true );
} else {
	$portfolio_detail_classes[] = 'row';

	$image_size = MiracleHelper::get_thumbnail_size( 1, false );
}

if ( $media_type == 'gallery' && $media_gallery_type_style == 'gallery1' ) {
	$image_size = MiracleHelper::get_thumbnail_size( $media_gallery_type_columns, $is_vertical );
} else if ( $media_type == 'gallery' && $media_gallery_type_style == 'gallery2' ) {
	$image_size = MiracleHelper::get_thumbnail_size( $media_gallery_type_columns, $is_vertical, true );
} else {
	$image_size = MiracleHelper::get_thumbnail_size( 1, $is_vertical );
}

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( implode(' ', $post_classes) ); ?>>

	<div class="<?php echo implode(' ', $media_content_classes); ?>">
	<?php if ( !post_password_required() ) : ?>
		<?php
			if ( $media_type == 'image' ) {
				echo '<div class="image-container box-lg">';
				//echo get_the_post_thumbnail( $portfolio_id , $image_size );
				echo get_the_post_thumbnail( $portfolio_id , 'full' );
				echo '</div>';
			} else if ( $media_type == 'video' ) {
				$video_url = esc_url( get_post_meta( $post->ID, '_miracle_video_url', true ) );
				$video_embed_code = esc_html( get_post_meta( $post->ID, '_miracle_video_embed', true ) );
				$video_ratio = esc_attr( get_post_meta( $post->ID, '_miracle_video_ratio', true ) );
				echo '<div class="video-container box-lg"><div class="full-video">';
				miracle_get_post_video( $video_url, $video_embed_code, $video_ratio );
				echo '</div></div>';
			} else if ( $media_type == 'gallery' ) {
				$galleries = get_post_galleries( $portfolio_id, false );
				if ( !empty( $galleries ) ) {
					foreach ( $galleries as $i=>$g ) {
						if ( $media_type == 'gallery' && $media_gallery_type_style == 'gallery2' ) {
							$double_image_size = MiracleHelper::get_thumbnail_size( $media_gallery_type_columns === 1 ? 1 : floor($media_gallery_type_columns / 2), $is_vertical, true );
						}

						$media_items = array_map( 'trim', explode( ',', $g['ids'] ) );
						$attachments = miracle_get_attachment_post_data( $media_items, $image_size );
						if ( $media_gallery_type_style == 'slider1' || $media_gallery_type_style == 'slider2' ) {
							miracle_get_post_gallery( $attachments, '', false, ( $media_gallery_type_style == 'slider2' ), 'box' );
						} else if ( $media_gallery_type_style == 'list' ) {
							foreach( $attachments as $attachment ) {
								echo '<div class="image hover-style1 box">';
								echo '<img src="'. esc_url( $attachment['img'] ) .'" alt="'. esc_attr( $attachment['alt'] ) .'" width="'. esc_attr( $attachment['img_width'] ) .'" height="'. esc_attr( $attachment['img_height'] ) .'" >';
								echo '<div class="caption-wrapper">';
								if ( !empty( $attachment['title'] ) ) {
									echo '<h4 class="slide-title">' . esc_html( $attachment['title'] ) . '</h4>';
								}
								if ( !empty( $attachment['description'] ) ) {
									echo 'p' . esc_html( $attachment['description'] ) . '</p>';
								}
								echo '</div>';
								echo '</div>';
							}
						} else if ( $media_gallery_type_style == 'gallery1' ) {
							echo '<div class="soap-gallery metro-style gallery-col-' . esc_attr( $media_gallery_type_columns ) . ' box"><div class="gallery-wrapper">';
							foreach( $attachments as $attachment ) {
								echo '<a href="' . esc_url( $attachment['full'] ) . '" class="image hover-style3">';
								echo '<img src="'. esc_url( $attachment['img'] ) .'" alt="'. esc_attr( $attachment['alt'] ) .'" width="'. esc_attr( $attachment['img_width'] ) .'" height="'. esc_attr( $attachment['img_height'] ) .'" >';
								echo '<div class="image-extras"></div>';
								echo '</a>';
							}
							echo '</div></div>';
						} else if ( $media_gallery_type_style == 'gallery2' ) {
							echo '<div class="iso-container style-masonry iso-col-' . esc_attr( $media_gallery_type_columns ) . ' box">';
							foreach( $attachments as $attachment ) {
								$item_classes = 'iso-item';
								if ( isset( $double_image_size ) ) {
									$attachment_images = miracle_get_attachment_post_data( array( $attachment['ID'] ), $double_image_size );
									$attachment = $attachment_images[0];
									$item_classes .= ' double-width';
									unset( $double_image_size );
								}
								echo '<div class="' . $item_classes . '">';
								echo '<a href="' . esc_url( $attachment['full'] ) . '" class="image soap-mfp-popup hover-style3">';
								echo '<img src="'. esc_url( $attachment['img'] ) .'" alt="'. esc_attr( $attachment['alt'] ) .'" width="'. esc_attr( $attachment['img_width'] ) .'" height="'. esc_attr( $attachment['img_height'] ) .'" >';
								echo '<div class="image-extras"></div>';
								echo '</div>';
							}
							echo '</div>';
						}
					}
				}
			}
		?>
	<?php else: ?>

		<?php MiracleHelper::get_cleared_content(); ?>

	<?php endif; ?>
	</div>
	<div class="<?php echo esc_attr( implode(' ', $portfolio_content_classes) ); ?>">
		<div class="<?php echo esc_attr( implode(' ', $portfolio_detail_classes) ); ?>">
		<?php if ( $is_vertical ) { ?>
			<?php miracle_print_post_share_buttons(); ?>
			<div class="post-meta block">
				<?php miracle_print_portfolio_meta(); ?>
			</div>
			<h5 class="portfolio-title">
				<?php if ( !empty( $project_link ) ) { ?>
					<a href="<?php echo esc_url( $project_link ); ?>" target="_blank"><?php the_title(); ?></a>
				<?php } else { ?>
					<?php the_title(); ?>
				<?php } ?>
			</h5>
			<?php echo MiracleHelper::get_cleared_content(); ?>
		<?php } else { ?>
			<div class="col-sm-8 col-md-9">
				<?php miracle_print_post_share_buttons(); ?>
				<h5 class="portfolio-title">
					<?php if ( !empty( $project_link ) ) { ?>
						<a href="<?php echo esc_url( $project_link ); ?>" target="_blank"><?php the_title(); ?></a>
					<?php } else { ?>
						<?php the_title(); ?>
					<?php } ?>
				</h5>
				<?php echo MiracleHelper::get_cleared_content(); ?>
			</div>
			<div class="col-sm-4 col-md-3">
				<div class="post-meta">
					<?php miracle_print_portfolio_meta(); ?>
				</div>
			</div>
		<?php } ?>
		</div>
	</div>
</article>
<?php miracle_link_pages(); ?>