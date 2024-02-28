sharadoApp.controller('CompanyController', 
	['$scope', '$http', '$translate', '$rootScope', 'Restangular', 'companyDetails', '$state', '$stateParams', '$location', 'top',
	function ($scope, $http, $translate, $rootScope, Restangular, companyDetails, $state, $stateParams, $location, top) {
		
		$rootScope.pageTitle = 'Sharado - ' + (companyDetails.company.category.name ? companyDetails.company.category.name : companyDetails.company.defaultCategory.name) + ' - ' + companyDetails.company.name;
		console.log($rootScope.pageTitle);
		$scope.applied = false;
		$scope.isClickEnabled = true;
		$scope.companyBreadcrumb = $stateParams.companyName;
		$scope.companyCountrySubBreadcrumb = $stateParams.companyName;
		$scope.companyCategoryName = companyDetails.company.category.name ? companyDetails.company.category.name : companyDetails.company.defaultCategory.name;
		$scope.companySectorName = companyDetails.company.sector.name ? companyDetails.company.sector.name : companyDetails.company.defaultSector.name;
		$scope.companyCitySubBreadcrumb = $stateParams.companyName + ' / ' + $stateParams.countryName + ' / ' + $stateParams.cityName;
		$scope.topCategories = top.categories;
		$scope.topSectorsValues = top.sectors;
		$scope.topLocations = top.locations;
		$scope.company = companyDetails.company;
		$scope.data = companyDetails;
		$rootScope.metaDescription = companyDetails.company.meta_description;
		$rootScope.metaKeywords = companyDetails.company.meta_keywords;
		if(companyDetails.company.jobs) {
			$rootScope.companyTab = 1;
		}
		var token = Cookies.get('token');

		if(token) {
			Restangular.one('/user-details').get().then(function(response) {
				$scope.userId = response.id;
				if(!$scope.userId) {
					$scope.userId = 0;
				}
			})
		}

		$scope.setTab = function(newTab){
	      $rootScope.companyTab = newTab;
	    };

	    $scope.isSet = function(tabNum){
	      return $rootScope.companyTab === tabNum;
	    };
		

		$scope.url = "";
		$scope.applyForJobCompany = function(id, url) {
				Restangular.one("/apply-job").get({job_id:id, user_id: $scope.userId}).then(function(response) {
					if(response.status == 'job_apply_success') {
						$('#apply'+id).hide();
						$('#appliedhidden'+id).attr('style', 'display:block;');
					}else if(response.status == 'not_logged_in') {
						var redirectUrl = $location.absUrl();
						$state.go('login', {apply_login:true, redirectUrl:redirectUrl}); 
					} else if (response.status == 'applied_for_job'){
						$scope.url = url;
						if(response.job_applying == 'form') {
							$('#apply'+id).hide();
							$('#appliedhidden'+id).attr('style', 'display:block');
						}
					}
					
				})
			}

		$scope.redirectToAppliedJob = function(){
			window.open($scope.url, '_blank');
		}

		$scope.changeSearchButtons = function() {
		 	$('.collapsing_search_button').hide();
	        $('.not_collapsing_search_button').show();
		 }

		if($(window).width() < 767) {
	        $('#notCollapsingBox').attr('class', 'collapse');
	        $('#notCollapsingBox').attr('id', 'collapsingBox');

	    }else {
	    	$('#collapsingBox').attr('class', '');
	        $('#collapsingBox').attr('id', 'notCollapsingBox');
	    }

	    $(window).on('resize', function() {
		    if($(window).width() < 767) {
		        $('#notCollapsingBox').attr('class', 'collapse');
		        $('#notCollapsingBox').attr('id', 'collapsingBox');

		    }else {
		    	$('#collapsingBox').attr('class', '');
		        $('#collapsingBox').attr('id', 'notCollapsingBox');
		    }
		})
		
}]);