<?php
$mts_options = get_option(MTS_THEME_NAME);
$lookbook_heading = isset( $mts_options['lookbook_heading'] ) ? $mts_options['lookbook_heading'] : '';
if ( is_active_sidebar( 'homepage-lookbook' ) ) {
	$class = "";
	$item_count = 5;
} else {
	$class = "full";
	$item_count = 7;
}
?>
<script>
	var mtsITEM = "<?php echo $item_count; ?>";
</script>
<div class="lookbook-with-sidebar home-section clearfix">
	<div class="container">
		<div class="lookbook-with-sidebar-left <?php echo isset($class) ? $class : "" ?>">
			<?php if ( !empty( $lookbook_heading ) ) { ?><div class="featured-category-title"><?php echo $lookbook_heading; ?></div><?php } ?>
			<?php if ( !empty( $mts_options['lookbook_images'] ) ) { ?>
				<div class="lookbook-container clearfix loading">
					<div id="lookbook-slider" class="lookbook-category">
					<?php foreach ( $mts_options['lookbook_images'] as $image ) {
						if ( !empty( $image['image'] ) ) {
							$image_url = wp_get_attachment_image_src( $image['image'], 'full' );
							$image_url = $image_url[0]; ?>
							<div class="lookbook-slider">
								<a href="<?php echo esc_url( $image_url )?>" data-title="<?php echo esc_attr( $image['title'] )?>" class="lookbook-image-src">
									<?php echo wp_get_attachment_image( $image['image'], 'lookbook', false, array('title' => '') ); ?>
									<div class="lookbook-caption">
										<div class="icon"><i class="fa fa-search"></i></div>
										<div class="text"><?php _e( 'Enlarge', MTS_THEME_TEXTDOMAIN ); ?></div>
									</div>
								</a>
							</div>
						<?php } 
					} ?>
					</div>
					<div class="custom-nav">
						<a class="btn lookbook-prev"><i class="fa fa-angle-left"></i></a>
						<a class="btn lookbook-next"><i class="fa fa-angle-right"></i></a>
					</div>
				</div>
			<?php } ?>
		</div>
		<?php if ( is_active_sidebar( 'homepage-lookbook' ) ) { ?>
			<aside class="sidebar c-4-12">
				<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar( 'homepage-lookbook' ) ) : ?><?php endif; ?>
			</aside>
		<?php } ?>
	</div>
</div>