<!DOCTYPE html>
<!-- 
Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 3.3.5
Version: 4.5.2
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Like: www.facebook.com/keenthemes
Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->

    <head>
        <meta charset="utf-8" />
        <title>Sharado</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />  
        <meta content="" name="description" />
        <meta content="" name="author" />
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
        <link href="/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
        <link href="/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <link href="/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css" rel="stylesheet" type="text/css" />
        <link href="/assets/global/plugins/morris/morris.css" rel="stylesheet" type="text/css" />
        <link href="/assets/global/plugins/fullcalendar/fullcalendar.min.css" rel="stylesheet" type="text/css" />
        <link href="/assets/global/plugins/jqvmap/jqvmap/jqvmap.css" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="/assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="/assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <link href="/assets/layouts/layout/css/layout.min.css" rel="stylesheet" type="text/css" />
        <link href="/assets/layouts/layout/css/themes/darkblue.min.css" rel="stylesheet" type="text/css" id="style_color" />
        <link href="/assets/layouts/layout/css/custom.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME LAYOUT STYLES -->
        <link rel="shortcut icon" href="/images/Sharado-picto.png" /> 

        <link href="/css/bootstrap-select.min.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <link href="/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css" />
        {!! Charts::assets() !!}
    </head>
    <!-- END HEAD -->

    <body class="page-header-fixed page-sidebar-closed-hide-logo page-container-bg-solid page-content-white" >
        <!-- BEGIN HEADER -->
        <div class="page-header navbar navbar-fixed-top">
            <!-- BEGIN HEADER INNER -->
            <div class="page-header-inner ">
                <!-- BEGIN LOGO -->
                <div class="page-logo">
                    <a href="{{url('/admin')}}">
                        <img style="width: 55%;margin-top: 10px;" src="/images/Sharado-logo.png" alt="Sharado" /> </a>
                    <div class="menu-toggler sidebar-toggler"> </div> 
                </div>
                <!-- END LOGO -->
                <!-- BEGIN RESPONSIVE MENU TOGGLER -->
                <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse"> </a>
                <!-- END RESPONSIVE MENU TOGGLER -->

            </div>
            <!-- END HEADER INNER -->
        </div>
        <!-- END HEADER -->
        <!-- BEGIN HEADER & CONTENT DIVIDER -->
        <div class="clearfix"> </div>
        <!-- END HEADER & CONTENT DIVIDER -->
        <!-- BEGIN CONTAINER -->
        <div class="page-container">
            <!-- BEGIN SIDEBAR -->
            <div class="page-sidebar-wrapper">
                <!-- BEGIN SIDEBAR -->
                <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
                <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
                <div class="page-sidebar navbar-collapse collapse">
                    <!-- BEGIN SIDEBAR MENU -->
                    <!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
                    <!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
                    <!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
                    <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
                    <!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
                    <!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
                    <ul class="page-sidebar-menu  page-header-fixed page-sidebar-menu-light " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" style="padding-top: 20px">
                        <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
                        <li class="sidebar-toggler-wrapper hide">
                            <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
                            <div class="sidebar-toggler"> </div>
                            <!-- END SIDEBAR TOGGLER BUTTON -->
                        </li>
                        <!-- DOC: To remove the search box from the sidebar you just need to completely remove the below "sidebar-search-wrapper" LI element -->
                        <li class="nav-item start active open">
                            <a href="{{url('/admin')}}" class="nav-link">
                                <i class="icon-home"></i>
                                <span class="title">Dashboard</span>
                            </a>
                        </li>
                        <li class="heading">
                            <h3 class="uppercase">General</h3>
                        </li>
                        <li class="nav-item  ">
                            <a href="{{action('Admin\CompanyController@getCompanies', ['date', 'asc']) }}" class="nav-link">
                                <i class="fa fa-tachometer"></i>
                                <span class="title">Company</span>
                            </a>
                        </li>
                        @if($currentUser->admin_type == 'generic')
                        <li class="nav-item  ">
                            <a href="{{url('/admin/jobs/date/asc')}}" class="nav-link">
                                <i class="fa fa-cog fa-fw"></i>
                                <span class="title">Job</span>
                            </a>
                        </li>
                    
                        <li class="heading">
                            <h3 class="uppercase">Data Management</h3>
                        </li>
                        <li class="nav-item  ">
                            <a href="{{url('/admin/sectors')}}" class="nav-link">
                                <i class="fa fa-cog fa-fw"></i>
                                <span class="title">Industry</span>
                            </a>
                        </li>
                        <li class="nav-item  ">
                            <a href="{{url('/admin/categories')}}" class="nav-link nav-toggle">
                                <i class="fa fa-cog fa-fw"></i>
                                <span class="title">Job Category</span>
                            </a>
                        </li>
                        <li class="nav-item  ">
                            <a href="{{url('/admin/countries')}}" class="nav-link">
                                <i class="fa fa-cog fa-fw"></i>
                                <span class="title">Countries</span>
                            </a>
                        </li>
                        <li class="nav-item  ">
                            <a href="{{url('/admin/translations')}}" class="nav-link">
                                <i class="fa fa-cog fa-fw"></i>
                                <span class="title">Translations</span>
                            </a>
                        </li>
                        <!-- <li class="nav-item  ">
                            <a href="{{url('/admin/cities')}}" class="nav-link">
                                <i class=" fa fa-cog fa-fw"></i>
                                <span class="title">Cities</span>
                            </a>
                        </li> -->
                        <li class="heading">
                            <h3 class="uppercase">User Management</h3>
                        </li>
                        <li class="nav-item  ">
                            <a href="{{url('/admin/users/admins/date/asc')}}" class="nav-link">
                                <i class="fa fa-user fa-fw"></i>
                                <span class="title">Admins</span>
                            </a>
                        </li>
                        <li class="nav-item  ">
                            <a href="{{url('/admin/users/users/date/asc')}}" class="nav-link">
                                <i class="fa fa-user fa-fw"></i>
                                <span class="title">Users</span>
                            </a>
                        </li>
                        <li class="nav-item  ">
                            <a href="{{url('/admin/roles')}}" class="nav-link">
                                <i class="fa fa-users"></i>
                                <span class="title">Roles</span>
                            </a>
                        </li>
                        <li class="nav-item  ">
                            <a href="{{url('/admin/contacts')}}" class="nav-link">
                                <i class="fa fa-users"></i>
                                <span class="title">Contacts</span>
                            </a>
                        </li>
                        <li class="nav-item  ">
                            <a href="{{url('/admin/alerts')}}" class="nav-link">
                                <i class="fa fa-users"></i>
                                <span class="title">Alerts</span>
                            </a>
                        </li>
                        <li class="nav-item  ">
                            <a href="{{url('/admin/applicants/date/desc')}}" class="nav-link">
                                <i class="fa fa-users"></i>
                                <span class="title">Applications</span>
                            </a>
                        </li>
                        @endif

                         <li class="nav-item  ">
                            <a href="{{url('/admin/location-details')}}" class="nav-link">
                                <i class="fa fa-users"></i>
                                <span class="title">Current location</span>
                            </a>
                        </li>

                        <li class="heading">
                            <h3 class="uppercase">Logout</h3>
                        </li>
                        <li class="nav-item  ">
                            <a href="{{ url('/admin/logout') }}" class="nav-link">
                                <i class="fa fa-sign-out"></i>
                                <span class="title">Logout</span>
                            </a>
                        </li>
                    </ul>
                    <!-- END SIDEBAR MENU -->
                    <!-- END SIDEBAR MENU -->
                </div>
                <!-- END SIDEBAR -->
            </div>
            <!-- END SIDEBAR -->
            <!-- BEGIN CONTENT -->
            <div class="page-content-wrapper">
                <!-- BEGIN CONTENT BODY -->
                <div class="page-content">
                    <!-- BEGIN PAGE HEADER-->
                    <!-- BEGIN THEME PANEL -->
                    
                    <!-- BEGIN PAGE BAR -->
                    <div class="page-bar">
                        <ul class="page-breadcrumb">
                            <li>
                                <a >Home</a>
                                <i class="fa fa-circle"></i>
                            </li>
                            <li>
                                <span>Dashboard</span>
                            </li>
                        </ul>
                        
                    </div>
                    <!-- END PAGE BAR -->
                    <!-- END PAGE HEADER-->
                    @yield('content')
            <!-- BEGIN QUICK SIDEBAR -->
            
            
        </div>
        <!-- END CONTAINER -->
        <!-- BEGIN FOOTER -->
        <div class="page-footer">
            
            <div class="scroll-to-top">
                <i class="icon-arrow-up"></i>
            </div>
        </div>
        <!-- END FOOTER -->
        <!--[if lt IE 9]>
<script src="../assets/global/plugins/respond.min.js"></script>
<script src="../assets/global/plugins/excanvas.min.js"></script> 
<![endif]-->
        <!-- BEGIN CORE PLUGINS -->
        <script src="/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
        <script src="/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="/assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
        <script src="/assets/global/plugins/morris/morris.js" type="text/javascript"></script>
        <script src="/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
        <script src="/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        <script src="/assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
        <script src="/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->

        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="/assets/global/scripts/app.min.js" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <script src="/assets/pages/scripts/dashboard.min.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL SCRIPTS -->
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <script src="/assets/layouts/layout/scripts/layout.min.js" type="text/javascript"></script>
        <script src="/assets/layouts/layout/scripts/demo.min.js" type="text/javascript"></script>
        <script src="/assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>

        <script src="/js/bootstrap-select.min.js" type="text/javascript"></script>
        <script src="/js/delete_user.js" type="text/javascript"></script>
        <script src="/js/delete_role.js" type="text/javascript"></script>
        <script src="/js/delete_sector.js" type="text/javascript"></script>
        <script src="/js/delete_category.js" type="text/javascript"></script>
        <script src="/js/delete_country.js" type="text/javascript"></script>
        <script src="/js/delete_city.js" type="text/javascript"></script>
        <script src="/js/delete_job.js" type="text/javascript"></script>
        <script src="/js/detach_job.js" type="text/javascript"></script>
        <script src="/js/delete_company.js" type="text/javascript"></script>
        <script src="/js/select_company_country.js" type="text/javascript"></script>

        
        <!-- <script src="/js/file-upload.main.js" type="text/javascript"></script> -->


        <script src="/angular/keys/google_key.js"></script>
        
        <script src="/js/logo_upload.js" type="text/javascript"></script>
        <script src="/js/counter.js" type="text/javascript"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <!-- <script src="/js/job_location.js" type="text/javascript"></script> -->
        <!-- <script src="/js/job_location_test.js" type="text/javascript"></script> -->


        <script src="/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>
        <script src="/js/logo.js" type="text/javascript"></script>

        @yield('scripts')
        <!-- END THEME LAYOUT SCRIPTS --> 
    </body>

</html>