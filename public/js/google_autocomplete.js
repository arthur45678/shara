		
function cityDetails() {

		var input = document.getElementById('city-name');

		// var searchBox = new google.maps.places.SearchBox(input);

		geocode = $('#country option:selected').data('code');

		// searchBox.addListener('place_changed', function() {
		//     infowindow.close();
		//     marker.setVisible(false);
		//     var place = searchBox.getPlace();
		//     console.log(place);
		//     if (!place.geometry) {
		//       window.alert("Autocomplete's returned place contains no geometry");
		//       return;
		//     }
		// })

		var options = {
			  types: ['(cities)'],
			  componentRestrictions: {country: geocode}
			 };
		var autocomplete = new google.maps.places.Autocomplete(input, options);

		// var searchBox = new google.maps.places.SearchBox(input, options);
		google.maps.event.addDomListener(window, 'load', function() {
		var places = autocomplete.getPlaces();
		console.log(123)
		  if (places.length == 0) {
		    return;
		  }
		  console.log(places);

		  var latitude = places[0].geometry.location.lat();
		  var longtitude = places[0].geometry.location.lng();
		  $('#city-latitude').val(latitude);
		  $('#city-longtitude').val(longtitude);
		
		for (var i=0; i < places[0].address_components.length; i++) {
              for (var j=0; j < places[0].address_components[i].types.length; j++) {
                if (places[0].address_components[i].types[j] == "country") {
                  country = places[0].address_components[i];
                  var countryName = country.long_name;
                  $('#city-country').val(countryName);
     //              if($('#city-name').data('type') == 'company')
					// {
						console.log(countryName)
						$('#country').val(countryName);
					// }
                }

                if(places[0].address_components[i].types[j] == "locality")
                {
                    var name = places[0].address_components[i].long_name;
		  			$('#city-name').val(name);

                }
              }
            }

	});	

        //subsidiary

		var subInput = document.getElementById('subsidiary-name');
		var subSearchBox = new google.maps.places.SearchBox(subInput);
		subSearchBox.addListener('places_changed', function() {
		var subPlaces = subSearchBox.getPlaces();
		  if (subPlaces.length == 0) {
		    return;
		  }
		  var subName = subPlaces[0].name;
		  var subLatitude = subPlaces[0].geometry.location.lat();
		  var subLongtitude = subPlaces[0].geometry.location.lng();
		  $('#subsidiary-latitude').val(subLatitude);
		  $('#subsidiary-longtitude').val(subLongtitude);
		  for (var i=0; i < subPlaces[0].address_components.length; i++) {
              for (var j=0; j < subPlaces[0].address_components[i].types.length; j++) {
                if (subPlaces[0].address_components[i].types[j] == "country") {
                  subCountry = subPlaces[0].address_components[i];
                  var subCountryName = subCountry.long_name;
                  $('#subsidiary-country').val(subCountryName);
     //              if($('#subsidiary-name').data('type') == 'country')
					// {
					// 	console.log(countryName)
						// $('#country').val(subCountryName);
					// }
                }
console.log(subCountryName)
                if(subPlaces[0].address_components[i].types[j] == "locality")
                {
                    var subName = subPlaces[0].address_components[i].long_name;
		  			$('#subsidiary-name').val(subName);

                }
              }
            }
	});
}

// setTimeout(function(){
// 	cityDetails();

// }, 500);
cityDetails();

$('.select-company').on('change', function(){
	if($('.select-company').val() == "Select Company")
		setTimeout(function(){
			cityDetails();
		}, 100);

})

$('.select-company').on('change', function(){
	if($('.select-company').val() == "Select Company")
		var param =  $('#country option:selected').data('code');
		setTimeout(function(){
			cityDetails();
		}, 100);

})

$('#country').on('change', function(){
	if($('#country').val() == "")
			var param =  $('#country option:selected').data('code');

		setTimeout(function(){
			cityDetails();
		}, 100);

})



