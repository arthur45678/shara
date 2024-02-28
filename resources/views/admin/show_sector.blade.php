@extends('admin/app_admin')
@section('content')

<link href="/css/admin/sectors.css" rel="stylesheet" type="text/css" />

<div class="portlet box blue-chambray">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-gift"></i>Show Industry
        </div>
        <div class="tools">
            <a href="javascript:;" class="collapse"> </a>
            <a href=""><i class="icon-refresh" style="color: white"></i> </a>
        </div>
    </div>
    <div class="portlet-body">
    <div class="table-responsive">
    <table class="table table-bordered table-hover responsive-table">
        <thead>
            <tr>            
                @foreach($locales as $locale)
                    <th class="locale-label"> {{$locale}} </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            <tr>
                @foreach($locales as $locale)
                   
                    <td> @if(isset($translated_names[$locale])) {{$translated_names[$locale]}}  @endif</th>
                   
                @endforeach
            </tr>
        </tbody>
    </table>
    </div>
	</div>
</div>

@endsection