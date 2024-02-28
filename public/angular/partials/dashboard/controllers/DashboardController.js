sharadoApp.controller('DashboardController', 
	['$scope', '$http', '$translate', '$rootScope', 'Restangular', '$state', '$stateParams', 

	function ($scope, $http,$translate, $rootScope, Restangular, $state, $stateParams, $modalStack) {
	   Restangular.one('/dashboard').get($stateParams).then(function(dashboardcontent){
	   		$rootScope.categories = dashboardcontent.categories;
        	$rootScope.homeCategories = dashboardcontent.homeCategories;
        	$rootScope.sectors = dashboardcontent.sectors;
        	$rootScope.locations = dashboardcontent.locations;
        	$rootScope.metaDescription = 'sharado, jobs gigs';
			$rootScope.metaKeywords = 'sharado, jobs gigs';

	   });
	   if($stateParams.navigateScroll) {
	   		$rootScope.navigateScrollTo('my_oblos');
	   		
    	}
	}]);