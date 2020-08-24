<?php $mts_options = get_option(MTS_THEME_NAME); ?>
<?php get_header(); ?>
<?php
if ( is_home() && $mts_options['mts_featured_slider'] == '1' ) {
	get_template_part( 'home/section', 'slider' );
}
?>
<div id="page" class="index-page">
        <?php
        if ( is_array( $mts_options['mts_home_layout'] ) && array_key_exists( 'enabled', $mts_options['mts_home_layout'] ) ) {
            $homepage_layout = $mts_options['mts_home_layout']['enabled'];
        } else {
            $homepage_layout = array();
        }

        foreach( $homepage_layout as $key => $section ) { get_template_part( 'home/section', $key ); } 
        ?>
<?php get_footer(); ?>