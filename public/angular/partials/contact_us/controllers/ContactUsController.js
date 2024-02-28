sharadoApp.controller('ContactUsController', 
	['$scope', '$http', '$translate', '$rootScope', 'Restangular', '$state', '$stateParams', '$filter',

	function ($scope, $http, $translate, $rootScope, Restangular, $state, $stateParams, $filter) {
		var $translate = $filter('translate');
		$rootScope.metaDescription = 'sharado contact us';
		$rootScope.metaKeywords = 'sharado, contact us';
		Restangular.one('/dashboard').get($stateParams).then(function(response) {
			$scope.topCategories = response.categories;
			$scope.topSectors = response.sectors;
			$scope.topLocations = response.locations;

		})
		
		$scope.contact = function(){
			$scope.contacEmailSendingFailed = false;
			var data = {};

			if($rootScope.details != undefined){
				data = $rootScope.details;
			}
			Restangular.all("/contact-us").post({data:data}).then(function(response) {
				if(response.errors != undefined){
					$scope.errors = response.errors;
					angular.forEach(response.errors, function(value, key) {
						angular.forEach(value, function(value1, key1) {
							if(key == 'company_name') {
								$scope.company_name_error = $translate('company_name_required');
							}else if(key == 'email') {
								if(value1 == 'The email field is required.') {
									$scope.email_error = $translate('email_required');
								} else {
									$scope.email_error = $translate('invalid_email');
								}
							}else if(key == 'message') {
								$scope.message_error = $translate('message_required')
								
							}else if(key == 'name') {
								$scope.name_error = $translate('name_error')
							} else if(key == 'surname') {
								$scope.surname_error = $translate('surname_error')
							} else if(key == 'privacy') {
								$scope.privacy_error = $translate('privacy_error')
							}
						})
						
					})
					$('html, body').animate({scrollTop:0}, 'slow');
					setTimeout(function() { 
		            	$('.alert-danger').hide('slow');
		            	$scope.errors = {};
		            	$('.alert-danger').removeClass('hide');
		             }, 3000);

				}else if(response.error == 'Email sending failed'){
		            	$scope.contacEmailSendingFailed = true;

				}else{
					$scope.errors = {};
					$scope.details = {
								company_name: "",
								email: "",
								location: "",
								country:"",
								city:"",
								web_site: "",
								message: "",
								name:"",
								surname:"",
							};
					$scope.message = "Your request successfully sent.";
					$('html, body').animate({scrollTop:0}, 'slow');
					setTimeout(function() { 
		            	$('.alert-success').hide('slow');
		            	$scope.message = undefined;
		            	$('.alert-success').removeClass('hide');
		             }, 2500);
				}
			})
		}

	}]);