sharadoApp.controller('SearchController', 
	['$scope', '$http', '$translate', '$rootScope',  'searchResult', 'Restangular', '$state', '$stateParams',  '$log', '$filter',

	function ($scope, $http,$translate, $rootScope, searchResult, Restangular, $state, $stateParams, $log, $filter) {
		var availableLanguageKeys = $translate.getAvailableLanguageKeys();
		if(availableLanguageKeys.indexOf($stateParams.lang) !== -1) {
			$translate.use($stateParams.lang);
		}else {
			$translate.use('en');
		}
		var $translateFilter = $filter('translate');
		$scope.breadcrumb = searchResult.breadcrumb;
		//$rootScope.breadcrumbs = breadcrumbs;
		$rootScope.search_text = searchResult.requestParams.search_text;
		$rootScope.metaDescription = 'sharado search';
		$rootScope.metaKeywords = 'sharado, search, top categories, top sectors, top location, jobs gigs';

		$rootScope.subscribtion = {
								email: "",
								country:  searchResult.requestParams.country_name ? searchResult.requestParams.country_name : searchResult.requestParams.mainCountry,
								countryCode:searchResult.requestParams.lang,
								city: searchResult.requestParams.cityName ? searchResult.requestParams.cityName : "",
								latitude: searchResult.requestParams.latitude,
								longtitude: searchResult.requestParams.longitude,
								keyword: searchResult.requestParams.search_text ? searchResult.requestParams.search_text : searchResult.requestParams.param ,
								showParam:searchResult.requestParams.showParam ? searchResult.requestParams.showParam : (searchResult.requestParams.search_text ? searchResult.requestParams.search_text : searchResult.requestParams.param ),
								category_id:searchResult.requestParams.category_id,
								sector_id:searchResult.requestParams.sector_id,
								authToken:Cookies.get('token'),
								activationAvailable:searchResult.requestParams.activationAvailable 
							};

	//$scope.breadcrumb = $stateParams.param;
	$scope.locationBreadcrumb = $stateParams.cityName;
	$scope.topGigsBreadcrumb = $stateParams.jobName;
	setTimeout(function() {
		if($stateParams.type == 'city') {
		if($stateParams.search_text) {
			$scope.searchByLocationBreadcrumb = '/ '+ $translateFilter('Search') + ' / ' + $translateFilter('search_results') +' "'+$stateParams.search_text+'"';
		}else {
			$scope.searchByLocationBreadcrumb = '/ '+ $translateFilter('Search') + ' / ' + $stateParams.country_name + ' / ' + $stateParams.cityName;
		}
		}else if($stateParams.type == 'country') {
			if($stateParams.search_text) {
				$scope.searchByLocationBreadcrumb = '/ '+ $translateFilter('Search') + ' / ' + $stateParams.country_name + ' / ' + $stateParams.search_text;
			}else {
				$scope.searchByLocationBreadcrumb = '/ '+ $translateFilter('Search') + ' / ' + $stateParams.country_name;
			}
		}else {
			$scope.searchByLocationBreadcrumb = '/ '+ $translateFilter('Search') + ' / ' + $stateParams.search_text;
		}
	}, 200);
	

	Restangular.one('/dashboard').get($stateParams).then(function(dashboardcontent){
		$rootScope.topCategories = dashboardcontent.categories;
		$rootScope.topSectors = dashboardcontent.sectors;
		$rootScope.topLocations = dashboardcontent.locations;
   });

	var token = Cookies.get('token');

	if(token) {
		Restangular.one('/user-details').get().then(function(response) {
			$scope.userId = response.id;
			if(!$scope.userId) {
				$scope.userId = 0;
			}
		})
	}

	$scope.getLaravelFunctionUrl = function() {
		switch ($state.current.name) {
			case 'top-sector':
				return '/top-sectors';
				
			case 'top-location':
				return '/search';
				
			case 'top-gigs':
				return '/top-gigs';
				
			case 'gigs':
				return '/browse-jobs-gigs';
				
			case 'top-category':
				return '/popular-categories';

			case 'search-results':
				return '/search';                
							
			default:

		}
	}

	$scope.$on('$stateChangeStart', function(event, toState, toParams, fromState, fromParams, options){
		setTimeout(function() {
			$scope.mapAdd();
		}, 200);
	   
	});

  // SEARCH RESULTS PAGINATION
  if($stateParams.page) {
  	$scope.currentPage = $stateParams.page;
  }else {
	$scope.currentPage = 1; 	
  }

  $scope.maxSize = 4;
  if(searchResult.companies) {
	$scope.totalItems = searchResult.pages_count;
	$scope.search_results = searchResult.companies;
  }

  $scope.setPage = function (pageNo) {
	$scope.currentPage = pageNo;
  };

  $scope.pageChanged = function() {
	$log.log('Page changed to: ' + $scope.currentPage);
		$stateParams.count = $scope.currentPage - 1;
		$stateParams.page = $scope.currentPage;
		Restangular.one($scope.getLaravelFunctionUrl()).get($stateParams).then( function(response) {
			console.log('inside rest')
			if(response.companies){
				$scope.search_results = response.companies;
				$scope.totalItems = response.pages_count;
				
						$('html,body').animate({
							scrollTop: 0
						}, 'slow');
					
				
			}
		});
  };

	Restangular.one('/get-subscribtion').get($rootScope.subscribtion).then(function(response){
			if(response.status == 'exists'){
				$scope.subscribtion_exists = true;
			}else{
				$scope.subscribtion_exists = false;
			}
		});

	$scope.subscribe = function(){
		Restangular.one('/subscribe-for-jobs').get($rootScope.subscribtion).then( function(response) {
			if(response.errors != undefined){
				$scope.errors = response.errors;
				angular.forEach(response.errors, function(value, key) {
					angular.forEach(value, function(value1, key1) {
						if(key == 'country') {
							$scope.country_error = $translate('country_required');
							
						} else if(key == 'code') {
							$scope.code_error = $translate('code_required')
							
						} else if(key == 'email') {
							if(value1 == 'The email field is required.') {
								$scope.email_error = $translate('email_required');
							}else {
								$scope.email_error = $translate('invalid_email');
							}
						} 
					})
				})
				setTimeout(function() { 
					$('.alert-danger').hide('slow');
					$scope.errors = {};
					$rootScope.subscribtion.email = "";
					$('.alert-danger').removeClass('hide');
				 }, 1300);
			}else if(response.successMessage != undefined){
				$scope.subscribtion.email = "";
				$scope.successMessage = $translateFilter('alert_success_message');
				setTimeout(function() { 
					$('.alert-success').hide('slow');
					$scope.successMessage = undefined;
					$('.alert-success').removeClass('hide');
				 }, 1300);
			}else if(response.existsMessage != undefined){
				$scope.existsMessage = $translateFilter('alerts_exists_message');
				setTimeout(function() { 
					$('.alert-danger').hide('slow');
					$scope.existsMessage = undefined;
					$rootScope.subscribtion.email = "";
					$('.alert-danger').removeClass('hide');
				 }, 1300);
			}
		});

	}
	
	$scope.url = "";
	$scope.applyForJobCompany = function(id, url) {
			Restangular.one("/apply-job").get({job_id:id, user_id: $scope.userId}).then(function(response) {
				if(response.status == 'job_apply_success') {
					$('#apply'+id).hide();
					$('#appliedhidden'+id).attr('style', 'display:block;');
				}else if(response.status == 'job_apply_redirect_success') {
					// window.location = url;
					$scope.url = url;
					window.open(url, '_blank');
				}else if(response.status == 'not_logged_in') {
					$state.go('login', {apply_login:true}); 
				} else if (response.status == 'applied_for_job'){
					$scope.url = url;
					if(response.job_applying == 'form') {
						$('#apply'+id).hide();
						$('#appliedhidden'+id).attr('style', 'display:block')
						$scope.applied = true;
					}else {
						$scope.applied = false;
						$('#apply'+id).attr('data-toggle', 'modal');
						$('#apply'+id).attr('data-target', '#applyModal');
						$('#applyModal').modal('show')
						$scope.isClickEnabled = false;
					}
				}
				
			})
		}

	$scope.redirectToAppliedJob = function(){
		window.open($scope.url, '_blank');
	}

	$scope.oldRequest = {};

	$scope.refreshSearch = function(){
		$scope.currentState = $state.current.name;
		$scope.oldRequest = searchResult.requestParams;
		if($scope.getLaravelFunctionUrl() == '/search'){
			$scope.oldRequest.search_text = $scope.new_search_text;
			$scope.oldRequest.keyword = $scope.new_search_text;
		}else{
			$scope.oldRequest.keyword = $scope.new_search_text;
		}
		$scope.oldRequest.showParam = $scope.new_search_text;
		Restangular.one($scope.getLaravelFunctionUrl()).get($scope.oldRequest).then( function(response) {
			$scope.search_results = response.companies
			$scope.search_results_jobs = response.jobs;
			$scope.pages_count = response.pages_count;
		});
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