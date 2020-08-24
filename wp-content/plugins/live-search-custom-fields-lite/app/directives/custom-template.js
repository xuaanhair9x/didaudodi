angular.module(angAppName)
    .directive('viewmodeCustom', function( customFilterService ) {
		
        return{
            
            restrict:"AE",
            scope:true,
            bindToController: true,
            controllerAs: 'vm',
            link:function( scope, elem, attrs ) {


				scope.directiveInfo.ready = function(){
					
					if ( 'undefined' !== typeof lscfOnCustomTemplateReady ) {
						lscfOnCustomTemplateReady();
					}

					setTimeout(function(){
						lscfEventListenerOnCustomTemplateReady();
					}, 300);
				};					
					
				scope.directiveInfo.afterPostsLoadCallback = function(){
					if ( 'undefined' !== typeof lscfPostsLoadCallback ) {
						lscfPostsLoadCallback();
					}
					setTimeout(function(){
						lscfEventListenerPostsLoadCallback();					
					},400);
				};				

			},
			template: '<div ng-include="pluginSettings.filterSettings.theme.custom_template.url">'
		};
	});



function lscfEventListenerOnCustomTemplateReady( state ) {
    
	var evt = new CustomEvent('lscf_on_custom_template_ready', { detail: state });

    window.dispatchEvent( evt );
}

function lscfEventListenerPostsLoadCallback( state ) {
    
	var evt = new CustomEvent('lscf_posts_load_callback', { detail: state });

    window.dispatchEvent( evt );
}