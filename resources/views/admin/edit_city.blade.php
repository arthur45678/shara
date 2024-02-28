@extends('admin/app_admin')
@section('content')
<link href="/css/admin/countries.css" rel="stylesheet" type="text/css" />
<div class="portlet box blue-chambray">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-gift"></i>Edit City
        </div>
        <div class="tools">
            <a href="javascript:;" class="collapse"> </a>
            <a href=""><i class="icon-refresh" style="color: white"></i> </a>
        </div>
    </div>
    <div class="portlet-body form">
    <form method="post" files = "true" class="form-horizontal" id="create-user-form" action="{{action('Admin\CityController@postEditCity')}}">
        {{csrf_field()}}
            
            <div class="form-body">
                <div class="form-group">
                
                
                    <label class="col-md-3 control-label">Name</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" placeholder="Name" maxlength="40" name="name" value="{{ $city->name }}"/>
                        <span class="error-message">{{$errors->first('name')}}</span>  
                        <span class="error-message">{{Session::get('same_name')}}</span> 
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label">Longtitude</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" placeholder="Longtitude" name="longtitude" value="{{ $city->longtitude }}"/> 
                        <span class="error-message">{{$errors->first('longtitude')}}</span>

                    </div>
                </div>
                <div class="form-group">               
                    <label class="col-md-3 control-label">Latitude</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" placeholder="Latitude" name="latitude" value="{{ $city->latitude }}"/>
                        <span class="error-message">{{$errors->first('latitude')}}</span>                      
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Population</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" placeholder="Population" name="population" value="{{ $city->population }}"/>
                        <span class="error-message">{{$errors->first('population')}}</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Country</label>
                    <div class="col-md-6">
                        <select class="selectpicker show-tick select-country" id="selectpicker" title="Country" name="country">
                            @foreach($countries as $country)
                                <option value="{{$country->id}}" data-content = "{{$country->name}}" @if($city->country->name == $country->name) selected @endif>$country->name</option>
                            @endforeach
                        </select>
                    </div>
                    <span class="error-message">{{$errors->first('country')}}</span>
                </div>
                <input type="hidden" name="city_id" value="{{$city->id}}">
            </div>
            <div class="form-actions form-actions-create-item">
                <div class="row">
                    <div class="col-md-offset-3 col-md-9">
                        <button type="submit" class="btn green">Submit</button>
                        <a type="button" class="btn grey-salsa btn-outline" href="{{URL::to('admin/cities')}}">Cancel</a>
                    </div>
                </div>
            </div>

    </form>
    </div>
</div>

@endsection