@extends('admin/app_admin')
@section('content')

<link href="/css/admin/users.css" rel="stylesheet" type="text/css" />

<div class="portlet box blue-chambray">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-gift"></i>Show User
        </div>
        <div class="tools">
            <a href="javascript:;" class="collapse"> </a>
            <a href=""><i class="icon-refresh" style="color: white"></i> </a>
        </div>
    </div>
    <div class="portlet-body">
		<p><b>First Name: </b>   <span>{{$user->first_name}}</span></p>
        <p><b>Last Name: </b>   <span>{{$user->last_name}}</span></p>
        <p><b>Email: </b>   <span>{{$user->email}}</span></p>
        <p><b>Phone number: </b>   <span>{{$user->phone_number}}</span></p>
        <p><b>Birth date: </b>   <span>{{$user->birth_date}}</span></p>
        <p><b>Gender: </b>   <span>{{$user->gender}}</span></p>
        <p><b>Nationality: </b>   <span>{{$user->nationality}}</span></p>
        <p><b>Languages: </b>   <span>  @foreach($user->languages as $key => $language)
                                        <span class='label label-success role-tag'> {{$language->language}} </span>
                                        @if($key !== count($user->languages) - 1)
                                            ,
                                        @endif
                                     @endforeach</span></p>
        @if($user->role == 'from_admin')
        <p><b>Roles:</b>    <span>  @foreach($user_roles as $key => $role)
                                        <span class='label label-success role-tag'> {{$role}} </span>
                                        @if($key !== count($user_roles) - 1)
                                            ,
                                        @endif
                                     @endforeach</span></p>
        @elseif($user->role == 'from_registration')
        <p><b>Skills:</b>    <span>  @foreach($user->skills as $key => $skill)
                                        <span class='label label-success role-tag'> {{$skill->name}} </span>
                                        @if($key !== count($user->skills) - 1)
                                            ,
                                        @endif
                                     @endforeach</span></p>
        <p><b>Applications:</b>    <span>  @foreach($user->applications as $key => $application)
                                        <span class='label label-success role-tag'> {{$application->name}} </span>
                                        @if($key !== count($user->applications) - 1)
                                            ,
                                        @endif
                                     @endforeach</span></p>
        <p><b>User experience: </b>   <span>{{$user->user_experience}}</span></p>
        <p><b>Facebook page: </b>   <span>{{$user->facebook_link}}</span></p>
        <p><b>Country: </b>   <span>{{$user->country}}</span></p>
        <p><b>City: </b>   <span>{{$user->city}}</span></p>
        <p><b>Driving License: </b>   <span>@if($user->driving_license) Yes @else No @endif</span></p>
        <p><b>Transportation: </b>   <span>@if($user->transportation) {{$user->transportation}} @endif</span></p>
        <p><b>Currently Student: </b>   <span>@if($user->currently_student) Yes @else No @endif</span></p>
        <p><b>Education: </b>   <span>@if($user->education) {{$user->education}} @endif</span></p>
        <p><b>Schedule: </b>   <span>@if($user->schedule) {{$user->schedule}} @endif</span></p>
        <p><b>Avilable to work(daily) : </b>   <span>@if($user->days) {{$user->days}} @endif</span></p>
        <p><b>Avilable to work(hourly) : </b>   <span>@if($user->hours) {{$user->hours}} @endif</span></p>
        <p><b>Working area: </b>   <span>@if($user->area) {{$user->area}} @endif</span></p>
        <p><b>Registration Date: </b>   <span>{{$user->regDate}}</span></p>
        <p><b>Registration Time: </b>   <span>{{$user->regTime}}</span></p>
        <p><b>Last Login: </b>   <span>{{$user->last_login}}</span></p>
        <p><b>Ip: </b>   <span>{{$user->ip}}</span></p>
        @endif
	</div>
</div>

@endsection