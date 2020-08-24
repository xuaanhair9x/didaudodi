<?php
$mts_options = get_option(MTS_THEME_NAME);

$welcome_heading = isset( $mts_options['welcome_heading'] ) ? $mts_options['welcome_heading'] : '';
$welcome_subheading = isset( $mts_options['welcome_subheading'] ) ? $mts_options['welcome_subheading'] : '';
?>
<div class="welcome-ecommerce home-section clearfix">
	<div class="container">
	<?php if ( !empty( $welcome_heading ) ) { ?>
		<h2 class="title"><?php echo $welcome_heading; ?></h2>
	<?php } ?>
	<?php if ( !empty( $welcome_subheading ) ) { ?>
		<div class="sub-title"><?php echo $welcome_subheading; ?></div>
	<?php } ?>

	<?php
	if ( isset( $mts_options['welcome_features'] ) ) {
		foreach( $mts_options['welcome_features'] as $feature ) :
			$title       = $feature['title'];
			$icon        = $feature['icon'];
			$description = $feature['description'];
			$url         = $feature['url'];
			?>
			<div class="ecommerce-content">
			<?php if ( !empty( $icon ) ) { ?>
				<div class="icon"><i class="fa fa-<?php echo $icon; ?>"></i></div>
			<?php } ?>
				<header>
				<?php if ( !empty( $title ) ) { ?>
					<h3 class="title front-view-title">
						<?php if ( !empty( $url ) ) { ?>
						<a href="<?php echo esc_url( $url ); ?>">
						<?php } ?>
							<?php echo $title; ?>
						<?php if ( !empty( $url ) ) { ?>
						</a>
						<?php } ?>
					</h3>
				<?php } ?>
				<?php if ( !empty( $description ) ) { ?>
					<div class="front-view-content"><?php echo $description; ?></div>
				<?php } ?>
				</header>
			</div>
		<?php
		endforeach;
	} ?>
	</div>
</div>