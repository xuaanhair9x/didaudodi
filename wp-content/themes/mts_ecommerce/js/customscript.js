jQuery.fn.exists = function(callback) {
  var args = [].slice.call(arguments, 1);
  if (this.length) {
    callback.call(this, args);
  }
  return this;
};

/*----------------------------------------------------
/* Show/hide Scroll to top
/*--------------------------------------------------*/
jQuery(document).ready(function($) {
	//move-to-top arrow
	jQuery("body").prepend("<a id='move-to-top' class='animate ' href='#blog'><i class='fa fa-angle-up'></i></a>");

	var scrollDes = 'html,body';
	/*Opera does a strange thing if we use 'html' and 'body' together so my solution is to do the UA sniffing thing*/
	if(navigator.userAgent.match(/opera/i)){
		scrollDes = 'html';
	}
	//show ,hide
	jQuery(window).scroll(function () {
		if (jQuery(this).scrollTop() > 160) {
			jQuery('#move-to-top').addClass('filling').removeClass('hiding');
		} else {
			jQuery('#move-to-top').removeClass('filling').addClass('hiding');
		}
	});
});


/*----------------------------------------------------
/* Make all anchor links smooth scrolling
/*--------------------------------------------------*/
jQuery(document).ready(function($) {
 // scroll handler
  var scrollToAnchor = function( id, event ) {
    // grab the element to scroll to based on the name
    var elem = $("a[name='"+ id +"']");
    // if that didn't work, look for an element with our ID
    if ( typeof( elem.offset() ) === "undefined" ) {
      elem = $("#"+id);
    }
    // if the destination element exists
    if ( typeof( elem.offset() ) !== "undefined" ) {
      // cancel default event propagation
      event.preventDefault();

      // do the scroll
      // also hide mobile menu
      var scroll_to = elem.offset().top;
      $('html, body').removeClass('mobile-menu-active').animate({
              scrollTop: scroll_to
      }, 600, 'swing', function() { if (scroll_to > 46) window.location.hash = id; } );
    }
  };
  // bind to click event
  $("a").click(function( event ) {
    //if(!$(this).parents('.wc-tabs')) {
      // only do this if it's an anchor link
      var href = $(this).attr("href");
      if ( href && href.match("#") && href !== '#' && !$(this).hasClass('mts-wc-tab') ) {
        // scroll to the location
        var parts = href.split('#'),
          url = parts[0],
          target = parts[1];
        if ((!url || url == window.location.href.split('#')[0]) && target)
          scrollToAnchor( target, event );
      }
    //}
  });
});

/*----------------------------------------------------
/* Responsive Navigation
/*--------------------------------------------------*/
if (mts_customscript.responsive && mts_customscript.nav_menu != 'none') {
    jQuery(document).ready(function($){
        // merge if two menus exist
        if (mts_customscript.nav_menu == 'both') {
            $('.navigation').not('.mobile-menu-wrapper').find('.menu').clone().appendTo('.mobile-menu-wrapper').hide();
        }

        $('.toggle-mobile-menu').click(function(e) {
            e.preventDefault();
            e.stopPropagation();
            $('body').toggleClass('mobile-menu-active');
        });

        // prevent propagation of scroll event to parent
        $(document).on('DOMMouseScroll mousewheel', '.mobile-menu-wrapper', function(ev) {
            var $this = $(this),
                scrollTop = this.scrollTop,
                scrollHeight = this.scrollHeight,
                height = $this.height(),
                delta = (ev.type == 'DOMMouseScroll' ?
                    ev.originalEvent.detail * -40 :
                    ev.originalEvent.wheelDelta),
                up = delta > 0;

            var prevent = function() {
                ev.stopPropagation();
                ev.preventDefault();
                ev.returnValue = false;
                return false;
            }

            if ( $('a#pull').css('display') !== 'none' ) { // if toggle menu button is visible ( small screens )

              if (!up && -delta > scrollHeight - height - scrollTop) {
                  // Scrolling down, but this will take us past the bottom.
                  $this.scrollTop(scrollHeight);
                  return prevent();
              } else if (up && delta > scrollTop) {
                  // Scrolling up, but this will take us past the top.
                  $this.scrollTop(0);
                  return prevent();
              }
            }
        });
    }).on('click', function(event) {

        var $target = jQuery(event.target);
        if ( ( $target.hasClass("fa") && $target.parent().hasClass("toggle-caret") ) ||  $target.hasClass("toggle-caret") ) {// allow clicking on menu toggles
            return;
        }

        jQuery('body').removeClass('mobile-menu-active');
    });


}

/*----------------------------------------------------
/*  Dropdown menu
/* ------------------------------------------------- */
jQuery(document).ready(function($) {

    function mtsDropdownMenu() {
        var wWidth = $(window).width();
        if(wWidth > 865) {
            $('.navigation ul.sub-menu, .navigation ul.children').hide();
            var timer;
            var delay = 100;
            $('.navigation li').hover(
              function() {
                var $this = $(this);
                timer = setTimeout(function() {
                    $this.children('ul.sub-menu, ul.children').slideDown('fast');
                }, delay);

              },
              function() {
                $(this).children('ul.sub-menu, ul.children').hide();
                clearTimeout(timer);
              }
            );
        } else {
            $('.navigation li').unbind('hover');
            $('.navigation li.active > ul.sub-menu, .navigation li.active > ul.children').show();
        }
    }

    mtsDropdownMenu();

    $(window).resize(function() {
        mtsDropdownMenu();
    });
});

/*---------------------------------------------------
/*  Vertical ( widget ) menus/lists
/* -------------------------------------------------*/
jQuery(document).ready(function($) {

    var wWidth = $(window).width();

    $('.widget_nav_menu, .widget_product_categories, .widget_pages, .widget_categories, .navigation .menu').addClass('toggle-menu');
    $('.toggle-menu ul.sub-menu, .toggle-menu ul.children').addClass('toggle-submenu');
    $('.toggle-menu .current-menu-item, .toggle-menu .current-cat, .toggle-menu .current_page_item').addClass('toggle-menu-current-item');
    //$('.toggle-menu .menu-item-has-children, .toggle-menu .cat-parent, .toggle-menu .page_item_has_children').addClass('toggle-menu-item-parent');
    $('.toggle-menu ul.sub-menu, .toggle-menu ul.children').parent().addClass('toggle-menu-item-parent');

    $('.toggle-menu').each(function() {
        var $this = $(this);

        $this.find('.toggle-submenu').hide();
        if ( ! ( $this.closest('#site-header').length && wWidth > 865 ) ) {// skip header nav dropdowns
          $this.find('.toggle-menu-current-item').last().parents('.toggle-menu-item-parent').addClass('active').children('.toggle-submenu').show();
        }
        $this.find('.toggle-menu-item-parent').append('<span class="toggle-caret"><i class="fa fa-angle-down"></i></span>');
    });

    $('.toggle-caret').click(function(e) {
        e.preventDefault();
        $(this).parent().toggleClass('active').children('.toggle-submenu').slideToggle('fast');
    });
});

/*----------------------------------------------------
/* Social button scripts
/*---------------------------------------------------*/
jQuery(document).ready(function($){
	(function(d, s) {
	  var js, fjs = d.getElementsByTagName(s)[0], load = function(url, id) {
		if (d.getElementById(id)) {return;}
		js = d.createElement(s); js.src = url; js.id = id;
		fjs.parentNode.insertBefore(js, fjs);
	  };
	jQuery('span.facebookbtn, span.facebooksharebtn, .facebook_like').exists(function() {
	  load('//connect.facebook.net/en_US/all.js#xfbml=1&version=v2.3', 'fbjssdk');
	});
	jQuery('span.twitterbtn').exists(function() {
	  load('//platform.twitter.com/widgets.js', 'tweetjs');
	});
	jQuery('span.linkedinbtn').exists(function() {
	  load('//platform.linkedin.com/in.js', 'linkedinjs');
	});
	jQuery('span.pinbtn').exists(function() {
	  load('//assets.pinterest.com/js/pinit.js', 'pinterestjs');
	});
	}(document, 'script'));
});

/*----------------------------------------------------
/* Lazy load avatars
/*---------------------------------------------------*/
jQuery(document).ready(function($){
    var lazyloadAvatar = function(){
        $('.comment-author .avatar').each(function(){
            var distanceToTop = $(this).offset().top;
            var scroll = $(window).scrollTop();
            var windowHeight = $(window).height();
            var isVisible = distanceToTop - scroll < windowHeight;
            if( isVisible ){
                var hashedUrl = $(this).attr('data-src');
                if ( hashedUrl ) {
                    $(this).attr('src',hashedUrl).removeClass('loading');
                }
            }
        });
    };
    if ( $('.comment-author .avatar').length > 0 ) {
        $('.comment-author .avatar').each(function(i,el){
            $(el).attr('data-src', el.src).removeAttr('src').addClass('loading');
        });
        $(function(){
            $(window).scroll(function(){
                lazyloadAvatar();
            });
        });
        lazyloadAvatar();
    }
});

/*..................
[custom-dropdown]
....................*/

jQuery(document).ready(function($){

  $('#e-dropdown').on('click', function(e) {
    e.preventDefault();
    $(this).toggleClass('active');
  });
  $('body').click(function(e) {
    if (!$(e.target).closest('#e-dropdown').length)
      $('#e-dropdown').removeClass('active');
  });

  $('#e-dropdown li').on('click', function(e) {
    e.preventDefault();
    var first = $(this).text();
    $('#e-dropdown .week').text(first);
  });
  $('.noclick').click(function(event) {
    event.preventDefault();
  });

});

/*........
[FAQ]
..........*/

jQuery(document).ready(function($){
 $(".faq-block .faq-a").hide();
    $(".faq-block .faq-q").click(function () {
        $(this).next(".faq-block .faq-a").slideToggle(300);
        $(this).toggleClass("expanded");
    });
});

/*.........................
[Header Morph Search]
...........................*/
jQuery(document).ready(function($){

  $(document).on('click', '.morphsearch-input', function(e) {
    e.preventDefault();
    var $this = $(this);
    $('#morphsearch').addClass('open');
  });

  $(document).on('click', '.morphsearch-close', function(ev) {
    closeSearch();
  });

  $(document).on('keydown', function(ev) {
    var keyCode = ev.keyCode || ev.which;
    if( keyCode === 27 && $('#morphsearch').hasClass('open') ) {
      closeSearch();
    }
  });

  function closeSearch() {
    var $morphSearch = $('#morphsearch'),
        $input = $('button.morphsearch-input');

    $morphSearch.removeClass('open');

    if( $input.val() !== '' ) {
      setTimeout(function() {
        $morphSearch.addClass('hideInput');
        setTimeout(function() {
          $morphSearch.removeClass('hideInput');
          $input.val('');
        }, 300 );
      }, 500);
    }

    $input.blur();
  }

  $(document).on('submit', '.morphsearch-form', function(e) {
    var $input = $('button.morphsearch-input');
    if ( !$('#morphsearch').hasClass('open') ) {
      ev.preventDefault();
      return false;
    }
  });
});
