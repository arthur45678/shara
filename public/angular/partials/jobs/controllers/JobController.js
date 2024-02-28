sharadoApp.controller('JobController', 
	['$scope', '$http', '$translate', '$rootScope', 'Restangular', 'jobDetails', '$state', '$stateParams',

	function ($scope, $http, $translate, $rootScope, Restangular, jobDetails, $state, $stateParams) {

		$scope.applied = false;
		$scope.isClickEnabled = true;
		$scope.job = jobDetails.job;
		$scope.job_id = $scope.job.id;
		$scope.genericJobBreadcrumb = $stateParams.companyName + ' / ' + $scope.job.name;
		$scope.countrySubJobBreadcrumb = $stateParams.companyName + ' / ' + $stateParams.countryName + ' / ' + $scope.job.name;
		$scope.citySubJobBreadcrumb = $stateParams.companyName + ' / ' + $stateParams.countryName + ' / ' + $stateParams.cityName + ' / ' + $scope.job.name;
		var token = Cookies.get('token');
		if(token) {
			Restangular.one('/user-details').get().then(function(response) {
				$scope.userId = response.id;
				if(!$scope.userId) {
					$scope.userId = 0;
				}
				Restangular.one("/user-applied-job").get({job_id:$scope.job_id, user_id: $scope.userId}).then(function(response) {
					if(response.status == 'applied_for_job') {
						if(response.job_applying == 'form') {
							$('.job-applying').hide();
							$scope.applied = true;
						}else {
							$scope.applied = false;
							$('.job-applying').attr('data-toggle', 'modal');
							$('.job-applying').attr('data-target', '#applyModal');
							$scope.isClickEnabled = false;
						}
						
					}else if(response.status == 'not_applied_for_job') {
						$scope.isClickEnabled = true;
						$scope.applied = false;
					}
				 })
			})
		}
		
		
		

		$scope.applyForJob = function() {
							
			
				Restangular.one("/apply-job").get({job_id:$scope.job_id, user_id: $scope.userId}).then(function(response) {
					if(response.status == 'job_apply_success') {
						$scope.applied = true;
			    		$('.job-applying').hide();
					} else if (response.status == 'job_apply_redirect_success') {
						window.location = $scope.job.url_to_redirect;
					} else if (response.status == 'not_logged_in') {
						$state.go('login', {apply_login:true});
					} else if (response.status == 'applied_for_job'){
						if(response.job_applying == 'form') {
							$('.job-applying').hide();
							$scope.applied = true;
						}else {
							$scope.applied = false;
							$('.job-applying').attr('data-toggle', 'modal');
							$('.job-applying').attr('data-target', '#applyModal');
							$scope.isClickEnabled = false;
						}
					}
					
				})
					
			
		}

		$scope.redirectToAppliedJob = function() {
			window.location = $scope.job.url_to_redirect;
		}
}]);