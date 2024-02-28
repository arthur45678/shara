@extends('admin/app_admin')
@section('content')

<link href="/css/admin/users.css" rel="stylesheet" type="text/css" />

<div class="portlet box blue-chambray">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-gift"></i>Show Role
        </div>
        <div class="tools">
            <a href="javascript:;" class="collapse"> </a>
            <a href=""><i class="icon-refresh" style="color: white"></i> </a>
        </div>
    </div>
    <div class="portlet-body">
		<p><b>Name: </b>   <span>{{$role->name}}</span></p>
        
        <p><b>Permissions:</b>    <span>  @foreach($permissions as $key => $permission)
                                        <span class='label label-success role-tag'> {{$permission}} </span>
                                        @if($key !== count($permissions) - 1)
                                            ,
                                        @endif
                                     @endforeach</span></p>
	</div>
</div>

@endsection