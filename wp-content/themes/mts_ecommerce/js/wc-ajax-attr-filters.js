jQuery(function($){

    //wrap the container
    $('ul.products').wrap('<div class="mts-ajax-filter-container"></div>');
    $('.woocommerce-info').wrap('<div class="mts-ajax-filter-container"></div>');

    $(document).on( 'click', '.mts-ajax-filter-links a', function(e) {

        e.preventDefault();
        var href = this.href;

        //loading
        $('ul.products').height($('ul.products').outerHeight()).html('').addClass('loading');
        $('nav.woocommerce-pagination').hide();

        $.ajax({
            url: href,
            success: function( response ) {
                $('ul.products').removeClass('loading');

                //container
                if( $( response ).find('ul.products').length > 0 ) {
                    $('.mts-ajax-filter-container').html( $(response).find('ul.products') );
                } else {
                    $('.mts-ajax-filter-container').html( $(response).find('.woocommerce-info') );
                }

                //pagination
                if( $(response).find('nav.woocommerce-pagination').length > 0 ) {
                    //if it does not exist create it
                    if( $('nav.woocommerce-pagination').length == 0 ) {
                        $('ul.products').after('<nav class="woocommerce-pagination pagination"></nav>');
                    }

                    $('nav.woocommerce-pagination')
                        .html( $(response).find('nav.woocommerce-pagination').html())
                        .show();
                }

                //load new widgets
                $('.mts-ajax-filter-widget').each(function(){
                    var id = $(this).attr('id');
                    $(this).html( $(response).find('#'+id).html() );

                    if( $(this).text() == '' ) {
                        $(this).hide();
                    } else {
                        $(this).show();
                    }
                });

                //update browser history (IE doesn't support it)
                if ( !navigator.userAgent.match(/msie/i) ) {
                    window.history.pushState( { "pageTitle": response.pageTitle }, "", href );
                }
            }
        });
    });
});