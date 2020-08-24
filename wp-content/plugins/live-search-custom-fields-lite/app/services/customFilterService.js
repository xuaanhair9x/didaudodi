angular.module(angAppName)
    .factory( "customFilterService", function($http, capfAPI){
        
        function getFilterFields(ID){
            
            return $http({
                method:"post",
                url:capfAPI.uri,
                data:{
                    section:"getFilterFields",
                    filter_id:ID
                }
            });
            
        }

		function getSidebar(  ){
            
            return $http({
                method:"post",
                url:capfAPI.uri,
                data:{
                    section:"getSidebar"
                }
            });
            
        }

        function getPosts(postType, postsPerPage, page, q){
          
            return $http({
                method:"post",
                url:capfAPI.uri,
                data:{
                    section:"getPosts",
                    post_type:postType,
                    featured_label:capfData.featuredLabel,
                    limit:postsPerPage,
                    page:page,
                    q:q,
					filter_id:capfData.ID
                }
            });
            
        }
        
        return{
            getFilterFields:getFilterFields,
            getPosts:getPosts,
			getSidebar:getSidebar
        };                
            
    });