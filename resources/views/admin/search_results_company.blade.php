@extends('admin/app_admin')
@section('content')
<link href="/css/admin/companies.css" rel="stylesheet" type="text/css" />
	<div class="row-fluid">
        <div class="span12">
        	<a href="{{url('/admin/create-company')}}" type="button" class="btn btn-success create-button">Create Company</a>
            <button type="submit" class="button-search" data-toggle="collapse" data-target="#filter"><i style="color:black" class='fa fa-search header-search'></i></button>
            <div id="filter" class="collapse">
                <form action="{{action('Admin\CompanyController@postSearchCompany')}}" method="post" class="form-horizontal search_form">
                    {{csrf_field()}}
                    <div class="form-group">
                        <div class="col-md-3">
                        <select class="form-control select-country" id="country" name="country">
                            <option value="">Select Country</option>
                            @foreach($countries as $country)
                                <option id="{{$country->name}}" class="company_country" data-content = "{{$country->id}}" @if($country->name == old('country')) selected @endif>{{$country->name}}</option>
                            @endforeach
                        </select> 
                        <span class="error-message">{{$errors->first('country')}}</span>
                    </div>                   
                    </div>
                    <div class="form-group">
                        <div class="col-md-3">
                            <!-- <select class="form-control select-city" id="selectpicker_city" title="Select City" old-name="{{ old('city') }}" name="city">
                             <option value="">Select City</option>
                            </select>
                            <span class="error-message">{{$errors->first('city')}}</span> -->
                            <input type="text" class="form-control autocomplete_city"  placeholder="City" name="city" id="city-name">
                            <span class="error-message">{{$errors->first('city')}}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-3">
                            <select class="form-control select-sector" id="selectpicker_sector" title="Select Industry" name="industry">
                            <option value="">Select Industry</option>
                            @foreach($sectors as $sector)
                                <option id="{{$sector->name}}" class="company_country" data-content = "{{$sector->name}}" value="{{$sector->id}}" @if($sector->id == old('sector')) selected @endif>{{$sector->name}}</option>
                            @endforeach
                            </select>
                            <span class="error-message">{{$errors->first('industry')}}</span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-md-3">
                            <select class="form-control select-category" id="selectpicker_category" title="Select Job Category" name="category">
                            <option value="">Select Job Category</option>
                            @foreach($categories as $category)
                                <option id="{{$category->name}}" class="company_category" data-content = "{{$category->name}}" value="{{$category->id}}" @if($category->id == old('category')) selected @endif>{{$category->name}}</option>
                            @endforeach
                            </select>
                            <span class="error-message">{{$errors->first('category')}}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-3 ">
                            <input class="form-control" type="text" name="company_search" placeholder="Name" maxlength="40">
                        </div>
                    </div>
                        <button type="submit" class="button-search btn btn-default">Filter</button>
                </form>
            </div>
            <div class="portlet box blue-chambray">
                <div class="portlet-title">
                    <div class="caption"><i class="icon-users"></i>Company Management</div>          
                </div>
                <div class="portlet-body no-more-tables">
                    @if (count($search_results) > 0)
                    <div class="table-responsive">
                    <table class="table table-bordered table-hover responsive-table">
                            <thead>
                                <tr>
                                    <th> # </th>
                                    <th>Name</th>
                                    <th></th>
                                    <th></th>
                                    <th>Action</th>
                                    <th></th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($search_results as $key => $company)
                                    <tr>
                                        <td>{{$key + 1}}</td>
                                        <td>{{$company->name}}</td>                                        
                                        <td>
                                        <a href="{{URL::to('/admin/show-company',$company->id)}}" type="button" class="btn btn-info">
                                            <i class="fa fa-book"></i> Show </a>
                                        </td>
                                        <td>
                                        <a href = "{{URL::to('/admin/edit-company', ['company_id' => $company->id, 'company_sub_type' => 'generic'])}}" type="button" class="btn btn-primary" >
                                        	<i class="fa fa-edit"></i> Update </a>
                                        </td>
                                        <!-- <td>
                                        <a href = "{{URL::to('/admin/subsidiaries',$company->id)}}" type="button" class="btn btn-primary" >
                                            <i class="fa fa-edit"></i> Subsidiaries </a>
                                        </td> -->
                                        <td>
                                        <a type="button" data-toggle="modal" data-target = "#company_delete" href="#small" alt="{{$company->id}}" class="btn btn-danger show_modal_company">
                                        	<i class="fa fa-trash-o"></i> Delete </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </div>
                    @else
                        <h3>No Results</h3>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade bs-modal-sm" id="company_delete" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Delete Company </h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                    <a href=""  class="delete_company"><button type="button" class="btn red">Delete</button></a>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

@endsection