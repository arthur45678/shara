@extends('admin/app_admin')
@section('content')

<link href="/css/admin/cities.css" rel="stylesheet" type="text/css" />

<div class="portlet box blue-chambray">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-gift"></i>Show City
        </div>
        <div class="tools">
            <a href="javascript:;" class="collapse"> </a>
            <a href=""><i class="icon-refresh" style="color: white"></i> </a>
        </div>
    </div>
    <div class="portlet-body">
		<p><b>Name: </b>   <span>{{$city->name}}</span></p>
        <p><b>Longtitude: </b>   <span>{{$city->longtitude}}</span></p>
        <p><b>Latitude: </b>   <span>{{$city->latitude}}</span></p>
        <p><b>Population: </b>   <span>{{$city->population}}</span></p>
        <p><b>Country: </b>   <span>{{$city->country->name}}</span></p>
        
	</div>
</div>

@endsection