@extends('admin/app_admin')
@section('content')

<link href="/css/admin/jobs.css" rel="stylesheet" type="text/css" />

<div class="portlet box blue-chambray">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-gift"></i>Show Job
        </div>
        <div class="tools">
            <a href="javascript:;" class="collapse"> </a>
            <a href=""><i class="icon-refresh" style="color: white"></i> </a>
        </div>
    </div>
    <div class="portlet-body">
        <p><b>Company Name: </b>@if($job->company)<span>{{$job->company->name}}</span>@endif</p>
		<p><b>Name: </b><span>{{$job->name}}</span></p>
        <p><b>Type: </b><span>{{$job->type}}</span></p>
        <p><b>About Company: </b><span>{{$job->about_company}}</span></p>
        <p><b>Why Us: </b><span>{{$job->why_us}}</span></p>
        <p><b>Benefits: </b><span>{{$job->benefits}}</span></p>
        <p><b>Requirement: </b><span>{{$job->requirement}}</span></p>
        <p><b>Schedule: </b><span>{{$job->schedule}}</span></p>
        <p><b>Country: </b>@if($job->country)<span>{{$job->country->name}}</span>@endif</p>
        <p><b>City: </b><span>{{$job->city_name}}</span></p>
        <p><b>Industry: </b>@if($job->sector)<span>{{$job->sector->name}}</span>@endif</p>
        <p><b>Category: </b>@if($job->category)<span>{{$job->category->name}}</span>@endif</p>
        <p><b>Job Applying: </b><span>{{$job->job_applying}}</span></p>
        <p><b>Url to redirect: </b> <span>{{$job->url_to_redirect}}</span></p>
        <!-- <p><b>Activation: </b><span>{{$job->activation}}</span></p> -->
        
	</div>
</div>

@endsection