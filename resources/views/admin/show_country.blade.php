@extends('admin/app_admin')
@section('content')

<link href="/css/admin/countries.css" rel="stylesheet" type="text/css" />

<div class="portlet box blue-chambray">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-gift"></i>Show Country
        </div>
        <div class="tools">
            <a href="javascript:;" class="collapse"> </a>
            <a href=""><i class="icon-refresh" style="color: white"></i> </a>
        </div>
    </div>
    <div class="portlet-body">
		<p><b>Name: </b>   <span>{{$country->name}}</span></p>
        <p><b>Abbreviation: </b>   <span>{{$country->abbreviation}}</span></p>
        <p><b>Language: </b>   <span>{{$country->language}}</span></p>
        <p><b>Currency: </b>   <span>{{$country->currency}}</span></p>
        <p><b>Metric: </b>   <span>{{$country->metric}}</span></p>
        
	</div>
</div>

@endsection