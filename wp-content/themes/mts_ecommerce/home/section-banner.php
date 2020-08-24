<?php
$mts_options = get_option(MTS_THEME_NAME);
$banner_light_text = isset( $mts_options['banner_light_text'] ) ? $mts_options['banner_light_text'] : '';
$banner_dark_text = isset( $mts_options['banner_dark_text'] ) ? $mts_options['banner_dark_text'] : '';
$banner_button_text = isset( $mts_options['banner_button_text'] ) ? $mts_options['banner_button_text'] : '';
$banner_button_link = isset( $mts_options['banner_button_link'] ) ? $mts_options['banner_button_link'] : '';
?>
<div class="thin-banner-section home-section clearfix">
	<div class="container">
		<div class="thin-banner clearfix">
		<?php if ( !empty( $banner_light_text ) ) { ?>
			<div class="text-1"><?php echo $banner_light_text; ?></div>
		<?php } ?>
		<?php if ( !empty( $banner_light_text ) ) { ?>
			<div class="text-2"><?php echo $banner_dark_text; ?></div>
		<?php } ?>
		<?php if ( !empty( $banner_button_text ) && !empty( $banner_button_link ) ) { ?>
			<div class="readMore">
				<a href="<?php echo esc_url( $banner_button_link ); ?>"><?php echo $banner_button_text; ?></a>
			</div>
		<?php } ?>
		</div>
	</div>
</div>