<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <base href="/">
    <title>Sharado</title>
    @if($company->meta_description)
    <meta name="description" content="{{$company->meta_description}}">
    @endif
    @if($company->meta_keywords)
    <meta name="keywords" content="{{$company->meta_keywords}}">
    @endif

    <!-- Facebook Meta Tags -->
    <meta property="og:title" content="{{$title}}" />
    <meta property="og:type" content="article" />
    <meta property="og:url" content="{{$url}}" />
    <meta property="og:image" content="{{$image}}" />
    <meta property="og:image:width" content="158" />
    <meta property="og:image:height" content="158" />
    <meta property="og:site_name" content="Sharado" />
    <meta property="og:description" content="{{$description}}" />
    <meta http-equiv="Expires" content="30" />

    <link rel="icon" href="/img/icon.png" type="image/ico">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,700" rel="stylesheet">
    <link href="/dist/assets/css/all.css" rel="stylesheet">

  </head>

  <body data-spy="scroll" data-target="#my-navbar" data-menu-position="closed">
  <div id="fb-root"></div>
  <div>
    <nav class="navbar transparent navbar-inverse navbar-fixed-top animate" id="my-navbar">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand"><img class="logo-img animate" src="/img/logo-sharado-converted.png"/></a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="dropdown">
               <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Companies <span class="caret"></span></a>
               <ul class="dropdown-menu">
                 <li><a href="https://goo.gl/forms/hTE3oj8u3b2ozEi22" target="_blank">Create Company</a></li>
                 <li><a href="">Contact Us</a></li>
               </ul>
             </li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li><a href ="#" >How It Works</a></li>
            <li><a href ="#" >Browse Jobs/Gigs</a></li>
            <li class="dropdown"><a href="" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">My Profile</a>
				<ul class="dropdown-menu" >
					<li><a href=""><i class="fa fa-lock"></i> Login</a></li>
					<li><a href="">My Profile</a></li>
					<li><a href="">My Skills</a></li>
					<li><a href="">My Availabilities</a></li>
					<li><a href="">My Applications</a></li>
					<li class="my_alerts_tab"><a href="">My Alerts</a></li>
					
				</ul>
			</li>
            <li><a class="openSignUpModal header-menu" href=""><i class="fa fa-user"></i>Sign up</a></li>
			<li class="dropdown"><a href="#"  class="dropdown-toggle user-name" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Hi</a>
				<ul class="dropdown-menu">
					<li><a href="">Logout</a></li>
				</ul>
			</li>
          </ul>
        </div>
      </div>
    </nav>

	
    <div class="cover cover--small" data-type="background" data-speed="2"  style="background: url('img/bg-cover.jpg') center center; background-size: cover; background-attachment: fixed;">
    <div class="cover__filter"></div>
    <p class="text-center" id="search-trigger"><i class="fa fa-search" aria-hidden="true"></i></p>
</div>

 <section class="content">
    <div class="container">

      <div class="row">
        <div class="col-sm-12 col-md-12">
          <h3 class="huger light margin-bottom wide company-breadcrumb">Home / Company / {{$company->name}} / {{$company->country->name}}</h3>
          <hr class="margin-bottom">
        </div>
        <div class="col-sm-8 col-md-8">

            <div class="list-item">
              <div class="row">
                <div class="col-xs-3">
                @if($company->logo)
                  <img src="/uploads/{{$company->logo}}" alt="" class="overview-image round">
                @else
                  <img src="img/deliveroo-logo.jpg" alt="" class="overview-image round">
                @endif
                </div>
                <div class="col-xs-9">
                  <h3 class="huger no-margin">{{$company->name}}</h3>
                  @if($company->short_description)<p>{{$company->short_description}}</p>@endif
                  <p class="meta">
                      <i class="fa fa-map-marker" style="padding-right: 3px;"></i>
                      @if($company->citiesOperating)
                        <span>
                        @foreach($company->citiesOperating as $city)
                          <span style="cursor: pointer; padding-right: 5px;">{{$city->city}}, </span>
                        @endforeach
                        @if($company->citiesOperatingCount)<span> + {{$company->citiesOperatingCount}}</span>@endif
                      </span>
                      @endif
                  </p>
                  @if($company->sector->name)<p class="meta"><span style="cursor: pointer;"><i class="fa fa-briefcase" aria-hidden="true"></i> {{$company->sector->name}}</span> </p>@else
                  <p class="meta"><span style="cursor: pointer;"><i class="fa fa-briefcase" aria-hidden="true"></i> {{$company->sector->defaultSector}}</span> </p>
                  @endif

                </div>
              </div>

              <hr>

              <h3 class="margin-top">Jobs Available</h3>

              @if($company->jobs)
              <div class="container-tabs margin-top margin-bottom">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                @foreach($company->jobs as $key => $job)
                   <li class="my-profile-tab job-tab">
                        <a hrefclass="job-pill">{{$job->name}}</a>
                    </li>
                @endforeach
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                @foreach($company->jobs as $key => $job)
                  <div role="tabpanel" class="tab-pane active" id="job1">

                    <!-- job 1 -->
                    <div class="row margin-bottom">
                      <div class="col-xs-3 col-sm-2">
                        <div class="number-bullet"> <span>#1</span></div>
                      </div>
                      <div class="col-xs-9 col-sm-10">
                        <h3>Description</h3>
                        <p>{{$job->description}}</p>
                      </div>
                    </div>

                    <div class="row margin-bottom">
                      <div class="col-xs-3 col-sm-2">
                        <div class="number-bullet"> <span>#2</span></div>
                      </div>
                      <div class="col-xs-9 col-sm-10">
                            <h3>Requirements</h3>
                            <p>{{$job->requirement}}</p>
                      </div>
                    </div>

                    <div class="row margin-bottom">
                      <div class="col-xs-3 col-sm-2">
                        <div class="number-bullet"> <span>#3</span></div>
                      </div>
                      <div class="col-xs-9 col-sm-10">
                       <h3>Benefits</h3>
                        <p>{{$job->benefits}}</p>
                      </div>
                    </div>
                    
                     <div class="row margin-bottom">
                      <div class="col-xs-3 col-sm-2">
                        <div class="number-bullet"> <span>#4</span></div>
                      </div>
                      <div class="col-xs-9 col-sm-10">
                       <h3>Why Us</h3>
                        <p>{{$job->why_us}}</p>
                      </div>
                    </div>

                    <div class="row margin-bottom">
                      <div class="col-xs-3 col-sm-2">
                        <div class="number-bullet"> <span>#5</span></div>
                      </div>
                      <div class="col-xs-9 col-sm-10">
                        <h3>Compensantion</h3>
                        <p>{{$job->compensation }}</p>
                      </div>
                    </div>
                    <div class="row margin-bottom">
                      <div class="col-xs-3 col-sm-2">

                      </div>
                      <div class="col-xs-9 col-sm-10">
                        <a href="" class="popup-with-zoom-anim button job-applying">Apply</a>
                      </div>
                    </div>
                    <!-- end job 1 -->


                  </div>
                  @endforeach
                </div>
              </div>
              @endif




            </div>


        </div>
        <div class="col-sm-4 col-md-3 col-md-offset-1">
        
          <div class="margin-bottom">
            <h3 class="big">{{$company->name}}</h3>
            @if($company->description)<p>{{$company->description}}</p>@else if($company->generic->description)
            <p>{{$company->generic->description}}</p>@endif
          </div>
          
          <hr />

        </div>
      </div>

    </div>
  </section>

	<footer class="footer clearfix">
	  <div class="container">
	    <div class="row">
	      <div class="col-sm-4">
	        <img src="img/logo-sharado-orange-converted.png" alt="logo" class="footer__logo">
	      </div>
	      <div class="col-sm-8">
	      <ul class="footer__menu">
	        <li><a href="#">How It Works</a></li>
	        <li><a href ="#">Browse Jobs/Gigs</a></li>
	       <li class="dropdown"><a href="" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">My Profile</a>
				<ul class="dropdown-menu" >
					<li><a href=""><i class="fa fa-lock"></i> Log In</a></li>
					<li><a href="">My Profile</a></li>
					<li><a href="">My Skills</a></li>
					<li><a href="">My Availabilities</a></li>
					<li><a href="">My Applications</a></li>
					<li><a href="">My Alerts</a></li>
				</ul>
			</li>
	        <li><a class="openSignUpModal header-menu" href=""><i class="fa fa-user"></i> Sign Up</a></li>
			<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Hi</a>
				<ul class="dropdown-menu">
					<li><a href="">Sign Out</a></li>
				</ul>
			</li>
	      </ul>
	      </div>
	    </div>
	  </div>
	  <div class="container-blue">
	    <div class="container">
	      <div class="row">
	        <div class="col-sm-4">
	          <p><a href="">Privacy Policy</a></p>
	          <p><a href="">Terms of Service</a></p>
	          <p><a href="">About</a></p>
	          <p><a href="">Contact Us</a></p>
	        </div>
	        <div class="col-sm-4">
	          <p>MAIL: info@sharado.com</p>
	        </div>
	        <div class="col-sm-4">


	        </div>
	      </div>
	    </div>
	  </div>
	  <div class="footer__bottom">
	    <div class="container">
	      <div class="row">
	        <div class="col-md-6">
	          <p>&copy; Copyright -  Sharado 2017 </p>
	        </div>
	        <div class="col-md-6">
	          <p class="alignright"> <a href="#top" class="white"><i class="fa fa-angle-double-up"></i> Top</a></p>
	        </div>
	      </div>
	    </div>
	  </div>
	</footer>

</div>

</body>
</html>
