$(document).ready(function(){
	$('#addSubsidiary').on('click', function(){
		var token = $('#token').val();
		var country = $('.bububu option:selected').val();
		var parentId = $('#parent-id').val();
		var type = $('#type').val();
		var subtype = $('#sub-type').val();
		data = {
			_token:token,
			country:country,
			parent_id:parentId,
			type:type,
			sub_type:subtype
		};
		$.ajax({
	        url: '/admin/create-company',
	        type: 'POST',
	        data: data,
	        success: function(data)
	        {

	            if(data == 'country-exists'){
	            	var message = country+' subsidiary already exists.';
	            	$('.danger-message').text(message);
	            	$('.alert-danger').show();
	            	$('.alert-success').hide();

	            }else if(data == 'success'){
	            	var message = country+' subsidiary succefully added.'
	            	$('.success-message').text(message);
	            	$('.alert-success').show();
	            	$('.alert-danger').hide();

					
	            }

	            setTimeout(function() { 
	            	$('.alert-danger').hide('slow');
	            	$('.alert-success').hide('slow');
	             }, 2500);
	        }
	    })

	})
})