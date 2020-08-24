<?php
/**
 * Custom Header
 *
 * @package Bosco
 */

/**
 * Setup the WordPress core custom header feature.
 */
function bosco_custom_header_setup() {
	add_theme_support( 'custom-header', apply_filters( 'bosco_custom_header_args', array(
		'default-image'          => '',
		'default-text-color'     => '000000',
		'width'                  => 840,
		'height'                 => 154,
		'flex-height'            => false,
		'wp-head-callback'       => 'bosco_header_style',
		'admin-head-callback'    => 'bosco_admin_header_style',
		'admin-preview-callback' => 'bosco_admin_header_image',
	) ) );
}
add_action( 'after_setup_theme', 'bosco_custom_header_setup' );

if ( ! function_exists( 'bosco_header_style' ) ) :
/**
 * Styles the header image and text displayed on the blog
 */
function bosco_header_style() {
	$header_image = get_header_image();
	$header_text_color = get_header_textcolor();

	// If no custom header image and no custom color for text is set, let's bail.
	if ( empty( $header_image ) && display_header_text() && $header_text_color === get_theme_support( 'custom-header', 'default-text-color' ) )
		return;

	// If we get this far, we have custom styles.
	?>
	<style type="text/css" id="bosco-header-css">
	<?php
		if ( ! empty( $header_image ) ) :
	?>
		.site-branding {
			background: url(<?php header_image(); ?>) no-repeat scroll top;
			background-size: 840px 154px;
		}
	<?php
		endif;

		// Has the text been hidden?
		if ( ! display_header_text() ) :
	?>
		.site-title,
		.site-description {
			position: absolute;
			clip: rect(1px, 1px, 1px, 1px);
		}
	<?php
		// If the user has set a custom color for the text use that
		elseif ( display_header_text() && $header_text_color !== get_theme_support( 'custom-header', 'default-text-color') ) :
	?>
		.site-title a,
		.site-description {
			color: #<?php echo $header_text_color; ?>;
		}
	<?php endif; ?>
	</style>
	<?php
}
endif; // bosco_header_style

if ( ! function_exists( 'bosco_admin_header_style' ) ) :
/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 */
function bosco_admin_header_style() {
	$header_image = get_header_image();
	$header_text_color = get_header_textcolor();
?>
	<style type="text/css" id="bosco-admin-header-css">
		#headimg h1 {
			font-family: Lora, Georgia, serif;
			font-size: 29px;
			font-weight: bold;
			line-height: 1.3;
			margin: 0;
			text-align: center;
		}
		#headimg h1 a {
			color: #c00;
			text-decoration: none;
		}
		#headimg h2 {
			font-family: Lora, Georgia, serif;
			font-size: 26px;
			font-weight: bold;
			line-height: 1.3;
			margin: 0;
			padding: 0;
			text-align: center;
		}
		.appearance_page_custom-header #headimg {
			background-color: #fff;
			border: none;
			padding: 42px 0;
		}

		<?php if ( ! empty( $header_image ) ) : ?>
		.site-branding {
			background: url(<?php header_image(); ?>) no-repeat scroll top;
			background-size: 840px 154px;
		}
		<?php endif; ?>

		<?php if ( ! display_header_text() ) : ?>
		#headimg h1,
		#headimg h2 {
			.site-title,
			.site-description {
				position: absolute;
				clip: rect(1px, 1px, 1px, 1px);
		}
		<?php elseif ( display_header_text() && $header_text_color !== get_theme_support( 'custom-header', 'default-text-color') ) : ?>
		.site-title a,
		.site-description {
			color: #<?php echo $header_text_color; ?>;
		}
		<?php endif; ?>
	</style>
<?php
}
endif; // bosco_admin_header_style

if ( ! function_exists( 'bosco_admin_header_image' ) ) :
/**
 * Custom header image markup displayed on the Appearance > Header admin panel.
 */
function bosco_admin_header_image() {
?>
	<div id="headimg">
		<?php
			if ( ( display_header_text() && get_header_textcolor() !== get_theme_support( 'custom-header', 'default-text-color') ) ) {
				$style = ' style="color:#' . get_header_textcolor() . ';"';
			} else {
				$style = '';
			}
		?>
		<div class="home-link">
			<h1 class="displaying-header-text"><a id="name"<?php echo $style; ?> onclick="return false;" href="#"><?php bloginfo( 'name' ); ?></a></h1>
			<h2 id="desc" class="displaying-header-text"<?php echo $style; ?>><?php bloginfo( 'description' ); ?></h2>
		</div>
	</div>
<?php
}
endif; // bosco_admin_header_image
