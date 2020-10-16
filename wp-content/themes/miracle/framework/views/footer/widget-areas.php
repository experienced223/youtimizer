<?php
/**
 * Output footer widget areas
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }
$count = miracle_get_option('footer_widget_areas', 4);
?>
<?php if ( $count !== 'none' ) : ?>
	<div class="footer-wrapper">
		<div class="container">
			<div class="row add-clearfix same-height">
			  <?php
			    $column_class = '';
				if ( $count == 1 ) {
					$column_class = 'col-sm-12';
				} else if ( $count == 2 ) {
					$column_class = 'col-md-6';
				} else if ( $count == 3 ) {
					$column_class = 'col-sm-4';
				} else if ( $count == 4 ) {
					$column_class = 'col-sm-6 col-md-3';
				}
				for ( $i = 1; $i <= $count; $i++ ) {
					echo '<div class="' . $column_class . '">';
					dynamic_sidebar( 'sidebar-footer-' . $i );
					if ( $i == $count ) {
						miracle_get_template( 'scroll-top', '', 'footer' );
					}
					echo '</div>';
				}
			  ?>
			</div>
		</div>
	</div>
<?php endif; ?>