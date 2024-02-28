$(document).ready(function(){
	window.oldCompanyId = $('.select-company option:selected').val();
	var oldSubtype = $('.select-company option:selected').data('subtype');
	var oldType = $('.select-company option:selected').data('type');
	window.oldCountryId = $('.select-country option:selected').data('content');
	console.log($('.select-country option:selected').data('content'));
	var oldCity
	var citySelect = document.getElementById('city-dropdown');
	if(citySelect)
	{
		oldCity = $('#city-dropdown').val();
	}else{
		oldCity = $('#job-city').val();
	}

	if(!oldCity){
		oldCity = $('#old-job-city').val();
	}
console.log($('#old-job-city').val());
	$(document).on('change', '.select-company', function(){
		
		var companyId = $('.select-company option:selected').val();
		console.log(companyId);
		if(companyId != '' && companyId != undefined)
		{
			//CLEAR CITY AND COUNTRY VALUES
			// $('#country').html('');
			// $('#city-name').remove();
			// var citySelect = document.getElementById("city-dropdown");
   //  		if(!citySelect)
   //  		{
   //  			var citySelect = document.createElement("select");
			// 	citySelect.setAttribute('id', 'city-dropdown');
   //              citySelect.setAttribute('class', 'form-control');
   //              citySelect.setAttribute('name', 'city');


   //              var z = document.createElement("option");
			// 	z.setAttribute("value", "");
		 //    	var t = document.createTextNode('Select City');
		 //   		z.appendChild(t);
		 //   		citySelect.appendChild(z);
   //              document.getElementById("select-city").appendChild(citySelect);
   //  		}else{
   //  			$('#city-dropdown').html('');
   //  			var z = document.createElement("option");
			// 	z.setAttribute("value", "");
		 //    	var t = document.createTextNode('Select City');
		 //   		z.appendChild(t);
		 //   		document.getElementById('city-dropdown').appendChild(z);
   //  		}



			//GET COMPANY DETAILS
			var companyType = $('.select-company option:selected').data('type');
			var companySubtype = $('.select-company option:selected').data('subtype');
			//GET COUNTRY DETAILS
			var countryId = $('.select-country option:selected').data('content');

			//SET INDUSTRY AND CATEGORY
			var category = $('.select-company option:selected').data('category');
			$('.select-category').val(category);
			var sector = $('.select-company option:selected').data('sector');
			$('.select-sector').val(sector);
			console.log(sector)
			var about = $('.select-company option:selected').data('about');
			$('#about-company').val(about);
			var oldAboutComp = $('textarea#about-company').val().length;
			$(".about-comp-result").text(oldAboutComp + " chars");

			var whyUs = $('.select-company option:selected').data('why');
			$('#why-us').val(whyUs);
			var oldWhyUs = $('textarea#why-us').val().length;
			$(".why-us-result").text(oldWhyUs + " chars");

console.log(companyType)
			if(companyType == 'generic')
			{
				$.ajax({
					url: '/admin/get-company-countries/'+companyId,
					method: 'GET',
					data: '',
					success:function(data)
					{
						var countries = data;
						$('.select-country').html();
						$('.select-country').attr('disabled', false)
						var select = document.getElementById("country");
							
						var z = document.createElement("option");
						z.setAttribute("value", "");
				    	var t = document.createTextNode('Select Country');
				   		z.appendChild(t);
				    	document.getElementById("country").appendChild(z); 

						if(countries)
						{

							for(var i=0; i<countries.length; i++)
							{
								var z = document.createElement("option");
								z.setAttribute("value", countries[i]['name']);
								z.setAttribute("data-content", countries[i]['id']);
								z.setAttribute("data-code", countries[i]['abbreviation']);
						    	var t = document.createTextNode(countries[i]['name']);
						   		z.appendChild(t);
						    	document.getElementById("country").appendChild(z);

							}
						}

					}
				})
			}else{
				var companyCountry = $('.select-company option:selected').data('country');
				console.log(companyCountry)
				$('.select-country').attr('disabled', false)
				var select = document.getElementById("country");
				var z = document.createElement("option");
				z.setAttribute("value", companyCountry.name);
				z.setAttribute("selected", "true");
				z.setAttribute("hidden", "true");
		    	var t = document.createTextNode(companyCountry.name);
		   		z.appendChild(t);
		    	document.getElementById("country").appendChild(z);

		  //   	if(companySubtype == 'city_subsidiary')
		  //   	{
		  //   		cityName = $('.select-company option:selected').data('city');
		  //   		cityLatitude = $('.select-company option:selected').data('latitude');
		  //   		cityLongtitude = $('.select-company option:selected').data('longtitude');
				// 	$('#city-latitude').val(cityLatitude);
				// 	$('#city-longtitude').val(cityLongtitude);

		    		

		  //   		var z = document.createElement("option");
				// 	z.setAttribute("value", cityName);
				// 	z.setAttribute("selected", "true");
				// 	z.setAttribute("hidden", "true");
			 //    	var t = document.createTextNode(cityName);
			 //   		z.appendChild(t);
			 //    	citySelect.appendChild(z);
				// }
		   //  	}else if(companySubtype == 'country_subsidiary'){
		   //  		console.log(document.getElementById("city-dropdown"));
		   //  		$.ajax({
					// 	url: '/admin/get-company-cities/'+companyId+'/'+companyCountry.id,
					// 	method: 'GET',
					// 	data: '',
					// 	success:function(data)
					// 	{
					// 		cities = data;
					// 		var z = document.createElement("option");
					// 		z.setAttribute("value", "");
					//     	var t = document.createTextNode('Select City');
					//    		z.appendChild(t);
					//     	citySelect.appendChild(z);
					// 		if(cities)
					// 		{

					// 			if(cities)
					// 			{

					// 				for(var i=0; i<cities.length; i++)
					// 				{
					// 					var z = document.createElement("option");
					// 					z.setAttribute("value", cities[i]['city']);
					// 					z.setAttribute("data-latitude", cities[i]['latitude']);
					// 					z.setAttribute("data-longtitude", cities[i]['longtitude']);
					// 			    	var t = document.createTextNode(cities[i]['city']);
					// 			   		z.appendChild(t);
					// 			    	document.getElementById("city-dropdown").appendChild(z);


					// 				}
					// 			}else{
					// 				var z = document.createElement("option");
					// 				z.setAttribute("value", '');
					// 				z.setAttribute("data-latitude", '');
					// 				z.setAttribute("data-longtitude", '');
							    	
					// 		    	document.getElementById("city-dropdown").appendChild(z);
					// 			}
					// 		}

					// 	}
					// })
		   //  	}

			}

		}else{
			$('.select-company').val('');
			$('.select-country').val('');
			$('.select-country').html('');
			$('#city-dropdown').remove();
			cityInput = document.getElementById('city-name');
			if(!cityInput)
			{
				var cityInput = document.createElement("input"); 
				cityInput.setAttribute("type", 'text');
				cityInput.setAttribute("class", 'form-control');
				cityInput.setAttribute("placeholder", 'City');
				cityInput.setAttribute("name", 'city');
				cityInput.setAttribute("id", 'city-name');
				cityInput.setAttribute("value", '');
				document.getElementById("select-city").appendChild(cityInput);
			}else{
				$('#city-name').val('');
			}

			var countries = $('.select-country').data('countries')
			if(countries)
			{
				var select = document.getElementById("country");
				
				var z = document.createElement("option");
				z.setAttribute("value", "");
		    	var t = document.createTextNode('Select Country');
		   		z.appendChild(t);
		    	document.getElementById("country").appendChild(z);

				for(var i=0; i<countries.length; i++)
				{
					var z = document.createElement("option");
					z.setAttribute("value", countries[i]['name']);
					z.setAttribute("data-content", countries[i]['id']);
			    	var t = document.createTextNode(countries[i]['name']);
			   		z.appendChild(t);
			    	document.getElementById("country").appendChild(z);

				}
			}
		}
	})


	//CHANGE COUNTRY EVENT
	$(document).on('change', '.select-country', function(){

		var countryId = $('.select-country option:selected').data('content');
		var companyId = $('.select-company option:selected').data('content');
        
        $('#city-name').remove();
		var citySelect = document.getElementById("city-dropdown");
		if(!citySelect)
		{
			var citySelect = document.createElement("select");
			citySelect.setAttribute('id', 'city-dropdown');
            citySelect.setAttribute('class', 'form-control');
            citySelect.setAttribute('name', 'city');

            document.getElementById("select-city").appendChild(citySelect);
        }else{
        	$('#city-dropdown').html('');
        }

		if(companyId != undefined && countryId != undefined && companyId != '' && countryId != '')
		{
			$.ajax({
				url: '/admin/get-company-cities/'+companyId+'/'+countryId,
				method: 'GET',
				data: '',
				success:function(data)
				{
					cities = data;

					var z = document.createElement("option");
					z.setAttribute("value", "");
			    	var t = document.createTextNode('Select City');
			   		z.appendChild(t);
			    	citySelect.appendChild(z);
					if(cities)
					{

						if(cities)
						{

							for(var i=0; i<cities.length; i++)
							{
								var z = document.createElement("option");
								z.setAttribute("value", cities[i]['city']);
								z.setAttribute("data-latitude", cities[i]['latitude']);
								z.setAttribute("data-longtitude", cities[i]['longtitude']);
						    	var t = document.createTextNode(cities[i]['city']);
						   		z.appendChild(t);
						    	citySelect.appendChild(z);


							}
						}else{
							var z = document.createElement("option");
							z.setAttribute("value", '');
							z.setAttribute("data-latitude", '');
							z.setAttribute("data-longtitude", '');
					    	
					    	citySelect.appendChild(z);
						}
					}
				}
			})
		}else{
			$('#city-dropdown').remove();
			cityInput = document.getElementById('city-name');
			if(!cityInput)
			{
				var cityInput = document.createElement("input"); 
				cityInput.setAttribute("type", 'text');
				cityInput.setAttribute("class", 'form-control');
				cityInput.setAttribute("placeholder", 'City');
				cityInput.setAttribute("name", 'city');
				cityInput.setAttribute("id", 'city-name');
				cityInput.setAttribute("value", '');
				document.getElementById("select-city").appendChild(cityInput);
			}else{
				cityInput.val('');
			}
		}


	})


	//CHANGE CITY EVENT
	$(document).on('change', '#city-dropdown', function(){
		var cityLatitude = $('#city-dropdown option:selected').data('latitude');
		$('#city-latitude').val(cityLatitude);
		var cityLongtitude = $('#city-dropdown option:selected').data('longtitude');
		$('#city-longtitude').val(cityLongtitude);
		window.oldCity = $('#city-dropdown option:selected').val();
	})


window.oldCityName = $('#city-dropdown option:selected').val();
console.log(oldCity, oldCountryId, oldCompanyId);
	if(oldCompanyId) 
	{
		$(document).ready(function(){
		$.ajax({
			url: '/admin/get-company-countries/'+oldCompanyId,
			method: 'GET',
			data: '',
			success:function(data)
			{
				if(oldCountryId != '' && oldCountryId != undefined)
				{
					var oldCountry = oldCountryId;
				}else if( oldSubtype === 'city_subsidiary' || oldSubtype === 'country_subsidiary')
				{
					var country = $('.select-company option:selected').data('country');
					var oldCountry = country.id;
				}
				$('.select-country').html('');
				var countries = data;
				if(countries)
				{
					if(oldType && oldType == 'generic')
					{
						var select = document.getElementById("country");
					
						var z = document.createElement("option");
						z.setAttribute("value", "");
				    	var t = document.createTextNode('Select Country');
				   		z.appendChild(t);
				    	document.getElementById("country").appendChild(z);
					}
					

					for(var i=0; i<countries.length; i++)
					{
						var z = document.createElement("option");
						z.setAttribute("value", countries[i]['name']);
						z.setAttribute("data-content", countries[i]['id']);
						if(oldCountry && oldCountry == countries[i]['id'])
						{
							z.setAttribute("selected", 'selected');
						}
				    	var t = document.createTextNode(countries[i]['name']);
				   		z.appendChild(t);
				    	document.getElementById("country").appendChild(z);

					}
				}

				if(oldCountryId)
				{
					$.ajax({
						url: '/admin/get-company-cities/'+oldCompanyId+'/'+oldCountryId,
						method: 'GET',
						data: '',
						success:function(data)
						{
							cities = data;
							console.log(cities);
							citySelect = document.getElementById('city-dropdown');
							if(!citySelect)
							{
								console.log(123);
								var citySelect = document.createElement("select");
								citySelect.setAttribute('id', 'city-dropdown');
					            citySelect.setAttribute('class', 'form-control');
					            citySelect.setAttribute('name', 'city');
					            var z = document.createElement("option");
								z.setAttribute("value", "");
						    	var t = document.createTextNode('Select City');
						   		z.appendChild(t);
						    	citySelect.appendChild(z);
					            document.getElementById("select-city").appendChild(citySelect);
					        }else{
					        	$('#city-dropdown').html('');
					        	var z = document.createElement("option");
								z.setAttribute("value", '');
						    	var t = document.createTextNode('Select City');
						   		z.appendChild(t);
						    	citySelect.appendChild(z);
					        }
					        $('#city-name').remove();
							
							var companyCity = $('.select-company option:selected').val();
							if(cities)
							{
								

								for(var i=0; i<cities.length; i++)
								{
									var z = document.createElement("option");
									z.setAttribute("value", cities[i]['city']);
									z.setAttribute("data-latitude", cities[i]['latitude']);
									z.setAttribute("data-longtitude", cities[i]['longtitude']);
									if(oldCity && oldCity == cities[i]['city'])
									{
										z.setAttribute("selected", 'selected');
									}
							    	var t = document.createTextNode(cities[i]['city']);
							   		z.appendChild(t);
							    	citySelect.appendChild(z);


								}
							}
						}
					})
				}
			}
		})
		})
	}

})