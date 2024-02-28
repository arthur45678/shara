sharadoApp.controller('ResetPasswordController', ['$scope', '$http', '$rootScope', '$state', '$stateParams', 'Restangular',  
	function ($scope, $http, $rootScope, $state, $stateParams, Restangular) {

	$scope.resetPassword = function() {
		if (!$scope.resetEmail) {
			return
		}
		var data = {}
		data.email  = $scope.resetEmail;
		data.lang = $stateParams.lang;
		setTimeout(function() {
			console.log(data)
			Restangular.all('/reset-password').post(data).then(function(response) {
				$('.forgot-password-p').hide();
				$('.reset-account-email').show();
			})
		}, 200);
		
	}
}]);