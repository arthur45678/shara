@extends('admin/app_admin')
@section('content')

<link href="/css/admin/companies.css" rel="stylesheet" type="text/css" />

<div class="portlet box blue-chambray">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-gift"></i>Show Company
        </div>
        <div class="tools">
            <a href="javascript:;" class="collapse"> </a>
            <a href=""><i class="icon-refresh" style="color: white"></i> </a>
        </div>
    </div>
    <div class="portlet-body">
		<p><b>Name: </b>   <span>{{$company->name}}</span></p>
        <p><b>Logo: </b>   <span>@if($company->logo)<img src="/uploads/{{$company->logo}}" alt="" style="max-width: 200px; max-height: 150px; border:none"/>@endif</span></p>
        <p><b>Type: </b>   <span>{{$company->type}}</span></p>
        <p><b>URL: </b>   <span>{{$company->url}}</span></p>
        <p><b>Description: </b>   <span>{{$company->description}}</span></p>
        <p><b>Short Description: </b>   <span>{{$company->short_description}}</span></p>
        <p><b>Facebook url: </b>   <span>{{$company->facebook_url}}</span></p>
        <p><b>Linkedin url: </b>   <span>{{$company->linkedin_url}}</span></p>
        <p><b>Twitter url: </b>   <span>{{$company->twitter_url}}</span></p>
        <p><b>Crunchbase url: </b>   <span>{{$company->crunchbase_url}}</span></p>
        <p><b>Ios url: </b>   <span>{{$company->ios_url}}</span></p>
        <p><b>Android url: </b>   <span>{{$company->android_url}}</span></p>
        <p><b>Country: </b>   <span>{{$company->country->name}}</span></p>
        @if($company->city)
        <p><b>City: </b>   <span>{{$company->city->name}}</span></p>
        @endif
        @if($company->sector)
        <p><b>Industry: </b>   <span>{{$company->sector->name}}</span></p>
        @endif
        @if($company->category)
        <p><b>Category: </b>   <span>{{$company->category->name}}</span></p>
        @endif
        <p><b>Looking for: </b>   <span>{{$company->looking_for}}</span></p>
        <p><b>Requirement: </b>   <span>{{$company->requirement}}</span></p>
        <p><b>Compensation: </b>   <span>{{$company->compensation}}</span></p>
        <p><b>Why Us: </b>   <span>{{$company->why_us}}</span></p>
        <p><b>Job Applying: </b>   <span>{{$company->job_applying}}</span></p>
        @if(isset($company->url_to_redirect))
        <p><b>Url to redirect: </b>   <span>{{$company->url_to_redirect}}</span></p>
        @endif
        
	</div>
</div>

@endsection