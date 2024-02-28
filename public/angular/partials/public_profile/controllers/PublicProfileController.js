sharadoApp.controller('PublicProfileController', 
	['$scope', '$http', '$translate', '$rootScope', 'Restangular', '$state', '$stateParams', 

	function ($scope, $http,$translate, $rootScope, Restangular, $state, $stateParams, $modalStack) {

		$rootScope.metaDescription = 'sharado public profile';
		$rootScope.metaKeywords = 'sharado, public profile';
	   Restangular.one('/public-profile').get($stateParams).then(function(response){

	   		$scope.not_exist = false;
	   		if(response.error) {
	   			$scope.not_exist = true;
	   		}else {
	   			$scope.first_name = response.user.first_name;
	   			$scope.last_name = response.user.last_name;
	   			$scope.gender = response.user.gender;
	   			$scope.age = response.user.age;
	   			$scope.city = response.user.city;
	   			$scope.experience = response.user.user_experience;
	   			$scope.schedule = response.user.schedule;
	   			$scope.daily = response.week_days;
	   			$scope.hourly = response.hours;
	   			$scope.areas = response.area;
	   			$scope.skills = response.user.skills;
	   			$scope.image = response.user.image;
	   			$rootScope.pageTitle = 'Sharado - ' + $scope.first_name + ' ' + $scope.last_name;
	   		}

	   });
	}]);