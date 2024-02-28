$(function() {

	if($('.company-has-logo').val()) {
      $('.logo_image_url').attr('disabled', 'disabled');
    }
	$('.clear-logo-url').on('click', function(){
		$('.logo_image_url').val('');
		$('.logo-file').removeAttr('disabled');
	})

	$('.logo_image_url').keyup(function() {
		if($(this).val())
		{
			$('.logo-file').attr('disabled', 'disabled');
		}else{
			$('.logo-file').removeAttr('disabled');
		}
		
	})

})

$(document)
$('.remove-image').on('click', function(){
	// $('.thumbnail').children("img").hide();
	$('.replace').html('Add File');
	// $('.add').removeClass('hide');
	$('.company-has-logo').val('');
	$('#logo_name').val('');
	$('.logo_image_url').removeAttr('disabled');
	$('#old-logo').val('');
})