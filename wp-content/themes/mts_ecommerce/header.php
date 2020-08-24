<!DOCTYPE html>
<?php $mts_options = get_option(MTS_THEME_NAME); ?>
<html class="no-js" <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame -->
	<!--[if IE ]>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<![endif]-->
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<?php mts_meta(); ?>
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<?php wp_head(); ?>
</head>
<body id="blog" <?php body_class('main'); ?>>
	<div class="main-container">
		<?php $header_type = isset( $mts_options['mts_header_layout'] ) ? $mts_options['mts_header_layout'] : '1'; ?>
		<?php get_template_part('header-type/header', $header_type ); ?>
		<?php do_action('mts_after_header'); ?>
		<?php
		if ( '1' === $mts_options['mts_breadcrumb'] && ! is_home() && ! is_page_template( 'page-contact.php' ) ) {
			mts_the_breadcrumb();
		}
		?>
