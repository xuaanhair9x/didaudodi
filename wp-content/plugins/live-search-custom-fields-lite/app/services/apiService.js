angular.module(angAppName)
    .factory( "capfAPI", function($http){
        
        var URI = pxData.ajaxURL+"?action=px-ang-http";
        
        return{
            uri:URI
        };
            
    });