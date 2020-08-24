<?php
class mts_wc_ajax_filter_widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'mts_wc_ajax_filter_widget',
            __('MyThemeShop: AJAX attribute filter', MTS_THEME_TEXTDOMAIN ),
            array( 'classname' => 'mts-ajax-filter-widget woocommerce widget_layered_nav', 'description' => __( 'Filter products by attributes. The widget will only show up on product archive pages corresponding to the selected attribute.', MTS_THEME_TEXTDOMAIN ) )
        );
    }

    public function form( $instance ) {

        $defaults = array(
            'title'      => '',
            'attribute'  => '',
            'query_type' => 'and',
            'type'       => 'list',
            'use_colors' => 1,
        );

        $instance = wp_parse_args( (array)$instance, $defaults );

        ?>

        <p>
            <label>
                <strong><?php _e( 'Title', MTS_THEME_TEXTDOMAIN ) ?>:</strong><br />
                <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
            </label>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('attribute'); ?>"><strong><?php _e('Attribute:', MTS_THEME_TEXTDOMAIN ) ?></strong></label>
            <select class="widefat" id="<?php echo esc_attr( $this->get_field_id('attribute') ); ?>" name="<?php echo esc_attr( $this->get_field_name('attribute') ); ?>">
                <?php $this->mts_wcan_dropdown_attributes( $instance['attribute'] ); ?>
            </select>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'query_type' ); ?>"><?php _e( 'Query Type:', MTS_THEME_TEXTDOMAIN ) ?></label>
            <select id="<?php echo esc_attr( $this->get_field_id( 'query_type' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'query_type' ) ); ?>">
                <option value="and" <?php selected( $instance['query_type'], 'and' ); ?>>AND</option>
                <option value="or" <?php selected( $instance['query_type'], 'or' ); ?>>OR</option>
            </select>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('type'); ?>"><strong><?php _e('Type:', MTS_THEME_TEXTDOMAIN) ?></strong></label>
            <select class="widefat" id="<?php echo esc_attr( $this->get_field_id('type') ); ?>" name="<?php echo esc_attr( $this->get_field_name('type') ); ?>">
                <option value="list" <?php selected( 'list', $instance['type'] ) ?>><?php _e( 'List', MTS_THEME_TEXTDOMAIN ) ?></option>
                <option value="label" <?php selected( 'label', $instance['type'] ) ?>><?php _e( 'Label', MTS_THEME_TEXTDOMAIN ) ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id("use_colors"); ?>">
                <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("use_colors"); ?>" name="<?php echo $this->get_field_name("use_colors"); ?>" value="1" <?php if (isset($instance['use_colors'])) { checked( 1, $instance['use_colors'], true ); } ?> />
                <?php _e( 'Use attribute colors if available', MTS_THEME_TEXTDOMAIN); ?>
            </label>
        </p>
    <?php
    }

    public function update( $new_instance, $old_instance ) {

        $instance = $old_instance;

        if ( empty( $new_instance['title'] ) ) {
            $new_instance['title'] = wc_attribute_label( $new_instance['title'] );
        }

        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['attribute'] = stripslashes( $new_instance['attribute'] );
        $instance['query_type'] = stripslashes( $new_instance['query_type'] );
        $instance['type'] = stripslashes( $new_instance['type'] );
        $instance['use_colors'] = intval( $new_instance['use_colors'] );

        return $instance;
    }


    public function widget( $args, $instance ) {

        wp_enqueue_script( 'wc-ajax-attr-filters' );

        global $_mts_filtered_product_ids, $_mts_unfiltered_product_ids;

        extract( $args );

        $_mts_attributes_array = mts_wcan_get_product_taxonomy();

        $product_taxonomies = ! empty( $_mts_attributes_array ) ? $_mts_attributes_array : get_object_taxonomies( 'product' );
        $_mts_attributes_array = array_merge( $product_taxonomies, apply_filters( 'yith_wcan_product_taxonomy_type', array() ) );

        if ( ! is_post_type_archive( 'product' ) && ! is_tax( array_merge( $_mts_attributes_array, array( 'product_cat', 'product_tag' ) ) ) || is_shop() )
            return;

        $filter_term_field  = version_compare( WC()->version, '2.6', '<' ) ? 'term_id' : 'slug';
        $current_term 	 = $_mts_attributes_array && is_tax( $_mts_attributes_array ) ? get_queried_object()->$filter_term_field : '';
        $title 			 = apply_filters('widget_title', $instance['title'], $instance, $this->id_base);
        $taxonomy        = isset( $instance['attribute'] ) ? wc_attribute_taxonomy_name( $instance['attribute'] ) : '';
        $query_type 	 = isset( $instance['query_type'] ) ? $instance['query_type'] : 'and';
        $display_type 	 = isset( $instance['type'] ) ? $instance['type'] : 'list';
        $use_colors      = (int) $instance['use_colors'];

        if ( ! taxonomy_exists( $taxonomy ) )
            return;

        $get_terms_args = array( 'hide_empty' => false );

        $orderby = wc_attribute_orderby( $taxonomy );

        switch ( $orderby ) {
            case 'name' :
                $get_terms_args['orderby']    = 'name';
                $get_terms_args['menu_order'] = false;
            break;
            case 'id' :
                $get_terms_args['orderby']    = 'id';
                $get_terms_args['order']      = 'ASC';
                $get_terms_args['menu_order'] = false;
            break;
            case 'menu_order' :
                $get_terms_args['menu_order'] = 'ASC';
            break;
        }

        $terms = get_terms( $taxonomy, $get_terms_args );

        if ( count( $terms ) > 0 ) { 

            ob_start();

            $found = false;

            echo $before_widget;

            if ( !empty( $title ) ) {
                echo $before_title . $title . $after_title;
            }

            $_chosen_attributes = mts_get_layered_nav_chosen_attributes();
            // Force found when option is selected - do not force found on taxonomy attributes
            if ( ! $_mts_attributes_array || ! is_tax( $_mts_attributes_array ) ) {
                if ( is_array( $_chosen_attributes ) && array_key_exists( $taxonomy, $_chosen_attributes ) ) {
                    $found = true;
                }
            }

            
            // List display
            echo "<ul class='mts-ajax-filter-links mts-ajax-filter-type-".$display_type."'>";

            foreach ( $terms as $term ) {

                // Get count based on current view - uses transients
                $transient_name = 'wc_ln_count_' . md5( sanitize_key( $taxonomy ) . sanitize_key( $term->$filter_term_field ) );

                //if ( false === ( $_products_in_term = get_transient( $transient_name ) ) ) {

                    $_products_in_term = get_objects_in_term( $term->term_id, $taxonomy );

                    set_transient( $transient_name, $_products_in_term );
                //}

                $option_is_set = ( isset( $_chosen_attributes[ $taxonomy ] ) && in_array( $term->$filter_term_field, $_chosen_attributes[ $taxonomy ]['terms'] ) );

                // If this is an AND query, only show options with count > 0
                if ( $query_type == 'and' ) {

                    $count = sizeof( array_intersect( $_products_in_term, $_mts_filtered_product_ids ) );

                    // skip the term for the current archive
                    if ( $current_term == $term->$filter_term_field )
                        continue;

                    if ( $count > 0 && $current_term !== $term->$filter_term_field )
                        $found = true;


                // If this is an OR query, show all options so search can be expanded
                } else {

                    // skip the term for the current archive
                    if ( $current_term == $term->$filter_term_field )
                        continue;

                    $count = sizeof( array_intersect( $_products_in_term, $_mts_unfiltered_product_ids ) );

                    if ( $count > 0 )
                        $found = true;

                }

                $arg = 'filter_' . sanitize_title( $instance['attribute'] );

                $current_filter = ( isset( $_GET[ $arg ] ) ) ? explode( ',', $_GET[ $arg ] ) : array();

                if ( ! is_array( $current_filter ) )
                    $current_filter = array();

                $current_filter = array_map( 'esc_attr', $current_filter );

                if ( ! in_array( $term->$filter_term_field, $current_filter ) )
                    $current_filter[] = $term->$filter_term_field;

                $link = mts_get_woocommerce_layered_nav_link();

                // All current filters
                if ( $_chosen_attributes ) {
                    foreach ( $_chosen_attributes as $name => $data ) {
                        if ( $name !== $taxonomy ) {

                            // Exclude query arg for current term archive term
                            while ( in_array( $current_term, $data['terms'] ) ) {
                                $key = array_search( $current_term, $data );
                                unset( $data['terms'][$key] );
                            }

                            // Remove pa_ and sanitize
                            $filter_name = sanitize_title( str_replace( 'pa_', '', $name ) );

                            if ( ! empty( $data['terms'] ) ){
                                $link = add_query_arg( 'filter_' . $filter_name, implode( ',', $data['terms'] ), $link );
                            }

                            if ( $data['query_type'] == 'or' ){
                                $link = add_query_arg( 'query_type_' . $filter_name, 'or', $link );
                            }
                        }
                    }
                }

                // Min/Max
                if ( isset( $_GET['min_price'] ) ){
                    $link = add_query_arg( 'min_price', $_GET['min_price'], $link );
                }

                if ( isset( $_GET['max_price'] ) ){
                    $link = add_query_arg( 'max_price', $_GET['max_price'], $link );
                }

                if( isset( $_GET['product_cat'] ) ){
                    $categories_filter_operator = 'and' == $query_type ? '+' : ',';
                    $_chosen_categories = explode( $categories_filter_operator, urlencode( $_GET['product_cat'] ) );
                    $link  = add_query_arg(
                        'product_cat',
                        implode( $categories_filter_operator, $_chosen_categories ),
                        $link
                    );
                }

                // Current Filter = this widget
                if ( isset( $_chosen_attributes[ $taxonomy ] ) && is_array( $_chosen_attributes[ $taxonomy ]['terms'] ) && in_array( $term->$filter_term_field, $_chosen_attributes[ $taxonomy ]['terms'] ) ) {
                    $class = 'class="chosen"';

                    // Remove this term is $current_filter has more than 1 term filtered
                    if ( sizeof( $current_filter ) > 1 ) {
                        $current_filter_without_this = array_diff( $current_filter, array( $term->$filter_term_field ) );
                        $link = add_query_arg( $arg, implode( ',', $current_filter_without_this ), $link );
                    }

                } else {
                    $class = '';
                    $link = add_query_arg( $arg, implode( ',', $current_filter ), $link );

                }

                // Search Arg
                if ( get_search_query() )
                    $link = add_query_arg( 's', get_search_query(), $link );

                // Post Type Arg
                if ( isset( $_GET['post_type'] ) )
                    $link = add_query_arg( 'post_type', $_GET['post_type'], $link );

                // Query type Arg
                if ( $query_type == 'or' && ! ( sizeof( $current_filter ) == 1 && isset( $_chosen_attributes[ $taxonomy ]['terms'] ) && is_array( $_chosen_attributes[ $taxonomy ]['terms'] ) && in_array( $term->$filter_term_field, $_chosen_attributes[ $taxonomy ]['terms'] ) ) )
                    $link = add_query_arg( 'query_type_' . sanitize_title( $instance['attribute'] ), 'or', $link );

                $term_taxonomy = $term->taxonomy;
                $term_id = $term->term_id;
                $tax_color_codes = get_option('mts_tax_color_codes');

                $label_color = '';
                if ( 'label' == $display_type && 1 == $use_colors ) {
                    if ( isset( $tax_color_codes[ $term_taxonomy ][ $term_id ] ) ) {
                        $label_color = ' style="background:'.$tax_color_codes[ $term_taxonomy ][ $term_id ].'"';
                    }
                }

                echo '<li ' . $class . '>';

                echo ( $count > 0 || $option_is_set ) ? '<a href="' . esc_url( apply_filters( 'woocommerce_layered_nav_link', $link ) ) . '"'.$label_color.'>' : '<span>';

                if ( 'list' == $display_type && 1 == $use_colors ) {
                    if ( isset( $tax_color_codes[ $term_taxonomy ][ $term_id ] ) ) {
                        echo '<span class="color-swatch" style="background:'.$tax_color_codes[ $term_taxonomy ][ $term_id ].'"></span>';
                    }
                }

                echo $term->name;

                echo ( $count > 0 || $option_is_set ) ? '</a>' : '</span>';

                if( $count != 0 && 'label' !== $display_type ) {
                    echo ' <small class="count">' . $count . '</small>';
                }

                echo '</li>';
            }

            echo "</ul>";

            

            echo $after_widget;

            if ( ! $found )  {
                ob_end_clean();
            } else {
                echo ob_get_clean();
            }
        }
    }

    public function mts_wcan_dropdown_attributes( $selected ) {
        global $woocommerce;

        if ( ! isset( $woocommerce ) ) return array();

        $attributes = array();

        $attribute_taxonomies = wc_get_attribute_taxonomies();

        if( empty( $attribute_taxonomies ) ) return array();
        foreach( $attribute_taxonomies as $attribute ) {

            $taxonomy = wc_attribute_taxonomy_name($attribute->attribute_name);

            if ( taxonomy_exists( $taxonomy ) ) {
                $attributes[] = $attribute->attribute_name;
            }
        }

        $options = "";

        foreach( $attributes as $attribute ) {
            echo "<option name='{$attribute}'". selected( $attribute, $selected, false ) .">{$attribute}</option>";
        }
    }
}
// Register Widget
add_action( 'widgets_init', 'mts_register_ajax_nav_widget' );
function mts_register_ajax_nav_widget() {
    register_widget( "mts_wc_ajax_filter_widget" );
}

global $_mts_unfiltered_product_ids, $_mts_filtered_product_ids;
$_mts_unfiltered_product_ids = array();
$_mts_filtered_product_ids   = array();

// Create an array of product attribute taxonomies for use in ajax nav widget
add_action( 'init', 'mts_wc_ajax_layered_nav_init', 99 );
function mts_wc_ajax_layered_nav_init() {
    if ( is_active_widget( false, false, 'mts_wc_ajax_filter_widget', true ) && ! is_admin() ) {
        //global $_chosen_attributes, $woocommerce, $_mts_attributes_array;
        $_chosen_attributes = mts_get_layered_nav_chosen_attributes();
        //$_chosen_attributes = $_mts_attributes_array = array();
        $attribute_taxonomies = wc_get_attribute_taxonomies();
        if ( $attribute_taxonomies ) {
            foreach ( $attribute_taxonomies as $tax ) {
                $attribute = sanitize_title( $tax->attribute_name );
                $taxonomy = wc_attribute_taxonomy_name($attribute);
                // create an array of product attribute taxonomies
                $_mts_attributes_array[] = $taxonomy;
                $name = 'filter_' . $attribute;
                $query_type_name = 'query_type_' . $attribute;
                if ( ! empty( $_GET[ $name ] ) && taxonomy_exists( $taxonomy ) ) {
                    $_chosen_attributes[ $taxonomy ]['terms'] = explode( ',', $_GET[ $name ] );
                    if ( empty( $_GET[ $query_type_name ] ) || ! in_array( strtolower( $_GET[ $query_type_name ] ), array( 'and', 'or' ) ) )
                        $_chosen_attributes[ $taxonomy ]['query_type'] = apply_filters( 'woocommerce_layered_nav_default_query_type', 'and' );
                    else
                        $_chosen_attributes[ $taxonomy ]['query_type'] = strtolower( $_GET[ $query_type_name ] );
                }
            }
        }
        if ( version_compare( WC()->version, '2.6', '<' ) ) add_filter('loop_shop_post_in', array( WC()->query, 'layered_nav_query' ));
    }
}

function mts_get_layered_nav_chosen_attributes(){
    $chosen_attributes = array();
    if( version_compare( WC()->version, '2.6', '<' ) ) {
        global $_chosen_attributes;
        $chosen_attributes = $_chosen_attributes;
    } else {
        $chosen_attributes = WC_Query::get_layered_nav_chosen_attributes();
    }
    return $chosen_attributes;
}

function mts_layered_navigation_array_for_wc_older_26(){

    if( version_compare( WC()->version, '2.6', '<' ) ){
        global $_mts_unfiltered_product_ids, $_mts_filtered_product_ids;
        $_mts_unfiltered_product_ids = WC()->query->unfiltered_product_ids;
        $_mts_filtered_product_ids   = WC()->query->filtered_product_ids;
    }
}

add_filter( 'the_posts', 'mts_aln_the_posts', 15, 2 );
function mts_aln_the_posts( $posts, $query = false ) {

    if( version_compare( WC()->version, '2.6', '<' ) ){
        add_action( 'wp', 'mts_layered_navigation_array_for_wc_older_26' );
    } else {
        global $_mts_unfiltered_product_ids, $_mts_filtered_product_ids;
        $filtered_posts   = array();
        $queried_post_ids = array();
        $query_filtered_posts = mts_layered_nav_query();

        foreach ( $posts as $post ) {

            if ( in_array( $post->ID, $query_filtered_posts ) ) {
                $filtered_posts[]   = $post;
                $queried_post_ids[] = $post->ID;
            }
        }

        $query->posts       = $filtered_posts;
        $query->post_count  = count( $filtered_posts );

        // Get main query
        $current_wp_query = $query->query;

        // Get WP Query for current page (without 'paged')
        unset( $current_wp_query['paged'] );

        // Ensure filters are set
        $unfiltered_args = array_merge(
            $current_wp_query,
            array(
                'post_type'              => 'product',
                'numberposts'            => -1,
                'post_status'            => 'publish',
                'meta_query'             => $query->meta_query,
                'fields'                 => 'ids',
                'no_found_rows'          => true,
                'update_post_meta_cache' => false,
                'update_post_term_cache' => false,
                'pagename'               => '',
                'wc_query'               => 'get_products_in_view'
            )
        );
        $_mts_unfiltered_product_ids = get_posts( $unfiltered_args );
        $_mts_filtered_product_ids   = $queried_post_ids;

        // Also store filtered posts ids...
        if ( sizeof( $queried_post_ids ) > 0 ) {
            $_mts_filtered_product_ids = array_intersect( $_mts_unfiltered_product_ids, $queried_post_ids );
        } else {
            $_mts_filtered_product_ids = $_mts_unfiltered_product_ids;
        }
    }
    
    return $posts;
}

function mts_layered_nav_query( $filtered_posts  = array()) {
    $_chosen_attributes = mts_get_layered_nav_chosen_attributes();

    if ( sizeof( $_chosen_attributes ) > 0 ) {

        $matched_products   = array(
            'and' => array(),
            'or'  => array()
        );
        $filtered_attribute = array(
            'and' => false,
            'or'  => false
        );

        foreach ( $_chosen_attributes as $attribute => $data ) {
            $matched_products_from_attribute = array();
            $filtered = false;

            if ( sizeof( $data['terms'] ) > 0 ) {
                foreach ( $data['terms'] as $value ) {

                    $args = array(
                        'post_type'     => 'product',
                        'numberposts'   => -1,
                        'post_status'   => 'publish',
                        'fields'        => 'ids',
                        'no_found_rows' => true,
                        'tax_query' => array(
                            array(
                                'taxonomy'  => $attribute,
                                'terms'     => $value,
                                'field'     => ( version_compare( WC()->version, '2.6', '<' ) ? 'term_id' : 'slug' )
                            )
                        )
                    );
                    
                    //TODO: Increase performance for get_posts()
                    $post_ids = apply_filters( 'woocommerce_layered_nav_query_post_ids', get_posts( $args ), $args, $attribute, $value );

                    if ( ! is_wp_error( $post_ids ) ) {

                        if ( sizeof( $matched_products_from_attribute ) > 0 || $filtered ) {
                            $matched_products_from_attribute = $data['query_type'] == 'or' ? array_merge( $post_ids, $matched_products_from_attribute ) : array_intersect( $post_ids, $matched_products_from_attribute );
                        } else {
                            $matched_products_from_attribute = $post_ids;
                        }

                        $filtered = true;
                    }
                }
            }

            if ( sizeof( $matched_products[ $data['query_type'] ] ) > 0 || $filtered_attribute[ $data['query_type'] ] === true ) {
                $matched_products[ $data['query_type'] ] = ( $data['query_type'] == 'or' ) ? array_merge( $matched_products_from_attribute, $matched_products[ $data['query_type'] ] ) : array_intersect( $matched_products_from_attribute, $matched_products[ $data['query_type'] ] );
            } else {
                $matched_products[ $data['query_type'] ] = $matched_products_from_attribute;
            }

            $filtered_attribute[ $data['query_type'] ] = true;
        }

        // Combine our AND and OR result sets
        if ( $filtered_attribute['and'] && $filtered_attribute['or'] )
            $results = array_intersect( $matched_products[ 'and' ], $matched_products[ 'or' ] );
        else
            $results = array_merge( $matched_products[ 'and' ], $matched_products[ 'or' ] );

        if ( $filtered ) {

            if ( sizeof( $filtered_posts ) == 0 ) {
                $filtered_posts   = $results;
                $filtered_posts[] = 0;
            } else {
                $filtered_posts   = array_intersect( $filtered_posts, $results );
                $filtered_posts[] = 0;
            }

        }
    }
    return (array) $filtered_posts;
}

add_filter( 'woocommerce_layered_nav_link', 'mts_plus_character_hack', 99 );
function mts_plus_character_hack($link) {
    return $link = str_replace('+', '%2B', $link);
}

function mts_get_woocommerce_layered_nav_link() {
    $return = false;
    if ( defined( 'SHOP_IS_ON_FRONT' ) || ( is_shop() && ! is_product_category()  ) ) {
        $taxonomy           = get_query_var( 'taxonomy' );
        $return             = get_post_type_archive_link( 'product' );

        return apply_filters( 'yith_wcan_untrailingslashit', true ) ? untrailingslashit( $return ) : $return;
    }

    elseif ( is_product_category() ) {
        $return = get_term_link( get_queried_object()->slug, 'product_cat' );
        return apply_filters( 'yith_wcan_untrailingslashit', true ) ? untrailingslashit( $return ) : $return;
    }

    else {
        $taxonomy           = get_query_var( 'taxonomy' );
        $return = get_term_link( get_query_var( 'term' ), $taxonomy );

        return apply_filters( 'yith_wcan_untrailingslashit', true ) ? untrailingslashit( $return ) : $return;
    }

    return $return;
}

function mts_wcan_get_product_taxonomy() {
    global $_mts_attributes_array;
    $product_taxonomies = ! empty( $_mts_attributes_array ) ? $_mts_attributes_array : get_object_taxonomies( 'product' );
    return array_merge( $product_taxonomies, apply_filters( 'yith_wcan_product_taxonomy_type', array() ) );
}