sharadoApp.controller('MyProfileController', ['$scope', '$http', '$rootScope', '$state', '$stateParams', 'Restangular', 'profileUser', 'countries', '$filter', function ($scope, $http, $rootScope, $state, $stateParams, Restangular, profileUser, countries, $filter) {
		$('.user-email').attr('readonly', 'readonly');
		$rootScope.doYouSpeak = null;

		var $translate = $filter('translate');
	    $scope.saveButtonFirst = $translate('save');
	    $scope.saveButtonSecond = $translate('save');
	    $scope.saveButtonThird = $translate('save');
	    $rootScope.metaDescription = 'sharado my profile';
		$rootScope.metaKeywords = 'sharado, my profile';

		if($state.includes('my-profile'))
		{
			$rootScope.tab = Cookies.get('profile-tab');
		}

		$scope.processFiles = function(files){
			$('#cropModal').modal({
			    backdrop: 'static',
			    keyboard: false
			})
			$('#cropModal').modal('show');
			$('#cropModal').on('shown.bs.modal', function() {
		        $scope.imageStrings = [];
				$rootScope.outputImage = null;
				$scope.imageString = null;
				$scope.inputImage = null;

		    	var fileReader = new FileReader();
	    	      fileReader.onload = function (event) {
	    	        $scope.$apply(function($scope){
	    	          var uri = event.target.result;
		              $scope.imageString = uri;
	    	        });
	    	      };

		          
		         fileReader.readAsDataURL(files[0].file);
		    })
	    };

	    $scope.hideModal = function() {
	    	$('#cropModal').modal('hide');
	   	    $scope.outputImageNew = $rootScope.outputImage;
	    }

	    //setting tab
	    $scope.setTab = function(newTab){
	      $rootScope.tab = newTab;
	      Cookies.set('profile-tab', $rootScope.tab, {expires: 60*60*24*10});
	    };

	    $scope.isSet = function(tabNum){
	      return $rootScope.tab == tabNum;
	    };
		//languages options
		$rootScope.languages = [];
		$scope.languages_options = [];
		$http({
	    	url: baseUrl + "/country-languages",
	    	method: "GET",
	    	params: {}
	    }).then(function(response) {
	    	$rootScope.user.spokenLanguages = profileUser.data.languages;
	    	if(profileUser.data.languages.length == 0) {
	    		$rootScope.user.spokenLanguages = '';
	    	}else {
	    		$rootScope.selectedLanguages = profileUser.data.languages;
	    	}

	    	for(i = 0; i < response.data.length; i++) {

	    		$rootScope.languages.push(response.data[i]);
	    		$scope.languages_options.push(response.data[i].native);
	    		if($rootScope.selectedLanguages) {
	    			$rootScope.selectedLanguages.forEach(function(value) {
	    			if(response.data[i].native == value) {
	    				$scope.languages_options.splice($scope.languages_options.indexOf(response.data[i].native), 1);
	    			}
	    		})
	    		}
	    		
	    		
	    	}

	    	
	    });

	    // Restangular.one("/countries").get($stateParams).then(function(response) {

	    // 	$rootScope.countries = response.countries;
	    // })
	    
	    //user data
	    var token = Cookies.get('token');
	    var lang = Cookies.get('lang');

		$rootScope.user.applications = profileUser.data.user.applications;
		$rootScope.user.selectedList = profileUser.data.user.skills;

		$scope.selectSkillLists = [];
		$rootScope.user.selectedList.forEach( function(element, index) {
			$scope.selectSkillLists.push(element.name);
		});

		$scope.chooseOptionList = function(id)
		{
			id = parseInt(id);
			$scope.optionsList.forEach( function(item) {
				if(item.id === id) {

					$rootScope.user.selectedList.push(item);
					$scope.skillsRequired = false;
				}
			});


		}

		$scope.checkInArray = function (array, id) {
            return (array.indexOf(id) > -1) ? true : false;
        };

        $scope.deleteSkill = function(id)
		{

			$rootScope.user.selectedList.forEach( function(item,i,array) {
				if(item.id === id) {

						array.splice(i,1);
				
				}
			});
			
		}
		angular.forEach($rootScope.user.selectedList, function(value, key) {
			if(value.name == '' || value.name  == undefined) {
				value.name = value.defaultCategory.name; 
			}
		})
		$http({
	    	url: baseUrl + "/categories",
	    	method: "GET",
	    	params: {lang:$stateParams.lang}
	    }).then(function(response) {
	    	var userSkills = [];
	    	if ($rootScope.user.selectedList) {
	    		angular.forEach($rootScope.user.selectedList, function(value, key) {
	    			userSkills.push(value.id);
	    		})
	    	}else {
	    		$rootScope.user.selectedList = [];
	    	}
	    	$scope.array = [];
	        angular.forEach(response.data, function(value, key) {
	        	
	        	if ($rootScope.user.selectedList) {
	            	if ($.inArray(value.id, userSkills) == -1) {
	            		var object = {
		            		id:value.id,
		            		name:value.name ? value.name : value.defaultCategory.name
		            	}
		            	$scope.array.push(object);
	            	}
	        	} else {
	        		var object = {
	            		id:value.id,
	            		name:value.name ? value.name : value.defaultCategory.name
	            	}
	            	$scope.array.push(object);
	        	}
	        })
	        $scope.optionsList = $scope.array;
	    });

		$rootScope.user.user_id = profileUser.data.user.id;
		$rootScope.user.first_name = profileUser.data.user.first_name;
		$rootScope.user.last_name = profileUser.data.user.last_name;
		$rootScope.user.email = profileUser.data.user.email;
		$rootScope.user.birth_date = profileUser.data.user.birth_date;
		$rootScope.user.location = profileUser.data.user.location;
		$rootScope.user.phone = profileUser.data.user.phone_number;
		$rootScope.user.transport = profileUser.data.user.transport;
		$rootScope.user.education = profileUser.data.user.education;
		$rootScope.user.week_days = profileUser.data.user.week_days;
		$rootScope.user.hours = profileUser.data.user.hours;
		$rootScope.user.working_area = profileUser.data.user.working_area;
		$rootScope.user.image = profileUser.data.user.image;
		$rootScope.user.currently_student = profileUser.data.user.currently_student;
		$rootScope.user.driving_license = profileUser.data.user.driving_license;
		$rootScope.user.country = profileUser.data.user.country;
		$rootScope.user.gender = profileUser.data.user.gender;
		$rootScope.user.age = profileUser.data.user.age;
		$rootScope.user.nationality = profileUser.data.user.nationality;
		$rootScope.user.user_experience = profileUser.data.user.user_experience;
		$rootScope.user.facebook_link = profileUser.data.user.facebook_link;
		$rootScope.countries = countries.countries;


		var transportArray = JSON.parse(profileUser.data.user.transport);
		if(transportArray) {
			transportArray.forEach(function(transport){
				$rootScope.user[transport] = transport ? true : false;
			});
		}
			
		var educationArray = JSON.parse(profileUser.data.user.education);
		if(educationArray) {
			educationArray.forEach(function(education) {
				$rootScope.user[ education.toLowerCase()] = education ? true : false;
			});
			
		}
		


		var weekArray = JSON.parse(profileUser.data.user.week_days);
		if(weekArray) {
			weekArray.forEach(function(week_days){
				$rootScope.user[week_days] = week_days ? true : false;
			});
		}
		
		var hoursArray = JSON.parse(profileUser.data.user.hours);
		if(hoursArray) {
			hoursArray.forEach(function(hours){
				$rootScope.user[hours] = hours ? true : false;
			});
		}
		
		var workingArray = JSON.parse(profileUser.data.user.working_area);
		if(workingArray) {
			workingArray.forEach(function(working_area){
				$rootScope.user[working_area.toLowerCase()] = working_area ? true : false;
			});
		}

		$rootScope.user.schedule = profileUser.data.user.schedule;

		// user subscibtions
		$rootScope.user.subscribtions = profileUser.data.user.subscribtions;

		if($rootScope.user.schedule) {
			if ($rootScope.user.schedule == 'full time') {
				$rootScope.user.full_time = true;
			} else if ($rootScope.user.schedule == 'part time') {
				$rootScope.user.part_time = true;
			} else if ($rootScope.user.schedule == 'both') {
				$rootScope.user.both = true;
			}
		}

		if($rootScope.user.subscribtions.length > 0)
		{
			$rootScope.user.subExists = true;
		}else{
			$rootScope.user.subExists = false;
		}

		if($rootScope.user.transport) {

	    	if( $rootScope.user.truck === true && $rootScope.user.car === true && $rootScope.user.bike === true && $rootScope.user.scooter === true ) {
	    		$rootScope.user.driving_license_all = true;
	    	}else{
	    		$rootScope.user.driving_license_all = false;
	    	}

		}

		//changing education bubbles
	    $scope.educationChange = function(val) {
			if(val == 'school') {
				$rootScope.user.school = !$rootScope.user.school;
				if ($rootScope.user.school == true) {
					$rootScope.user.graduate = false;
					$rootScope.user.undergraduate = false;
				}

			} else if(val == 'undergraduate') {
				$rootScope.user.undergraduate = !$rootScope.user.undergraduate;
				if ($rootScope.user.undergraduate == true) {
					$rootScope.user.school = false;
					$rootScope.user.graduate = false;
				}
			} else if(val == 'graduate') {
				$rootScope.user.graduate = !$rootScope.user.graduate;
				if ($rootScope.user.graduate == true) {
					$rootScope.user.school = false;
					$rootScope.user.undergraduate = false;
				}
			}

			if($rootScope.user.school == true) {
				$rootScope.user.undergraduate = $rootScope.user.graduate = false;
			} else if($rootScope.user.undergraduate == true) {
				$rootScope.user.school = $rootScope.user.graduate = false;
			} else if($rootScope.user.graduate == true) {
				$rootScope.user.school = $rootScope.user.undergraduate = false;
			}

		}

		//changing availability bubbles
		$scope.availabilityChange = function(val) {
			if (val == 'full_time') {
				$rootScope.user.full_time = !$rootScope.user.full_time;
				if ($rootScope.user.full_time == true) {
					$rootScope.user.both = false;
					$rootScope.user.part_time = false;
				}
			} else if(val == 'part_time') {
				$rootScope.user.part_time = !$rootScope.user.part_time;
				if ($rootScope.user.part_time == true) {
					$rootScope.user.full_time = false;
					$rootScope.user.both = false;
				}
			} else if(val == 'both') {
				$rootScope.user.both = !$rootScope.user.both;
				if ($rootScope.user.both == true) {
					$rootScope.user.full_time = false;
					$rootScope.user.part_time = false;
				}
			}
			if ($rootScope.user.full_time == true) {
				$rootScope.user.part_time = $rootScope.user.both = false;
			} else if($rootScope.user.part_time == true) {
				$rootScope.user.full_time = $rootScope.user.both = false;
			} else if($rootScope.user.both == true) {
				$rootScope.user.full_time = $rootScope.user.part_time = false;
			}

		}

		//saving the first tab changes
		$scope.saved_disable = false;
		$rootScope.obj = {};
		$scope.saveFirstTab = function(form)
		{
			if(form.$invalid) {
				$('html,body').animate({
			            scrollTop: 500
			        }, 700);
			}else {
				$scope.country_error = false;
				$scope.city_error = false;
				$scope.phone_number_error = false;
				$scope.first_name_error = false;
				$scope.last_name_error = false;
				$scope.education_error = false;
				$scope.birth_date_error = false;
				$scope.email_error = false;
				$scope.customPhoneValidation = false;
				if($rootScope.phone_code == '' || $rootScope.phone_code == undefined) {
					$http({
			    		url: baseUrl + "/phone-code-name",
			    		method: "GET",
			    		params: {country_name: $rootScope.user.country}
			    	}).then(function(response) {
				    	var phone_code = response.data.phone_code;
				    	if($rootScope.user.phone == phone_code) {
							$scope.customPhoneValidation = true;
							$('html,body').animate({
					            scrollTop: 500
					        }, 700);
							return;			
						} else {
							$scope.doneFirstStep();
						}
				    });
				} else {
					if($rootScope.user.phone == $rootScope.phone_code) {
						$scope.customPhoneValidation = true;
						$('html,body').animate({
					            scrollTop: 700
					        }, 700);
						return;			
					} else {
						$scope.doneFirstStep();
					}
				}
			}
			
		}

		//finishing the saving for the first tab
		$scope.doneFirstStep = function() {
			window.formData = new FormData();
			$rootScope.user.spokenLanguages = $rootScope.selectedLanguages;
			if($rootScope.obj.flow.files[0]) {
				$rootScope.fd = {};
				$rootScope.fd[0] = $rootScope.obj.flow.files[0].file;
				formData.append('image', $rootScope.fd[0]);
				formData.append('cropped', $scope.outputImageNew);
			}
			formData.append('user', JSON.stringify($rootScope.user))
			$http.post(baseUrl + '/edit-my-profile-first', formData, {
	                transformRequest: angular.identity,
	                headers: {'Content-Type': undefined}
	            }
			).then(function(data){
				if(data.data.errors) {
					$('html,body').animate({
			            scrollTop: 500
			        }, 700);
					angular.forEach(data.data.errors, function(value, key) {
						angular.forEach(value, function(value1, key1) {
							if(key == 'country') {
								$scope.country_error = $translate('country_required');
							}else if(key == 'city') {
								$scope.city_error = $translate('city_required');
							}else if(key == 'phone_number') {
								if(value1 == 'The phone number must be a number.') {
									$scope.phone_number_error = $translate('phone_must_be_number');
								}else {
									$scope.phone_number_error = $translate('phone_number_required');
								}
								
							} else if(key == 'first_name') {
								if(value1 == 'The first name format is invalid.') {
									$scope.first_name_error = $translate('first_name_invalid');
								}else {
									$scope.first_name_error = $translate('first_name_required');
								}
								
							}else if(key == 'last_name') {
								if(value1 == 'The last name format is invalid.') {
									$scope.last_name_error = $translate('last_name_invalid');
								}else {
									$scope.last_name_error = $translate('last_name_required');
								}
							} else if(key == 'birth_date') {
								$scope.birth_date_error = $translate('birth_date_required');
							}
						})
						
					})
				} else {
					$('html,body').animate({
			            scrollTop: 0
			        }, 700);
					Cookies.set('user', $rootScope.user.first_name, {expires: 60*60*24*10});
					$rootScope.loggedUser = $rootScope.user.first_name;
			        $scope.savedAccountInfo = true;

				}	
			})
		}
		
		//changing transportation bubbles
		$scope.tranportationChange = function(){
			if($rootScope.user.truck && $rootScope.user.car && $rootScope.user.bike && $rootScope.user.scooter){
				var param = true;
			}else{
				var param = false;
			}
			if(param === false){
	    		angular.element('#truck').addClass('active');
				$rootScope.user.truck = true;

	    		angular.element('#car').addClass('active');
				$rootScope.user.car = true;

	    		angular.element('#bike').addClass('active');
				$rootScope.user.bike = true;

	    		angular.element('#scooter').addClass('active');
				$rootScope.user.scooter = true;

				$rootScope.user.driving_license_all = true;
		    } else {
		    	angular.element('#truck').removeClass('active');
				$rootScope.user.truck = false;

	    		angular.element('#car').removeClass('active');
				$rootScope.user.car = false;

	    		angular.element('#bike').removeClass('active');
				$rootScope.user.bike = false;

	    		angular.element('#scooter').removeClass('active');
				$rootScope.user.scooter = false;

				$rootScope.user.driving_license_all = false;
		    }
			
		}

		//saving second tab
		$scope.saveSecondTab = function()
		{
			$scope.skillsRequired = false;
			if($rootScope.user.selectedList.length == 0) {
				$scope.skillsRequired = true;
				$('html,body').animate({
			            scrollTop: 0
			        }, 700);
			}else {
				window.formData = new FormData();
				if($rootScope.obj.flow.files[0]) {
					$rootScope.fd = {};
					$rootScope.fd[0] = $rootScope.obj.flow.files[0].file;
					formData.append('image', $rootScope.fd[0]);
					formData.append('cropped', $scope.outputImageNew);
				}
				formData.append('user', JSON.stringify($rootScope.user));
				$http.post(baseUrl + '/edit-my-profile-second', formData, {
		                transformRequest: angular.identity,
		                headers: {'Content-Type': undefined}
		            }
				).then(function(data){
					$('html,body').animate({
				            scrollTop: 0
				        }, 700);
					$scope.savedAccountInfo = true;
				})
			}			
		}

		//saving third tab
		$scope.saveThirdTab = function()
		{
			if(!$rootScope.user.part_time && !$rootScope.user.full_time && !$rootScope.user.both) {
				$scope.availabilityRequired = true;
			}else {
				$scope.availabilityRequired = false;
			}

			if(!$rootScope.user.monday && !$rootScope.user.tuesday && !$rootScope.user.wednesday && !$rootScope.user.thursday && !$rootScope.user.friday && !$rootScope.user.saturday && !$rootScope.user.sunday) {
				$scope.weekRequired = true;
			}else {
				$scope.weekRequired = false;
			}

			if(!$rootScope.user.morning && !$rootScope.user.afternoon && !$rootScope.user.evening && !$rootScope.user.night) {
				$scope.hoursRequired = true;
			}else {
				$scope.hoursRequired = false;
			}

			if(!$rootScope.user.my_area && !$rootScope.user.outside_my_area && !$rootScope.user.remotely) {
				$scope.areaRequired = true;
			}else {
				$scope.areaRequired = false;
			}
			
			if ($scope.availabilityRequired || $scope.weekRequired || $scope.hoursRequired || $scope.areaRequired) {
				$('html,body').animate({
			            scrollTop: 200
			        }, 700);
			}else{
				window.formData = new FormData();
				if($rootScope.obj.flow.files[0]) {
					$rootScope.fd = {};
					$rootScope.fd[0] = $rootScope.obj.flow.files[0].file;
					formData.append('image', $rootScope.fd[0]);
					formData.append('cropped', $scope.outputImageNew);
				}
				formData.append('user', JSON.stringify($rootScope.user))
				$http.post(baseUrl + '/edit-my-profile-third', formData, {
		                transformRequest: angular.identity,
		                headers: {'Content-Type': undefined}
		            }
				).then(function(data){
					$('html,body').animate({
				            scrollTop: 0
				        }, 700);
			        $scope.savedAccountInfo = true;
				})
				}

			
		}

		//language selecting handling
		$rootScope.$watch('doYouSpeak', function(newval, oldval) {
			if (newval ==  oldval) return;
			$http({
		    	url: baseUrl + "/country-languages",
		    	method: "GET",
		    	params: {}
		    }).then(function(response) {
		    	$rootScope.languages = [];
				$scope.languages_options = [];

		    	for(i = 0; i < response.data.length; i++) {
		    		if (response.data[i].language != newval)
		    		{
		    			$rootScope.languages.push(response.data[i]);
		    			$scope.languages_options.push(response.data[i].native)
		    		}
		    	}
		    });

		}, true);
		$rootScope.selectedLanguages = [];
		$scope.selectLanguage = function(selected) {
			angular.forEach($scope.languages_options, function(item){
			    if(item == selected){
			    	$scope.languages_options.splice($scope.languages_options.indexOf(item), 1);
			    	$rootScope.selectedLanguages.push(selected);
			    }   
			});
		}

		$scope.deleteLanguage = function(language) {
			$scope.languages_options.push(language);
			$rootScope.selectedLanguages.splice($rootScope.selectedLanguages.indexOf(language), 1);
		}


		$rootScope.$watch('user', function(newval, oldval) {
			if (newval ==  oldval) return;
			$scope.savedAccountInfo = false;
		}, true);


		$scope.download = function()
		{
			Restangular.one('/generate-token').get({}).then(function(response) {
				if(response.token !== undefined){
					var token = response.token;

					window.location.replace('/email/'+token);
				}
			})
		};

		$scope.removeAlert = function(key, alertId)
		{
			Restangular.one('/remove-alert/'+alertId).get({alertId}).then(function(response) {
				if(response.status == 'success'){
					$rootScope.user.subscribtions.splice(key, 1);

				}
			})
		}

		$scope.afterSelectItem = function(element) {
			if (element.hasClass('open')) {
                element.removeClass('open');
                $document.unbind('click', clickHandler);
                scope.$parent.$eval(scope.onBlur);
            }
		}

	// set ready status
	$scope.status = 'ready';
}]);
