sharadoApp.controller('HomeController', 
	['$scope', '$http', '$translate', '$rootScope', 'Restangular', '$state', '$stateParams',  '$filter', '$location', '$window',

	function ($scope, $http,$translate, $rootScope, Restangular, $state, $stateParams,  $filter, $location, $window) {
		// console.log(document.body.innerHTML.lastIndexOf("1710239039027614"))
			// document.body.innerHTML = document.body.innerHTML.replace(document.body.innerHTML.lastIndexOf("1710239039027614"), document.body.innerHTML.lastIndexOf("1710239039027614") + 16 , '');
			//document.body.innerHTML = document.body.innerHTML.substr(0, document.body.innerHTML.lastIndexOf("1710239039027614")) + ""+ document.body.innerHTML.substr(document.body.innerHTML.lastIndexOf("1710239039027614") + 16);
			// var script = document.getElementById('google_maps_script');
			// var new_source = script.src+'&lang='+'it';
			// script.setAttribute('src', new_source);
			// document.body.appendChild(script);

			$scope.token = Cookies.get('token');
			$rootScope.loggedUser = Cookies.get('user');

			//scrolling to 'how it works'
			$(function() {
		    	$('.how_it_works').bind('click', function(event) {
		    		if($state.current.name != '/' && $state.current.name != 'alternative') {
		    			$state.go('/');
		    			setTimeout(function() {			    			
				        	$('html, body').stop().animate({
				            	scrollTop: $('#how_it_works').offset().top
				        	}, 500);
				        	event.preventDefault();
			    		}, 200);
			    	}else {

			        	$('html, body').stop().animate({
			            	scrollTop: $('#how_it_works').offset().top
			        	}, 500);
			        	event.preventDefault();
			    	}
		    		
		        	
		    	});
			});

			$rootScope.$on('$stateChangeSuccess', 
				function(event, toState, toParams, fromState, fromParams){
					$rootScope.lang = $stateParams.lang;
			console.log($stateParams)
					$window.ga('send', 'pageview', { page: $location.url() });
			 		setTimeout(function() {
			 			$scope.setDefaultLocation(toParams.lang);
			 		}, 100);

			 		$rootScope.emailSendingFailed = false;
			 		$rootScope.userExists = false;

				 	Cookies.set('urlCode', toParams.lang);
					if(toState.name == "/"){
						$rootScope.countryName = undefined;
						$rootScope.countryCode = undefined;
						$scope.showParam = undefined;
						$scope.search_text = undefined;
						$scope.type = undefined;
						$scope.url = undefined;
						$rootScope.search_text = undefined;
						$scope.keyword = undefined;
						$rootScope.searchLocation = undefined;
						$scope.count = undefined;
						$rootScope.inSearchLocation = undefined;
						$scope.locationField = undefined;

					}

					if(toState.name !== '/' && toState.name !== 'alternative') {
						setTimeout(function() {
							$scope.mapAdd();
							
						}, 50);
					}					
						
					$('html,body').animate({
			            scrollTop: 0
			        }, 200);
					

					// $translate.use($stateParams.lang);
					var availableLanguageKeys = $translate.getAvailableLanguageKeys();
					if(availableLanguageKeys.indexOf($stateParams.lang) !== -1) {
						$translate.use($stateParams.lang);
					}else {
						$translate.use('en');
					}

					$rootScope.metaDescription = null;
					$rootScope.metaKeywords = null;

					$(".navbar-collapse").collapse('hide');
					window.prerenderReady = true;
			})
			
		// $(document).on('click touchstart', function(){
		//     $(".navbar-collapse").collapse('hide');
		// });
		$(document).on('click',function (e) {
		  footerUl = $('ul:first li');
		  if (!footerUl.is(e.target) 
		      && footerUl.has(e.target).length === 0){
		    $(".navbar-collapse").collapse('hide');
		  }
		});
		var $translateFilter = $filter('translate');
		//hide the loader
		setTimeout(function(){
			$('.loader').hide();
			$('.sharado-body').removeClass('sharado-body');
			$('.content_loading').removeClass('content_loading');
		})
					
		

		//refresh jwt token hourly
		setInterval(function(){
			if(Cookies.get('token')) {
				Restangular.one('/token-refresh').get().then(function(response) {
				Cookies.set('token', response.new_token, {expires: 60*60*24*10});
				})
			}	
		}, 60*60*1000)

		//cookies policy
		$rootScope.cookiesPolicy = Cookies.get('cookiesPolicy');
		$scope.acceptCookiesPolicy = function() {
			Cookies.set('cookiesPolicy', true, {expires: 60*60*24*10});
			$rootScope.cookiesPolicy = Cookies.get('cookiesPolicy');
		}


		$scope.openSignUpModal = function() {
			$rootScope.user = {};
			$rootScope.registeredByFacebook = false;
			$rootScope.facebook_message = false;
			$rootScope.user.read_terms = false;
			$rootScope.emailSendingFailed = false;
	 		$rootScope.userExists = false;
			Cookies.set('registeredByFacebook', 'false', {expires: 60*60*24*10});
			this.firstStepForm.$setPristine();
			$('.user-email').removeAttr('readonly');
			$('.openSignUpModal').attr('data-toggle', 'modal');
			$('.openSignUpModal').attr('data-target', '#myModal');
		}

		//get dashboard data and location data
		$rootScope.locationData = {
		        	topCode : "",
			        mainCity : "",
			        mainCountry : "",
			        mainLatitude : "",
			        mainLongitude : "",
			        defaultLocation : ""		        
			    };
		setTimeout(function() {
			if($stateParams.lang == 'it') {		    	
		    	$rootScope.facebookRegisterImg = '/angular/images/capture-facebook-converted_it.png';
		    	$rootScope.facebookLoginImg = '/angular/images/capture-facebook-login_it.png';
		    	
		    }else {
		    	$rootScope.facebookRegisterImg = '/angular/images/capture-facebook-converted.png';
		    	$rootScope.facebookLoginImg = '/angular/images/capture-facebook-login.png';
		    }
			Restangular.one("/main-details").get({lang:$stateParams.lang}).then(function(response) {
				// var script = document.createElement('script');
	   //          script.type = 'text/javascript';
	   //          script.src = 'https://maps.googleapis.com/maps/api/js?key=' + window.googleKey + '&libraries=places';
	   //          script.src += '&language=' + response.languageCode;
	   //          script.id = "google-maps-script";
	   //          document.body.appendChild(script);
	   //          console.log(script)
				setTimeout(function() {
		    		$scope.mapAdd();
		    	}, 100);
				if ( response ) {
					//location data
			        $rootScope.locationData = {
			        	topCode : response.countryCode,
				        mainCity : response.cityName,
				        mainCountry : response.countryName,
				        mainLatitude : response.latitude,
				        mainLongitude : response.longitude,
				        defaultLocation : response.defaultLocation,
				        langCode : response.languageCode
			        };
			        $scope.localeLang = window.location.pathname.split("/")[1] === "" ? $rootScope.locationData.langCode : window.location.pathname.split("/")[1];
				}
				Restangular.one('/user-language').get({ lang: $scope.localeLang }).then(function(response) {
					var availableLanguageKeys = $translate.getAvailableLanguageKeys();
					if(availableLanguageKeys.indexOf(response.languageCode) !== -1) {
						$translate.use(response.languageCode);
					}else {
						$translate.use('en');
					}
		 			
		 		});
			})
		}, 200);



		// set location and get location details by country code
		$rootScope.defaultLocation = {};
		$scope.setDefaultLocation = function(defaultLocation){
			Restangular.one("/main-details").get({lang:defaultLocation}).then(function(response) {
		        if(response.defaultLatitude && response.defaultLongitude){
		        	$rootScope.defaultLocation = {
							        		defaultLatitude: response.defaultLatitude, 
							        	    defaultLongitude: response.defaultLongitude ,
							        	    defaultCountryCode: response.defaultCountryCode, 
							        	    defaultCountryName: response.defaultCountryName, 
							        	    defaultLocation: response.defaultLocation,
							        	    defaultCityName: response.defaultCityName

		        						};

		        	$rootScope.locationData = {
			        	topCode : response.countryCode,
				        mainCity : response.cityName,
				        mainCountry : response.countryName,
				        mainLatitude : response.latitude,
				        mainLongitude : response.longtitude,
				        defaultLocation : response.defaultLocation
			        };

			        if($rootScope.inSearchLocation == undefined){
						$rootScope.inSearchLocation = response.defaultLocation;
					}

		        }
			})

		};

		//details for contact us page
		$rootScope.details = {
					company_name: "",
					email: "",
					location: "",
					country:"",
					city:"",
					web_site: "",
					message: ""
				};
		//unknown location mapadd 
		$rootScope.unknownLocationMapAdd = function() {
			var input = document.getElementById('unknown-location-input');

			$('#unknown-location-input').keydown(
			    function(event){
			      
			     if (event.which == '13') {
			        event.preventDefault();
			        var $form = $(this).closest('form');
			      }
			});
			
			if (!input) {
				return;
			}
			var options = {
				  types: ['(cities)'],
				 };
			$scope.searchBox = new google.maps.places.Autocomplete(input, options);
			google.maps.event.addListener($scope.searchBox, 'place_changed', function() {
				var places = $scope.searchBox.getPlace();
		                $scope.$apply();
				if (places == undefined || places.length == 0) {
				    return;
				  }
				var row;
				var geocoder = new google.maps.Geocoder();

			    geocoder.geocode({
			        "address": input.value
			    },function(results){
			        raw = results;
			        for (var i=0; i < results[0].address_components.length; i++) {
			          for (var j=0; j < results[0].address_components[i].types.length; j++) {

			        	//find country name
			            if (results[0].address_components[i].types[j] == "country") {
			            	var latitude = results[0].geometry.location.lat();
				            var longitude = results[0].geometry.location.lng();
				            var countryCode = results[0].address_components[i].short_name;
				            var countryName = results[0].address_components[i].long_name;
			            }

			            //find city name
			            if(results[0].address_components[i].types[j] == "locality") {
			            	var cityName = 	results[0].address_components[i].long_name;
			            }  
			          }
			          $rootScope.defaultLocation = {
			        		defaultLatitude: latitude, 
			        	    defaultLongitude: longitude ,
			        	    defaultCountryCode: angular.lowercase(countryCode), 
			        	    defaultCountryName: countryName, 
			        	    defaultLocation: cityName+', '+countryName,
			        	    defaultCityName: cityName

						};

			        }
			        $stateParams.lang = angular.lowercase(countryCode); 
					setTimeout(function() {
			        	$state.go('/', $stateParams);
			        }, 200);
			    }); 
			});
		}

		//mapadd function
		$scope.mapAdd = function() {
			var input = document.getElementById('pac-input');
			$('#pac-input').keydown(
			    function(event){
			      
			     if (event.which == '13') {
			        event.preventDefault();
			        var $form = $(this).closest('form');
			      }
			      
			});
			
			if (!input) {
				return;
			}
			// var defaultBounds = new google.maps.LatLngBounds(
			//   new google.maps.LatLng(-33.8902, 151.1759),
			//   new google.maps.LatLng(-33.8474, 151.2631));
			// if (navigator.geolocation) {
	  //         navigator.geolocation.getCurrentPosition(function(position) {
	  //           var geolocation = {
	  //             lat: position.coords.latitude,
	  //             lng: position.coords.longitude
	  //           };
	  //           var circle = new google.maps.Circle({
	  //             center: geolocation,
	  //             radius: position.coords.accuracy
	  //           });
	            
	  //         });
	  //       }
	  	
			var circle = new google.maps.Circle({ radius: 40233.6, center: {lat: parseFloat($rootScope.locationData.mainLatitude), lng: parseFloat($rootScope.locationData.mainLongitude)} });
			var options = {
				  types: ['(cities)'],
				  bounds: circle.getBounds()
				 };
			$scope.searchBox = new google.maps.places.Autocomplete(input, options);
			google.maps.event.addListener($scope.searchBox, 'place_changed', function() {
				var places = $scope.searchBox.getPlace();
		                $scope.$apply();
				if (places == undefined || places.length == 0) {
				    return;
				  }
				var row;
				var geocoder = new google.maps.Geocoder();

				// blabla
				$rootScope.user.location = input.value;
				$rootScope.details.location = input.value;

				// function geolocate() {
			 //        if (navigator.geolocation) {
			 //          navigator.geolocation.getCurrentPosition(function(position) {
			 //            var geolocation = {
			 //              lat: position.coords.latitude,
			 //              lng: position.coords.longitude
			 //            };
			 //            var circle = new google.maps.Circle({
			 //              center: geolocation,
			 //              radius: position.coords.accuracy
			 //            });
			 //            autocomplete.setBounds(circle.getBounds());
			 //          });
			 //        }
			 //      }

			    geocoder.geocode({
			        "address": input.value
			    },function(results){
			        raw = results;
			        for (var i=0; i < results[0].address_components.length; i++) {
			          for (var j=0; j < results[0].address_components[i].types.length; j++) {
			        	//find country name
			            if (results[0].address_components[i].types[j] == "country") {
			            	var latitude = results[0].geometry.location.lat();
				            var longitude = results[0].geometry.location.lng();
				            var countryCode = results[0].address_components[i].short_name;
				            var countryName = results[0].address_components[i].long_name;
			            }

			            //find city name
			            if(results[0].address_components[i].types[j] == "locality") {
			            	var cityName = 	results[0].address_components[i].long_name;

			            }
			          }
			        }

	        	    // location details	
			        if($('#pac-input').hasClass('search-location')){
			        	$rootScope.searchLocation = input.value;
						$rootScope.inSearchLocation = input.value;
						$rootScope.locationField = input.value;
			        	$rootScope.latitude = latitude;
		                $rootScope.longitude = longitude;
		                $rootScope.countryCode = countryCode;
		                $rootScope.countryName = countryName;
		                $rootScope.cityName = cityName;
			        }
	                
  
	                // country name for contact us
	                $rootScope.details.country = countryName;
	                $rootScope.details.city = cityName;
  
	                // user location
	                $rootScope.user.city_name = cityName;
	                $rootScope.user.country_name = countryName;
	                $rootScope.user.country = countryName;
	                $rootScope.user.latitude = latitude;
	                $rootScope.user.longitude = longitude;
	                
	                // set country language
	                Restangular.one('/country-language').get({countryCode:countryCode}).then(function(response) {
			 			var locale = response.language;
			 			angular.forEach($rootScope.languages, function(language){
	                	if (language.code == angular.lowercase(locale)) {
		                		$rootScope.doYouSpeak = language.native;
		                		$rootScope.user.doYouSpeakLang = language.native;
		                		// $rootScope.$apply();
		                	}
		                })
			 		})
	                
  
	                // set phone code of country
	                Restangular.one("/phone-code").get({country_code:countryCode}).then(function(response) {
	                	$rootScope.user.phone = response.phone_code;
				      	$rootScope.phone_code = response.phone_code;
	                });

			    }); 
			});
		}

		// search by location (and keyword ?)
		$scope.searchByLocation = function() {
			// get value of location input
			var input = document.getElementById('pac-input');

			var locationValue = input.value;
			if(!locationValue){
				$rootScope.countryName = undefined;
				$rootScope.latitude = undefined;
				$rootScope.longitude = undefined;
				$rootScope.countryCode = undefined;
				$rootScope.cityName = undefined;
				$rootScope.type = undefined;
				$rootScope.searchLocation = undefined;
				$rootScope.inSearchLocation =  $rootScope.defaultLocation.defaultCityName+', '+$rootScope.defaultLocation.defaultCountryName;
			}
			var type;
			// if(!$rootScope.inSearchLocation)
			// if no location is set for search, get user location
			if(!$rootScope.countryName){
				$rootScope.countryName = $rootScope.defaultLocation.defaultCountryName;
				$rootScope.latitude = $rootScope.defaultLocation.defaultLatitude;
				$rootScope.longitude = $rootScope.defaultLocation.defaultLongitude;
				$rootScope.countryCode = $rootScope.defaultLocation.defaultCountryCode;
				$rootScope.cityName = $rootScope.defaultLocation.defaultCityName;
				if($rootScope.searchLocation === undefined && $rootScope.inSearchLocation == undefined){
					$rootScope.inSearchLocation =  $rootScope.defaultLocation.defaultCityName+', '+$rootScope.defaultLocation.defaultCountryName;
				}
			} else if(($stateParams.lang !== angular.lowercase($rootScope.defaultLocation.defaultCountryCode)) && $rootScope.defaultLocation.defaultCountryCode != undefined) {
				$rootScope.countryName = $rootScope.defaultLocation.defaultCountryName;
				$rootScope.latitude = $rootScope.defaultLocation.defaultLatitude;
				$rootScope.longitude = $rootScope.defaultLocation.defaultLongitude;
				$rootScope.countryCode = $rootScope.defaultLocation.defaultCountryCode;
				$rootScope.cityName = $rootScope.defaultLocation.defaultCityName;

			} else {
				$rootScope.countryName = $rootScope.countryName;
				$rootScope.latitude = $rootScope.latitude;
				$rootScope.longitude = $rootScope.longitude;
				$rootScope.countryCode = $rootScope.countryCode;
				$rootScope.cityName = $rootScope.cityName;
				if(!$rootScope.searchLocation){
					if($rootScope.cityName && $rootScope.countryName) {
						$rootScope.inSearchLocation = $rootScope.cityName+', '+$rootScope.countryName;
					}
					else{
						$rootScope.inSearchLocation = $rootScope.defaultLocation.defaultCityName+', '+$rootScope.defaultLocation.defaultCountryName;
					}
				}else{
					$rootScope.inSearchLocation = $rootScope.searchLocation;
				}
				
			}
			// $rootScope.searchLocation = undefined;
			// set search type
			if($rootScope.cityName !== undefined && $rootScope.cityName !== "") {
				type =  "city"; 
			} else if ($rootScope.countryCode != undefined && $rootScope.countryCode != "") {
				type =  "country"; 
			}

			// if no location and no keyword for search, return eptty parameters error
			if(!input.value && !$rootScope.search_text){
				
					$('.empty-error').show();
					$scope.emptyError = true;
					setTimeout(function() { 
			            	$scope.emptyError = false;

			            	$('.empty-error').hide('slow');
			            	
			             }, 1500);
					
				
			}else{
				if((input.className.indexOf('pac-input-results') == -1) && !input.value) {
					$scope.emptyError = true;
						setTimeout(function() { 
				            	$scope.emptyError = false;

				            	$('.empty-error').hide('slow');
				            	
				             }, 1500);
				}else {
						$scope.searchParams = {
										activationAvailable: true, 
										type:type, 
										// param:param,
										latitude: $rootScope.latitude, 
										longitude: $rootScope.longitude, 
										count:0, 
										country_short_name: $rootScope.countryCode ? $rootScope.countryCode : $stateParams.lang, 
										country_name: $rootScope.countryName, 
										cityName: $rootScope.cityName, 
										search_text: $rootScope.search_text, 
										showParam: $rootScope.search_text, 
										url:'/search', 
										keyword: "",
										lang: $rootScope.countryCode? angular.lowercase($rootScope.countryCode) : $stateParams.lang
									};
					return Restangular.one('/search').get($scope.searchParams).then(function(resp) {
						if(resp.status == 'company') {
				        	if(resp.company_type == 'generic') {
				        		$state.go('company', {companyName:resp.company.name});
				        	}else if (resp.company_type == 'city_subsidiary') {
				        		Restangular.one('/company-subsidiary-info').get({companyId:resp.company.id}).then(function(response) {
									var companyCountryName = response.countryName;
									var companyCityName = response.cityName;
									$state.go('company-sub-city', {companyName:resp.company.name, countryName:companyCountryName, cityName:companyCityName});
								})
				        	} else if (resp.company_type == 'country_subsidiary') {
				        		Restangular.one('/company-subsidiary-info').get({companyId:resp.company.id}).then(function(response) {
									var companyCountryName = response.countryName;
									$state.go('company-sub', {companyName:resp.company.name, countryName:companyCountryName});
								})
				        	}
							
						}else {
							$state.go("search-results",  $scope.searchParams);
						}
					});
				}
				
			}
			
		}

		// browse jobs/gigs
		$scope.browseJobsGigs = function() {
			$rootScope.inSearchLocation = $rootScope.defaultLocation.defaultCityName+', '+($rootScope.countryName ? $rootScope.countryName : $rootScope.defaultLocation.defaultCountryName)	
			$rootScope.search_text = undefined;	
			$rootScope.cityName = undefined;
			$rootScope.countryName	 = undefined;
			$rootScope.searchLocation = undefined;
			$scope.searchParams = {
								count:0, 
								activationAvailable:true, 
								keyword:"",
								latitude: $rootScope.defaultLocation.defaultLatitude,
								longitude: $rootScope.defaultLocation.defaultLongitude,
								countryCode: $rootScope.defaultLocation.defaultCountryCode
							};
			$state.go("gigs",  $scope.searchParams);
		}

		// top locations
		$scope.searchByTopLocation = function(cityName, lat, long, countryCode) {
			$rootScope.inSearchLocation = cityName+', '+($rootScope.countryName ? $rootScope.countryName : $rootScope.defaultLocation.defaultCountryName)
			$rootScope.searchLocation = cityName+', '+($rootScope.countryName ? $rootScope.countryName : $rootScope.defaultLocation.defaultCountryName)
			$rootScope.latitude = lat;
			$rootScope.longitude = long;
			$rootScope.countryName = $rootScope.countryName ? $rootScope.countryName : $rootScope.defaultLocation.defaultCountryName;
			$rootScope.cityName = cityName;
			$rootScope.searchLocation = undefined;
			$rootScope.search_text = undefined;
			$scope.searchParams = {
					activationAvailable: true,  
					type:'city', 
					latitude:lat, 
	 				longitude: long,
					count:0, 
					country_short_name: countryCode ? countryCode : $rootScope.defaultLocation.defaultCountryCode,
					country_name:  $rootScope.countryName ? $rootScope.countryName : $rootScope.defaultLocation.defaultCountryName, 
					cityName: cityName,  
					url:'/search', 
					keyword: "",
					search_text:undefined
				};
			$state.go("search-results", $scope.searchParams);
		
		}

		// top sectors
		$scope.topSectors = function(id, sector)
		{
			$rootScope.inSearchLocation = ($rootScope.defaultLocation.defaultCityName)+', '+($rootScope.countryName ? $rootScope.countryName : $rootScope.defaultLocation.defaultCountryName);
			$rootScope.search_text = undefined;	
			$rootScope.cityName = undefined;
			$rootScope.countryName	 = undefined;
			$rootScope.searchLocation = undefined;
			$rootScope.showParam = sector;
			newsector =  sector.replace('-','');
			var reg = new RegExp("[ ]+","g");
	    	newsector =  newsector.replace(reg,"-");
			$scope.searchParams = {
									activationAvailable:true, 
									param: angular.lowercase(newsector),
									count:0, 
									countryCode:$rootScope.defaultLocation.defaultCountryCode, 
									//country_name:$rootScope.defaultLocation.defaultCountryName, 
									latitude: $rootScope.defaultLocation.defaultLatitude, 
									longitude:$rootScope.defaultLocation.defaultLongitude,
									showParam:$rootScope.showParam,
									id:id
								};
			$state.go('top-sector', $scope.searchParams);
		}

		// popular categories
		$scope.popularCategories = function(id, category, section)
		{
			// console.log(id)
			$rootScope.inSearchLocation = ($rootScope.defaultLocation.defaultCityName)+', '+($rootScope.countryName ? $rootScope.countryName : $rootScope.defaultLocation.defaultCountryName);
			$rootScope.search_text = undefined;
			$rootScope.cityName = undefined;
			$rootScope.countryName	 = undefined;
			$rootScope.searchLocation = undefined;
			$rootScope.showParam = category;
			newcategory =  category.replace('-','');
			var reg = new RegExp("[ ]+","g");
			newcategory =  newcategory.replace(reg,"-");
			$scope.searchParams = {
									activationAvailable:true, 
									param: angular.lowercase(newcategory),
									count:0, 
									countryCode:$rootScope.defaultLocation.defaultCountryCode, 
									//country_name:$rootScope.defaultLocation.defaultCountryName, 
									latitude: $rootScope.defaultLocation.defaultLatitude, 
									longitude: $rootScope.defaultLocation.defaultLongitude,
									showParam:$rootScope.showParam, 
									id:id
								};
			
			$state.go('top-category', $scope.searchParams);

		}

		// VIEW COMPANY DETAILS
		$scope.goToCompanyPage = function(companyName, companyType, companySubType, companyId)
		{
			if(companyType == 'generic') {
				$scope.params = {companyName:companyName, countryName:$rootScope.defaultLocation.defaultCountryName};
				// $state.go('company', );
			}else if(companyType == 'subsidiary') {
				if(companySubType == 'country_subsidiary') {
					Restangular.one('/company-subsidiary-info').get({companyId:companyId, lang: $stateParams.lang}).then(function(response) {
						// var companyCountryName = response.countryName;
						var companyCategory = response.category;
						var companyCategoryName = response.category.name;
						if(companyCategoryName) {
							companyCategoryName = companyCategoryName.replace('-', '');
							console.log(companyCategoryName)
							var reg = new RegExp("[ ]+","g");
	    					newcategory =  companyCategoryName.replace(reg,"-");
						}else {
							companyCategoryName = companyCategory.defaultCategory.name;
							companyCategoryName = companyCategoryName.replace('-', '');
							var reg = new RegExp("[ ]+","g");
	    					newcategory =  companyCategoryName.replace(reg,"-");
						}
						newcategory = angular.lowercase(newcategory);
						$state.go('company-sub', {companyName: companyName, categoryName:newcategory});
					})
					
				}else if(companySubType == 'city_subsidiary') {
					Restangular.one('/company-subsidiary-info').get({companyId:companyId}).then(function(response) {
						var companyCountryName = response.countryName;
						var companyCityName = response.cityName;
						$state.go('company-sub-city', {companyName:companyName, countryName:companyCountryName, cityName:companyCityName});
					})
					
				}
			}
			
		}

		// if user has an account. close registration modal, go to login page
		$scope.haveAccount = function() {
			$('#myModal').modal('hide');
			$state.go('login');
		}

		$scope.myProfileTab = function(tab)
		{
			$rootScope.tab = tab;
			Cookies.set('profile-tab', $rootScope.tab, {expires: 60*60*24*10});
			var lang = Cookies.get('lang');
			$state.go('my-profile');
		}

		//logout funciton
		$scope.logout = function() {
			$scope.token = Cookies.get('token');
			Restangular.all('/logout').post().then(function(data) {
				$('.user-email').removeAttr('readonly');
				$rootScope.user = {};
				Cookies.set('token', '', {expires: 60*60*24*10});
				Cookies.set('user', '', {expires: 60*60*24*10});
				Cookies.set('user_id', '', {expires: 60*60*24*10});
				$rootScope.loggedUser = Cookies.get('user');
				angular.element('#menu-modal').modal('hide');
				$state.go('alternative');
			})
		}
		
		//facebook login and registration
		$scope.facebookReg = function() {
			FB.login(function(response){
			  if (response.status === 'connected') {
				FB.api('/me?fields=id,first_name,last_name,email,picture', function(response) {

				    if(response.email != '' && response.email != undefined)
				    {
				    	$('.email').attr('disabled', false);
				    	
				    } else {
				    	$('.email').attr('disabled', true);
				    }
				    var data = {}
				    if(response.picture) {
						data.image = true;
					}
				    data.first_name = response.first_name;
				    data.last_name = response.last_name;
				    data.facebook_id = response.id;
				    data.registeredByFacebook = true;
				    $rootScope.registeredByFacebook = true;
				    Cookies.set('registeredByFacebook', 'true', {expires: 60*60*24*10});
				    console.log(Cookies.get('registeredByFacebook'))
				    $rootScope.user.registeredByFacebook = true;
				    data.email = response.email;

				    if(response.email != '' && response.email != undefined)
	                {
	                    $('.user-email').attr('readonly', 'readonly');
	                    var fbRegisterForm = new FormData();
						fbRegisterForm.append('user', JSON.stringify(data));
			    		$http.post(baseUrl + '/registration-first-step', fbRegisterForm, {
			                transformRequest: angular.identity,
			                headers: {'Content-Type': undefined}
			            }
						).then(function(data){
							if(data.data.error == 'Email sending failed') {
								$rootScope.emailSendingFailed = true;
							} else if(data.data.errors) {
								angular.forEach(data.data.errors, function(value, key) {
									angular.forEach(value, function(value1, key1) {
										if(key == 'password') {
											if(value1 == 'The password field is required.') {
												$scope.password_error = $translateFilter('password_required');
											}else {
												$scope.password_error = $translateFilter('password_min_6');
											}
											
										} else if(key == 'first_name') {
											if(value1 == 'The first name field is required.') {
												$scope.first_name_error = $translateFilter('first_name_required');
											}else {
												$scope.first_name_error = $translateFilter('first_name_invalid');
											}
											
										}else if(key == 'last_name') {
											if(value1 == 'The last name field is required.') {
												$scope.last_name_error = $translateFilter('last_name_required');
											}else {
												$scope.last_name_error = $translateFilter('last_name_invalid');
											}
										} else if(key == 'email') {
											if(value1 == 'The email field is required.') {
												$scope.email_error = $translateFilter('email_required');
											}else {
												$scope.email_error = $translateFilter('invalid_email');
											}
											
										} 
									})
									
								})
							} else if(data.data.error == 'user exists'){
								// $scope.exists_error = true;
								$scope.facebook = {};
								$scope.facebook.facebook_id = response.id;
							    $scope.facebook.email = response.email;
							    $http({
									url: baseUrl + '/facebook-login',
									method: 'POST',
									params: $scope.facebook
								}).then(function(data) {
									if(data.data.error == 'fbsuspended') {
										$scope.fbsuspended = true;
										$('.suspended').show();
										setTimeout(function() { 
							            	$scope.fbsuspended = false;
							            	$('.fbsuspended').hide('slow');
		            						$('.fbsuspended').removeClass('hide');
							             }, 1300);
									}else{
										Cookies.set('token', data.data.token.token, {expires: 60*60*24*10});
										Cookies.set('user', data.data.user.first_name, {expires: 60*60*24*10});
										Cookies.set('user_id', data.data.user.id, {expires: 60*60*24*10});
										$rootScope.loggedUser = Cookies.get('user');
										if($stateParams.redirectUrl){
											window.location = $stateParams.redirectUrl;
										}else{
											$state.go('/');
										}
										$('#myModal').modal('hide');
									}

								})
								
							} else {
								$rootScope.facebook = {};
								$rootScope.facebook.facebook_id = response.id;
								$rootScope.facebook.email = response.email;
								Cookies.set('registered_user_email', data.data.user.email, {expires: 60*60*24*10});
								$('#myModal').modal('hide');
								$state.go('second-step', {id:data.data.user.id});
							}
						})
	                }
				});

			  } else if(response.status === 'not_authorized') {
			  } else {
			  }
			}, {scope: 'public_profile,email'});
		}

		//set default value of rootscope.user
		if ($rootScope.user == undefined || !$rootScope.user) {
			$rootScope.user = {
				car: false,
				truck: false,
				bike: false,
				scooter: false,
				school:false,
				undergraduate:false,
				graduate:false,
				driving_license:false,
				currently_student:false,
				monday:false,
				tuesday:false,
				wednesday:false,
				thursday:false,
				friday:false,
				saturday:false,
				sunday:false,
				morning:false,
				afternoon:false,
				evening:false,
				night:false,
				my_area:false,
				outside_my_area:false,
				remotely:false,
				speakLang:false,
				registeredByFacebook:false,
				full_time:false,
				part_time:false,
				both:false,
				read_terms:false
			}
		}

		//check if user with the given email exists or not
		$scope.checkEmail = function(){
			Restangular.one("/check-email").get({email:$rootScope.user.email}).then(function(response) {
				if(response.error !=  undefined) {
		    		$rootScope.userExists = true;
		    	} else{
					$rootScope.userExists = false;
		    	}
				
			});
		}

		//first step of registration
		$scope.firstStep = function() {
			if($rootScope.user)
			{
				$rootScope.pass_unmatch = false;
				if($rootScope.user.email && $rootScope.user.password) {
					$http({
				    	url: baseUrl + "/check-email",
				    	method: "GET",
				    	params: {email:$rootScope.user.email}
				    }).then(function(response) {
				    	if(response.data.error) {
				    		$rootScope.userExists = true;
				    	} else {
							var registerForm = new FormData();
							registerForm.append('user', JSON.stringify($rootScope.user));
				    		registerForm.append('lang', $stateParams.lang);
				    		$http.post(baseUrl + '/registration-first-step', registerForm, {
					                transformRequest: angular.identity,
					                headers: {'Content-Type': undefined}
					            }
							).then(function(data){
								if(data.data.error == 'Email sending failed') {
									$rootScope.emailSendingFailed = true;
								} else if(data.data.errors) {
									angular.forEach(data.data.errors, function(value, key) {
										angular.forEach(value, function(value1, key1) {
											if(key == 'password') {
												if(value1 == 'The password field is required.') {
													$scope.password_error = $translateFilter('password_required');
												}else {
													$scope.password_error = $translateFilter('password_min_6');
												}
												
											} else if(key == 'first_name') {
												if(value1 == 'The first name field is required.') {
													$scope.first_name_error = $translateFilter('first_name_required');
												}else {
													$scope.first_name_error = $translateFilter('first_name_invalid');
												}
												
											}else if(key == 'last_name') {
												if(value1 == 'The last name field is required.') {
													$scope.last_name_error = $translateFilter('last_name_required');
												}else {
													$scope.last_name_error = $translateFilter('last_name_invalid');
												}
											} else if(key == 'email') {
												if(value1 == 'The email field is required.') {
													$scope.email_error = $translateFilter('email_required');
												}else {
													$scope.email_error = $translateFilter('invalid_email');
												}
												
											} 
										})
										
									})
								} else {
									Cookies.set('registered_user_email', data.data.user.email, {expires: 60*60*24*10});
									$('#myModal').modal('hide');
									$state.go('second-step', {id:data.data.user.id});
								}
							})
				    	}
				    });	
				}
			}
		}

		

		$scope.howItWorks = function() {

			$state.go('/',{navigateScroll:true});

		}

		$scope.goToTos = function() {
			var url = $state.href('terms-of-service');
			window.open(url,'_blank');
		}

		$scope.aboutSignUp = function() {
			if(Cookies.get('token')) {
				$state.go('/');
			}else {
				$state.go('login');
			}
		}

		$scope.loginTryItNow = function() {
			var redirectUrl = $state.href('gigs', {}, {absolute: true});
			$state.go('login', {apply_login:false, redirectUrl:redirectUrl}); 
		}

		// set ready status
		
			$scope.status = 'ready';

		
		$scope.htmlReady();

	}]);

sharadoApp.controller('LoginController', ['$scope', '$http', '$rootScope', '$state', '$stateParams', 'Restangular',  function ($scope, $http, $rootScope, $state, $stateParams, Restangular) {
	$scope.token = Cookies.get('token');


	if ($stateParams.apply_login == true) {
		$scope.apply_login = true;
	}

	if ($stateParams.activated == "1") {
		$scope.login_activated = true;
	}
	
	$scope.login = function()
	{
		if($scope.login.email && $scope.login.password)
		{
			$http({
				url: baseUrl + '/login',
				method: 'POST',
				params: $scope.login
			}).then(function(data){
				if(data.data.error) {
					if(data.data.error == 'invalid_credentials') {
						$scope.invalid_credentials = true;
					} else if(data.data.error == 'could_not_create_token') {
						$scope.something_went_wrong = true;
					} else if(data.data.error == 'not_activated_user') {
						$scope.not_activated_user = true;
						$scope.not_activated_user_id = data.data.user_id;
					} else if(data.data.error == 'suspended') {
						$scope.suspended = true;
						$('.suspended').show();
						setTimeout(function() { 
						            	$scope.suspended = false;
						            	$('.suspended').hide('slow');
	            						$('.suspended').removeClass('hide');
						             }, 1300);
					}
				} else {
					Cookies.set('token', data.data.token.token, {expires: 60*60*24*10});
					Cookies.set('user', data.data.user.first_name, {expires: 60*60*24*10});
					Cookies.set('user_id', data.data.user.id, {expires: 60*60*24*10});
					$rootScope.loggedUser = Cookies.get('user');

					if($stateParams.redirectUrl){
						window.location = $stateParams.redirectUrl;
					}else{
						$state.go('/');
					}
						
				}
			})
		}
	}
	$scope.resendActivationEmail = function(id) {
		Restangular.one("/resend-activation-email").get({user_id:id, lang:$stateParams.lang}).then(function(response) {
			if(response.resend_status == 'success') {
				$scope.resend_status_success = true;
				$scope.not_activated_user = false;
				$scope.resend_status_error = false;
				$rootScope.emailSendingFailed = false;
			}else if(response.resend_status == 'user_is_already_active') {
				$scope.resend_status_error = true;
				$scope.resend_status_success = false;
				$scope.not_activated_user = false;
				$rootScope.emailSendingFailed = false;
			}else if(response.resend_status == 'Email sending failed') {
				$scope.resend_status_error = false;
				$scope.resend_status_success = false;
				$scope.not_activated_user = false;
				$rootScope.emailSendingFailed = true;

			}
		})
	}

	
}]);