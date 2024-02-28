var depenencies = [
  'ui.router',
  'restangular',
  'oc.lazyLoad',
  'flow',
  'pascalprecht.translate',
  'ui.bootstrap',
  'angular-jwt',
  'ncy-angular-breadcrumb',
  'ngSanitize',
  'ui.bootstrap',
  'ui.bootstrap.pagination',
  'ui.utils.masks',
  'ngImgCrop',
  'seo'
];

var sharadoApp = angular.module('sharadoApp', depenencies);
 
// sharadoApp.factory('settings', ['$rootScope', function($rootScope) {
//     // supported languages
//     var settings = {
//         layout: {
//             pageSidebarClosed: false, // sidebar menu state
//             pageContentWhite: true, // set page content layout
//             pageBodySolid: false, // solid body color state
//             pageAutoScrollOnLoad: 500 // auto scroll to top on page load
//         },
//         assetsPath: '../assets',
//         globalPath: '../assets/global',
//         layoutPath: '../assets/layouts/layout4',
//     };

//     $rootScope.settings = settings;

//     return settings;
// }]);


sharadoApp.config(function($translateProvider, flowFactoryProvider, RestangularProvider) {
  flowFactoryProvider.defaults = {
      permanentErrors: [404, 500, 501],
        testChunks:false,
        simultaneousUploads: 1,
        singleFile: true
        };
    $translateProvider.registerAvailableLanguageKeys(['en', 'fr', 'es', 'de', 'it', 'am']);
    $translateProvider.useStaticFilesLoader({
       prefix: '/angular/languages/',
       suffix: '.json'
    });
    // var lang = (Cookies.get('lang')) ? Cookies.get('lang') : 'en';
    // $translateProvider.use(lang);
    $translateProvider.fallbackLanguage('en');
    RestangularProvider.setBaseUrl(baseUrl);
});

sharadoApp.config(['$locationProvider', function($location) {
  $location.html5Mode(true);
  $location.hashPrefix('!');
}]);

sharadoApp.run(function(Restangular , $state, $stateParams, $rootScope, $location, $translate){
    // var str = "<span style='display : none'> 1710239039027614 </span>";
    // var index = document.body.innerHTML.lastIndexOf("1710239039027614");
    // document.body.innerHTML = document.body.innerHTML.substr(0, index) + str + document.body.innerHTML.substr(index + 16);
    var currentUrl = window.location.href;
    var availableLanguageKeys = $translate.getAvailableLanguageKeys();
    if( currentUrl == appUrl && ( !$stateParams.lang || $stateParams.lang == "") ){
        // if(resp.unknown == true) {
        //     setTimeout(function() {
        //         $rootScope.unknownLocationMapAdd();
        //     }, 50);
        //     $('#locationModal').modal('show');
        // }else {
        //}
        Restangular.one("/return-country-code").get().then(function(response) {            
            $state.go('/', {lang:response.countryCode});
            if(availableLanguageKeys.indexOf(response.languageCode) !== -1) {
              $translate.use(response.languageCode)
            }else {
                $translate.use('en');
            }
                                        
        })
    }

    Restangular.addResponseInterceptor(function (resp) {
       if (resp.error_status == -1000) {
            Cookies.set('token', '');
            Cookies.set('user', '');
            Cookies.set('user_step', '');
            Cookies.set('user_id', '');
            window.location.href = '/';
        } else if(resp.status == 'company') {
            if(resp.company_type == 'generic') {
                $state.go('company', {companyName:resp.company.name});
            }else if (resp.company_type == 'city_subsidiary') {
                Restangular.one('/company-subsidiary-info').get({companyId:resp.company.id}).then(function(response) {
                    var companyCountryName = response.countryName;
                    var companyCityName = response.cityName;
                    $state.go('company-sub-city', {companyName:resp.company.name, countryName:companyCountryName, cityName:companyCityName});
                })
            } else if (resp.company_type == 'country_subsidiary') {
                Restangular.one('/company-subsidiary-info').get({companyId:resp.company.id}).then(function(response) {
                    var companyCountryName = response.countryName;
                    $state.go('company-sub', {companyName:resp.company.name, countryName:companyCountryName});
                })
            }
            
        } else if (resp.status == 'registration_not_completed') {
            var redirectUrl = $location.absUrl();
            $state.go('second-step', {id:resp.user_id, disableBackButton:true, redirectUrl:redirectUrl})
        } 
        
        if(resp.mesage) {
            if(resp.message.indexOf('Cartalyst\Sentinel\Sentinel::login()') != -1) {
                var token = Cookies.get('token');
                Restangular.all('/logout').post().then(function(data) {
                    $('.user-email').removeAttr('readonly');
                    $rootScope.user = {};
                    Cookies.set('token', '', {expires: 60*60*24*10});
                    Cookies.set('user', '', {expires: 60*60*24*10});
                    Cookies.set('user_id', '', {expires: 60*60*24*10});
                    $rootScope.loggedUser = Cookies.get('user');
                    angular.element('#menu-modal').modal('hide');
                    $state.go('/');
                })
            }
        }

        if(resp.error == 'redirect_to_homepage') {
            $state.go('/', {lang: resp.lang});
        }

        return resp;
    });
});


// sharadoApp.config(function(, RestangularProvider){
//        
// });
sharadoApp.controller('headerCtrl', function($translate, $scope, $http, $state) {
    

});


sharadoApp.run(function($rootScope, $state) {
    $rootScope.$on("$stateChangeSuccess",  function(event, toState, toParams, fromState, fromParams) {

        // to be used for back button //won't work when page is reloaded.
        $rootScope.previousStateName = fromState.name;
        $rootScope.previousStateParams = fromParams;
        $rootScope.pageTitle = toState.data.title;
    });

});


sharadoApp.config(function ($httpProvider) {
    $httpProvider.interceptors.push(function() {
        return {
            'request': function (config) {
                config.headers = config.headers || {};
                if (Cookies.get('token')) {
                    // console.log(Cookies.get('token'))
                    config.headers.Authorization = 'Bearer ' + Cookies.get('token');
                }
                return config;
            },
            'response': function(response) {
                  if(response.data.error_status == -1000) {
                    Cookies.set('token', '');
                    Cookies.set('user', '');
                    Cookies.set('user_step', '');
                    Cookies.set('user_id', '');
                    window.location.href = '/';
                  }
                  return response;
    },
        };
    });
});

sharadoApp.directive('ngEnter', function () {
    return function (scope, element, attrs) {
        element.bind("keydown keypress", function (event) {
            if(event.which === 13) {
                scope.$apply(function (){
                    scope.$eval(attrs.ngEnter);
                });
 
                event.preventDefault();
            }
        });
    };
});

sharadoApp.filter('ellipsis', function () {
    return function (text, length) {
        if (text.length > length) {
            return text.substr(0, length) + "... (+)";
        }
        return text;
    }
});

sharadoApp.filter('ellipsisdots', function () {
    return function (text, length) {
        if (text.length > length) {
            return text.substr(0, length) + "...";
        }
        return text;
    }
});

sharadoApp.run(function($rootScope, $state) {
    $rootScope.state = $state;
});

sharadoApp.directive('datepicker', function () {
    return {
        restrict: 'A',
        link: function(scope, el, attrs){
        $(el).inputmask({
        alias: 'dd/mm/yyyy',
        yearrange: { minyear: 1940, maxyear: 2000}
        })
    }
    }
});

sharadoApp.filter('capitalize', function() {
    return function(input) {
      return (!!input) ? input.charAt(0).toUpperCase() + input.substr(1).toLowerCase() : '';
    }
});
