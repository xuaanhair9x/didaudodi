<?php
$mts_options = get_option(MTS_THEME_NAME);
if ( mts_isWooCommerce() ) {
	if ( isset( $mts_options['mts_home_tabs'] ) && is_array( $mts_options['mts_home_tabs'] ) && array_key_exists( 'enabled', $mts_options['mts_home_tabs'] ) ) {
		$tabs = $mts_options['mts_home_tabs']['enabled'];
	} else {
		$tabs = array( 'best_sellers_tab' => __('Best sellers', MTS_THEME_TEXTDOMAIN), 'new_products_tab' => __('New Arrivals', MTS_THEME_TEXTDOMAIN), 'top_rated_tab' => __('Top Rated', MTS_THEME_TEXTDOMAIN) );
	}
	?>
	<div class="featured-product-tabs home-section clearfix">
		<div class="container">
			<ul class="tabs">
				<?php $i=0; foreach ( $tabs as $tab => $label ) { ?>
					<?php $active_class = ( 0 == $i ) ? ' active loaded' : ''; ?>
					<li class="tab<?php echo $active_class; ?>"><a href="#" data-tab="<?php echo $tab; ?>"><?php echo $label; ?></a></li>
					<?php $i++ ?>
				<?php } ?>
			</ul>
			<div class="tabs-content">
				<?php $i=0; foreach ( $tabs as $tab => $label ) { ?>
					<?php $active_class = ( 0 == $i ) ? ' active' : ''; ?>
					<div class="tab-content <?php echo $tab . $active_class; ?>">
						<?php if ( 0 == $i ) mts_homepage_tab( $tab ); ?>
					</div>
					<?php $i++ ?>
				<?php } ?>
			</div>
		</div>
	</div>
<?php } ?>
