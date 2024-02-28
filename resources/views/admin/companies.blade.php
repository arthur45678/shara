@extends('admin/app_admin')
@section('content')
<link href="/css/admin/companies.css" rel="stylesheet" type="text/css" />
	<div class="row-fluid">
        <div class="span12">
        	<a href="{{url('/admin/create-company')}}" type="button" class="btn btn-success create-button">Create Company</a>
            <!-- <form action="{{action('Admin\CompanyController@postSearchCompany')}}" method="post" class="form-horizontal search_form">
            {{csrf_field()}}
            <div class="col-md-3 ">
                <input class="form-control" type="text" name="company_search" placeholder="Search ..." maxlength="40">
            </div>
                <button type="submit" class="button-search"><i style="color:black" class='fa fa-search header-search'></i></button>
            </form> -->
            <button type="submit" class="button-search" data-toggle="collapse" data-target="#filter"><i style="color:black" class='fa fa-search header-search'></i></button>
            @include('messages')
            <div id="filter" class="collapse">
                <form action="{{action('Admin\CompanyController@getCompanies', ['date', 'asc'])}}" method="get" class="form-horizontal search_form">
                    {{csrf_field()}}
                    <div class="form-group">
                        <div class="col-md-3">
                            <select class="form-control" id="country" name="country">
                                <option value="">Select Country</option>
                                @foreach($countries as $country)
                                    <option id="{{$country->name}}" class="company_country" data-content = "{{$country->id}}" data-code="{{$country->abbreviation}}" @if($country->name == old('country')) selected @endif>{{$country->name}}</option>
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
                            <input type="text" class="form-control" placeholder="City" name="city" id="city-name">
                            <span class="error-message">{{$errors->first('city')}}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-3">
                            <select class="form-control select-sector" id="selectpicker_sector" title="Select Industry" name="industry">
                            <option value="">Select Industry</option>
                            @foreach($sectors as $sector)
                                <option id="{{$sector->sectorName}}" class="company_country" data-content = "{{$sector->sectorName}}" value="{{$sector->sector_id}}" @if($sector->sector_id == old('sector')) selected @endif>{{$sector->sectorName}}</option>
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
                                <option id="{{$category->categoryName}}" class="company_category" data-content = "{{$category->categoryName}}" value="{{$category->category_id}}" @if($category->category_id == old('category')) selected @endif>{{$category->categoryName}}</option>
                            @endforeach
                            </select>
                            <span class="error-message">{{$errors->first('category')}}</span>
                        </div>
                    </div>
                    <!-- <input type="hidden" name="old_results[]" value="{{$companies}}"> -->
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
                    @if (count($companies) > 0)
                    <div class="table-responsive">
                    <table class="table table-bordered table-hover responsive-table">
                            <thead>
                                <tr>
                                    <th> <label>#</label></th>
                                    <th>
                                        <form action="{{action('Admin\CompanyController@getCompanies', ['name', $type])}}" method="get" class="form-horizontal search_form">
                                            <input type="hidden" name="country" @if(isset($searchDetails['country']))value="{{$searchDetails['country']}}"@endif>
                                            <input type="hidden" name="city" @if(isset($searchDetails['city']))value="{{$searchDetails['city']}}"@endif>
                                            <input type="hidden" name="category" @if(isset($searchDetails['category']))value="{{$searchDetails['category']}}"@endif>
                                            <input type="hidden" name="industry" @if(isset($searchDetails['industry']))value="{{$searchDetails['industry']}}"@endif>
                                            <input type="hidden" name="company_search" @if(isset($searchDetails['company_search']))value="{{$searchDetails['company_search']}}"@endif>
                                            <button class="link-button" type="submit">Name</button>
                                        </form>
                                    </th>
                                    <th>
                                        <form action="{{action('Admin\CompanyController@getCompanies', ['industry', $type])}}" method="get" class="form-horizontal search_form">
                                            <input type="hidden" name="country" @if(isset($searchDetails['country']))value="{{$searchDetails['country']}}"@endif>
                                            <input type="hidden" name="city" @if(isset($searchDetails['city']))value="{{$searchDetails['city']}}"@endif>
                                            <input type="hidden" name="category" @if(isset($searchDetails['category']))value="{{$searchDetails['category']}}"@endif>
                                            <input type="hidden" name="industry" @if(isset($searchDetails['industry']))value="{{$searchDetails['industry']}}"@endif>
                                            <input type="hidden" name="company_search" @if(isset($searchDetails['company_search']))value="{{$searchDetails['company_search']}}"@endif>
                                            <button class="link-button" type="submit">Industry</button>
                                        </form></th>
                                    <th>
                                        <form action="{{action('Admin\CompanyController@getCompanies', ['category', $type])}}" method="get" class="form-horizontal search_form">
                                            <input type="hidden" name="country" @if(isset($searchDetails['country']))value="{{$searchDetails['country']}}"@endif>
                                            <input type="hidden" name="city" @if(isset($searchDetails['city']))value="{{$searchDetails['city']}}"@endif>
                                            <input type="hidden" name="category" @if(isset($searchDetails['category']))value="{{$searchDetails['category']}}"@endif>
                                            <input type="hidden" name="industry" @if(isset($searchDetails['industry']))value="{{$searchDetails['industry']}}"@endif>
                                            <input type="hidden" name="company_search" @if(isset($searchDetails['company_search']))value="{{$searchDetails['company_search']}}"@endif>
                                            <button class="link-button" type="submit">Category</button>
                                        </form></th>
                                    <th></th>
                                    <th><lable class="not-link">Action</lable></th>
                                    <th><lable class="not-link">Type</lable></th>
                                    <th></th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($companies as $key => $company)
                                    <tr>
                                        <td>
                                            {{($page-1)*50 + $key + 1}}
                                            <a class="urlClass" data-href="{{$company->url}}" target='_blank'><img src="/images/globe-point.png" style="width: 20px; float: right"></a>
                                        </td>
                                        <td>{{$company->company_name}}</td>
                                        @if($company->sector)
                                        <td>{{$company->sector->name}}</td>
                                        @else
                                        <td>None</td>
                                        @endif
                                        @if($company->category)
                                        <td>{{$company->category->name}}</td>
                                        @else
                                        <td>None</td>
                                        @endif                                    
                                        <td>
                                        <a href="{{URL::to('/admin/show-company',$company->company_id)}}" type="button" class="btn btn-info" title="Show">
                                            <i class="fa fa-book"></i></a>
                                        </td>
                                        <td>
                                        <a href = "{{URL::to('/admin/edit-company', ['company_id' => $company->company_id, 'company_sub_type' => 'generic'])}}" type="button" class="btn btn-primary" title="Update">
                                        	<i class="fa fa-edit"></i></a>
                                        </td>
                                        <!-- <td>
                                        <a href = "{{URL::to('/admin/subsidiaries',$company->company_id)}}" type="button" class="btn btn-primary" >
                                            <i class="fa fa-edit"></i> Subsidiaries </a>
                                        </td> -->
                                        <td>
                                            @if($company->restrict != 'true')
                                            <a type="button" href="{{URL::to('/admin/make-unpublish',$company->company_id)}}"  class="btn purple btn-outline " title="Delete">
                                                Unpublish
                                            </a>
                                            @else
                                            <a type="button"  href="{{URL::to('/admin/make-publish',$company->company_id)}}" class="btn green-haze btn-outline " title="Delete">
                                                Publish
                                            </a>
                                            @endif
                                        </td>
                                        <td>
                                            <a type="button" data-toggle="modal" data-target = "#company_delete" href="#small" alt="{{$company->company_id}}" data-type = "generic" class="btn btn-danger show_modal_company" title="Delete">
                                            <i class="fa fa-trash-o"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </div>
                        <input type="hidden" name="country" @if(isset($searchDetails['country']))value="{{$searchDetails['country']}}"@endif>
                        <input type="hidden" name="city" @if(isset($searchDetails['city']))value="{{$searchDetails['city']}}"@endif>
                        <input type="hidden" name="category" @if(isset($searchDetails['category']))value="{{$searchDetails['category']}}"@endif>
                        <input type="hidden" name="industry" @if(isset($searchDetails['industry']))value="{{$searchDetails['industry']}}"@endif>
                        <input type="hidden" name="company_search" @if(isset($searchDetails['company_search']))value="{{$searchDetails['company_search']}}"@endif>
                        <span class="col-md-offset-5">{{$companies->appends(\Input::except('page'))->render()}}</span>
                    @else
                        <h3>No Companies</h3>
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
@section('scripts')
<script type="text/javascript">
$(function(){
    $(".urlClass").click(function(){
        var url = $(this).attr('data-href');
        var array = url.split(':');
        console.log(array[0] !== 'http')
        if(array[0] == 'http' || array[0] == 'https')
        {
            $('.urlClass').attr('href', url);
            return
        }
        url = 'http://'+url;
        $('.urlClass').attr('href', url);
        console.log(url)
    })
})
</script>
@endsection