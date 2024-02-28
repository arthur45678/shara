$(document).ready(function(){
	var company = $('#company').val();
	var oldCompany = $('.select-company option:selected').val();
	var cities = [];
	$(document).on('change', '.select-company', function(){

		var selectCompany = $('.select-company option:selected').val();
		if(selectCompany != '' && selectCompany != undefined)
		{
			
			$('#city-name').val('');
			$('#city-dropdown').html('');

			// var citySelect = document.createElement("select");
			// citySelect.setAttribute('id', 'city-dropdown');
			// citySelect.setAttribute('class', 'form-control');
			// citySelect.setAttribute('name', 'city');
			// var option = document.createElement("option");
			// var text = document.createTextNode('Select City');
			// option.appendChild(text);
			// citySelect.appendChild(option);
			// document.getElementById("select-city").appendChild(citySelect);

			var country = $('.select-company option:selected').data('country');
			var category = $('.select-company option:selected').data('category');
			$('.select-category').val(category);
			var sector = $('.select-company option:selected').data('sector');
			$('.select-sector').val(sector);
			

			var type = $('.select-company option:selected').data('type');
			var company = $('.select-company option:selected').data('content');
			if(type == 'generic')
			{
				$('.select-country').html('');
				$('.city-dropdown').html('');
				var select = document.getElementById("city-dropdown");
				console.log(select);
					if(!select){
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
						var z = document.createElement("option");
						z.setAttribute("value", "");
				    	var t = document.createTextNode('Select City');
				   		z.appendChild(t);
				    	document.getElementById("city-dropdown").appendChild(z);
				    }
				$.ajax({
					url: '/admin/get-company-countries/'+company,
					method: 'GET',
					data: '',
					success:function(data)
					{
						var countries = data;

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
						    	var t = document.createTextNode(countries[i]['name']);
						   		z.appendChild(t);
						    	document.getElementById("country").appendChild(z);

							}
						}

						// $(document).on('change', '.select-country', function(){

						// 	var subcountry = $('.select-country option:selected').val();
						// 	var subcompany = $('.select-company option:selected').data('content');
						// 	$('.city-dropdown').html('');
						// 	$('#city-name').remove();
						// 	if(subcompany !== undefined && subcountry !== undefined)
						// 	{
						// 		$.ajax({
						// 			url: '/admin/get-company-cities/'+subcompany+'/'+subcountry,
						// 			method: 'GET',
						// 			data: '',
						// 			success:function(data)
						// 			{
						// 				var cities = data;
						// 				var select = document.getElementById("city-dropdown");

						// 				if(cities)
						// 				{

						// 					for(var i=0; i<cities.length; i++)
						// 					{
						// 						var z = document.createElement("option");
						// 						z.setAttribute("value", cities[i]['city']);
						// 						z.setAttribute("data-latitude", cities[i]['latitude']);
						// 						z.setAttribute("data-longtitude", cities[i]['longtitude']);
						// 				    	var t = document.createTextNode(cities[i]['city']);
						// 				   		z.appendChild(t);
						// 				    	document.getElementById("city-dropdown").appendChild(z);


						// 					}
						// 				}
						// 			}
						// 		})
						// 	}
							
						// })

					}
				})
			}else{
				$('#job-country').attr('name', 'country');
                $('#job-country').val(country);
				$('.select-country').val(country);
				$('.select-country').attr('disabled', 'disabled')
				var subType = $('.select-company option:selected').data('subtype');
				if(subType == 'city_subsidiary')
				{
					$('#city-dropdown').remove();
					$('#city-name').remove();
					var cityInput = document.createElement("input"); 
					cityInput.setAttribute("type", 'text');
					cityInput.setAttribute("class", 'form-control');
					cityInput.setAttribute("placeholder", 'City');
					cityInput.setAttribute("name", 'city');
					cityInput.setAttribute("id", 'city-name');
					document.getElementById("select-city").appendChild(cityInput);
					var city = $('.select-company option:selected').data('city');
					$('#city-name').val(city);
					$('#city-name').attr('disabled', true);
					$('#job-city').attr('name', 'city');
					$('#job-city').val(city);

				}else if(subType == 'country_subsidiary'){
					$('.city-dropdown').html('');
					$.ajax({
						url: '/admin/get-company-cities/'+company+'/'+country,
						method: 'GET',
						data: '',
						success:function(data)
						{
							console.log(data)
							$('.city-dropdown').html('');
							var cities = data;

							var select = document.getElementById("city-dropdown");
								if(!select)
								{
									var citySelect = document.createElement("select");
									citySelect.setAttribute('id', 'city-dropdown');
			                        citySelect.setAttribute('class', 'form-control');
			                        citySelect.setAttribute('name', 'city');
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
									}
								}else{
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
									    	document.getElementById("city-dropdown").appendChild(z);


										}
									}
								}
							
						}
					})
				}
				

		}

		$('.city-dropdown').on('chnage', function(){
			var latitude = $('.city-dropdown option:selected').data('latitude');
			$('#city-latitude').val(latitude);
			var longtitude = $('.city-dropdown option:selected').data('longtitude');
			$('#longtitude').val(longtitude);
		})

	}else{
		$('.select-country').val('');
		$('.select-country').attr('disabled', false);
		$('#city-dropdown').remove('');
		$('#city-name').remove();

		var cityInput = document.createElement("input"); 
		cityInput.setAttribute("type", 'text');
		cityInput.setAttribute("class", 'form-control');
		cityInput.setAttribute("placeholder", 'City');
		cityInput.setAttribute("name", 'city');
		cityInput.setAttribute("id", 'city-name');
		document.getElementById("select-city").appendChild(cityInput);
		$('.select-country').html('');
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
		    	var t = document.createTextNode(countries[i]['name']);
		   		z.appendChild(t);
		    	document.getElementById("country").appendChild(z);

			}
		}

	}
	
})


companyChange = $('.select-company option:selected').val();
// if(companyChange == oldCompany){
	$(document).on('change', '.select-country', function(){
		var subcountry = $('.select-country option:selected').val();
		var subcompany = $('.select-company option:selected').data('content');

		if(subcompany !== undefined && subcountry !== undefined)
		{
			$.ajax({
				url: '/admin/get-company-cities/'+subcompany+'/'+subcountry,
				method: 'GET',
				data: '',
				success:function(data)
				{
					$('.city-dropdown').html('');
					$('#city-dropdown').html('');
					$('#city-name').remove();
					var cities = data;
					var select = document.getElementById("city-dropdown");
					if(!select){
						var citySelect = document.createElement("select");
						citySelect.setAttribute('id', 'city-dropdown');
                        citySelect.setAttribute('class', 'form-control');
                        citySelect.setAttribute('name', 'city');
                        var z = document.createElement("option");
						z.setAttribute("value", "");
				    	var t = document.createTextNode('Select City');
				   		z.appendChild(t);
				    	citySelect.appendChild(z);
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
						}
						document.getElementById("select-city").appendChild(citySelect);
					}else{
						var z = document.createElement("option");
						z.setAttribute("value", "");
				    	var t = document.createTextNode('Select City');
				   		z.appendChild(t);
				    	document.getElementById("city-dropdown").appendChild(z);
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
						    	document.getElementById("city-dropdown").appendChild(z);


							}
						}
					}
					
				}
			})
		}
		
	})
// }

	
})