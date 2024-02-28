<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Sharado</title>

    <link href="/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="/angular/css/style.css">
	<link rel="stylesheet" href="/angular/css/colors/orange.css" id="colors">
	<link rel="stylesheet" href="/angular/css/sharado.css">

	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css"/>

	<link href="/bower_components/angular-multiple-select/build/multiple-select.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="/bower_components/angularjs-datepicker/angular-datepicker.css">
	<link rel="shortcut icon" href="/images/Sharado-picto.png" />
</head>

<body>
<div class="loader"></div>
<div class="content_loading">
<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
ga('create', 'UA-93771942-1', 'auto');
ga('send', 'pageview');
</script>
<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '101917906996452',
      xfbml      : true,
      version    : 'v2.8'
    });
    FB.getLoginStatus(function(response) {
	    
	    console.log(response)
	});
    FB.AppEvents.logPageView();
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
</script>
<header class="transparent sticky-header full-width">
<div class="container" ng-controller="headerCtrl">
	<div class="sixteen columns">
	
		<!-- Logo -->
		<div id="logo">
			<h1><a href="{{config('app.url').'#!/'}}"><img style="width: 55%" src="/angular/images/Capture.JPG" alt="Sharado" /></a></h1>
		</div>


	</div>
</div>
</header>


	<div class="container complete-registration-container" style="height: 450px;">

		<div class="complete-registration-div">
			<h3 class="congratulations-h3">Your alert deleted.</h3>
		</div>

	</div>

<!-- <script src="bower_components/jquery/dist/jquery.min.js"></script> -->

<!-- <script src="angular-translate/angular-translate.js"></script> -->
<div class="cookies-warning" ng-show="!$root.cookiesPolicy">
	<div class="cookies-warning-message">
		<p>To give you the best possible experience, this site uses cookies. Accept our Cookie Policy.</p>
	</div>
	
	<div class="cookies-warning-actions">
		<a href="" class="btn btn-default" ng-click="acceptCookiesPolicy()">Accept</a>
	</div>	
</div>    
<div class="margin-top-15"></div>
<div id="footer">
	<!-- Main -->
	<!-- Bottom -->
	<div class="container">
		<div class="footer-bottom">
			<div class="sixteen columns">
				<ul style="float: right;">
					<li style="display: inline; margin-right: 5px"><a href="#">Home</a></li>
					<li style="display: inline; margin-right: 5px"><a href="#">For Companies</a></li>
					<li style="display: inline; margin-right: 5px"><a href="#">Email</a></li>
					<li style="display: inline; margin-right: 5px">Sharado 2017</li>
				</ul>		
			</div>
		</div>
	</div>
</div>
<!-- Back To Top Button -->
<div id="backtotop"><a href="#"></a></div>
<div id="myModal" class="modal fade" role="dialog" >
   <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      	<div class="modal-header">
        	<button type="button" class="close" data-dismiss="modal">&times;</button>
        	<img style="margin-left: 85px;" src="/angular/images/Capture.JPG" alt="Sharado" />
        	<p>The easiest way to get a job in the sharing economy</p>
        	<p>Join Sharado and get instant access to hundreds of jobs and gigs in your area</p>
      	</div>
    </div>
   </div>
</div>
<script src="/angular/scripts/jquery-2.1.3.min.js"></script>
<script src="/angular/scripts/custom.js"></script>
<script src="/angular/scripts/jquery.superfish.js"></script>
<script src="/angular/scripts/jquery.themepunch.tools.min.js"></script>
<script src="/angular/scripts/jquery.themepunch.revolution.min.js"></script>
<script src="/angular/scripts/jquery.themepunch.showbizpro.min.js"></script>
<script src="/angular/scripts/jquery.flexslider-min.js"></script>
<script src="/angular/scripts/chosen.jquery.min.js"></script>
<script src="/angular/scripts/jquery.magnific-popup.min.js"></script>
<script src="/angular/scripts/waypoints.min.js"></script>
<script src="/angular/scripts/jquery.counterup.min.js"></script>
<script src="/angular/scripts/jquery.jpanelmenu.js"></script>
<script src="/angular/scripts/stacktable.js"></script>
<script src="/angular/scripts/headroom.min.js"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAGI7_1mD-JsKdZ2WwFlJvg7rDzfbmRn64
&libraries=places&language=en"></script>

<script src="/bower_components/angular/angular.js"></script>
<script src="/bower_components/angular-ui-router.min.js"></script>

<script src="/bower_components/lodash/lodash.js"></script>
<script src="/bower_components/angular-local-storage/dist/angular-local-storage.min.js"></script>
<script src="/bower_components/restangular/dist/restangular.min.js"></script>

<!-- <script src="//cdnjs.cloudflare.com/ajax/libs/angular-ui-router/0.2.8/angular-ui-router.min.js"></script> -->
<script src="/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="/bower_components/ocLazyLoad.min.js" type="text/javascript"></script>
<!-- concatenated flow.js + ng-flow libraries -->
<script src="/bower_components/ng-flow-standalone.js"></script>

<script src="/bower_components/angular-multiple-select/build/multiple-select.min.js"></script>

<script src="/bower_components/angular-translate/angular-translate.js"></script>
<script src="/bower_components/angular-translate-loader-static-files/angular-translate-loader-static-files.min.js"></script>
<script src="/bower_components/cookies-js/dist/cookies.js"></script>
<script src="/bower_components/amitava82-angular-multiselect/dist/multiselect.js"></script>
<script src="/bower_components/amitava82-angular-multiselect/dist/multiselect-tpls.js"></script>
<script src="/bower_components/angular-bootstrap/ui-bootstrap-tpls.js"></script>
<script src="/bower_components/angular-bootstrap/ui-bootstrap.js"></script>
<script src="/bower_components/angular-jwt/dist/angular-jwt.js"></script>
<script src="/js/bootstrap-datepicker.js"></script>
<script src="/bower_components/angularjs-datepicker/angular-datepicker.js"></script>
<script src="/bower_components/angular-breadcrumb/dist/angular-breadcrumb.js"></script>

</div>
</body>
</html>