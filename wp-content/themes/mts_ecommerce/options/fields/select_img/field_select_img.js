jQuery(document).ready(function(){
	jQuery(document).on('change', 'select.img-select', function() {

		var $this = jQuery(this),
			imgSrc = $this.find(':selected').data('img'),
			$previewImg = $this.parent().find('.img-preview');

		$previewImg.attr( 'src', imgSrc );
	});
});