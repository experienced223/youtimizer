<?php
/**
 * Display Single post contenet according to its post type
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

global $post;

$post_link = get_permalink();
$image_size = MiracleHelper::get_thumbnail_size( 1 );

$category = get_the_category(); 
$category_name = '';
$category_link = 'javascript:void(0)';
if ( isset( $category[0] ) ) {
	$category_link = esc_url( get_category_link( $category[0]->cat_ID ) );
	$category_name =  $category[0]->cat_name;
}


?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'box-lg' ); ?>>
	<div class="post-date">
		<span class="day"><?php echo get_the_date( 'j' ); ?></span>
		<span class="month uppercase"><?php echo get_the_date( 'M' ) ?></span>
	</div>

<?php if ( !post_password_required() ) : ?>

	<?php

	$post_format = get_post_format();
	switch ( $post_format ) {
		case 'video':
			$video_url = esc_url( get_post_meta( $post->ID, '_miracle_video_url', true ) );
			$video_embed_code = esc_html( get_post_meta( $post->ID, '_miracle_video_embed', true ) );
			$video_ratio = esc_attr( get_post_meta( $post->ID, '_miracle_video_ratio', true ) );
			if ( !empty( $video_url ) || !empty( $video_embed_code ) ) {
				miracle_get_post_video( $video_url, $video_embed_code, $video_ratio );
			}
			break;
		case 'gallery':
			$gallery = get_post_gallery( $post->ID, false );
			/*if ( !empty($gallery['ids']) ) {

				$media_items = array_map( 'trim', explode( ',', $gallery['ids'] ) );
				$attachments = miracle_get_attachment_post_data( $media_items, $image_size );
				miracle_get_post_gallery( $attachments, '', false );
			} else */if ( has_post_thumbnail() && empty( $gallery ) ) {
				echo '<div class="image-container">';
				the_post_thumbnail( 'full' );
				echo '</div>';
			}
			break;
		case 'audio':
			$audio_url = esc_url( get_post_meta( $post->ID, '_miracle_audio_mp3', true ) );
			$audio_embed_code = esc_html( get_post_meta( $post->ID, '_miracle_audio_embed', true ) );
			miracle_get_post_audio( $audio_url, $audio_embed_code );
			break;
		case 'quote':
			$quote_text = get_post_meta( $post->ID, '_miracle_quote_quote', true );
			if ( !empty( $quote_text ) ) {
				echo '<blockquote class="style1">';
				echo "<p>" . $quote_text . "</p>";
				$author = get_post_meta( $post->ID, '_miracle_quote_cite', true );
				if ( !empty($author) ) {
					echo '<span class="name">&#8212;' . $author .'</span>';
				}
				echo '</blockquote>';
			}
			break;
		default:
			// thumbnail
			if ( has_post_thumbnail() ) {
				echo '<div class="image-container">';
				the_post_thumbnail( 'full' );
				echo '</div>';
			}
	}
	?>

	<div class="post-content">
		<?php miracle_print_post_share_buttons(); ?>
		<h2 class="entry-title"><?php the_title(); ?></h2>
		<div class="post-meta">
			<span class="entry-author fn"><?php printf(__( 'by <a href="%s">%s</a>', LANGUAGE_ZONE ), esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ), get_the_author()); ?></span>
			<span class="entry-author fn"><?php printf(__( 'in <a href="%s">%s</a>', LANGUAGE_ZONE ), esc_url( $category_link ), esc_html( $category_name ) ) ?></span>
			<span class="post-comment">
				<?php
					$comment_number = get_comments_number( $post->ID );
					if ( $comment_number == 0 ) {
						esc_html_e( 'No Comments', LANGUAGE_ZONE );
					} elseif ( $comment_number == 1 ) {
						esc_html_e( '1 Comment', LANGUAGE_ZONE );
					} else {
						echo esc_html( $comment_number . ' ' );
						esc_html_e( 'Comments', LANGUAGE_ZONE );
					}
				?>
			</span>
		</div>
		<?php the_content(); ?>
		<?php miracle_print_post_tags(); ?>
	</div>
	<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', LANGUAGE_ZONE ), 'after' => '</div>' ) ); ?>

	<?php
		if ( miracle_get_option('blog_show_author_in_posts', 1) ) {
			miracle_display_post_author( 'box' );
		}

		if ( miracle_get_option( 'blog_show_related_posts', 1 ) ) {
			miracle_display_related_posts();
		}

		comments_template();
	?>

	

<?php else: ?>

	<?php echo MiracleHelper::get_cleared_content(); ?>

<?php endif; ?>

</article>
