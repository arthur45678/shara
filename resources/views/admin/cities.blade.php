@extends('admin/app_admin')
@section('content')
<link href="/css/admin/cities.css" rel="stylesheet" type="text/css" />
	<div class="row-fluid">
        <div class="span12">
        	<a href="{{url('/admin/add-city')}}" type="button" class="btn btn-success create-button">Add City</a>
            <a href="{{url('/admin/add-cities')}}" type="button" class="btn btn-success create-button">Import</a>
            <button type="submit" class="button-search" data-toggle="collapse" data-target="#filter"><i style="color:black" class='fa fa-search header-search'></i></button>
            <div id="filter" class="collapse">
            <form action="{{action('Admin\CityController@postSearchCity')}}" method="post" class="form-horizontal search_form">
            {{csrf_field()}}
            <div class="form-group">
                <div class="col-md-3">
                    <select class="form-control select-country" id="selectpicker_country" title="Country" name="country">
                        <option value="">Select Country</option>
                        @foreach($countries as $country)
                            <option id="{{$country->name}}" data-content = "{{$country->id}}" value="{{$country->name}}">{{$country->name}}</option>
                        @endforeach
                    </select>
                    <span class="error-message country-error">{{$errors->first('country')}}</span>
                </div>                    
            </div>
            <div class="form-group">
                <div class="col-md-3 ">
                    <input class="form-control autocomplete_city" type="text" name="city_search" placeholder="Name" maxlength="40" id="autocomplete_city">
                </div>
            </div>
                <button type="submit" class="button-search btn btn-default">Filter</button>
            </form>
            </div>
            
            
            <div class="portlet box blue-chambray">
                <div class="portlet-title">
                    <div class="caption"><i class="icon-users"></i>City Management</div>          
                </div>
                <div class="portlet-body no-more-tables">
                
                    @if (count($cities) > 0)
                    <div class="table-responsive">
                    <table class="table table-bordered table-hover responsive-table">
                            <thead>
                                <tr>
                                    <th> # </th>
                                    <th>Name</th>
                                    <th>Population</th>
                                    <th>Country</th>
                                    <th></th>
                                    <th>Action</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cities as $key => $city)
                                    <tr>
                                        <td>{{$key + 1}} <?php $map_url = "http://maps.google.com/maps?z=12&t=k&q=".$city->latitude.' '.$city->longtitude ?> <a target = "_blank" href="{{$map_url}}"> <i class="fa fa-map-marker" style="float:right"></i></a></td>
                                        <td>{{$city->name}}</td>  
                                        <td>{{$city->population}}</td>
                                        <td>{{$city->country->name}}</td>                                       
                                        <td>
                                        <a href="{{URL::to('/admin/show-city',$city->id)}}" type="button" class="btn btn-info" title="Show">
                                            <i class="fa fa-book"></i></a>
                                        </td>
                                        <td>
                                        <a href = "{{URL::to('/admin/edit-city',$city->id)}}" type="button" class="btn btn-primary" title="Update">
                                        	<i class="fa fa-edit"></i></a>
                                        </td>
                                        <td>
                                        <a type="button" data-toggle="modal" data-target = "#city_delete" href="#small" alt="{{$city->id}}" class="btn btn-danger show_modal_city" title="Delete">
                                        	<i class="fa fa-trash-o"></i></a>
                                        </td>
                                    </tr>
                                @endforeach


                            </tbody>
                        </table>
                        </div>
                        <span class="col-md-offset-5">{{$cities->links()}}</span>
                    @else
                        <h3>No Cities</h3>
                    @endif
                
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade bs-modal-sm" id="city_delete" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Delete City </h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                    <a href=""  class="delete_city"><button type="button" class="btn red">Delete</button></a>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

@endsection