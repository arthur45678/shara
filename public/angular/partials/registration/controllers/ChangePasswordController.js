sharadoApp.controller('ChangePasswordController', ['$scope', '$http', '$rootScope', '$state', '$stateParams', 'Restangular','params',  
	function ($scope, $http, $rootScope, $state, $stateParams, Restangular, params) {


	if (params.success == '0') {
		if (params.response && parms.response == 'reset-password-expired') {
			$state.go('reset-password-expired');
		} else {
			$state.go('/');
		}
	}
	$scope.resetPasswordChange = function() {
		$rootScope.pass_unmatch = false;
		if($scope.password !== $scope.confirmPassword)
		{
			$rootScope.pass_unmatch = true;
			return
		} else {
			var data = {}
			data.email = $stateParams.email;
			data.token =  $stateParams.token;
			data.password = $scope.password;
			if ($stateParams.email && $stateParams.token) {
				Restangular.all('/change-pass').post(data).then(function(response) {
					$state.go('login');
				})
			}
		}
	}
}]);