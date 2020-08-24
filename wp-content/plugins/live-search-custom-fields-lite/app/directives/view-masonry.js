angular.module(angAppName)
    .directive('viewmodeMasonryGrid', function( customFilterService ){
        
        return{
            
            restrict:"AE",
            scope:true,
            bindToController: true,
            controllerAs: 'vm',
			templateUrl: capfData.plugin_url + 'app/views/posts-mansonry.html',
            link:function(scope, elem, attrs){
				
				scope.actionSettings.initPostTheme = true;

				scope.directiveInfo.ready = function(){		

				};					
					
				scope.directiveInfo.afterPostsLoadCallback = function(){
					
				};

			}
		};
	});