<?php

$active_user = wp_get_current_user();
$is_administrator = false;

if ( in_array( 'administrator', $active_user->roles, true ) ) {
	$is_administrator = true;
}

$filter_settings = ( isset( $filter_data['settings'] ) ? $filter_data['settings'] : array() );

$sidebar_position = ( isset( $filter_settings['theme']['sidebar']['position'] ) ? $filter_settings['theme']['sidebar']['position'] : 'left' );

$view_changer = array();
$view_changer['grid'] = ( isset( $filter_settings['theme']['viewchanger']['grid'] ) ? (int) $filter_settings['theme']['viewchanger']['grid'] : 0 );
$view_changer['list'] = ( isset( $filter_settings['theme']['viewchanger']['list'] ) ? (int) $filter_settings['theme']['viewchanger']['list'] : 0 );

$container_class = lscf_return_viewchanger_class( $view_changer );

if ( isset( $filter_data['only_posts_show'] ) && '1' == $filter_data['only_posts_show'] ) {

	$sidebar_position = null;
	$filter_sidebar = 0;
	$post_container_class = 'col-xs-12 col-sm-12 col-md-12 col-lg-12 only-posts-show';

	$wrapper_class = 'wide';

} else {

	$filter_sidebar = 1;

	if ( isset( $filter_settings['theme']['columns'] )  && 4 === $filter_settings['theme']['columns'] && ( 'default' == $filter_settings['theme']['display'] ) ) {

		$wrapper_class = 'wide';
		$post_container_class = 'col-xs-12 col-sm-9 col-md-10 col-lg-10';
		$sidebar_class = 'col-xs-12 col-sm-3 col-md-2 col-lg-2 px-filter-fields';

	} else {

		$wrapper_class = '';
		$post_container_class = 'col-xs-12 col-sm-9 col-md-9 col-lg-9';
		$sidebar_class = 'col-xs-12 col-sm-3 col-md-3 col-lg-3 px-filter-fields';
	}
}


?>
<div class="px-capf-wrapper row <?php echo esc_attr( $wrapper_class ); ?>" ng-app="px-capf">

	<div class="col-sm-12 col-md-12 col-lg-12 lscf-container <?php echo esc_attr( $container_class )?>" ng-controller="pxfilterController" >

		<?php

		if ( true === $is_administrator ) {
		?>
			<div class="lscf-sidebar-live-customizer" sidebar-live-customizer></div>
		<?php

		}

		?>

		<div class="row" id="lscf-posts-wrapper" ng-if="pluginSettings.existsPosts" ng-class="{'lscf-administrator' : pluginSettings.filterSettings.is_administrator == 1 }">

			<div ng-if="pluginSettings.filterSettings.theme.sidebar.position == 'left' " class="{{ 'col-xs-12 ' +pluginSettings.className.sidebar+' px-filter-fields' }}" ng-include="pluginSettings.pluginPath + 'app/views/filterFields.html'"></div>


			<div class="{{ 'col-xs-12 ' + pluginSettings.className.posts_theme + 'lscf-posts' }} ">

				<div class="px-posts-overlay-loading px-hide" ng-class="{'ang_ready' : loadMoreBtn.ready}" ng-if="loadMoreBtn.postsLoading" >
					<img src="<?php echo esc_url( LSCF_PLUGIN_URL . 'assets/images/loading_light.gif' ); ?>">
				</div>
				
				<div class="no-filter-results-error px-hide" ng-class="{'ang_ready' : loadMoreBtn.ready}" ng-if="loadMoreBtn.noResults">No Results</div>
			
				<div ng-switch="pluginSettings.filterSettings.theme.display">
					
					<div ng-switch-when="default">
						
						<div class="row filter-headline">
							
							<div class="viewModeBlock col-sm-12 col-md-12 col-lg-12 ">
					
								<div class="viewMode">
									<div id="blockView" class="glyphicon glyphicon-th"></div>
									<div id="listView" class="active glyphicon glyphicon-th-list"></div>
								</div>

							</div>
							
						</div>
						
						<div id="lscf-posts-container-defaultTheme" class="view lscf-posts-block lscf-grid-view" viewmode-default></div>

					</div>

				</div>
				<div class="clear"></div>
				
				<div class="capf_loading px-hide" ng-class="{'ang_ready' : loadMoreBtn.ready}" ng-if="loadMoreBtn.morePostsAvailable">
					<img ng-if="loadMoreBtn.loading" src="<?php echo esc_url( LSCF_PLUGIN_URL . 'assets/images/loading_light.gif' ); ?>"><br/>
					<label class="loadMore" ng-if="!loadMoreBtn.loading" ng-click="load_more()"><?php echo esc_attr( $options['writing']['load_more'] )?></label>
				</div>

			</div>
	
			<div ng-if="pluginSettings.filterSettings.theme.sidebar.position == 'right' " class="{{ 'col-xs-12 ' +pluginSettings.className.sidebar+' px-filter-fields' }}" ng-include="pluginSettings.pluginPath + 'app/views/filterFields.html'"></div>

			
		</div>

	</div>

</div>
