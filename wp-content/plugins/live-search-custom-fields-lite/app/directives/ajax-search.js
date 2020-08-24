angular.module(angAppName)
    .directive('ajaxSearch', function(customFilterService){
        
        return{
            
            restrict:"AE",
            require: "?ngModel",
            scope:true,
            bindToController: true,
            controllerAs: 'vm',
            link:function( scope, elem, attrs, ngModel ) {
                
				var activePostsList = [];

				var searchType = attrs.searchType;

				

				elem.bind("keydown keypress", function (event) {
					
					if ( event.which === 13 ) {

						var searchQ,
							query = {},
							field_exists = false,
							field;

						switch ( searchType ) {
							
							case "general-search":
								
								searchQ = scope.pxsearch;
								
								field_exists = false;

								for ( var c=0; i < scope.actionSettings.customFields.length; i++ ) {
									field = scope.actionSettings.customFields[c];
									if ( 'ajax-main-search' == field.ID ) {
										scope.actionSettings.customFields[c].value = searchQ;
										field_exists = true;
									}
								}
								
								if ( false === field_exists ) {

									query = {
										"ID":"ajax-main-search",
										"filter_as":null,
										"type":"main-search",
										"value":searchQ
									};

									scope.actionSettings.customFields.push(query);
								}
														
								break;
							
							case "woo-product-sku":
								
								searchQ = scope.pxsearch_woo_sku;
								
								field_exists = false;

								for ( var i=0; i < scope.actionSettings.customFields.length; i++ ) {
									field = scope.actionSettings.customFields[i];
									if ( 'ajax-product-sku-search' == field.ID ) {
										scope.actionSettings.customFields[i].value = searchQ;
										field_exists = true;
									}
								}

								if ( false === field_exists ) {

									query = {
										"ID":"ajax-product-sku-search",
										"filter_as":null,
										"type":"main-search",
										"value":searchQ
									};

									scope.actionSettings.customFields.push(query);

								}
								
								
								break;
						}



						scope.loadMoreBtn.postsLoading = true;


						customFilterService.getPosts( scope.postType, scope.postsPerPage, 1, scope.actionSettings.customFields )
							.success(function( data ) {

								scope.actionSettings.postsCount = data.postsCount;
								scope.actionSettings.pagesCount = data.pages;
								scope.actionSettings.pxCurrentPage = 2;

								if ( scope.actionSettings.pxCurrentPage <= data.pages) scope.loadMoreBtn.morePostsAvailable = true;
								else scope.loadMoreBtn.morePostsAvailable = false;

								scope.actionSettings.filterPostsTemplate = data.posts;
								scope.filterPostsTemplate.posts = data.posts;
								scope.loadMoreBtn.postsLoading = false;

								scope.directiveInfo.afterPostsLoadCallback();

							});

						event.preventDefault();

					}
				});


            }
            
        };
        
    });