var angAppName='';
var appElems = document.querySelectorAll('[ng-app]');

if( appElems.length >= 1 ){
    angAppName = appElems[0].getAttribute('ng-app');    
    
    if(angAppName == 'px-capf'){

        angular.module("px-capf", ['ngSanitize', 'ngAnimate'])
            .config( ['$sceDelegateProvider', function( $sceDelegateProvider ){

				$sceDelegateProvider.resourceUrlWhitelist([
					// Allow same origin resource loads.
					'self',
					// Allow loading from outer templates domain.
					capfData.plugin_url+'**'
				]);

            }]);
    }
}
else{
    angAppName = 'px-capf';
    angular.module("px-capf", ['ngSanitize', 'ngAnimate'])
        .config( ['$sceDelegateProvider', function( $sceDelegateProvider ){
			
			$sceDelegateProvider.resourceUrlWhitelist([
				// Allow same origin resource loads.
				'self',
				// Allow loading from outer templates domain.
				capfData.plugin_url+'**'
			]);
			
        }]);
}

   


angular.module(angAppName)
    .controller("pxfilterController", ['$scope', 'customFilterService', function ($scope, customFilterService) {

        var postsPerPage = capfData.post_per_page,
            page = 1;

        var filterID = capfData.ID,
			filterType = '',
            postType = capfData.postType,
            filterFieldsTemplate,
			dataPostsDefault,
			defaultLoadMoreBtnStatus,
			wrapperGeneralClassNames,
			sidebarSection,
			previousSidebarSection,
            rangeField = new px_customRange(),
            customSelectBoxes = new customSelectBox(),
			defaultFilter = null,
			$j = jQuery,
            filterFieldsAction = new pxFilterFieldsAction();


        $scope.loading = false;
        $scope.noResults = false;
        $scope.morePostsAvailable = false;
		$scope.variations = {};


        $scope.featuredLabel = false;
        $scope.postType = postType;

		$scope.existsPosts = false;
        $scope.filterPostsTemplate = {};

		// methods: ready; afterPostsLoadCallback 
		$scope.directiveInfo = {};

		$scope.liveSearchTemplate = {
			"class":""
		};

		$scope.actionSettings = {
			"customFields":[],
			"pxCurrentPage":page,
			"postsPerPage":postsPerPage,
			"lsLoadPosts":true,
			"activeTerms":[],
			"postsCount":"",
			"pagesCount":"",
			"activeQuery":[],
			"filterPostsTemplate":[],
			"previousSidebarPosition":"",
			"disableInactiveTerms":false,
			"initSidebar":false,
			"initPostTheme":false,
			"initFieldsDraggable":false,
			"isAdministrator":('undefined' !== typeof capfData.settings.is_administrator ? parseInt( capfData.settings.is_administrator ) : 0 )
		};
		
		$scope.lscfSidebar = {};

        $scope.loadMoreBtn = {
            "morePostsAvailable": false,
            "noResults": false,
            "loading": false,
			"postsLoading": false,
			"ready":true,
			"type":"default"
        };

		$scope.pluginSettings = {
			"className":{
				"sidebar":"",
				"posts_theme":""
			},
			"existsPosts": true,
			"pluginPath":capfData.plugin_url,
			"filterSettings":capfData.settings,
			"generalSettings":capfData.options,
			"editShortcodeLink":capfData.site_url + '/wp-admin/admin.php?page=pxLF_plugin&plugin-tab=filter-generator&edit_filter=' + capfData.ID
		};


		$scope.makeWrapperClassName = function( ) {

			var dataClass = {
				"sidebar":"",
				"posts_theme":""
				
			};

			if ( 'top' == $scope.pluginSettings.filterSettings.theme.sidebar.position ||  '0' == $scope.pluginSettings.filterSettings.theme.sidebar.position ) {

				dataClass.sidebar = 'col-sm-12 col-md-12 col-lg-12 lscf-horizontal-sidebar ';
				dataClass.posts_theme = 'col-sm-12 col-md-12 col-lg-12 lscf-wide-posts ';	

			} else {
				
				dataClass.sidebar =  ( capfData.settings.theme.columns > 3 ? 'col-sm-2 col-md-2 col-lg-2 ' : 'col-sm-3 col-md-3 col-lg-3 ' );
				dataClass.posts_theme = ( capfData.settings.theme.columns > 3 ? 'col-sm-10 col-md-10 col-lg-10 ' : 'col-sm-9 col-md-9 col-lg-9 ' );

			}

			return dataClass;

		};

		$scope.$watch('actionSettings.postsPerPage', function( newVal, oldVal ){
			if ( newVal != oldVal ) {
				
				customFilterService.getPosts( postType, $scope.actionSettings.postsPerPage, page, $scope.actionSettings.activeQuery )
                 	.success(function (data) {

						$scope.actionSettings.postsCount = data.postsCount;
						$scope.actionSettings.pagesCount = data.pages;
						$scope.actionSettings.pxCurrentPage = page + 1;

						if ($scope.actionSettings.pxCurrentPage > data.pages) $scope.loadMoreBtn.morePostsAvailable = false;
						$scope.loadMoreBtn.loading = false;

						$scope.actionSettings.filterPostsTemplate = data.posts;

						$scope.filterPostsTemplate.posts = $scope.actionSettings.filterPostsTemplate;

						$scope.directiveInfo.afterPostsLoadCallback();

                });
			}
		});



		$scope.$watch('actionSettings.initPostTheme', function( newVal, oldVal ){

			if ( true === newVal ) {

				$scope.actionSettings.initPostTheme = false;
				
				$scope.directiveInfo.ready();
			}

		});

		wrapperGeneralClassNames = $scope.makeWrapperClassName( $scope.pluginSettings.filterSettings.theme.sidebar.position );

		$scope.pluginSettings.className.sidebar = wrapperGeneralClassNames.sidebar;
		$scope.pluginSettings.className.posts_theme = wrapperGeneralClassNames.posts_theme;
		


        $scope.load_more = function () {

            $scope.loadMoreBtn.loading = true;

			var loadMoreQ = $scope.actionSettings.customFields;

			if ( null !== defaultFilter && loadMoreQ.length === 0 ) {
				loadMoreQ = defaultFilter;
			}


            customFilterService.getPosts( postType, $scope.actionSettings.postsPerPage, $scope.actionSettings.pxCurrentPage, loadMoreQ )
                .success(function (data) {

                    $scope.actionSettings.postsCount = data.postsCount;
                    $scope.actionSettings.pagesCount = data.pages;
                    $scope.actionSettings.pxCurrentPage += 1;

                    if ($scope.actionSettings.pxCurrentPage > data.pages) $scope.loadMoreBtn.morePostsAvailable = false;
                    $scope.loadMoreBtn.loading = false;

                    $scope.actionSettings.filterPostsTemplate = $scope.actionSettings.filterPostsTemplate.concat( data.posts );

                    $scope.filterPostsTemplate.posts = $scope.actionSettings.filterPostsTemplate;

					$scope.directiveInfo.afterPostsLoadCallback();

                });

        };


		customFilterService.getSidebar()
			.success(function(data){
				$scope.lscfSidebar.html = data;
			});

        customFilterService.getFilterFields(filterID)
            .success(function (data) {

				filterType = data.filter_type;

				if ( 'undefined' !== typeof ( data.default_data.custom_templates ) ) {
					$scope.pluginSettings.custom_templates = data.default_data.custom_templates;
				}


				if ( 'undefined' !== typeof data.default_data.settings.theme.posts_display_from && 
					'undefined' !== data.default_data.settings.theme.posts_display_from.post_taxonomies.active_terms && 
					 data.default_data.settings.theme.posts_display_from.post_taxonomies.active_terms.length > 0 )  
				{

					var default_filter = {"default_filter":{}};
					if ( 'undefined' !== typeof data.fields ) {
						default_filter.default_filter.fields = data.fields;
					}

					default_filter.default_filter.post_taxonomies = data.default_data.settings.theme.posts_display_from.post_taxonomies;

					defaultFilter = default_filter;

					customFilterService.getPosts( postType, $scope.actionSettings.postsPerPage, page, default_filter)
						.success(function (data) {

							$scope.actionSettings.activeTerms = data.active_terms;
							$scope.actionSettings.postsHasLoaded = true;
							$scope.filterPostsTemplate.filter_type = data.filter_type;

							if ( data.postsCount < 1 ) { $scope.pluginSettings.existsPosts = false; }
							
							if (data.posts.length > 0) $scope.loadMoreBtn.noResults = false;
							else $scope.loadMoreBtn.noResults = true;

							if (data.featuredLabel === 1) $scope.featuredLabel = true;

							$scope.actionSettings.postsCount = data.postsCount;
							$scope.actionSettings.pagesCount = data.pages;
							$scope.actionSettings.pxCurrentPage = page + 1;
							$scope.allPostsCount = data.postsCount;


							if ( $scope.actionSettings.pxCurrentPage <= data.pages ) $scope.loadMoreBtn.morePostsAvailable = true;
							
							defaultLoadMoreBtnStatus = $scope.loadMoreBtn.morePostsAvailable;

							$scope.actionSettings.filterPostsTemplate = data.posts;
							dataPostsDefault = data.posts;
							
							$scope.filterPostsTemplate.posts = $scope.actionSettings.filterPostsTemplate;

							$scope.directiveInfo.ready();

							$scope.directiveInfo.afterPostsLoadCallback();

							$scope.loadMoreBtn.postsLoading = false;
							
							defaultFilter = null;

						});

				} else {

					customFilterService.getPosts(postType, $scope.actionSettings.postsPerPage, page, null)
						.success(function (data) {

							$scope.actionSettings.activeTerms = data.active_terms;
							$scope.filterPostsTemplate.filter_type = data.filter_type;

							if ( data.postsCount < 1 ) { $scope.pluginSettings.existsPosts = false; }
							
							if (data.posts.length > 0) $scope.loadMoreBtn.noResults = false;
							else $scope.loadMoreBtn.noResults = true;

							if (data.featuredLabel === 1) $scope.featuredLabel = true;

							$scope.actionSettings.postsCount = data.postsCount;
							$scope.actionSettings.pagesCount = data.pages;
							$scope.actionSettings.pxCurrentPage = page + 1;
							$scope.allPostsCount = data.postsCount;


							if ( $scope.actionSettings.pxCurrentPage <= data.pages ) $scope.loadMoreBtn.morePostsAvailable = true;
							
							defaultLoadMoreBtnStatus = $scope.loadMoreBtn.morePostsAvailable;

							$scope.actionSettings.filterPostsTemplate = data.posts;
							dataPostsDefault = data.posts;
							
							$scope.filterPostsTemplate.posts = $scope.actionSettings.filterPostsTemplate;

							$scope.directiveInfo.ready();

							$scope.directiveInfo.afterPostsLoadCallback();

							$scope.loadMoreBtn.postsLoading = false;

						});
				}

				var dataToFilter = [];


				if ( null !== defaultFilter ) {

					for ( var i = 0; i < defaultFilter.default_filter.post_taxonomies.active_terms.length; i++ ) {

						var matches = defaultFilter.default_filter.post_taxonomies.active_terms[i].match( /^([0-9]+)-(.*)/ ),
							catID = parseInt( matches[1] ),
							taxID = matches[2];

						for ( var k = 0; k < data.fields.length; k++ ) {

							if ( 'taxonomies' == data.fields[ k ].group_type &&
								'undefined' !== typeof data.fields[ k ].tax  && 
								data.fields[ k ].tax.slug == taxID ) 
							{
									
								for ( var t = 0; t < data.fields[ k ].tax.terms.length; t++ ) {
									
									var term = data.fields[ k ].tax.terms[ t ];
									
									if ( catID == term.data.value ) {
										data.fields[ k ].tax.terms[ t ].checked = true;
									} else {
										data.fields[ k ].tax.terms[ t ].checked = false;
									}
								}
							}

						}

					}
				}

                filterFieldsTemplate = data;
				
                $scope.filterFieldsTemplate = filterFieldsTemplate;
				

				$scope.actionSettings.initSidebar = true;
				$scope.$watch('actionSettings.initSidebar', function( newVal, oldVal ){

					sidebarSection = $scope.pluginSettings.filterSettings.theme.sidebar.position == 'left' || $scope.pluginSettings.filterSettings.theme.sidebar.position == 'top' ? 1 :2;

					if ( $scope.actionSettings.previousSidebarPosition == 'left' || $scope.actionSettings.previousSidebarPosition == 'top' ) {
						
						previousSidebarSection = 1;

					} else if(  '' !== $scope.actionSettings.previousSidebarPosition ) {
						
						previousSidebarSection = 2;

					}

					if ( true === newVal ) {
						
						$scope.actionSettings.initSidebar = false;
						$scope.actionSettings.previousSidebarPosition = $scope.pluginSettings.filterSettings.theme.sidebar.position;

						if ( sidebarSection === previousSidebarSection ) {
							return;
						}

						customSelectBoxes.construct();	
						

						// callback; 
						//it's called on field action
						filterFieldsAction.construct(function ( dataValue ) {

							// reset page
							page = 1;
							dataToFilter = $scope.actionSettings.customFields;

							var dataAction = "add";
							
							$scope.loadMoreBtn.postsLoading = true;

							if ( dataValue.constructor !== Array ) {
								var dataArray = [];
								dataArray[0] = dataValue;
								dataValue = dataArray;
							} 

							$j('.lscf-live-search-input').val('');

							dataValue.forEach(function(data){

								switch (data.type) {

									case "date-interval":

										if (data.fields.from === '' || data.fields.to === '') {

											return;
										}

										break;

									case "checkbox_post_terms":
										
										if ( data.value.length === 0 ) { 

											dataAction = 'remove';
										}

										
										break;

									case "px_icon_check_box":
									case "checkbox":

										if ( data.value.length === 0 ) {

											dataToFilter = removeObjectKey(dataToFilter, data.ID);

											if ( 'checkbox_post_terms' == data.filter_as ) {
											
												for ( var k_cb in dataToFilter ) {
												
													if ( 'default_filter' == dataToFilter[ k_cb ].type ) {
														dataToFilter[ k_cb ].default_filter.post_taxonomies = lscf_reset_default_filter( dataToFilter[ k_cb ].default_filter.post_taxonomies, data );
														break;
													}
												}

											}

											dataAction = 'remove';

										}

										break;


									case "select":

										if ( data.value.constructor === Array ) {
											sVal = data.value[0];
										} else {
											sVal = data.value;
										}

										if (sVal.toLowerCase() == 'select' || sVal == '0') {

											dataToFilter = removeObjectKey(dataToFilter, data.ID);
											dataToFilter = removeObjectLikeKey( dataToFilter, data.ID );


											if ( 'cf_variation' == data.group_type ) {
												if ( 'undefined' === typeof $scope.variations[ data.ID ] ) {
													$scope.variations[ data.variation_id ] = {};
												}

												$scope.variations[ data.variation_id ].active = 'default';
											}

											dataAction = 'remove';

											if ( 'checkbox_post_terms' == data.filter_as ) {
												for ( var k_s in dataToFilter ) {
													
													if ( 'default_filter' == dataToFilter[ k_s ].type ) {
														dataToFilter[ k_s ].default_filter.post_taxonomies = lscf_reset_default_filter( dataToFilter[ k_s ].default_filter.post_taxonomies, data );
														break;
													}
												}
											}

										} else if( 'checkbox_post_terms' == data.filter_as ) {

											dataToFilter = lscf_data_to_filter_subcategories( dataToFilter, data, filterFieldsTemplate );

										}

										break;

									case "radio":
										
										if ( data.value.constructor === Array ) {

											sVal = data.value[0];

										} else {

											sVal = data.value;
										}


										if ( sVal == '0' ) {
											
											dataToFilter = removeObjectKey(dataToFilter, data.ID);

											dataAction = 'remove';

											if ( 'cf_variation' == data.group_type ) {
												if ( 'undefined' === typeof $scope.variations[ data.ID ] ) {
													$scope.variations[ data.variation_id ] = {};
												}

												$scope.variations[ data.variation_id ].active = 'default';
											}

											if ( 'checkbox_post_terms' == data.filter_as ) {
												for ( var k_r in dataToFilter ) {
												
													if ( 'default_filter' == dataToFilter[ k_r ].type ) {
														dataToFilter[ k_r ].default_filter.post_taxonomies = lscf_reset_default_filter( dataToFilter[ k_r ].default_filter.post_taxonomies, data );
														break;
													}
												}
											}

										} else if( 'checkbox_post_terms' == data.filter_as ) {

											dataToFilter = lscf_data_to_filter_subcategories( dataToFilter, data, filterFieldsTemplate );

										}

										break;

									case "date":

										if (data.value === '') {

											dataToFilter = removeObjectKey(dataToFilter, data.ID);
											dataAction = 'remove';
										}

										break;
								}
							


								if ( dataAction == 'add' ) {

									if ( 'cf_variation' == data.group_type ) {

										if ( 'undefined' === typeof $scope.variations[ data.ID ] ) {
											$scope.variations[ data.variation_id ] = {};
										}
										var variation_option = ( data.value.constructor == Array ? data.value[0] : data.value );
										$scope.variations[ data.variation_id ].active = lscf_sanitize_string( variation_option );
										
									}

									var fdFieldExists = false;
									
									for ( var k in dataToFilter ) {
										if ( dataToFilter[ k ].ID == data.ID ) {
											dataToFilter[ k ] = data;
											fdFieldExists = true;
											break;
										}
									}

									if ( false === fdFieldExists ) {
										dataToFilter[ data.ID ] = data;
									}

									if ( 'checkbox_post_terms' == data.filter_as ) {
										for ( var k_rr in dataToFilter ) {

											if ( 'default_filter' == dataToFilter[ k_rr ].type ) {
												dataToFilter[ k_rr ].default_filter.post_taxonomies = lscf_reset_default_filter( dataToFilter[ k_rr ].default_filter.post_taxonomies, data );
												break;
											}
										}
									}

									$scope.loadMoreBtn.type = 'default';	
								}

							});

							var q = [];

							var taxonomiesActiveIds = [],
								activeParentIDs;

							for ( var key in dataToFilter ) {
								
								var item = dataToFilter[ key ];
								
								if ( 'undefined' !== typeof item.ID ) {

									q.push( item );

									if ( 'undefined' !== typeof item.filter_as && 'checkbox_post_terms' == item.filter_as ) {

										var tax_slug = lscf_return_tax_main_slug( item.ID ),
											catValue;

										if ( 'undefined' === typeof taxonomiesActiveIds[ tax_slug ] ) {
											taxonomiesActiveIds[ tax_slug ] = [];
										}

										if ( Array === item.value.constructor ) {
											
											for ( var i = 0; i < item.value.length; i++ ) {

												catValue = parseInt( item.value[ i ] );

												taxonomiesActiveIds[ tax_slug ][ catValue ] = catValue;
											}

										} else {
											
											catValue = parseInt( item.value );

											taxonomiesActiveIds[ tax_slug ][ catValue ] = catValue;

										}
										
									}
									
								}
							}


							taxonomiesActiveIds = lscf_reset_object_index( taxonomiesActiveIds );

							if ( null !== defaultFilter && q.length === 0  ) {
								q = defaultFilter;
							}

							$scope.actionSettings.activeQuery = q;

							customFilterService.getPosts(postType, $scope.actionSettings.postsPerPage, page, q)
								.success(function (data) {

									$scope.actionSettings.lsLoadPosts = true;

									$scope.actionSettings.disableInactiveTerms = false;

									if (data.posts.length > 0) $scope.loadMoreBtn.noResults = false;
									else $scope.loadMoreBtn.noResults = true;

									$scope.actionSettings.activeTerms = data.active_terms;
									$scope.actionSettings.postsCount = data.postsCount;
									$scope.actionSettings.pagesCount = data.pages;
									$scope.actionSettings.pxCurrentPage = page + 1;
									$scope.actionSettings.customFields = q;

									if ( $scope.actionSettings.pxCurrentPage <= data.pages ) $scope.loadMoreBtn.morePostsAvailable = true;
									else $scope.loadMoreBtn.morePostsAvailable = false;

									$scope.actionSettings.filterPostsTemplate = data.posts;
									$scope.filterPostsTemplate.posts = data.posts;
									$scope.loadMoreBtn.postsLoading = false;

									$scope.directiveInfo.afterPostsLoadCallback();
									
								});

								$scope.reset_filter = function(){

									filterFieldsAction.reset_fields();

									$scope.filterPostsTemplate.posts = dataPostsDefault;
									$scope.actionSettings.filterPostsTemplate = dataPostsDefault;
									$scope.loadMoreBtn.morePostsAvailable = defaultLoadMoreBtnStatus;
									dataToFilter = [];
									$scope.actionSettings.customFields = [];
									$scope.actionSettings.pxCurrentPage = 2;
									$scope.loadMoreBtn.noResults = false;
									
									$scope.directiveInfo.afterPostsLoadCallback();

								};

						});

					}
				});

            });

    }]);

function lscf_data_to_filter_subcategories( dataToFilter, data, fieldsData ){

	var tax_slug = lscf_return_tax_main_slug( data.ID ),
		catID,
		parentIDs,
		parentID;

	dataToFilter = removeObjectKey(dataToFilter, data.ID);
									
	tax_slug = lscf_return_tax_main_slug( data.ID );
	catID = parseInt( data.value );

	dataToFilter = removeObjectLikeKey( dataToFilter, tax_slug );
	
	if ( 'undefined' !== typeof fieldsData.post_taxonomies[ tax_slug ] ) {

		if ( 'undefined' !== typeof fieldsData.post_taxonomies[ tax_slug ].tax.subcategs_hierarchy && 'undefined' !== typeof fieldsData.post_taxonomies[ tax_slug ].tax.subcategs_hierarchy[ catID ] ) {
		
			parentIDs = fieldsData.post_taxonomies[ tax_slug ].tax.subcategs_hierarchy[ catID ];

			for ( var i = 0; i < parentIDs.length; i++ ) {
				
				if ( 'undefined' !== typeof fieldsData.post_taxonomies[ tax_slug ].tax.subcategs_hierarchy[ parentIDs[ i ] ] ) {
					
					parentID = tax_slug + '_-_' + parentIDs[ i ];
					
					dataToFilter[ parentID ] = {
						"ID":parentID,
						"type":data.type,
						"value":parentIDs[ i ],
						"filter_as":data.filter_as
					};

				}
				
			}

		}

	}

	return dataToFilter;
}

function removeObjectKey(objectData, key) {

    var temp = {};

    for ( var prop in objectData ) {

        if ( objectData[ prop ].ID != key ) {
            temp[ prop ] = objectData[ prop ];
        }

    }

    return temp;
}
function removeObjectLikeKey(objectData, key){
	
	var temp = {};
    for (var prop in objectData) {

        if ( -1 === prop.indexOf(key) ) {
            temp[prop] = objectData[prop];
        }

    }

    return temp;
}

function lscf_reset_default_filter( default_filter, termData ){
	
	var termID = termData.value,
		tax_slug = lscf_return_tax_main_slug( termData.ID );


	if ( 'undefined' !== typeof default_filter[ tax_slug ] ) {

		var tax = default_filter[ tax_slug ].tax,
			termsData = [];

		for ( var t in tax.terms ){

			if ( 0 === termID ) {
				
				delete default_filter[ tax_slug ];

			} else if ( 'undefined' !== typeof default_filter[ tax_slug ] && 'undefined' !== typeof default_filter[ tax_slug ].tax &&  tax.terms[ t ].data.value != termID ) {

				if ( tax.terms.length == 1 ) {

					delete default_filter[ tax_slug ];

				} else {

					delete default_filter[ tax_slug ].tax.terms[ t ];

					var terms_t = lscf_reset_object_index( default_filter[ tax_slug ].terms );
					default_filter[ tax_slug ].terms = terms_t;
					default_filter[ tax_slug ].terms.length = terms_t.length;

					if ( terms_t.length === 0 ) {
						delete default_filter[ tax_slug ];
					}

				 }	
			}
		}
	}
	
	return default_filter;

}

function lscf_return_tax_main_slug( string ){
	
	var tax_slug = string;

	if ( string.match( /(.+?)_-_([0-9]+)$/ ) ) {
								
		var matches = string.match( /(.+?)_-_([0-9]+)$/ );
		
		tax_slug = matches[1];
	}

	return tax_slug;
}

function lscf_reset_object_index( objData ) {
	
	var results = [];
	
	for ( var key in objData ) {
		
		if ( 'undefined' === typeof results[ key ] ) {
			results[ key ] = [];
		}
		for ( var i in objData[ key ] ) {
			results[ key ].push( objData[ key ] [ i ] );
		}
		
	}

	return results;
}


function lscf_sanitize_string( string ) {
	string = string.replace( /([\!\@\#\$\%\^\&\*\(\[\)\]\{\-\}\\\/\:\;\+\=\.\\<\,\>\?\~\`\'\" ]+)/g, '_');
	return string.toLowerCase();
}
angular.module(angAppName)
    .factory( "capfAPI", ['$http', function($http){
        
        var URI = pxData.ajaxURL+"?action=px-ang-http";
        
        return{
            uri:URI
        };
            
    }]);
angular.module(angAppName)
    .factory( "customFilterService", ['$http', 'capfAPI', function($http, capfAPI){
        
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
            
    }]);
angular.module(angAppName)
    .directive('viewmodeAccordion', ['customFilterService', function(customFilterService){
        
        return{
            
            restrict:"AE",
            scope:true,
            bindToController: true,
            controllerAs: 'vm',
			templateUrl: capfData.plugin_url + 'app/views/posts-accordion.html',
            link:function(scope, elem, attrs){

				var accordionPosts = new lscfAccordionPosts();
				
				scope.actionSettings.initPostTheme = true;

				scope.directiveInfo.ready = function(){
					
					accordionPosts.options.link_type = ( 'undefined' !== typeof scope.pluginSettings.filterSettings.theme.link_type ? scope.pluginSettings.filterSettings.theme.link_type : 0 );

					setTimeout(function(){
						accordionPosts.init();
					}, 500);
					

				};					
					
				scope.directiveInfo.afterPostsLoadCallback = function(){

					setTimeout(function(){
						accordionPosts.init();
					}, 500);
				};

			}
		};
	}]);

function lscfAccordionPosts(){
	
	var $j = jQuery,
		self = this;

	this.options = {
		"link_type":0
	};

	this.init = function(){
		
		$j('.lscf-posts-accordion .lscf-title').unbind('click');

		if ( 'link-only' === self.options.link_type ) {
			return false;
		 }
		
		$j('.lscf-posts-accordion .lscf-title').bind( 'click', function(event){

			var parentContainer = $j(this).closest('.lscf-accordion-post');

			if ( parentContainer.hasClass('active') ) {
				parentContainer.find('.post-caption').animate({
					height:0
				}, 400);	
				parentContainer.removeClass('active');
				parentContainer.addClass('inactive');
				return false;
			}

			$j('.lscf-accordion-post').removeClass('active');
			$j('.lscf-accordion-post').addClass('inactive');

			
			parentContainer.addClass('active');
			parentContainer.removeClass('inactive');

			var animateHeight = parentContainer.find('.caption').height()+40;

			parentContainer.find('.post-caption').animate({
				height:animateHeight
			}, 400);

			$j('.lscf-accordion-post.inactive').find('.post-caption').animate({
				height:0
			}, 300);

			event.preventDefault();
			event.stopPropagation();

			return false;
		});

	};

}
angular.module(angAppName)
    .directive('ajaxSearch', ['customFilterService', function(customFilterService){
        
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
        
    }]);
angular.module(angAppName)
    .directive('viewmodeCustom', ['customFilterService', function( customFilterService ) {
		
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
	}]);



function lscfEventListenerOnCustomTemplateReady( state ) {
    
	var evt = new CustomEvent('lscf_on_custom_template_ready', { detail: state });

    window.dispatchEvent( evt );
}

function lscfEventListenerPostsLoadCallback( state ) {
    
	var evt = new CustomEvent('lscf_posts_load_callback', { detail: state });

    window.dispatchEvent( evt );
}
angular.module(angAppName)
    .directive('viewmodeDefault', ['customFilterService', function( customFilterService ) {
        
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
	}]);
angular.module(angAppName)
    .directive('liveSearch', ['customFilterService', function(customFilterService){
        
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
        
    }]);

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
angular.module(angAppName)
    .directive('viewmodePortrait', ['customFilterService', function(customFilterService){
        
        return{
            
            restrict:"AE",
            scope:true,
            bindToController: true,
            controllerAs: 'vm',
			templateUrl: capfData.plugin_url + 'app/views/posts-portrait.html',
            link:function(scope, elem, attrs){

				scope.actionSettings.initPostTheme = true;

				scope.directiveInfo.ready = function(){


				};					
					
				scope.directiveInfo.afterPostsLoadCallback = function(){
					
				};

			}
		};
	}]);
angular.module(angAppName)
    .directive('sidebarLiveCustomizer', ['customFilterService', function(customFilterService){
        
        return{
            
            restrict:"AE",
            require: "?ngModel",
            scope:true,
            bindToController: true,
            controllerAs: 'vm',
			templateUrl: capfData.plugin_url + 'app/views/sidebar-live-customizer.html',
            link:function( scope, elem, attrs, ngModel ) {

				scope.$watch('pluginSettings.custom_templates', function(newVal, oldVal){
					
					if ( 'undefined' !== typeof scope.pluginSettings.filterSettings.theme.custom_template &&
						 'undefined' !== typeof scope.pluginSettings.custom_templates && 
						 'custom-theme' == scope.pluginSettings.filterSettings.theme.display ) 
					{
						for ( var i=0; i < scope.pluginSettings.custom_templates.length; i++ ) {
							if ( scope.pluginSettings.filterSettings.theme.custom_template.slug == scope.pluginSettings.custom_templates[i].slug ) {
								scope.pluginSettings.custom_templates[i].checked = true;
							} else {
								scope.pluginSettings.custom_templates[i].checked = false;
							}
						}
					}
				});
				

				var liveCustomizer = new lscfSidebarCustomizator(),
					filterSettings,
					wrapperGeneralClassNames;

				setTimeout( function(){
					
					liveCustomizer.init();

					liveCustomizer.initColorpicker(function(data){
						liveCustomizer.generateDynamicCssColor( data );

						scope.$apply(function(){
							scope.pluginSettings.filterSettings['main-color'] = data.hex;
							scope.pluginSettings.filterSettings['main-color-rgb'] = data.rgb;
							
							filterSettings = angular.toJson( scope.pluginSettings );
							liveCustomizer.saveSettings( filterSettings );
							
						});
					});

					liveCustomizer.saveExtraOptions( function(data){

						scope.$apply(function(){


							switch ( data.type ) {

								case 'taxonomies-listing':

										scope.pluginSettings.filterSettings.theme.posts_display_from = data.data;

									break;

								case 'settings-page':

										scope.pluginSettings.generalSettings.run_shortcodes = liveCustomizer.templateData.settings.run_shortcodes.value;
										scope.pluginSettings.generalSettings.disable_empty_option_on_filtering = liveCustomizer.templateData.settings.disable_empty_option_on_filtering.value;
										scope.pluginSettings.generalSettings.order_by = liveCustomizer.templateData.settings.order_by;

									break;
							}

							
							filterSettings = angular.toJson( scope.pluginSettings);

							liveCustomizer.saveSettings( filterSettings );

						});
					});

					liveCustomizer.onFormAction(function(data){

						switch ( data.dataType ) {

							case 'order-fields':
									scope.$apply(function(){
										scope.actionSettings.initFieldsDraggable = data.fieldValue;
									});

								break;
							
							case 'sidebar-position':								

								scope.$apply(function(){

									scope.pluginSettings.filterSettings.theme.sidebar.position = data.fieldValue;
									scope.actionSettings.initSidebar = true;
									
									wrapperGeneralClassNames = scope.makeWrapperClassName();

									scope.pluginSettings.className.sidebar = wrapperGeneralClassNames.sidebar;
									scope.pluginSettings.className.posts_theme = wrapperGeneralClassNames.posts_theme;

								});
								
								
								break;

							case 'theme-style':
								
								scope.$apply(function(){

									scope.pluginSettings.filterSettings.theme.display = data.fieldValue;

									if ( 'custom-theme' == data.fieldValue ) {
										
										var activeCustomThemeIndex = parseInt( data.custom_theme_active_index );

										if ( 'undefined' === typeof scope.pluginSettings.filterSettings.theme.custom_template ) {
											scope.pluginSettings.filterSettings.theme.custom_template = {};
										}

										scope.pluginSettings.filterSettings.theme.custom_template.url = scope.pluginSettings.custom_templates[ activeCustomThemeIndex ].url;
										scope.pluginSettings.filterSettings.theme.custom_template.name = scope.pluginSettings.custom_templates[ activeCustomThemeIndex ].name;
										scope.pluginSettings.filterSettings.theme.custom_template.slug = scope.pluginSettings.custom_templates[ activeCustomThemeIndex ].slug;

									}

								});

								break;

							case 'columns-number':
									
									var columnsNumber = parseInt( data.fieldValue );
									
									scope.$apply(function(){
										scope.pluginSettings.filterSettings.theme.columns = columnsNumber;
										scope.pluginSettings.className.sidebar = ( columnsNumber > 3 ? 'col-sm-2 col-md-2 col-lg-2' : 'col-sm-3 col-md-3 col-lg-3' );
										scope.pluginSettings.className.posts_theme = ( columnsNumber > 3 ? 'col-sm-10 col-md-10 col-lg-10' : 'col-sm-9 col-md-9 col-lg-9' );
									});
									
								
								break;

							case 'view-changer':
								
								switch( data.fieldValue ) {
									
									case 'full':
										
										scope.$apply(function(){
											scope.pluginSettings.filterSettings.theme.viewchanger.grid = 1;
											scope.pluginSettings.filterSettings.theme.viewchanger.list = 1;
										});

										jQuery( '.viewMode' ).fadeIn();
										jQuery( '.lscf-posts-block' ).addClass('block-view');
										jQuery( '.viewMode > div' ).removeClass('active');
										jQuery( '.viewMode #blockView' ).addClass('active');

										break;

									case 'list':
										
										scope.$apply(function(){
											scope.pluginSettings.filterSettings.theme.viewchanger.grid = 0;
											scope.pluginSettings.filterSettings.theme.viewchanger.list = 1;
										});

										jQuery( '.viewMode' ).hide();
										jQuery( '.lscf-posts-block' ).removeClass('block-view');

										break;

									case 'grid':

										scope.$apply(function(){
											scope.pluginSettings.filterSettings.theme.viewchanger.grid = 1;
											scope.pluginSettings.filterSettings.theme.viewchanger.list = 0;
										});

										jQuery( '.viewMode' ).hide();
										jQuery( '.lscf-posts-block' ).addClass('block-view');

										break;
								}

								break;

							case 'link-type':

								scope.$apply(function(){
									scope.pluginSettings.filterSettings.theme.link_type = data.fieldValue;
									scope.actionSettings.initPostTheme = true;
								});

								break;

							case 'posts-per-page':

								scope.$apply(function(){
									scope.actionSettings.postsPerPage = data.fieldValue;
									scope.pluginSettings.filterSettings['posts-per-page'] = data.fieldValue;
								});

								break;

						}

						filterSettings = angular.toJson( scope.pluginSettings );

						liveCustomizer.saveSettings( filterSettings );

					});


				}, 800 );

            }
            
        };
        
    }]);


function lscfSidebarCustomizator(){
	
	var $j = jQuery,
		self = this;
	
	this.ajaxRequest = new lscfGeneralAjaxRequests();

	this.templateData = {
		"active_terms":[],
		"settings":{
			"run_shortcodes":{
				"key":"run_shortcodes",
				"value":( 'undefined' !== typeof capfData.options.run_shortcodes ? capfData.options.run_shortcodes : 0 )
			},
			"disable_empty_option_on_filtering":{
				"key":"disable_empty_option_on_filtering",
				"value":( 'undefined' !== typeof capfData.options.disable_empty_option_on_filtering ? capfData.options.disable_empty_option_on_filtering : 0 )
			},
			
			"orderable_list":[
				{
					"id":"post_title",
					"name":( 'undefined' !== typeof capfData.options.writing.title ? capfData.options.writing.title : 'Title' )
				},
				{
					"id":"post_date",
					"name":( 'undefined' !== typeof capfData.options.writing.date ? capfData.options.writing.date : 'Date' )
				}
			],

			"order_by":{
				"items":[]
			}
		},

		"post_taxonomies":{
			"items":[],
			"set_tax_term_as_active":function(){
				return function( term_id, render ) {
					if ( self.templateData.active_terms.length > 0 && -1 !== self.templateData.active_terms.indexOf( render( term_id ) ) ) {
						return 'checked="checked"';
					}
				};
			},
		},

		"post_custom_fields":{
			"items":[]
		},
		

		"set_settings_option_as_active":function(){
			return function( key, render ) {

				if ( self.templateData.settings[ render( key ) ].value == 1 ) {
					return 'checked="checked"';
				}

			};
		},
		"set_order_fields_as_active":function(){
			return function ( key, render ) {

				for ( var ls = 0; ls < self.templateData.settings.order_by.items.length; ls++  ) {

					if ( self.templateData.settings.order_by.items[ ls ].id == render( key ) ) {

						return 'checked="checked"';
					}
				}
			};
		}
	};

	this.templates = {};

	this.templates.loadTaxonomies = function( templateData, callback ) {

		var url = capfData.plugin_url + 'assets/js/templates/live-customizer/tax-template.html';

		$j.get( url, function( template ) {

			var renderedTemplate = Mustache.render( $j( template ).filter('#template-tax').html(), templateData );

			callback(renderedTemplate);

		});

	};

	this.templates.loadTemplate = function( templateData, templateUrl, callback ) {
		
		$j.get( templateUrl, function( template ) {

			var renderedTemplate = Mustache.render( $j( template ).filter('#template-tax').html(), templateData );

			callback( renderedTemplate );

		});
	};

	this.serializeExtraOptions = function(){
		
		var containerType = $j('.lscf-extrasidebar-template').attr('data-fields-type'),
			data = {
				"type" : containerType,
				"data" :{}
			};

		switch ( containerType ) {
			
			case 'taxonomies-listing':

				data.data.post_taxonomies = {};

				var slug, value, name;

				self.templateData.active_terms = [];

				$j('.lscf-taxonomies-block').each(function(){

					$j(this).find('.px-checkbox:checked').each(function(){
						
						slug = $j(this).attr('data-taxonomy');
					
						if ( 'undefined' === typeof data.data.post_taxonomies[slug] ) {
							data.data.post_taxonomies[slug] = {};
							data.data.post_taxonomies[slug].ID = slug;
							data.data.post_taxonomies[slug].group_type= 'taxonomies';
							data.data.post_taxonomies[slug].tax = {
								"terms":[]
							};

						}

						value = parseInt( $j(this).val().split("!#")[0] );
						name = $j(this).val().split("!#")[1];

						data.data.post_taxonomies[slug].tax.terms.push({
							"data":{
								"name":name,
								"value":value
							}
						});

						self.templateData.active_terms.push(value+'-'+slug);

					});
					

				});

				data.data.post_taxonomies.active_terms = self.templateData.active_terms;

				break;

			case 'settings-page':

				data.data.settings = {};
				self.templateData.settings.order_by.items = [];
				$j('.lscf-fronteditor-settings input[type="checkbox"]').each(function(){
					
					var key = $j(this).attr('data-key');

					if ( ! $j(this).hasClass( 'has-multple-values' ) ) {

						self.templateData.settings[ key ].value = ( $j(this).prop("checked") ? 1 : 0 );

						data.data.settings[ key ] = self.templateData.settings[ key ];

					} else {
						
						if ( $j(this).prop( "checked" ) ) {
							
							self.templateData.settings[ key ].items.push({
								"id": $j(this).val(),
								"name":$j(this).attr('data-name')
							});
						}

						data.data.settings[ key ] = self.templateData.settings[ key ];
					}

				});

				break;
		}
		
		return data;

	};


	this.init = function(){
	
		if ( 'undefined' !== typeof capfData.options.order_by && 'undefined' !== typeof capfData.options.order_by.items ) {
			
			self.templateData.settings.order_by.items = capfData.options.order_by.items;

		}

		self.ajaxRequest.getCustomFieldsByPostType( capfData.postType, 'all', function(data){
			if (typeof data.success !== 'undefined' && data.success == 1) {
				
				var customFields = data.data.data.fields;

				customFields.forEach(function(customField) {
					var custom_field = {
						"id":customField.field_form_id,
						"name":customField.name
					};

					self.templateData.post_custom_fields.items.push( custom_field );
					self.templateData.settings.orderable_list.push( custom_field );
				});

			}		
		});


		if ( 'undefined' !== typeof capfData.settings.theme.posts_display_from && 
			'undefined' !== typeof capfData.settings.theme.posts_display_from.post_taxonomies &&
			'undefined' !== typeof capfData.settings.theme.posts_display_from.post_taxonomies.active_terms ) 
		{

			self.templateData.active_terms = capfData.settings.theme.posts_display_from.post_taxonomies.active_terms;
		}

		self.get_post_taxonomies_and_terms(
			function(data){
				self.templateData.post_taxonomies.items = data;
		});


		$j('#lscf-expand-sidebar-extra-options, .lscf-open-extra-sidebar').click(function(){
			
			$j('.lscf-close-customizer').addClass('lscf-hide');
			$j('.lscf-sidebar-extra-container').addClass('active');	

			var container_type = $j(this).closest('.lscf-sidebar-option').attr('data-type');

			self.loadExtraSidebarContent( container_type );

		});

	
		$j('.lscf-open-customizer').click(function(){
			$j(this).addClass('deactivate-animations');
			$j('.lscf-sidebar-live-customizer').addClass('active');
			$j('#lscf-posts-wrapper').addClass('translate');
		});
		$j('.lscf-close-customizer').click(function(){
			$j('.lscf-sidebar-live-customizer').removeClass('active');

			$j('#lscf-posts-wrapper').removeClass('translate');
		});


		self.initCustomDropdown();
		

		var activeTheme = $j('.lscf-theme-list').find('input[type="radio"]:checked').val();
		self.initThemeOptions( activeTheme );

	};

	this.loadExtraSidebarContent = function( content_type ){


		switch ( content_type ) {

			case 'show-from-categories':
				
				self.templates.loadTaxonomies( self.templateData.post_taxonomies, function(data){

					$j('.lscf-sidebar-extra-container-wrapper').html( data );
					$j('.lscf-sidebar-extra-container-wrapper').customScrollbar();

				});

				break;

			case 'filter-settings':

				var url = capfData.plugin_url + 'assets/js/templates/live-customizer/settings-template.html';

				self.templates.loadTemplate( self.templateData, url, function( data ){
					
					$j('.lscf-sidebar-extra-container-wrapper').html( data );
					$j('.lscf-sidebar-extra-container-wrapper').customScrollbar();

				});

				break;

		}	

	};

	this.saveExtraOptions = function( callback ){

		$j('#save-and-close-extra-options').click(function() {

			$j('.lscf-close-customizer').removeClass('lscf-hide');
			$j('.lscf-sidebar-extra-container').removeClass('active');	
			
			var formData = self.serializeExtraOptions();

			callback( formData );

		});	

	};

	this.initColorpicker = function( colorpickerCallback ){
		
		var colorPickerToggled = false;

		$j('.lscf-colorpicker').colorPicker({
			renderCallback:function(elem, toggle){
				if ( true === toggle ) {
					colorPickerToggled = false;
				}
				if ( false === toggle && false === colorPickerToggled ) {
					
					colorPickerToggled = true;
					var rgbColor = elem[0].style.backgroundColor.replace(/rgb\(|\)/g, ''),
					color = elem[0].value;

					colorpickerCallback({
						"hex":color,
						"rgb":rgbColor
					});

				}
				
			}
		});
	};

	this.generateDynamicCssColor = function( color ){
		
		$j.ajax({
			type:"POST",
			url:capfData.ajax_url,
			data:{
				action:"px-plugin-ajax",
				section:"generate-theme-color-style",
				color:color
			},
			success:function(data){
				var dynamicStyle = document.getElementById("px_base-inline-css");
				dynamicStyle.innerHTML = data;
			},
			dataType:"html"
		});
	};

	this.get_post_taxonomies_and_terms = function( callback ) {

		$j.ajax({
			type:"POST",
			url:capfData.ajax_url,
			data:{
				action:"lscf-administrator-ajax",
				section:"get_taxonomies_and_terms",
				post_type:capfData.postType
			},
			success:function(data){
				callback( data );
			},
			dataType:"json"
		});

	};

	this.saveSettings = function( settings ) {

		$j.ajax({
			type:"POST",
			url:capfData.ajax_url,
			data:{
				action:"lscf-administrator-ajax",
				section:"save-filter-settings",
				filter_id:capfData.ID,
				settings:settings
			},
			success:function(data){

			},
			dataType:"html"
		});
	};

	this.onFormAction = function( callback ){

		var fieldType,
			dataType,
			fieldValue,
			data = {},
			customThemeIndex;


		$j('.lscf-sidebar-option').each(function(){
			
			fieldType = $j(this).attr('data-field-type');

			switch ( fieldType ) {

				case 'dropdown':

					$j(this).find('.options li ').each(function(){
						
						$j(this).click(function(){

							if ( $j( this ).hasClass('lscf-inactive') ) {
								return false;
							}

							fieldValue = $j(this).attr('rel');
							dataType = $j(this).closest('.lscf-sidebar-option').attr('data-type');

							data = {
								"fieldType":fieldType,
								"dataType":dataType,
								"fieldValue":fieldValue
							};

							callback( data );

						});

					});

					break;

				case 'checkbox':
					
					$j(this).find('#lscf-fields-ordering').click(function(){
						
						var parent = $j(this).closest('.lscf-check-btn');
						
						fieldValue = ( 'undefined' !== typeof parent.find('input[type="checkbox"]:checked').val() ? parseInt( parent.find('input[type="checkbox"]:checked').val() ) : 0 );
						dataType = $j(this).closest('.lscf-sidebar-option').attr('data-type');

						data = {
							"fieldType":fieldType,
							"dataType":dataType,
							"fieldValue":!fieldValue
						};

						callback( data );

					});

					break;


				case 'radiobutton':

					$j(this).on('click', '.lscf-live-customizer-radiobutton-label', function(){

							fieldValue = $j(this).attr('data-value');

							self.initThemeOptions( fieldValue );

							if ( 'custom-theme' == fieldValue  ) {
								customThemeIndex = $j(this).attr('data-index');
							}

							dataType = 'theme-style';

							data = {
								"fieldType":fieldType,
								"dataType":dataType,
								"fieldValue":fieldValue,
								"custom_theme_active_index":customThemeIndex
							};

							callback( data );
					});

					break;

				case 'number':
					
					$j(this).find('input[type="number"]').blur(function(){

						fieldValue = ('NaN' !== parseInt( $j(this).val() ) ? parseInt( $j(this).val() ) : 15 );
						dataType = $j(this).closest('.lscf-sidebar-option').attr('data-type');
						
						data = {
							"fieldType":fieldType,
							"dataType":dataType,
							"fieldValue":fieldValue
						};

						callback( data );
					});
					
					break;

			}

		});

	};

	this.initThemeOptions = function( theme ) {
			
			$j('.lscf-optional-option').hide();

			switch ( theme ) {
				
				case 'default':

					$j('.lscf-optional-grid').fadeIn();
					$j('.lscf-optional-viewchanger').fadeIn();

					break;
			}

	};

	this.initCustomDropdown = function(){
                
		$j('.lscf-custom-select-dropwdown').each(function(){
			var dataClass=$j(this).attr('data-class');
			var $this=$j(this),
				numberOfOptions=$j(this).children('option').length;

			$this.addClass('s-hidden');
			$this.wrap('<div class="select '+dataClass+'"></div>');
			$this.after('<div class="styledSelect"></div>');
			
			var $styledSelect=$this.next('div.styledSelect'),
				optionValue,
				selectedOptionIndex;

			$this.find('option').each(function(index){
				
				if ( $j(this).is(':selected') ) {
					selectedOptionIndex = index;
				}
			});

			optionValue = ( $this.find('option:selected') ?  $this.find('option:selected').text() : $this.children('option').eq(0).text() );

			$styledSelect.text( optionValue );

			var $list=$j('<ul />',{'class':'options'}).insertAfter($styledSelect);

			for ( var i=0; i<numberOfOptions; i++ ) {
				
				var listClassName = ( selectedOptionIndex == i ? 'pxselect-hidden-list' : '' );
				listClassName += $this.children('option').eq(i).attr('data-status');

				$j('<li />',{
					text:$this.children('option').eq(i).text(),
					rel:$this.children('option').eq(i).val(),
					class:listClassName
				}).appendTo( $list );
			}

			var $listItems = $list.children('li');

			$styledSelect.click( function(e){
				
				e.stopPropagation();
				
				$j('div.styledSelect.active').each(function(){
					$j(this).removeClass('active').next('ul.options').hide();
				});

				$j(this).toggleClass('active').next('ul.options').toggle();

			});

			$listItems.click(function(e){

				e.stopPropagation();

				if ( $j( this ).hasClass( 'lscf-inactive' ) ) {
					return false;
				}

				$listItems.removeClass('pxselect-hidden-list');

				$j(this).addClass('pxselect-hidden-list');
				

				
				$styledSelect.text($j(this).text()).removeClass('active');
				
				$this.val($j(this).attr('rel'));
				
				$list.hide();

			});

			$j( document ).click( function(){
				
				$styledSelect.removeClass('active');
				$list.hide();

			});
		});

	};

}

function lscfGeneralAjaxRequests() {

	var self = this,
		$j = jQuery,
		action = "px-plugin-ajax",
		adminAction = "lscf-administrator-ajax";
	

	this.getCustomFieldsByPostType = function( postType, fieldType, callback ) {

		$j.ajax({
            type:"POST",
            url:capfData.ajax_url,
            data:{
                action:action,
                section:"getPostType_customFields",
                fieldType:fieldType,
                post_type:postType
            },
            success: function (data) {
                callback(data);
            },
            dataType:"json"
        });

	};

}
angular.module(angAppName)
    .directive('sortBy', ['customFilterService', function(customFilterService){
        
        return{
            
            restrict:"AE",
            require: "?ngModel",
            scope:true,
            bindToController: true,
            controllerAs: 'vm',
            link:function( scope, elem, attrs, ngModel ) {
                
				var field_exists = false,
					field,
					orderByID,
					dropdownTemplate,
					$j = jQuery;

				scope.$watch( 'pluginSettings.generalSettings.order_by.items', function( newVal, oldVal ){
					
						if ( 'undefined' !== typeof scope.pluginSettings.generalSettings.order_by && 
							'undefined' !== typeof scope.pluginSettings.generalSettings.order_by.items &&
							scope.pluginSettings.generalSettings.order_by.items.length > 0 ) {

								dropdownTemplate = '<select data-class="lscf-order-by-dropdown lscf-sorting-by" class="lscf-sorting-custom-dropdown">';
								dropdownTemplate += '<option value="0">' + scope.pluginSettings.generalSettings.writing.sort_by + '</option>';
								scope.pluginSettings.generalSettings.order_by.items.forEach( function( item ){
									dropdownTemplate += '<option value="'+item.id+'">' + item.name + '</option>';
								});

								dropdownTemplate += '</select>';

								dropdownTemplate += '<div class="lscf-sorting-opt">';
								dropdownTemplate += '<div class="lscf-sort-up"><span class="glyphicon glyphicon-triangle-top"></span></div>';
								dropdownTemplate += '<div class="lscf-sort-down"><span class="glyphicon glyphicon-triangle-bottom"></span></div>';
								dropdownTemplate += '</div>';

								// dropdownTemplate += '<select data-class="lscf-order-by-dropdown lscf-sorting-order" class="lscf-sorting-custom-dropdown">';
								// dropdownTemplate += '<option value="ASC">' + scope.pluginSettings.generalSettings.writing.sort_asc + '</option>';
								// dropdownTemplate += '<option value="DESC">' + scope.pluginSettings.generalSettings.writing.sort_desc + '</option>';
								// dropdownTemplate += '</select>';

								elem[0].innerHTML = dropdownTemplate;
								lscfSortingCustomDropddown();

								if ( 'undefined' !== typeof capfData.settings.theme.posts_display_from && 
								'undefined' !== capfData.settings.theme.posts_display_from.post_taxonomies.active_terms && 
									capfData.settings.theme.posts_display_from.post_taxonomies.active_terms.length > 0 ) 
								{

									scope.actionSettings.customFields.push({
										"ID":"default_filter",
										"type":"default_filter",
										"default_filter":{
											"post_taxonomies":capfData.settings.theme.posts_display_from.post_taxonomies
										} 
									});

								}

								$j('.lscf-sort-up').click(function(){
									
									$j(this).addClass('active');
									$j('.lscf-sort-down').removeClass('active');

									var order = "DESC";	

									for ( var c = 0; c < scope.actionSettings.customFields.length; c++ ) {
						
										field = scope.actionSettings.customFields[ c ];
										
										if ( 'order-by' == field.ID ) {

											scope.actionSettings.customFields[ c ].order = order;
											
											field_exists = true;
											break;
										}
									}
									
									if ( false === field_exists ) {

										query = {
											"ID":"order-by",
											"filter_as":null,
											"type":"order-posts",
											"order":order,
											"value":"post_date"
										};

										scope.actionSettings.customFields.push( query );
									}

									scope.loadMoreBtn.postsLoading = true;

									customFilterService.getPosts( scope.postType, scope.actionSettings.postsPerPage, 1, scope.actionSettings.customFields )
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

								});

								$j('.lscf-sort-down').click(function(){
									
									$j(this).addClass('active');
									$j('.lscf-sort-up').removeClass('active');
									
									var order = 'ASC';	

									for ( var c = 0; c < scope.actionSettings.customFields.length; c++ ) {
						
										field = scope.actionSettings.customFields[ c ];
										
										if ( 'order-by' == field.ID ) {

											scope.actionSettings.customFields[ c ].order = order;
											
											field_exists = true;
											break;

										}
									}
									
									if ( false === field_exists ) {

										query = {
											"ID":"order-by",
											"filter_as":null,
											"type":"order-posts",
											"order":order,
											"value":"post_date"
										};

										scope.actionSettings.customFields.push( query );
									}

									scope.loadMoreBtn.postsLoading = true;

									customFilterService.getPosts( scope.postType, scope.actionSettings.postsPerPage, 1, scope.actionSettings.customFields )
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
								});
 
								$j('.lscf-sorting-by .lscf-dropdown-option').each(function() {
									
									$j(this).click(function(){
										
										orderByID = $j(this).attr('rel');	

										if ( '0' == orderByID ) {

											$j('.lscf-sorting-by').removeClass('active');

											$j('.lscf-sort-down').removeClass('active');											
											$j('.lscf-sort-up').removeClass('active');											

										} else {
											$j('.lscf-sorting-by').addClass('active');
										}
										for ( var c = 0; c < scope.actionSettings.customFields.length; c++ ) {
							
											field = scope.actionSettings.customFields[ c ];
											
											if ( 'order-by' == field.ID ) {

												scope.actionSettings.customFields[ c ].value = orderByID;
												
												field_exists = true;

												break;

											}
										}
										
										if ( false === field_exists ) {

											query = {
												"ID":"order-by",
												"filter_as":null,
												"type":"order-posts",
												"order":"ASC",
												"value":orderByID
											};

											scope.actionSettings.customFields.push( query );
										}

										scope.loadMoreBtn.postsLoading = true;

										customFilterService.getPosts( scope.postType, scope.actionSettings.postsPerPage, 1, scope.actionSettings.customFields )
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

									});
								});
							}
					});
         	   }    
        };
    }]);

function lscfSortingCustomDropddown() {
	
	var $j = jQuery;

	$j('.lscf-sorting-custom-dropdown').each(function(){
		var dataClass = $j(this).attr('data-class');
		var $this=$j(this),
			numberOfOptions=$j(this).children('option').length;
		$this.addClass('s-hidden');
		$this.wrap('<div class="select '+dataClass+'"></div>');
		$this.after('<div class="styledSelect"></div>');
		var $styledSelect=$this.next('div.styledSelect');
		$styledSelect.text($this.children('option').eq(0).text());
		var $list=$j('<div />',{'class':'options'}).insertAfter($styledSelect);

		for ( var i=0; i < numberOfOptions; i++ ) {
			
			var listClassName = ( 0 === i ? 'lscf-dropdown-option pxselect-hidden-list' : 'lscf-dropdown-option' );
		
			$j('<div />',{
				text:$this.children('option').eq(i).text(),
				rel:$this.children('option').eq(i).val(),
				'class':listClassName
			}).appendTo( $list );
		}
		var $listItems = $list.children('.lscf-dropdown-option');
		$styledSelect.click(function(e){
			e.stopPropagation();
			$j('div.styledSelect.active').each(function(){

				$j(this).removeClass('active').next('div.options').hide();
			});

			$j(this).toggleClass('active').next('div.options').toggle();
			$j(this).toggleClass('active').next('div.options').customScrollbar();
		});

		$listItems.click(function(e){
			$listItems.removeClass('pxselect-hidden-list');
			$j(this).addClass('pxselect-hidden-list');
			e.stopPropagation();
			$styledSelect.text($j(this).text()).removeClass('active');
			$this.val($j(this).attr('rel'));
			$list.hide();
		});
		
		$j(document).click(function(){
			$styledSelect.removeClass('active');
			$list.hide();
		});

	});
}
angular.module(angAppName)
    .directive('viewmodeBasicGrid', ['customFilterService', function( customFilterService ){
        
        return{
            
            restrict:"AE",
            scope:true,
            bindToController: true,
            controllerAs: 'vm',
			templateUrl: capfData.plugin_url + 'app/views/posts-basic-grid.html',
            link:function(scope, elem, attrs){
				
				scope.actionSettings.initPostTheme = true;

				scope.directiveInfo.ready = function(){		

				};					
					
				scope.directiveInfo.afterPostsLoadCallback = function(){
					
				};

			}
		};
	}]);
angular.module(angAppName)
    .directive('viewmodeMasonryGrid', ['customFilterService', function( customFilterService ){
        
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
	}]);
angular.module(angAppName)
    .directive('viewmodeWoocommerce', ['customFilterService', function( customFilterService ){
        
        return{
            
            restrict:"AE",
            scope:true,
            bindToController: true,
            controllerAs: 'vm',
			templateUrl: capfData.plugin_url + 'app/views/posts-woocommerce.html',
            link:function(scope, elem, attrs){
				
				scope.actionSettings.initPostTheme = true;

				scope.directiveInfo.ready = function(){		
					
				};					
					
				scope.directiveInfo.afterPostsLoadCallback = function(){
					
				};

			}
		};
	}]);