<?php
/**
 * Template Name: Contact Page
 */
?>
<?php $mts_options = get_option(MTS_THEME_NAME); ?>
<?php get_header(); ?>
<?php if ( $mts_options['mts_map_coordinates'] != '' ) { ?>
<div id="map"></div>
<?php } ?>
<div id="page" class="<?php mts_single_page_class(); ?>">
	<?php $header_animation = mts_get_post_header_effect(); ?>
	<?php if ( 'parallax' === $header_animation ) {?>
		<?php if (mts_get_thumbnail_url()) : ?>
	        <div id="parallax" <?php echo 'style="background-image: url('.mts_get_thumbnail_url().');"'; ?>></div>
	    <?php endif; ?>
	<?php } else if ( 'zoomout' === $header_animation ) {?>
		 <?php if (mts_get_thumbnail_url()) : ?>
	        <div id="zoom-out-effect"><div id="zoom-out-bg" <?php echo 'style="background-image: url('.mts_get_thumbnail_url().');"'; ?>></div></div>
	    <?php endif; ?>
	<?php } ?>
	<article class="">
		<div id="content_box" >
			<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
				<div id="post-<?php the_ID(); ?>" <?php post_class('g post'); ?>>
					<div class="single_page">
						<div id="presale-q" class="faqs-container clearfix">
						<?php if ( !empty( $mts_options['mts_faqs_title'] ) ) { ?><h3 class="title"><?php echo $mts_options['mts_faqs_title']; ?></h3><?php } ?>
						<?php if ( !empty( $mts_options['mts_faqs_desc'] ) ) { ?><div class="sub-heading"><?php echo $mts_options['mts_faqs_desc']; ?></div><?php } ?>
						<?php if ( !empty( $mts_options['mts_faqs'] ) ) { ?>
							<div class="faq-blocks clearfix">
							<?php foreach ( $mts_options['mts_faqs'] as $faq ) { ?>
								<div class="faq-block">
									<div class="faq-q"><?php echo $faq['question'];?></div>
									<div class="faq-a" style="display: none;"><?php echo $faq['answer'];?></div>
								</div>
							<?php } ?>
							</div>
						<?php } ?>
						</div>
						<header class="entry-header">
							<h1 class="title entry-title"><?php the_title(); ?></h1>
						</header>
						<div class="post-content box mark-links entry-content contact-us">
							<?php if ( !empty( $mts_options['mts_contact_title'] ) ) { ?><h3 class="title"><?php echo $mts_options['mts_contact_title']; ?></h3><?php } ?>
							<?php if ( !empty( $mts_options['mts_contact_desc'] ) ) { ?><div class="sub-heading"><?php echo $mts_options['mts_contact_desc']; ?></div><?php } ?>
							<?php //the_content(); ?>
							<?php //wp_link_pages(array('before' => '<div class="pagination">', 'after' => '</div>', 'link_before'  => '<span class="current"><span class="currenttext">', 'link_after' => '</span></span>', 'next_or_number' => 'next_and_number', 'nextpagelink' => __('Next', MTS_THEME_TEXTDOMAIN ), 'previouspagelink' => __('Previous', MTS_THEME_TEXTDOMAIN ), 'pagelink' => '%','echo' => 1 )); ?>
							<?php mts_contact_form() ?>
						</div><!--.post-content box mark-links-->
					</div>
				</div>
				<?php //comments_template( '', true ); ?>
			<?php endwhile; ?>
		</div>
	</article>
	<?php //get_sidebar(); ?>
	<?php if ( $mts_options['mts_map_coordinates'] != '' ) { ?>
		<?php
		if (!isset($mts_options['mts_maps_api_key'])) {
			$mts_options['mts_maps_api_key'] = '';
		}
		?>
		<script type="text/javascript">
			//var mapLoaded = false;
			function mtsMapInitialize() {
				mapLoaded = true;

				var geocoder = new google.maps.Geocoder();
				var lat='';
				var lng='';
				geocoder.geocode( { 'address': '<?php echo addslashes($mts_options['mts_map_coordinates']); ?>'}, function(results, status) {
					if (status == google.maps.GeocoderStatus.OK) {
						lat = results[0].geometry.location.lat(); //getting the lat
						lng = results[0].geometry.location.lng(); //getting the lng
						map.setCenter(results[0].geometry.location);
						var marker = new google.maps.Marker({
							map: map,
							position: results[0].geometry.location
						});
					}
				});

				var latlng = new google.maps.LatLng(lat, lng);

				var mapOptions = {
					zoom: 11,
					center: latlng,
					scrollwheel: false,
					navigationControl: false,
					scaleControl: false,
					streetViewControl: false,
					draggable: true,
					panControl: false,
					mapTypeControl: false,
					zoomControl: false,
					mapTypeId: google.maps.MapTypeId.ROADMAP
				};

				var map = new google.maps.Map(document.getElementById("map"), mapOptions);
			}

			jQuery(window).load(function() {
				jQuery('body').append('<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $mts_options['mts_maps_api_key']; ?>&sensor=false&v=3&callback=mtsMapInitialize"></'+'script>');
			});
		</script>
	<?php } ?>
<?php get_footer(); ?>