<?php $mts_options = get_option(MTS_THEME_NAME); ?>

<header id="site-header" role="banner" itemscope itemtype="http://schema.org/WPHeader" class="header-6">

	<div id="header">

		<div class="container">

			<div class="logo-wrap">

				<?php if ($mts_options['mts_logo'] != '') { ?>

					<?php if( is_front_page() || is_home() || is_404() ) { ?>

							<h1 id="logo" class="image-logo" itemprop="headline">

								<a href="<?php echo esc_url( home_url() ); ?>"><img src="<?php echo esc_attr( $mts_options['mts_logo'] ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"<?php if (!empty($mts_options['mts_logo2x'])) { echo ' data-at2x="'.esc_attr( $mts_options['mts_logo2x'] ).'"'; } ?>></a>

							</h1><!-- END #logo -->

					<?php } else { ?>

						  <h2 id="logo" class="image-logo" itemprop="headline">

								<a href="<?php echo esc_url( home_url() ); ?>"><img src="<?php echo esc_attr( $mts_options['mts_logo'] ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"<?php if (!empty($mts_options['mts_logo2x'])) { echo ' data-at2x="'.esc_attr( $mts_options['mts_logo2x'] ).'"'; } ?>></a>

							</h2><!-- END #logo -->

					<?php } ?>

				<?php } else { ?>

					<?php if( is_front_page() || is_home() || is_404() ) { ?>

							<h1 id="logo" class="text-logo" itemprop="headline">

								<a href="<?php echo esc_url( home_url() ); ?>"><?php bloginfo( 'name' ); ?></a>

							</h1><!-- END #logo -->

					<?php } else { ?>

						  <h2 id="logo" class="text-logo" itemprop="headline">

								<a href="<?php echo esc_url( home_url() ); ?>"><?php bloginfo( 'name' ); ?></a>

							</h2><!-- END #logo -->

					<?php } ?>

					<div class="site-description" itemprop="description">

						<?php bloginfo( 'description' ); ?>

					</div>

				<?php } ?>

			</div>



			<div class="widget widget_search">

				<form method="get" id="searchform" class="search-form" action="<?php echo esc_attr( home_url() ); ?>" _lpchecked="1">
					<fieldset>
						<input type="text" name="s" id="s" value="" placeholder="<?php if ( mts_isWooCommerce() ) { _e( 'Search Products...', MTS_THEME_TEXTDOMAIN ); } else { _e( 'Search Blog Posts...', MTS_THEME_TEXTDOMAIN ); } ?>"  autocomplete="off" />
						<input type="hidden" name="post_type" value="<?php if ( mts_isWooCommerce() ) { echo 'product'; } else { echo 'post'; } ?>" class="post-type-input"/>
						<button id="search-image" class="sbutton" type="submit" value=""><i class="fa fa-search"></i></button>
					</fieldset>
				</form>

			</div>



			<div class="header-right">



				<?php if ( $mts_options['mts_show_secondary_nav'] == '1' ) { ?>

				<div id="secondary-navigation" role="navigation" itemscope itemtype="http://schema.org/SiteNavigationElement">

					<?php if ( $mts_options['mts_show_primary_nav'] !== '1' ) {?><a href="#" id="pull" class="toggle-mobile-menu"><?php _e('Menu', MTS_THEME_TEXTDOMAIN ); ?></a><?php } ?>

					<nav class="navigation clearfix<?php if ( $mts_options['mts_show_primary_nav'] !== '1' ) echo ' mobile-menu-wrapper'; ?>">

						<?php if ( has_nav_menu( 'secondary-menu' ) ) { ?>

							<?php wp_nav_menu( array( 'theme_location' => 'secondary-menu', 'menu_class' => 'menu clearfix', 'container' => '', 'walker' => new mts_menu_walker ) ); ?>

						<?php } else { ?>

							<ul class="menu clearfix">

								<?php wp_list_categories('title_li='); ?>

							</ul>

						<?php } ?>

					</nav>

				</div>

				<?php } ?>



				<div class="header-right-below">



					<?php mts_wishlist_link(); ?>



					<?php if ( mts_isWooCommerce() ) mts_cart(); ?>



			    </div><!--.header-right-below-->

			</div><!--.header-right-->

		</div><!--.container-->

	</div><!-- #header-->



	<?php if ( $mts_options['mts_show_primary_nav'] == '1' ) { ?>

		<?php if( $mts_options['mts_sticky_nav'] == '1' ) { ?>

			<div id="catcher" class="clear" ></div>

			<div id="primary-navigation" class="sticky-navigation" role="navigation" itemscope itemtype="http://schema.org/SiteNavigationElement">

		<?php } else { ?>

		<div id="primary-navigation" role="navigation" itemscope itemtype="http://schema.org/SiteNavigationElement">

		<?php } ?>

		    <div class="container clearfix">

		    	<a href="#" id="pull" class="toggle-mobile-menu"><?php _e('Menu', MTS_THEME_TEXTDOMAIN ); ?></a>

				<nav class="navigation clearfix mobile-menu-wrapper">

					<?php if ( has_nav_menu( 'primary-menu' ) ) { ?>

						<?php wp_nav_menu( array( 'theme_location' => 'primary-menu', 'menu_class' => 'menu clearfix', 'container' => '', 'walker' => new mts_menu_walker ) ); ?>

					<?php } else { ?>

						<ul class="menu clearfix">

							<?php wp_list_pages('title_li='); ?>

						</ul>

					<?php } ?>

				</nav>

			</div>

		</div>

	<?php } ?>



	<div class="ad-navigation clearfix">

		<div class="container">

			<ul class="ad-options">

				<li>

					<div class="title"><?php echo $mts_options['mts_header_bottom_ad_nav_1_title']; ?></div>

					<div class="text"><?php echo $mts_options['mts_header_bottom_ad_nav_1_desc']; ?></div>

				</li>

				<li>

					<div class="title"><?php echo $mts_options['mts_header_bottom_ad_nav_2_title']; ?></div>

					<div class="text"><?php echo $mts_options['mts_header_bottom_ad_nav_2_desc']; ?></div>

				</li>

				<li>

					<div class="title"><?php echo $mts_options['mts_header_bottom_ad_nav_3_title']; ?></div>

					<div class="text"><?php echo $mts_options['mts_header_bottom_ad_nav_3_desc']; ?></div>

				</li>

			</ul>

		</div>

	</div>

</header>
