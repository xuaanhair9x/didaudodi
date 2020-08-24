var angAppName='';
var appElems = document.querySelectorAll('[ng-app]');

if( appElems.length >= 1 ){
    angAppName = appElems[0].getAttribute('ng-app');    
    
    if(angAppName == 'px-capf'){

        angular.module("px-capf", ['ngSanitize', 'ngAnimate'])
            .config( function( $sceDelegateProvider ){

				$sceDelegateProvider.resourceUrlWhitelist([
					// Allow same origin resource loads.
					'self',
					// Allow loading from outer templates domain.
					capfData.plugin_url+'**'
				]);

            });
    }
}
else{
    angAppName = 'px-capf';
    angular.module("px-capf", ['ngSanitize', 'ngAnimate'])
        .config( function( $sceDelegateProvider ){
			
			$sceDelegateProvider.resourceUrlWhitelist([
				// Allow same origin resource loads.
				'self',
				// Allow loading from outer templates domain.
				capfData.plugin_url+'**'
			]);
			
        });
}

   

