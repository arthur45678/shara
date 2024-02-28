// $(function () {
// 	var country = $('#company_country').val();
// 	if(country)
// 	{
// 		$.ajax({
// 			url: '/admin/get-country-cities/'+country,
// 			method: 'GET',
// 			data: '',
// 			success:function(data)
// 			{
// 				var cities = data.cities;
// 				$('#autocomplete_city_edit').autocomplete({
// 					source:cities
// 				});
// 			}
// 		})
// 	}
	
// 	$(document).on('change', '#selectpicker_country', function() {
// 		var country = $('#selectpicker_country option:selected').val();
// 		$.ajax({
// 			url: '/admin/get-country-cities/'+country,
// 			method: 'GET',
// 			data: '',
// 			success:function(data)
// 			{
// 				var cities = data.cities;
// 				$('#autocomplete_city_edit').autocomplete({
// 					source:cities
// 				});
// 			}
// 		})
// 	})
	
// });

// $(function () {
// 	var country = $('#company_country').val();
// 	if(country)
// 	{
// 		$.ajax({
// 			url: '/admin/get-country-cities/'+country,
// 			method: 'GET',
// 			data: {value:''},
// 			success:function(data)
// 			{
// 				var cities = data.cities;
// 				$('#autocomplete_city_edit').autocomplete({
// 					source:cities
// 				});
// 			}
// 		})
// 	}
	
// 	$('#autocomplete_city_edit').keyup(function() {
// 		var country = $('#selectpicker_country option:selected').val();
// 		var value = this.value;
// 		$('#autocomplete_city_edit').autocomplete({
// 					source:''
// 				});
// 		if(this.value.length < 2 || !country)
// 		{
// 			return;
// 		}
		
// 		$.ajax({
// 			url: '/admin/get-country-cities/'+country,
// 			method: 'GET',
// 			data: {value:value},
// 			success:function(data)
// 			{
// 				var cities = data.cities;
// 				$('#autocomplete_city_edit').autocomplete({
// 					source:cities
// 				});
// 			}
// 		})
// 	})
	
// });