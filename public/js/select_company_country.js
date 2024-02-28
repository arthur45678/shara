$(function(){

$(document).on('change', '#selectpicker_country', function(){
	var country = $('#selectpicker_country option:selected').val();
	if (!country) {
		return;
	}
	// var selectpicker = document.getElementsByClassName("select-city");
	// var select = selectpicker[0].lastChild;
	$('#selectpicker_city').html('');

	$.ajax({
		url: '/admin/get-country-cities/'+country,
		method: 'GET',
		data: '',
		success:function(data)
		{
			var cities = data.cities;
			if(cities)
			{
				var city_option = document.createElement("option");
				var city_text = document.createTextNode('Select City');
				city_option.appendChild(city_text);
				city_option.setAttribute("value", '');
				var select = $('#selectpicker_city');
				select.append(city_option);
				cities.forEach(function(city, key, cities)
				{
					var city_option = document.createElement("option");
					var city_text = document.createTextNode(city);
					city_option.appendChild(city_text);
					city_option.setAttribute("value", city);
					var select = $('#selectpicker_city');
					select.append(city_option);
				});
				
			}
		}
	})	
})
var country = $('#selectpicker_country option:selected').val();
if (country) {
	var oldCity = $('#selectpicker_city').attr('old-name');
	$('#selectpicker_city').html('');
	$.ajax({
		url: '/admin/get-country-cities/'+country,
		method: 'GET',
		data: '',
		success:function(data)
		{
			var cities = data.cities;
			if(cities)
			{
				var city_option = document.createElement("option");
				var city_text = document.createTextNode('Select City');
				city_option.appendChild(city_text);
				city_option.setAttribute("value", '');
				var select = $('#selectpicker_city');
				select.append(city_option);
				cities.forEach(function(city, key, cities)
				{
					var city_option = document.createElement("option");
					var city_text = document.createTextNode(city);
					city_option.appendChild(city_text);
					city_option.setAttribute("value", city);
					var select = $('#selectpicker_city');
					select.append(city_option);
				});
				$('#selectpicker_city').val(oldCity)
			}
		}
	})
}

})