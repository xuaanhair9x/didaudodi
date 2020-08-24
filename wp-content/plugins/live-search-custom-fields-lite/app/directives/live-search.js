angular.module(angAppName)
    .directive('liveSearch', function(customFilterService){
        
        return{
            
            restrict:"AE",
            require: "?ngModel",
            scope:true,
            bindToController: true,
            controllerAs: 'vm',
            link:function( scope, elem, attrs, ngModel ) {
                
				var activePostsList = [],
					default_filter = null,
					postsFirstLoad = true;

				scope.postsHasLoaded = false;

				lscf_initLiveSearch(function(){
					
					$j('.lscf-live-search-input').focus(function(){

						if ( true === scope.actionSettings.lsLoadPosts ) {

							scope.postsHasLoaded = false;

							if ( 'undefined' !== typeof capfData.settings.theme.posts_display_from && 
								'undefined' !== capfData.settings.theme.posts_display_from.post_taxonomies.active_terms && 
									capfData.settings.theme.posts_display_from.post_taxonomies.active_terms.length > 0 ) 
							{
								default_filter = {
									"default_filter":{}
								};

								default_filter.default_filter.post_taxonomies = capfData.settings.theme.posts_display_from.post_taxonomies;

							} else {

								default_filter = scope.actionSettings.customFields;

							}

							customFilterService.getPosts( scope.postType, 200, 1, default_filter )
								.success( function( data ) {

									scope.postsHasLoaded = true;
									scope.actionSettings.lsLoadPosts = false;

									if ( true === postsFirstLoad ) { 
										lscfPostsList = new lscfPosts();
									}

									postsFirstLoad = false;

									posts = data.posts;
							});
						}

					});

				});

				scope.$watch( "pxsearch", function( newVal, oldVal ) {

					scope.$watch( "postsHasLoaded", function( new_posts_status, old_posts_status ) {

						if ( true === new_posts_status ) {

								if ( typeof newVal !== 'undefined' && newVal != oldVal  ) {
									
									scope.$parent.loadMoreBtn.morePostsAvailable = false;
									var updatedPostList = [];

									if ( newVal !== '' && 'undefined' !== typeof posts ) {
										
										posts.forEach(function(post){
										
											var sTitleLong = pxDecodeEntities( post.title.long.replace( /(<strong(.*?)class\=\"matched-word\"\>)|(<\/strong\>)/ig, '' ) );
											var sTitleShort = pxDecodeEntities( post.title.short.replace( /(<strong(.*?)class\=\"matched-word\"\>)|(<\/strong\>)/ig, '' ) );
											var sContent = pxDecodeEntities ( post.content.replace( /(<strong(.*?)class\=\"matched-word\"\>)|(<\/strong\>)/ig, '' ) );
								
											if ( newVal.toLowerCase() == sTitleShort.toLowerCase() ) {
												post.class_name = 'ls-matches-search';
											} else {
												post.class_name = '';
											}

											if ( sTitleLong.toLowerCase().indexOf(newVal.toLowerCase()) != -1 || post.full_content.toLowerCase().indexOf(newVal.toLowerCase()) != -1 ) {
													
													sTitleLong = sTitleLong.replace(new RegExp( '(' + newVal + ')', 'ig'), '<strong class="matched-word">' + "$1" + '</strong>');
													sTitleShort = sTitleShort.replace(new RegExp( '(' + newVal + ')', 'ig'), '<strong class="matched-word">' + "$1" + '</strong>');
													sContent = sContent.replace(new RegExp(newVal, 'ig'), '<strong class="matched-word">' + "$1" + '</strong>');
													
													post.title.long = sTitleLong;
													post.title.short = sTitleShort;
													post.content = sContent;
													
													updatedPostList.push(post);
											}
											
										});
										
									}
									else {
										if ( 'undefined' !== typeof posts ) {

											posts.forEach(function(post){
											
												var sTitleLong = post.title.long.replace( /(<strong(.*?)class\=\"matched-word\"\>)|(<\/strong\>)/ig, '' );
												var sTitleShort = post.title.short.replace( /(<strong(.*?)class\=\"matched-word\"\>)|(<\/strong\>)/ig, '' );
												var sContent = post.content.replace( /(<strong(.*?)class\=\"matched-word\"\>)|(<\/strong\>)/ig, '' );
												
												post.title.long = sTitleLong;
												post.title.short = sTitleShort;
												post.content = sContent;
												
												updatedPostList.push(post);
													
											});
										}
										
									}
									
									scope.$parent.filterPostsTemplate.posts = updatedPostList;
									lscfPostsList.constructHover(); 

								}
							}
						});
					});

                customFilterService.getPosts( scope.postType, 200, 1, default_filter )
                    .success( function( data ) {

						scope.$watch( "pxsearch_woo_sku", function( newVal, oldVal ) {
							
							if ( typeof newVal !== 'undefined' && newVal != oldVal  ){
								
								scope.$parent.loadMoreBtn.morePostsAvailable = false;
                                var updatedPostList = [];

                                if (newVal !== '') {
                                    
                                    posts.forEach(function(post){
                                    
                                        if ( 'undefined' !== typeof post.woocommerce.sku && post.woocommerce.sku.toLowerCase().indexOf(newVal.toLowerCase()) != -1 ) {

                                                updatedPostList.push(post);
                                        }
                                        
                                    
                                    });
                                    
                                }
                                else{
                                    if ( 'undefined' !== posts ) {

										posts.forEach(function(post){

											updatedPostList.push(post);
												
										});
									}
                                    
                                }
                                
                                scope.$parent.filterPostsTemplate.posts = updatedPostList;
								lscfPostsList.constructHover(); 

                            }

						});
                
                    });
            }
            
        };
        
    });

	function lscf_initLiveSearch( callback ){
		
		var $j = jQuery,
			self = this,
			checkInterval;


		this.ready = function(){

			if ( $j('.lscf-live-search-input').length && $j('.lscf-live-search-input').length > 0 ){
				
				clearInterval( checkInterval );
				callback();

			}

		};

		checkInterval = setInterval( self.ready(), 500 );

		setTimeout(function(){
			clearInterval( checkInterval );
		}, 3000);

	}