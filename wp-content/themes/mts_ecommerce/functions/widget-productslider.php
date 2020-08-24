<?php
/*-----------------------------------------------------------------------------------

	Plugin Name: MyThemeShop Product Slider
	Version: 1.0
	
-----------------------------------------------------------------------------------*/


class mts_product_slider_widget extends WP_Widget {

	public function __construct() {
		parent::__construct(
	 		'mts_product_slider_widget',
			__('MyThemeShop: Product Slider',MTS_THEME_TEXTDOMAIN),
			array( 'description' => __( 'Display shop products from multiple categories in an animated slider.',MTS_THEME_TEXTDOMAIN ) )
		);
	}

 	public function form( $instance ) {
		$defaults = array(
			'title' => __( 'Featured Products',MTS_THEME_TEXTDOMAIN ),
			'cat' => array(),
			'slides_num' => 3,
			'show_title' => 0,
            'title_limit' => 40
		);
		$instance = wp_parse_args((array) $instance, $defaults);
        extract($instance);
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:',MTS_THEME_TEXTDOMAIN ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'cat' ); ?>"><?php _e( 'Category:',MTS_THEME_TEXTDOMAIN ); ?></label>
			<select id="<?php echo $this->get_field_id( 'cat' ); ?>" name="<?php echo $this->get_field_name( 'cat' ); ?>[]" class="widefat" multiple="multiple">
            <?php
            	$args = apply_filters( 'mts_product_slide_widgets_args', array(
					'hide_empty'	=> 1,
					'hierarchical'	=> 1,
					'taxonomy'		=> 'product_cat'
				) );
                $product_categories = get_categories( $args );
        		foreach ( $product_categories as $category ) {
        			$selected = (is_array($cat) && in_array($category->term_id, $cat))?' selected="selected"':'';
        			echo '<option value="'.$category->term_id.'"'.$selected.'>'.$category->name.'</option>';
        		}
             ?>
             </select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'slides_num' ); ?>"><?php _e( 'Number of Slides to show ( four products per slide )',MTS_THEME_TEXTDOMAIN ); ?></label> 
			<input id="<?php echo $this->get_field_id( 'slides_num' ); ?>" name="<?php echo $this->get_field_name( 'slides_num' ); ?>" type="number" min="1" step="1" value="<?php echo esc_attr( $slides_num ); ?>" />
		</p>
	   
		<?php 
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['cat'] = $new_instance['cat'];
		$instance['slides_num'] = intval( $new_instance['slides_num'] );

		return $instance;
	}

	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		$cat = $instance['cat'];
		$slides_num = (int) $instance['slides_num'];

		echo $before_widget;
		if ( ! empty( $title ) ) echo $before_title . $title . $after_title;
		echo self::get_cat_products( $cat, $slides_num );
		echo $after_widget;
	}

	public function get_cat_products( $cat, $slides_num ) {
		
        // Enqueue owl carousel needed for
        // the widget's output
        wp_enqueue_script('owl-carousel');
		wp_enqueue_style('owl-carousel');
        
        $cat_slugs = array();
        if ( is_array( $cat ) ) {
            foreach ( $cat as $cat_id ) {
	        	$term = get_term( $cat_id, 'product_cat' );
	        	$cat_slugs[] = $term->slug;
	        }
	    }
        

        $product_cats = implode (", ", $cat_slugs);

        $query_args = array(
            'post_type' => 'product',
            'posts_per_page' => $slides_num*4,
            'product_cat' => $product_cats
        );
        $posts = new WP_Query( $query_args );
        $count = 1;
        ?>
			<div class="product-slider-widget-container">
				<div class="product-slider-container loading">
					<div class="product-widget-slider slides">
						<?php while ( $posts->have_posts()) : $posts->the_post(); ?>
							<?php if ( $count % 4 == 1 ) echo '<div class="slide">'; ?>
							<div class="slide-image">
								<?php the_post_thumbnail('productslider',array('title' => '')); ?>
								<div class="product-hover">
									<div class="details"><a href="<?php echo esc_url( get_the_permalink() ); ?>"><?php _e('Details', MTS_THEME_TEXTDOMAIN ); ?></a></div>
								</div>
							</div>
							<?php if ( $count % 4 == 0 ) echo '</div>'; ?>
						<?php $count++; endwhile; wp_reset_postdata(); ?>
					</div>
				</div>
				<div class="custom-nav">
					<a class="btn widget-slider-prev"><i class="fa fa-angle-left"></i></a>
					<a class="btn widget-slider-next"><i class="fa fa-angle-right"></i></a>
				</div>
			</div><!--.product-slider-widget-container-->
		<?php 
	}

}

// Register widget
add_action( 'widgets_init', 'register_mts_product_slider_widget' );
function register_mts_product_slider_widget() {
	register_widget( 'mts_product_slider_widget' );
}