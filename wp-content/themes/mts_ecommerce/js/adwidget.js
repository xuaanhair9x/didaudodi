jQuery(document).ready(function($){

    mtsImageWidgetField = {

        uploader : function( widget_id, widget_id_string ) {

            var frame = wp.media({
                title : adWidget.frame_title,
                multiple : false,
                library : { type : 'image' },
                button : { text : adWidget.button_title }
            });

            frame.on('close',function( ) {
                var attachments = frame.state().get('selection').toJSON();
                $("#" + widget_id_string + 'preview').html('<img src="' + attachments[0].url + '" style="margin:0 0 10px;padding:0;max-width:100%;height:auto;float:left;display:inline-block"/>');
                $("#" + widget_id_string + 'attachment_id').val(attachments[0].id);
                $("#" + widget_id_string + 'image_uri').val(attachments[0].url);
            });

            frame.open();
            return false;
        }
    };
});

/*
 * Checkbox which show/hide color pickers
 */
(function( $ ){

    "use strict";

    function mtsAdWidgetCheckboxToggle( el ) {
        var $this = el,
            tempValue = $this.prop( 'checked' ),
            id = $this.attr('id'),
            childOptClass = '.mother-checkbox-'+id;

        $(childOptClass).addClass('hidden');

        if ( tempValue ) {

            $(childOptClass).removeClass('hidden');
        }
    }

    function mtsAdWidgetCP( el ) {
        var $this = el,
            hasColorPicker = $this.hasClass('wp-color-picker');

        if ( ! hasColorPicker ) {

            $this.wpColorPicker();
        }
    }

    $(document).ready(function () {

        $('#widgets-right .ad-widget-color-picker, .inactive-sidebar .ad-widget-color-picker').wpColorPicker();

        $('.ad-widget-mother-checkbox').each(function() {
            mtsAdWidgetCheckboxToggle($(this));
        });

        $(document).on('click', '.ad-widget-mother-checkbox', function() {
            mtsAdWidgetCheckboxToggle($(this));
        });

        $(document).on('ajaxStop', function() {


            $('.ad-widget-mother-checkbox').each(function() {
                mtsAdWidgetCheckboxToggle($(this));
            });

            $('#widgets-right .ad-widget-color-picker, .inactive-sidebar .ad-widget-color-picker').each(function() {
                mtsAdWidgetCP($(this));
            });
        });
    });
})( jQuery );