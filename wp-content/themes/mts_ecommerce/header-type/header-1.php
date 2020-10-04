<?php $mts_options = get_option(MTS_THEME_NAME); ?>

<header id="site-header" role="banner" itemscope itemtype="http://schema.org/WPHeader" class="header-1">

	<div id="header">
        <button class="menu-vertical-icon pull-left" tabindex="1">
            Tất cả <br><span>Môn thể thao</span> <span class="spinner" title="css loader"></span></button>
		<div class="container-aa">

			<div class="logo-wrap">
				<?php if ($mts_options['mts_logo'] != '') { ?>

					<?php if( is_front_page() || is_home() || is_404() ) { ?>

						<h1 id="logo" class="image-logo" itemprop="headline">

							<a href="<?php echo esc_url( home_url() ); ?>"><img src="<?php echo esc_attr( $mts_options['mts_logo'] ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"></a>

						</h1><!-- END #logo -->

					<?php } else { ?>

						<h2 id="logo" class="image-logo" itemprop="headline">

							<a href="<?php echo esc_url( home_url() ); ?>"><img src="<?php echo esc_attr( $mts_options['mts_logo'] ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"></a>

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

					<div class="widget widget_search">

						<form method="get" id="searchform" class="search-form" action="<?php echo esc_attr( home_url() ); ?>" _lpchecked="1">
							<fieldset>
								<input type="text" name="s" id="s" value="" placeholder="<?php if ( mts_isWooCommerce() ) { _e( 'Search Products...', MTS_THEME_TEXTDOMAIN ); } else { _e( 'Search Blog Posts...', MTS_THEME_TEXTDOMAIN ); } ?>"  autocomplete="off" />
								<input type="hidden" name="post_type" value="<?php if ( mts_isWooCommerce() ) { echo 'product'; } else { echo 'post'; } ?>" class="post-type-input"/>
								<button id="search-image" class="sbutton" type="submit" value=""><i class="fa fa-search"></i></button>
							</fieldset>
						</form>

					</div>
                    <div class="contact" style="
                        float: left;
                        margin-right: 25px;
                        padding-top: 5px;
                        padding-bottom: 7px;
                        padding-right: 25px;
                        border-right: 1px solid #d5d8db;">
                        <a class="face-book" href="#"> FACEBOOK </a></div>


					<?php mts_wishlist_link(); ?>



					<?php if ( mts_isWooCommerce() ) mts_cart(); ?>



			    </div><!--.header-right-below-->

			</div><!--.header-right-->

		</div><!--.container-->
	</div><!-- #header-->
    <?php wc_get_template('custom/header-custom.php'); ?>
	<?php if ( $mts_options['mts_show_primary_nav'] == '1'  && false) { ?>

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

</header>