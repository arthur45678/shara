// $(function () {
// 	$(document).on('change', '#selectpicker_country', function() {
// 		var country = $('#selectpicker_country option:selected').val();
// 		$.ajax({
// 			url: '/admin/get-country-cities/'+country,
// 			method: 'GET',
// 			data: '',
// 			success:function(data)
// 			{
// 				var cities = data.cities;
// 				$('#autocomplete_city').autocomplete({
// 					source:cities
// 				});
// 			}
// 		})
// 	})
	
// });

// $(function () {
// 	$('.autocomplete_city').keyup(function() {
// 		var country = $('#selectpicker_country option:selected').attr('data-content');
// 		var value = this.value;
// 		console.log(country);
// 		if(this.value.length <= 2 || !country)
// 		{
// 			$('.autocomplete_city').autocomplete({
// 					source:''
// 				});
// 			return;
// 		}
// 		console.log(this.value.length)
// 		$.ajax({
// 			url: '/admin/get-country-cities/'+country,
// 			method: 'GET',
// 			data: {value:value},
// 			success:function(data)
// 			{
// 				var cities = data.cities;
// 				console.log(cities)
// 				$('.autocomplete_city').autocomplete({
// 					source:cities
// 				});
// 			}
// 		})
// 	})

// 	$('#selectpicker_country').on('change', function() {
// 		$('.autocomplete_city').autocomplete({
// 			source:''
// 		});
// 	})
	
// });