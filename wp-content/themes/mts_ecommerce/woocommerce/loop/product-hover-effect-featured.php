<?php global $product, $mts_options; ?>



<div class="product-wrap effect-default">

	<div class="product-wrap-inner">
		<a href="<?php echo esc_url( get_the_permalink() ); ?>">
		<?php

			/**

			 * woocommerce_before_shop_loop_item_title hook

			 *

			 * @hooked woocommerce_show_product_loop_sale_flash - 10

			 * @hooked woocommerce_template_loop_product_thumbnail - 10

			 */

			do_action( 'woocommerce_before_shop_loop_item_title' );

		?>
		</a>

		<div class="product-hover">

			<div class="product-caption">

				<div class="icon"><a href="#" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>" class="quick-look-link"><i class="fa fa-search"></i></a></div>

				<div class="text"><a href="<?php echo esc_url( get_the_permalink() ); ?>"><?php _e( 'View Details', MTS_THEME_TEXTDOMAIN );?></a></div>

			</div>

			<div class="product-buttons">

				<?php woocommerce_template_loop_add_to_cart(); ?>

				<?php echo mts_wishlist_button(); ?>

			</div>

		</div>

	</div>

	<div class="product-category">

	<?php

    $terms = wp_get_post_terms( $product->get_id(), 'product_cat' );

    $count = 0;

    foreach( $terms as $category) {

        if ( 0 !== $count ) echo ', ';

        echo '<span>' . $category->name . '</span>';

        $count++;

    }

    ?>

    </div>

	<div class="product-title"><a href="<?php echo esc_url( get_the_permalink() ); ?>"><?php echo esc_html( get_the_title() ); ?></a></div>

	<div class="product-data">

		<?php

			/**

			 * woocommerce_after_shop_loop_item_title hook

			 *

			 * @hooked woocommerce_template_loop_rating - 5

			 * @hooked woocommerce_template_loop_price - 10

			 */

			do_action( 'woocommerce_after_shop_loop_item_title' );

		?>

	</div>

</div>