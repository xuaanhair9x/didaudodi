<?php

defined('ABSPATH') or die;

/*
 *
 * Require the framework class before doing anything else, so we can use the defined urls and dirs
 *
 */
require_once( dirname( __FILE__ ) . '/options/options.php' );

/*
 *
 * Add support tab
 *
 */
if ( ! defined('MTS_THEME_WHITE_LABEL') || ! MTS_THEME_WHITE_LABEL ) {
	require_once( dirname( __FILE__ ) . '/options/support.php' );
	$mts_options_tab_support = MTS_Options_Tab_Support::get_instance();
}

/*
 *
 * Custom function for filtering the sections array given by theme, good for child themes to override or add to the sections.
 * Simply include this function in the child themes functions.php file.
 *
 * NOTE: the defined constansts for urls, and dir will NOT be available at this point in a child theme, so you must use
 * get_template_directory_uri() if you want to use any of the built in icons
 *
 */
function add_another_section($sections){

	//$sections = array();
	$sections[] = array(
				'title' => __('A Section added by hook', MTS_THEME_TEXTDOMAIN ),
				'desc' => __('<p class="description">This is a section created by adding a filter to the sections array, great to allow child themes, to add/remove sections from the options.</p>', MTS_THEME_TEXTDOMAIN ),
				//all the glyphicons are included in the options folder, so you can hook into them, or link to your own custom ones.
				//You dont have to though, leave it blank for default.
				'icon' => trailingslashit(get_template_directory_uri()).'options/img/glyphicons/glyphicons_062_attach.png',
				//Lets leave this as a blank section, no options just some intro text set above.
				'fields' => array()
				);

	return $sections;

}//function
//add_filter('nhp-opts-sections-twenty_eleven', 'add_another_section');


/*
 *
 * Custom function for filtering the args array given by theme, good for child themes to override or add to the args array.
 *
 */
function change_framework_args($args){

	//$args['dev_mode'] = false;

	return $args;

}//function
//add_filter('nhp-opts-args-twenty_eleven', 'change_framework_args');

/*
 * This is the meat of creating the optons page
 *
 * Override some of the default values, uncomment the args and change the values
 * - no $args are required, but there there to be over ridden if needed.
 *
 *
 */

function setup_framework_options(){
$args = array();

//Set it to dev mode to view the class settings/info in the form - default is false
$args['dev_mode'] = false;
//Remove the default stylesheet? make sure you enqueue another one all the page will look whack!
//$args['stylesheet_override'] = true;

//Add HTML before the form
//$args['intro_text'] = __('<p>This is the HTML which can be displayed before the form, it isnt required, but more info is always better. Anything goes in terms of markup here, any HTML.</p>', MTS_THEME_TEXTDOMAIN );

//Setup custom links in the footer for share icons
$args['share_icons']['twitter'] = array(
										'link' => 'http://twitter.com/mythemeshopteam',
										'title' => 'Follow Us on Twitter',
										'img' => 'fa fa-twitter-square'
										);
$args['share_icons']['facebook'] = array(
										'link' => 'http://www.facebook.com/mythemeshop',
										'title' => 'Like us on Facebook',
										'img' => 'fa fa-facebook-square'
										);

//Choose to disable the import/export feature
//$args['show_import_export'] = false;

//Choose a custom option name for your theme options, the default is the theme name in lowercase with spaces replaced by underscores
$args['opt_name'] = MTS_THEME_NAME;

//Custom menu icon
//$args['menu_icon'] = '';

//Custom menu title for options page - default is "Options"
$args['menu_title'] = __('Theme Options', MTS_THEME_TEXTDOMAIN );

//Custom Page Title for options page - default is "Options"
$args['page_title'] = __('Theme Options', MTS_THEME_TEXTDOMAIN );

//Custom page slug for options page (wp-admin/themes.php?page=***) - default is "nhp_theme_options"
$args['page_slug'] = 'theme_options';

//Custom page capability - default is set to "manage_options"
//$args['page_cap'] = 'manage_options';

//page type - "menu" (adds a top menu section) or "submenu" (adds a submenu) - default is set to "menu"
//$args['page_type'] = 'submenu';

//parent menu - default is set to "themes.php" (Appearance)
//the list of available parent menus is available here: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
//$args['page_parent'] = 'themes.php';

//custom page location - default 100 - must be unique or will override other items
$args['page_position'] = 62;

//Custom page icon class (used to override the page icon next to heading)
//$args['page_icon'] = 'icon-themes';

//Set ANY custom page help tabs - displayed using the new help tab API, show in order of definition
$args['help_tabs'][] = array(
							'id' => 'nhp-opts-1',
							'title' => __('Support', MTS_THEME_TEXTDOMAIN ),
							'content' => __('<p>If you are facing any problem with our theme or theme option panel, head over to our <a href="http://community.mythemeshop.com/">Support Forums.</a></p>', MTS_THEME_TEXTDOMAIN )
							);
$args['help_tabs'][] = array(
							'id' => 'nhp-opts-2',
							'title' => __('Earn Money', MTS_THEME_TEXTDOMAIN ),
							'content' => __('<p>Earn 55% commision on every sale by refering your friends and readers. Join our <a href="http://mythemeshop.com/affiliate-program/">Affiliate Program</a>.</p>', MTS_THEME_TEXTDOMAIN )
							);

//Set the Help Sidebar for the options page - no sidebar by default
//$args['help_sidebar'] = __('<p>This is the sidebar content, HTML is allowed.</p>', MTS_THEME_TEXTDOMAIN );

$mts_patterns = array(
	'nobg' => array('img' => NHP_OPTIONS_URL.'img/patterns/nobg.png'),
	'pattern0' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern0.png'),
	'pattern1' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern1.png'),
	'pattern2' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern2.png'),
	'pattern3' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern3.png'),
	'pattern4' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern4.png'),
	'pattern5' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern5.png'),
	'pattern6' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern6.png'),
	'pattern7' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern7.png'),
	'pattern8' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern8.png'),
	'pattern9' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern9.png'),
	'pattern10' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern10.png'),
	'pattern11' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern11.png'),
	'pattern12' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern12.png'),
	'pattern13' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern13.png'),
	'pattern14' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern14.png'),
	'pattern15' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern15.png'),
	'pattern16' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern16.png'),
	'pattern17' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern17.png'),
	'pattern18' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern18.png'),
	'pattern19' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern19.png'),
	'pattern20' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern20.png'),
	'pattern21' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern21.png'),
	'pattern22' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern22.png'),
	'pattern23' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern23.png'),
	'pattern24' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern24.png'),
	'pattern25' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern25.png'),
	'pattern26' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern26.png'),
	'pattern27' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern27.png'),
	'pattern28' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern28.png'),
	'pattern29' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern29.png'),
	'pattern30' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern30.png'),
	'pattern31' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern31.png'),
	'pattern32' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern32.png'),
	'pattern33' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern33.png'),
	'pattern34' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern34.png'),
	'pattern35' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern35.png'),
	'pattern36' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern36.png'),
	'pattern37' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern37.png'),
	'hbg' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg.png'),
	'hbg2' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg2.png'),
	'hbg3' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg3.png'),
	'hbg4' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg4.png'),
	'hbg5' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg5.png'),
	'hbg6' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg6.png'),
	'hbg7' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg7.png'),
	'hbg8' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg8.png'),
	'hbg9' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg9.png'),
	'hbg10' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg10.png'),
	'hbg11' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg11.png'),
	'hbg12' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg12.png'),
	'hbg13' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg13.png'),
	'hbg14' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg14.png'),
	'hbg15' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg15.png'),
	'hbg16' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg16.png'),
	'hbg17' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg17.png'),
	'hbg18' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg18.png'),
	'hbg19' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg19.png'),
	'hbg20' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg20.png'),
	'hbg21' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg21.png'),
	'hbg22' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg22.png'),
	'hbg23' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg23.png'),
	'hbg24' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg24.png'),
	'hbg25' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg25.png')
);

$product_hover_effects = array(
	'default'=> __('Shop', MTS_THEME_TEXTDOMAIN ),
	'featured'=> __('Featured', MTS_THEME_TEXTDOMAIN ),
	'imgswitch' => __('Image switch', MTS_THEME_TEXTDOMAIN ),
	'zoom'=> __('Image zoom', MTS_THEME_TEXTDOMAIN ),
	'slideinbottom'=> __('Slide in buttons from bottom', MTS_THEME_TEXTDOMAIN ),
	'buttonscenter'=> __('Buttons in center', MTS_THEME_TEXTDOMAIN ),
	'apollo'=> __('Buttons in bottom right angle', MTS_THEME_TEXTDOMAIN ),
	'lexi'=> __('Buttons in bottom right circle', MTS_THEME_TEXTDOMAIN ),
);

$sections = array();

$sections[] = array(
				'icon' => 'fa fa-cogs',
				'title' => __('General Settings', MTS_THEME_TEXTDOMAIN ),
				'desc' => __('<p class="description">This tab contains common setting options which will be applied to the whole theme.</p>', MTS_THEME_TEXTDOMAIN ),
				'fields' => array(
					array(
						'id' => 'mts_logo',
						'type' => 'upload',
						'title' => __('Logo Image', MTS_THEME_TEXTDOMAIN ),
						'sub_desc' => __('Upload your logo using the Upload Button or insert image URL.', MTS_THEME_TEXTDOMAIN )
						),
					array(
						'id' => 'mts_favicon',
						'type' => 'upload',
						'title' => __('Favicon', MTS_THEME_TEXTDOMAIN ),
						'sub_desc' => __('Upload a <strong>32 x 32 px</strong> image that will represent your website\'s favicon.', MTS_THEME_TEXTDOMAIN )
						),
					array(
						'id' => 'mts_touch_icon',
						'type' => 'upload',
						'title' => __('Touch icon', MTS_THEME_TEXTDOMAIN ),
						'sub_desc' => __('Upload a <strong>152 x 152 px</strong> image that will represent your website\'s touch icon for iOS 2.0+ and Android 2.1+ devices.', MTS_THEME_TEXTDOMAIN )
						),
					array(
						'id' => 'mts_metro_icon',
						'type' => 'upload',
						'title' => __('Metro icon', MTS_THEME_TEXTDOMAIN ),
						'sub_desc' => __('Upload a <strong>144 x 144 px</strong> image that will represent your website\'s IE 10 Metro tile icon.', MTS_THEME_TEXTDOMAIN )
						),
					array(
						'id' => 'mts_twitter_username',
						'type' => 'text',
						'title' => __('Twitter Username', MTS_THEME_TEXTDOMAIN ),
						'sub_desc' => __('Enter your Username here.', MTS_THEME_TEXTDOMAIN ),
						'class' => 'medium-text',
						),
					array(
						'id' => 'mts_feedburner',
						'type' => 'text',
						'title' => __('FeedBurner URL', MTS_THEME_TEXTDOMAIN ),
						'sub_desc' => __('Enter your FeedBurner\'s URL here, ex: <strong>http://feeds.feedburner.com/mythemeshop</strong> and your main feed (http://example.com/feed) will get redirected to the FeedBurner ID entered here.)', MTS_THEME_TEXTDOMAIN ),
						'validate' => 'url',
						'class' => 'medium-text',
						),
					array(
						'id' => 'mts_header_code',
						'type' => 'textarea',
						'title' => __('Header Code', MTS_THEME_TEXTDOMAIN ),
						'sub_desc' => __('Enter the code which you need to place <strong>before closing </head> tag</strong>. (ex: Google Webmaster Tools verification, Bing Webmaster Center, BuySellAds Script, Alexa verification etc.)', MTS_THEME_TEXTDOMAIN )
						),
					array(
						'id' => 'mts_analytics_code',
						'type' => 'textarea',
						'title' => __('Footer Code', MTS_THEME_TEXTDOMAIN ),
						'sub_desc' => __('Enter the codes which you need to place in your footer. <strong>(ex: Google Analytics, Clicky, STATCOUNTER, Woopra, Histats, etc.)</strong>.', MTS_THEME_TEXTDOMAIN )
						),
					array(
						'id' => 'mts_copyrights',
						'type' => 'textarea',
						'title' => __('Copyrights Text', MTS_THEME_TEXTDOMAIN ),
						'sub_desc' => __('You can change or remove our link from footer and use your own custom text. (You can also use your affiliate link to <strong>earn 55% of sales</strong>. Ex: <a href="https://mythemeshop.com/go/aff/aff" target="_blank">https://mythemeshop.com/?ref=username</a>)', MTS_THEME_TEXTDOMAIN ),
						'std' => 'Theme by <a href="http://mythemeshop.com/">MyThemeShop</a>'
						),
					array(
						'id' => 'mts_responsive',
						'type' => 'button_set',
						'title' => __('Responsiveness', MTS_THEME_TEXTDOMAIN ),
						'options' => array( '0' => __( 'Off', MTS_THEME_TEXTDOMAIN ), '1' => __( 'On', MTS_THEME_TEXTDOMAIN ) ),
						'sub_desc' => __('MyThemeShop themes are responsive, which means they adapt to tablet and mobile devices, ensuring that your content is always displayed beautifully no matter what device visitors are using. Enable or disable responsiveness using this option.', MTS_THEME_TEXTDOMAIN ),
						'std' => '1'
						),
					array(
						'id' => 'mts_rtl',
						'type' => 'button_set',
						'title' => __('Right To Left Language Support', MTS_THEME_TEXTDOMAIN ),
						'options' => array( '0' => __( 'Off', MTS_THEME_TEXTDOMAIN ), '1' => __( 'On', MTS_THEME_TEXTDOMAIN ) ),
						'sub_desc' => __('Enable this option for right-to-left sites.', MTS_THEME_TEXTDOMAIN ),
						'std' => '0',
						'reset_at_version' => '1.5.4'
						),
					)
				);
$sections[] = array(
				'icon' => 'fa fa-bolt',
				'title' => __('Performance', MTS_THEME_TEXTDOMAIN ),
				'desc' => '<p class="description">' . __('This tab contains performance-related options which can help speed up your website.', MTS_THEME_TEXTDOMAIN ) . '</p>',
				'fields' => array(
					array(
						'id' => 'mts_prefetching',
						'type' => 'button_set',
						'title' => __('Prefetching', MTS_THEME_TEXTDOMAIN ),
						'options' => array( '0' => __( 'Off', MTS_THEME_TEXTDOMAIN ), '1' => __( 'On', MTS_THEME_TEXTDOMAIN ) ),
						'sub_desc' => __('Enable or disable prefetching. If user is on homepage, then single page will load faster and if user is on single page, homepage will load faster in modern browsers.', MTS_THEME_TEXTDOMAIN ),
						'std' => '0'
						),
					array(
						'id' => 'mts_lazy_load',
						'type' => 'button_set_hide_below',
						'title' => __('Lazy Load', MTS_THEME_TEXTDOMAIN ),
						'options' => array( '0' => __( 'Off', MTS_THEME_TEXTDOMAIN ), '1' => __( 'On', MTS_THEME_TEXTDOMAIN ) ),
						'sub_desc' => __('Delay loading of images outside of viewport, until user scrolls to them.', MTS_THEME_TEXTDOMAIN ),
						'std' => '0',
						'args' => array('hide' => 2)
						),
					array(
						'id' => 'mts_lazy_load_thumbs',
						'type' => 'button_set',
						'title' => __('Lazy load fatured images', MTS_THEME_TEXTDOMAIN ),
						'options' => array( '0' => __( 'Off', MTS_THEME_TEXTDOMAIN ), '1' => __( 'On', MTS_THEME_TEXTDOMAIN ) ),
						'sub_desc' => __('Enable or disable Lazy load of featured images across site.', MTS_THEME_TEXTDOMAIN ),
						'std' => '0'
						),
					array(
						'id' => 'mts_lazy_load_content',
						'type' => 'button_set',
						'title' => __('Lazy load post content images', MTS_THEME_TEXTDOMAIN ),
						'options' => array( '0' => __( 'Off', MTS_THEME_TEXTDOMAIN ), '1' => __( 'On', MTS_THEME_TEXTDOMAIN ) ),
						'sub_desc' => __('Enable or disable Lazy load of images inside post/page content.', MTS_THEME_TEXTDOMAIN ),
						'std' => '0'
						),
					array(
						'id' => 'mts_async_js',
						'type' => 'button_set',
						'title' => __('Async JavaScript', MTS_THEME_TEXTDOMAIN ),
						'options' => array( '0' => __( 'Off', MTS_THEME_TEXTDOMAIN ), '1' => __( 'On', MTS_THEME_TEXTDOMAIN ) ),
						'sub_desc' => sprintf( __('Add %s attribute to script tags to improve page download speed.', MTS_THEME_TEXTDOMAIN ), '<code>async</code>' ),
						'std' => '1',
						'reset_at_version' => '1.1.1'
						),
					array(
						'id' => 'mts_remove_ver_params',
						'type' => 'button_set',
						'title' => __('Remove ver parameters', MTS_THEME_TEXTDOMAIN ),
						'options' => array( '0' => __( 'Off', MTS_THEME_TEXTDOMAIN ), '1' => __( 'On', MTS_THEME_TEXTDOMAIN ) ),
						'sub_desc' => sprintf( __('Remove %s parameter from CSS and JS file calls. It may improve speed in some browsers which do not cache files having the parameter.', MTS_THEME_TEXTDOMAIN ), '<code>ver</code>' ),
						'std' => '1',
						'reset_at_version' => '1.1.1'
						),
					array(
						'id' => 'mts_optimize_wc',
						'type' => 'button_set',
						'title' => __('Optimize WooCommerce scripts', MTS_THEME_TEXTDOMAIN ),
						'options' => array( '0' => __( 'Off', MTS_THEME_TEXTDOMAIN ), '1' => __( 'On', MTS_THEME_TEXTDOMAIN ) ),
						'sub_desc' => __('Load WooCommerce scripts and styles only on WooCommerce pages (WooCommerce plugin must be enabled).', MTS_THEME_TEXTDOMAIN ),
						'std' => '0',
						'reset_at_version' => '1.1.1'
						),
					'cache_message' => array(
						'id' => 'mts_cache_message',
						'type' => 'info',
						'title' => __('Use Cache', MTS_THEME_TEXTDOMAIN ),
						/*
							Translators: %1$s = popup link to W3 Total Cache, %2$s = popup link to WP Super Cache
						 */
						'desc' => sprintf(
							__('A cache plugin can increase page download speed dramatically. We recommend using %1$s or %2$s.', MTS_THEME_TEXTDOMAIN ),
							'<a href="https://community.mythemeshop.com/tutorials/article/8-make-your-website-load-faster-using-w3-total-cache-plugin/" target="_blank" title="W3 Total Cache">W3 Total Cache</a>',
							'<a href="'.admin_url( 'plugin-install.php?tab=plugin-information&plugin=wp-super-cache&TB_iframe=true&width=772&height=574' ).'" class="thickbox" title="WP Super Cache">WP Super Cache</a>'
						),
					),
				)
			);

// Hide cache message on multisite or if a chache plugin is active already
if ( is_multisite() || strstr( join( ';', get_option( 'active_plugins' ) ), 'cache' ) ) {
	unset( $sections[1]['fields']['cache_message'] );
}
$sections[] = array(
				'icon' => 'fa fa-adjust',
				'title' => __('Styling Options', MTS_THEME_TEXTDOMAIN ),
				'desc' => __('<p class="description">Control the visual appearance of your theme, such as colors, layout and patterns, from here.</p>', MTS_THEME_TEXTDOMAIN ),
				'fields' => array(
					array(
						'id' => 'mts_color_scheme',
						'type' => 'color',
						'title' => __('First Color', MTS_THEME_TEXTDOMAIN),
						'sub_desc' => __('This color will be used for sections with blue colors.', MTS_THEME_TEXTDOMAIN),
						'std' => '#4b5f6b'
					),
					array(
						'id' => 'mts_color_scheme2',
						'type' => 'color',
						'title' => __('Second Color', MTS_THEME_TEXTDOMAIN),
						'sub_desc' => __('This color will be used for sections with green colors.', MTS_THEME_TEXTDOMAIN),
						'std' => '#26bfa1'
					),
					array(
						'id' => 'mts_color_scheme3',
						'type' => 'color',
						'title' => __('Third Color', MTS_THEME_TEXTDOMAIN),
						'sub_desc' => __('This color will be used for sections with orange colors.', MTS_THEME_TEXTDOMAIN),
						'std' => '#edb88b'
					),
					array(
						'id' => 'mts_layout',
						'type' => 'radio_img',
						'title' => __('Layout Style', MTS_THEME_TEXTDOMAIN ),
						'sub_desc' => __('Choose the <strong>default sidebar position</strong> for your site. The position of the sidebar for individual posts can be set in the post editor.', MTS_THEME_TEXTDOMAIN ),
						'options' => array(
							'cslayout' => array('img' => NHP_OPTIONS_URL.'img/layouts/cs.png'),
							'sclayout' => array('img' => NHP_OPTIONS_URL.'img/layouts/sc.png')
						),
						'std' => 'cslayout'
						),
					array(
						'id' => 'mts_payment_section',
						'type' => 'button_set_hide_below',
						'title' => __('Before Footer Section', MTS_THEME_TEXTDOMAIN ),
						'sub_desc' => __('Enable or disable section above footer area with this option.', MTS_THEME_TEXTDOMAIN ),
						'options' => array( '0' => __( 'Off', MTS_THEME_TEXTDOMAIN ), '1' => __( 'On', MTS_THEME_TEXTDOMAIN ) ),
						'std' => '0',
						),
					array(
                        'id'        => 'mts_payment',
                        'type'      => 'group',
                        'title'     => __('Before Footer Section Items', MTS_THEME_TEXTDOMAIN),
                        'sub_desc'  => __('Add before footer section items here.', MTS_THEME_TEXTDOMAIN),
                        'groupname' => __('Grid Item', MTS_THEME_TEXTDOMAIN), // Group name
                        'subfields' =>
                            array(
                                array(
                                    'id' => 'title',
            						'type' => 'text',
            						'title' => __('Title', MTS_THEME_TEXTDOMAIN),
                                    ),
                                array(
									'id' => 'icon',
									'type' => 'icon_select',
			                        'allow_empty' => true,
									'title' => __('Icon', MTS_THEME_TEXTDOMAIN ),
								),
                                array(
                                    'id' => 'description',
                                    'type' => 'textarea',
                                    'title' => __('Description', MTS_THEME_TEXTDOMAIN),
                                ),
                                array(
                                    'id' => 'url',
                                    'type' => 'text',
                                    'title' => __('Link', MTS_THEME_TEXTDOMAIN),
                                    'std' => '#'
                                )
                            ),
							'std' => array(
            					'1' => array(
            						'group_title' => '',
            						'group_sort' => '1',
            					)
            				)
                        ),
					array(
						'id' => 'mts_top_footer',
						'type' => 'button_set_hide_below',
						'title' => __('Top Footer Area', MTS_THEME_TEXTDOMAIN ),
						'sub_desc' => __('Enable or disable top footer area with this option.', MTS_THEME_TEXTDOMAIN ),
						'options' => array( '0' => __( 'Off', MTS_THEME_TEXTDOMAIN ), '1' => __( 'On', MTS_THEME_TEXTDOMAIN ) ),
						'std' => '0',
						'args' => array('hide' => 2)
						),
                        array(
                        'id'        => 'mts_accepted_payment_method_images',
                        'type'      => 'group',
                        'title'     => __('Footer Accepted Payment Methods', MTS_THEME_TEXTDOMAIN),
                        'sub_desc'  => __('Accepted payment method images', MTS_THEME_TEXTDOMAIN),
                        'groupname' => __('Payment Method Image', MTS_THEME_TEXTDOMAIN), // Group name
                        'subfields' =>
                            array(
                                array(
                                    'id' => 'mts_payment_method_title',
            						'type' => 'text',
            						'title' => __('Payment Method', MTS_THEME_TEXTDOMAIN),
            						'sub_desc' => __('Enter the title for this payment method.', MTS_THEME_TEXTDOMAIN),
                                    ),
                                array(
									'id' => 'mts_payment_method_image',
									'type' => 'select_img',
									'title' => __('Payment method image', MTS_THEME_TEXTDOMAIN),
									'sub_desc' => __('Select image for this payment method.', MTS_THEME_TEXTDOMAIN),
									'options' => array(
											'2co'        => array( 'name'=> 'Two Check Out', 'img' => NHP_OPTIONS_URL.'img/credit-cards/2co.png' ),
											'amex'       => array( 'name'=> 'American Express', 'img' => NHP_OPTIONS_URL.'img/credit-cards/amex.png' ),
											'cirrus'       => array( 'name'=> 'Cirrus', 'img' => NHP_OPTIONS_URL.'img/credit-cards/cirrus.png' ),
											'delta'       => array( 'name'=> 'Delta', 'img' => NHP_OPTIONS_URL.'img/credit-cards/delta.png' ),
											//'diners'       => array( 'name'=> 'Diners', 'img' => NHP_OPTIONS_URL.'img/credit-cards/diners.png' ),
											'discover'   => array( 'name'=> 'Discover', 'img' => NHP_OPTIONS_URL.'img/credit-cards/discover.png' ),
											//'jcb' => array( 'name'=> 'JCB', 'img' => NHP_OPTIONS_URL.'img/credit-cards/jcb.png' ),
											//'maestro'   => array( 'name'=> 'Maestro', 'img' => NHP_OPTIONS_URL.'img/credit-cards/maestro.png' ),
											'mastercard' => array( 'name'=> 'MasterCard', 'img' => NHP_OPTIONS_URL.'img/credit-cards/mastercard.png' ),
											'moneybookers' => array( 'name'=> 'Money Bookers', 'img' => NHP_OPTIONS_URL.'img/credit-cards/moneybookers.png' ),
											'paypal'     => array( 'name'=> 'PayPal', 'img' => NHP_OPTIONS_URL.'img/credit-cards/paypal.png' ),
											'visa'       => array( 'name'=> 'VISA', 'img' => NHP_OPTIONS_URL.'img/credit-cards/visa.png' ),
											'switch'     => array( 'name'=> 'Switch', 'img' => NHP_OPTIONS_URL.'img/credit-cards/switch.png' ),
											//'visaelectron'   => array( 'name'=> 'Visa Electron', 'img' => NHP_OPTIONS_URL.'img/credit-cards/visaelectron.png' ),
											//'westernunion'    => array( 'name'=> 'Western Union', 'img' => NHP_OPTIONS_URL.'img/credit-cards/westernunion.png' ),
										),
									'std' => 'visa'
									),
                                array(
                                    'id' => 'mts_payment_method_custom_image',
            						'type' => 'upload',
            						'title' => __('Custom Image (Recommended size: 62x40px)', MTS_THEME_TEXTDOMAIN),
            						'sub_desc' => __('Upload custom image for this payment method', MTS_THEME_TEXTDOMAIN),
                                    'return' => 'id'
            						),
                            ),
							'std' => array(
            					'1' => array(
            						'group_title' => '',
            						'group_sort' => '1',
            						'mts_payment_method_title' => 'PayPal',
            						'mts_payment_method_image' => 'paypal',
            						'mts_payment_method_custom_image' => ''
            					)
            				)
                        ),
					array(
                        'id'        => 'mts_footer_social_icons',
                        'type'      => 'group',
                        'title'     => __('Footer Social Icons', MTS_THEME_TEXTDOMAIN),
                        'sub_desc'  => __('Social Icons in footer area', MTS_THEME_TEXTDOMAIN),
                        'groupname' => __('Social Icon', MTS_THEME_TEXTDOMAIN), // Group name
                        'subfields' =>
                            array(
                                array(
                                    'id' => 'mts_footer_social_icon_title',
            						'type' => 'text',
            						'title' => __('Social Icon Title', MTS_THEME_TEXTDOMAIN),
            						'sub_desc' => __('Enter the title for this icon.', MTS_THEME_TEXTDOMAIN),
            					),
                                array(
									'id' => 'mts_footer_social_icon',
									'type' => 'icon_select',
			                        'subset' => 'Brand Icons',
			                        'allow_empty' => false,
									'title' => __('Social Icon', MTS_THEME_TEXTDOMAIN ),
									'sub_desc' => __('Choose social icon.', MTS_THEME_TEXTDOMAIN ),
								),
								array('id' => 'mts_footer_social_icon_url',
            						'type' => 'text',
            						'title' => __('Link', MTS_THEME_TEXTDOMAIN),
            						'sub_desc' => __('Insert social network link', MTS_THEME_TEXTDOMAIN),
                                    'std' => '#'
                                   ),
                                array(
									'id' => 'mts_footer_social_icon_color',
									'type' => 'color',
									'title' => __('Icon Color', MTS_THEME_TEXTDOMAIN ),
									'sub_desc' => __('Icon Color.', MTS_THEME_TEXTDOMAIN ),
									'std' => '#ffffff'
								),
								array(
									'id' => 'mts_footer_social_icon_bg_color',
									'type' => 'color',
									'title' => __('Icon Background Color', MTS_THEME_TEXTDOMAIN ),
									'sub_desc' => __('Icon Background Color.', MTS_THEME_TEXTDOMAIN ),
									'std' => '#26bfa1'
								),
                            ),
							'std' => array(
            					'1' => array(
            						'group_title' => '',
            						'group_sort' => '1',
            						'mts_footer_social_icon_title' => '',
            						'mts_footer_social_icon' => '',
            						'mts_footer_social_icon_url' => '#',
            						'mts_footer_social_icon_color' => '#ffffff',
            						'mts_footer_social_icon_bg_color' => '#26bfa1'
            					)
            				)
                        ),
                    array(
						'id' => 'mts_first_footer',
						'type' => 'button_set_hide_below',
						'title' => __('Footer Widgets', MTS_THEME_TEXTDOMAIN ),
						'sub_desc' => __('Enable or disable footer widgets with this option.', MTS_THEME_TEXTDOMAIN ),
						'options' => array( '0' => __( 'Off', MTS_THEME_TEXTDOMAIN ), '1' => __( 'On', MTS_THEME_TEXTDOMAIN ) ),
						'std' => '0'
						),
                        array(
						'id' => 'mts_first_footer_num',
						'type' => 'button_set',
                        'class' => 'green',
						'title' => __('Footer Widget Columns', MTS_THEME_TEXTDOMAIN ),
						'sub_desc' => __('Choose the number of widget areas in the <strong>first footer</strong>', MTS_THEME_TEXTDOMAIN ),
						'options' => array(
										'3' => __('3 Widgets', MTS_THEME_TEXTDOMAIN),
										'4' => __('4 Widgets', MTS_THEME_TEXTDOMAIN),
										'5' => __('5 Widgets', MTS_THEME_TEXTDOMAIN)
											),
						'std' => '3'
						),
					array(
						'id' => 'mts_background',
						'type' => 'background',
						'title' => __('Site Background', MTS_THEME_TEXTDOMAIN ),
						'sub_desc' => __('Set your site background.', MTS_THEME_TEXTDOMAIN ),
						'options' => array(
							'color'         => '',            // false to disable, not needed otherwise
							'image_pattern' => $mts_patterns, // false to disable, array of options otherwise ( required !!! )
							'image_upload'  => '',            // false to disable, not needed otherwise
							'repeat'        => array(),       // false to disable, array of options to override default ( optional )
							'attachment'    => array(),       // false to disable, array of options to override default ( optional )
							'position'      => array(),       // false to disable, array of options to override default ( optional )
							'size'          => array(),       // false to disable, array of options to override default ( optional )
							'gradient'      => '',            // false to disable, not needed otherwise
							'parallax'      => false,       // false to disable, array of options to override default ( optional )
						),
						'std' => array(
							'color'         => '#fafcfd',
							'use'           => 'pattern',
							'image_pattern' => 'nobg',
							'image_upload'  => '',
							'repeat'        => 'repeat',
							'attachment'    => 'scroll',
							'position'      => 'left top',
							'size'          => 'cover',
							'gradient'      => array('from' => '#ffffff', 'to' => '#000000', 'direction' => 'horizontal' ),
							//'parallax'      => '0',
						)
					),
					array(
						'id' => 'mts_footer_background',
						'type' => 'background',
						'title' => __('Footer Background', MTS_THEME_TEXTDOMAIN ),
						'sub_desc' => __('Set your site footer background.', MTS_THEME_TEXTDOMAIN ),
						'options' => array(
							'color'         => '',            // false to disable, not needed otherwise
							'image_pattern' => $mts_patterns, // false to disable, array of options otherwise ( required !!! )
							'image_upload'  => '',            // false to disable, not needed otherwise
							'repeat'        => array(),       // false to disable, array of options to override default ( optional )
							'attachment'    => array(),       // false to disable, array of options to override default ( optional )
							'position'      => array(),       // false to disable, array of options to override default ( optional )
							'size'          => array(),       // false to disable, array of options to override default ( optional )
							'gradient'      => '',            // false to disable, not needed otherwise
							'parallax'      => false,       // false to disable, array of options to override default ( optional )
						),
						'std' => array(
							'color'         => '#344655',
							'use'           => 'pattern',
							'image_pattern' => 'nobg',
							'image_upload'  => '',
							'repeat'        => 'repeat',
							'attachment'    => 'scroll',
							'position'      => 'left top',
							'size'          => 'cover',
							'gradient'      => array('from' => '#ffffff', 'to' => '#000000', 'direction' => 'horizontal' ),
							//'parallax'      => '0',
						)
					),
					array(
						'id' => 'mts_custom_css',
						'type' => 'textarea',
						'title' => __('Custom CSS', MTS_THEME_TEXTDOMAIN ),
						'sub_desc' => __('You can enter custom CSS code here to further customize your theme. This will override the default CSS used on your site.', MTS_THEME_TEXTDOMAIN )
						),
					array(
						'id' => 'mts_lightbox',
						'type' => 'button_set',
						'title' => __('Lightbox', MTS_THEME_TEXTDOMAIN ),
						'options' => array( '0' => __( 'Off', MTS_THEME_TEXTDOMAIN ), '1' => __( 'On', MTS_THEME_TEXTDOMAIN ) ),
						'sub_desc' => __('A lightbox is a stylized pop-up that allows your visitors to view larger versions of images without leaving the current page. You can enable or disable the lightbox here.', MTS_THEME_TEXTDOMAIN ),
						'std' => '0'
						),
					)
				);
$sections[] = array(
				'icon' => 'fa fa-credit-card',
				'title' => __('Header', MTS_THEME_TEXTDOMAIN ),
				'desc' => __('<p class="description">From here, you can control the elements of header section.</p>', MTS_THEME_TEXTDOMAIN ),
				'fields' => array(
					array(
						'id' => 'mts_header_layout',
						'type' => 'radio_img',
						'class' => 'header_radio_img',
						'title' => __('Header Style', MTS_THEME_TEXTDOMAIN ),
						'sub_desc' => __('Choose the header type for your site..', MTS_THEME_TEXTDOMAIN ),
						'options' => array(
							'1' => array('img' => NHP_OPTIONS_URL.'img/layouts/header-1.png'),
							'2' => array('img' => NHP_OPTIONS_URL.'img/layouts/header-2.png'),
							'3' => array('img' => NHP_OPTIONS_URL.'img/layouts/header-3.png'),
							'4' => array('img' => NHP_OPTIONS_URL.'img/layouts/header-4.png'),
							'5' => array('img' => NHP_OPTIONS_URL.'img/layouts/header-5.png'),
							'6' => array('img' => NHP_OPTIONS_URL.'img/layouts/header-6.png'),
						),
						'std' => '1'
						),
					array(
						'id' => 'mts_sticky_nav',
						'type' => 'button_set',
						'class' => 'header_radio_img_val-1-2-3-4-5-6',
						'title' => __('Floating Navigation Menu', MTS_THEME_TEXTDOMAIN ),
						'options' => array( '0' => __( 'Off', MTS_THEME_TEXTDOMAIN ), '1' => __( 'On', MTS_THEME_TEXTDOMAIN ) ),
						'sub_desc' => __('Use this button to enable <strong>Floating Navigation Menu</strong>.', MTS_THEME_TEXTDOMAIN ),
						'std' => '0'
						),
                    array(
						'id' => 'mts_show_primary_nav',
						'type' => 'button_set',
						'class' => 'header_radio_img_val-1-2-3-4-5-6',
						'title' => __('Show Primary Menu', MTS_THEME_TEXTDOMAIN ),
						'options' => array( '0' => __( 'Off', MTS_THEME_TEXTDOMAIN ), '1' => __( 'On', MTS_THEME_TEXTDOMAIN ) ),
						'sub_desc' => __('Use this button to enable <strong>Primary Navigation Menu</strong>.', MTS_THEME_TEXTDOMAIN ),
						'std' => '1'
						),
                    array(
						'id' => 'mts_primary_nav_bgcolor',
						'type' => 'color',
						'class' => 'header_radio_img_val-3-4',
						'title' => __('Primary Menu Background Color', MTS_THEME_TEXTDOMAIN ),
						'std' => '#f4f7f9'
						),
                    array(
						'id' => 'mts_show_secondary_nav',
						'type' => 'button_set',
						'class' => 'header_radio_img_val-1-2-3-4-5-6',
						'title' => __('Show secondary menu', MTS_THEME_TEXTDOMAIN ),
						'options' => array( '0' => __( 'Off', MTS_THEME_TEXTDOMAIN ), '1' => __( 'On', MTS_THEME_TEXTDOMAIN ) ),
						'sub_desc' => __('Use this button to enable <strong>Secondary Navigation Menu</strong>.', MTS_THEME_TEXTDOMAIN ),
						'std' => '1'
						),
					array(
						'id' => 'mts_header_section2',
						'type' => 'button_set',
						'class' => 'header_radio_img_val-1-2-3-4-5-6',
						'title' => __('Show Logo', MTS_THEME_TEXTDOMAIN ),
						'options' => array( '0' => __( 'Off', MTS_THEME_TEXTDOMAIN ), '1' => __( 'On', MTS_THEME_TEXTDOMAIN ) ),
						'sub_desc' => __('Use this button to Show or Hide <strong>Logo</strong> completely.', MTS_THEME_TEXTDOMAIN ),
						'std' => '1'
						),
					array(
						'id' => 'mts_header_bottom_ad_nav_1_title',
						'type' => 'text',
						'class' => 'header_radio_img_val-6',
						'title' => __('Header bottom first block text heading', MTS_THEME_TEXTDOMAIN ),
						'std' => ''
						),
					array(
						'id' => 'mts_header_bottom_ad_nav_1_desc',
						'type' => 'text',
						'class' => 'header_radio_img_val-6',
						'title' => __('Header bottom first block text subheading', MTS_THEME_TEXTDOMAIN ),
						'std' => ''
						),
					array(
						'id' => 'mts_header_bottom_ad_nav_2_title',
						'type' => 'text',
						'class' => 'header_radio_img_val-6',
						'title' => __('Header bottom second block text heading', MTS_THEME_TEXTDOMAIN ),
						'std' => ''
						),
					array(
						'id' => 'mts_header_bottom_ad_nav_2_desc',
						'type' => 'text',
						'class' => 'header_radio_img_val-6',
						'title' => __('Header bottom second block text subheading', MTS_THEME_TEXTDOMAIN ),
						'std' => ''
						),
					array(
						'id' => 'mts_header_bottom_ad_nav_3_title',
						'type' => 'text',
						'class' => 'header_radio_img_val-6',
						'title' => __('Header bottom third block text heading', MTS_THEME_TEXTDOMAIN ),
						'std' => ''
						),
					array(
						'id' => 'mts_header_bottom_ad_nav_3_desc',
						'type' => 'text',
						'class' => 'header_radio_img_val-6',
						'title' => __('Header bottom third block text subheading', MTS_THEME_TEXTDOMAIN ),
						'std' => ''
						),
					array(
						'id' => 'mts_header_background',
						'type' => 'background',
						'title' => __('Header Background', MTS_THEME_TEXTDOMAIN ),
						'sub_desc' => __('Set your site header background.', MTS_THEME_TEXTDOMAIN ),
						'options' => array(
							'color'         => '',            // false to disable, not needed otherwise
							'image_pattern' => $mts_patterns, // false to disable, array of options otherwise ( required !!! )
							'image_upload'  => '',            // false to disable, not needed otherwise
							'repeat'        => array(),       // false to disable, array of options to override default ( optional )
							'attachment'    => array(),       // false to disable, array of options to override default ( optional )
							'position'      => array(),       // false to disable, array of options to override default ( optional )
							'size'          => array(),       // false to disable, array of options to override default ( optional )
							'gradient'      => '',            // false to disable, not needed otherwise
							'parallax'      => false,       // false to disable, array of options to override default ( optional )
						),
						'std' => array(
							'color'         => '#fafcfd',
							'use'           => 'pattern',
							'image_pattern' => 'nobg',
							'image_upload'  => '',
							'repeat'        => 'repeat',
							'attachment'    => 'scroll',
							'position'      => 'left top',
							'size'          => 'cover',
							'gradient'      => array('from' => '#ffffff', 'to' => '#000000', 'direction' => 'horizontal' ),
							//'parallax'      => '0',
						)
					),
					)
				);
$sections[] = array(
				'icon' => 'fa fa-home',
				'title' => __('Homepage', MTS_THEME_TEXTDOMAIN ),
				'desc' => __('<p class="description">From here, you can control the elements of the homepage.</p>', MTS_THEME_TEXTDOMAIN ),
				'fields' => array(
					array(
						'id' => 'mts_featured_slider',
						'type' => 'button_set_hide_below',
						'title' => __('Homepage Slider', MTS_THEME_TEXTDOMAIN ),
						'options' => array( '0' => __( 'Off', MTS_THEME_TEXTDOMAIN ), '1' => __( 'On', MTS_THEME_TEXTDOMAIN ) ),
						'sub_desc' => __('<strong>Enable or Disable</strong> homepage slider with this button.', MTS_THEME_TEXTDOMAIN ),
						'std' => '0',
					),
					array(
                        'id'        => 'mts_custom_slider',
                        'type'      => 'group',
                        'title'     => __('Homepage Slides', MTS_THEME_TEXTDOMAIN),
                        'sub_desc'  => '',
                        'groupname' => __('Slide', MTS_THEME_TEXTDOMAIN), // Group name
                        'subfields' =>
                            array(
                                array(
                                    'id' => 'mts_custom_slider_title',
            						'type' => 'text',
            						'title' => __('Title', MTS_THEME_TEXTDOMAIN),
            						'sub_desc' => __('Title of the slide', MTS_THEME_TEXTDOMAIN),
                                ),
                                array(
                                    'id' => 'mts_custom_slider_subtitle',
            						'type' => 'text',
            						'title' => __('Subitle', MTS_THEME_TEXTDOMAIN),
            						'sub_desc' => __('Subtitle of the slide', MTS_THEME_TEXTDOMAIN),
                                ),
                                array('id' => 'mts_custom_slider_heading',
            						'type' => 'text',
            						'title' => __('Heading', MTS_THEME_TEXTDOMAIN),
            						'sub_desc' => __('Heading of the slide', MTS_THEME_TEXTDOMAIN),
                                ),
                                array('id' => 'mts_custom_slider_subheading',
            						'type' => 'text',
            						'title' => __('Subheading', MTS_THEME_TEXTDOMAIN),
            						'sub_desc' => __('Subheading of the slide', MTS_THEME_TEXTDOMAIN),
                                ),
                                array('id' => 'mts_custom_slider_button_text',
            						'type' => 'text',
            						'title' => __('Button Text', MTS_THEME_TEXTDOMAIN),
            						'sub_desc' => __('Button text of the slide', MTS_THEME_TEXTDOMAIN),
                                ),
                                array(
                                    'id' => 'mts_custom_slider_image',
            						'type' => 'upload',
            						'title' => __('Image', MTS_THEME_TEXTDOMAIN),
            						'sub_desc' => __('Upload or select an image for this slide', MTS_THEME_TEXTDOMAIN),
                                    'return' => 'id'
            					),
                                array('id' => 'mts_custom_slider_link',
            						'type' => 'text',
            						'title' => __('Link', MTS_THEME_TEXTDOMAIN),
            						'sub_desc' => __('Insert a link URL for the slide', MTS_THEME_TEXTDOMAIN),
                                    'std' => '#'
                                ),
                        ),
                    ),
					array(
		                'id'       => 'mts_home_layout',
		                'type'     => 'layout2',
		                'title'    => __('Home Layout', MTS_THEME_TEXTDOMAIN),
		                'options'  => array(
		                    'enabled'  => array(
		                        'banners'   => array(
		                            'label'     => __('Banners',MTS_THEME_TEXTDOMAIN),
		                            'subfields' => array(

		                            )
		                        ),
		                        'welcome'   => array(
		                            'label'     => __('Welcome',MTS_THEME_TEXTDOMAIN),
		                            'subfields' => array(
		                                array(
		                                    'id' => 'welcome_heading',
		                                    'type' => 'text',
		                                    'title' => __('Welcome Section Heading', MTS_THEME_TEXTDOMAIN),
		                                    'std'=> __('Welcome To Ecommerce', MTS_THEME_TEXTDOMAIN),
		                                ),
		                                array(
		                                    'id' => 'welcome_subheading',
		                                    'type' => 'text',
		                                    'title' => __('Welcome Section Subheading', MTS_THEME_TEXTDOMAIN),
		                                    'std'=> __('Wordpress Theme by MythemeShop', MTS_THEME_TEXTDOMAIN),
		                                ),
		                                array(
		                                    'id'        => 'welcome_features',
		                                    'type'      => 'group',
		                                    'title'     => __('Features', MTS_THEME_TEXTDOMAIN),
		                                    'groupname' => __('Feature', MTS_THEME_TEXTDOMAIN), // Group name
		                                    'subfields' =>  array(
		                                        array(
		                                            'id' => 'title',
		                                            'type' => 'text',
		                                            'title' => __('Title', MTS_THEME_TEXTDOMAIN),
		                                        ),
		                                        array(
													'id' => 'icon',
													'type' => 'icon_select',
							                        'allow_empty' => true,
													'title' => __('Icon', MTS_THEME_TEXTDOMAIN ),
												),
		                                        array(
		                                            'id' => 'description',
		                                            'type' => 'textarea',
		                                            'title' => __('Description', MTS_THEME_TEXTDOMAIN),
		                                        ),
		                                        array(
		                                            'id' => 'url',
		                                            'type' => 'text',
		                                            'title' => __('Link', MTS_THEME_TEXTDOMAIN),
		                                            'std' => '#'
		                                        )
		                                    ),
										)
		                            )
		                        ),
								'banner'   => array(
		                            'label'     => __('Thin Banner',MTS_THEME_TEXTDOMAIN),
		                            'subfields' => array(
		                                array(
                                            'id' => 'banner_light_text',
                                            'type' => 'text',
                                            'title' => __('Light Text', MTS_THEME_TEXTDOMAIN),
                                        ),
		                                array(
                                            'id' => 'banner_dark_text',
                                            'type' => 'text',
                                            'title' => __('Dark Text', MTS_THEME_TEXTDOMAIN),
                                        ),
		                                array(
                                            'id' => 'banner_button_text',
                                            'type' => 'text',
                                            'title' => __('Button Text', MTS_THEME_TEXTDOMAIN),
                                        ),
		                                array(
                                            'id' => 'banner_button_link',
                                            'type' => 'text',
                                            'title' => __('Button URL', MTS_THEME_TEXTDOMAIN),
                                            'std' => '#'
                                        ),
		                            )
		                        ),
								'product_cats'   => array(
		                            'label'     => __('Product Categories Slider', MTS_THEME_TEXTDOMAIN),
		                            'subfields' => array(
		                                array(
		                                    'id' => 'browse_heading',
		                                    'type' => 'text',
		                                    'title' => __('Product Categories Heading', MTS_THEME_TEXTDOMAIN),
		                                    'std'=> __('Browse our Categories', MTS_THEME_TEXTDOMAIN),
		                                ),
		                            )
		                        ),
		                        'product_grid'   => array(
		                            'label'     => __('Products Grid', MTS_THEME_TEXTDOMAIN),
		                            'subfields' => array(
		                                array(
		                                    'id' => 'product_grid_heading',
		                                    'type' => 'text',
		                                    'title' => __('Products Grid Heading', MTS_THEME_TEXTDOMAIN),
		                                    'std'=> '',
		                                ),
		                                array(
											'id' => 'product_grid_num',
											'type' => 'text',
											'title' => __('No. of Products', MTS_THEME_TEXTDOMAIN),
											'sub_desc' => __('Enter the total number of products which you want to show.', MTS_THEME_TEXTDOMAIN),
											'validate' => 'numeric',
											'std' => '6',
											'class' => 'small-text'
										),
										array(
											'id' => 'product_grid_img_num',
											'type' => 'text',
											'title' => __('No. of Images', MTS_THEME_TEXTDOMAIN),
											'sub_desc' => __('Enter the maximum number of imagees/variations which you want to show for each product.', MTS_THEME_TEXTDOMAIN),
											'validate' => 'numeric',
											'std' => '3',
											'class' => 'small-text'
										),
										array(
												'id' => 'products_grid_sortby',
												'type' => 'select',
												'title' => __('Sort Products By', MTS_THEME_TEXTDOMAIN),
												'std'=> __('Sort Products By', MTS_THEME_TEXTDOMAIN),
												'options' => array(
													'name'       => __('Name', MTS_THEME_TEXTDOMAIN),
													'popular'    => __('Popularity ( Sales )', MTS_THEME_TEXTDOMAIN),
													'rating'     => __('Average Rating', MTS_THEME_TEXTDOMAIN),
													'recent'     => __('Most Recent', MTS_THEME_TEXTDOMAIN),
													'price-asc'  => __('Price Ascending', MTS_THEME_TEXTDOMAIN),
													'price-desc' => __('Price Descending', MTS_THEME_TEXTDOMAIN),
													'random'     => __('Random', MTS_THEME_TEXTDOMAIN),
												),
												'std' => 'recent',
										),
		                            )
		                        ),
		                        'lookbook'   => array(
		                            'label'     => __('Lookbook', MTS_THEME_TEXTDOMAIN),
		                            'subfields' => array(
		                            	array(
		                                    'id' => 'lookbook_heading',
		                                    'type' => 'text',
		                                    'title' => __('Lookbook Section Heading', MTS_THEME_TEXTDOMAIN),
		                                    'std'=> __('Lookbook', MTS_THEME_TEXTDOMAIN),
		                                ),
		                                array(
		                                    'id'        => 'lookbook_images',
		                                    'type'      => 'group',
		                                    'title'     => __('Images', MTS_THEME_TEXTDOMAIN),
		                                    //'sub_desc'  => __('Recommended Size: 148x284 px', MTS_THEME_TEXTDOMAIN),
		                                    'groupname' => __('Image', MTS_THEME_TEXTDOMAIN), // Group name
		                                    'subfields' =>  array(
		                                        array(
		                                            'id' => 'title',
		                                            'type' => 'text',
		                                            'title' => __('Title', MTS_THEME_TEXTDOMAIN),
		                                        ),
		                                        array(
		                                            'id' => 'image',
		                                            'type' => 'upload',
		                                            'title' => __('Image', MTS_THEME_TEXTDOMAIN),
		                                            'sub_desc'  => __('Recommended Size: 148x284 px', MTS_THEME_TEXTDOMAIN),
		                                            'return' => 'id'
		                                        ),
		                                    ),
										)
		                            )
		                        ),
								'product_tabs'   => array(
		                            'label'     => __('Product Tabs', MTS_THEME_TEXTDOMAIN),
		                            'subfields' => array(
		                                array(
											'id' => 'mts_tabs_hover_effect',
											'type' => 'select',
											'title' => __('Products hover effect', MTS_THEME_TEXTDOMAIN),
											'sub_desc' => __('Products hover effect.', MTS_THEME_TEXTDOMAIN),
											'options' => $product_hover_effects,
											'std' => 'featured',
										),
										array(
											'id'       => 'mts_home_tabs',
											'type'     => 'layout',
											'title'    => __('Product tabs to Show', 'mythemeshop'),
											'sub_desc' => __('Organize how you want the tabs on homepage to appear', MTS_THEME_TEXTDOMAIN),
											'options'  => array(
											    'enabled'  => array(
											        'best_sellers_tab' => __('Best sellers', MTS_THEME_TEXTDOMAIN),
											        'new_products_tab' => __('New Arrivals', MTS_THEME_TEXTDOMAIN),
											        'top_rated_tab'    => __('Top Rated', MTS_THEME_TEXTDOMAIN),
											    ),
											    'disabled' => array(
											    )
											),
											'std'  => array(
											    'enabled'  => array(
											        'best_sellers_tab' => __('Best sellers', MTS_THEME_TEXTDOMAIN),
											        'new_products_tab' => __('New Arrivals', MTS_THEME_TEXTDOMAIN),
											        'top_rated_tab'    => __('Top Rated', MTS_THEME_TEXTDOMAIN),
											    ),
											    'disabled' => array(
											    )
											)
										),

		                            )
		                        ),
		                        'offers'   => array(
		                            'label'     => __('Offers', MTS_THEME_TEXTDOMAIN),
		                            'subfields' => array(
		                                array(
		                                    'id' => 'offers_heading',
		                                    'type' => 'text',
		                                    'title' => __('Offers Section Heading', MTS_THEME_TEXTDOMAIN),
		                                    'std'=> __('Limited Offers', MTS_THEME_TEXTDOMAIN),
		                                ),
																		array(
		                                    'id' => 'offers_sortby',
		                                    'type' => 'select',
		                                    'title' => __('Sort Products By', MTS_THEME_TEXTDOMAIN),
		                                    'std'=> __('Sort Products By', MTS_THEME_TEXTDOMAIN),
																				'options' => array(
																					'name'       => __('Name', MTS_THEME_TEXTDOMAIN),
																					'popular'    => __('Popularity ( Sales )', MTS_THEME_TEXTDOMAIN),
																					'rating'     => __('Average Rating', MTS_THEME_TEXTDOMAIN),
																					'recent'     => __('Most Recent', MTS_THEME_TEXTDOMAIN),
																					'price-asc'  => __('Price Ascending', MTS_THEME_TEXTDOMAIN),
																					'price-desc' => __('Price Descending', MTS_THEME_TEXTDOMAIN),
																					'random'     => __('Random', MTS_THEME_TEXTDOMAIN),
																				),
																				'std' => 'recent',
		                                ),
		                            )
		                        ),
		                        'brands'   => array(
		                            'label'     => __('Brands', MTS_THEME_TEXTDOMAIN),
		                            'subfields' => array(
		                                array(
		                                    'id' => 'brands_heading',
		                                    'type' => 'text',
		                                    'title' => __('Brands Section Heading', MTS_THEME_TEXTDOMAIN),
		                                    'std'=> __('Shop By Brand', MTS_THEME_TEXTDOMAIN),
		                                ),
		                                array(
		                                    'id'        => 'brand_images',
		                                    'type'      => 'group',
		                                    'title'     => __('Images', MTS_THEME_TEXTDOMAIN),
		                                    'groupname' => __('Image', MTS_THEME_TEXTDOMAIN), // Group name
		                                    'subfields' =>  array(
		                                        array(
		                                            'id' => 'title',
		                                            'type' => 'text',
		                                            'title' => __('Title', MTS_THEME_TEXTDOMAIN),
		                                        ),
		                                        array(
		                                            'id' => 'image',
		                                            'type' => 'upload',
		                                            'title' => __('Image', MTS_THEME_TEXTDOMAIN),
		                                            'sub_desc' => __('Recommended Size: 170x100 px', MTS_THEME_TEXTDOMAIN),
		                                            'return' => 'id'
		                                        ),
		                                        array(
		                                            'id' => 'link',
		                                            'type' => 'text',
		                                            'title' => __('Link', MTS_THEME_TEXTDOMAIN),
		                                            'std' => '#',
		                                        ),
		                                    ),
										)
		                            )
		                        ),
		                        'posts'   => array(
		                            'label'     => __('Latest Posts', MTS_THEME_TEXTDOMAIN),
		                            'subfields' => array(
		                                array(
		                                    'id' => 'latest_posts_heading',
		                                    'type' => 'text',
		                                    'title' => __('Latest Posts Section Heading', MTS_THEME_TEXTDOMAIN),
		                                    'std'=> __('Latest From Our blog', MTS_THEME_TEXTDOMAIN),
		                                ),
		                            )
		                        ),
		                    ),
		                    'disabled' => array(

		                    )
		                )
		            ),
				),
			);

$sections[] = array(
		'icon' => 'fa fa-shopping-cart',
		'title' => __('WooCommerce', MTS_THEME_TEXTDOMAIN) ,
		'desc' => __('<p class="description">From here, you can control your WooCommerce Shop ( WooCommerce plugin must be enabled ).</p>', MTS_THEME_TEXTDOMAIN) ,
		'fields' => array(
			array(
				'id' => 'mts_shop_products',
				'type' => 'text',
				'title' => __('No. of Products on Shop Page', MTS_THEME_TEXTDOMAIN),
				'sub_desc' => __('Enter the total number of products which you want to show on shop page.', MTS_THEME_TEXTDOMAIN),
				'validate' => 'numeric',
				'std' => '9',
				'class' => 'small-text'
				),
			array(
				'id' => 'mts_shop_hover_effect',
				'type' => 'select',
				'title' => __('Product hover effect on Shop Pages', MTS_THEME_TEXTDOMAIN),
				'sub_desc' => __('Select Product hover effect on Shop archive pages.', MTS_THEME_TEXTDOMAIN),
				'options' => $product_hover_effects,
				'std' => 'default',
				),
			array(
				'id' => 'mts_related_products',
				'type' => 'button_set_hide_below',
				'title' => __('Related Products Carousel', MTS_THEME_TEXTDOMAIN) ,
				'options' => array( '0' => __( 'Off', MTS_THEME_TEXTDOMAIN ), '1' => __( 'On', MTS_THEME_TEXTDOMAIN ) ),
				'sub_desc' => __('Enable or disable Related Products Carousel on single product view.', MTS_THEME_TEXTDOMAIN) ,
				'std' => '0',
				'args' => array('hide' => 2)
				),
				array(
					'id' => 'mts_related_products_num',
					'type' => 'text',
					'title' => __('No. of Related Products', MTS_THEME_TEXTDOMAIN),
					'sub_desc' => __('Enter the total number of related products.', MTS_THEME_TEXTDOMAIN),
					'validate' => 'numeric',
					'std' => '8',
					'class' => 'small-text'
					),
				array(
					'id' => 'mts_related_products_hover_effect',
					'type' => 'select',
					'title' => __('Related Products hover effect', MTS_THEME_TEXTDOMAIN),
					'sub_desc' => __('Related Products hover effect.', MTS_THEME_TEXTDOMAIN),
					'options' => $product_hover_effects,
					'std' => 'featured',
				),
			array(
				'id' => 'mts_featured_products',
				'type' => 'button_set_hide_below',
				'title' => __('Featured Products Carousel', MTS_THEME_TEXTDOMAIN) ,
				'options' => array( '0' => __( 'Off', MTS_THEME_TEXTDOMAIN ), '1' => __( 'On', MTS_THEME_TEXTDOMAIN ) ),
				'sub_desc' => __('Enable or disable Featured Products Carousel. This section will appear after related products in single Product page.', MTS_THEME_TEXTDOMAIN),
				'std' => '0',
				'args' => array('hide' => 3)
				),
				array(
					'id' => 'mts_featured_products_num',
					'type' => 'text',
					'title' => __('No. of Featured Products', MTS_THEME_TEXTDOMAIN),
					'sub_desc' => __('Enter the total number of featured products.', MTS_THEME_TEXTDOMAIN),
					'validate' => 'numeric',
					'std' => '8',
					'class' => 'small-text'
					),
				array(
					'id' => 'mts_featured_products_locations',
					'type' => 'multi_checkbox',
						'title' => __('Featured Products Carousel Locations', MTS_THEME_TEXTDOMAIN),
						'sub_desc' => __('Choose where would you like Featured Products Carousel to appear.', MTS_THEME_TEXTDOMAIN),
						'options' => array(
							'product'  => __('Below Single Product',MTS_THEME_TEXTDOMAIN),
							'cart'     => __('Below Cart Page',MTS_THEME_TEXTDOMAIN),
							'checkout' => __('Below Checkout Page',MTS_THEME_TEXTDOMAIN),
							'thankyou' => __('Below Order Complete Page',MTS_THEME_TEXTDOMAIN),
						),
						'std' => array(
							'product'  => '1',
							'cart'     => '1',
							'checkout' => '1',
							'thankyou' => '1'
						)
					),
				array(
					'id' => 'mts_featured_hover_effect',
					'type' => 'select',
					'title' => __('Featured Products hover effect', MTS_THEME_TEXTDOMAIN),
					'sub_desc' => __('Select Featured Products hover effect.', MTS_THEME_TEXTDOMAIN),
					'options' => $product_hover_effects,
					'std' => 'featured',
				),
			array(
				'id' => 'mts_mark_new_products',
				'type' => 'button_set_hide_below',
				'title' => __('Mark New Products', MTS_THEME_TEXTDOMAIN) ,
				'options' => array( '0' => __( 'Off', MTS_THEME_TEXTDOMAIN ), '1' => __( 'On', MTS_THEME_TEXTDOMAIN ) ),
				'sub_desc' => __('Enable or disable "new" marking on latest products.', MTS_THEME_TEXTDOMAIN),
				'std' => '0',
				),
			array(
				'id' => 'mts_new_products_time',
				'type' => 'text',
					'title' => __('No. of days the product is considered new', MTS_THEME_TEXTDOMAIN),
					'sub_desc' => __('No. of days the product is considered new.', MTS_THEME_TEXTDOMAIN),
					'validate' => 'numeric',
					'std' => '30',
					'class' => 'small-text'
				),
			array(
				'id' => 'mts_wishlist',
				'type' => 'button_set',
				'title' => __('Wishlist Feature', MTS_THEME_TEXTDOMAIN),
				'options' => array( '0' => __( 'Off', MTS_THEME_TEXTDOMAIN ), '1' => __( 'On', MTS_THEME_TEXTDOMAIN ) ),
				'sub_desc' => __('Enable or disable wishlist feature. Page with "Wishlist" title and "Wishlist Page" template is required.', MTS_THEME_TEXTDOMAIN),
				'std' => '0'
			),
			array(
				'id' => 'mts_category_ad_widgets_enabled',
				'type' => 'button_set_hide_below',
				'title' => __('Product Category Ad Widgets', MTS_THEME_TEXTDOMAIN) ,
				'options' => array(
					'0' => 'Single',
					'1' => 'Per Category'
				) ,
				'sub_desc' => __('Create separate ad widget areas (appears above the products listing) for each product category with this option.', MTS_THEME_TEXTDOMAIN),
				'std' => '0',
				'class' => 'green',
				'args' => array('hide' => 1)
			),
			array(
				'id' => 'mts_category_ad_widgets',
				'type' => 'product_cat_multi_checkbox',
				'title' => __('Create Category Ad Widget Areas', MTS_THEME_TEXTDOMAIN),
				'sub_desc' => __('Create Ad Widgets for archive pages of Product Categories. These widgets can be used to show promotional banners.', MTS_THEME_TEXTDOMAIN),
				'options' => array(),
				'std' => array()
			),
			array(
				'id' => 'mts_wc_product_gallery',
				'type' => 'button_set_hide_below',
				'title' => __('WooCommerce v3.0.0 Product Gallery', MTS_THEME_TEXTDOMAIN),
				'options' => array( '0' => __( 'Off', MTS_THEME_TEXTDOMAIN ), '1' => __( 'On', MTS_THEME_TEXTDOMAIN ) ),
				'sub_desc' => __('Enable or disable product gallery introduced in WooCommerce v 3.0.0.', MTS_THEME_TEXTDOMAIN),
				'std' => '1',
				'reset_at_version' => '1.5'
			),
			array(
				'id' => 'mts_wc_product_gallery_enable',
				'type' => 'multi_checkbox',
				'title' => __('Gallery Options', MTS_THEME_TEXTDOMAIN ),
				'sub_desc' => __('Enable/disable Gallery options of single product page from here.', MTS_THEME_TEXTDOMAIN ),
				'options' => array(
					'zoom'=> __('Enable Zoom', MTS_THEME_TEXTDOMAIN ),
					'lightbox'=>__('Enable Lightbox', MTS_THEME_TEXTDOMAIN ),
					'slider' => __('Enable Slider', MTS_THEME_TEXTDOMAIN )
				),
				'std' => array(
					'zoom'=> '1',
					'lightbox'=>'1',
					'slider'=> '1',
				),
				'reset_at_version' => '1.5'
			),
		),
	);
$sections[] = array(
			'icon' => 'fa fa-th-list',
			'title' => __('Blog Page', MTS_THEME_TEXTDOMAIN ),
			'desc' => __('From here, you can control the elements of the Blog page.', MTS_THEME_TEXTDOMAIN ),
			'fields' => array(
				array(
                        'id' => 'mts_pagenavigation_type',
                        'type' => 'radio',
                        'title' => __('Pagination Type', MTS_THEME_TEXTDOMAIN ),
                        'sub_desc' => __('Select pagination type.', MTS_THEME_TEXTDOMAIN ),
                        'options' => array(
                                        '0'=> __('Default (Next / Previous)', MTS_THEME_TEXTDOMAIN ),
                                        '1' => __('Numbered (1 2 3 4...)', MTS_THEME_TEXTDOMAIN ),
                                        '2' => __('AJAX (Load More Button)', MTS_THEME_TEXTDOMAIN ),
                                        '3' => __('AJAX (Auto Infinite Scroll)', MTS_THEME_TEXTDOMAIN )
                                    ),
                        'std' => '1'
                        ),
                    array(
                        'id' => 'mts_ajax_search',
                        'type' => 'button_set',
                        'title' => __('AJAX Quick search', MTS_THEME_TEXTDOMAIN ),
						'options' => array( '0' => __( 'Off', MTS_THEME_TEXTDOMAIN ), '1' => __( 'On', MTS_THEME_TEXTDOMAIN ) ),
						'sub_desc' => __('Enable or disable search results appearing instantly below the search form', MTS_THEME_TEXTDOMAIN ),
						'std' => '0'
                        ),
                    array(
                        'id' => 'mts_full_posts',
                        'type' => 'button_set',
                        'title' => __('Posts on blog pages', MTS_THEME_TEXTDOMAIN ),
						'options' => array('0' => 'Excerpts','1' => 'Full posts'),
						'sub_desc' => __('Show post excerpts or full posts on the homepage and other archive pages.', MTS_THEME_TEXTDOMAIN ),
						'std' => '0',
                        'class' => 'green'
                        ),
                    array(
                        'id'       => 'mts_home_headline_meta_info',
                        'type'     => 'layout',
                        'title'    => __('Blog Post Meta Info', MTS_THEME_TEXTDOMAIN ),
                        'sub_desc' => __('Organize how you want the post meta info to appear on the homepage', MTS_THEME_TEXTDOMAIN ),
                        'options'  => array(
                            'enabled'  => array(
                                'author'   => __('Author Name', MTS_THEME_TEXTDOMAIN ),
                                'date'     => __('Date', MTS_THEME_TEXTDOMAIN ),
                                'category' => __('Categories', MTS_THEME_TEXTDOMAIN ),
                                'comment'  => __('Comment Count', MTS_THEME_TEXTDOMAIN )
                            ),
                            'disabled' => array(
                            )
                        ),
                        'std'  => array(
                            'enabled'  => array(
                                'author'   => __('Author Name', MTS_THEME_TEXTDOMAIN ),
                                'date'     => __('Date', MTS_THEME_TEXTDOMAIN ),
                                'category' => __('Categories', MTS_THEME_TEXTDOMAIN ),
                                'comment'  => __('Comment Count', MTS_THEME_TEXTDOMAIN )
                            ),
                            'disabled' => array(
                            )
                        )
                    ),
            )
		);
$sections[] = array(
				'icon' => 'fa fa-file-text',
				'title' => __('Single Posts', MTS_THEME_TEXTDOMAIN ),
				'desc' => __('<p class="description">From here, you can control the appearance and functionality of your single posts page.</p>', MTS_THEME_TEXTDOMAIN ),
				'fields' => array(
					array(
                        'id'       => 'mts_single_post_layout',
                        'type'     => 'layout2',
                        'title'    => __('Single Post Layout', MTS_THEME_TEXTDOMAIN ),
                        'sub_desc' => __('Customize the look of single posts', MTS_THEME_TEXTDOMAIN ),
                        'options'  => array(
                            'enabled'  => array(
                                'content'   => array(
                                	'label' 	=> __('Post Content', MTS_THEME_TEXTDOMAIN ),
                                	'subfields'	=> array(

                                	)
                                ),
                                'author'   => array(
                                	'label' 	=> __('Author Box', MTS_THEME_TEXTDOMAIN ),
                                	'subfields'	=> array(

                                	)
                                ),
                            ),
                            'disabled' => array(
                            	'tags'   => array(
                                	'label' 	=> __('Tags', MTS_THEME_TEXTDOMAIN ),
                                	'subfields'	=> array(
                                	)
                                ),
                            )
                        )
                    ),
					array(
        				'id' => 'mts_related_posts',
        				'type' => 'button_set_hide_below',
        				'title' => __('Related Posts', MTS_THEME_TEXTDOMAIN) ,
        				'options' => array( '0' => __( 'Off', MTS_THEME_TEXTDOMAIN ), '1' => __( 'On', MTS_THEME_TEXTDOMAIN ) ),
        				'sub_desc' => __('Use this button to show related posts with thumbnails below the content area in a post.', MTS_THEME_TEXTDOMAIN) ,
        				'std' => '1',
        				'args' => array(
        					'hide' => 3
        				)
        			),
        			array(
        				'id' => 'mts_related_posts_taxonomy',
        				'type' => 'button_set',
        				'title' => __('Related Posts Taxonomy', MTS_THEME_TEXTDOMAIN) ,
        				'options' => array(
        					'tags' => 'Tags',
        					'categories' => 'Categories'
        				) ,
        				'class' => 'green',
        				'sub_desc' => __('Related Posts based on tags or categories.', MTS_THEME_TEXTDOMAIN) ,
        				'std' => 'categories'
        			),
        			array(
        				'id' => 'mts_related_postsnum',
        				'type' => 'text',
        				'class' => 'small-text',
        				'title' => __('Number of related posts', MTS_THEME_TEXTDOMAIN) ,
        				'sub_desc' => __('Enter the number of posts to show in the related posts section.', MTS_THEME_TEXTDOMAIN) ,
        				'std' => '4',
        				'args' => array(
        					'type' => 'number'
        				)
        			),
					array(
	                    'id'       => 'mts_single_headline_meta_info',
	                    'type'     => 'layout',
	                    'title'    => __('Meta Info to Show', MTS_THEME_TEXTDOMAIN ),
	                    'sub_desc' => __('Organize how you want the post meta info to appear', MTS_THEME_TEXTDOMAIN ),
	                    'options'  => array(
	                        'enabled'  => array(
	                            'author'   => __('Author Name', MTS_THEME_TEXTDOMAIN ),
	                            'date'     => __('Date', MTS_THEME_TEXTDOMAIN ),
	                            'category' => __('Categories', MTS_THEME_TEXTDOMAIN ),
	                            'comment'  => __('Comment Count', MTS_THEME_TEXTDOMAIN )
	                        ),
	                        'disabled' => array(
	                        )
	                    ),
	                    'std'  => array(
	                        'enabled'  => array(
	                            'author'   => __('Author Name', MTS_THEME_TEXTDOMAIN ),
	                            'date'     => __('Date', MTS_THEME_TEXTDOMAIN ),
	                            'category' => __('Categories', MTS_THEME_TEXTDOMAIN ),
	                            'comment'  => __('Comment Count', MTS_THEME_TEXTDOMAIN )
	                        ),
	                        'disabled' => array(
	                        )
	                    )
	                ),
					array(
						'id' => 'mts_breadcrumb',
						'type' => 'button_set',
						'title' => __('Breadcrumbs', MTS_THEME_TEXTDOMAIN ),
						'options' => array( '0' => __( 'Off', MTS_THEME_TEXTDOMAIN ), '1' => __( 'On', MTS_THEME_TEXTDOMAIN ) ),
						'sub_desc' => __('Breadcrumbs are a great way to make your site more user-friendly. You can enable them by checking this box.', MTS_THEME_TEXTDOMAIN ),
						'std' => '1'
						),
					array(
						'id' => 'mts_author_comment',
						'type' => 'button_set',
						'title' => __('Highlight Author Comment', MTS_THEME_TEXTDOMAIN ),
						'options' => array( '0' => __( 'Off', MTS_THEME_TEXTDOMAIN ), '1' => __( 'On', MTS_THEME_TEXTDOMAIN ) ),
						'sub_desc' => __('Use this button to highlight author comments.', MTS_THEME_TEXTDOMAIN ),
						'std' => '1'
						),
					array(
						'id' => 'mts_comment_date',
						'type' => 'button_set',
						'title' => __('Date in Comments', MTS_THEME_TEXTDOMAIN ),
						'options' => array( '0' => __( 'Off', MTS_THEME_TEXTDOMAIN ), '1' => __( 'On', MTS_THEME_TEXTDOMAIN ) ),
						'sub_desc' => __('Use this button to show the date for comments.', MTS_THEME_TEXTDOMAIN ),
						'std' => '1'
						),
					)
				);
$sections[] = array(
				'icon' => 'fa fa-group',
				'title' => __('Social Buttons', MTS_THEME_TEXTDOMAIN ),
				'desc' => __('<p class="description">Enable or disable social sharing buttons on single posts using these buttons.</p>', MTS_THEME_TEXTDOMAIN ),
				'fields' => array(
					array(
						'id' => 'mts_social_button_position',
						'type' => 'button_set',
						'title' => __('Social Sharing Buttons Position', MTS_THEME_TEXTDOMAIN ),
						'options' => array('top' => __('Above Content', MTS_THEME_TEXTDOMAIN ), 'bottom' => __('Below Content', MTS_THEME_TEXTDOMAIN ), 'floating' => __('Floating', MTS_THEME_TEXTDOMAIN )),
						'sub_desc' => __('Choose position for Social Sharing Buttons.', MTS_THEME_TEXTDOMAIN ),
						'std' => 'bottom',
						'class' => 'green'
					),
					array(
                        'id'       => 'mts_social_buttons',
                        'type'     => 'layout',
                        'title'    => __('Social Media Buttons', MTS_THEME_TEXTDOMAIN ),
                        'sub_desc' => __('Organize how you want the social sharing buttons to appear on single posts', MTS_THEME_TEXTDOMAIN ),
                        'options'  => array(
                            'enabled'  => array(
                            	'facebookshare'   => __('Facebook Share', MTS_THEME_TEXTDOMAIN ),
                            	'facebook'  => __('Facebook Like', MTS_THEME_TEXTDOMAIN ),
                                'twitter'   => __('Twitter', MTS_THEME_TEXTDOMAIN ),
                                'pinterest' => __('Pinterest', MTS_THEME_TEXTDOMAIN ),
                            ),
                            'disabled' => array(
                            	'linkedin'  => __('LinkedIn', MTS_THEME_TEXTDOMAIN ),
                                'stumble'   => __('StumbleUpon', MTS_THEME_TEXTDOMAIN ),
                            )
                        ),
                        'std'  => array(
                            'enabled'  => array(
                            	'facebookshare'   => __('Facebook Share', MTS_THEME_TEXTDOMAIN ),
                            	'facebook'  => __('Facebook Like', MTS_THEME_TEXTDOMAIN ),
                                'twitter'   => __('Twitter', MTS_THEME_TEXTDOMAIN ),
                                'pinterest' => __('Pinterest', MTS_THEME_TEXTDOMAIN ),
                            ),
                            'disabled' => array(
                            	'linkedin'  => __('LinkedIn', MTS_THEME_TEXTDOMAIN ),
                                'stumble'   => __('StumbleUpon', MTS_THEME_TEXTDOMAIN ),
                            )
                        )
                    ),
				)
			);
$sections[] = array(
				'icon' => 'fa fa-envelope',
				'title' => __('Contact Page', MTS_THEME_TEXTDOMAIN ),
				'desc' => __('<p class="description">Customize contact page.</p>', MTS_THEME_TEXTDOMAIN ),
				'fields' => array(
					array(
						'id' => 'mts_map_coordinates',
						'type' => 'text',
						'title' => __('Map Coordinates', MTS_THEME_TEXTDOMAIN),
						'sub_desc' => __('Enter the longitude and latitude or full address e.g. 47.6203394,-122.3491925', MTS_THEME_TEXTDOMAIN)
					),
					array(
						'id' => 'mts_maps_api_key',
						'type' => 'text',
						'title' => __('Google Maps API Key', MTS_THEME_TEXTDOMAIN),
						'sub_desc' => __('Enter your API key, which is now required by Google Maps. Create a new key <a href="https://console.developers.google.com/flows/enableapi?apiid=maps_backend,geocoding_backend,directions_backend,distance_matrix_backend,elevation_backend&keyType=CLIENT_SIDE&reusekey=true" target="_blank">here</a>.', MTS_THEME_TEXTDOMAIN)
					),
					array(
						'id' => 'mts_contact_title',
						'type' => 'textarea',
						'title' => __( 'Contact Form Title', MTS_THEME_TEXTDOMAIN ),
						'sub_desc' => __('Enter the title for contact form section', MTS_THEME_TEXTDOMAIN)
					),
					array(
						'id' => 'mts_contact_desc',
						'type' => 'textarea',
						'title' => __( 'Contact Form Description', MTS_THEME_TEXTDOMAIN ),
						'sub_desc' => __('Enter the description for contact form section', MTS_THEME_TEXTDOMAIN)
					),
					array(
						'id' => 'mts_contact_email',
						'type' => 'text',
						'title' => __( 'Email Address', MTS_THEME_TEXTDOMAIN ),
						'sub_desc' => __('If you leave it empty, admin email address will be used', MTS_THEME_TEXTDOMAIN)
					),
					array(
						'id' => 'mts_faqs_title',
						'type' => 'text',
						'title' => __( 'FAQ Section Title', MTS_THEME_TEXTDOMAIN ),
						'sub_desc' => __('Enter the title for FAQ section', MTS_THEME_TEXTDOMAIN)
					),
					array(
						'id' => 'mts_faqs_desc',
						'type' => 'textarea',
						'title' => __( 'FAQ Section Description', MTS_THEME_TEXTDOMAIN ),
						'sub_desc' => __('Enter the description FAQ section', MTS_THEME_TEXTDOMAIN)
					),
					array(
	                    'id'        => 'mts_faqs',
	                    'type'      => 'group',
	                    'title'     => __('Frequently Asked Questions', MTS_THEME_TEXTDOMAIN),
	                    'groupname' => __('FAQ', MTS_THEME_TEXTDOMAIN), // Group name
	                    'subfields' =>  array(
	                    	array(
	                            'id' => 'title',
	                            'type' => 'text',
	                            'title' => __('Title ( not used on front end )', MTS_THEME_TEXTDOMAIN),
	                        ),
	                        array(
	                            'id' => 'question',
	                            'type' => 'text',
	                            'title' => __('Question', MTS_THEME_TEXTDOMAIN),
	                        ),
	                        array(
	                            'id' => 'answer',
	                            'type' => 'textarea',
	                            'title' => __('Answer', MTS_THEME_TEXTDOMAIN),
	                        ),
	                    ),
					)
				)
			);
$sections[] = array(
				'icon' => 'fa fa-bar-chart-o',
				'title' => __('Ad Management', MTS_THEME_TEXTDOMAIN ),
				'desc' => __('<p class="description">Now, ad management is easy with our options panel. You can control everything from here, without using separate plugins.</p>', MTS_THEME_TEXTDOMAIN ),
				'fields' => array(
					array(
						'id' => 'mts_posttop_adcode',
						'type' => 'textarea',
						'title' => __('Below Post Title', MTS_THEME_TEXTDOMAIN ),
						'sub_desc' => __('Paste your Adsense, BSA or other ad code here to show ads below your article title on single posts.', MTS_THEME_TEXTDOMAIN )
						),
					array(
						'id' => 'mts_posttop_adcode_time',
						'type' => 'text',
						'title' => __('Show After X Days', MTS_THEME_TEXTDOMAIN ),
						'sub_desc' => __('Enter the number of days after which you want to show the Below Post Title Ad. Enter 0 to disable this feature.', MTS_THEME_TEXTDOMAIN ),
						'validate' => 'numeric',
						'std' => '0',
						'class' => 'small-text',
                        'args' => array('type' => 'number')
						),
					array(
						'id' => 'mts_postend_adcode',
						'type' => 'textarea',
						'title' => __('Below Post Content', MTS_THEME_TEXTDOMAIN ),
						'sub_desc' => __('Paste your Adsense, BSA or other ad code here to show ads below the post content on single posts.', MTS_THEME_TEXTDOMAIN )
						),
					array(
						'id' => 'mts_postend_adcode_time',
						'type' => 'text',
						'title' => __('Show After X Days', MTS_THEME_TEXTDOMAIN ),
						'sub_desc' => __('Enter the number of days after which you want to show the Below Post Title Ad. Enter 0 to disable this feature.', MTS_THEME_TEXTDOMAIN ),
						'validate' => 'numeric',
						'std' => '0',
						'class' => 'small-text',
                        'args' => array('type' => 'number')
						),
					)
				);
$sections[] = array(
				'icon' => 'fa fa-columns',
				'title' => __('Sidebars', MTS_THEME_TEXTDOMAIN ),
				'desc' => __('<p class="description">Now you have full control over the sidebars. Here you can manage sidebars and select one for each section of your site, or select a custom sidebar on a per-post basis in the post editor.<br></p>', MTS_THEME_TEXTDOMAIN ),
                'fields' => array(
                    array(
                        'id'        => 'mts_custom_sidebars',
                        'type'      => 'group', //doesn't need to be called for callback fields
                        'title'     => __('Custom Sidebars', MTS_THEME_TEXTDOMAIN ),
                        'sub_desc'  => __('Add custom sidebars. <strong style="font-weight: 800;">You need to save the changes to use the sidebars in the dropdowns below.</strong><br />You can add content to the sidebars in Appearance &gt; Widgets.', MTS_THEME_TEXTDOMAIN ),
                        'groupname' => __('Sidebar', MTS_THEME_TEXTDOMAIN ), // Group name
                        'subfields' =>
                            array(
                                array(
                                    'id' => 'mts_custom_sidebar_name',
            						'type' => 'text',
            						'title' => __('Name', MTS_THEME_TEXTDOMAIN ),
            						'sub_desc' => __('Example: Homepage Sidebar', MTS_THEME_TEXTDOMAIN )
            						),
                                array(
                                    'id' => 'mts_custom_sidebar_id',
            						'type' => 'text',
            						'title' => __('ID', MTS_THEME_TEXTDOMAIN ),
            						'sub_desc' => __('Enter a unique ID for the sidebar. Use only alphanumeric characters, underscores (_) and dashes (-), eg. "sidebar-home"', MTS_THEME_TEXTDOMAIN ),
            						'std' => 'sidebar-'
            						),
                            ),
                        ),
                    /*array(
						'id' => 'mts_sidebar_for_home',
						'type' => 'sidebars_select',
						'title' => __('Homepage', MTS_THEME_TEXTDOMAIN ),
						'sub_desc' => __('Select a sidebar for the homepage.', MTS_THEME_TEXTDOMAIN ),
                        'args' => array('allow_nosidebar' => false, 'exclude' => mts_get_excluded_sidebars()),
                        'std' => ''
						),*/
                    array(
						'id' => 'mts_sidebar_for_post',
						'type' => 'sidebars_select',
						'title' => __('Single Post', MTS_THEME_TEXTDOMAIN ),
						'sub_desc' => __('Select a sidebar for the single posts. If a post has a custom sidebar set, it will override this.', MTS_THEME_TEXTDOMAIN ),
                        'args' => array('exclude' => mts_get_excluded_sidebars()),
                        'std' => ''
						),
                    array(
						'id' => 'mts_sidebar_for_page',
						'type' => 'sidebars_select',
						'title' => __('Single Page', MTS_THEME_TEXTDOMAIN ),
						'sub_desc' => __('Select a sidebar for the single pages. If a page has a custom sidebar set, it will override this.', MTS_THEME_TEXTDOMAIN ),
                        'args' => array('exclude' => mts_get_excluded_sidebars()),
                        'std' => ''
						),
                    array(
						'id' => 'mts_sidebar_for_archive',
						'type' => 'sidebars_select',
						'title' => __('Archive', MTS_THEME_TEXTDOMAIN ),
						'sub_desc' => __('Select a sidebar for the archives. Specific archive sidebars will override this setting (see below).', MTS_THEME_TEXTDOMAIN ),
                        'args' => array('allow_nosidebar' => false, 'exclude' => mts_get_excluded_sidebars()),
                        'std' => ''
						),
                    array(
						'id' => 'mts_sidebar_for_category',
						'type' => 'sidebars_select',
						'title' => __('Category Archive', MTS_THEME_TEXTDOMAIN ),
						'sub_desc' => __('Select a sidebar for the category archives.', MTS_THEME_TEXTDOMAIN ),
                        'args' => array('allow_nosidebar' => false, 'exclude' => mts_get_excluded_sidebars()),
                        'std' => ''
						),
                    array(
						'id' => 'mts_sidebar_for_tag',
						'type' => 'sidebars_select',
						'title' => __('Tag Archive', MTS_THEME_TEXTDOMAIN ),
						'sub_desc' => __('Select a sidebar for the tag archives.', MTS_THEME_TEXTDOMAIN ),
                        'args' => array('allow_nosidebar' => false, 'exclude' => mts_get_excluded_sidebars()),
                        'std' => ''
						),
                    array(
						'id' => 'mts_sidebar_for_date',
						'type' => 'sidebars_select',
						'title' => __('Date Archive', MTS_THEME_TEXTDOMAIN ),
						'sub_desc' => __('Select a sidebar for the date archives.', MTS_THEME_TEXTDOMAIN ),
                        'args' => array('allow_nosidebar' => false, 'exclude' => mts_get_excluded_sidebars()),
                        'std' => ''
						),
                    array(
						'id' => 'mts_sidebar_for_author',
						'type' => 'sidebars_select',
						'title' => __('Author Archive', MTS_THEME_TEXTDOMAIN ),
						'sub_desc' => __('Select a sidebar for the author archives.', MTS_THEME_TEXTDOMAIN ),
                        'args' => array('allow_nosidebar' => false, 'exclude' => mts_get_excluded_sidebars()),
                        'std' => ''
						),
                    array(
						'id' => 'mts_sidebar_for_search',
						'type' => 'sidebars_select',
						'title' => __('Search', MTS_THEME_TEXTDOMAIN ),
						'sub_desc' => __('Select a sidebar for the search results.', MTS_THEME_TEXTDOMAIN ),
                        'args' => array('allow_nosidebar' => false, 'exclude' => mts_get_excluded_sidebars()),
                        'std' => ''
						),
                    array(
						'id' => 'mts_sidebar_for_notfound',
						'type' => 'sidebars_select',
						'title' => __('404 Error', MTS_THEME_TEXTDOMAIN ),
						'sub_desc' => __('Select a sidebar for the 404 Not found pages.', MTS_THEME_TEXTDOMAIN ),
                        'args' => array('allow_nosidebar' => false, 'exclude' => mts_get_excluded_sidebars()),
                        'std' => ''
						),

                    array(
						'id' => 'mts_sidebar_for_shop',
						'type' => 'sidebars_select',
						'title' => __('Shop Pages', MTS_THEME_TEXTDOMAIN ),
						'sub_desc' => __('Select a sidebar for Shop main page and product archive pages (WooCommerce plugin must be enabled). Default is <strong>Shop Page Sidebar</strong>.', MTS_THEME_TEXTDOMAIN ),
                        'args' => array('allow_nosidebar' => false, 'exclude' => mts_get_excluded_sidebars()),
                        'std' => 'shop-sidebar'
						),
                    array(
						'id' => 'mts_sidebar_for_product',
						'type' => 'sidebars_select',
						'title' => __('Single Product', MTS_THEME_TEXTDOMAIN ),
						'sub_desc' => __('Select a sidebar for single products (WooCommerce plugin must be enabled). Default is <strong>Single Product Sidebar</strong>.', MTS_THEME_TEXTDOMAIN ),
                        'args' => array('allow_nosidebar' => false, 'exclude' => mts_get_excluded_sidebars()),
                        'std' => 'product-sidebar'
						),
                    ),
				);
//$sections[] = array(
//				'icon' => NHP_OPTIONS_URL.'img/glyphicons/fontsetting.png',
//				'title' => __('Fonts', MTS_THEME_TEXTDOMAIN ),
//				'desc' => __('<p class="description"><div class="controls">You can find theme font options under the Appearance Section named <a href="themes.php?page=typography"><b>Theme Typography</b></a>, which will allow you to configure the typography used on your site.<br></div></p>', MTS_THEME_TEXTDOMAIN ),
//				);
$sections[] = array(
				'icon' => 'fa fa-list-alt',
				'title' => __('Navigation', MTS_THEME_TEXTDOMAIN ),
				'desc' => __('<p class="description"><div class="controls">Navigation settings can now be modified from the <a href="nav-menus.php"><b>Menus Section</b></a>.<br></div></p>', MTS_THEME_TEXTDOMAIN )
				);


	$tabs = array();

    $args['presets'] = array();
    $args['show_translate'] = false;
    include('theme-presets.php');

	global $NHP_Options;
	$NHP_Options = new NHP_Options($sections, $args, $tabs);

}//function
add_action('init', 'setup_framework_options', 0);

/*
 *
 * Custom function for the callback referenced above
 *
 */
function my_custom_field($field, $value){
	print_r($field);
	print_r($value);

}//function

/*
 *
 * Custom function for the callback validation referenced above
 *
 */
function validate_callback_function($field, $value, $existing_value){

	$error = false;
	$value =  'just testing';
	/*
	do your validation

	if(something){
		$value = $value;
	}elseif(somthing else){
		$error = true;
		$value = $existing_value;
		$field['msg'] = 'your custom error message';
	}
	*/
	$return['value'] = $value;
	if($error == true){
		$return['error'] = $field;
	}
	return $return;

}//function

/*--------------------------------------------------------------------
 *
 * Default Font Settings
 *
 --------------------------------------------------------------------*/
if(function_exists('mts_register_typography')) {
  mts_register_typography(array(
    'navigation_font' => array(
      'preview_text' => __('Navigation Font', MTS_THEME_TEXTDOMAIN),
      'preview_color' => 'light',
      'font_family' => 'Oxygen',
      'font_variant' => 'normal',
      'font_size' => '14px',
      'font_color' => '#657b89',
      'css_selectors' => '#primary-navigation a'
    ),
    'home_title_font' => array(
      'preview_text' => __('Blog Article Title', MTS_THEME_TEXTDOMAIN),
      'preview_color' => 'light',
      'font_family' => 'Oxygen',
      'font_size' => '26px',
	  'font_variant' => '700',
      'font_color' => '#4b5f6b',
      'css_selectors' => '.latestPost .title a'
    ),
    'single_title_font' => array(
      'preview_text' => __('Single Article Title', MTS_THEME_TEXTDOMAIN),
      'preview_color' => 'light',
      'font_family' => 'Oxygen',
      'font_size' => '38px',
	  'font_variant' => '700',
      'font_color' => '#4b5f6b',
      'css_selectors' => '.single-title'
    ),
    'content_font' => array(
      'preview_text' => __('Content Font', MTS_THEME_TEXTDOMAIN),
      'preview_color' => 'light',
      'font_family' => 'Source Sans Pro',
      'font_size' => '16px',
	  'font_variant' => 'normal',
      'font_color' => '#748087',
      'css_selectors' => 'body'
    ),
	'sidebar_font' => array(
      'preview_text' => __('Sidebar Widget Title Font', MTS_THEME_TEXTDOMAIN),
      'preview_color' => 'light',
      'font_family' => 'Source Sans Pro',
      'font_variant' => '600',
      'font_size' => '18px',
      'font_color' => '#4b5f6b',
      'css_selectors' => '.sidebar .widget h3'
    ),
    'widget_post_title_font' => array(
      'preview_text' => __('Widget Post Title Font', MTS_THEME_TEXTDOMAIN),
      'preview_color' => 'light',
      'font_family' => 'Oxygen',
      'font_variant' => '700',
      'font_size' => '15px',
      'font_color' => '#748087',
      'css_selectors' => '.widget-post-title a'
    ),
	'footer_font' => array(
      'preview_text' => __('Footer Widgets Font', MTS_THEME_TEXTDOMAIN),
      'preview_color' => 'dark',
      'font_family' => 'Source Sans Pro',
      'font_variant' => 'normal',
      'font_size' => '16px',
      'font_color' => '#c0c8cf',
      'css_selectors' => '.footer-widgets'
    ),
    'footer_links' => array(
      'preview_text' => __('Footer Widgets Links', MTS_THEME_TEXTDOMAIN),
      'preview_color' => 'dark',
      'font_family' => 'Source Sans Pro',
      'font_variant' => 'normal',
      'font_size' => '16px',
      'font_color' => '#ffffff',
      'css_selectors' => '.footer-widgets a, #site-footer .widget li a'
    ),
    'copyrights_font' => array(
      'preview_text' => __('Copyrights Font', MTS_THEME_TEXTDOMAIN),
      'preview_color' => 'dark',
      'font_family' => 'Source Sans Pro',
      'font_variant' => 'normal',
      'font_size' => '14px',
      'font_color' => '#8e9aa2',
      'css_selectors' => '.copyrights'
    ),
    'h1_headline' => array(
      'preview_text' => __('Content H1', MTS_THEME_TEXTDOMAIN),
      'preview_color' => 'light',
      'font_family' => 'Oxygen',
      'font_variant' => '700',
      'font_size' => '38px',
      'font_color' => '#4b5f6b',
      'css_selectors' => 'h1'
    ),
	'h2_headline' => array(
      'preview_text' => __('Content H2', MTS_THEME_TEXTDOMAIN),
      'preview_color' => 'light',
      'font_family' => 'Oxygen',
      'font_variant' => '700',
      'font_size' => '34px',
      'font_color' => '#4b5f6b',
      'css_selectors' => 'h2'
    ),
	'h3_headline' => array(
      'preview_text' => __('Content H3', MTS_THEME_TEXTDOMAIN),
      'preview_color' => 'light',
      'font_family' => 'Oxygen',
      'font_variant' => '700',
      'font_size' => '30px',
      'font_color' => '#4b5f6b',
      'css_selectors' => 'h3'
    ),
	'h4_headline' => array(
      'preview_text' => __('Content H4', MTS_THEME_TEXTDOMAIN),
      'preview_color' => 'light',
      'font_family' => 'Oxygen',
      'font_variant' => '700',
      'font_size' => '26px',
      'font_color' => '#4b5f6b',
      'css_selectors' => 'h4'
    ),
	'h5_headline' => array(
      'preview_text' => __('Content H5', MTS_THEME_TEXTDOMAIN),
      'preview_color' => 'light',
      'font_family' => 'Oxygen',
      'font_variant' => '700',
      'font_size' => '22px',
      'font_color' => '#4b5f6b',
      'css_selectors' => 'h5'
    ),
	'h6_headline' => array(
      'preview_text' => __('Content H6', MTS_THEME_TEXTDOMAIN),
      'preview_color' => 'light',
      'font_family' => 'Oxygen',
      'font_variant' => '700',
      'font_size' => '20px',
      'font_color' => '#4b5f6b',
      'css_selectors' => 'h6'
    )
  ));
}

?>
