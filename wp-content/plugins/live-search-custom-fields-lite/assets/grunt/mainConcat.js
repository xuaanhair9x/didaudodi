
var adjustPostContainerHeight = new posts_block_container();
adjustPostContainerHeight();


function posts_block_container(){
    
    var $j = jQuery,
        called = false,
        self = this;

    this.check_container_block_width = function() {
        
		if ( $j('.lscf-grid-view').length > 0 ) {
			called  = true;

			var containerWidth = $j('.lscf-grid-view').width();

			if ( containerWidth < 800 )
				$j('.lscf-grid-view').addClass("small-view");
			else 
				$j('.lscf-grid-view').removeClass("small-view");

			
			if (containerWidth > 840)
				$j('.lscf-grid-view').addClass("large-view");
			else
				$j('.lscf-grid-view').removeClass("large-view");

		}            


    };    

    return function() {
        
        self.check_container_block_width();

        if (!called) {
            setTimeout(function() {
                self.check_container_block_width();    
            }, 400);
        }

    }

}


function pxFilterFieldsAction(){
    
    var $j = jQuery,
        self = this,
        scriptInterval;

	this.reset_fields = function(){
		 
		 $j(".pxSelectField").each(function(){
			 
			 if ( $j( this ).hasClass('active-val') ) {
				 $j(this).find('.options .lscf-dropdown-option[rel="0"]').trigger("click");
			 }
		 });

		 $j(".pxDateField").each(function(){
			 $j(this).find('.initCalendar').val('');
		 });

		 $j(".pxCheckField").each(function(){
			 $j( this ).find('.px_checkboxesList .px_checkbox').each(function(){
				 $j(this).removeClass('active');
			 })
		 });
		 $j('.pxRadioField').each(function(){
			 $j( this ).find('.px_checkbox-li input[type="radio"]').each(function(){
				 $j(this).removeAttr('checked');
			 })
		 });

		$j('.subcategs-tax').hide();

	};

	this.mobileExpandFilter = function(){

		

		$j('.px-filter-label-mobile').on( 'click', function(){

			var animationHeight = $j('.px-field-wrapper-container').height()+140;
			$j('.px-capf-wrapper').css({"min-height":(animationHeight+200)+"px"});

			if ( $j('.px-fiels-wrapper').hasClass('active') ){
				$j('.px-fiels-wrapper').removeClass('ready');
				$j('.px-fiels-wrapper').animate({
					height:"41px"
				}, 400, function(){
					$j(this).removeClass('active');
				});
			} else {
				$j('.px-fiels-wrapper').addClass('active');

				$j('.px-fiels-wrapper').animate({
					height:animationHeight
				}, 300, function(){
					$j(this).addClass('ready');
				});
			}
		});

	};

	this.initSeeMore = function(){
		
		$j( '.lscf-see-more' ).on( 'click', function () {
	
			var parent = $j(this).closest('.px_capf-field');
			
			if ( parent.hasClass('active') ) {
				$j(this).text( capfData.options.writing.see_more );
				parent.removeClass('active');

			} else {
				$j(this).text( capfData.options.writing.see_less );
				parent.addClass('active');

			}

		});

	}

	this.reset_subcategs = function(parent){
		
		parent.find(".subcategs-tax .pxSelectField").each(function(){
			$j(this).removeClass('active-val');
			$j(this).find('.styledSelect').text('Select');
		});

		parent.find(".subcategs-tax .pxCheckField").each(function(){
			$j( this ).find('.px_checkboxesList .px_checkbox').each(function(){
				 $j(this).removeClass('active');
			 })
		});
		
		parent.find(".subcategs-tax .pxRadioField").each(function(){
			$j( this ).find('.px_checkbox-li input[type="radio"]').each(function(){
				 $j(this).removeAttr('checked');
			})
		});


	}

    this.construct = function(callback){
                
        scriptInterval = setInterval( function(){
            self.init(callback);
        }, 500 );
        
        setTimeout( function(){
            clearInterval(scriptInterval);
        }, 1100 );
    }
    
    this.init = function(callback){
        
        self.pxSelect(callback);
        self.pxDate(callback);
        self.pxDateInterval(callback);
        self.pxCheckbox(callback);
        self.pxRadiobox(callback);
		self.mobileExpandFilter();

		setTimeout(function(){
			self.initSeeMore();
		}, 2000);
		

    };	
        
    this.pxSelect = function(callback){
        
        $j(".pxSelectField").ready(function(){
            
            clearInterval( scriptInterval );
            
            $j(".pxSelectField").each(function(){
               
               var ID = $j(this).data("id");
               var _parent = $j(this),
			   		group_type = $j(this).closest( '.lscf-group-type' ).attr( 'data-group-type' ),
					variation_id = ( $j(this).closest( '.lscf-variation-field' ).length > 0 ? $j(this).closest('.lscf-variation-field').attr('data-variation-id') : null );
			   
			   var filterTypeAttr = $j(this).attr('data-filter-as');
			   
			   if ( typeof filterTypeAttr !== typeof undefined && false !== filterTypeAttr  ) {
				   var filterAs = filterTypeAttr;
			   } else {
				   var filterAs = "select";
			   }

			   var dropdownField = $j(this);

               $j(this).find(".options .lscf-dropdown-option").click(function(){
               
                    var value = $j(this).attr("rel"),
                        data = [];

					if ( 0 == value ) {
						
						if( ! dropdownField.hasClass('px_capf-subfield') ) {
							self.reset_subcategs( _parent.closest('.lscf-taxonomies-fields') );
						} else {

							var subcategIndex = parseInt( dropdownField.closest('.subcategs-tax').attr('data-index') );

							_parent.closest('.lscf-taxonomies-fields').find('.px_capf-subfield.pxSelectField').each(function(index){
								
                                if ( index > subcategIndex ) {
									
                                    $j(this).removeClass('active-val');
			                        $j(this).find('.styledSelect').text('Select');

                                    var reset_value = "0";

                                    if ( 'px_check_box' == filterAs || 'px_icon_check_box' == filterAs ) {
                                        
                                        var new_val = [];
                                            new_val[0] = "0";
                                        reset_value = new_val;

                                    }

                                    data.push({
                                        "ID":$j(this).data('id'),
                                        "value":reset_value,
                                        "type":"select",
                                        "filter_as":filterAs,
										"group_type":group_type,
										"variation_id":variation_id
                                    });
								};

							});

						}

						_parent.removeClass('active-val');

					} else {
						_parent.addClass('active-val');
					}

					if ( 'px_check_box' == filterAs || 'px_icon_check_box' == filterAs ) {
						
                        var new_val = [];
							new_val[0] = value;
						value = new_val;
					}

                    data.push({
                        "ID":ID,
                        "value":value,
                        "type":"select",
						"filter_as": filterAs,
						"group_type":group_type,
						"variation_id":variation_id
                    });

                    callback(data);
               });
                
            });
            
            
        });
        
    };
    
    this.pxDate = function(callback){
        
        $j(".pxDateField").ready(function(){
            
            clearInterval( scriptInterval );
            
            $j(".pxDateField").each(function(){
               
                var ID = $j(this).data("id");
                
                // remove Date When input is empty
                $j(this).find('input[type="text"]').blur(function(){
                    var inputVal = $j(this).val();
                    
                    if(inputVal ==='' && !$j(this).hasClass("empty")){
                        var data = {
                            "ID":ID,
                            "value":"",
                            "type":"date"
                        }
                        callback(data);
                        $j(this).addClass("empty");
                    }
                });
                
                $j(this).find('input[type="text"]').datepicker({
                    onSelect: function(date){
                        var data = {
                            "ID":ID,
                            "value":date,
                            "type":"date"
                        }
                        $j(this).removeClass("empty");
                        callback(data);
                    }
                });
                
            });
            
        })
        
    },
    
    this.pxDateInterval = function(callback){
        
        $j(".pxDateIntervalField").ready(function(){
            
            clearInterval( scriptInterval );
            
            $j(".pxDateIntervalField").each(function(){
               
                var ID = $j(this).data("id");
                
                var data = {
                    "type":"date-interval",
                    "ID":ID,
                    "fields":{
                        "from":"",
                        "to":""
                    }
                };
                    
                $j(this).find('input[type="text"]').each(function(index){
                    
                    $j(this).datepicker({
                        onSelect: function(date){
                            data.fields[$j(this).data("type")] = {
                                "value":date
                            };

                            callback(data);
                        }
                    });
                })
            });
            
        })
        
    }
    
    this.pxCheckbox = function(callback){
        
       $j(".pxCheckField").find("label.px_checkbox").ready(function(){
           
            clearInterval(scriptInterval);
            var values = new Array();
			 
            $j(".pxCheckField").each(function(c){

				var filterTypeAttr = $j(this).attr('data-filter-as'),
					group_type = $j(this).closest( '.lscf-group-type' ).attr( 'data-group-type' ),
					variation_id = ( $j(this).closest( '.lscf-variation-field' ).length > 0 ? $j(this).closest('.lscf-variation-field').attr('data-variation-id') : null );

				if ( typeof filterTypeAttr !== typeof undefined && false !== filterTypeAttr  ) {
					var filterAs = filterTypeAttr;
				} else {
					var filterAs = "px_check-box";
				}

                var checkboxType = $j(this).data('type');
                var ID = $j(".pxCheckField").eq(c).data("id");
                
                $j(".pxCheckField").eq(c).find("label.px_checkbox").each(function(index){
                    
                    values[c] = new Array();
                    
                    $j(this).click(function(e){
                        
                        e.preventDefault();
                        e.stopPropagation();
                        e.stopImmediatePropagation();
                        
                        $j(this).toggleClass("active");
                        
                        var value = $j(".pxCheckField").eq(c).find(".px_checkboxInput").eq(index).val(),
                            data = [];
                        
                        if( $j(this).hasClass("active") ){
                            
                            values[c].push(value);
                                
                        }        
                        else{
                            
                            var valueIndex = values[c].indexOf(value);
                            
                            if( valueIndex >-1 ){
                                
                                values[c].splice(valueIndex, 1);    
                                
                            }
                        }

                        data.push({
                            "ID":ID,
                            "value":values[c],
                            "type":checkboxType,
							"filter_as":filterAs,
							"group_type":group_type,
							"variation_id":variation_id
                        });

                        callback(data);
                        
                        return false;
                    })        
                });
                
            })
       });
           
    }
    
    this.pxRadiobox = function(callback){
        
        $j(".pxRadioField").ready(function(){
            
            clearInterval( scriptInterval );
            
            $j(".pxRadioField").each(function(){
               
                var ID = $j(this).data("id");
                var _this = $j(this),
					group_type = $j(this).closest( '.lscf-group-type' ).attr( 'data-group-type' ),
					variation_id = ( $j(this).closest( '.lscf-variation-field' ).length > 0 ? $j(this).closest('.lscf-variation-field').attr('data-variation-id') : null );
                
				var filterTypeAttr = $j(this).attr('data-filter-as');

				if ( typeof filterTypeAttr !== typeof undefined && false !== filterTypeAttr  ) {
				   var filterAs = filterTypeAttr;
			   	} else {
				   var filterAs = "radio";
			   	}


                $j(this).find('.pxRadioLabel').each(function(index){
                   
                   $j(this).click(function(){
                      
                       var value = _this.find('input[type=radio]').eq(index).val(),
                            data = [];
                      

					   if ( 0 == value ) {
						   
						   if ( _this.hasClass('px_tax-field') && !_this.hasClass('px_capf-subfield') ) {
							   
								self.reset_subcategs( _this.closest('.lscf-taxonomies-fields') );
								
								_this.closest('.lscf-taxonomies-fields').find('.pxRadioField').each(function(){
									
									data.push({
										"ID":$j(this).data('id'),
										"value":0,
										"type":"radio",
										"filter_as":filterAs,
										"group_type":group_type,
										"variation_id":variation_id
                                    });

								});

						   } else if( _this.hasClass('px_capf-subfield') ) {
							   
							   var subcategIndex = parseInt( _this.closest('.subcategs-tax').attr('data-index') );

								_this.closest('.lscf-taxonomies-fields').find('.px_capf-subfield.pxRadioField').each(function(index){

									if ( index > subcategIndex ) {
										
                                        data.push({
											"ID":$j(this).data('id'),
											"value":0,
											"type":"radio",
											"filter_as":filterAs,
											"group_type":group_type,
											"variation_id":variation_id
                                        });

									}

								})
					   		}
					   }

					   if ( 'px_check_box' == filterAs || 'px_icon_check_box' == filterAs ) {
						   var new_val = [];
						   	   new_val[0] = value;
							value = new_val;
					   }

                       data.push({
                           "ID":ID,
                           "value":value,
                           "type":"radio",
						   "filter_as":filterAs,
						   "group_type":group_type,
						   "variation_id":variation_id
                       });

                       callback(data);
                       
                   });                             
                    
                });
                        
            });
            
        })
        
    }
    
}


function px_customRange(){

    var $j = jQuery,
        self = this, 
        rangeInterval;
    
    this.construct = function(callback){
       
        
        rangeInterval = setInterval(function(){
            self.init(callback);
        }, 500); 
        
        setTimeout(function(){
            
            clearInterval(rangeInterval);
            
        }, 1100);
    };
    
    this.init = function( callback ){
       
       $j(".customRange").ready(function(){
           
           clearInterval( rangeInterval );

           $j(".customRange").each(function(index){
              
              var _this = $j(this);
              var rangeVal = 0;
              var ID = $j(".pxRangeField").eq(index).data("id");
			  var valueLabel = _this.find(".rangeVal").data('labelval');
			  var rangeValues = {
				  "min":0,
				  "max": parseInt( _this.data('maxval') )
			  };

              self.defaultPosition(_this);
              
              _this.find(".draggablePoint").draggable({
                  drag:function(event){
                      
                      var x = ( $j(this).position().left < 30 ? $j(this).position().left : $j(this).position().left + 15 );
                      _this.find(".range_draggable").css({
                          "width":parseInt(x) - _this.find('.startPoint').position().left + "px"
                      });

					  _this.find(".range_draggable").attr('data-width', parseInt(x) );
                      
					  rangeVal = self.calculateCurrentRangeValue(_this, x);
                      _this.attr('data-value', rangeVal );
					  _this.find(".rangeVal").text( valueLabel+rangeVal);
        			  _this.find('input[type="hidden"]').val(rangeVal);
                      
					  rangeValues.max = rangeVal;

                  },
                  axis:"x",
                  stop:function(){
                    var data = {
                        "ID":ID,
                        "value":rangeValues,
                        "type":"range"
                    }  
                    callback(data);
                  },
                  containment: _this
              });

			  _this.find(".startDraggablePoint").draggable({

					drag:function(){
						var x = $j(this).position().left,
							dataWidth = _this.find('.range_draggable').attr('data-width'),
							rangeTrackerWidth = ( dataWidth != '-1' ? dataWidth : _this.find('.range_draggable').width() ),
							rangeVal = 0;

						if ( '-1' == dataWidth ) {
							 _this.find(".range_draggable").attr('data-width', _this.find('.range_draggable').width() );
						}

						rangeVal = self.calculateCurrentRangeValue( _this, x );
						_this.attr('data-value', rangeVal );

						_this.find(".defaultVal").text( valueLabel+rangeVal);

						_this.find(".range_draggable").css({
						  "width":( rangeTrackerWidth - x ) + "px" ,
						  "left":x + "px"
                      	});

						rangeValues.min = rangeVal;

					},
					axis:"x",
					containment: _this,
					stop:function(){
						var data = {
							"ID":ID,
							"value":rangeValues,
							"type":"range"
						}  
                    	callback(data);
                  },
                  containment: _this
			  
		  		});
              
           });
               
       }); 
    };
    
    this.calculateCurrentRangeValue = function(rangeElement, position){
        var _this = rangeElement;
        var containerWidth = _this.width();
        var maxValue = parseInt( _this.data('maxval') );
        var startValue = parseInt( _this.data('minval') );

        var rangeValue = Math.round(position*(maxValue-startValue)/containerWidth);
        rangeValue = startValue + rangeValue;

        rangeValue = rangeValue>maxValue?maxValue:rangeValue;
        
        
        return rangeValue;
    };
    this.defaultPosition = function(_this){
        var percentage = _this.data('defaultpos'),
            x = 0;
        _this.find(".range_draggable").css({"width":percentage+"%"});
        
        x = parseInt(_this.find(".draggablePoint").position().left);
        self.calculateCurrentRangeValue(_this, x);
    }
    
}

function customSelectBox(){
    
    var $j = jQuery,
        self = this,
        scriptInterval;
    
    this.construct = function(){
        
        scriptInterval = setInterval(function(){
            self.init();
        }, 500);
        
        setTimeout(function(){
            clearInterval(scriptInterval);
        }, 1100);
    }
    
    this.init = function(){
        
        $j(".custom-select").ready(function(){
            
            clearInterval(scriptInterval);
                
            $j('.custom-select').each(function(){
                var dataClass=$j(this).attr('data-class');
                var $this=$j(this),
                    numberOfOptions=$j(this).children('option').length;
                $this.addClass('s-hidden');
                $this.wrap('<div class="select '+dataClass+'"></div>');
                $this.after('<div class="styledSelect"></div>');
                var $styledSelect=$this.next('div.styledSelect');
                $styledSelect.text($this.children('option').eq(0).text());
                var $list=$j('<div />',{'class':'options'}).insertAfter($styledSelect);

                for ( var i=0; i<numberOfOptions; i++ ) {
					
					var listClassName = ( 0 == i ? 'lscf-dropdown-option pxselect-hidden-list' : 'lscf-dropdown-option' );
				
					if ( 0 !== parseInt( $this.children('option').eq(i).val() ) ) {
						listClassName += " lscf-field-option";
					}


					listClassName += $this.children('option').eq(i).attr('data-status');

					$j('<div />',{
						text:$this.children('option').eq(i).text(),
						rel:$this.children('option').eq(i).val(),
						'data-index':$this.children('option').eq(i).attr('data-index'),
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
        })        
    }
    
}

function lscfPosts(){
    var $j = jQuery,
        self = this,
        scriptInterval;
    
    this.constructHover = function(){
     
        scriptInterval = setInterval(function(){
            self.blockPosts_hover();
        }, 500);
        
        setTimeout( function(){
            clearInterval(scriptInterval);
        }, 1100);
        
    }
    
    this.init = function(){
        self.viewMode();
        self.choseDisplayMode_ofListing();
    }
    this.viewMode = function(){
        
		$j(".viewMode #blockView").on("click", function(){
            $j(".viewMode div").removeClass("active");
            $j(this).addClass("active");
            $j("#lscf-posts-container-defaultTheme").addClass("block-view");
        });

        $j(".viewMode #listView").on("click", function(){
            $j(".viewMode div").removeClass("active");
            $j(this).addClass("active");
            $j("#lscf-posts-container-defaultTheme").removeClass("block-view");
        });
		
    };
    
    this.choseDisplayMode_ofListing = function(){
        var windowWidth = $j(window).width(),
            previousScreen=0;// possible values: 0=desktop; 1=mobile
        if(windowWidth<=768){
            $j(".viewMode #blockView").trigger("click");
        }
        $j(window).resize(function(){
            var windowWidth = $j(window).width(),
                currentScreen = (windowWidth>768?0:1);
            if(previousScreen!=currentScreen){
                previousScreen = currentScreen;
                if(currentScreen==1){
                    $j(".viewMode #blockView").trigger("click");
                }
            }
          
        });
        
    };
    
    this.blockPosts_hover = function(){
        $j(".post-list").ready(function(){
            
            clearInterval(scriptInterval);
            
            $j(".post-block, .post-list .post-featuredImage").each(function(){
                $j(this).hover(function(){
                        $j(this).find(".post-overlay").addClass("active");
                    },
                    function(){
                        $j(this).find(".post-overlay").removeClass("active");
                    }
                )
            })    
        
        });
        
    }
}


var pxDecodeEntities = (function() {
  
  var element = document.createElement('div');

  function decodeHTMLEntities (str) {
  
    if(str && typeof str === 'string') {
  
      str = str.replace(/<script[^>]*>([\S\s]*?)<\/script>/gmi, '');
      str = str.replace(/<\/?\w(?:[^"'>]|"[^"]*"|'[^']*')*>/gmi, '');
      element.innerHTML = str;
      str = element.textContent;
      element.textContent = '';
    }

    return str;
  }

  return decodeHTMLEntities;
})();

var lscfExtraFunctionalities = (function(){
	
	var self = this,
		$j = jQuery,
		bounceAnimation;

	this.init = function(){
		
		$j(window).load(function(){
			self.shakeSettingsButton();
		});
			
		bounceAnimation = setInterval(function(){
			self.shakeSettingsButton();
		}, 7000);

	};

	this.shakeSettingsButton = function(){

		if ( $j('.lscf-open-customizer').hasClass('deactivate-animations') ) {
			clearInterval( bounceAnimation );
			return; 
		}

		if ( $j('.lscf-sidebar-live-customizer').hasClass('active') ) {
			return;
		}

		$j('.lscf-open-customizer').addClass('shake');

		setTimeout( function(){
			$j('.lscf-open-customizer').removeClass('shake');
		}, 1000 )

	};

	return this.init();

})();

