<?php
$mts_options = get_option(MTS_THEME_NAME);
$brands_heading = isset( $mts_options['brands_heading'] ) ? $mts_options['brands_heading'] : '';
?>
<div class="shop-by-brand home-section clearfix">
	<div class="container">
		<div class="brand-controls">
			<?php if ( !empty( $brands_heading ) ) { ?><div class="featured-category-title"><?php echo $brands_heading; ?></div><?php } ?>
			<div class="custom-nav">
				<a class="btn brand-prev"><i class="fa fa-angle-left"></i></a>
				<a class="btn brand-next"><i class="fa fa-angle-right"></i></a>
			</div>
		</div>
		<?php if ( !empty( $mts_options['brand_images'] ) ) { ?>
			<div class="brand-container clearfix loading">
				<div id="brands-slider" class="brand-category">
				<?php foreach ( $mts_options['brand_images'] as $image ) {
					extract($image);// was gettin' some strange "Illegal string offset" warning, only this helped
					if ( !empty( $image ) ) { ?>
						<div class="brand-slider">
							<?php if(!empty($link)) { ?><a href="<?php echo esc_url( $link );?>"><?php } ?>
								<?php echo wp_get_attachment_image( $image, 'brand', false, array('title' => '') ); ?>
							<?php if(!empty($link)) { ?></a><?php } ?>
						</div>
					<?php }
				} ?>
				</div>
			</div>
		<?php } ?>
	</div>
</div>