$(document).ready(function(){
	$('#addCity').on('click', function(){
		var token = $('#token').val();
		var city = $('#subsidiary-name').val();
		var latitude = $('#subsidiary-latitude').val();
		var longtitude = $('#subsidiary-longtitude').val(); 
		var cityCountry = $('#subsidiary-country').val();
		var region = $('#subsidiary-region').val();
		var country = $('#parent-country-id').val();
		var parentId = $('#parent-id').val();
		var countryParent = $('#country-parent').val();
		var type = $('#type').val();
		var subtype = $('#sub-type').val();
		data = {
			_token:token,
			subsidiary_city:city,
			city_longtitude:longtitude,
			city_latitude:latitude,
			city_country:cityCountry,
			city_country:cityCountry,
			country:country,
			city_region:region,
			parent_id:parentId,
			country_parent:countryParent,
			type:type,
			sub_type:subtype
			};
		$.ajax({
	        url: '/admin/create-city',
	        type: 'POST',
	        data: data,
	        success: function(data)
	        {
	        	$('#subsidiary-name').val('');
				$('#subsidiary-latitude').val('');
				$('#subsidiary-longtitude').val('');
				$('#subsidiary-country').val('');
				$('#subsidiary-region').val('');

	            if(data == 'city-exists'){
	            	var message = city+' city subsidiary already exists.';
	            	$('.danger-message').text(message);
	            	$('.alert-danger').show();
	            	$('.alert-success').hide();


	            	// $('.alert-danger').show('slow').html('You have successfully registered').delay(30000).fadeOut();

	            }else if(data == 'success'){
	            	var message = city+' city subsidiary succefully added.'
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