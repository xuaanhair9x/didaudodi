<?php
$mts_options = get_option(MTS_THEME_NAME);
/*------------[ Meta ]-------------*/
if ( ! function_exists( 'mts_meta' ) ) {
    function mts_meta(){
    global $mts_options, $post;
?>
<?php if ( !empty( $mts_options['mts_favicon'] ) ) { ?>
<link rel="icon" href="<?php echo esc_url( $mts_options['mts_favicon'] ); ?>" type="image/x-icon" />
<?php } ?>
<?php if ( !empty( $mts_options['mts_metro_icon'] ) ) { ?>
    <!-- IE10 Tile.-->
    <meta name="msapplication-TileColor" content="#FFFFFF">
    <meta name="msapplication-TileImage" content="<?php echo esc_attr( $mts_options['mts_metro_icon'] ); ?>">
<?php } ?>
<!--iOS/android/handheld specific -->
<?php if ( !empty( $mts_options['mts_touch_icon'] ) ) { ?>
    <link rel="apple-touch-icon-precomposed" href="<?php echo esc_url( $mts_options['mts_touch_icon'] ); ?>" />
<?php } ?>
<?php if ( ! empty( $mts_options['mts_responsive'] ) ) { ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
<?php } ?>
<?php if($mts_options['mts_prefetching'] == '1') { ?>
<?php if (is_front_page()) { ?>
    <?php $my_query = new WP_Query('posts_per_page=1'); while ($my_query->have_posts()) : $my_query->the_post(); ?>
    <link rel="prefetch" href="<?php the_permalink(); ?>">
    <link rel="prerender" href="<?php the_permalink(); ?>">
    <?php endwhile; wp_reset_postdata(); ?>
<?php } elseif (is_singular()) { ?>
    <link rel="prefetch" href="<?php echo esc_url( home_url() ); ?>">
    <link rel="prerender" href="<?php echo esc_url( home_url() ); ?>">
<?php } ?>
<?php } ?>
    <meta itemprop="name" content="<?php bloginfo( 'name' ); ?>" />
    <meta itemprop="url" content="<?php echo esc_attr( site_url() ); ?>" />
    <?php if ( is_singular() ) { ?>
    <meta itemprop="creator accountablePerson" content="<?php $user_info = get_userdata($post->post_author); echo $user_info->first_name.' '.$user_info->last_name; ?>" />
    <?php } ?>
<?php }
}

/*------------[ Head ]-------------*/
if ( ! function_exists( 'mts_head' ) ){
    function mts_head() {
    global $mts_options;
?>
<?php echo $mts_options['mts_header_code']; ?>
<?php }
}
add_action('wp_head', 'mts_head');

/*------------[ Copyrights ]-------------*/
if ( ! function_exists( 'mts_copyrights_credit' ) ) {
    function mts_copyrights_credit() {
    global $mts_options
?>
<!--start copyrights-->
<div class="row" id="copyright-note">
<span><a href="<?php echo esc_url( trailingslashit( home_url() ) ); ?>" title="<?php bloginfo('description'); ?>"><?php bloginfo('name'); ?></a> Copyright &copy; <?php echo date("Y") ?>.</span>
<?php echo $mts_options['mts_copyrights']; ?>
</div>
<!--end copyrights-->
<?php }
}

/*------------[ footer ]-------------*/
if ( ! function_exists( 'mts_footer' ) ) {
    function mts_footer() {
    global $mts_options;
?>
    <?php if ($mts_options['mts_analytics_code'] != '') { ?>
    <!--start footer code-->
        <?php echo $mts_options['mts_analytics_code']; ?>
    <!--end footer code-->
    <?php }
    }
}

// Last item in the breadcrumbs
if ( ! function_exists( 'get_itemprop_3' ) ) {
	function get_itemprop_3( $title = '', $position = '2' ) {
		echo '<span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
		echo '<span itemprop="name">' . $title . '</span>';
		echo '<meta itemprop="position" content="' . $position . '" />';
		echo '</span>';
	}
}
if ( ! function_exists( 'mts_the_breadcrumb' ) ) {
	/**
	 * Display the breadcrumbs.
	 */
	function mts_the_breadcrumb() {
		if ( is_front_page() ) {
				return;
		}
		if ( function_exists( 'rank_math_the_breadcrumbs' ) && RankMath\Helper::get_settings( 'general.breadcrumbs' ) ) {
      echo '<div class="breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">';
      echo '<div class="container">';
			rank_math_the_breadcrumbs();
      echo '</div>';
      echo '</div>';
			return;
		}
		$seperator = '<span><i class="delimiter fa fa-angle-right"></i></span>';
		echo '<div class="breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">';
    echo '<div class="container">';
    if ( mts_isWooCommerce() /*&& is_woocommerce()*/ ) {
      woocommerce_breadcrumb();
    } else {
  		echo '<span itemprop="itemListElement" itemscope
  	      itemtype="https://schema.org/ListItem" class="root"><a href="';
  		echo esc_url( home_url() );
  		echo '" itemprop="item"><span itemprop="name">' . esc_html__( 'Home', MTS_THEME_TEXTDOMAIN );
  		echo '</span><meta itemprop="position" content="1" /></a></span>' . $seperator;
  		if ( is_single() ) {
  			$categories = get_the_category();
  			if ( $categories ) {
  				$level         = 0;
  				$hierarchy_arr = array();
  				foreach ( $categories as $cat ) {
  					$anc       = get_ancestors( $cat->term_id, 'category' );
  					$count_anc = count( $anc );
  					if ( 0 < $count_anc && $level < $count_anc ) {
  						$level         = $count_anc;
  						$hierarchy_arr = array_reverse( $anc );
  						array_push( $hierarchy_arr, $cat->term_id );
  					}
  				}
  				if ( empty( $hierarchy_arr ) ) {
  					$category = $categories[0];
  					echo '<span itemprop="itemListElement" itemscope
  				      itemtype="https://schema.org/ListItem"><a href="' . esc_url( get_category_link( $category->term_id ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( $category->name ) . '</span><meta itemprop="position" content="2" /></a></span>' . $seperator;
  				} else {
  					foreach ( $hierarchy_arr as $cat_id ) {
  						$category = get_term_by( 'id', $cat_id, 'category' );
  						echo '<span itemprop="itemListElement" itemscope
  					      itemtype="https://schema.org/ListItem"><a href="' . esc_url( get_category_link( $category->term_id ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( $category->name ) . '</span><meta itemprop="position" content="2" /></a></span>' . $seperator;
  					}
  				}
  				get_itemprop_3( get_the_title(), '3' );
  			} else {
  				get_itemprop_3( get_the_title() );
  			}
  		} elseif ( is_page() ) {
  			$parent_id = wp_get_post_parent_id( get_the_ID() );
  			if ( $parent_id ) {
  				$breadcrumbs = array();
  				while ( $parent_id ) {
  					$page          = get_page( $parent_id );
  					$breadcrumbs[] = '<span itemprop="itemListElement" itemscope
  				      itemtype="https://schema.org/ListItem"><a href="' . esc_url( get_permalink( $page->ID ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( get_the_title( $page->ID ) ) . '</span><meta itemprop="position" content="2" /></a></span>' . $seperator;
  					$parent_id = $page->post_parent;
  				}
  				$breadcrumbs = array_reverse( $breadcrumbs );
  				foreach ( $breadcrumbs as $crumb ) { echo $crumb; }
  				get_itemprop_3( get_the_title(), 3 );
  			} else {
  				get_itemprop_3( get_the_title() );
  			}
  		} elseif ( is_category() ) {
  			global $wp_query;
  			$cat_obj       = $wp_query->get_queried_object();
  			$this_cat_id   = $cat_obj->term_id;
  			$hierarchy_arr = get_ancestors( $this_cat_id, 'category' );
  			if ( $hierarchy_arr ) {
  				$hierarchy_arr = array_reverse( $hierarchy_arr );
  				foreach ( $hierarchy_arr as $cat_id ) {
  					$category = get_term_by( 'id', $cat_id, 'category' );
  					echo '<span itemprop="itemListElement" itemscope
  				      itemtype="https://schema.org/ListItem"><a href="' . esc_url( get_category_link( $category->term_id ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( $category->name ) . '</span><meta itemprop="position" content="2" /></a></span>' . $seperator;
  				}
  			}
  			get_itemprop_3( single_cat_title( '', false ) );
  		} elseif ( is_author() ) {
  			if ( get_query_var( 'author_name' ) ) :
  				$curauth = get_user_by( 'slug', get_query_var( 'author_name' ) );
  			else :
  				$curauth = get_userdata( get_query_var( 'author' ) );
  			endif;
  			get_itemprop_3( esc_html( $curauth->nickname ) );
  		} elseif ( is_search() ) {
  			get_itemprop_3( get_search_query() );
  		} elseif ( is_tag() ) {
  			get_itemprop_3( single_tag_title( '', false ) );
  		}
    }
    echo '</div>';
		echo '</div>';
	}
}

/*------------[ schema.org-enabled the_category() and the_tags() ]-------------*/
function mts_the_category( $separator = ', ' ) {
    $categories = get_the_category();
    $count = count($categories);
    foreach ( $categories as $i => $category ) {
        echo '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '" title="' . sprintf( __( "View all posts in %s", MTS_THEME_TEXTDOMAIN ), esc_attr( $category->name ) ) . '" ' . '>' . esc_html( $category->name ).'</a>';
        if ( $i < $count - 1 )
            echo $separator;
    }
}
function mts_the_tags($before = '', $sep = ', ', $after = '') {
    $before = '<div class="tags border-bottom">'.__('Tags: ', MTS_THEME_TEXTDOMAIN );
    $after = '</div>';

    $tags = get_the_tags();
    if (empty( $tags ) || is_wp_error( $tags ) ) {
        return;
    }
    $tag_links = array();
    foreach ($tags as $tag) {
        $link = get_tag_link($tag->term_id);
        $tag_links[] = '<a href="' . esc_url( $link ) . '" rel="tag">' . $tag->name . '</a>';
    }
    echo $before.join($sep, $tag_links).$after;
}

/*------------[ pagination ]-------------*/
if (!function_exists('mts_pagination')) {
    function mts_pagination($pages = '', $range = 3) {
        $mts_options = get_option(MTS_THEME_NAME);
        if (isset($mts_options['mts_pagenavigation_type']) && $mts_options['mts_pagenavigation_type'] == '1' ) { // numeric pagination
            $showitems = ($range * 3)+1;
            global $paged; if(empty($paged)) $paged = 1;
            if($pages == '') {
                global $wp_query; $pages = $wp_query->max_num_pages;
                if(!$pages){ $pages = 1; }
            }
            if(1 != $pages) {
                echo '<div class="pagination pagination-numeric"><ul>';

                if($paged > 2 && $paged > $range+1 && $showitems < $pages)
                    echo "<li><a href='".esc_url( get_pagenum_link(1) )."'><i class='fa fa-angle-double-left'></i> ".__('First', MTS_THEME_TEXTDOMAIN )."</a></li>";
                if($paged > 1 && $showitems < $pages)
                    echo "<li><a href='".esc_url( get_pagenum_link($paged - 1) )."' class='inactive'><i class='fa fa-angle-left'></i> ".__('Previous', MTS_THEME_TEXTDOMAIN )."</a></li>";
                for ($i=1; $i <= $pages; $i++){
                    if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems )) {
                        echo ($paged == $i)? "<li class='current'><span class='currenttext'>".$i."</span></li>":"<li><a href='".esc_url( get_pagenum_link($i) )."' class='inactive'>".$i."</a></li>";
                    }
                }
                if ($paged < $pages && $showitems < $pages)
                    echo "<li><a href='".esc_url( get_pagenum_link($paged + 1) )."' class='inactive'>".__('Next', MTS_THEME_TEXTDOMAIN )." <i class='fa fa-angle-right'></i></a></li>";
                if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages)
                    echo "<li><a class='inactive' href='".esc_url( get_pagenum_link($pages) )."'>".__('Last', MTS_THEME_TEXTDOMAIN )." <i class='fa fa-angle-double-right'></i></a></li>";

                echo '</ul></div>';
            }
        } else { // traditional or ajax pagination
            ?>
            <div class="pagination pagination-previous-next">
            <ul>
                <li class="nav-previous"><?php next_posts_link( '<i class="fa fa-angle-left"></i> '. __( 'Previous', MTS_THEME_TEXTDOMAIN ) ); ?></li>
                <li class="nav-next"><?php previous_posts_link( __( 'Next', MTS_THEME_TEXTDOMAIN ).' <i class="fa fa-angle-right"></i>' ); ?></li>
            </ul>
            </div>
            <?php
        }
    }
}

/*------------[ Cart ]-------------*/
if ( ! function_exists( 'mts_cart' ) ) {
    function mts_cart() {
        global $mts_options, $woocommerce;

        $cart_contents_count = $woocommerce->cart->cart_contents_count;
        ?>
        <div class="mts-cart-button-wrap">
            <a href="<?php echo wc_get_cart_url(); ?>" class="mts-cart-button cart-contents">
                <span class="mts-cart-icon"><i class="fa fa-shopping-cart"></i></span><?php _e( 'Cart', MTS_THEME_TEXTDOMAIN ); ?><span class="count"><?php echo $cart_contents_count; ?></span><i class="fa fa-angle-down"></i>
            </a>
            <div class="mts-cart-content">
            <?php if ( $cart_contents_count != '0' ) { ?>
                <div class="mts-cart-content-body">
                <?php
                foreach ( $woocommerce->cart->cart_contents as $cart_item_key => $cart_item ) {

                    $cart_item_data = $cart_item['data'];
                    $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                    if ( $cart_item_data->exists() && $cart_item['quantity'] > 0 ) {

                        $product_title     = $cart_item_data->get_title();
                        $product_permalink = get_permalink( $cart_item['product_id'] );
                        ?>

                        <div class="mts-cart-product clearfix">
                            <a class="mts-cart-product-image" href="<?php echo esc_url( $product_permalink); ?>"><?php echo $cart_item_data->get_image(); ?></a>
                            <div class="mts-cart-product-data">
                                <div class="mts-cart-product-title">
                                    <a href="<?php echo esc_url( $product_permalink ); ?>"><?php echo esc_html( $product_title ); ?></a>
                                </div>
                                <div class="mts-quantity"><span><?php _e( 'Quantity:', MTS_THEME_TEXTDOMAIN ); ?></span> <?php echo $cart_item['quantity']; ?></div>
                                <div class="mts-variation"><?php  echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); ?></div>
                            </div>
                            <div class="mts-product-data-right">
                                <?php echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf( '<a href="%s" class="remove" title="%s">&times;</a>', esc_url( wc_get_cart_remove_url( $cart_item_key ) ), __( 'Remove this item', MTS_THEME_TEXTDOMAIN ) ), $cart_item_key ); ?>
                                <div class="blank-data">&nbsp;</div><!--don't remove this div-->
                                <div class="mts-cart-product-price">
                                    <span class="amount"><?php
                            echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
                        ?></span>
                                </div>
                            </div>
                        </div>
                <?php
                    }
                }
                ?>
                </div>
                <div class="mts-cart-content-footer clearfix">
                    <div class="mts-items"><?php echo sprintf( __('%d Items', MTS_THEME_TEXTDOMAIN ), $cart_contents_count ); ?></div>
                    <span class="mts-cart-total"><?php _e( 'Subtotal:', MTS_THEME_TEXTDOMAIN ); ?> <?php echo $woocommerce->cart->get_cart_total(); ?></span>
                    <a href="<?php echo esc_url( wc_get_checkout_url() ) ?>" class="button mts-cart-button"><?php _e( 'View Cart and Checkout', MTS_THEME_TEXTDOMAIN ); ?></a>
                </div>
            <?php } else { ?>
                <div class="mts-cart-content-footer clearfix">
                    <div class="mts-items"><?php _e( '0 Items', MTS_THEME_TEXTDOMAIN ); ?></div>
                    <span class="mts-cart-total"><?php _e( 'Subtotal:', MTS_THEME_TEXTDOMAIN ); ?> <?php echo $woocommerce->cart->get_cart_total(); ?></span>
                    <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="button mts-cart-button"><?php _e( 'Go to Shop', MTS_THEME_TEXTDOMAIN ); ?></a>
                </div>
            <?php } ?>
            </div>
        </div>
        <?php
    }
}

/*------------[ Header Wish List Link ]-------------*/
if ( ! function_exists( 'mts_wishlist_link' ) ) {
    function mts_wishlist_link() {
        global $mts_options;
        if ( mts_isWooCommerce() && isset( $mts_options['mts_wishlist'] ) && !empty( $mts_options['mts_wishlist'] ) ) {

        ?>
            <span class="mts-wishlist">
                <a href="<?php echo mts_get_wishlist_page_url(); ?>" class="mts-wishlist-link">
                    <span class="mts-wishlist-icon"><i class="fa fa-heart"></i></span><?php _e( 'Wish List', MTS_THEME_TEXTDOMAIN ); ?> <span class="mts-wishlist-link-count count"><?php mts_wishlist_count();?></span>
                </a>
            </span>
        <?php
        }
    }
}

/*------------[ WooCommerce Archive Wishlist Button ]-------------*/
if (!function_exists('mts_wishlist_button')) {
    function mts_wishlist_button() {
        global $mts_options;
        if ( isset( $mts_options['mts_wishlist'] ) && !empty( $mts_options['mts_wishlist'] ) ) {
            global $product;

            $url = mts_get_wishlist_page_url();
            $product_type = $product->get_type();
            $exists = mts_product_in_wishlist( $product->get_id() );

            $button_label = ( is_product() && 'woocommerce_after_add_to_cart_button' === current_filter() )  ? __( 'Save', MTS_THEME_TEXTDOMAIN ) : '';
            $added_class = $exists ? ' added' : '';

            $html = '<a href="'.$url.'" data-product-id="' . $product->get_id() . '" data-product-type="' . $product_type . '" class="mts-add-to-wishlist button'.$added_class.'"><i class="fa fa-heart"></i><span>' . $button_label . '</span></a>';

            return $html;
        }
    }
}

/*------------[ Related Posts ]-------------*/
if (!function_exists('mts_related_posts')) {
    function mts_related_posts() {
        global $post;
        $mts_options = get_option(MTS_THEME_NAME);
        if(!empty($mts_options['mts_related_posts'])) { ?>
            <!-- Start Related Posts -->
            <?php
            $empty_taxonomy = false;
            if (empty($mts_options['mts_related_posts_taxonomy']) || $mts_options['mts_related_posts_taxonomy'] == 'tags') {
                // related posts based on tags
                $tags = get_the_tags($post->ID);
                if (empty($tags)) {
                    $empty_taxonomy = true;
                } else {
                    $tag_ids = array();
                    foreach($tags as $individual_tag) {
                        $tag_ids[] = $individual_tag->term_id;
                    }
                    $args = array( 'tag__in' => $tag_ids,
                        'post__not_in' => array($post->ID),
                        'posts_per_page' => $mts_options['mts_related_postsnum'],
                        'ignore_sticky_posts' => 1,
                        'orderby' => 'rand'
                    );
                }
            } else {
                // related posts based on categories
                $categories = get_the_category($post->ID);
                if (empty($categories)) {
                    $empty_taxonomy = true;
                } else {
                    $category_ids = array();
                    foreach($categories as $individual_category)
                        $category_ids[] = $individual_category->term_id;
                    $args = array( 'category__in' => $category_ids,
                        'post__not_in' => array($post->ID),
                        'posts_per_page' => $mts_options['mts_related_postsnum'],
                        'ignore_sticky_posts' => 1,
                        'orderby' => 'rand'
                    );
                }
            }
            if (!$empty_taxonomy) {
            $my_query = new WP_Query( $args ); if( $my_query->have_posts() ) {
                echo '<div class="related-posts"><div class="container">';
                echo '<h4>'.__('You May Also Like', MTS_THEME_TEXTDOMAIN ).'</h4>';
                echo '<div class="clear">';
                $posts_per_row = 4;
                $j = 0;
                while( $my_query->have_posts() ) { $my_query->the_post();
                    $format = get_post_format();
                    $format_class = ( false === $format ) ? ' format-standard' : ' format-'.$format;
                    ?>
                    <article class="latestPost excerpt<?php echo $format_class;?> <?php echo (++$j % $posts_per_row == 0) ? 'last' : ''; ?>">
                        <a href="<?php echo esc_url( get_the_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>">
                            <div class="featured-thumbnail">
                                <?php the_post_thumbnail('related',array('title' => '')); ?>
                                <span class="icon"><i class="fa format-icon"></i></span>
                                <?php if (function_exists('wp_review_show_total')) wp_review_show_total(true, 'latestPost-review-wrapper'); ?>
                            </div>
                        </a>
                        <header>
                            <h2 class="title front-view-title"><a href="<?php echo esc_url( get_the_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>"><?php the_title(); ?></a></h2>
                        </header>
                        <div class="front-view-content">
                            <?php echo mts_excerpt(15); ?>
                        </div>
                        <div class="post-info">
                            <div class="thetime"><span><?php the_time( get_option( 'date_format' ) ); ?></span></div>
                            <?php mts_readmore(); ?>
                        </div>
                    </article><!--.post.excerpt-->
                <?php } echo '</div></div></div>'; }} wp_reset_postdata(); ?>
            <!-- .related-posts -->
        <?php }
    }
}

/*------------[ Post Meta Info ]-------------*/
if ( ! function_exists('mts_the_postinfo' ) ) {
    function mts_the_postinfo( $section = 'home' ) {
        $mts_options = get_option( MTS_THEME_NAME );
        $opt_key = 'mts_'.$section.'_headline_meta_info';

        if ( isset( $mts_options[ $opt_key ] ) && is_array( $mts_options[ $opt_key ] ) && array_key_exists( 'enabled', $mts_options[ $opt_key ] ) ) {
            $headline_meta_info = $mts_options[ $opt_key ]['enabled'];
        } else {
            $headline_meta_info = array();
        }
        if ( ! empty( $headline_meta_info ) ) { ?>
            <div class="post-info">
                <?php foreach( $headline_meta_info as $key => $meta ) { mts_the_postinfo_item( $key ); } ?>
            </div>
        <?php }
    }
}
if ( ! function_exists('mts_the_postinfo_item' ) ) {
    function mts_the_postinfo_item( $item ) {
        switch ( $item ) {
            case 'author':
            ?>
                <span class="theauthor"><?php _e('By', MTS_THEME_TEXTDOMAIN ); ?> <span><?php the_author_posts_link(); ?></span></span>
            <?php
            break;
            case 'date':
            ?>
                <span class="thetime date updated"><span><?php the_time( get_option( 'date_format' ) ); ?></span></span>
            <?php
            break;
            case 'category':
            ?>
                <span class="thecategory"><?php mts_the_category(', ') ?></span>
            <?php
            break;
            case 'comment':
            ?>
                <span class="thecomment"><a href="<?php echo esc_url( get_comments_link() ); ?>" itemprop="interactionCount"><?php comments_number();?></a></span>
            <?php
            break;
        }
    }
}

/*------------[ Social Icons ]-------------*/
if (!function_exists('mts_social_buttons')) {
    function mts_social_buttons() {
        $mts_options = get_option( MTS_THEME_NAME );

        if ( isset( $mts_options['mts_social_buttons'] ) && is_array( $mts_options['mts_social_buttons'] ) && array_key_exists( 'enabled', $mts_options['mts_social_buttons'] ) ) {
            $buttons = $mts_options['mts_social_buttons']['enabled'];
        } else {
            $buttons = array();
        }

        if ( ! empty( $buttons ) ) {
        ?>
            <!-- Start Share Buttons -->
            <div class="shareit <?php echo $mts_options['mts_social_button_position']; ?>">
                <?php foreach( $buttons as $key => $button ) { mts_social_button( $key ); } ?>
            </div>
            <!-- end Share Buttons -->
        <?php
        }
    }
}

if ( ! function_exists('mts_social_button' ) ) {
    function mts_social_button( $button ) {
        $mts_options = get_option( MTS_THEME_NAME );
        switch ( $button ) {
            case 'facebookshare':
            ?>
                <!-- Facebook Share-->
                <span class="share-item facebooksharebtn">
                    <div class="fb-share-button" data-layout="button_count"></div>
                </span>
            <?php
            break;
            case 'twitter':
            ?>
                <!-- Twitter -->
                <span class="share-item twitterbtn">
                    <a href="https://twitter.com/share" class="twitter-share-button" data-via="<?php echo esc_attr( $mts_options['mts_twitter_username'] ); ?>">Tweet</a>
                </span>
            <?php
            break;
            case 'facebook':
            ?>
                <!-- Facebook -->
                <span class="share-item facebookbtn">
                    <div id="fb-root"></div>
                    <div class="fb-like" data-send="false" data-layout="button_count" data-width="150" data-show-faces="false"></div>
                </span>
            <?php
            break;
            case 'pinterest':
                global $post;
            ?>
                <!-- Pinterest -->
                <span class="share-item pinbtn">
                    <a href="http://pinterest.com/pin/create/button/?url=<?php the_permalink(); ?>&media=<?php $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'large' ); echo $thumb['0']; ?>&description=<?php the_title(); ?>" class="pin-it-button" count-layout="horizontal">Pin It</a>
                </span>
            <?php
            break;
            case 'linkedin':
            ?>
                <!--Linkedin -->
                <span class="share-item linkedinbtn">
                    <script type="IN/Share" data-url="<?php echo esc_url( get_the_permalink() ); ?>"></script>
                </span>
            <?php
            break;
            case 'stumble':
            ?>
            <span class="share-item stumblebtn">
              <a target="_blank" href="https://mix.com/add?url=<?php echo urlencode( get_permalink() ); ?>"><span class="icon"><svg height="16px" style="enable-background:new 0 0 512 512;" version="1.1" viewBox="0 0 512 512" width="20px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g id="comp_x5F_217-mix"><g><path d="M26.001,54.871v358.246c0,57.705,90.357,59.656,90.357,0V168.124c8.11-54.316,90.357-51.749,90.357,6.675v179.994    c0,59.453,98.57,59.556,98.57,0V235.584c5.44-56.166,90.357-53.906,90.357,4.415v24.44c0,61.503,90.355,58.114,90.355,0V54.871    H26.001z"/></g></g><g id="Layer_1"/></svg></span><span class="mix-text"><?php echo __('Mix', 'mythemeshop' ); ?></span></a>
            </span>
            <?php
            break;
        }
    }
}

/*------------[ Class attribute for <article> element ]-------------*/
if ( ! function_exists( 'mts_article_class' ) ) {
    function mts_article_class() {
        $mts_options = get_option( MTS_THEME_NAME );
        $class = '';

        // sidebar or full width
        if ( mts_custom_sidebar() == 'mts_nosidebar' ) {
            $class = 'ss-full-width';
        } else {
            $class = 'article';
        }

        echo $class;
    }
}

/*------------[ Class attribute for #page element ]-------------*/
if ( ! function_exists( 'mts_single_page_class' ) ) {
    function mts_single_page_class() {
        $class = '';

        if ( is_single() || is_page() ) {

            $class = 'single';

            $header_animation = mts_get_post_header_effect();
            if ( !empty( $header_animation )) $class .= ' '.$header_animation;
        }

        echo $class;
    }
}

/*------------[ Archive Posts ]-------------*/
if ( ! function_exists( 'mts_archive_post' ) ) {
    function mts_archive_post( $layout = '' ) {

        $mts_options = get_option(MTS_THEME_NAME);

        $format = get_post_format();
        $format_class = ( false === $format ) ? ' format-standard' : ' format-'.$format;
        ?>
        <article class="latestPost excerpt<?php echo $format_class; ?>">
            <a href="<?php echo esc_url( get_the_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>" class="post-image post-image-left">
                <div class="featured-thumbnail">
                    <?php the_post_thumbnail('featured',array('title' => '')); ?>
                    <span class="icon"><i class="fa format-icon"></i></span>
                    <?php if (function_exists('wp_review_show_total')) wp_review_show_total(true, 'latestPost-review-wrapper'); ?>
                </div>
            </a>
            <div class="latestPost-inner">
                <header>
                    <h2 class="title front-view-title"><a href="<?php echo esc_url( get_the_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>"><?php the_title(); ?></a></h2>
                </header>
                <?php if (empty($mts_options['mts_full_posts'])) : ?>
                    <div class="front-view-content">
                        <?php echo mts_excerpt(55); ?>
                    </div>
                    <?php mts_the_postinfo(); ?>
                    <?php mts_readmore(); ?>
                <?php else : ?>
                    <div class="front-view-content full-post">
                        <?php the_content(); ?>
                    </div>
                    <?php if (mts_post_has_moretag()) : ?>
                        <?php mts_readmore(); ?>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </article><!--.post excerpt-->
    <?php
    }
}

/*------------[ Credit Cards ]-------------*/
if ( ! function_exists( 'mts_credit_cards' ) ) {
    function mts_credit_cards() {
        global $mts_options;

        if ( !empty( $mts_options['mts_accepted_payment_method_images'] ) ) {
        ?>
        <ul class="card-list">
        <?php foreach( $mts_options['mts_accepted_payment_method_images'] as $image ) {
            $image_title      = $image['mts_payment_method_title'];
            $card             = $image['mts_payment_method_image'];

            $custom_image_src = wp_get_attachment_image_src( $image['mts_payment_method_custom_image'], 'full' );
            $custom_image_src = $custom_image_src[0];

            $src = ( empty( $custom_image_src ) ) ? get_template_directory_uri().'/options/img/credit-cards/'.$card.'.png' : $custom_image_src; ?>
            <li><img src="<?php echo esc_attr( $src ); ?>" title="<?php echo esc_attr( $image_title ); ?>" alt="<?php echo esc_attr( $image_title ); ?>"/></li>
        <?php } ?>
        </ul><!-- .card-list -->
        <?php
        }
    }
}

/*------------[ Footer Social Icons ]-------------*/
if ( ! function_exists( 'mts_footer_icons' ) ) {
    function mts_footer_icons() {
        global $mts_options;

        if ( !empty( $mts_options['mts_footer_social_icons'] ) ) {
        ?>
        <div class="social-icons">
           <div class="social-icons-inner">
            <?php
            foreach( $mts_options['mts_footer_social_icons'] as $social_icon ) {
                $title      = $social_icon['mts_footer_social_icon_title'];
                $href       = $social_icon['mts_footer_social_icon_url'];
                $icon       = $social_icon['mts_footer_social_icon'];
                $color      = $social_icon['mts_footer_social_icon_color'];
                $bg_color   = $social_icon['mts_footer_social_icon_bg_color'];

                $style = '';
                if ( !empty( $color ) || !empty( $bg_color ) ) {
                    $style .= 'style="';
                    $style .= !empty( $color ) ? 'color:'.$color.';' : '';
                    $style .= !empty( $bg_color ) ? 'background-color:'.$bg_color.';' : '';
                    $style .= '"';
                }
            ?>
                <a href="<?php echo esc_attr( $href ); ?>" title="<?php echo esc_attr( $title ); ?>" <?php echo $style; ?>><i class="fa fa-<?php echo $icon; ?>"></i></a>
            <?php
            }
            ?>
            </div>
        </div>
        <?php
        }
    }
}

/*------------[ Footer Social Icons ]-------------*/
if ( ! function_exists( 'mts_payment_guarantee' ) ) {
    function mts_payment_guarantee() {
        global $mts_options;

        if ( !empty( $mts_options['mts_payment_section'] ) && !empty( $mts_options['mts_payment'] ) ) {
        ?>
        <div class="payment-guarantee clearfix">
            <div class="container payment-content-container">
            <?php
            foreach( $mts_options['mts_payment'] as $payment ) {
                $title       = $payment['title'];
                $href        = $payment['url'];
                $icon        = $payment['icon'];
                $description = $payment['description'];
            ?>
            <div class="payment-content">
                <?php if ( !empty( $icon ) ) { ?><div class="icon"><i class="fa fa-<?php echo $icon; ?>"></i></div><?php } ?>
                <div class="payment-header">
                    <h2 class="title front-view-title"><?php if(!empty($href)) { ?><a href="<?php echo esc_attr( $href ); ?>"><?php } echo $title; if(!empty($href)) { ?></a><?php } ?></h2>
                    <div class="front-view-content"><?php echo $description; ?></div>
                </div>
            </div>
            <?php
            }
            ?>
            </div>
        </div>
        <?php
        }
    }
}

/*------------[   ]-------------*/
if ( ! function_exists( 'mts_show_product_loop_offer_sale_flash' ) ) {

    function mts_show_product_loop_offer_sale_flash() {
        wc_get_template( 'loop/offers-sale-flash.php' );
    }
}

function mts_theme_action( $action = null ) {
    update_option( 'mts__thl', '1' );
    update_option( 'mts__pl', '1' );
}

function mts_theme_activation( $oldtheme_name = null, $oldtheme = null ) {
    // Check for Connect plugin version > 1.4
    if ( class_exists('mts_connection') && defined('MTS_CONNECT_ACTIVE') && MTS_CONNECT_ACTIVE ) {
        return;
    }
     $plugin_path = 'mythemeshop-connect/mythemeshop-connect.php';

    // Check if plugin exists
    if ( ! function_exists( 'get_plugins' ) ) {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }
    $plugins = get_plugins();
    if ( ! array_key_exists( $plugin_path, $plugins ) ) {
        // auto-install it
        include_once( ABSPATH . 'wp-admin/includes/misc.php' );
        include_once( ABSPATH . 'wp-admin/includes/file.php' );
        include_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );
        include_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );
        $skin     = new Automatic_Upgrader_Skin();
        $upgrader = new Plugin_Upgrader( $skin );
        $plugin_file = 'https://www.mythemeshop.com/mythemeshop-connect.zip';
        $result = $upgrader->install( $plugin_file );
        // If install fails then revert to previous theme
        if ( is_null( $result ) || is_wp_error( $result ) || is_wp_error( $skin->result ) ) {
            switch_theme( $oldtheme->stylesheet );
            return false;
        }
    } else {
        // Plugin is already installed, check version
        $ver = isset( $plugins[$plugin_path]['Version'] ) ? $plugins[$plugin_path]['Version'] : '1.0';
         if ( version_compare( $ver, '2.0.5' ) === -1 ) {
            include_once( ABSPATH . 'wp-admin/includes/misc.php' );
            include_once( ABSPATH . 'wp-admin/includes/file.php' );
            include_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );
            include_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );
            $skin     = new Automatic_Upgrader_Skin();
            $upgrader = new Plugin_Upgrader( $skin );

            add_filter( 'pre_site_transient_update_plugins',  'mts_inject_connect_repo', 10, 2 );
            $result = $upgrader->upgrade( $plugin_path );
            remove_filter( 'pre_site_transient_update_plugins', 'mts_inject_connect_repo' );

            // If update fails then revert to previous theme
            if ( is_null( $result ) || is_wp_error( $result ) || is_wp_error( $skin->result ) ) {
                switch_theme( $oldtheme->stylesheet );
                return false;
            }
        }
    }
    $activate = activate_plugin( $plugin_path );
}

function mts_inject_connect_repo( $pre, $transient ) {
    $plugin_file = 'https://www.mythemeshop.com/mythemeshop-connect.zip';

    $return = new stdClass();
    $return->response = array();
    $return->response['mythemeshop-connect/mythemeshop-connect.php'] = new stdClass();
    $return->response['mythemeshop-connect/mythemeshop-connect.php']->package = $plugin_file;

    return $return;
}

add_action( 'wp_loaded', 'mts_maybe_set_constants' );
function mts_maybe_set_constants() {
    if ( ! defined( 'MTS_THEME_S' ) ) {
        mts_set_theme_constants();
    }
}

add_action( 'init', 'mts_nhp_sections_override', -11 );
function mts_nhp_sections_override() {
    define( 'MTS_THEME_INIT', 1 );
    if ( class_exists('mts_connection') && defined('MTS_CONNECT_ACTIVE') && MTS_CONNECT_ACTIVE ) {
        return;
    }
    if ( ! get_option( MTS_THEME_NAME, false ) ) {
        return;
    }
    add_filter( 'nhp-opts-sections', '__return_empty_array' );
    add_filter( 'nhp-opts-sections', 'mts_nhp_section_placeholder' );
    add_filter( 'nhp-opts-args', 'mts_nhp_opts_override' );
    add_filter( 'nhp-opts-extra-tabs', '__return_empty_array', 11, 1 );
}

function mts_nhp_section_placeholder( $sections ) {
    $sections[] = array(
        'icon' => 'fa fa-cogs',
        'title' => __('Not Connected', MTS_THEME_TEXTDOMAIN ),
        'desc' => '<p class="description">' . __('You will find all the theme options here after connecting with your MyThemeShop account.', MTS_THEME_TEXTDOMAIN ) . '</p>',
        'fields' => array()
    );
    return $sections;
}

function mts_nhp_opts_override( $opts ) {
    $opts['show_import_export'] = false;
    $opts['show_typography'] = false;
    $opts['show_translate'] = false;
    $opts['show_child_theme_opts'] = false;
    $opts['last_tab'] = 0;

    return $opts;
}
