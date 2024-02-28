@extends('admin/app_admin')
@section('content')
<h1>Dashboard content</h1>
                    <!-- BEGIN DASHBOARD STATS 1-->
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="dashboard-stat blue">
                                <div class="visual">
                                    <i class="fa fa-comments"></i>
                                </div>
                                <div class="details">
                                    <div class="number" style="padding-top: 0">
                                        <span data-counter="counterup" >{{$companiesCount}}</span>
                                        @if($companiesCountToday > 0)<span style="display: block; font-size: 15px">Today + {{$companiesCountToday}}</span>@endif
                                    </div>
                                    
                                    <div class="desc"> Companies </div>
                                </div>
                                <a class="more" href="/admin/companies/date/asc"> View more
                                    <i class="m-icon-swapright m-icon-white"></i>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="dashboard-stat red">
                                <div class="visual">
                                    <i class="fa fa-bar-chart-o"></i>
                                </div>
                                <div class="details">
                                    <div class="number" style="padding-top: 0">
                                        <span data-counter="counterup" data-value="{{$jobsCount}}">{{$jobsCount}}</span>
                                        @if($jobsCountToday > 0)<span style="display: block; font-size: 15px">Today + {{$jobsCountToday}}</span>@endif
                                    </div>
                                    <div class="desc"> Jobs </div>
                                </div>
                                <a class="more" href="/admin/jobs/date/asc"> View more
                                    <i class="m-icon-swapright m-icon-white"></i>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="dashboard-stat green">
                                <div class="visual">
                                    <i class="fa fa-shopping-cart"></i>
                                </div>
                                <div class="details">
                                    <div class="number" style="padding-top: 0">
                                        <span data-counter="counterup" data-value="549">{{$usersCount}}</span>
                                        @if($usersCountToday > 0)<span style="display: block; font-size: 15px">Today + {{$usersCountToday}}</span>@endif
                                    </div>
                                    <div class="desc"> Users </div>
                                </div>
                                <a class="more" href="/admin/users/users/date/asc"> View more
                                    <i class="m-icon-swapright m-icon-white"></i>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="dashboard-stat purple">
                                <div class="visual">
                                    <i class="fa fa-globe"></i>
                                </div>
                                <div class="details">
                                    <div class="number" style="padding-top: 0">
                                        <span data-counter="counterup" data-value="89">{{$alertsCount}}</span>
                                        @if($alertsCountToday > 0)<span style="display: block; font-size: 15px">Today + {{$alertsCountToday}}</span>@endif
                                    </div>
                                    <div class="desc"> Alerts </div>
                                </div>
                                <a class="more" href="/admin/alerts"> View more
                                    <i class="m-icon-swapright m-icon-white"></i>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="dashboard-stat yellow">
                                <div class="visual">
                                    <i class="fa fa-globe"></i>
                                </div>
                                <div class="details">
                                    <div class="number" style="padding-top: 0">
                                        <span data-counter="counterup" data-value="89">{{$applicationsCount}}</span>
                                        @if($applicationsCountToday > 0)<span style="display: block; font-size: 15px">Today + {{$applicationsCountToday}}</span>@endif
                                    </div>
                                    <div class="desc"> Applications </div>
                                </div>
                                <a class="more" href="/admin/applicants/date/desc"> View more
                                    <i class="m-icon-swapright m-icon-white"></i>
                                </a>
                            </div>
                        </div>
                        <center>
                            {!! $chart->render() !!}
                        </center>
                    </div>
                    <div class="clearfix"></div>
@endsection