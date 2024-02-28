sharadoApp.controller('CategoryController', 
	['$scope', '$http', '$translate', '$rootScope', 'Restangular', '$state', '$stateParams', 'allcategories','top',

	function ($scope, $http,$translate, $rootScope, Restangular, $state, $stateParams, allcategories,top) {
		$scope.categoriesList = allcategories;
		$scope.topCategories = top.categories;
		$scope.topSectors = top.sectors;
		$scope.topLocations = top.locations;
		$rootScope.metaDescription = 'sharado all categories';
		$rootScope.metaKeywords = 'sharado, categories';
		angular.forEach($scope.categoriesList, function(value, key) { 
	    		if(value.name == '' || value.name  == undefined) {
	    			value.name = value.defaultCategory.name; 
	    		}
	    	})
		
		$scope.getCategory = function(id, category, sector)
		{
			$rootScope.showParam = category; 
			var reg = new RegExp("[ ]+","g");
	    	newcategory =  category.replace(reg,"");
			$scope.searchParams = {
									activationAvailable:true, 
									param: angular.lowercase(newcategory),
									count:0, 
									countryCode:$rootScope.defaultLocation.defaultCountryCode, 
									country_name:$rootScope.defaultLocation.defaultCountryName, 
									latitude: $rootScope.defaultLocation.defaultLatitude, 
									longitude: $rootScope.defaultLocation.defaultLongitude,
									showParam:$rootScope.showParam, 
									id:id
								};
			$state.go('top-category', $scope.searchParams);
		}
	}])