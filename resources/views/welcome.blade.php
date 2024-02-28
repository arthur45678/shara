<!DOCTYPE html>
<html lang="en" ng-app="sharadoApp" ng-controller="HomeController" >
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
			<h1><a ui-sref="/"><img style="width: 55%" src="/angular/images/Capture.JPG" alt="Sharado" /></a></h1>
		</div>

		<!-- Menu -->
		<nav id="navigation" class="menu">
			<ul id="responsive">
				<!-- <li><a href="index.html" id="current">Home</a>
					<ul>
						<li><a href="index.html">Home #1</a></li>
						<li><a href="index-2.html">Home #2</a></li>
						<li><a href="index-3.html">Home #3</a></li>
						<li><a href="index-4.html">Home #4</a></li>
						<li><a href="index-5.html">Home #5</a></li>
					</ul>
				</li> -->
				<li data-ng-show="!$state.includes('login') && !$state.includes('second-step') && !$state.includes('third-step') && !$state.includes('fourth-step') && !$state.includes('fourth-step') && !$state.includes('fifth-step')" ><a href="" ng-click="browseJobsGigs()">Browse Jobs/Gigs</a>					
				</li>
				
				<!-- <li ng-show="loggedUser"><a href="#">My Profile</a>
				</li> -->


				<!-- <li><a href="#">For Candidates</a>
					<ul>
						<li><a href="browse-jobs.html">Browse Jobs</a></li>
						<li><a href="browse-categories.html">Browse Categories</a></li>
						<li><a href="add-resume.html">Add Resume</a></li>
						<li><a href="manage-resumes.html">Manage Resumes</a></li>
						<li><a href="job-alerts.html">Job Alerts</a></li>
					</ul>
				</li> -->

				<li data-ng-show="!$state.includes('login') && !$state.includes('second-step') && !$state.includes('third-step') && !$state.includes('fourth-step') && !$state.includes('fourth-step') && !$state.includes('fifth-step')"><a href="#">For Companies</a>
				</li>
				<li data-ng-show="!$state.includes('login') && !$state.includes('second-step') && !$state.includes('third-step') && !$state.includes('fourth-step') && !$state.includes('fourth-step') && !$state.includes('fifth-step')">
					<div class="dropdown">
					  <button class="btn btn-degault dropdown-toggle language-dropdown" type="button" data-toggle="dropdown"><img class="mainFlag" src="/angular/images/flag_en.png">
					  <span class="caret"></span></button>
					  <ul class="dropdown-menu lang-ul">
					    <li><a href="" ng-click="changeLanguage('en','/angular/images/flag_en.png')" ><img src="/angular/images/flag_en.png" alt="english"></a></li>
					    <li><a href="" ng-click="changeLanguage('fr','/angular/images/flag_fr.png')" ><img src="/angular/images/flag_fr.png" alt="french"></a></li>
					    <li><a href="" ng-click="changeLanguage('it','/angular/images/flag_it.png')" ><img src="/angular/images/flag_it.png" alt="italian"></a></li>
					    <li><a href="" ng-click="changeLanguage('am','/angular/images/flag_am.png')" ><img src="/angular/images/flag_am.png" alt="armenian"></a></li>
					  </ul>
					</div>
				</li>
				<li data-ng-show="!$state.includes('login') && !$state.includes('second-step') && !$state.includes('third-step') && !$state.includes('fourth-step') && !$state.includes('fourth-step') && !$state.includes('fifth-step')" class="sharado-my-profile"><a href="">My Profile</a>
					<ul >
						<li><a ng-show="!loggedUser" data-toggle="modal" data-target="#myModal" href=""><i class="fa fa-user"></i> Sign Up</a></li>
						<li><a ng-show="!loggedUser" ui-sref="login" href=""><i class="fa fa-lock"></i> Log In</a></li>
						<li><a ng-show="loggedUser" href="" ng-click="myProfileTab(1)">My Profile</a></li>
						<li><a ng-show="loggedUser" href="" ng-click="myProfileTab(2)">My Skills</a></li>
						<li><a ng-show="loggedUser" href="" ng-click="myProfileTab(3)">My Availabilities</a></li>
						<li><a ng-show="loggedUser" href="" ng-click="myProfileTab(4)">My Applications</a></li>
						<li><a ng-show="loggedUser" href="" ng-click="myProfileTab(5)">My Alert</a></li>
						
					</ul>
				</li>
				<li data-ng-show="!$state.includes('login') && !$state.includes('second-step') && !$state.includes('third-step') && !$state.includes('fourth-step') && !$state.includes('fourth-step') && !$state.includes('fifth-step')"><a ng-show="!loggedUser" data-toggle="modal" data-target="#myModal" href=""><i class="fa fa-user"></i> Sign Up</a></li>
				<li ng-show="loggedUser"><a href="#">Hi, @{{loggedUser}}</a>
					<ul>
						<li><a href="" ng-click="logout()">Sign Out</a></li>
					</ul>
				</li>
			</ul>
			
			

		</nav>

		<!-- Navigation -->
		<div id="mobile-navigation">
			<a href="" class="menu-trigger"><i class="fa fa-reorder"></i> Menu</a>
		</div>

	</div>
</div>
</header>
@if(isset($expirationMessage))

	<div class="container complete-registration-container" style="height: 450px;">

		<div class="complete-registration-div">
			<h3 class="congratulations-h3">{{$expirationMessage}}</h3>
		</div>

	</div>
@else
	<div ui-view></div>
@endif
<div class="clearfix"></div>

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
      <div class="modal-body">
        <div class="container sharado-registration">
			<div class="my-account-shar">
			<!-- <img src="/angular/images/facebook_square.png" class="facebook-square">
			<a class="btn btn-default facebook-reg">Register With Facebook</a> -->
			<a href=""><img src="/angular/images/capture-facebook.PNG" class="facebook-reg" ng-click="facebookReg()"></a>
			<hr />
				<div class="tabs-container">
					<!-- Register -->
					<div class="tab-content" id="tab2">
						<form method="post" class="register" name="firstStepForm">
						<p class="form-row form-row-wide modal-first" style="margin-bottom: 50px">								
								<input type="text" class="input-text" name="first_name" id="first_name" value="" placeholder="First Name" ng-model="$root.user.first_name" required/>
							<span class="validation_error" ng-show="firstStepForm.first_name.$dirty && firstStepForm.first_name.$error.required">First Name is required.</span>
							<span class="validation_error" ng-show="first_name_error">@{{first_name_error}}</span>
						</p>
						<p class="form-row form-row-wide modal-first" style="margin-bottom: 50px">
								<input type="text" class="input-text" name="last_name" id="last_name" value="" placeholder="Last Name" ng-model="$root.user.last_name" required />
							<span class="validation_error" ng-show="firstStepForm.last_name.$dirty && firstStepForm.last_name.$error.required">Last Name is required.</span>
							<span class="validation_error" ng-show="last_name_error">@{{last_name_error}}</span>
						</p>
						<p class="form-row form-row-wide modal-first">	
							<input type="email" class="input-text user-email" name="email" id="email2" value="" placeholder="Email" ng-model="$root.user.email" required />
							<span class="validation_error" ng-show="firstStepForm.email.$dirty && firstStepForm.email.$error.required">Email is required.</span>
							<span class="validation_error" ng-show="firstStepForm.email.$dirty && firstStepForm.email.$error.email">Invalid email address.</span>
							<span class="validation_error" ng-show="$root.userExists">Email is already taken.</span>
							<span class="validation_error" ng-show="$root.emailSendingFailed">Email sending failed.</span>
							<span class="validation_error" ng-show="email_error">@{{email_error}}</span>
							<span class="validation_error" ng-show="exists_error">User already exists</span>
						</p>

						<p class="form-row form-row-wide modal-first">	
							<input class="input-text" type="password" name="password1" id="password1" placeholder="Password" required ng-model="$root.user.password" />
							
						</p>
						<span class="validation_error password_validation_error" ng-show="password_error">@{{password_error}}</span>

						<p class="form-row" style="text-align: center;">
							<button ng-click="firstStep()" ng-disabled="disableFirstStep" class="button border fw margin-top-10 sharado-button-next sharado-second-step" name="register" >Next <i class="fa fa-arrow-circle-right"></i></button>
							<a href="" ng-click="haveAccount()">I already have an account</a>
						</p>

						</form>
					</div>
				</div>
			</div>
		</div>
			<!-- Back To Top Button -->
			<div id="backtotop"><a href="#"></a></div>
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
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDOSHZrD8ERF65DayZTEUnHi_ge84YFgcw&libraries=places&language=en"></script>

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


<script src="/angular/app.js"></script>
<script src="/angular/routes.app.js"></script>
<script src="/angular/HomeController.js"></script>
<script src="/angular/config.js"></script>
</body>
</html>