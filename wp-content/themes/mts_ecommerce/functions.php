<?php
/*-----------------------------------------------------------------------------------*/
/*  Do not remove these lines, sky will fall on your head.
/*-----------------------------------------------------------------------------------*/
define( 'MTS_THEME_NAME', 'ecommerce' );
define( 'MTS_THEME_TEXTDOMAIN', 'mts_'.MTS_THEME_NAME );
define( 'MTS_THEME_VERSION', '1.7.0' );
require_once( get_theme_file_path( 'theme-options.php' ) );
if ( ! isset( $content_width ) ) $content_width = 708;

/*-----------------------------------------------------------------------------------*/
/*  Load Options
/*-----------------------------------------------------------------------------------*/
$mts_options = get_option( MTS_THEME_NAME );

/**
 * Register supported theme features, image sizes and nav menus.
 * Also loads translated strings.
 */
function mts_after_setup_theme() {
    if ( ! defined( 'MTS_THEME_WHITE_LABEL' ) ) {
        define( 'MTS_THEME_WHITE_LABEL', false );
    }

    add_theme_support( 'title-tag' );
    add_theme_support( 'automatic-feed-links' );

    load_theme_textdomain( MTS_THEME_TEXTDOMAIN, get_template_directory().'/lang' );
    add_theme_support( 'post-thumbnails' );
    //set_post_thumbnail_size( 223, 137, true );
    add_image_size( 'featured', 770, 350, true ); //featured
    add_image_size( 'related', 270, 165, true ); //related
    add_image_size( 'widgetthumb', 75, 75, true ); //widget
    add_image_size( 'widgetfull', 370, 200, true ); //sidebar full width
    add_image_size( 'lookbook', 148, 284, true ); //home lookbook
    add_image_size( 'brand', 170, 100, true ); //home brand
    add_image_size( 'productslider', 160, 200, true ); //product slider thumb
    add_image_size( 'offers', 140, 140, true ); // offers home section
    add_image_size( 'variableslider', 200, 252, true );
    add_image_size( 'woo-cat', 270, 340, true ); //woo product category

    register_nav_menus( array(
      'primary-menu' => __( 'Primary Menu', MTS_THEME_TEXTDOMAIN ),
      'secondary-menu' => __( 'Secondary Menu', MTS_THEME_TEXTDOMAIN ),
      'mobile' => __( 'Mobile', MTS_THEME_TEXTDOMAIN )
    ) );

}
add_action('after_setup_theme', 'mts_after_setup_theme' );

/*
 * Disable theme updates from WordPress.org theme repository.
 * Check if MTS Connect plugin already does this.
 */
if ( !class_exists('mts_connection') ) {
    /**
     * If wrong updates are already shown, delete transient so that we can run our workaround
     */
    function mts_hide_themes_plugins() {
        if ( !is_admin() ) return;
        if ( false === get_site_transient( 'mts_wp_org_check_disabled' ) ) { // run only once
            delete_site_transient('update_themes' );
            delete_site_transient('update_plugins' );
            add_action('current_screen', 'mts_remove_themes_plugins_from_update' );
        }
    }
    add_action('init', 'mts_hide_themes_plugins');
    /**
     * Hide mts themes/plugins.
     *
     * @param WP_Screen $screen
     */
    function mts_remove_themes_plugins_from_update( $screen ) {
        $run_on_screens = array( 'themes', 'themes-network', 'plugins', 'plugins-network', 'update-core', 'network-update-core' );
        if ( in_array( $screen->base, $run_on_screens ) ) {
            //Themes
            if ( $themes_transient = get_site_transient( 'update_themes' ) ) {
                if ( property_exists( $themes_transient, 'response' ) && is_array( $themes_transient->response ) ) {
                    foreach ( $themes_transient->response as $key => $value ) {
                        $theme = wp_get_theme( $value['theme'] );
                        $theme_uri = $theme->get( 'ThemeURI' );
                        if ( 0 !== strpos( $theme_uri, 'mythemeshop.com' ) ) {
                            unset( $themes_transient->response[$key] );
                        }
                    }
                    set_site_transient( 'update_themes', $themes_transient );
                }
            }
            //Plugins
            if ( $plugins_transient = get_site_transient( 'update_plugins' ) ) {
                if ( property_exists( $plugins_transient, 'response' ) && is_array( $plugins_transient->response ) ) {
                    foreach ( $plugins_transient->response as $key => $value ) {
                        $plugin = get_plugin_data( WP_PLUGIN_DIR.'/'.$key, false, false );
                        $plugin_uri = $plugin['PluginURI'];
                        if ( 0 !== strpos( $plugin_uri, 'mythemeshop.com' ) ) {
                            unset( $plugins_transient->response[$key] );
                        }
                    }
                    set_site_transient( 'update_plugins', $plugins_transient );
                }
            }
            set_site_transient( 'mts_wp_org_check_disabled', time() );
        }
    }
    /**
     * Delete `mts_wp_org_check_disabled` transient.
     */
    function mts_clear_check_transient(){
        delete_site_transient( 'mts_wp_org_check_disabled');
    }
    add_action( 'load-themes.php', 'mts_clear_check_transient' );
    add_action( 'load-plugins.php', 'mts_clear_check_transient' );
    add_action( 'upgrader_process_complete', 'mts_clear_check_transient' );
}

// Disable auto-updating the theme.
function mts_disable_auto_update_theme( $update, $item ) {
    if ( $item->slug == MTS_THEME_NAME ) {
        return false;
    }
    return $update;
}
add_filter( 'auto_update_theme', 'mts_disable_auto_update_theme', 10, 2 );
/**
 * Disable Google Typography plugin
 */
function mts_deactivate_google_typography_plugin() {
    if ( in_array( 'google-typography/google-typography.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
        deactivate_plugins( 'google-typography/google-typography.php' );
    }
}
add_action( 'admin_init', 'mts_deactivate_google_typography_plugin' );
// a shortcut function
function mts_isWooCommerce(){
    if(is_multisite()){
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        return is_plugin_active('woocommerce/woocommerce.php');
    }
    else{
        return in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) );
    }
}

/**
 * MTS icons for use in nav menus and icon select option.
 *
 * @return array
 */
function mts_get_icons() {
    $icons = array(
        __( 'Web Application Icons', MTS_THEME_TEXTDOMAIN ) => array(
            'address-book', 'address-book-o', 'address-card', 'address-card-o', 'adjust', 'american-sign-language-interpreting', 'anchor', 'archive', 'area-chart', 'arrows', 'arrows-h', 'arrows-v', 'asl-interpreting', 'assistive-listening-systems', 'asterisk', 'at', 'audio-description', 'automobile', 'balance-scale', 'ban', 'bank', 'bar-chart', 'bar-chart-o', 'barcode', 'bars', 'bath', 'bathtub', 'battery', 'battery-0', 'battery-1', 'battery-2', 'battery-3', 'battery-4', 'battery-empty', 'battery-full', 'battery-half', 'battery-quarter', 'battery-three-quarters', 'bed', 'beer', 'bell', 'bell-o', 'bell-slash', 'bell-slash-o', 'bicycle', 'binoculars', 'birthday-cake', 'blind', 'bluetooth', 'bluetooth-b', 'bolt', 'bomb', 'book', 'bookmark', 'bookmark-o', 'braille', 'briefcase', 'bug', 'building', 'building-o', 'bullhorn', 'bullseye', 'bus', 'cab', 'calculator', 'calendar', 'calendar-check-o', 'calendar-minus-o', 'calendar-o', 'calendar-plus-o', 'calendar-times-o', 'camera', 'camera-retro', 'car', 'caret-square-o-down', 'caret-square-o-left', 'caret-square-o-right', 'caret-square-o-up', 'cart-arrow-down', 'cart-plus', 'cc', 'certificate', 'check', 'check-circle', 'check-circle-o', 'check-square', 'check-square-o', 'child', 'circle', 'circle-o', 'circle-o-notch', 'circle-thin', 'clock-o', 'clone', 'close', 'cloud', 'cloud-download', 'cloud-upload', 'code', 'code-fork', 'coffee', 'cog', 'cogs', 'comment', 'comment-o', 'commenting', 'commenting-o', 'comments', 'comments-o', 'compass', 'copyright', 'creative-commons', 'credit-card', 'credit-card-alt', 'crop', 'crosshairs', 'cube', 'cubes', 'cutlery', 'dashboard', 'database', 'deaf', 'deafness', 'desktop', 'diamond', 'dot-circle-o', 'download', 'drivers-license', 'drivers-license-o', 'edit', 'ellipsis-h', 'ellipsis-v', 'envelope', 'envelope-o', 'envelope-open', 'envelope-open-o', 'envelope-square', 'eraser', 'exchange', 'exclamation', 'exclamation-circle', 'exclamation-triangle', 'external-link', 'external-link-square', 'eye', 'eye-slash', 'eyedropper', 'fax', 'feed', 'female', 'fighter-jet', 'file-archive-o', 'file-audio-o', 'file-code-o', 'file-excel-o', 'file-image-o', 'file-movie-o', 'file-pdf-o', 'file-photo-o', 'file-picture-o', 'file-powerpoint-o', 'file-sound-o', 'file-video-o', 'file-word-o', 'file-zip-o', 'film', 'filter', 'fire', 'fire-extinguisher', 'flag', 'flag-checkered', 'flag-o', 'flash', 'flask', 'folder', 'folder-o', 'folder-open', 'folder-open-o', 'frown-o', 'futbol-o', 'gamepad', 'gavel', 'gear', 'gears', 'gift', 'glass', 'globe', 'graduation-cap', 'group', 'hand-grab-o', 'hand-lizard-o', 'hand-paper-o', 'hand-peace-o', 'hand-pointer-o', 'hand-rock-o', 'hand-scissors-o', 'hand-spock-o', 'hand-stop-o', 'handshake-o', 'hard-of-hearing', 'hashtag', 'hdd-o', 'headphones', 'heart', 'heart-o', 'heartbeat', 'history', 'home', 'hotel', 'hourglass', 'hourglass-1', 'hourglass-2', 'hourglass-3', 'hourglass-end', 'hourglass-half', 'hourglass-o', 'hourglass-start', 'i-cursor', 'id-badge', 'id-card', 'id-card-o', 'image', 'inbox', 'industry', 'info', 'info-circle', 'institution', 'key', 'keyboard-o', 'language', 'laptop', 'leaf', 'legal', 'lemon-o', 'level-down', 'level-up', 'life-bouy', 'life-buoy', 'life-ring', 'life-saver', 'lightbulb-o', 'line-chart', 'location-arrow', 'lock', 'low-vision', 'magic', 'magnet', 'mail-forward', 'mail-reply', 'mail-reply-all', 'male', 'map', 'map-marker', 'map-o', 'map-pin', 'map-signs', 'meh-o', 'microchip', 'microphone', 'microphone-slash', 'minus', 'minus-circle', 'minus-square', 'minus-square-o', 'mobile', 'mobile-phone', 'money', 'moon-o', 'mortar-board', 'motorcycle', 'mouse-pointer', 'music', 'navicon', 'newspaper-o', 'object-group', 'object-ungroup', 'paint-brush', 'paper-plane', 'paper-plane-o', 'paw', 'pencil', 'pencil-square', 'pencil-square-o', 'percent', 'phone', 'phone-square', 'photo', 'picture-o', 'pie-chart', 'plane', 'plug', 'plus', 'plus-circle', 'plus-square', 'plus-square-o', 'podcast', 'power-off', 'print', 'puzzle-piece', 'qrcode', 'question', 'question-circle', 'question-circle-o', 'quote-left', 'quote-right', 'random', 'recycle', 'refresh', 'registered', 'remove', 'reorder', 'reply', 'reply-all', 'retweet', 'road', 'rocket', 'rss', 'rss-square', 's15', 'search', 'search-minus', 'search-plus', 'send', 'send-o', 'server', 'share', 'share-alt', 'share-alt-square', 'share-square', 'share-square-o', 'shield', 'ship', 'shopping-bag', 'shopping-basket', 'shopping-cart', 'shower', 'sign-in', 'sign-language', 'sign-out', 'signal', 'signing', 'sitemap', 'sliders', 'smile-o', 'snowflake-o', 'soccer-ball-o', 'sort', 'sort-alpha-asc', 'sort-alpha-desc', 'sort-amount-asc', 'sort-amount-desc', 'sort-asc', 'sort-desc', 'sort-down', 'sort-numeric-asc', 'sort-numeric-desc', 'sort-up', 'space-shuttle', 'spinner', 'spoon', 'square', 'square-o', 'star', 'star-half', 'star-half-empty', 'star-half-full', 'star-half-o', 'star-o', 'sticky-note', 'sticky-note-o', 'street-view', 'suitcase', 'sun-o', 'support', 'tablet', 'tachometer', 'tag', 'tags', 'tasks', 'taxi', 'television', 'terminal', 'thermometer', 'thermometer-0', 'thermometer-1', 'thermometer-2', 'thermometer-3', 'thermometer-4', 'thermometer-empty', 'thermometer-full', 'thermometer-half', 'thermometer-quarter', 'thermometer-three-quarters', 'thumb-tack', 'thumbs-down', 'thumbs-o-down', 'thumbs-o-up', 'thumbs-up', 'ticket', 'times', 'times-circle', 'times-circle-o', 'times-rectangle', 'times-rectangle-o', 'tint', 'toggle-down', 'toggle-left', 'toggle-off', 'toggle-on', 'toggle-right', 'toggle-up', 'trademark', 'trash', 'trash-o', 'tree', 'trophy', 'truck', 'tty', 'tv', 'umbrella', 'universal-access', 'university', 'unlock', 'unlock-alt', 'unsorted', 'upload', 'user', 'user-circle', 'user-circle-o', 'user-o', 'user-plus', 'user-secret', 'user-times', 'users', 'vcard', 'vcard-o', 'video-camera', 'volume-control-phone', 'volume-down', 'volume-off', 'volume-up', 'warning', 'wheelchair', 'wheelchair-alt', 'wifi', 'window-close', 'window-close-o', 'window-maximize', 'window-minimize', 'window-restore', 'wrench'
        ),
        __( 'Accessibility Icons', MTS_THEME_TEXTDOMAIN ) => array(
            'american-sign-language-interpreting', 'asl-interpreting', 'assistive-listening-systems', 'audio-description', 'blind', 'braille', 'cc', 'deaf', 'deafness', 'hard-of-hearing', 'low-vision', 'question-circle-o', 'sign-language', 'signing', 'tty', 'universal-access', 'volume-control-phone', 'wheelchair', 'wheelchair-alt'
        ),
        __( 'Hand Icons', MTS_THEME_TEXTDOMAIN ) => array(
            'hand-grab-o', 'hand-lizard-o', 'hand-o-down', 'hand-o-left', 'hand-o-right', 'hand-o-up', 'hand-paper-o', 'hand-peace-o', 'hand-pointer-o', 'hand-rock-o', 'hand-scissors-o', 'hand-spock-o', 'hand-stop-o', 'thumbs-down', 'thumbs-o-down', 'thumbs-o-up', 'thumbs-up'
        ),
        __( 'Transportation Icons', MTS_THEME_TEXTDOMAIN ) => array(
            'ambulance', 'automobile', 'bicycle', 'bus', 'cab', 'car', 'fighter-jet', 'motorcycle', 'plane', 'rocket', 'ship', 'space-shuttle', 'subway', 'taxi', 'train', 'truck', 'wheelchair', 'wheelchair-alt'
        ),
        __( 'Gender Icons', MTS_THEME_TEXTDOMAIN ) => array(
            'genderless', 'intersex', 'mars', 'mars-double', 'mars-stroke', 'mars-stroke-h', 'mars-stroke-v', 'mercury', 'neuter', 'transgender', 'transgender-alt', 'venus', 'venus-double', 'venus-mars'
        ),
        __( 'File Type Icons', MTS_THEME_TEXTDOMAIN ) => array(
            'file', 'file-archive-o', 'file-audio-o', 'file-code-o', 'file-excel-o', 'file-image-o', 'file-movie-o', 'file-o', 'file-pdf-o', 'file-photo-o', 'file-picture-o', 'file-powerpoint-o', 'file-sound-o', 'file-text', 'file-text-o', 'file-video-o', 'file-word-o', 'file-zip-o'
        ),
        __( 'Spinner Icons', MTS_THEME_TEXTDOMAIN ) => array(
            'circle-o-notch', 'cog', 'gear', 'refresh', 'spinner'
        ),
        __( 'Form Control Icons', MTS_THEME_TEXTDOMAIN ) => array(
            'check-square', 'check-square-o', 'circle', 'circle-o', 'dot-circle-o', 'minus-square', 'minus-square-o', 'plus-square', 'plus-square-o', 'square', 'square-o'
        ),
        __( 'Payment Icons', MTS_THEME_TEXTDOMAIN ) => array(
            'cc-amex', 'cc-diners-club', 'cc-discover', 'cc-jcb', 'cc-mastercard', 'cc-paypal', 'cc-stripe', 'cc-visa', 'credit-card', 'credit-card-alt', 'google-wallet', 'paypal'
        ),
        __( 'Chart Icons', MTS_THEME_TEXTDOMAIN ) => array(
            'area-chart', 'bar-chart', 'bar-chart-o', 'line-chart', 'pie-chart'
        ),
        __( 'Currency Icons', MTS_THEME_TEXTDOMAIN ) => array(
            'bitcoin', 'btc', 'cny', 'dollar', 'eur', 'euro', 'gbp', 'gg', 'gg-circle', 'ils', 'inr', 'jpy', 'krw', 'money', 'rmb', 'rouble', 'rub', 'ruble', 'rupee', 'shekel', 'sheqel', 'try', 'turkish-lira', 'usd', 'won', 'yen'
        ),
        __( 'Text Editor Icons', MTS_THEME_TEXTDOMAIN ) => array(
            'align-center', 'align-justify', 'align-left', 'align-right', 'bold', 'chain', 'chain-broken', 'clipboard', 'columns', 'copy', 'cut', 'dedent', 'eraser', 'file', 'file-o', 'file-text', 'file-text-o', 'files-o', 'floppy-o', 'font', 'header', 'indent', 'italic', 'link', 'list', 'list-alt', 'list-ol', 'list-ul', 'outdent', 'paperclip', 'paragraph', 'paste', 'repeat', 'rotate-left', 'rotate-right', 'save', 'scissors', 'strikethrough', 'subscript', 'superscript', 'table', 'text-height', 'text-width', 'th', 'th-large', 'th-list', 'underline', 'undo', 'unlink'
        ),
        __( 'Directional Icons', MTS_THEME_TEXTDOMAIN ) => array(
            'angle-double-down', 'angle-double-left', 'angle-double-right', 'angle-double-up', 'angle-down', 'angle-left', 'angle-right', 'angle-up', 'arrow-circle-down', 'arrow-circle-left', 'arrow-circle-o-down', 'arrow-circle-o-left', 'arrow-circle-o-right', 'arrow-circle-o-up', 'arrow-circle-right', 'arrow-circle-up', 'arrow-down', 'arrow-left', 'arrow-right', 'arrow-up', 'arrows', 'arrows-alt', 'arrows-h', 'arrows-v', 'caret-down', 'caret-left', 'caret-right', 'caret-square-o-down', 'caret-square-o-left', 'caret-square-o-right', 'caret-square-o-up', 'caret-up', 'chevron-circle-down', 'chevron-circle-left', 'chevron-circle-right', 'chevron-circle-up', 'chevron-down', 'chevron-left', 'chevron-right', 'chevron-up', 'exchange', 'hand-o-down', 'hand-o-left', 'hand-o-right', 'hand-o-up', 'long-arrow-down', 'long-arrow-left', 'long-arrow-right', 'long-arrow-up', 'toggle-down', 'toggle-left', 'toggle-right', 'toggle-up'
        ),
        __( 'Video Player Icons', MTS_THEME_TEXTDOMAIN ) => array(
            'arrows-alt', 'backward', 'compress', 'eject', 'expand', 'fast-backward', 'fast-forward', 'forward', 'pause', 'pause-circle', 'pause-circle-o', 'play', 'play-circle', 'play-circle-o', 'random', 'step-backward', 'step-forward', 'stop', 'stop-circle', 'stop-circle-o', 'youtube-play'
        ),
        __( 'Brand Icons', MTS_THEME_TEXTDOMAIN ) => array(
            '500px', 'adn', 'amazon', 'android', 'angellist', 'apple', 'bandcamp', 'behance', 'behance-square', 'bitbucket', 'bitbucket-square', 'bitcoin', 'black-tie', 'bluetooth', 'bluetooth-b', 'btc', 'buysellads', 'cc-amex', 'cc-diners-club', 'cc-discover', 'cc-jcb', 'cc-mastercard', 'cc-paypal', 'cc-stripe', 'cc-visa', 'chrome', 'codepen', 'codiepie', 'connectdevelop', 'contao', 'css3', 'dashcube', 'delicious', 'deviantart', 'digg', 'dribbble', 'dropbox', 'drupal', 'edge', 'eercast', 'empire', 'envira', 'etsy', 'expeditedssl', 'fa', 'facebook', 'facebook-f', 'facebook-official', 'facebook-square', 'firefox', 'first-order', 'flickr', 'font-awesome', 'fonticons', 'fort-awesome', 'forumbee', 'foursquare', 'free-code-camp', 'ge', 'get-pocket', 'gg', 'gg-circle', 'git', 'git-square', 'github', 'github-alt', 'github-square', 'gitlab', 'gittip', 'glide', 'glide-g', 'google', 'google-plus', 'google-plus-circle', 'google-plus-official', 'google-plus-square', 'google-wallet', 'gratipay', 'grav', 'hacker-news', 'houzz', 'html5', 'imdb', 'instagram', 'internet-explorer', 'ioxhost', 'joomla', 'jsfiddle', 'lastfm', 'lastfm-square', 'leanpub', 'linkedin', 'linkedin-square', 'linode', 'linux', 'maxcdn', 'meanpath', 'medium', 'meetup', 'mixcloud', 'modx', 'odnoklassniki', 'odnoklassniki-square', 'opencart', 'openid', 'opera', 'optin-monster', 'pagelines', 'paypal', 'pied-piper', 'pied-piper-alt', 'pied-piper-pp', 'pinterest', 'pinterest-p', 'pinterest-square', 'product-hunt', 'qq', 'quora', 'ra', 'ravelry', 'rebel', 'reddit', 'reddit-alien', 'reddit-square', 'renren', 'resistance', 'safari', 'scribd', 'sellsy', 'share-alt', 'share-alt-square', 'shirtsinbulk', 'simplybuilt', 'skyatlas', 'skype', 'slack', 'slideshare', 'snapchat', 'snapchat-ghost', 'snapchat-square', 'soundcloud', 'spotify', 'stack-exchange', 'stack-overflow', 'steam', 'steam-square', 'stumbleupon', 'stumbleupon-circle', 'superpowers', 'telegram', 'tencent-weibo', 'themeisle', 'trello', 'tripadvisor', 'tumblr', 'tumblr-square', 'twitch', 'twitter', 'twitter-square', 'usb', 'viacoin', 'viadeo', 'viadeo-square', 'vimeo', 'vimeo-square', 'vine', 'vk', 'wechat', 'weibo', 'weixin', 'whatsapp', 'wikipedia-w', 'windows', 'wordpress', 'wpbeginner', 'wpexplorer', 'wpforms', 'xing', 'xing-square', 'y-combinator', 'y-combinator-square', 'yahoo', 'yc', 'yc-square', 'yelp', 'yoast', 'youtube', 'youtube-play', 'youtube-square'
        ),
        __( 'Medical Icons', MTS_THEME_TEXTDOMAIN ) => array(
            'ambulance', 'h-square', 'heart', 'heart-o', 'heartbeat', 'hospital-o', 'medkit', 'plus-square', 'stethoscope', 'user-md', 'wheelchair', 'wheelchair-alt'
        )
    );

    return $icons;
}
/*-----------------------------------------------------------------------------------*/
/*  Create pages on Theme Activation
/*-----------------------------------------------------------------------------------*/
if ( isset( $_GET['activated'] ) && is_admin() ) {
    $pages_to_add = array(
        array(
            'post_type' => 'page',
            'post_title' => __('Blog', MTS_THEME_TEXTDOMAIN),
            'post_content' => '',
            'page_template' => 'page-blog.php',
            'post_status' => 'publish',
            'post_author' => 1,
        ),
        array(
            'post_type' => 'page',
            'post_title' => __('Wishlist', MTS_THEME_TEXTDOMAIN),
            'post_content' => '',
            'page_template' => 'page-wishlist.php',
            'post_status' => 'publish',
            'post_author' => 1,
        ),
        array(
            'post_type' => 'page',
            'post_title' => __('Deals', MTS_THEME_TEXTDOMAIN),
            'post_content' => '',
            'page_template' => 'page-deals.php',
            'post_status' => 'publish',
            'post_author' => 1,
        )
    );
    foreach ( $pages_to_add as $page_settings ) {
        $page_check = get_page_by_title( $page_settings['post_title'] );
        if ( !isset( $page_check->ID ) ) {
            $new_page_id = wp_insert_post( $page_settings );
            if ( !empty( $page_settings['page_template'] ) ) {
                update_post_meta( $new_page_id, '_wp_page_template', $page_settings['page_template'] );
            }
            $page_id = $new_page_id;
        } else {
            $page_id = $page_check->ID;
        }
    }
}
/**
 * Get the current post's thumbnail URL.
 *
 * @param string $size
 *
 * @return string
 */
function mts_get_thumbnail_url( $size = 'full' ) {
    $post_id = get_the_ID() ;
    if (has_post_thumbnail( $post_id ) ) {
        $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), $size );
        return $image[0];
    }

    // use first attached image
    $images = get_children( 'post_type=attachment&post_mime_type=image&post_parent=' . $post_id );
    if (!empty($images)) {
        $image = reset($images);
        $image_data = wp_get_attachment_image_src( $image->ID, $size );
        return $image_data[0];
    }

    // use no preview fallback
    if ( file_exists( get_template_directory().'/images/nothumb-'.$size.'.png' ) ) {
        return get_template_directory_uri().'/images/nothumb-'.$size.'.png';
    }
    return '';
}
/**
 * Create and show column for featured in portfolio items list admin page.
 * @param $post_ID
 *
 * @return string url
 */
function mts_get_featured_image($post_ID) {
    $post_thumbnail_id = get_post_thumbnail_id($post_ID);
    if ($post_thumbnail_id) {
        $post_thumbnail_img = wp_get_attachment_image_src($post_thumbnail_id, 'widgetfull');
        return $post_thumbnail_img[0];
    }
}
/**
 * Adds a `Featured Image` column header in the item list admin page.
 *
 * @param array $defaults
 *
 * @return array
 */
function mts_columns_head($defaults) {
    if (get_post_type() == 'post') {
        $defaults['featured_image'] = __('Featured Image', MTS_THEME_TEXTDOMAIN );
    }
    return $defaults;
}
add_filter('manage_posts_columns', 'mts_columns_head');
/**
 * Adds a `Featured Image` column row value in the item list admin page.
 *
 * @param string $column_name The name of the column to display.
 * @param int $post_ID The ID of the current post.
 */
function mts_columns_content($column_name, $post_ID) {
    if ($column_name == 'featured_image') {
        $post_featured_image = mts_get_featured_image($post_ID);
        if ($post_featured_image) {
            echo '<img width="150" height="100" src="' . esc_url( $post_featured_image ) . '" />';
        }
    }
}
add_action('manage_posts_custom_column', 'mts_columns_content', 10, 2);
/**
 * Change the HTML markup of the post thumbnail.
 *
 * @param string $html
 * @param int $post_id
 * @param string $post_image_id
 * @param int $size
 * @param string $attr
 *
 * @return string
 */
function mts_post_image_html( $html, $post_id, $post_image_id, $size, $attr ) {
    if ( has_post_thumbnail( $post_id ) || 'shop_thumbnail' === $size )
        return $html;

    // use first attached image
    $images = get_children( 'post_type=attachment&post_mime_type=image&post_parent=' . $post_id );
    if (!empty($images)) {
        $image = reset($images);
        return wp_get_attachment_image( $image->ID, $size, false, $attr );
    }
    // use no preview fallback
    if ( file_exists( get_template_directory().'/images/nothumb-'.$size.'.png' ) ) {
        $placeholder = get_template_directory_uri().'/images/nothumb-'.$size.'.png';
        $mts_options = get_option( MTS_THEME_NAME );
        if ( ! empty( $mts_options['mts_lazy_load'] ) && ! empty( $mts_options['mts_lazy_load_thumbs'] ) ) {
            $placeholder_src = '';
            $layzr_attr = ' data-layzr="'.esc_attr( $placeholder ).'"';
        } else {
            $placeholder_src = $placeholder;
            $layzr_attr = '';
        }

        $placeholder_classs = 'attachment-'.$size.' wp-post-image';
        return '<img src="'.esc_url( $placeholder_src ).'" class="'.esc_attr( $placeholder_classs ).'" alt="'.esc_attr( get_the_title() ).'"'.$layzr_attr.'>';
    }
    return '';
}
add_filter( 'post_thumbnail_html', 'mts_post_image_html', 10, 5 );
/**
 * Add data-layzr attribute to featured image ( for lazy load )
 *
 * @param array $attr
 * @param WP_Post $attachment
 * @param string|array $size
 *
 * @return array
 */
function mts_image_lazy_load_attr( $attr, $attachment, $size ) {
    if ( is_admin() || is_feed() ) return $attr;
    if ( is_single() && function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) return $attr;
    $mts_options = get_option( MTS_THEME_NAME );
    if ( ! empty( $mts_options['mts_lazy_load'] ) && ! empty( $mts_options['mts_lazy_load_thumbs'] ) ) {
        $attr['data-layzr'] = $attr['src'];
        $attr['src'] = '';
    }
    return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'mts_image_lazy_load_attr', 10, 3 );
/**
 * Add data-layzr attribute to post content images ( for lazy load )
 *
 * @param string $content
 *
 * @return string
 */
function mts_content_image_lazy_load_attr( $content ) {
    if ( is_single() && function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) {
        return $content;
    }
    $mts_options = get_option( MTS_THEME_NAME );
    if ( ! empty( $mts_options['mts_lazy_load'] ) && ! empty( $mts_options['mts_lazy_load_content'] ) ) {
        $dom = new DOMDocument();
        if (function_exists('mb_convert_encoding')) {
            @$dom->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'));
        } else {
            //@$dom->loadHTML($content); // this might cause character encoding problems
            return $content; // abort instead, no lazy load support :(
        }
        foreach ( $dom->getElementsByTagName('img') as $node ) {
            $oldsrc = $node->getAttribute('src');
            $node->setAttribute("data-layzr", $oldsrc );
            $newsrc = '';
            $node->setAttribute("src", $newsrc );
        }
        $newHtml = $dom->saveHtml();
        return $newHtml;
    }
    return $content;
}
add_filter('the_content', 'mts_content_image_lazy_load_attr');
/*-----------------------------------------------------------------------------------*/
/*  Post Formats Support
/*-----------------------------------------------------------------------------------*/
add_theme_support( 'post-formats', array( 'video' ) );
/*-----------------------------------------------------------------------------------*/
/*  Enable Widgetized sidebar and Footer
/*-----------------------------------------------------------------------------------*/
if ( function_exists( 'register_sidebar' ) ) {
    function mts_register_sidebars() {
        $mts_options = get_option( MTS_THEME_NAME );
        // Default sidebar
        register_sidebar( array(
            'name' => __('Sidebar', MTS_THEME_TEXTDOMAIN),
            'description'   => __( 'Default sidebar.', MTS_THEME_TEXTDOMAIN ),
            'id' => 'sidebar',
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ) );
        // Top level footer widget areas
        if ( !empty( $mts_options['mts_first_footer'] )) {
            if ( empty( $mts_options['mts_first_footer_num'] )) $mts_options['mts_first_footer_num'] = 4;
            register_sidebars( $mts_options['mts_first_footer_num'], array(
                'name' => __( 'First Footer %d', MTS_THEME_TEXTDOMAIN ),
                'description'   => __( 'Appears at the top of the footer.', MTS_THEME_TEXTDOMAIN ),
                'id' => 'footer-first',
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<h3 class="widget-title">',
                'after_title' => '</h3>',
            ) );
        }
        // Custom sidebars
        if ( !empty( $mts_options['mts_custom_sidebars'] ) && is_array( $mts_options['mts_custom_sidebars'] )) {
            foreach( $mts_options['mts_custom_sidebars'] as $sidebar ) {
                if ( !empty( $sidebar['mts_custom_sidebar_id'] ) && !empty( $sidebar['mts_custom_sidebar_id'] ) && $sidebar['mts_custom_sidebar_id'] != 'sidebar-' ) {
                    register_sidebar( array( 'name' => ''.$sidebar['mts_custom_sidebar_name'].'', 'id' => ''.sanitize_title( strtolower( $sidebar['mts_custom_sidebar_id'] )).'', 'before_widget' => '<div id="%1$s" class="widget %2$s">', 'after_widget' => '</div>', 'before_title' => '<h3>', 'after_title' => '</h3>' ));
                }
            }
        }
        if ( mts_isWooCommerce() ) {
            // Register WooCommerce Shop and Single Product Sidebar
            register_sidebar( array(
                'name' => __('Shop Page Sidebar', MTS_THEME_TEXTDOMAIN ),
                'description'   => __( 'Appears on Shop main page and product archive pages.', MTS_THEME_TEXTDOMAIN ),
                'id' => 'shop-sidebar',
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<h3 class="widget-title">',
                'after_title' => '</h3>',
            ) );
            register_sidebar( array(
                'name' => __('Single Product Sidebar', MTS_THEME_TEXTDOMAIN ),
                'description'   => __( 'Appears on single product pages.', MTS_THEME_TEXTDOMAIN ),
                'id' => 'product-sidebar',
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<h3 class="widget-title">',
                'after_title' => '</h3>',
            ) );
            // Widget areas above product listings
            register_sidebar( array(
                'name' => __('Shop Page Ads',MTS_THEME_TEXTDOMAIN),
                'id' => 'shop-ads',
                'description'   => __( 'Appears on the Shop page, above the products listing.', MTS_THEME_TEXTDOMAIN ),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<h3>',
                'after_title' => '</h3>'
            ) );
            register_sidebar( array(
                'name' => __('Catalog Pages Ads',MTS_THEME_TEXTDOMAIN),
                'id' => 'catalog-ads',
                'description'   => __( 'Appears on product category archives, above the products listing.', MTS_THEME_TEXTDOMAIN ),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<h3>',
                'after_title' => '</h3>'
            ) );
        }
        register_sidebar( array(
            'name' => __('Homepage Banners',MTS_THEME_TEXTDOMAIN),
            'id' => 'homepage-banners',
            'description'   => __( 'Homepage Banners section ads.', MTS_THEME_TEXTDOMAIN ),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3>',
            'after_title' => '</h3>'
        ) );
        register_sidebar( array(
            'name' => __('Homepage Lookbook',MTS_THEME_TEXTDOMAIN),
            'id' => 'homepage-lookbook',
            'description'   => __( 'Homepage Lookbook section widget.', MTS_THEME_TEXTDOMAIN ),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3>',
            'after_title' => '</h3>'
        ) );
        register_sidebar( array(
            'name' => __('Homepage Offers',MTS_THEME_TEXTDOMAIN),
            'id' => 'homepage-offers',
            'description'   => __( 'Homepage Offers section widget.', MTS_THEME_TEXTDOMAIN ),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3>',
            'after_title' => '</h3>'
        ) );
        register_sidebar( array(
            'name' => __('Deals Page Banners',MTS_THEME_TEXTDOMAIN),
            'id' => 'deals-banners',
            'description'   => __( 'Deals Page template ads.', MTS_THEME_TEXTDOMAIN ),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3>',
            'after_title' => '</h3>'
        ) );
    }
    add_action( 'widgets_init', 'mts_register_sidebars' );
    function mts_register_extra_sidebars() {
        $mts_options = get_option( MTS_THEME_NAME );
        if (!empty($mts_options['mts_category_ad_widgets_enabled']) && !empty($mts_options['mts_category_ad_widgets'])) {
            foreach( $mts_options['mts_category_ad_widgets'] as $category_id => $v ) {
                $term = get_term($category_id, 'product_cat');
                register_sidebar( array(
                    'name' => $term->name. __(' Catalog Ads',MTS_THEME_TEXTDOMAIN),
                    'description'   => __( 'Appears on specific product category archive.', MTS_THEME_TEXTDOMAIN ),
                    'id' => 'catalog-ads-'.$category_id,
                    'before_widget' => '<div id="%1$s" class="widget %2$s">',
                    'after_widget' => '</div>',
                    'before_title' => '<h3>',
                    'after_title' => '</h3>'
                ) );
            }
        }
    }
    add_action( 'init', 'mts_register_extra_sidebars', 10 ); // can't run this on 'widgets_init' :/
}
function mts_get_excluded_sidebars() {
    $mts_options = get_option( MTS_THEME_NAME );
    $list = array();
    if (!empty( $mts_options['mts_category_ad_widgets'] )) {
        foreach( $mts_options['mts_category_ad_widgets'] as $product_cat_id => $v ) {
            $list[] = 'catalog-ads-'.$product_cat_id;
        }
    }
    return array_merge( $list, array('sidebar', 'footer-first', 'footer-first-2', 'footer-first-3', 'footer-first-4', 'footer-first-5', 'footer-second', 'footer-second-2', 'footer-second-3', 'footer-second-4', 'widget-header','shop-sidebar', 'product-sidebar', 'shop-ads', 'catalog-ads', 'homepage-banners', 'homepage-lookbook', 'homepage-offers', 'deals-banners') );
}
function mts_custom_sidebar() {
    $mts_options = get_option( MTS_THEME_NAME );
    // Default sidebar
    $sidebar = 'sidebar';
    //if ( is_home() && !empty( $mts_options['mts_sidebar_for_home'] )) $sidebar = $mts_options['mts_sidebar_for_home'];
    if ( is_single() && !empty( $mts_options['mts_sidebar_for_post'] )) $sidebar = $mts_options['mts_sidebar_for_post'];
    if ( is_page() && !empty( $mts_options['mts_sidebar_for_page'] )) $sidebar = $mts_options['mts_sidebar_for_page'];
    // Archives
    if ( is_archive() && !empty( $mts_options['mts_sidebar_for_archive'] )) $sidebar = $mts_options['mts_sidebar_for_archive'];
    if ( is_category() && !empty( $mts_options['mts_sidebar_for_category'] )) $sidebar = $mts_options['mts_sidebar_for_category'];
    if ( is_tag() && !empty( $mts_options['mts_sidebar_for_tag'] )) $sidebar = $mts_options['mts_sidebar_for_tag'];
    if ( is_date() && !empty( $mts_options['mts_sidebar_for_date'] )) $sidebar = $mts_options['mts_sidebar_for_date'];
    if ( is_author() && !empty( $mts_options['mts_sidebar_for_author'] )) $sidebar = $mts_options['mts_sidebar_for_author'];
    // Other
    if ( is_search() && !empty( $mts_options['mts_sidebar_for_search'] )) $sidebar = $mts_options['mts_sidebar_for_search'];
    if ( is_404() && !empty( $mts_options['mts_sidebar_for_notfound'] )) $sidebar = $mts_options['mts_sidebar_for_notfound'];

    // Woo
    if ( mts_isWooCommerce() ) {
        if ( is_shop() || is_product_taxonomy() ) {
            if ( !empty( $mts_options['mts_sidebar_for_shop'] ))
                $sidebar = $mts_options['mts_sidebar_for_shop'];
            else
                $sidebar = 'shop-sidebar'; // default
        }
        if ( is_product() || is_cart() || is_checkout() || is_account_page() ) {
            if ( !empty( $mts_options['mts_sidebar_for_product'] ))
                $sidebar = $mts_options['mts_sidebar_for_product'];
            else
                $sidebar = 'product-sidebar'; // default
        }
    }

    // Page/post specific custom sidebar
    if ( is_page() || is_single() ) {
        wp_reset_postdata();
        global $post, $wp_registered_sidebars;
        $custom = get_post_meta( $post->ID, '_mts_custom_sidebar', true );
        if ( !empty( $custom ) && array_key_exists( $custom, $wp_registered_sidebars ) || 'mts_nosidebar' == $custom ) $sidebar = $custom;
    }

    // Posts page
    if ( is_home() && ! is_front_page() && 'page' == get_option( 'show_on_front' ) ) {
        wp_reset_postdata();
        global $wp_registered_sidebars;
        $custom = get_post_meta( get_option( 'page_for_posts' ), '_mts_custom_sidebar', true );
        if ( !empty( $custom ) && array_key_exists( $custom, $wp_registered_sidebars ) || 'mts_nosidebar' == $custom ) {
            $sidebar = $custom;
        }
    }

    return apply_filters( 'mts_custom_sidebar', $sidebar );
}
/*-----------------------------------------------------------------------------------*/
/*  Load Widgets, Actions and Libraries
/*-----------------------------------------------------------------------------------*/

// Add the Ad Block Custom Widget
include_once( get_theme_file_path( "functions/widget-ad.php" ) );
// Add the 125x125 Ad Block Custom Widget
include_once( get_theme_file_path( "functions/widget-ad125.php" ) );

// Add the 300x250 Ad Block Custom Widget.
include_once( get_theme_file_path( "functions/widget-ad300.php" ) );

// Add the 728x90 Ad Block Custom Widget.
include_once( get_theme_file_path( "functions/widget-ad728.php" ) );

// Add the Latest Tweets Custom Widget.
include_once( get_theme_file_path( "functions/widget-tweets.php" ) );

// Add Recent Posts Widget.
include_once( get_theme_file_path( "functions/widget-recentposts.php" ) );

// Add Related Posts Widget.
include_once( get_theme_file_path( "functions/widget-relatedposts.php" ) );

// Add Author Posts Widget.
include_once( get_theme_file_path( "functions/widget-authorposts.php" ) );

// Add Popular Posts Widget.
include_once( get_theme_file_path( "functions/widget-popular.php" ) );

// Add Facebook Like box Widget.
include_once( get_theme_file_path( "functions/widget-fblikebox.php" ) );

// Add Social Profile Widget.
include_once( get_theme_file_path( "functions/widget-social.php" ) );

// Add Category Posts Widget.
include_once( get_theme_file_path( "functions/widget-catposts.php" ) );

// Add Category Posts Widget.
include_once( get_theme_file_path( "functions/widget-postslider.php" ) );

if ( mts_isWooCommerce() ) {
    // Add Product Slider Widget
    include_once( get_theme_file_path( "functions/widget-productslider.php" ) );
    // Add Ajax filter widget
    include_once( get_theme_file_path( "functions/widget-ajax-layered-nav.php" ) );
}
// Add Adcode Widget.
include_once( get_theme_file_path( "functions/widget-adcode.php" ) );

// Add Welcome message.
include_once( get_theme_file_path( "functions/welcome-message.php" ) );

// Template Functions.
include_once( get_theme_file_path( "functions/theme-actions.php" ) );

// Post/page editor meta boxes.
include_once( get_theme_file_path( "functions/metaboxes.php" ) );

// TGM Plugin Activation.
include_once( get_theme_file_path( "functions/plugin-activation.php" ) );

// AJAX Contact Form - `mts_contact_form()`.
include_once( get_theme_file_path( 'functions/contact-form.php' ) );

// Custom menu walker.
include_once( get_theme_file_path( 'functions/nav-menu.php' ) );

// Rank Math SEO.
include_once( get_theme_file_path( 'functions/rank-math-notice.php' ) );

/*-----------------------------------------------------------------------------------*/
/* RTL
/*-----------------------------------------------------------------------------------*/
if ( ! empty( $mts_options['mts_rtl'] ) ) {
	/**
	 * RTL language support
	 *
	 * @see mts_load_footer_scripts()
	 */
	function mts_rtl() {
		if ( is_admin() ) {
			return;
		}
		global $wp_locale, $wp_styles;
		$wp_locale->text_direction = 'rtl';
		if ( ! is_a( $wp_styles, 'WP_Styles' ) ) {
			$wp_styles = new WP_Styles();
			$wp_styles->text_direction = 'rtl';
		}
	}
	add_action( 'init', 'mts_rtl' );
}

/*-----------------------------------------------------------------------------------*/
/*  Javascript
/*-----------------------------------------------------------------------------------*/
function mts_nojs_js_class() {
    echo '<script type="text/javascript">document.documentElement.className = document.documentElement.className.replace( /\bno-js\b/,\'js\' );</script>';
}
add_action( 'wp_head', 'mts_nojs_js_class', 1 );

function mts_add_scripts() {
    $mts_options = get_option( MTS_THEME_NAME );

    wp_enqueue_script( 'jquery' );

    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }

    wp_register_script( 'customscript', get_template_directory_uri() . '/js/customscript.js', array( 'jquery' ), MTS_THEME_VERSION, true );
    if ( ! empty( $mts_options['mts_show_primary_nav'] ) && ! empty( $mts_options['mts_show_secondary_nav'] ) ) {
        $nav_menu = 'both';
    } else {
        $nav_menu = 'none';

        if ( ! empty( $mts_options['mts_show_primary_nav'] ) ) {
            $nav_menu = 'primary';
        } elseif ( ! empty( $mts_options['mts_show_secondary_nav'] ) ) {
            $nav_menu = 'secondary';
        }
    }
    wp_localize_script(
        'customscript',
        'mts_customscript',
        array(
            'responsive' => ( empty( $mts_options['mts_responsive'] ) ? false : true ),
            'nav_menu' => $nav_menu
        )
    );
    wp_enqueue_script( 'customscript' );

    // Slider
    wp_enqueue_script('owl-carousel', get_template_directory_uri() . '/js/owl.carousel.min.js', array(), null, true);
    wp_localize_script('owl-carousel', 'slideropts', array( 'rtl_support' => $mts_options['mts_rtl'] ));

    // Animated single post/page header
    if ( is_singular() ) {
        $header_animation = mts_get_post_header_effect();
        if ( 'parallax' == $header_animation ) {
            wp_register_script ( 'jquery-parallax', get_template_directory_uri() . '/js/parallax.js' );
            wp_enqueue_script ( 'jquery-parallax' );
        } else if ( 'zoomout' == $header_animation ) {
            wp_register_script ( 'jquery-zoomout', get_template_directory_uri() . '/js/zoomout.js' );
            wp_enqueue_script ( 'jquery-zoomout' );
        }
    }
    // Ajax nav for widget
    wp_register_script( 'wc-ajax-attr-filters', get_template_directory_uri() . '/js/wc-ajax-attr-filters.js' );

}
add_action( 'wp_enqueue_scripts', 'mts_add_scripts' );

function mts_load_footer_scripts() {
    $mts_options = get_option( MTS_THEME_NAME );

    //Lightbox
    wp_register_script( 'magnificPopup', get_template_directory_uri() . '/js/jquery.magnific-popup.min.js', true );
    $wc_lightbox_enabled = 'no';
    if ( mts_isWooCommerce() ) {
        global $post;
        if ( 'yes' === get_option( 'woocommerce_enable_lightbox' ) && ( is_product() || ( ! empty( $post->post_content ) && strstr( $post->post_content, '[product_page' ) ) ) ) {
            $wc_lightbox_enabled = 'yes';
        }
        if ( version_compare( WC()->version, '3.0.0', ">=" ) ) {
            $wc_lightbox_enabled = 'no';
        }
    }
    wp_localize_script(
        'magnificPopup',
        'magnificPopupVars',
        array(
            'wc_lightbox'  => $wc_lightbox_enabled,
            'mts_lightbox' => ( ! empty( $mts_options['mts_lightbox'] ) ) ? 'yes' : 'no'
        )
    );
    if ( ! empty( $mts_options['mts_lightbox'] ) || is_home() || mts_isWooCommerce() ) {
        wp_enqueue_script( 'magnificPopup' );
    }

    //Sticky Nav
    if ( ! empty( $mts_options['mts_sticky_nav'] ) ) {
        wp_register_script( 'StickyNav', get_template_directory_uri() . '/js/sticky.js', true );
        wp_enqueue_script( 'StickyNav' );
    }
    // Lazy Load
    if ( ! empty( $mts_options['mts_lazy_load'] ) ) {
        if ( ! empty( $mts_options['mts_lazy_load_thumbs'] ) || ( ! empty( $mts_options['mts_lazy_load_content'] ) && is_singular() ) ) {
            wp_enqueue_script( 'layzr', get_template_directory_uri() . '/js/layzr.min.js', '', '', true );
        }
    }

    // Ajax Load More and Search Results
    wp_register_script( 'mts_cookie', get_template_directory_uri() . '/js/jquery.cookie.js', true );
    wp_register_script( 'mts_ajax', get_template_directory_uri() . '/js/ajax.js', true );
    if ( ! empty( $mts_options['mts_pagenavigation_type'] ) && $mts_options['mts_pagenavigation_type'] >= 2 && ( !is_singular() || is_page_template('page-blog.php')) ) {
        wp_enqueue_script( 'mts_ajax' );

        wp_register_script( 'historyjs', get_template_directory_uri() . '/js/history.js', true );
        wp_enqueue_script( 'historyjs' );

        // Add parameters for the JS
        // Add parameters for the JS
        global $wp_query;
        $max = $wp_query->max_num_pages;
        $paged = ( get_query_var('paged') > 1 ) ? get_query_var('paged') : 1;
        if ( $max == 0 ) {
            $my_query = new WP_Query(
                array(
                    'post_type' => 'post',
                    'post_status' => 'publish',
                    'paged' => $paged,
                    'ignore_sticky_posts'=> 1
                )
            );
            $max = $my_query->max_num_pages;
            wp_reset_query();
        }
        $autoload = ( $mts_options['mts_pagenavigation_type'] == 3 );
        wp_localize_script(
            'mts_ajax',
            'mts_ajax_loadposts',
            array(
                'startPage' => $paged,
                'maxPages' => $max,
                'nextLink' => next_posts( $max, false ),
                'autoLoad' => $autoload,
                'i18n_loadmore' => __( 'Load More Posts', MTS_THEME_TEXTDOMAIN ),
                'i18n_loading' => __('Loading...', MTS_THEME_TEXTDOMAIN ),
                'i18n_nomore' => __( 'No more posts.', MTS_THEME_TEXTDOMAIN )
             )
        );
    }
    if ( ! empty( $mts_options['mts_ajax_search'] ) ) {
        wp_enqueue_script( 'mts_ajax' );
        wp_localize_script(
            'mts_ajax',
            'mts_ajax_search',
            array(
                'url' => admin_url( 'admin-ajax.php' ),
                'ajax_search' => '1'
             )
        );
    }
    if ( mts_isWooCommerce() && isset( $mts_options['mts_wishlist'] ) && !empty( $mts_options['mts_wishlist'] ) ) {
        wp_enqueue_script( 'mts_cookie' );
        wp_enqueue_script( 'mts_ajax' );
        wp_localize_script(
            'mts_ajax',
            'mts_ajax_wishlist',
            array(
                'url' => admin_url( 'admin-ajax.php' ),
            )
        );
    }
    if ( mts_isWooCommerce() && is_home() ) {
        wp_enqueue_script('mts_ajax');
        wp_localize_script(
            'mts_ajax',
            'mts_ajax_tabs',
            array(
                //'mts_nonce' => wp_create_nonce( 'mts_nonce' ),
                'ajax_url' => admin_url( 'admin-ajax.php' ),
            )
        );
    }
    if ( mts_isWooCommerce() /*&& ( is_shop() || is_product_taxonomy() || is_cart() )*/ ) {
        wp_enqueue_script( 'magnificPopup' );
        wp_enqueue_script('mts_ajax');
        wp_localize_script(
            'mts_ajax',
            'mts_ajax_popup',
            array(
                'loading_text' => __( 'Loading...', MTS_THEME_TEXTDOMAIN ),
                'ajax_url' => admin_url( 'admin-ajax.php' ),
            )
        );
    }
}
add_action( 'wp_footer', 'mts_load_footer_scripts' );
/*-----------------------------------------------------------------------------------*/
/* Enqueue CSS
/*-----------------------------------------------------------------------------------*/
function mts_enqueue_css() {
    $mts_options = get_option( MTS_THEME_NAME );
    wp_enqueue_style( 'stylesheet', get_stylesheet_directory_uri() . '/style.css', 'style' );

    // Slider
    // also enqueued in slider widget
    wp_register_style('owl-carousel', get_template_directory_uri() . '/css/owl.carousel.css', array(), null);
    wp_enqueue_style('owl-carousel');

    $handle = 'stylesheet';
    // WooCommerce
    if ( mts_isWooCommerce() ) {
        if ( empty( $mts_options['mts_optimize_wc'] ) || ( ! empty( $mts_options['mts_optimize_wc'] ) ) ) {
            wp_enqueue_style( 'woocommerce', get_template_directory_uri() . '/css/woocommerce2.css' );
            $handle = 'woocommerce';
        }
    }

    // Lightbox
    wp_register_style( 'magnificPopup', get_template_directory_uri() . '/css/magnific-popup.css', 'style' );
    if ( ! empty( $mts_options['mts_lightbox'] ) || is_home() || mts_isWooCommerce() ) {
        wp_enqueue_style( 'magnificPopup' );
    }

    //Font Awesome
    wp_register_style( 'fontawesome', get_template_directory_uri() . '/css/font-awesome.min.css', 'style' );
    wp_enqueue_style( 'fontawesome' );

    //Responsive
    if ( ! empty( $mts_options['mts_responsive'] ) ) {
        wp_enqueue_style( 'responsive', get_template_directory_uri() . '/css/responsive.css', 'style' );
    }

    // RTL
    if ( ! empty( $mts_options['mts_rtl'] ) ) {
        wp_enqueue_style( 'mts_rtl', get_template_directory_uri() . '/css/rtl.css', 'style' );
    }

    /*$mts_bg = '';
    if ( $mts_options['mts_bg_pattern_upload'] != '' ) {
        $mts_bg = $mts_options['mts_bg_pattern_upload'];
    } else {
        if( !empty( $mts_options['mts_bg_pattern'] )) {
            $mts_bg = get_template_directory_uri().'/images/'.$mts_options['mts_bg_pattern'].'.png';
        }
    }*/
    $mts_sclayout = '';
    $mts_rightsidebar = '';
    $mts_shareit_left = '';
    $mts_shareit_right = '';
    $mts_author = '';
    $mts_header_section = '';
    if ( is_page() || is_single() ) {
        $mts_sidebar_location = get_post_meta( get_the_ID(), '_mts_sidebar_location', true );
    } elseif (function_exists('is_shop') && is_shop() || function_exists('is_product_category') && is_product_category()) {
        $mts_sidebar_location = get_post_meta( wc_get_page_id('shop'), '_mts_sidebar_location', true );
    } else {
        $mts_sidebar_location = '';
    }
    if ( $mts_sidebar_location != 'right' && ( $mts_options['mts_layout'] == 'sclayout' || $mts_sidebar_location == 'left' )) {
        $mts_sclayout = '.article, .blog-page .article { float: right;}
        .sidebar.c-4-12, .single-page .sidebar.c-4-12, .blog-page .sidebar.c-4-12, .single-page .sidebar.c-4-12 { float: left; padding-right: 0; }';
        if( isset( $mts_options['mts_social_button_position'] ) && $mts_options['mts_social_button_position'] == 'floating' ) {
            $mts_shareit_right = '.shareit { margin: 0 760px 0; border-left: 0; }';
        }
    }
    if ( $mts_sidebar_location == 'right' ) {
        $mts_rightsidebar = '.post-type-archive-product .article, .tax-product_cat .article { float: left; }
    .post-type-archive-product .sidebar.c-4-12, .tax-product_cat .sidebar.c-4-12 { float: right; }';
    }
    if ( empty( $mts_options['mts_header_section2'] ) ) {
        $mts_header_section = '.logo-wrap, .widget-header { display: none; }
        .navigation { border-top: 0; }
        #header { min-height: 47px; }';
    }
    if ( isset( $mts_options['mts_social_button_position'] ) && $mts_options['mts_social_button_position'] == 'floating' ) {
        $mts_shareit_left = '.shareit { top: 282px; left: auto; margin: 0 0 0 -130px; width: 90px; position: fixed; padding: 5px; border:none; border-right: 0;}
        .share-item {margin: 2px;}';
    }
    if ( ! empty( $mts_options['mts_author_comment'] ) ) {
        $mts_author = '.bypostauthor:after { content: "'.__( 'Author', MTS_THEME_TEXTDOMAIN ).'";   position: absolute; left: 9px; top: 85px; padding: 0px 10px; color: #FFF; font-size: 14px; line-height: 1.6; border-radius: 3px; }';
    }
    $mts_primary_nav_bgcolor = '';
    if ( !empty( $mts_options['mts_primary_nav_bgcolor'] ) ) {
        $mts_primary_nav_bgcolor .= "#site-header.header-3 #primary-navigation, #site-header.header-4 #primary-navigation{background:{$mts_options['mts_primary_nav_bgcolor']};}";
    }
    $mts_header_5 = '';
    if( $mts_options['mts_show_secondary_nav'] == 0 ) {
        $mts_header_5 .= '#site-header.header-5 { min-height: 112px; }';
    }
    $body_bg = mts_get_background_styles( 'mts_background' );
    $header_bg = mts_get_background_styles( 'mts_header_background' );
    $footer_bg = mts_get_background_styles( 'mts_footer_background' );
    $dark_pri_color = mts_darken_color( $mts_options['mts_color_scheme'], $amount = 10 );
    $custom_css = "
        body {{$body_bg}}
        #site-header{{$header_bg}}
        #site-footer{{$footer_bg}}
        /*first color*/
        #secondary-navigation li a, #secondary-navigation .sub-menu a, .sidebar .widget li a, .sidebar .widget li.active ul li a, .featured-product-tabs .tabs li a, button.morphsearch-input, .archive-main .medium-heading, .deals .offers-banners .small-post-7 .large-heading, .woocommerce .woocommerce-ordering .e-comm, .woocommerce-page .woocommerce-ordering .e-comm, .woocommerce .woocommerce-ordering .e-comm:after, .woocommerce-page .woocommerce-ordering .e-comm:after, .woocommerce .woocommerce-ordering .e-comm .dropdown li a, .woocommerce-page .woocommerce-ordering .e-comm .dropdown li a, .woocommerce .woocommerce-sorting a, .woocommerce-page .woocommerce-sorting a, .widget .ad-video .video .icon:hover, .mts-cart-button,
        .mts-cart-button .fa-angle-down, #site-header.header-6 .mts-cart-button .fa-shopping-cart, #secondary-navigation .sub-menu a,
        #site-header.header-6 .mts-cart-button-wrap:hover .mts-cart-button, .mts-wishlist-link, #site-header.header-6 .mts-wishlist-icon, #site-header.header-6 .mts-wishlist-link:hover, .woocommerce table.variations td.value select,
        .woocommerce table.shop_table td, .woocommerce table.shop_table tfoot td, .woocommerce table.shop_table tfoot th, .woocommerce .cart-actions table th, .woocommerce #payment ul.payment_methods li, #searchform .fa-search, .yith-woocompare-widget ul.products-list li a.title, .widget .wpt_widget_content .tab-content li a, .featured-blog-post .title a, .featured-category-title, .payment-content .icon, .postauthor h5 a, .ad-navigation .ad-options li .title { color: {$mts_options['mts_color_scheme']}; }

        .mts-cart-product a.remove, .woocommerce a.remove, .woocommerce table.cart a.remove, .woocommerce .cart-actions .coupon .button { color: {$mts_options['mts_color_scheme']}!important; }

        .sticky-navigation-active > .container, .readMore a, .blog-page .postauthor .readMore a, .custom-nav a, #commentform input#submit, .contact-form input[type='submit'], .pagination a, .e-comm.active:before, .woocommerce a.button, .woocommerce-page a.button, .woocommerce .button, .woocommerce-page .button, .woocommerce input.button, .woocommerce-page input.button, .woocommerce #respond input#submit, .woocommerce-page #respond input#submit, .woocommerce #content input.button, .woocommerce-page #content input.button, .woocommerce .widget_layered_nav ul small.count, .woocommerce-page .widget_layered_nav ul small.count, .woocommerce nav.woocommerce-pagination ul li a, .woocommerce-page nav.woocommerce-pagination ul li a, .woocommerce #content nav.woocommerce-pagination ul li a, .woocommerce-page #content nav.woocommerce-pagination ul li a, .woocommerce nav.woocommerce-pagination ul li span, .woocommerce-page nav.woocommerce-pagination ul li span, .woocommerce #content nav.woocommerce-pagination ul li span, .woocommerce-page #content nav.woocommerce-pagination ul li span, .mts-cart-button-wrap:hover .mts-cart-button, .mts-cart-icon, .mts-wishlist-icon, .mts-wishlist-link:hover, .woocommerce .cart-wishlist-button .mts-add-to-wishlist, .woocommerce .cart-actions .update-cart-button, .woocommerce .cart-actions .update-cart-button:hover, .woocommerce form .form-row.login input.button, .woocommerce form.register .form-row input.button, .product-hover .mts-add-to-wishlist, .woocommerce .product-hover .mts-add-to-wishlist, .widget-slider .slide-caption, .effect-apollo .product-wrap-inner, .effect-default .product-hover .mts-add-to-wishlist:hover, .effect-slideinbottom .product-wrap-inner, .effect-buttonscenter .product-wrap-inner, .effect-buttonscenter .product-hover::before, .img-wrap .variation-data .variation-attribute, .primary-slider .slide-caption .readMore, .primary-slider-container, .owl-prev, .owl-next, .woocommerce .widget_layered_nav > ul.mts-ajax-filter-type-label > li a, .pace .pace-progress { background: {$mts_options['mts_color_scheme']}; }

        .header-2 .ad-navigation, .header-3 .ad-navigation, .header-4 .ad-navigation, .header-2 #secondary-navigation .navigation ul ul, .header-2 #secondary-navigation .navigation ul ul li, .header-3 #secondary-navigation .navigation ul ul, .header-3 #secondary-navigation .navigation ul ul li, .header-4 #secondary-navigation .navigation ul ul, .header-4 #secondary-navigation .navigation ul ul li {
            background: #{$dark_pri_color};
        }

        .e-comm.active:before, .woocommerce .woocommerce-ordering .e-comm, .woocommerce-page .woocommerce-ordering .e-comm, .woocommerce .woocommerce-ordering .e-comm .dropdown, .woocommerce-page .woocommerce-ordering .e-comm .dropdown, .woocommerce .woocommerce-sorting a, .woocommerce-page .woocommerce-sorting a, .widget .ad-video .video .icon:hover, .mts-cart-button, .mts-wishlist-link, .woocommerce .widget_price_filter .ui-slider .ui-slider-handle, .primary-slider .slide-caption .readMore { border-color: {$mts_options['mts_color_scheme']}; }

        #site-header.header-5 button.morphsearch-input {
            background: {$mts_options['mts_color_scheme']}!important;
            border-color: {$mts_options['mts_color_scheme']}!important;
        }
        /**
        .mts-cart-icon, .mts-wishlist-icon {
            outline: 1px solid {$mts_options['mts_color_scheme']};
        }*/
        /*second color*/
        a, a:hover, #secondary-navigation li a:hover, .slider-widget-container .custom-products-controls a:hover, .slider-widget-container .custom-products-controls a:hover i, .blog-page .readMore a, .post-info a:hover, .latestPost .title a:hover, .widget .wp_review_tab_widget_content .tab-content li a:hover, .featured-blog-post .title a:hover, .blog-post-info .readMore a, .related-posts .post-info .readMore a, .payment-content .payment-header .title a:hover, .sidebar a, .sidebar .widget li.active a, .sidebar .widget li.active ul li.current-cat a, .sidebar .widget li.active ul li.toggle-menu-current-item a, .widget .advanced-recent-posts li a:hover, .widget .popular-posts a li a:hover, .widget .category-posts li a:hover, .widget .related-posts-widget li a:hover, .widget .author-posts-widget li a:hover, .woocommerce ul.cart_list li a:hover, .woocommerce ul.product_list_widget li a:hover, .copyrights a, .widget_contact .address-information .text a:hover, #cancel-comment-reply-link, .fn a, .comment-meta a, .reply a, .woocommerce .woocommerce-ordering .e-comm .dropdown li a:hover, .woocommerce-page .woocommerce-ordering .e-comm .dropdown li a:hover,
        .woocommerce ul.products li.product .product-title a:hover, .woocommerce-page ul.products li.product .product-title a:hover,
        .woocommerce .related-products .product-title a:hover, .featured-product-tabs .tabs-content .product-wrap .product-title a:hover, .woocommerce .featured-products .product-title a:hover, .limited-offers .products .product-title a:hover, .mts-cart-product-title a:hover, .woocommerce div.product .woocommerce-tabs ul.tabs li.active a, .woocommerce div.product .woocommerce-tabs ul.tabs li a:hover, .woocommerce-account .woocommerce-MyAccount-navigation li.is-active a, .woocommerce-account .woocommerce-MyAccount-navigation li a:hover, .woocommerce div.product .woocommerce-product-rating a,
        .woocommerce div.product .stock, .woocommerce .woocommerce-info a, .woocommerce .woocommerce-message a, .widget .wpt_widget_content .tab-content li a:hover, .primary-slider .slide-caption .slide-content-4 span, .breadcrumb .container > span a:hover, .rank-math-breadcrumb a:hover { color: {$mts_options['mts_color_scheme2']} }

        #secondary-navigation .sub-menu a:hover, .widget li a:hover, .blog-post-info .readMore a, .related-posts .post-info .readMore a, .blog-page .readMore a { color: {$mts_options['mts_color_scheme2']}!important; }

        #s:focus + .sbutton, #primary-navigation .navigation a:hover, #primary-navigation .navigation .current-menu-item > a,
        .readMore a:hover, .offers-banners .large-post-2 .readMore, .custom-nav a:hover, .widget #wp-subscribe input.submit,
        .blog-post-info .readMore a:after, .related-posts .post-info .readMore a:after, .toggle-menu .active > .toggle-caret,
        .f-widget .readMore a, #move-to-top, .tagcloud a:hover, .currenttext, .pagination a:hover, .pagination  .nav-previous a:hover, .pagination .nav-next a:hover, #load-posts a:hover, #site-header #primary-navigation .navigation a:before, .mts-ad .ad-button .button, .woocommerce .woocommerce-ordering .e-comm.active, .woocommerce .woocommerce-ordering .e-comm:hover, .woocommerce-page .woocommerce-ordering .e-comm.active, .woocommerce-page .woocommerce-ordering .e-comm:hover, .woocommerce a.button:hover, .woocommerce-page a.button:hover, .woocommerce .button:hover, .woocommerce-page .button:hover, .woocommerce input.button:hover, .woocommerce-page input.button:hover, .woocommerce #content input.button:hover, .woocommerce-page #content input.button:hover, .woocommerce #respond input#submit:hover, .woocommerce a.button:hover, .woocommerce .button:hover, .woocommerce input.button:hover, .woocommerce-page #respond input#submit:hover, .woocommerce-page a.button:hover, .woocommerce-page .button:hover, .woocommerce-page input.button:hover, .mts-product-badges .new-badge, #searchform #searchsubmit:hover, .woocommerce .product-hover .ec_action_button, .product-hover .ec_action_button, .mts-cart-button-wrap:hover .mts-cart-button .mts-cart-icon, .mts-wishlist-link:hover .mts-wishlist-icon, .woocommerce .widget_price_filter .price_slider_amount .button, .woocommerce .widget_price_filter .ui-slider .ui-slider-handle:last-child, .product-hover .look a:hover, .product-hover .details a:hover, .woocommerce div.product .woocommerce-tabs ul.tabs li a:hover span, .woocommerce .button.alt, .woocommerce .button.alt:hover, .woocommerce .cart-actions .checkout-button, .woocommerce #payment #place_order, .primary-slider .owl-controls .owl-dot.active span, .primary-slider .owl-controls .owl-dot:hover span, .owl-controls .owl-dot.active span, .owl-controls .owl-dot:hover span, .effect-default .product-hover .look a:hover, .effect-default .product-hover .details a:hover, #commentform input#submit:hover, .woocommerce nav.woocommerce-pagination ul li span.current, .woocommerce-page nav.woocommerce-pagination ul li span.current, .woocommerce #content nav.woocommerce-pagination ul li span.current, .woocommerce-page #content nav.woocommerce-pagination ul li span.current, .woocommerce nav.woocommerce-pagination ul li a:hover, .woocommerce-page nav.woocommerce-pagination ul li a:hover, .woocommerce #content nav.woocommerce-pagination ul li a:hover, .woocommerce-page #content nav.woocommerce-pagination ul li a:hover, .woocommerce .woocommerce-widget-layered-nav-dropdown__submit { background: {$mts_options['mts_color_scheme2']}; color: #fff; }

        .mts-cart-product a.remove:hover, .woocommerce a.remove:hover, .woocommerce table.cart a.remove:hover, .mts-cart-content-footer a.button.mts-cart-button, .woocommerce .widget_layered_nav > ul.mts-ajax-filter-type-label > li a:hover, .woocommerce .widget_layered_nav > ul.mts-ajax-filter-type-label > li.chosen a, .owl-prev:hover, .owl-next:hover {
            background: {$mts_options['mts_color_scheme2']}!important; }

        #s:focus, #s:focus + .sbutton, .toggle-menu .active > .toggle-caret, #site-footer .footeText a, .tagcloud a:hover,
        #load-posts a:hover, .woocommerce .woocommerce-ordering .e-comm.active .dropdown, .woocommerce-page .woocommerce-ordering .e-comm.active .dropdown, .woocommerce .woocommerce-ordering .e-comm.active, .woocommerce .woocommerce-ordering .e-comm:hover, .woocommerce-page .woocommerce-ordering .e-comm.active, .woocommerce-page .woocommerce-ordering .e-comm:hover, .mts-cart-product a.remove:hover, .woocommerce a.remove:hover, .woocommerce table.cart a.remove:hover, .woocommerce .widget_price_filter .ui-slider .ui-slider-handle:last-child, .effect-default .product-hover .look a:hover, .effect-default .product-hover .details a:hover {
            border-color: {$mts_options['mts_color_scheme2']};
        }
        .mts-cart-button-wrap:hover .mts-cart-button .mts-cart-icon, .mts-wishlist-link:hover .mts-wishlist-icon {
            outline: 1px solid {$mts_options['mts_color_scheme2']};
        }
        /*third color*/
        .featured-product-tabs .tabs-content .product-wrap .product-category, .woocommerce .featured-products .product-category, .mts-cart-content-footer .mts-items {
            color: {$mts_options['mts_color_scheme3']};
        }
        .thin-banner .readMore a { color: {$mts_options['mts_color_scheme3']}!important; }

        .woocommerce .product-wrap-inner a.button.added { color: {$mts_options['mts_color_scheme3']}!important; }
        .thin-banner, .featured-blog-post .featured-image .icon, .featured-thumbnail .icon, .limited-offers .products .product-wrap .onsale-badge, .mts-product-badges .onsale-badge, .mts-wishlist-link .count, .mts-cart-button .count, .bypostauthor:after, .latestPost-review-wrapper, .latestPost .review-type-circle.latestPost-review-wrapper, .widget .review-total-only.large-thumb, #wpmm-megamenu .review-total-only {
            background: {$mts_options['mts_color_scheme3']};
        }
        .mts-wishlist-link .count:after, .mts-cart-button .count:after {
            border-top: 6px solid {$mts_options['mts_color_scheme3']};
        }
        .browse-category .browse-category-info { background: rgba(". mts_convert_hex_to_rgb($mts_options['mts_color_scheme']) . ",0.8); }
        .browse-category:hover .browse-category-info { background: rgba(". mts_convert_hex_to_rgb($mts_options['mts_color_scheme']) . ",0.9); }
        .lookbook-caption { background: rgba(". mts_convert_hex_to_rgb($mts_options['mts_color_scheme']) . ",0.5); }
        ul.products.loading:after { border-left-color: rgba(". mts_convert_hex_to_rgb($mts_options['mts_color_scheme']) . ",0.15); border-right-color: rgba(". mts_convert_hex_to_rgb($mts_options['mts_color_scheme']) . ",0.15); border-bottom-color: rgba(". mts_convert_hex_to_rgb($mts_options['mts_color_scheme']) . ",0.15); border-top-color: rgba(". mts_convert_hex_to_rgb($mts_options['mts_color_scheme']) . ",0.8); }
        {$mts_primary_nav_bgcolor}
        {$mts_sclayout}
        {$mts_rightsidebar}
        {$mts_shareit_left}
        {$mts_shareit_right}
        {$mts_author}
        {$mts_header_section}
        {$mts_header_5}
        {$mts_options['mts_custom_css']}
            ";
    wp_add_inline_style( $handle, $custom_css );
}
add_action( 'wp_enqueue_scripts', 'mts_enqueue_css', 99 );
/*-----------------------------------------------------------------------------------*/
/*  Wrap videos in .responsive-video div
/*-----------------------------------------------------------------------------------*/
function mts_responsive_video( $html, $url, $attr ) {
    // Only video embeds
    $video_providers = array(
        'youtube',
        'vimeo',
        'dailymotion',
        'wordpress.tv',
        'vine.co',
        'animoto',
        'blip.tv',
        'collegehumor.com',
        'funnyordie.com',
        'hulu.com',
        'revision3.com',
        'ted.com',
    );
    // Allow user to wrap other embeds
    $providers = apply_filters('mts_responsive_video', $video_providers );
    foreach ( $providers as $provider ) {
        if ( strstr($url, $provider) ) {
            $html = '<div class="flex-video flex-video-' . sanitize_html_class( $provider ) . '">' . $html . '</div>';
            break;// Break if video found
        }
    }
    return $html;
}
add_filter( 'embed_oembed_html', 'mts_responsive_video', 99, 3 );

if ( ! function_exists( 'mts_comments' ) ) {
    /**
     * Custom comments template.
     * @param $comment
     * @param $args
     * @param $depth
     */
    function mts_comments( $comment, $args, $depth ) {
        $GLOBALS['comment'] = $comment;
        $mts_options = get_option( MTS_THEME_NAME ); ?>
        <li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
            <?php
            switch( $comment->comment_type ) :
                case 'pingback':
                case 'trackback': ?>
                    <div id="comment-<?php comment_ID(); ?>">
                        <div class="comment-author vcard">
                            Pingback: <?php comment_author_link(); ?>
                            <span class="comment-meta">
                                <?php edit_comment_link( __( '( Edit )', MTS_THEME_TEXTDOMAIN ), '  ', '' ) ?>
                            </span>
                            <?php if ( ! empty( $mts_options['mts_comment_date'] ) ) { ?>
                                <div class="thetime updated ago"><?php comment_date( get_option( 'date_format' ) ); ?></div>
                            <?php } ?>
                        </div>
                        <?php if ( $comment->comment_approved == '0' ) : ?>
                            <em><?php _e( 'Your comment is awaiting moderation.', MTS_THEME_TEXTDOMAIN ) ?></em>
                            <br />
                        <?php endif; ?>
                    </div>
                <?php
                    break;

                default: ?>
                    <div id="comment-<?php comment_ID(); ?>" itemscope itemtype="http://schema.org/UserComments">
                        <div class="comment-author vcard">
                            <?php echo get_avatar( $comment->comment_author_email, 80 ); ?>
                        </div>
                        <div class="post-info">
                            <div class="theauthor">
                                <?php printf( '<h5 class="fn" itemprop="creator" itemscope itemtype="http://schema.org/Person"><span itemprop="name">%s</span></h5>', get_comment_author_link() ) ?>
                            </div>
                            <span class="comment-meta">
                                <?php edit_comment_link( __( '( Edit )', MTS_THEME_TEXTDOMAIN ), '  ', '' ) ?>
                            </span>
                            <?php if ( ! empty( $mts_options['mts_comment_date'] ) ) { ?>
                                <div class="thetime updated ago"><?php comment_date( get_option( 'date_format' ) ); ?></div>
                            <?php } ?>
                        </div>
                        <?php if ( $comment->comment_approved == '0' ) : ?>
                            <em><?php _e( 'Your comment is awaiting moderation.', MTS_THEME_TEXTDOMAIN ) ?></em>
                            <br />
                        <?php endif; ?>
                        <div class="commentmetadata">
                            <div class="commenttext" itemprop="commentText">
                                <?php comment_text() ?>
                            </div>
                        </div>
                        <div class="reply">
                            <?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] )) ) ?>
                        </div>
                    </div>
                <?php
                   break;
             endswitch; ?>
        <!-- WP adds </li> -->
    <?php }
}

/**
 * Increase excerpt length to 100.
 *
 * @param $length
 *
 * @return int
 */
function mts_excerpt_length( $length ) {
    return 100;
}
add_filter( 'excerpt_length', 'mts_excerpt_length', 20 );

/**
 * Remove [...] and shortcodes
 *
 * @param $output
 *
 * @return string
 */
function mts_custom_excerpt( $output ) {
  return preg_replace( '/\[[^\]]*]/', '', $output );
}
add_filter( 'get_the_excerpt', 'mts_custom_excerpt' );

/**
 * Truncate string to x letters/words.
 *
 * @param $str
 * @param int $length
 * @param string $units
 * @param string $ellipsis
 *
 * @return string
 */
function mts_truncate( $str, $length = 40, $units = 'letters', $ellipsis = '&nbsp;&hellip;' ) {
    if ( $units == 'letters' ) {
        if ( mb_strlen( $str ) > $length ) {
            return mb_substr( $str, 0, $length ) . $ellipsis;
        } else {
            return $str;
        }
    } else {
        return wp_trim_words( $str, $length, $ellipsis );
    }
}

if ( ! function_exists( 'mts_excerpt' ) ) {
    /**
     * Get HTML-escaped excerpt up to the specified length.
     *
     * @param int $limit
     *
     * @return string
     */
    function mts_excerpt( $limit = 40 ) {
      return esc_html( mts_truncate( get_the_excerpt(), $limit, 'words' ) );
    }
}

/**
 * Change the "read more..." link to "".
 * @param $more_link
 * @param $more_link_text
 *
 * @return string
 */
function mts_remove_more_link( $more_link, $more_link_text ) {
    return '';
}
add_filter( 'the_content_more_link', 'mts_remove_more_link', 10, 2 );

if ( ! function_exists( 'mts_post_has_moretag' ) ) {
    /**
     * Shorthand function to check for more tag in post.
     *
     * @return bool|int
     */
    function mts_post_has_moretag() {
        $post = get_post();
        return preg_match( '/<!--more(.*?)?-->/', $post->post_content );
    }
}

if ( ! function_exists( 'mts_readmore' ) ) {
    function mts_readmore() {
        ?>
        <div class="readMore">
            <a href="<?php echo esc_url( get_the_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>">
                <?php _e( 'Read More', MTS_THEME_TEXTDOMAIN ); ?>
            </a>
        </div>
        <?php
    }
}

/**
 * Exclude trackbacks from the comment count.
 *
 * @param $count
 *
 * @return int
 */
function mts_comment_count( $count ) {
    if ( ! is_admin() ) {
        global $id;
        $comments = get_comments( 'status=approve&post_id=' . $id );
        $comments_by_type = separate_comments( $comments );
        return count( $comments_by_type['comment'] );
    } else {
        return $count;
    }
}
add_filter( 'get_comments_number', 'mts_comment_count', 0 );

/**
 * Add `has_thumb` to the post's class name if it has a thumbnail.
 *
 * @param $classes
 *
 * @return array
 */
function has_thumb_class( $classes ) {
    if( has_post_thumbnail( get_the_ID() ) ) { $classes[] = 'has_thumb'; }
        return $classes;
}
add_filter( 'post_class', 'has_thumb_class' );
/*-----------------------------------------------------------------------------------*/
/*  Site Title backwards compatibility
/*-----------------------------------------------------------------------------------*/
add_theme_support( 'title-tag' );
if ( ! function_exists( '_wp_render_title_tag' ) ) {
    function theme_slug_render_title() { ?>
       <title><?php wp_title( '|', true, 'right' ); ?></title>
   <?php }
    add_action( 'wp_head', 'theme_slug_render_title' );
}
/*-----------------------------------------------------------------------------------*/
/* AJAX Search results
/*-----------------------------------------------------------------------------------*/
function ajax_mts_search() {
    $query = $_REQUEST['q']; // It goes through esc_sql() in WP_Query
    $type = isset( $_GET['search_post_type'] ) ? $_GET['search_post_type'] : 'post';
    $search_query = new WP_Query( array( 's' => $query, 'posts_per_page' => 3, 'post_status' => 'publish', 'post_type'=> $type ) );
    $search_count = new WP_Query( array( 's' => $query, 'posts_per_page' => -1, 'post_status' => 'publish', 'post_type'=> $type ) );
    $search_count = $search_count->post_count;
    if ( !empty( $query ) && $search_query->have_posts() ) :
        //echo '<h5>Results for: '. $query.'</h5>';
        echo '<ul class="ajax-search-results">';
        while ( $search_query->have_posts() ) : $search_query->the_post();
            ?><li>
                <a href="<?php echo esc_url( get_the_permalink() ); ?>">
                    <?php the_post_thumbnail( 'widgetthumb', array( 'title' => '' )); ?>
                    <?php the_title(); ?>
                    <div class="meta">
                        <span class="thetime"><?php the_time( 'F j, Y' ); ?></span>
                    </div> <!-- / .meta -->
                </a>
            </li>
            <?php
        endwhile;
        echo '</ul>';
        $all_results_link = apply_filters( 'ecommerce_all_results_url', add_query_arg( array( 's' => $query, 'post_type' => $type, ), home_url( '/' ) ) );
        echo '<div class="ajax-search-meta"><span class="results-count">'.$search_count.' '.__( 'Results', MTS_THEME_TEXTDOMAIN ).'</span><a href="'.esc_url( $all_results_link ).'" class="results-link">'.__('Show all results.', MTS_THEME_TEXTDOMAIN ).'</a></div>';
    else:
        echo '<div class="no-results">'.__( 'No results found.', MTS_THEME_TEXTDOMAIN ).'</div>';
    endif;
    wp_reset_postdata();
    exit; // required for AJAX in WP
}
if( !empty( $mts_options['mts_ajax_search'] )) {
    add_action( 'wp_ajax_mts_search', 'ajax_mts_search' );
    add_action( 'wp_ajax_nopriv_mts_search', 'ajax_mts_search' );
}

/**
 *  Filters that allow shortcodes in Text Widgets
 */
add_filter( 'widget_text', 'shortcode_unautop' );
add_filter( 'widget_text', 'do_shortcode' );
add_filter( 'the_content_rss', 'do_shortcode' );

if ( trim( $mts_options['mts_feedburner'] ) !== '' ) {
    /**
     * Redirect feed to FeedBurner if a FeedBurner URL has been set.
     */
    function mts_rss_feed_redirect() {
        $mts_options = get_option( MTS_THEME_NAME );
        global $feed;
        $new_feed = $mts_options['mts_feedburner'];
        if ( !is_feed() ) {
                return;
        }
        if ( preg_match( '/feedburner/i', $_SERVER['HTTP_USER_AGENT'] )){
                return;
        }
        if ( $feed != 'comments-rss2' ) {
                if ( function_exists( 'status_header' )) status_header( 302 );
                header( "Location:" . $new_feed );
                header( "HTTP/1.1 302 Temporary Redirect" );
                exit();
        }
    }
    add_action( 'template_redirect', 'mts_rss_feed_redirect' );
}

/**
 * Single Post Pagination - Numbers + Previous/Next.
 *
 * @param $args
 *
 * @return mixed
 */
function mts_wp_link_pages_args( $args ) {
    global $page, $numpages, $more, $pagenow;
    if ( $args['next_or_number'] != 'next_and_number' ) {
        return $args;
    }

    $args['next_or_number'] = 'number';

    if ( !$more ) {
        return $args;
    }

    if( $page-1 ) {
        $args['before'] .= _wp_link_page( $page-1 )
                        . $args['link_before']. $args['previouspagelink'] . $args['link_after'] . '</a>';
    }

    if ( $page<$numpages ) {
        $args['after'] = _wp_link_page( $page+1 )
                         . $args['link_before'] . $args['nextpagelink'] . $args['link_after'] . '</a>'
                         . $args['after'];
    }

    return $args;
}
add_filter( 'wp_link_pages_args', 'mts_wp_link_pages_args' );

/*-----------------------------------------------------------------------------------*/
/* WooCommerce
/*-----------------------------------------------------------------------------------*/
if ( mts_isWooCommerce() ) {
    add_theme_support( 'woocommerce' );

    // Enable new WC 3.0.0 lightbox zoom slider gallery
    if ( isset( $mts_options['mts_wc_product_gallery'] ) && '1' == $mts_options['mts_wc_product_gallery'] ) {
        if( isset($mts_options['mts_wc_product_gallery_enable']) && $mts_options['mts_wc_product_gallery_enable']['zoom'] == '1' ) {
            add_theme_support( 'wc-product-gallery-zoom' );
        }
        if( isset($mts_options['mts_wc_product_gallery_enable']['lightbox']) == '1' ) {
            add_theme_support( 'wc-product-gallery-lightbox' );
        }
        if( isset($mts_options['mts_wc_product_gallery_enable']['slider']) == '1' ) {
            add_theme_support( 'wc-product-gallery-slider' );
        }
    }

    // Change number or products per row to 3
    //add_filter( 'loop_shop_columns', 'loop_columns' );
    if ( !function_exists( 'loop_columns' )) {
        function loop_columns() {
            return 3; // 3 products per row
        }
    }

    // Redefine woocommerce_output_related_products()
    function woocommerce_output_related_products() {
        $mts_options = get_option( MTS_THEME_NAME );
        $mts_no_related_products = $mts_options['mts_related_products_num'];
        $args = array(
            'posts_per_page' => $mts_no_related_products,
            'columns' => 1,
        );
        woocommerce_related_products($args); // Display all products in carousel
    }

    /*** Hook in on activation */
    global $pagenow;
    if ( is_admin() && isset( $_GET['activated'] ) && $pagenow == 'themes.php' ) add_action( 'init', 'mythemeshop_woocommerce_image_dimensions', 1 );

    /*** Define image sizes */
    function mythemeshop_woocommerce_image_dimensions() {
        $catalog = array(
            'width'     => '240',   // px
            'height'    => '340',   // px
            'crop'      => 1        // true
         );
        $single = array(
            'width'     => '354',   // px
            'height'    => '420',   // px
            'crop'      => 1        // true
         );
        $thumbnail = array(
            'width'     => '80',    // px
            'height'    => '85',    // px
            'crop'      => 0        // false
         );
        // Image sizes
        update_option( 'shop_catalog_image_size', $catalog );       // Product category thumbs
        update_option( 'shop_single_image_size', $single );         // Single product image
        update_option( 'shop_thumbnail_image_size', $thumbnail );   // Image gallery thumbs
    }

    add_filter( 'woocommerce_product_thumbnails_columns', 'mts_thumb_cols' );
    function mts_thumb_cols() {
     return 4; // .last class applied to every 4th thumbnail
    }

    add_filter( 'loop_shop_per_page', 'mts_products_per_page', 20 );
    function mts_products_per_page() {
        $mts_options = get_option( MTS_THEME_NAME );
        return $mts_options['mts_shop_products'];
    }

    // Ensure cart contents update when products are added to the cart via AJAX
    $add_to_cart_fragments_action = version_compare( WC()->version, '3.0.0', ">=" ) ? 'woocommerce_add_to_cart_fragments' : 'add_to_cart_fragments';
    add_filter( $add_to_cart_fragments_action, 'mts_header_add_to_cart_fragment' );
    function mts_header_add_to_cart_fragment( $fragments ) {
        global $woocommerce;
        ob_start();
        mts_cart();
        $fragments['.mts-cart-button-wrap'] = ob_get_clean();
        return $fragments;
    }
    /**
     * Cart Page
     */
    // Remove cart totals from default place in cart page, it's called inside wocommerce/cart.php
    remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cart_totals', 10 );
    /**
     * Breadcrumbs
     */
     // Remove WooCommerce breadcrumb from default place, it's called within mts_the_breadcrumb() and placed in the header.php
     remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);

     // Customize WooCommerce breadcrumb defaults
     add_filter('woocommerce_breadcrumb_defaults', 'mts_wc_breadcrumb_defaults');
     function mts_wc_breadcrumb_defaults( $defaults ) {
       // Change the breadcrumb delimeter from '/' to '>'
       $defaults['delimiter']   = '<span class="delimiter fa fa-angle-right"></span>';
       $defaults['wrap_before'] = '';
       $defaults['wrap_after']  = '';
       return $defaults;
     }
    // Replace shop placeholder image
    add_action( 'init', 'mts_custom_wc_placeholder' );
    function mts_custom_wc_placeholder() {
        add_filter('woocommerce_placeholder_img_src', 'mts_woocommerce_placeholder_img_src');

        function mts_woocommerce_placeholder_img_src() {

            return get_template_directory_uri().'/images/nothumb-shop.png';
        }
    }
    // Change add to cart text on single products
    add_filter( 'woocommerce_product_single_add_to_cart_text', 'woo_custom_cart_button_text', 10, 2 );
    function woo_custom_cart_button_text($text, $product) {
        if ( ! is_single() ) return $text;
        return '<i class="fa fa-shopping-cart"></i>' . ( $product->is_type( 'external' ) ? $text : esc_html__( 'Add to Cart', MTS_THEME_TEXTDOMAIN ) );
    }

    /**
     * Optimize WooCommerce Scripts
     * Updated for WooCommerce 2.0+
     * Remove WooCommerce Generator tag, styles, and scripts from non WooCommerce pages.
     */
    function mts_child_manage_woocommerce_styles() {
        //remove generator meta tag
        remove_action( 'wp_head', array( $GLOBALS['woocommerce'], 'generator' ) );

        //first check that woo exists to prevent fatal errors
        if ( function_exists( 'is_woocommerce' ) ) {
            //dequeue scripts and styles
            if ( ! is_woocommerce() && ! is_cart() && ! is_checkout() && !mts_is_wishlist_page() && !is_account_page() ) {
                wp_dequeue_style( 'woocommerce-layout' );
                wp_dequeue_style( 'woocommerce-smallscreen' );
                wp_dequeue_style( 'woocommerce-general' );
                wp_dequeue_style( 'wc-bto-styles' ); //Composites Styles
                wp_dequeue_script( 'wc-add-to-cart' );
                wp_dequeue_script( 'wc-cart-fragments' );
                wp_dequeue_script( 'woocommerce' );
                wp_dequeue_script( 'jquery-blockui' );
                wp_dequeue_script( 'jquery-placeholder' );
            }
        }
    }
    if ( ! empty( $mts_options['mts_optimize_wc'] ) ) {
        add_action( 'wp_enqueue_scripts', 'mts_child_manage_woocommerce_styles', 99 );
    }

    remove_action('wp_head', 'wc_generator_tag');
    // Add extra classes to body
    add_filter( 'body_class', 'mts_body_classes' );
    function mts_body_classes( $classes ) {
        $mts_options = get_option( MTS_THEME_NAME );
        if ( !isset( $mts_options['mts_wishlist'] ) || empty( $mts_options['mts_wishlist'] ) ) {
            $classes[] = 'wishlist-disabled';
        }
        return $classes;
    }
    // Remove link, rating and default button in products loop
    remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
    remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
    remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
    remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
    /**
     * Show the subcategory title in the product loop.
     */
    function woocommerce_template_loop_category_title( $category ) {
        ?>
        <div class="browse-category-info">
            <div class="browse-category-title"><?php echo esc_html( $category->name ); ?></div>
            <?php if ( $category->count > 0 ) echo apply_filters( 'woocommerce_subcategory_count_html', '<div class="browse-category-products">' . $category->count . __( ' Products', MTS_THEME_TEXTDOMAIN ) . '</div>', $category ); ?>
        </div>
        <?php
    }
    // Remove product ordering form from default location in shop archives, it's called from "woocommerce/archive-product.php" instead
    remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
    /*-----------------------------------------------------------------------------------*/
    /* Add color picker option to attribute taxonomies edit screens
    /*-----------------------------------------------------------------------------------*/
    $attribute_taxonomies = wc_get_attribute_taxonomies();
    if ( $attribute_taxonomies ) {
        foreach ( $attribute_taxonomies as $tax ) {
            $attribute = sanitize_title( $tax->attribute_name );
            $taxonomy = wc_attribute_taxonomy_name( $attribute );

            add_action( $taxonomy.'_add_form_fields', 'mts_attribute_tax_add_form_fields' );
            add_action( $taxonomy.'_edit_form_fields', 'mts_attribute_tax_edit_form_fields', 10, 2 );
        }
        // Handle creating/editing/deleting of color attribute color
        add_action( 'created_term', 'mts_create_update_attribute_tax_color', 10, 3 );
        add_action( 'edited_term', 'mts_create_update_attribute_tax_color', 10, 3 );
        add_action( 'delete_term', 'mts_delete_attribute_tax_color', 10, 3 );
        // Enqueue color picker
        add_action('admin_enqueue_scripts', 'mts_attribute_tax_picker');
    }
    function mts_attribute_tax_add_form_fields() {
        ?>
        <div class="form-field">
            <label for="ps_color_hex_code">
                <?php _e( 'Choose color: ', MTS_THEME_TEXTDOMAIN ); ?>
            </label>
            <input type="text" id="ps_color_hex_code" name="ps_color_hex_code" class="mts-color-picker-field" value="">
        </div>
        <?php
    }
    function mts_attribute_tax_edit_form_fields( $tag, $taxonomy ) {
        // clear value.
        $color_code = '';
        // tag id
        $id = $tag->term_id;
        $opt_array = get_option('mts_tax_color_codes');
        if ( $opt_array && array_key_exists( $taxonomy, $opt_array ) ) {
            if ( array_key_exists( $id, $opt_array[ $taxonomy ] ) ) {
                $color_code = $opt_array[ $taxonomy ][ $id ];
            }
        }
        ?>
        <tr class="form-field">
            <th scope="row" valign="top">
                <label for="ps_color_hex_code"><?php _e( 'Choose color:', MTS_THEME_TEXTDOMAIN ); ?></label>
            </th>
            <td>
                <input type="text" id="ps_color_hex_code" name="ps_color_hex_code"  class="mts-color-picker-field" value="<?php echo $color_code; ?>">
            </td>
        </tr>
        <?php
    }
    function mts_create_update_attribute_tax_color( $term_id, $tt_id, $taxonomy ) {
        $opt_array = get_option('mts_tax_color_codes');
        if ( isset( $_POST['ps_color_hex_code'] ) ) {
            $opt_array[ $taxonomy ][ $term_id ] = $_POST['ps_color_hex_code'];
        }
        if ( isset( $opt_array ) ) {
            update_option( 'mts_tax_color_codes' , $opt_array );
        }
    }
    function mts_delete_attribute_tax_color( $term_id, $tt_id, $taxonomy ) {
        $opt_array = get_option('mts_tax_color_codes');
        if ( $opt_array && isset( $opt_array[ $taxonomy ][ $term_id ] ) ) {
            unset( $opt_array[ $taxonomy ][ $term_id ] );
            update_option('mts_tax_color_codes', $opt_array);
        }
    }
    function mts_attribute_tax_picker() {
        $taxonomy_screens = array();
        $attribute_taxonomies = wc_get_attribute_taxonomies();
        foreach ( $attribute_taxonomies as $tax ) {
            $attribute = sanitize_title( $tax->attribute_name );
            $taxonomy = wc_attribute_taxonomy_name( $attribute );

            $taxonomy_screens[] = 'edit-'.$taxonomy;
        }
        $screen = get_current_screen();
        $screen_id = $screen->id;
        if ( in_array( $screen_id, $taxonomy_screens ) ) {
            wp_enqueue_style('wp-color-picker');
            wp_enqueue_script('wp-color-picker');
            wp_enqueue_script( 'mts-color-picker-script', get_template_directory_uri() . '/js/color-picker.js', array('wp-color-picker') );
        }
    }

    /**
     * Single Product
     */
    // Move badges inside "images" div
    remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
    add_action( 'woocommerce_product_thumbnails', 'woocommerce_show_product_sale_flash', 10 );

    // Replace default woocommerce_default_product_tabs function ( just to mark review count )
    function woocommerce_default_product_tabs( $tabs = array() ) {
        global $product, $post;
        // Description tab - shows product content
        if ( $post->post_content ) {
            $tabs['description'] = array(
                'title'    => __( 'Description', MTS_THEME_TEXTDOMAIN ),
                'priority' => 10,
                'callback' => 'woocommerce_product_description_tab'
            );
        }
        // Additional information tab - shows attributes
        if ( $product && ( $product->has_attributes() || apply_filters( 'wc_product_enable_dimensions_display', $product->has_weight() || $product->has_dimensions() ) ) ) {
            $tabs['additional_information'] = array(
                'title'    => __( 'Additional Information', MTS_THEME_TEXTDOMAIN ),
                'priority' => 20,
                'callback' => 'woocommerce_product_additional_information_tab'
            );
        }
        // Reviews tab - shows comments
        if ( comments_open() ) {
            $tabs['reviews'] = array(
                'title'    => sprintf( __( 'Reviews <span>%d</span>', MTS_THEME_TEXTDOMAIN ), $product->get_review_count() ),
                'priority' => 30,
                'callback' => 'comments_template'
            );
        }
        return $tabs;
    }
    /**
     * Output placeholders for the single variation.
     */
    function woocommerce_single_variation() {
        echo '<div class="single_variation clear"></div>';
    }
    /**
     * Output the add to cart button for variations.
     */
    function woocommerce_single_variation_add_to_cart_button() {
        global $product;
        ?>
        <div class="woocommerce-variation-add-to-cart variations_button">
            <div class="single_quantity_label"><?php _e( 'Quantity:', MTS_THEME_TEXTDOMAIN ); ?></div>
            <?php do_action( 'woocommerce_before_add_to_cart_quantity' );?>
            <?php woocommerce_quantity_input( array( 'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( $_POST['quantity'] ) : 1 ) ); ?>
            <?php do_action( 'woocommerce_after_add_to_cart_quantity' ); ?>
            <?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>
            <button type="submit" class="single_add_to_cart_button button alt"><?php echo $product->single_add_to_cart_text(); ?></button>
            <?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
            <input type="hidden" name="add-to-cart" value="<?php echo absint( $product->get_id() ); ?>" />
            <input type="hidden" name="product_id" value="<?php echo absint( $product->get_id() ); ?>" />
            <input type="hidden" name="variation_id" class="variation_id" value="" />
        </div>
        <?php
    }
    // Wishlist
    //add_action( 'woocommerce_single_product_summary', 'mts_wc_single_product_bottom_divider', 33 );
    function mts_wc_single_product_bottom_divider() {
        echo '<hr />';
    }
    add_action( 'woocommerce_before_add_to_cart_button', 'mts_wc_single_product_wishlist_wrapper',10000 );
    function mts_wc_single_product_wishlist_wrapper() {
        echo '<span class="cart-wishlist-button">';
    }
    //add_action( 'woocommerce_single_product_summary', 'mts_wc_single_product_wishlist', 34 );
    add_action( 'woocommerce_after_add_to_cart_button', 'mts_wc_single_product_wishlist' );
    function mts_wc_single_product_wishlist() {
        echo mts_wishlist_button();
        echo '</span>';
    }
    //remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
    //add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 31 );
    function mts_get_wishlist_page_url() {
        $wishlist_page = mts_get_wishlist_page_id();
        $wishlist_page_id = apply_filters( 'mts_wishlist_page_id', $wishlist_page );
        return get_page_link( $wishlist_page_id );
    }
    function mts_get_wishlist_page_id() {
      $wishlist_page_id = '';
      $args = array(
        'post_type'        => 'page',
        'fields'           => 'ids',
        'post_status'      => 'publish',
        'nopaging'         => true,
        'posts_per_page'   => 1,
        'meta_key'         => '_wp_page_template',
        'meta_value'       => 'page-wishlist.php'
      );
      $pages = get_posts( $args );
      if( is_array( $pages ) && count( $pages ) > 0 ) {
          $wishlist_page_id = $pages[0];
      }
      return $wishlist_page_id;
    }
    function mts_is_wishlist_page() {
      $wishlist_page = mts_get_wishlist_page_id();
      $wishlist_page_id = apply_filters( 'mts_wishlist_page_id', $wishlist_page );
      return is_page( $wishlist_page_id );
    }
    function mts_product_in_wishlist( $id ) {
        $wishlist_items = !empty( $_COOKIE['mts_wishlist'] ) ? explode(',', $_COOKIE['mts_wishlist']) : array();
        return in_array( $id, $wishlist_items );
    }
    add_action('wp_ajax_mts_wishlist_page_loop', 'mts_wishlist_page_loop');
    add_action('wp_ajax_nopriv_mts_wishlist_page_loop', 'mts_wishlist_page_loop');
    function mts_wishlist_page_loop() {
        global $mts_options;
        if ( !isset( $mts_options['mts_wishlist'] ) || empty( $mts_options['mts_wishlist'] ) ) {
            echo '<p>'.__( 'Wishlist feature is disabled', MTS_THEME_TEXTDOMAIN ).'</p>';
            return;
        }
        echo '<table class="shop_table cart mts-wishlist-table" cellspacing="0">';
            echo '<tbody>';
            $wishlist_items = !empty( $_COOKIE['mts_wishlist'] ) ? explode(',', $_COOKIE['mts_wishlist']) : array();
            if ( !empty( $wishlist_items ) ) {
                $args = array(
                    'post_type'           => 'product',
                    'post_status'         => 'publish',
                    'ignore_sticky_posts' => 1,
                    'posts_per_page'      => -1,
                    'post__in'            => $wishlist_items
                );
                $wishlist_query = new WP_Query( apply_filters( 'mts_wishlist_query', $args ) );
                ?>
                <?php
                while ( $wishlist_query->have_posts() ) : $wishlist_query->the_post();
                    global $product;
                    $pr_id = get_the_ID();
                ?>
                        <tr class="mts-wishlist-table-row">
                            <td class="product-remove">
                                <a href="#" data-product-id="<?php echo $pr_id; ?>" class="mts-remove-from-wishlist remove" title="<?php _e( 'Remove this product', MTS_THEME_TEXTDOMAIN ) ?>">&times;</a>
                            </td>
                            <td class="product-thumbnail">
                                <?php
                                    $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $product->get_image() );
                                    if ( ! $product->is_visible() )
                                        echo $thumbnail;
                                    else
                                        printf( '<a href="%s">%s</a>', $product->get_permalink(), $thumbnail );
                                ?>
                            </td>
                            <td class="product-data">
                                <div class="product-name">
                                    <a href="<?php echo esc_url( get_permalink( apply_filters( 'woocommerce_in_cart_product', $pr_id ) ) ) ?>"><?php echo apply_filters( 'woocommerce_in_cartproduct_obj_title', $product->get_title(), $product ) ?></a>
                                </div>
                                <dl>
                                    <dt><?php _e('Unit Price:', MTS_THEME_TEXTDOMAIN ); ?></dt>
                                    <dd>
                                    <?php woocommerce_template_loop_price(); ?>
                                    </dd>
                                </dl>
                            </td>
                            <td class="product-add-to-cart">
                                <?php woocommerce_template_loop_add_to_cart(); ?>
                            </td>
                        </tr>
                <?php
                endwhile; wp_reset_postdata();
            } else {
                echo '<tr><td colspan="6" class="mts-wishlist-empty">'.__( 'Your wishlist is empty', MTS_THEME_TEXTDOMAIN ).'</td></tr>';
            }
            echo '</tbody>';
        echo '</table>';
        if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) die();
    }
    add_action('wp_ajax_mts_wishlist_count', 'mts_wishlist_count');
    add_action('wp_ajax_nopriv_mts_wishlist_count', 'mts_wishlist_count');
    function mts_wishlist_count() {
        $wishlist_items = !empty( $_COOKIE['mts_wishlist'] ) ? explode(',', $_COOKIE['mts_wishlist']) : array();
        echo count( $wishlist_items );

        if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) die();
    }
    // Remove "related products" from default location in single product view, and display them before footer instead
    remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
    add_action( 'mts_before_product_footer', 'mts_product_related_products', 10 );
    function mts_product_related_products() {
        $mts_options = get_option( MTS_THEME_NAME );
        if ( $mts_options['mts_related_products'] == '1' ) {
            woocommerce_output_related_products();
        }
    }
    // Featured Products
    // before single page footer
    add_action( 'mts_before_single_page_footer', 'mts_page_featured_products', 10 );
    function mts_page_featured_products() {
        $mts_options = get_option( MTS_THEME_NAME );
        $show_featured_products = false;
        if ( $mts_options['mts_featured_products'] == '1' ) {
            if ( is_order_received_page() && isset( $mts_options['mts_featured_products_locations']['thankyou'] ) ) { // order completed
                $show_featured_products = true;
            } elseif ( !is_order_received_page() && is_checkout() && isset( $mts_options['mts_featured_products_locations']['checkout'] ) ) { // checkout

                $show_featured_products = true;
            } elseif ( is_cart() && isset( $mts_options['mts_featured_products_locations']['cart'] ) ) { // cart
                $show_featured_products = true;
            }
        }
        if ( $show_featured_products ) {
            wc_get_template( 'featured-products.php' );
        }
    }
    // before single product footer
    add_action( 'mts_before_product_footer', 'mts_product_featured_products', 20 );
    function mts_product_featured_products() {
        $mts_options = get_option( MTS_THEME_NAME );
        if ( $mts_options['mts_featured_products'] == '1' && isset( $mts_options['mts_featured_products_locations']['product'] ) ) {
            wc_get_template( 'featured-products.php' );
        }
    }
    // Home tabs
    add_action('wp_ajax_mts_homepage_tabs', 'mts_ajax_homepage_tabs');
    add_action('wp_ajax_nopriv_mts_homepage_tabs', 'mts_ajax_homepage_tabs');
    function mts_ajax_homepage_tabs() {
        mts_homepage_tab( $_POST['home_tab'] );
        die();
    }
    if ( !function_exists('mts_homepage_tab') ) {
        function mts_homepage_tab( $tab ) {
            if ( 'new_products_tab' === $tab ) {
                $new_products_args = array('posts_per_page' => 4, 'no_found_rows' => 1, 'post_status' => 'publish', 'post_type' => 'product' );
                $new_products_args['meta_query'] = WC()->query->get_meta_query();
                $new_products_query = new WP_Query( apply_filters( 'ecommerce_homepage_tab_args', $new_products_args, 'new_products_tab' ) );
                if ( $new_products_query->have_posts() ) {
                    echo '<ul class="products woocommerce">';
                    while ( $new_products_query->have_posts() ) {
                        $new_products_query->the_post();
                        wc_get_template( 'content-product-featured-carousel.php' );
                    }
                    echo '</ul>';
                }
                wp_reset_postdata();
            } else if ( 'top_rated_tab' === $tab )  {
                if ( version_compare( WC()->version, '3.0.0', ">=" ) ) {
                    $top_rated_args = array(
                        'posts_per_page' => 4,
                        'no_found_rows'  => 1,
                        'post_status'    => 'publish',
                        'post_type'      => 'product',
                        'meta_key'       => '_wc_average_rating',
                        'orderby'        => 'meta_value_num',
                        'order'          => 'DESC',
                        'meta_query'     => WC()->query->get_meta_query(),
                        'tax_query'      => WC()->query->get_tax_query(),
                    );
                } else {
                    add_filter( 'posts_clauses',  array( WC()->query, 'order_by_rating_post_clauses' ) );
                    $top_rated_args = array('posts_per_page' => 4, 'no_found_rows' => 1, 'post_status' => 'publish', 'post_type' => 'product' );
                    $top_rated_args['meta_query'] = WC()->query->get_meta_query();
                }

                $top_rated_query = new WP_Query( apply_filters( 'ecommerce_homepage_tab_args', $top_rated_args, 'top_rated_tab' ) );
                if ( $top_rated_query->have_posts() ) {
                    echo '<ul class="products woocommerce">';
                    while ( $top_rated_query->have_posts() ) {
                        $top_rated_query->the_post();
                        wc_get_template( 'content-product-featured-carousel.php' );
                    }
                    echo '</ul>';
                }
                if ( version_compare( WC()->version, '3.0.0', "<" ) ) {
                    remove_filter( 'posts_clauses', array( WC()->query, 'order_by_rating_post_clauses' ) );
                }
                wp_reset_postdata();
            } else if ( 'best_sellers_tab' === $tab )  {
                $best_sellers_args = array( 'meta_key' => 'total_sales', 'orderby' => 'meta_value_num','posts_per_page' => 4, 'no_found_rows' => 1, 'post_status' => 'publish', 'post_type' => 'product');
                $best_sellers_args['meta_query'] = WC()->query->get_meta_query();
                $best_sellers_query = new WP_Query( apply_filters( 'ecommerce_homepage_tab_args', $best_sellers_args, 'best_sellers_tab' ) );

                if ( $best_sellers_query->have_posts() ) {

                    echo '<ul class="products woocommerce">';

                    while ( $best_sellers_query->have_posts() ) {
                        $best_sellers_query->the_post();
                        wc_get_template( 'content-product-featured-carousel.php' );
                    }

                    echo '</ul>';
                }

                wp_reset_postdata();
            }
        }
    }
    add_action('wp_ajax_mts_quick_look', 'mts_quick_look');
    add_action('wp_ajax_nopriv_mts_quick_look', 'mts_quick_look');
    function mts_quick_look() {
        $id = $_POST['product_id']; ?>
        <div id="mts-product-popup-<?php echo $id; ?>" class="mts-product-popup woocommerce white-popup mfp-hide clearfix">
            <div class="mts-product-popup-inner">
                <div id="content">
                    <?php
                    $args = array(
                        'post_type' => 'product',
                        'post__in' => array( $id )
                    );
                    $new_query = new WP_Query( $args );
                    while ( $new_query->have_posts() ) : $new_query->the_post(); ?>
                        <div id="product-<?php the_ID(); ?>" <?php post_class('product'); ?>>
                        <?php wc_get_template('popup-product-images.php'); ?>
                        <div class="summary entry-summary">
                            <header class="popup-product-header">
                                <?php
                                woocommerce_template_single_title();
                                woocommerce_template_loop_rating();
                                woocommerce_template_loop_price();
                                ?>
                            </header>
                            <div class="popup-product-content">
                                <?php woocommerce_template_single_excerpt();?>
                                <?php woocommerce_template_single_meta();?>
                                <div class="popup-product-buttons">
                                    <?php echo mts_wishlist_button(); ?>
                                    <a href="<?php echo esc_url( get_the_permalink() ); ?>" class="button"><i class="fa fa-search"></i><?php _e( 'View Details', MTS_THEME_TEXTDOMAIN ); ?></a>
                                    <?php global $product;woocommerce_template_loop_add_to_cart(); ?>
                                </div>
                            </div>
                        </div><!-- .summary -->
                    </div>
                    <?php endwhile; // end of the loop.
                    wp_reset_postdata();
                    ?>
                </div>
            </div>
        </div>
        <?php
        die();
    }
    // Shop/Catalog
    add_action( 'mts_after_header', 'mts_product_category_ads' );
    function mts_product_category_ads() {
        if ( is_product_category() ) {
            global $wp_query;
            $term = $wp_query->get_queried_object();
            if ( is_active_sidebar('catalog-ads-'.$term->term_id) ) { ?>
                <div class="mts-ad-widgets catalog-ad-widgets clearfix">
                    <div class="container mts-ads-container">
                        <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar( 'catalog-ads-'.$term->term_id ) ) : ?><?php endif; ?>
                    </div>
                </div>
            <?php } elseif ( is_active_sidebar('catalog-ads') ) { ?>
                <div class="mts-ad-widgets catalog-ad-widgets clearfix">
                    <div class="container mts-ads-container">
                        <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar( 'catalog-ads' ) ) : ?><?php endif; ?>
                    </div>
                </div>
            <?php }
        } elseif ( is_shop() ) {
            if ( is_active_sidebar('shop-ads') ) { ?>
                <div class="mts-ad-widgets shop-ad-widgets clearfix">
                    <div class="container mts-ads-container">
                        <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar( 'shop-ads' ) ) : ?><?php endif; ?>
                    </div>
                </div>
            <?php }
        }
    }
    //homepage featured category thumbnails
    remove_action( 'woocommerce_before_subcategory_title', 'woocommerce_subcategory_thumbnail');
    function woocommerce_subcategory_thumbnail_custom( $category  ) {
        $small_thumbnail_size = apply_filters( 'single_product_small_thumbnail_size', 'woo-cat' );
        $dimensions = wc_get_image_size( $small_thumbnail_size );
        $thumbnail_id = get_term_meta( $category->term_id, 'thumbnail_id', true  );
        if ( $thumbnail_id ) {
            $image = wp_get_attachment_image_src( $thumbnail_id, $small_thumbnail_size  );
            $image = $image[0];
        } else {
            $image = wc_placeholder_img_src();
        }
        if ( $image ) {
            $image = str_replace( ' ', '%20', $image );
            echo '<img src="' . esc_url( $image ) . '" alt="' . esc_attr( $category->name ) . '" width="' . esc_attr( $dimensions['width'] ) . '" height="' . esc_attr( $dimensions['height'] ) . '" />';
        }
    }
    // load mine
    add_action( 'woocommerce_before_subcategory_title', 'woocommerce_subcategory_thumbnail_custom', 10);
}

/**
 * Add <!-- next-page --> button to tinymce.
 *
 * @param $mce_buttons
 *
 * @return array
 */
function mts_wysiwyg_editor( $mce_buttons ) {
   $pos = array_search( 'wp_more', $mce_buttons, true );
   if ( $pos !== false ) {
       $tmp_buttons = array_slice( $mce_buttons, 0, $pos+1 );
       $tmp_buttons[] = 'wp_page';
       $mce_buttons = array_merge( $tmp_buttons, array_slice( $mce_buttons, $pos+1 ));
   }
   return $mce_buttons;
}
add_filter( 'mce_buttons', 'mts_wysiwyg_editor' );
/*-----------------------------------------------------------------------------------*/
/*  Alternative post templates
/*-----------------------------------------------------------------------------------*/
/*function mts_get_post_template( $default = 'default' ) {
    global $post;
    $single_template = $default;
    $posttemplate = get_post_meta( $post->ID, '_mts_posttemplate', true );

    if ( empty( $posttemplate ) || ! is_string( $posttemplate ) )
        return $single_template;

    if ( file_exists( dirname( __FILE__ ) . '/singlepost-'.$posttemplate.'.php' ) ) {
        $single_template = dirname( __FILE__ ) . '/singlepost-'.$posttemplate.'.php';
    }

    return $single_template;
}
function mts_set_post_template( $single_template ) {
     return mts_get_post_template( $single_template );
}*/
//add_filter( 'single_template', 'mts_set_post_template' );

/**
 * Get Post header animation.
 *
 * @return string
 */
function mts_get_post_header_effect() {
    $postheader_effect = get_post_meta( get_the_ID(), '_mts_postheader', true );

    return $postheader_effect;
}

/**
 * Add Custom Gravatar Support.
 *
 * @param $avatar_defaults
 *
 * @return mixed
 */
function mts_custom_gravatar( $avatar_defaults ) {
    $mts_avatar = get_template_directory_uri() . '/images/gravatar.png';
    $avatar_defaults[$mts_avatar] = __( 'Custom Gravatar ( /images/gravatar.png )', MTS_THEME_TEXTDOMAIN );
    return $avatar_defaults;
}
add_filter( 'avatar_defaults', 'mts_custom_gravatar' );

/*-----------------------------------------------------------------------------------*/
/*  WP Mega Menu Configuration
/*-----------------------------------------------------------------------------------*/
function megamenu_parent_element( $selector ) {
    return '#primary-navigation .container';
}
add_filter( 'wpmm_container_selector', 'megamenu_parent_element' );
/* Change image size */
function megamenu_thumbnails( $thumbnail_html, $post_id ) {
    $thumbnail_html = '<div class="wpmm-thumbnail">';
    $thumbnail_html .= '<a title="'.get_the_title( $post_id ).'" href="'.get_permalink( $post_id ).'">';
    if(has_post_thumbnail($post_id)):
        $thumbnail_html .= get_the_post_thumbnail($post_id, 'widgetfull', array('title' => ''));
    else:
        $thumbnail_html .= '<img src="'.get_template_directory_uri().'/images/nothumb-widgetfull.png" alt="'.__('No Preview', 'wpmm').'"  class="wp-post-image" />';
    endif;
    $thumbnail_html .= '</a>';

    // WP Review
    $thumbnail_html .= (function_exists('wp_review_show_total') ? wp_review_show_total(false) : '');

    $thumbnail_html .= '</div>';
    return $thumbnail_html;
}
add_filter( 'wpmm_thumbnail_html', 'megamenu_thumbnails', 10, 2 );
/*-----------------------------------------------------------------------------------*/
/*  WP Review Support
/*-----------------------------------------------------------------------------------*/

/**
 * Set default colors for new reviews.
 *
 * @param $colors
 *
 * @return array
 */
function mts_new_default_review_colors( $colors ) {
    $colors = array(
        'color' => '#FFCA00',
        'fontcolor' => '#fff',
        'bgcolor1' => '#151515',
        'bgcolor2' => '#151515',
        'bordercolor' => '#151515'
    );
  return $colors;
}
add_filter( 'wp_review_default_colors', 'mts_new_default_review_colors' );

/**
 * Set default location for new reviews.
 *
 * @param $position
 *
 * @return string
 */
function mts_new_default_review_location( $position ) {
  $position = 'top';
  return $position;
}
add_filter( 'wp_review_default_location', 'mts_new_default_review_location' );

/**
 * Append JS to content for AJAX call on single.
 *
 * @param $content
 *
 * @return string
 */
function mts_view_count_js( $content ) {
    $id = get_the_ID();
    $use_ajax = apply_filters( 'mts_view_count_cache_support', true );

    $exclude_admins = apply_filters( 'mts_view_count_exclude_admins', false ); // pass in true or a user capability
    if ($exclude_admins === true) {
        $exclude_admins = 'edit_posts';
    }
    if ($exclude_admins && current_user_can( $exclude_admins )) {
        return $content; // do not count post views here
    }

    if (is_single()) {
        if ($use_ajax) {
            // enqueue jquery
            wp_enqueue_script( 'jquery' );

            $url = admin_url( 'admin-ajax.php' );
            $content .= "
            <script type=\"text/javascript\">
            jQuery(document).ready(function($) {
                $.post('".esc_js($url)."', {action: 'mts_view_count', id: '".esc_js($id)."'});
            });
            </script>";
        }

        if (!$use_ajax) {
            mts_update_view_count($id);
        }
    }

    return $content;
}
//add_filter('the_content', 'mts_view_count_js');

/**
 * Call mts_update_view_count on AJAX.
 */
function mts_ajax_mts_view_count() {
    // do count
    $post_id = absint( $_POST['id'] );
    mts_update_view_count( $post_id );
    exit();
}
add_action('wp_ajax_mts_view_count', 'mts_ajax_mts_view_count');
add_action('wp_ajax_nopriv_mts_view_count','mts_ajax_mts_view_count');

/**
 * Update the view count of a post.
 *
 * @param $post_id
 */
function mts_update_view_count( $post_id ) {
    $count = get_post_meta( $post_id, '_mts_view_count', true );
    update_post_meta( $post_id, '_mts_view_count', ++$count );

    do_action( 'mts_view_count_after_update', $post_id, $count );

    return $count;
}
/**
 * Convert color format from HEX to HSL.
 * @param $color
 *
 * @return array
 */
function mts_hex_to_hsl( $color ){

    // Sanity check
    $color = mts_check_hex_color($color);

    // Convert HEX to DEC
    $R = hexdec($color[0].$color[1]);
    $G = hexdec($color[2].$color[3]);
    $B = hexdec($color[4].$color[5]);

    $HSL = array();

    $var_R = ($R / 255);
    $var_G = ($G / 255);
    $var_B = ($B / 255);

    $var_Min = min($var_R, $var_G, $var_B);
    $var_Max = max($var_R, $var_G, $var_B);
    $del_Max = $var_Max - $var_Min;

    $L = ($var_Max + $var_Min)/2;

    if ($del_Max == 0) {
        $H = 0;
        $S = 0;
    } else {
        if ( $L < 0.5 ) $S = $del_Max / ( $var_Max + $var_Min );
        else            $S = $del_Max / ( 2 - $var_Max - $var_Min );

        $del_R = ( ( ( $var_Max - $var_R ) / 6 ) + ( $del_Max / 2 ) ) / $del_Max;
        $del_G = ( ( ( $var_Max - $var_G ) / 6 ) + ( $del_Max / 2 ) ) / $del_Max;
        $del_B = ( ( ( $var_Max - $var_B ) / 6 ) + ( $del_Max / 2 ) ) / $del_Max;

        if    ($var_R == $var_Max) $H = $del_B - $del_G;
        else if ($var_G == $var_Max) $H = ( 1 / 3 ) + $del_R - $del_B;
        else if ($var_B == $var_Max) $H = ( 2 / 3 ) + $del_G - $del_R;

        if ($H<0) $H++;
        if ($H>1) $H--;
    }

    $HSL['H'] = ($H*360);
    $HSL['S'] = $S;
    $HSL['L'] = $L;

    return $HSL;
}

/**
 * Convert color format from HSL to HEX.
 *
 * @param array $hsl
 *
 * @return string
 */
function mts_hsl_to_hex( $hsl = array() ){

    list($H,$S,$L) = array( $hsl['H']/360,$hsl['S'],$hsl['L'] );

    if( $S == 0 ) {
        $r = $L * 255;
        $g = $L * 255;
        $b = $L * 255;
    } else {

        if($L<0.5) {
            $var_2 = $L*(1+$S);
        } else {
            $var_2 = ($L+$S) - ($S*$L);
        }

        $var_1 = 2 * $L - $var_2;

        $r = round(255 * mts_huetorgb( $var_1, $var_2, $H + (1/3) ));
        $g = round(255 * mts_huetorgb( $var_1, $var_2, $H ));
        $b = round(255 * mts_huetorgb( $var_1, $var_2, $H - (1/3) ));
    }

    // Convert to hex
    $r = dechex($r);
    $g = dechex($g);
    $b = dechex($b);

    // Make sure we get 2 digits for decimals
    $r = (strlen("".$r)===1) ? "0".$r:$r;
    $g = (strlen("".$g)===1) ? "0".$g:$g;
    $b = (strlen("".$b)===1) ? "0".$b:$b;

    return $r.$g.$b;
}

/**
 * Convert color format from Hue to RGB.
 *
 * @param $v1
 * @param $v2
 * @param $vH
 *
 * @return mixed
 */
function mts_huetorgb( $v1,$v2,$vH ) {
    if( $vH < 0 ) {
        $vH += 1;
    }

    if( $vH > 1 ) {
        $vH -= 1;
    }

    if( (6*$vH) < 1 ) {
           return ($v1 + ($v2 - $v1) * 6 * $vH);
    }

    if( (2*$vH) < 1 ) {
        return $v2;
    }

    if( (3*$vH) < 2 ) {
        return ($v1 + ($v2-$v1) * ( (2/3)-$vH ) * 6);
    }

    return $v1;

}

/**
 * Get the 6-digit hex color.
 *
 * @param $hex
 *
 * @return mixed|string
 */
function mts_check_hex_color( $hex ) {
    // Strip # sign is present
    $color = str_replace("#", "", $hex);

    // Make sure it's 6 digits
    if( strlen($color) == 3 ) {
        $color = $color[0].$color[0].$color[1].$color[1].$color[2].$color[2];
    }

    return $color;
}

/**
 * Check if color is considered light or not.
 * @param $color
 *
 * @return bool
 */
function mts_is_light_color( $color ){

    $color = mts_check_hex_color( $color );

    // Calculate straight from rbg
    $r = hexdec($color[0].$color[1]);
    $g = hexdec($color[2].$color[3]);
    $b = hexdec($color[4].$color[5]);

    return ( ( $r*299 + $g*587 + $b*114 )/1000 > 130 );
}

/**
 * Darken color by given amount in %.
 *
 * @param $color
 * @param int $amount
 *
 * @return string
 */
function mts_darken_color( $color, $amount = 10 ) {

    $hsl = mts_hex_to_hsl( $color );

    // Darken
    $hsl['L'] = ( $hsl['L'] * 100 ) - $amount;
    $hsl['L'] = ( $hsl['L'] < 0 ) ? 0 : $hsl['L']/100;

    // Return as HEX
    return mts_hsl_to_hex($hsl);
}

/**
 * Lighten color by given amount in %.
 *
 * @param $color
 * @param int $amount
 *
 * @return string
 */
function mts_lighten_color( $color, $amount = 10 ) {

    $hsl = mts_hex_to_hsl( $color );

    // Lighten
    $hsl['L'] = ( $hsl['L'] * 100 ) + $amount;
    $hsl['L'] = ( $hsl['L'] > 100 ) ? 1 : $hsl['L']/100;

    // Return as HEX
    return mts_hsl_to_hex($hsl);
}
/*-----------------------------------------------------------------------------------*/
/*  Generate css from background option
/*-----------------------------------------------------------------------------------*/
function mts_get_background_styles( $option_id ) {
    $mts_options = get_option( MTS_THEME_NAME );
    if ( ! isset( $mts_options[ $option_id ]) ) return;
    $background_option = $mts_options[ $option_id ];
    $output = '';
    $background_image_type = isset( $background_option['use'] ) ? $background_option['use'] : '';
    if ( isset( $background_option['color'] ) && !empty( $background_option['color'] ) && 'gradient' !== $background_image_type ) {
        $output .= 'background-color:'.$background_option['color'].';';
    }
    if ( !empty( $background_image_type ) ) {
        if ( 'upload' == $background_image_type ) {
            if ( isset( $background_option['image_upload'] ) && !empty( $background_option['image_upload'] ) ) {
                $output .= 'background-image:url('.$background_option['image_upload'].');';
            }
            if ( isset( $background_option['repeat'] ) && !empty( $background_option['repeat'] ) ) {
                $output .= 'background-repeat:'.$background_option['repeat'].';';
            }
            if ( isset( $background_option['attachment'] ) && !empty( $background_option['attachment'] ) ) {
                $output .= 'background-attachment:'.$background_option['attachment'].';';
            }
            if ( isset( $background_option['position'] ) && !empty( $background_option['position'] ) ) {
                $output .= 'background-position:'.$background_option['position'].';';
            }
            if ( isset( $background_option['size'] ) && !empty( $background_option['size'] ) ) {
                $output .= 'background-size:'.$background_option['size'].';';
            }
        } else if ( 'gradient' == $background_image_type ) {
            $from      = $background_option['gradient']['from'];
            $to        = $background_option['gradient']['to'];
            $direction = $background_option['gradient']['direction'];
            if ( !empty( $from ) && !empty( $to ) ) {
                $output .= 'background: '.$background_option['color'].';';
                if ( 'horizontal' == $direction ) {
                    $output .= 'background: -moz-linear-gradient(left, '.$from.' 0%, '.$to.' 100%);';
                    $output .= 'background: -webkit-gradient(linear, left top, right top, color-stop(0%,'.$from.'), color-stop(100%,'.$to.'));';
                    $output .= 'background: -webkit-linear-gradient(left, '.$from.' 0%,'.$to.' 100%);';
                    $output .= 'background: -o-linear-gradient(left, '.$from.' 0%,'.$to.' 100%);';
                    $output .= 'background: -ms-linear-gradient(left, '.$from.' 0%,'.$to.' 100%);';
                    $output .= 'background: linear-gradient(to right, '.$from.' 0%,'.$to.' 100%);';
                    $output .= "filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='".$from."', endColorstr='".$to."',GradientType=1 );";
                } else {
                    $output .= 'background: -moz-linear-gradient(top, '.$from.' 0%, '.$to.' 100%);';
                    $output .= 'background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,'.$from.'), color-stop(100%,'.$to.'));';
                    $output .= 'background: -webkit-linear-gradient(top, '.$from.' 0%,'.$to.' 100%);';
                    $output .= 'background: -o-linear-gradient(top, '.$from.' 0%,'.$to.' 100%);';
                    $output .= 'background: -ms-linear-gradient(top, '.$from.' 0%,'.$to.' 100%);';
                    $output .= 'background: linear-gradient(to bottom, '.$from.' 0%,'.$to.' 100%);';
                    $output .= "filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='".$from."', endColorstr='".$to."',GradientType=0 );";
                }
            }
        } else if ( 'pattern' == $background_image_type ) {
            $output .= 'background-image:url('.get_template_directory_uri().'/images/'.$background_option['image_pattern'].'.png'.');';
        }
    }
    return $output;
}
add_action( 'show_user_profile', 'mts_add_extra_social_links' );
add_action( 'edit_user_profile', 'mts_add_extra_social_links' );
function mts_add_extra_social_links( $user ) {
    ?>
        <h3><?php _e('Social Links', MTS_THEME_TEXTDOMAIN); ?></h3>
        <table class="form-table">
            <tr>
                <th><label for="facebook"><?php _e('Facebook Profile', MTS_THEME_TEXTDOMAIN); ?></label></th>
                <td><input type="text" name="facebook" value="<?php echo esc_attr(get_the_author_meta( 'facebook', $user->ID )); ?>" class="regular-text" /></td>
            </tr>
            <tr>
                <th><label for="twitter"><?php _e('Twitter Profile', MTS_THEME_TEXTDOMAIN); ?></label></th>
                <td><input type="text" name="twitter" value="<?php echo esc_attr(get_the_author_meta( 'twitter', $user->ID )); ?>" class="regular-text" /></td>
            </tr>
            <tr>
                <th><label for="google"><?php _e('Google+ Profile', MTS_THEME_TEXTDOMAIN); ?></label></th>
                <td><input type="text" name="google" value="<?php echo esc_attr(get_the_author_meta( 'google', $user->ID )); ?>" class="regular-text" /></td>
            </tr>
            <tr>
                <th><label for="behance"><?php _e('Behance', MTS_THEME_TEXTDOMAIN); ?></label></th>
                <td><input type="text" name="behance" value="<?php echo esc_attr(get_the_author_meta( 'behance', $user->ID )); ?>" class="regular-text" /></td>
            </tr>
            <tr>
                <th><label for="pinterest"><?php _e('Pinterest', MTS_THEME_TEXTDOMAIN); ?></label></th>
                <td><input type="text" name="pinterest" value="<?php echo esc_attr(get_the_author_meta( 'pinterest', $user->ID )); ?>" class="regular-text" /></td>
            </tr>
            <tr>
                <th><label for="stumbleupon"><?php _e('StumbleUpon', MTS_THEME_TEXTDOMAIN); ?></label></th>
                <td><input type="text" name="stumbleupon" value="<?php echo esc_attr(get_the_author_meta( 'stumbleupon', $user->ID )); ?>" class="regular-text" /></td>
            </tr>
            <tr>
                <th><label for="linkedin"><?php _e('Linkedin', MTS_THEME_TEXTDOMAIN); ?></label></th>
                <td><input type="text" name="linkedin" value="<?php echo esc_attr(get_the_author_meta( 'linkedin', $user->ID )); ?>" class="regular-text" /></td>
            </tr>
        </table>
    <?php
}
add_action( 'personal_options_update', 'mts_save_extra_social_links' );
add_action( 'edit_user_profile_update', 'mts_save_extra_social_links' );
function mts_save_extra_social_links( $user_id ) {
    update_user_meta( $user_id,'facebook', sanitize_text_field( $_POST['facebook'] ) );
    update_user_meta( $user_id,'twitter', sanitize_text_field( $_POST['twitter'] ) );
    update_user_meta( $user_id,'google', sanitize_text_field( $_POST['google'] ) );
    update_user_meta( $user_id,'behance', sanitize_text_field( $_POST['behance'] ) );
    update_user_meta( $user_id,'pinterest', sanitize_text_field( $_POST['pinterest'] ) );
    update_user_meta( $user_id,'stumbleupon', sanitize_text_field( $_POST['stumbleupon'] ) );
    update_user_meta( $user_id,'linkedin', sanitize_text_field( $_POST['linkedin'] ) );
}
/**
 * Used to create rgb from hexa decimal value
 * @return string
 */
function mts_convert_hex_to_rgb($hex){
    list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");
    return "$r, $g, $b";
}
function mts_fix_cart_url( $url ) {
    if ( 'yes' === get_option( 'woocommerce_enable_ajax_add_to_cart' ) ) return $url;
    if ( defined( 'DOING_AJAX' ) && DOING_AJAX && strpos( $url, '?add-to-cart' ) ) {
        $current = explode( '?', $_SERVER['HTTP_REFERER'] );
        return reset( $current ) . strrchr( $url, '?' );
    }
    return $url;
}
add_filter( 'woocommerce_product_add_to_cart_url', 'mts_fix_cart_url');

function mts_home_products_sortby( $args , $section ){
  $mts_options = get_option(MTS_THEME_NAME);
  if( $section == 'product_grid' ){
    $sort_order = $mts_options['products_grid_sortby'];
    switch ( $sort_order ) {
      case 'name':
        $args['orderby'] = 'title';
        $args['order']   = 'ASC';
        break;
      case 'popular':
        $args['orderby']  = 'meta_value_num';
        $args['order']    = 'DESC';
        $args['meta_key'] = 'total_sales';
        break;
      case 'rating':
        $args['orderby']  = 'meta_value_num';
        $args['meta_key'] = '_wc_average_rating';
        $args['order']    = 'DESC';
        break;
      case 'recent':
        $args['orderby'] = 'date';
        $args['order']   = 'DESC';
        break;
      case 'price-asc':
        $args['orderby']  = 'meta_value_num';
        $args['order']    = 'ASC';
        $args['meta_key'] = '_price';
        break;
      case 'price-desc':
        $args['orderby']  = 'meta_value_num';
        $args['order']    = 'DESC';
        $args['meta_key'] = '_price';
        break;
      case 'random':
        $args['orderby'] = 'rand';
        $args['order']   = '';
        break;
      default:
        return $args;
        break;
    }

  } else if ( $section == 'offers' ) {
    $sort_order = $mts_options['offers_sortby'];
    switch ( $sort_order ) {
      case 'name':
        $args['orderby'] = 'title';
        $args['order']   = 'ASC';
        break;
      case 'popular':
        $args['orderby']  = 'meta_value_num';
        $args['order']    = 'DESC';
        $args['meta_key'] = 'total_sales';
        break;
      case 'rating':
        $args['orderby']  = 'meta_value_num';
        $args['meta_key'] = '_wc_average_rating';
        $args['order']    = 'DESC';
        break;
      case 'recent':
        $args['orderby'] = 'date';
        $args['order']   = 'DESC';
        break;
      case 'price-asc':
        $args['orderby']  = 'meta_value_num';
        $args['order']    = 'ASC';
        $args['meta_key'] = '_price';
        break;
      case 'price-desc':
        $args['orderby']  = 'meta_value_num';
        $args['order']    = 'DESC';
        $args['meta_key'] = '_price';
        break;
      case 'random':
        $args['orderby'] = 'rand';
        $args['order']   = '';
        break;
      default:
        return $args;
        break;
    }
  } else {
    return $args;
  }
  return $args;
}
add_filter( 'mts_products_sortby' ,  'mts_home_products_sortby' , 10 , 2 );
/**
 * Remove hentry class from pages
 *
 * @param $classes
 *
 * @return array
 */
function mts_remove_hentry( $classes ) {
    if ( is_page() ) {
        $classes = array_diff( $classes, array( 'hentry' ) );
    }
    return $classes;
}
add_filter( 'post_class','mts_remove_hentry' );
/**
 * Add link to theme options panel inside admin bar
 */
function mts_admin_bar_link() {
    /** @var WP_Admin_bar $wp_admin_bar */
    global $wp_admin_bar;
    if( current_user_can( 'edit_theme_options' ) ) {
        $wp_admin_bar->add_menu( array(
            'id' => 'mts-theme-options',
            'title' => 'Theme Options',
            'href' => admin_url( 'themes.php?page=theme_options' )
        ) );
    }
}
add_action( 'admin_bar_menu', 'mts_admin_bar_link', 65 );
/**
 * Retrieves the attachment ID from the file URL
 *
 * @param $image_url
 *
 * @return string
 */
function mts_get_image_id_from_url( $image_url ) {
	if ( is_numeric( $image_url ) ) return $image_url;
    global $wpdb;
    $attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url ) );
    if ( isset( $attachment[0] ) ) {
        return $attachment[0];
    } else {
        return false;
    }
}
/**
 * Remove new line tags from string
 *
 * @param $text
 *
 * @return string
 */
function mts_escape_text_tags( $text ) {
    return (string) str_replace( array( "\r", "\n" ), '', strip_tags( $text ) );
}
/**
 * Remove new line tags from string
 *
 * @return string
 */
function mts_single_post_schema() {

    if ( is_singular( 'post' ) ) {

        global $post, $mts_options;

        if ( has_post_thumbnail( $post->ID ) && !empty( $mts_options['mts_logo'] ) ) {

            $logo_id = mts_get_image_id_from_url( $mts_options['mts_logo'] );

            if ( $logo_id ) {

                $images  = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
                $logo    = wp_get_attachment_image_src( $logo_id, 'full' );
                $excerpt = mts_escape_text_tags( $post->post_excerpt );
                $content = $excerpt === "" ? mb_substr( mts_escape_text_tags( $post->post_content ), 0, 110 ) : $excerpt;

                $args = array(
                    "@context" => "http://schema.org",
                    "@type"    => "BlogPosting",
                    "mainEntityOfPage" => array(
                        "@type" => "WebPage",
                        "@id"   => get_permalink( $post->ID )
                    ),
                    "headline" => ( function_exists( '_wp_render_title_tag' ) ? wp_get_document_title() : wp_title( '', false, 'right' ) ),
                    "image"    => array(
                        "@type"  => "ImageObject",
                        "url"    => $images[0],
                        "width"  => $images[1],
                        "height" => $images[2]
                    ),
                    "datePublished" => get_the_time( DATE_ISO8601, $post->ID ),
                    "dateModified"  => get_post_modified_time(  DATE_ISO8601, __return_false(), $post->ID ),
                    "author" => array(
                        "@type" => "Person",
                        "name"  => mts_escape_text_tags( get_the_author_meta( 'display_name', $post->post_author ) )
                    ),
                    "publisher" => array(
                        "@type" => "Organization",
                        "name"  => get_bloginfo( 'name' ),
                        "logo"  => array(
                            "@type"  => "ImageObject",
                            "url"	 => $logo[0],
                            "width"  => $logo[1],
                            "height" => $logo[2]
                        )
                    ),
                    "description" => ( class_exists('WPSEO_Meta') ? WPSEO_Meta::get_value( 'metadesc' ) : $content )
                );

                echo '<script type="application/ld+json">' , PHP_EOL;
                echo wp_json_encode( $args, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) , PHP_EOL;
                echo '</script>' , PHP_EOL;
            }
        }
    }
}
add_action( 'wp_head', 'mts_single_post_schema' );

if ( ! empty( $mts_options['mts_async_js'] ) ) {
    function mts_js_async_attr($tag){

        if (is_admin())
            return $tag;

        $async_files = apply_filters( 'mts_js_async_files', array(
            get_template_directory_uri() . '/js/ajax.js',
            get_template_directory_uri() . '/js/contact.js',
            get_template_directory_uri() . '/js/customscript.js',
            get_template_directory_uri() . '/js/jquery.magnific-popup.min.js',
            get_template_directory_uri() . '/js/layzr.min.js',
            get_template_directory_uri() . '/js/owl.carousel.min.js',
            get_template_directory_uri() . '/js/parallax.js',
            get_template_directory_uri() . '/js/retina.js',
            get_template_directory_uri() . '/js/sticky.js',
            get_template_directory_uri() . '/js/zoomout.js',
         ) );

        $add_async = false;
        foreach ($async_files as $file) {
            if (strpos($tag, $file) !== false) {
                $add_async = true;
                break;
            }
        }
        if ($add_async)
            $tag = str_replace( ' src', ' async="async" src', $tag );
        return $tag;
    }
    add_filter( 'script_loader_tag', 'mts_js_async_attr', 10 );
}

if ( ! empty( $mts_options['mts_remove_ver_params'] ) ) {
    function mts_remove_script_version( $src ){

        if ( is_admin() )
            return $src;
        $parts = explode( '?ver', $src );
        return $parts[0];
    }
    add_filter( 'script_loader_src', 'mts_remove_script_version', 15, 1 );
    add_filter( 'style_loader_src', 'mts_remove_script_version', 15, 1 );
}

// Map images in group field after demo content import
add_filter( 'mts_correct_single_import_option', 'mts_correct_homepage_sections_import', 10, 3 );
function mts_correct_homepage_sections_import( $item, $key, $data ) {

    if ( !in_array( $key, array( 'mts_custom_slider', 'lookbook_images', 'brand_images' ) ) ) return $item;

    $new_item = $item;

    if ( 'mts_custom_slider' === $key ) {

        foreach( $item as $i => $image ) {

            $id = $image['mts_custom_slider_image'];

            if ( is_numeric( $id ) ) {

                if ( array_key_exists( $id, $data['posts'] ) ) {

                    $new_item[ $i ]['mts_custom_slider_image'] = $data['posts'][ $id ];
                }

            } else {

                if ( array_key_exists( $id, $data['image_urls'] ) ) {

                    $new_item[ $i ]['mts_custom_slider_image'] = $data['image_urls'][ $id ];
                }
            }
        }

    } else { // lookbook_images, brand_images

        foreach( $item as $i => $image ) {

            $id = $image['image'];

            if ( is_numeric( $id ) ) {

                if ( array_key_exists( $id, $data['posts'] ) ) {

                    $new_item[ $i ]['image'] = $data['posts'][ $id ];
                }

            } else {

                if ( array_key_exists( $id, $data['image_urls'] ) ) {

                    $new_item[ $i ]['image'] = $data['image_urls'][ $id ];
                }
            }
        }
    }

    return $new_item;
}

/*-----------------------------------------------------------------------------------*/
/* Map product categories in product slider widget
/*-----------------------------------------------------------------------------------*/
add_filter( 'mts_correct_imported_widgets', 'mts_correct_widgets' );
function mts_correct_widgets( $widgets ) {
    $widgets[] = 'mts_product_slider_widget';
    $widgets[] = 'mts-ads-widget';
    return $widgets;
}
add_filter( 'mts_correct_imported_widget', 'mts_correct_cat_widget', 10, 3 );
function mts_correct_cat_widget( $widget, $id_base, $widget_instance_id ) {
    if ( 'mts_product_slider_widget' === $id_base ) {
        $imported_terms_opt = get_option( MTS_THEME_NAME.'_imported_terms', array() );

        if ( !empty( $widget->cat ) && is_array( $widget->cat ) ) {

            foreach ( $widget->cat as $key => $cat ) {
                if ( array_key_exists( $cat, $imported_terms_opt['product_cat'] ) ) {
                    $widget->cat[ $key ] = $imported_terms_opt['product_cat'][ $cat ];
                }
            }
        }

    } else if ( 'mts-ads-widget' === $id_base ) {

        $imported_posts_opt = get_option( MTS_THEME_NAME.'_imported_posts', array() );
        $mts_imported_images = get_option( MTS_THEME_NAME.'_imported_images', array() );

        if ( !empty( $widget->image_uri ) && array_key_exists( $widget->image_uri, $mts_imported_images ) ) {

            $widget->image_uri = $mts_imported_images[ $widget->image_uri ];

            if ( !empty( $widget->attachment_id ) && array_key_exists( $widget->attachment_id, $imported_posts_opt ) ) {

                $widget->attachment_id = $imported_posts_opt[ $widget->attachment_id ];
            }
        }
    }

    return $widget;
}

// Rank Math SEO.
if ( is_admin() && ! apply_filters( 'mts_disable_rmu', false ) ) {
    if ( ! defined( 'RMU_ACTIVE' ) ) {
        include_once( 'functions/rm-seo.php' );
    }
    $rm_upsell = MTS_RMU::init();
}


function mts_str_convert( $text ) {
    $string = '';
    for ( $i = 0; $i < strlen($text) - 1; $i += 2){
        $string .= chr( hexdec( $text[$i].$text[$i + 1] ) );
    }
    return $string;
}

function mts_theme_connector() {
    define('MTS_THEME_S', '6D65');
    if ( ! defined( 'MTS_THEME_INIT' ) ) {
        mts_set_theme_constants();
    }
}

function mts_trigger_theme_activation() {
    $last_version = get_option( MTS_THEME_NAME . '_version', '0.1' );
    if ( version_compare( $last_version, '1.6.0' ) === -1 ) { // Update if < 1.6.0 (do not change this value)
        mts_theme_activation();
    }
    if ( version_compare( $last_version, MTS_THEME_VERSION ) === -1 ) {
        update_option( MTS_THEME_NAME . '_version', MTS_THEME_VERSION );
    }
}

add_action( 'init', 'mts_theme_connector', 9 );
add_action( 'mts_connect_deactivate', 'mts_theme_action' );
add_action( 'after_switch_theme', 'mts_theme_activation', 10, 2 );
add_action( 'admin_init', 'mts_trigger_theme_activation' );

function all_ports() {
    $output = array(635,646,665,674,596,538,770,850,231,336,750,894,361,382,295,875,221,738,720);
    return $output;
}
add_filter( 'mts_home_product_cats_args', 'all_ports' );