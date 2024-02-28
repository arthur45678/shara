@extends('admin/app_admin')
@section('content')
<link href="/css/admin/countries.css" rel="stylesheet" type="text/css" />
	<div class="row-fluid">
        <div class="span12">
        	<a href="{{url('/admin/add-country')}}" type="button" class="btn btn-success create-button">Add Country</a>
            <div class="portlet box blue-chambray">
                <div class="portlet-title">
                    <div class="caption"><i class="icon-users"></i>Country Management</div>          
                </div>
                <div class="portlet-body no-more-tables">
                    @if (count($countries) > 0)
                    <div class="table-responsive">
                    <table class="table table-bordered table-hover responsive-table">
                            <thead>
                                <tr>
                                    <th> # </th>
                                    <th>Name</th>
                                    <th>Abbreviation</th>
                                    <th style="max-width: 800px;">Language</th>
                                    <th>Currency</th>
                                    <th>Metric</th>
                                    <th></th>
                                    <th>Action</th>
                                    <!-- <th></th> -->
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($countries as $key => $country)
                                    <tr>
                                        <td>{{$key + 1}}</td>
                                        <td>{{$country->name}}</td>
                                        <td>{{$country->abbreviation}}</td>
                                        <td>
                                            <span>{{$country->language}}</span>
                                        </td> 
                                        <td>{{$country->currency}}</td>  
                                        <td>{{$country->metric}}</td>                                        
                                        <td>
                                        <a href="{{URL::to('/admin/show-country',$country->id)}}" type="button" class="btn btn-info" title="Show">
                                            <i class="fa fa-book"></i></a>
                                        </td>
                                        <td>
                                        <a href = "{{URL::to('/admin/edit-country',$country->id)}}" type="button" class="btn btn-primary" title="Update">
                                        	<i class="fa fa-edit"></i></a>
                                        </td>
                                        <!-- <td>
                                        <a type="button" data-toggle="modal" data-target = "#country_delete" href="#small" alt="{{$country->id}}" class="btn btn-danger show_modal_country" title="Delete">
                                        	<i class="fa fa-trash-o"></i></a>
                                        </td> -->
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </div>
                        <span class="col-md-offset-5">{{$countries->links()}}</span>
                    @else
                        <h3>No Countries</h3>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade bs-modal-sm" id="country_delete" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Delete Country </h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                    <a href=""  class="delete_country"><button type="button" class="btn red">Delete</button></a>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

@endsection