  var placeSearch, autocomplete, subautocomplete;
  var componentForm = {
    street_number: 'short_name',
    route: 'long_name',
    locality: 'long_name',
    administrative_area_level_1: 'short_name',
    country: 'long_name',
    postal_code: 'short_name'
  };


  function initAutocomplete() {
    // FOR GENERIC AND COUNTRY SUBSIDIARIES AND JOBS

    // Create the autocomplete object, restricting the search to geographical
    // location types.
    var input = document.getElementById('city-name');
    geocode = $('#country option:selected').data('code');

    var options = {
        types: ['(cities)'],
        componentRestrictions: {country: geocode}
       };
    autocomplete = new google.maps.places.Autocomplete(input, options);

    // When the user selects an address from the dropdown, populate the address
    // fields in the form.
    autocomplete.addListener('place_changed', fillInAddress);

    //FOR CITY SUBSIDIARY

    // Create the autocomplete object, restricting the search to geographical
    // location types.
    var subinput = document.getElementById('subsidiary-name');

    subautocomplete = new google.maps.places.Autocomplete(subinput, options);

    // When the user selects an address from the dropdown, populate the address
    // fields in the form.
    subautocomplete.addListener('place_changed', fillInSubsidiary);



    // USER LOCATION
    var location = document.getElementById('user-location');
    // if(location){
    // console.log(location)
    //    userAutocomplete = new google.maps.places.Autocomplete(location);
    //    console.log($('#user-location').val())
    //   // When the user selects an address from the dropdown, populate the address
    //   // fields in the form.
    //   autocomplete.addListener('place_changed', fillInUserLocation);
    // }
    if(!location){
        return ;
    }
    var searchBox = new google.maps.places.SearchBox(location);
    
    searchBox.addListener('places_changed', function() {
        var places = searchBox.getPlaces();
        console.log(places)
        var latitude = places[0].geometry.location.lat();
        var longtitude = places[0].geometry.location.lng();
        $('#latitude').val(latitude);
        $('#longtitude').val(longtitude);
        for (var i=0; i < places[0].address_components.length; i++) {

        //Fill country name
          for (var j=0; j < places[0].address_components[i].types.length; j++) {

            if (places[0].address_components[i].types[j] == "country") {
              country = places[0].address_components[i];
              var countryName = country.long_name;
              var countryCode = country.short_name;
                $('#country').val(countryName);
            }

            //Fill city name
            if(places[0].address_components[i].types[j] == "locality"){
                var name = places[0].address_components[i].long_name;
                $('#city').val(name);

                }
            }
        }

        $.ajax({
          url:'/admin/phone-code',
          method:'GET',
          data:{countryCode:countryCode},
          success:function(response) {
            console.log(response)
            $('.phone_number').val(response.phone_code);
            $('.phone_code').val(response.phone_code);
          }
        });

    });
  }

  //FILLING DETAILS FOR CITY SUBSIDIAIARY COMPANY
  function fillInSubsidiary() {
    // Get the place details from the autocomplete object.
    var places = subautocomplete.getPlace();
      if (places.length == 0) {
        return;
      }

      //Get latitude and longtitude and fill input values
      var latitude = places.geometry.location.lat();
      var longtitude = places.geometry.location.lng();
      $('#subsidiary-latitude').val(latitude);
      $('#subsidiary-longtitude').val(longtitude);
    
    //Get country and city names
    for (var i=0; i < places.address_components.length; i++) {
      
        //Fill country name
      for (var j=0; j < places.address_components[i].types.length; j++) {
        if (places.address_components[i].types[j] == "country") {
          country = places.address_components[i];
          var countryName = country.long_name;
          $('#subsidiary-country').val(countryName);

        }

        //Fill city name 
        if(places.address_components[i].types[j] == "locality")
        {
            var name = places.address_components[i].long_name;
            console.log(name);
            $('#subsidiary-name').val(name);

        }
        if(places.address_components[i].types[j] == "administrative_area_level_1")
        {
          var regionName = places.address_components[i].long_name;
          $('#subsidiary-region').val(regionName);
          console.log(regionName);
        }
      }
    }
  }

  function fillInAddress() {
    // Get the place details from the autocomplete object. 
    var places = autocomplete.getPlace();
      if (places.length == 0) {
        return;
      }

      //Get latitude and longtitude and fill input values
      var latitude = places.geometry.location.lat();
      var longtitude = places.geometry.location.lng();
      $('#city-latitude').val(latitude);
      $('#city-longtitude').val(longtitude);
      $('#latitude').val(latitude);
      $('#longtitude').val(longtitude);
    
    //Get country and city names
    console.log(places.address_components)
    for (var i=0; i < places.address_components.length; i++) {

        //Fill country name
      for (var j=0; j < places.address_components[i].types.length; j++) {

        if (places.address_components[i].types[j] == "country") {
          country = places.address_components[i];
          var countryName = country.long_name;
          $('#city-country').val(countryName);
            $('#country').val(countryName);
        }

        //Fill city name
        if(places.address_components[i].types[j] == "locality")
        {
            var name = places.address_components[i].long_name;
            $('#city-name').val(name);

        }

        if(places.address_components[i].types[j] == "administrative_area_level_1")
        {
          var regionName = places.address_components[i].long_name;
          $('#region').val(regionName);
        }
      }
    }

    
  }


  // FILL IN THE USER LOCATION
  function fillInUserLocation()
  {
    var places = autocomplete.getPlace();
      if (places.length == 0) {
        return;
      }
    //Get country and city names
    console.log(places.address_components)
    var latitude = places.geometry.location.lat();
    var longtitude = places.geometry.location.lng();
    $('#latitude').val(latitude);
    $('#longtitude').val(longtitude);
    for (var i=0; i < places.address_components.length; i++) {

        //Fill country name
      for (var j=0; j < places.address_components[i].types.length; j++) {

        if (places.address_components[i].types[j] == "country") {
          country = places.address_components[i];
          var countryName = country.long_name;
          var countryCode = country.short_name;
          console.log(countryName)
            $('#country').val(countryName);
        }

        //Fill city name
        if(places.address_components[i].types[j] == "locality")
        {
            var name = places.address_components[i].long_name;
            $('#city').val(name);
        }

        
      }
    }
    
  }


initAutocomplete();

$('.select-company').on('change', function(){
  if($('.select-company').val() == "Select Company")
    setTimeout(function(){
      initAutocomplete();
    }, 100);

})

$('.select-company').on('change', function(){
  if($('.select-company').val() == "Select Company")
    var param =  $('#country option:selected').data('code');
    setTimeout(function(){
      initAutocomplete();
    }, 100);

})

$('#country').on('change', function(){
  if($('#country').val() == "")
      var param =  $('#country option:selected').data('code');
    $('#city-name').val('');
    setTimeout(function(){
      initAutocomplete();
    }, 100);

})

$(document).keypress(
    function(event){
      
     if (event.which == '13') {
        event.preventDefault();
      }
});