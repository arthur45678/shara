@extends('admin/app_admin')
@section('content')
<link href="/css/admin/jobs.css" rel="stylesheet" type="text/css" />
	<div class="row-fluid">
        <div class="span12">
        	<a href="{{action('Admin\JobController@getCreateJob', "new")}}" type="button" class="btn btn-success create-button">Create Job</a>
            <!-- <form action="{{action('Admin\JobController@postSearchJob')}}" method="post" class="form-horizontal search_form">
            {{csrf_field()}}
            <div class="col-md-3 ">
                <input class="form-control" type="text" name="job_search" placeholder="Search ..." maxlength="40">
            </div>
                <button type="submit" class="button-search"><i style="color:black" class='fa fa-search header-search'></i></button>
            </form> -->
            <button type="submit" class="button-search" data-toggle="collapse" data-target="#filter"><i style="color:black" class='fa fa-search header-search'></i></button>
           @include('messages')
            
            <div id="filter" class="collapse">
                <form action="{{action('Admin\JobController@getJobs', ['date', 'asc'])}}" method="get" class="form-horizontal search_form">
                    {{csrf_field()}}
                    <div class="form-group">
                        <div class="col-md-3"> 
                        <select class="form-control" id="country" name="country">
                            <option value="">Select Country</option>
                            @foreach($countries as $country)
                                <option id="{{$country->name}}" class="company_country" data-content="{{$country->id}}" data-code="{{$country->abbreviation}}" @if($country->name == old('country')) selected @endif>{{$country->name}}</option>
                            @endforeach
                        </select>
                        <span class="error-message">{{$errors->first('country')}}</span>
                    </div>                   
                    </div>
<!--                     <div class="form-group">
                        <div class="col-md-3">
                            <input type="text" class="form-control" placeholder="City" name="city" id="city-name">
                            <span class="error-message">{{$errors->first('city')}}</span>
                        </div>
                    </div> -->
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
                    <div class="form-group">
                        <div class="col-md-3 ">
                            <input class="form-control" type="text" name="job_search" placeholder="Name" maxlength="40">
                        </div>
                    </div>
                        <button type="submit" class="button-search btn btn-default">Filter</button>
                </form>
            </div>
            <div class="portlet box blue-chambray">
                <div class="portlet-title">
                    <div class="caption"><i class="icon-users"></i>Job Management</div>          
                </div>
                <div class="portlet-body no-more-tables">
                    @if (count($jobs) > 0)
                    <div class="table-responsive">
                    <table class="table table-bordered table-hover responsive-table">
                            <thead>
                                <tr>
                                    <th> # </th>
                                    <th>
                                        <form action="{{action('Admin\JobController@getJobs', ['company', $type])}}" method="get" class="form-horizontal search_form">
                                            <input type="hidden" name="country" @if(isset($searchDetails['country']))value="{{$searchDetails['country']}}"@endif>
                                            <input type="hidden" name="city" @if(isset($searchDetails['city']))value="{{$searchDetails['city']}}"@endif>
                                            <input type="hidden" name="category" @if(isset($searchDetails['category']))value="{{$searchDetails['category']}}"@endif>
                                            <input type="hidden" name="industry" @if(isset($searchDetails['industry']))value="{{$searchDetails['industry']}}"@endif>
                                            <input type="hidden" name="job_search" @if(isset($searchDetails['job_search']))value="{{$searchDetails['job_search']}}"@endif>
                                            <button class="link-button" type="submit">Company</button>
                                        </form></th>
                                    <th>
                                        <form action="{{action('Admin\JobController@getJobs', ['name', $type])}}" method="get" class="form-horizontal search_form">
                                            <input type="hidden" name="country" @if(isset($searchDetails['country']))value="{{$searchDetails['country']}}"@endif>
                                            <input type="hidden" name="city" @if(isset($searchDetails['city']))value="{{$searchDetails['city']}}"@endif>
                                            <input type="hidden" name="category" @if(isset($searchDetails['category']))value="{{$searchDetails['category']}}"@endif>
                                            <input type="hidden" name="industry" @if(isset($searchDetails['industry']))value="{{$searchDetails['industry']}}"@endif>
                                            <input type="hidden" name="job_search" @if(isset($searchDetails['job_search']))value="{{$searchDetails['job_search']}}"@endif>
                                            <button class="link-button" type="submit">Name</button>
                                        </form></th>
                                    <th>
                                        <form action="{{action('Admin\JobController@getJobs', ['country', $type])}}" method="get" class="form-horizontal search_form">
                                            <input type="hidden" name="country" @if(isset($searchDetails['country']))value="{{$searchDetails['country']}}"@endif>
                                            <input type="hidden" name="city" @if(isset($searchDetails['city']))value="{{$searchDetails['city']}}"@endif>
                                            <input type="hidden" name="category" @if(isset($searchDetails['category']))value="{{$searchDetails['category']}}"@endif>
                                            <input type="hidden" name="industry" @if(isset($searchDetails['industry']))value="{{$searchDetails['industry']}}"@endif>
                                            <input type="hidden" name="job_search" @if(isset($searchDetails['job_search']))value="{{$searchDetails['job_search']}}"@endif>
                                            <button class="link-button" type="submit">Country</button>
                                        </form>
                                    </th>
<!--                                     <th>
                                        <form action="{{action('Admin\JobController@getJobs', ['city', $type])}}" method="get" class="form-horizontal search_form">
                                            <input type="hidden" name="country" @if(isset($searchDetails['country']))value="{{$searchDetails['country']}}"@endif>
                                            <input type="hidden" name="city" @if(isset($searchDetails['city']))value="{{$searchDetails['city']}}"@endif>
                                            <input type="hidden" name="category" @if(isset($searchDetails['category']))value="{{$searchDetails['category']}}"@endif>
                                            <input type="hidden" name="industry" @if(isset($searchDetails['industry']))value="{{$searchDetails['industry']}}"@endif>
                                            <input type="hidden" name="job_search" @if(isset($searchDetails['job_search']))value="{{$searchDetails['job_search']}}"@endif>
                                            <button class="link-button" type="submit">City</button>
                                        </form>
                                    </th> -->
                                    <th></th>
                                    <th>Action</th>
                                    <th>Type</th>
                                    <th></th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($jobs as $key => $job)
                                    <tr>
                                        <td>{{$key + 1}}</td>
                                        <td>@if($job->company){{$job->company->name}}@endif</td> 
                                        <td>{{$job->job_name}}</td> 
                                        <td>@if($job->country){{$job->country->name}}@endif</td> 
                                        <!-- <td>{{$job->city_name}}</td>                                         -->
                                        <td>
                                        <a href="{{URL::to('/admin/show-job',$job->jobId)}}" type="button" class="btn btn-info" title="Show"> 
                                            <i class="fa fa-book"></i></a>
                                        </td>
                                        <td>
                                        <a href = "{{URL::to('/admin/edit-job',$job->jobId)}}" type="button" class="btn btn-primary" title="Update">
                                        	<i class="fa fa-edit"></i></a>
                                        </td>
                                        <td>
                                            @if($job->restrict != 'true')
                                            <a type="button" href="{{URL::to('/admin/make-unpublish-job',$job->jobId)}}" alt="{{$job->jobId}}" class="btn purple btn-outline show_modal_job" title="Delete">
                                            	Unpublish
                                            </a>
                                            @else
                                            <a type="button"  href="{{URL::to('/admin/make-publish-job',$job->jobId)}}" alt="{{$job->jobId}}" class="btn green-haze btn-outline show_modal_job" title="Delete">
                                                Publish
                                            </a>
                                            @endif
                                        </td>
                                        <td>
                                            <a type="button" data-toggle="modal" data-target = "#job_delete" href="#small" alt="{{$job->jobId}}" data-type = "$company->type" class="btn btn-danger show_modal_job" title="Delete">
                                            <i class="fa fa-trash-o"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="table-responsive">
                        <span class="col-md-offset-5">{{$jobs->appends(Request::except('page'))->render()}}</span>
                    @else
                        <h3>No Jobs</h3>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade bs-modal-sm" id="job_delete" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Delete Job </h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                    <a href=""  class="delete_job"><button type="button" class="btn red">Delete</button></a>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

@endsection