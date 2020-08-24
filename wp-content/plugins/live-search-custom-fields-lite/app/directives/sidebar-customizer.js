angular.module(angAppName)
    .directive('sidebarLiveCustomizer', function(customFilterService){
        
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
        
    });


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