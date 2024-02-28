sharadoApp.config(function($urlRouterProvider, $stateProvider) {
	$stateProvider
	.state('app', {
	  abstract: true,
	  url: '/:lang?',
	  template: '<ui-view/>'
	})
	.state('/', {
		url: '/',
		templateUrl: 'dist/assets/html/home.html',
		controller: 'DashboardController',
		params:{navigateScroll:null},
		data:{title: 'Sharado'},
		resolve : {
			// dashboardContent: function(Restangular, $stateParams){
			// 	return Restangular.one('/dashboard').get($stateParams); 
		 //   	},
            data:function($q, $state, $timeout, $stateParams) {
                var deferred=$q.defer();
                var loggedIn=Cookies.get('token');
                $timeout(function() {
                    if(!loggedIn) {
                        $state.go('alternative', $stateParams);
                        deferred.reject();
                    }
                    else {
                        deferred.resolve();
                    }
                }
                );
                return deferred.promise;
            },
		   	deps: ['$ocLazyLoad', function($ocLazyLoad) {
			   return $ocLazyLoad.load({
				   name: 'sharadoApp',
				   files: [
				   		'angular/partials/dashboard/controllers/DashboardController.js',
				   ]                    
			   });
		   }]
	   	},
		ncyBreadcrumb: {
		    label: 'Home'
		  },
		parent: "app",
	})
	.state('alternative', {
        url:'/landing-page', 
        templateUrl:'dist/assets/html/alternative.html', 
        controller:'DashboardController',
        data: {title: 'Sharado'}, 
        params: {
            navigateScroll: null
        }
        , resolve: {
            deps:['$ocLazyLoad', function($ocLazyLoad) {
                return $ocLazyLoad.load( {
                    name: 'sharadoApp', files: ['angular/partials/dashboard/controllers/DashboardController.js', ]
                }
                );
            }
            ]
        }
        , ncyBreadcrumb: {
            label: 'Home'
        }
        , parent:"app",
    })
	.state('login', {
		url: '/login?activated?redirectUrl',
		templateUrl: 'dist/assets/html/login.html',
		controller: 'LoginController', 
		params: {apply_login: false},
		data: {title: 'Sharado - Login'},
		ncyBreadcrumb: {
			parent: '/',
		    label: 'Login'
		  },
		parent: "app"
	}).
	// when('/signup', {
	//     templateUrl: 'partials/signup.html', 
	//     controller: 'SignupController'
	// }).
	
	state ('second-step', {
		url: '/registration?id?redirectUrl',
		templateUrl: 'dist/assets/html/step2.html',
		controller: 'RegisterController',
		params:{disableBackButton:false},
		data:{title: 'Sharado - Registration'},
		resolve : {
		   	user: function(Restangular, $stateParams){
				return Restangular.one('/user-details-register').get($stateParams); 
		   	},
		   	deps: ['$ocLazyLoad', function($ocLazyLoad) {
			   return $ocLazyLoad.load({
				   name: 'sharadoApp',
				   files: [
				   		'angular/partials/registration/controllers/RegisterController.js',
					   "bower_components/amitava82-angular-multiselect/dist/multiselect-tpls.js",
				   ]                    
			   });
		   }]
	   	},
	   	ncyBreadcrumb: {
			parent: '/',
		    label: 'Registration'
		  },
		parent: "app"  
	}).
	// 	state('job-generic', {
	// 	url: '/company/:companyName/job/:jobId',
	// 	templateUrl: 'angular/partials/jobs/views/job_details.html',
	// 	controller: 'JobController',
	// 	resolve : {
	// 	   jobDetails: function(Restangular, $stateParams){
	// 			return Restangular.one('/job-details').get($stateParams); 
	// 	   },
	// 	   deps: ['$ocLazyLoad', function($ocLazyLoad) {
	// 		   return $ocLazyLoad.load({
	// 			   name: 'sharadoApp',
	// 			   files: [
	// 				   'angular/partials/jobs/controllers/JobController.js',
	// 			   ]                    
	// 		   });
	// 	   }]
	//    },
	//    ncyBreadcrumb: {
	// 		// parent: function($rootScope) {
	// 		// 	if($rootScope.previousStateName)
	// 		// 		return $rootScope.previousStateName + '(' + JSON.stringify($rootScope.previousStateParams) + ')';
	// 		// 	else
	// 		// 		return "/";
	// 	 //    },
	// 	 	parent: '/',
	// 	    label: '{{genericJobBreadcrumb}}'
	// 	  },
	// 	parent: "app" 
	// }).
	// state('job-country-sub', {
	// 	url: '/company/:companyName/:countryName/job/:jobId',
	// 	templateUrl: 'angular/partials/jobs/views/job_details.html',
	// 	controller: 'JobController',
	// 	resolve : {
	// 	   jobDetails: function(Restangular, $stateParams){
	// 			return Restangular.one('/job-details').get($stateParams); 
	// 	   },
	// 	   deps: ['$ocLazyLoad', function($ocLazyLoad) {
	// 		   return $ocLazyLoad.load({
	// 			   name: 'sharadoApp',
	// 			   files: [
	// 				   'angular/partials/jobs/controllers/JobController.js',
	// 			   ]                    
	// 		   });
	// 	   }]
	//    },
	//    ncyBreadcrumb: {
	// 		// parent: function($rootScope) {
	// 		// 	if($rootScope.previousStateName)
	// 		// 		return $rootScope.previousStateName + '(' + JSON.stringify($rootScope.previousStateParams) + ')';
	// 		// 	else
	// 		// 		return "/";
	// 	 //    },
	// 	 	parent: '/',
	// 	    label: '{{countrySubJobBreadcrumb}}'
	// 	  },
	// 	parent: "app"  
	// }).
	// state('job-city-sub', {
	// 	url: '/company/:companyName/:countryName/:cityName/job/:jobId',
	// 	templateUrl: 'angular/partials/jobs/views/job_details.html',
	// 	controller: 'JobController',
	// 	resolve : {
	// 	   jobDetails: function(Restangular, $stateParams){
	// 			return Restangular.one('/job-details').get($stateParams); 
	// 	   },
	// 	   deps: ['$ocLazyLoad', function($ocLazyLoad) {
	// 		   return $ocLazyLoad.load({
	// 			   name: 'sharadoApp',
	// 			   files: [
	// 				   'angular/partials/jobs/controllers/JobController.js',
	// 			   ]                    
	// 		   });
	// 	   }]
	//    },
	//    ncyBreadcrumb: {
	// 		// parent: function($rootScope) {
	// 		// 	if($rootScope.previousStateName)
	// 		// 		return $rootScope.previousStateName + '(' + JSON.stringify($rootScope.previousStateParams) + ')';
	// 		// 	else
	// 		// 		return "/";
	// 	 //    },
	// 	 	parent: '/',
	// 	    label: '{{citySubJobBreadcrumb}}'
	// 	  },
	// 	parent: "app"  
	// }).
	/*state('company', {
		url: '/company/:companyName',
		templateUrl: 'dist/assets/html/company_details.html',
		controller: 'CompanyController',
		resolve : {
		   companyDetails: function(Restangular, $stateParams) {
				return Restangular.one('/company-details').get($stateParams); 
		   },
		   deps: ['$ocLazyLoad', function($ocLazyLoad) {
			   return $ocLazyLoad.load({
				   name: 'sharadoApp',
				   files: [
					   'angular/partials/companies/controllers/CompanyController.js',

				   ]                    
			   });
		   }]
	   },
	   ncyBreadcrumb: {
			// parent: function($rootScope) {
			// 	if($rootScope.previousStateName)
			// 		return $rootScope.previousStateName + '(' + JSON.stringify($rootScope.previousStateParams) + ')';
			// 	else
			// 		return "/";
		 //    },
		 	parent: '/',
		    label:' / Company / {{companyBreadcrumb}}'
		  },
		parent: "app"  
	}).*/
	state('company-sub', {
		url: '/company/:categoryName/:companyName',
		templateUrl: 'dist/assets/html/company_details.html',
		controller: 'CompanyController',
		data: {title: 'Sharado'},
		resolve : {
		   top: function(Restangular, $stateParams) {
				return Restangular.one('/dashboard').get($stateParams);
		   },
		   companyDetails: function(Restangular, $stateParams) {
				return Restangular.one('/company-details').get($stateParams); 
		   },
		   deps: ['$ocLazyLoad', function($ocLazyLoad) {
			   return $ocLazyLoad.load({
				   name: 'sharadoApp',
				   files: [
					   'angular/partials/companies/controllers/CompanyController.js',

				   ]                    
			   });
		   }]
	   },
	   ncyBreadcrumb: {
			// parent: function($rootScope) {
			// 	if($rootScope.previousStateName)
			// 		return $rootScope.previousStateName + '(' + JSON.stringify($rootScope.previousStateParams) + ')';
			// 	else
			// 		return "/";
		 //    },
		 	parent:'/',
		    label: ' / {{companySectorName}} / {{companyCategoryName}} / {{companyCountrySubBreadcrumb}}'
		  },
		parent: "app"  
	}).
	// state('company-sub-city', {
	// 	url: '/company/:companyName/:countryName/:cityName',
	// 	templateUrl: 'dist/assets/html/company_details.html',
	// 	controller: 'CompanyController',
	// 	resolve : {
	// 	   companyDetails: function(Restangular, $stateParams) {
	// 			return Restangular.one('/company-details').get($stateParams); 
	// 	   },
	// 	   deps: ['$ocLazyLoad', function($ocLazyLoad) {
	// 		   return $ocLazyLoad.load({
	// 			   name: 'sharadoApp',
	// 			   files: [
	// 				   'angular/partials/companies/controllers/CompanyController.js',

	// 			   ]                    
	// 		   });
	// 	   }]
	//    },
	//    ncyBreadcrumb: {
	// 		// parent: function($rootScope) {
	// 		// 	if($rootScope.previousStateName)
	// 		// 		return $rootScope.previousStateName + '(' + JSON.stringify($rootScope.previousStateParams) + ')';
	// 		// 	else
	// 		// 		return "/";
	// 	 //    },
	// 	 	parent: '/',
	// 	    label: 'Company / {{companyCitySubBreadcrumb}}'
	// 	  },
	// 	parent: "app"  
	// }).
	// state('search-results', {
	// 	url: '/search-results',
	//     templateUrl: 'angular/partials/search_results.html',
	//     controller: 'HomeController' 
	// }).
	state('reset-password', {
		url: '/reset-password/{email}/{token}',
		templateUrl: 'dist/assets/html/reset-password.html',
		controller: 'ChangePasswordController',
		data: {title: 'Sharado - Reset Password'},
		resolve : {
			params: function(Restangular, $stateParams) {
		   		return Restangular.one('/reset-password/'+$stateParams.email+'/'+$stateParams.token).get();
		   	},
		   	deps: ['$ocLazyLoad', function($ocLazyLoad) {
			   	return $ocLazyLoad.load({
				   name: 'sharadoApp',
				   files: [
					   "angular/partials/registration/controllers/ChangePasswordController.js",
				   ]                    
			   	});
		   	}]
	   	},
		ncyBreadcrumb: {
			parent: '/',
		    label: 'Reset Password'
		  },
		parent: "app"  
	}).
	state('forgot-password', {
		url: '/forgot-password',
		templateUrl: 'dist/assets/html/forgot-password.html',
		controller: 'ResetPasswordController',
		data: {title: 'Sharado - Forgot Password'},
		resolve : {
		   deps: ['$ocLazyLoad', function($ocLazyLoad) {
			   return $ocLazyLoad.load({
				   name: 'sharadoApp',
				   files: [
					   "angular/partials/registration/controllers/ResetPasswordController.js",
				   ]                    
			   });
		   }]
	   	},
		ncyBreadcrumb: {
			parent: '/',
		    label: 'Forgot Password'
		},
		parent: "app"		
	}).
	state('reset-password-expired', {
		url: '/reset-password-expired',
		templateUrl: 'dist/assets/html/reset-password-expiration.html',
		data: {title: 'Sharado'},
		parent: "app"
	}).
	state('my-profile', {
		url: '/my-profile',
		templateUrl: 'dist/assets/html/my-profile.html',
		controller: 'MyProfileController',
		params:{lang: Cookies.get('urlCode')},
		data: {title: 'Sharado - My Profile'},
		resolve : {
		   profileUser: function(Restangular, $stateParams) {
		   		return Restangular.one('/my-profile').get($stateParams);
		   },
		   countries: function(Restangular, $stateParams) {
		   		return Restangular.one('/countries').get($stateParams);
		   },
		   deps: ['$ocLazyLoad', function($ocLazyLoad) {
			   return $ocLazyLoad.load({
				   name: 'sharadoApp',
				   files: [
					   "bower_components/amitava82-angular-multiselect/dist/multiselect-tpls.js",
					   "angular/partials/my_profile/controllers/MyProfileController.js",
				   ]                    
			   });
		   }]
	   },
	   ncyBreadcrumb: {
			parent: '/',
		    label: 'My Profile'
		  },
		parent: "app",
	}).
	// state('city-jobs', {
	//     url: '/city/:city',
	//     templateUrl: 'angular/partials/search_results.html',
	//     controller: 'HomeController'
	// })


	state('search-results', {
		url: '/search/:search_text?&type$latitude&longitude&country_short_name&country_name&id&cityName&section&page',
		templateUrl: 'dist/assets/html/search_results.html',
		controller: 'SearchController',
		params : { id: null, count: 0, url: null, activationAvailable: true, search_text:""},
		data: {title: 'Sharado - Search'},
		resolve : {
		   searchResult: function(Restangular, $stateParams, $state){
				return Restangular.one('/search').get($stateParams); 
		   },
		   breadcrumbs: function($stateParams, $httpParamSerializer){

		   		var searchParams = {
										activationAvailable: true, 
										type:$stateParams.type, 
										latitude: $stateParams.latitude, 
										longitude: $stateParams.longitude, 
										count:0, 
										country_short_name: $stateParams.country_short_name, 
										country_name: $stateParams.country_name, 
										cityName: $stateParams.cityName, 
										url:'/search', 
										keyword: "",
										lang: $stateParams.lang
									};

		   		var url = $httpParamSerializer(searchParams);
		   		return [
				         {
				          name: 'Home ',
				          url: '/'+$stateParams.lang+'/',
				          type: ''
				         },
				         {
				          name: 'Search ',
				          url: '/'+$stateParams.lang+'/gigs',
				          type: ''

				         },
				         {
				          name: $stateParams.country_name ,
				          // url: '/#!/'+$stateParams.lang+'/gigs',
				          type: 'gigs'
				         },
				         {
				          name: $stateParams.cityName ,
				          url: '/#!/'+$stateParams.lang+'/search/?'+url,
				          type: 'city-search'
				         },
				         {
				          name: $stateParams.search_text,
				          url: '',
				          type: 'search_text'
				         },
				        ]
		   	} ,
		   deps: ['$ocLazyLoad', function($ocLazyLoad) {
			   return $ocLazyLoad.load({
				   name: 'sharadoApp',
				   files: [
					   'angular/partials/search/controllers/SearchController.js',
				   ]                    
			   });
		   }]
	   },
	   ncyBreadcrumb: {
	    parent: '/',
	    label: '{{searchByLocationBreadcrumb}}'
	  },
	  parent: "app",
	}).
	state('top-sector', {
		url: '/topsector/:param?&id',
		templateUrl: 'dist/assets/html/search_results.html',
		controller: 'SearchController',
		params : { count: 0, activationAvailable: true, search_text:"", country_name:"", latitude:"", longitude:"", showParam:"", keyword:"", id:""},
		data: {title: 'Sharado - Top Sector'},
		resolve : {
		   searchResult: function(Restangular, $stateParams, $state){
				return Restangular.one('/top-sectors').get($stateParams);
		   },

		   // breadcrumbs: function($stateParams){
		   // 	// var breadcrumb = {{breadcrumb}};
		   // 	return [
			  //  		{
			  //         name: 'Home ',
			  //         url: '/'+$stateParams.lang+'/',
			  //         type: ''
			  //        },
			  //        // {
			  //        // 	name: 'Top Sector',
			  //        // 	url:'',
			  //        // 	type: 'disabled'
			  //        // },
			  //        {
			  //        	name: breadcrumb,
			  //        	url:'',
			  //        	type: 'disabled'
			  //        },
		   // 	]
		   // },
		// },
		 //   ncyBreadcrumb: {
			// // parent: function($rootScope) {
			// // 	if($rootScope.previousStateName)
			// // 		return $rootScope.previousStateName + '(' + JSON.stringify($rootScope.previousStateParams) + ')';
			// // 	else
			// // 		return "/";
		 // //    },
		 // 	parent:'/',
		 //    label: ' / aaa'
		 //  },
		   deps: ['$ocLazyLoad', function($ocLazyLoad) {
			   return $ocLazyLoad.load({
				   name: 'sharadoApp',
				   files: [
					   'angular/partials/search/controllers/SearchController.js',
				   ]                    
			   });
		   }]
	   },
	   ncyBreadcrumb: {
	    parent: '/',
	    label: '/ {{breadcrumb}}'
	  },
	  parent: "app"
	}).
	state('top-location', {
		url: '/toplocation/:locationName?',
		templateUrl: 'dist/assets/html/search_results.html',
		controller: 'SearchController',
		params : { count: 0, activationAvailable: false, cityName:"", country_name:"", latitude:"", longitude:"", search_text:""},
		data: {title: 'Sharado - Top Location'},
		resolve : {
		   searchResult: function(Restangular, $stateParams, $state){
				return Restangular.one('/search').get($stateParams);
		   },

		   breadcrumbs: function(){
		   	return [];
		   },
		   deps: ['$ocLazyLoad', function($ocLazyLoad) {
			   return $ocLazyLoad.load({
				   name: 'sharadoApp',
				   files: [
					   'angular/partials/search/controllers/SearchController.js',
				   ]                    
			   });
		   }]
	   },
	   ncyBreadcrumb: {
	    parent: '/',
	    label: 'Top Location / {{locationBreadcrumb}}'
	  },
	  parent: "app"
	}).
	// state('top-gigs', {
	// 	url: '/topgigs/:jobName?',
	// 	templateUrl: 'dist/assets/html/search_results.html',
	// 	controller: 'SearchController',
	// 	params : { count: 0, activationAvailable: true, search_text:"", country_name:"", latitude:"", longtitude:"", showParam:"", keyword:""},
	// 	resolve : {
	// 	   searchResult: function(Restangular, $stateParams, $state){
	// 			return Restangular.one('/top-gigs').get($stateParams);
	// 	   },

	// 	   breadcrumbs: function(){
	// 	   	return [];
	// 	   },
	// 	   deps: ['$ocLazyLoad', function($ocLazyLoad) {
	// 		   return $ocLazyLoad.load({
	// 			   name: 'sharadoApp',
	// 			   files: [
	// 				   'angular/partials/search/controllers/SearchController.js',
	// 			   ]                    
	// 		   });
	// 	   }]
	//    },
	//    ncyBreadcrumb: {
	//     parent: '/',
	//     label: 'Top Gigs / {{topGigsBreadcrumb}}'
	//   },
	//   parent: "app"
	// }).
	state('gigs', {
		url: '/gigs?page',
		templateUrl: 'dist/assets/html/search_results.html',
		controller: 'SearchController',
		params : { count: 0, activationAvailable: false, latitude:"", longitude:"", countryCode:""},
		data: {title: 'Sharado - Gigs'},
		resolve : {
		   searchResult: function(Restangular, $stateParams, $state){
				return Restangular.one('/browse-jobs-gigs').get($stateParams);
		   },

		   breadcrumbs: function($stateParams){
		   	return [

		   			{
			          name: 'Home ',
			          url: '/'+$stateParams.lang+'/',
			          type: ''
			         },
			         {
			         	name: 'Gigs',
			         	url:'',
			         	type: 'disabled'
			         },
		   	]
		   },
		   deps: ['$ocLazyLoad', function($ocLazyLoad) {
			   return $ocLazyLoad.load({
				   name: 'sharadoApp',
				   files: [
					   'angular/partials/search/controllers/SearchController.js',
				   ]                    
			   });
		   }]
	   },
	   ncyBreadcrumb: {
	    parent: '/',
	    label: '/ Gigs'
	  },
	  parent: "app"
	}).
	state('top-category', {
		url: '/topcategory/:param?&id',
		templateUrl: 'dist/assets/html/search_results.html',
		controller: 'SearchController',
		params : { count: 0, activationAvailable: true, search_text:"", country_name:"", latitude:"", longitude:"", showParam:"", keyword:""},
		data: {title: 'Sharado - Top Category'},
		resolve : {
		   searchResult: function(Restangular, $stateParams, $state){
				return Restangular.one('/popular-categories').get($stateParams);
		   },

		   breadcrumbs: function($stateParams){
		   		var breadcrumb = $stateParams.param;
			   	return [
				   		{
				          name: 'Home ',
				          url: '/#!/'+$stateParams.lang+'/',
				          type: ''
				         },
				         {
				         	name: 'Top Category',
				         	url:'',
				         	type: 'disabled'
				         },
				         {
				         	name: breadcrumb,
				         	url:'',
				         	type: 'disabled'
				         },
			   	]

		   },
		   deps: ['$ocLazyLoad', function($ocLazyLoad) {
			   return $ocLazyLoad.load({
				   name: 'sharadoApp',
				   files: [
					   'angular/partials/search/controllers/SearchController.js',
				   ]                    
			   });
		   }]
	   },
	   ncyBreadcrumb: {
	    parent: '/',
	    label: '/ {{breadcrumb}}'
	  },
	  parent: "app"
	}).
	state('expired-account-activation', {
		url: '/account-activation/expired/:token',
		templateUrl: 'dist/assets/html/account-confirmation.html',
		data: {title: 'Sharado'},
		parent: "app"
	}).
	state('contact-us', {
		url: '/contact-us',
		templateUrl: 'dist/assets/html/contact_us.html',
		controller: 'ContactUsController',
		params:{lang: Cookies.get('urlCode')},
		data: {title: 'Sharado - Contact Us'},
		resolve : {
		   deps: ['$ocLazyLoad', function($ocLazyLoad) {
			   return $ocLazyLoad.load({
				   name: 'sharadoApp',
				   files: [
					   'angular/partials/contact_us/controllers/ContactUsController.js',
				   ]                    
			   });
		   }]
	   },
	   	ncyBreadcrumb: {
			parent: '/',
		    label: 'Contact Us'
		  },
		parent: "app"
	}).
	state('all-categories', {
		url: '/all-categories',
		templateUrl: 'dist/assets/html/all_categories.html',
		controller: 'CategoryController',
		data: {title: 'Sharado - Categories'},
		resolve : {
		   allcategories: function(Restangular, $stateParams, $state){
				return Restangular.one('/all-categories').get($stateParams);
		   },
		   top: function(Restangular, $stateParams, $state){
				return Restangular.one('/dashboard').get($stateParams);
		   },
		   deps: ['$ocLazyLoad', function($ocLazyLoad) {
			   return $ocLazyLoad.load({
				   name: 'sharadoApp',
				   files: [
					   'angular/partials/categories/controllers/CategoryController.js',
				   ]                    
			   });
		   }]
	   },
	   	ncyBreadcrumb: {
			parent: '/',
		    label: 'All Categories'
		  },
		parent: "app"  
	}).
	state('terms-of-service', {
		url: '/tos',
		templateUrl: 'dist/assets/html/terms-of-service.html',
		controller: '',
		data: {title: 'Sharado - Terms of Service'},
	   	ncyBreadcrumb: {
			parent: '/',
		    label: 'Terms Of Service'
		  },
		parent: "app"  
	}).
	state('privacy', {
		url: '/privacy',
		templateUrl: 'dist/assets/html/privacy-policy.html',
		controller: '',
		data: {title: 'Sharado - Privacy Policy'},
	   	ncyBreadcrumb: {
			parent: '/',
		    label: 'Privacy Policy'
		  },
		parent: "app"  
	}).
	state('about', {
		url: '/about',
		templateUrl: 'dist/assets/html/about-us.html',
		controller: 'HomeController',
		data: {title: 'Sharado - About Us'},
	   	ncyBreadcrumb: {
			parent: '/',
		    label: 'About Us'
		  },
		parent: "app"  
	}).
	state('public-profile', {
		url: '/public-profile/:username',
		templateUrl: 'dist/assets/html/public-profile.html',
		controller: 'PublicProfileController',
		data: {title: 'Sharado'},
		resolve : {
			deps: ['$ocLazyLoad', function($ocLazyLoad) {
			   return $ocLazyLoad.load({
				   name: 'sharadoApp',
				   files: [
					   'angular/partials/public_profile/controllers/PublicProfileController.js',
				   ]                    
			   });
		   }]
		},
		parent: "app"  
	})
	// state('not-operating-company', {
	// 	url: 'search-result',
	// 	templateUrl: 'angular/partials/companies/views/not_operating_company.html',
	// 	controller: 'CompanyController',
	// 	resolve : {
	// 	   companyDetails: function(Restangular, $stateParams){
				
	// 	   },
	// 	   deps: ['$ocLazyLoad', function($ocLazyLoad) {
	// 		   return $ocLazyLoad.load({
	// 			   name: 'sharadoApp',
	// 			   files: [
	// 				   'angular/partials/companies/controllers/CompanyController.js',
	// 			   ]                    
	// 		   });
	// 	   }]
	//    }

	// })
	// $urlRouterProvider.otherwise('/alternative')
	$urlRouterProvider.otherwise(function($injector, $location){
	  $injector.invoke(['$state', 'Restangular', function($state, Restangular) {
	    Restangular.one("/return-country-code").get().then(function(response) {            
            $state.go('/', {lang:response.countryCode});
        })
	  }]);
	}); 
});