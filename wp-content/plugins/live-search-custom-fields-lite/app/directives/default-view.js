angular.module(angAppName)
    .directive('viewmodeDefault', function( customFilterService ) {
        
        return{
            
            restrict:"AE",
            scope:true,
            bindToController: true,
            controllerAs: 'vm',
			templateUrl: capfData.plugin_url + 'app/views/posts-default.html',
            link:function( scope, elem, attrs ) {

				scope.actionSettings.initPostTheme = true;
				
				var postsLayout = new lscfPosts();
				
				scope.directiveInfo.ready = function(){

					postsLayout.init();
					postsLayout.constructHover();

					if ( 'undefined' !== typeof capfData.settings && 'undefined' !== typeof capfData.settings.theme ) {
						if ( 'undefined' === typeof capfData.settings.theme.viewchanger || 'undefined' === typeof capfData.settings.theme.viewchanger.list || 1 == capfData.settings.theme.viewchanger.grid || 1 != capfData.settings.theme.viewchanger.list ) {
							
							jQuery( '.lscf-posts-block' ).addClass('block-view');
							jQuery( '.viewMode > div' ).removeClass('active');
							jQuery( '.viewMode #blockView' ).addClass('active');

						}
					}

				};					
					
				scope.directiveInfo.afterPostsLoadCallback = function(){

					postsLayout.constructHover();
				
				};

			}
		};
	});