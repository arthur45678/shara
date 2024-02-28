sharadoApp.controller('RegisterController', 
	['$scope', '$http', '$rootScope','$state', '$stateParams', 'Restangular','user','$filter', 'uibDateParser',
	function ($scope, $http, $rootScope,$state,$stateParams, Restangular, user, $filter, uibDateParser) {
    if($stateParams.lang == 'it') {
    	$scope.imageUrl = 'img/placeholder-person-it.jpg';
    }else {
    	$scope.imageUrl = 'img/placeholder-person.jpg';
    }
	//scroll to top when changing state
	$scope.$watch('currentState', function(newval, oldval) {
		$('html,body').animate({
	            scrollTop: 0
	        }, 200);
	}, true);

	//getting language from Cookies and call mapAdd function
	var lang = Cookies.get('lang');
	setTimeout(function() {
		$scope.mapAdd()
	}, 200);

	//if existing user continues his registration, get his data
	if (user) {
		$rootScope.user.first_name = user.first_name;
		$rootScope.user.last_name  = user.last_name;
		$rootScope.user.email 	   = user.email;
		$rootScope.user.image 	   = user.image;
		$scope.current_user_id     = user.id;
	}

	//disabling back button
	if ($stateParams.disableBackButton == true) {
		$scope.disableBackButton = true;
	}

	//if user continues his registration, decide the step where he left
	var step = user.step + 1;
	$scope.currentState = 'step'+step;
	$('.user-email').attr('readonly', 'readonly');

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
	    		if (response.data[i].native != newval)
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

	$rootScope.languages = [];
	$scope.languages_options = [];
	$http({
    	url: baseUrl + "/country-languages",
    	method: "GET",
    	params: {}
    }).then(function(response) {
    	for(i = 0; i < response.data.length; i++) {
	    	$rootScope.user.spokenLanguages = '';

	    	for(i = 0; i < response.data.length; i++) {
	    		$rootScope.languages.push(response.data[i]);
	    		$rootScope.selectedLanguages.forEach(function(value) {
	    			if(response.data[i].native != value) {
	    				$scope.languages_options.push(response.data[i].native);
	    			}

	    		})
	    		
	    	}
    	}
    });
    Restangular.one("/user-language").get($stateParams).then(function(response) {
    // 	$rootScope.doYouSpeak = response.language;
  		// $rootScope.user.doYouSpeakLang = response.language;
  		$rootScope.doYouSpeak = response.native;
  		$rootScope.user.doYouSpeakLang = response.native;
    })

    Restangular.one("/countries").get($stateParams).then(function(response) {

    	$rootScope.countries = response.countries;
    })
	//setting default params for user
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
			driving_license_all:false,
			full_time:false,
			part_time:false,
			both:false,
			read_terms:false
		}
	}

	//image croppping
	$scope.processFiles = function(files){
		$('#cropModal').modal({
		    backdrop: 'static',
		    keyboard: false
		})
		$('#cropModal').modal('show');
		$('#cropModal').on('shown.bs.modal', function() {
	        
	        $scope.imageString = null;
	        $scope.a = null;
			$rootScope.outputImage = null;
			$rootScope.outputImageNew = null;
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

    //close image cropping modal
    $scope.hideModal = function() {
    	$('#cropModal').modal('hide');
   	    $scope.outputImageNew = $rootScope.outputImage;
    }

   //changing education bubbles
	$scope.educationChange = function(val) {
		if (val == 'school') {
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
		if ($rootScope.user.school == true) {
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
			$rootScope.user.part_time = false 
			$rootScope.user.both = false;
		} else if($rootScope.user.part_time == true) {
			$rootScope.user.full_time = false
			$rootScope.user.both = false;
		} else if($rootScope.user.both == true) {
			$rootScope.user.full_time = false
			$rootScope.user.part_time = false;
		}
	}

	//change transport bubbles
	// $scope.tranportationChange = function(){
	// 	$rootScope.user.driving_license_all = !$rootScope.user.driving_license_all;

	// 	if($rootScope.user.driving_license_all === true){
 //    		angular.element('#truck').addClass('active');
	// 		$rootScope.user.truck = true;

 //    		angular.element('#car').addClass('active');
	// 		$rootScope.user.car = true;

 //    		angular.element('#bike').addClass('active');
	// 		$rootScope.user.bike = true;

 //    		angular.element('#scooter').addClass('active');
	// 		$rootScope.user.scooter = true;
	//     } else {
	//     	angular.element('#truck').removeClass('active');
	// 		$rootScope.user.truck = false;

 //    		angular.element('#car').removeClass('active');
	// 		$rootScope.user.car = false;

 //    		angular.element('#bike').removeClass('active');
	// 		$rootScope.user.bike = false;

 //    		angular.element('#scooter').removeClass('active');
	// 		$rootScope.user.scooter = false;
	//     }
	// }

	//submitting second step
	window.formData = new FormData();
	$rootScope.obj = {};
	$scope.secondStep = function(form) {

		$scope.read_terms_error = false;
		if (form.$invalid || $rootScope.user.read_terms == false) {
			if(form.$invalid) {
				$('html,body').animate({
		            scrollTop: 200
		        }, 700);
			}
			
			if($rootScope.user.read_terms == false) {
				$scope.read_terms_error = true;
			}
		}else {
			$scope.customPhoneValidation = false;
			if($rootScope.user.phone == $scope.phone_code) {
				$scope.customPhoneValidation = true;
				return;			
			}

			if($rootScope.obj.flow.files[0]) {
				$rootScope.fd = {};
				$rootScope.fd[0] = $rootScope.obj.flow.files[0].file;
				
			}
			$rootScope.user.spokenLanguages = $rootScope.selectedLanguages;
			window.formData = new FormData();
			$rootScope.user.step = 2;
			formData.append('user', JSON.stringify($rootScope.user));
			if($rootScope.fd) {
				formData.append('image', $rootScope.fd[0]);
				formData.append('cropped', $scope.outputImageNew);
			}
			$http.post(baseUrl + '/registration', formData, {
	                transformRequest: angular.identity,
	                headers: {'Content-Type': undefined}
	            }
			).then(function(data){
				$rootScope.user.selectedList = "";
				if (data.data.errors) {
					angular.forEach(data.data.errors, function(value, key) {
						angular.forEach(value, function(value1, key1) {
							$('html,body').animate({
					            scrollTop: 200
					        }, 700);
						    if (key == 'city') {
								$scope.city_error = value1;
							}else if (key == 'phone_number') {
								$scope.phone_number_error = value1;
							} else if (key == 'first_name') {
								$scope.first_name_error = value1;
							}else if (key == 'last_name') {
								$scope.last_name_error = value1;
							} else if (key == 'education') {
								$scope.education_error = value1;
							} else if (key == 'birth_date') {
								$scope.birth_date = value1;
							}
						})
						
					})
				} else {
					$scope.currentState = 'step3';
				}
				
			})
		}			
	}

	//skills selecting handling
	$http({
    	url: baseUrl + "/categories",
    	method: "GET",
    	params: {lang:$stateParams.lang}
    }).then(function(response) {
    	
    	$scope.array = [];
        angular.forEach(response.data, function(value, key) {
        	var object = {
        		id:value.id,
        		name:value.name ? value.name : value.defaultCategory.name
        	}
        	$scope.array.push(object);
        })
        $scope.optionsList = $scope.array;
        $scope.categoriesList = angular.copy($scope.array);
    });
	
	$scope.selectedSkills = [];
	$scope.changeSkills = function(selected) {		
			angular.forEach($scope.optionsList, function(item){
		    if(item.id == selected){
			    $scope.optionsList.splice($scope.optionsList.indexOf(item), 1);
			    var object = {'id' : item.id, 'name' : item.name};
			    $scope.selectedSkills.push(object);
			    $scope.skillsRequired = false;
		    }   
		});
	}

	$scope.deleteSkill = function(skill) {
		$scope.optionsList.push(skill);
		$scope.selectedSkills.splice($scope.selectedSkills.indexOf(skill), 1);
	}

	//submitting he third step of registration
	$scope.thirdStep = function(form) {
		$scope.skillsRequired = false;
		if ($scope.selectedSkills.length == 0) {
			$scope.skillsRequired = true;
			$('html,body').animate({
		            scrollTop: 0
		        }, 700);
		} else {
			window.formData = new FormData();
			$rootScope.user.step = 3;
			$rootScope.user.selectedList = $scope.selectedSkills;
			formData.append('user', JSON.stringify($rootScope.user));
			$http.post(baseUrl + '/registration', formData, {
	                transformRequest: angular.identity,
	                headers: {'Content-Type': undefined}
	            }
			).then(function(data){
				$scope.currentState = 'step4';
			})
		}
	}

	//submitting the fourth step of registration
	$scope.fourthStep = function(form) {
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
		
		}else {
			window.formData = new FormData();
			$rootScope.user.step = 4;
			formData.append('user', JSON.stringify($rootScope.user));
			$http.post(baseUrl + '/registration', formData, {
	                transformRequest: angular.identity,
	                headers: {'Content-Type': undefined}
	            }
			).then(function(data){
				if($stateParams.redirectUrl) {
					window.location = $stateParams.redirectUrl;
				}else {
					if(!$rootScope.facebook) {
						$rootScope.facebook = {};
					}
					if(Cookies.get('registeredByFacebook') == 'true') {
						$rootScope.facebook.facebook_id = data.data.user.facebook_id;
					    $rootScope.facebook.email = data.data.user.email;
					    $http({
							url: baseUrl + '/facebook-login',
							method: 'POST',
							params: $rootScope.facebook
						}).then(function(data) {
							Cookies.set('token', data.data.token.token, {expires: 60*60*24*10});
							Cookies.set('user', data.data.user.first_name, {expires: 60*60*24*10});
							Cookies.set('user_id', data.data.user.id, {expires: 60*60*24*10});
							$rootScope.loggedUser = Cookies.get('user');
						})
						$rootScope.facebook_message = true;
						Cookies.set('registeredByFacebook', 'false', {expires: 60*60*24*10});
					}
					$scope.currentState = 'step5';
				}
				

			})
		}
	}
	
	//resending activation email
	$scope.resendActivationEmail = function(id) {
		Restangular.one("/resend-activation-email").get({user_id:id}).then(function(response) {
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

	if($stateParams.email && $stateParams.token) {
		$rootScope.resetEmail = $stateParams.email;
		$rootScope.resetToken = $stateParams.token;
	}

	// set ready status
	$scope.status = 'ready';
}]);