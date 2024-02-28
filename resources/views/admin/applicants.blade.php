@extends('admin/app_admin')
@section('content')
<link href="/css/admin/users.css" rel="stylesheet" type="text/css" />
	<div class="row-fluid">
        <div class="span12"> 
            <form action="{{action('Admin\UserController@getExportCsv')}}" method="get" class="form-horizontal search_form">
            <input type="hidden" value="{{$applicantsIds}}" name="filtered_users">
             <button type="submit" class="button-search btn btn-success">Export</button>
            </form>
                <form action="{{action('Admin\UserController@getApplicants', ['param' => $param, 'order' => $order ])}}" method="get" class="form-horizontal search_form">
                    {{csrf_field()}}
                    <div class="form-group col-md-3 applicant-filter">
                        <div> 
                        <select class="form-control" id="company_applicants" name="company">
                            <option value="">Select Company</option>
                            @foreach($companies as $company)
                                <option id="{{$company->id}}" class="company_country" data-content="{{$company->id}}" value="{{$company->id}}" @if(isset($filteredCompany)) @if($company->id == $filteredCompany) selected @endif @endif>{{$company->name}} / {{$company->country->name}} </option>
                            @endforeach
                        </select>
                        </div>                   
                    </div>
                    <div class="form-group col-md-3 applicant-filter">
                        <div>
                            <select class="form-control" id="job_applicants" title="Select Job" name="job">

                            </select>
                        </div>
                    </div>
                    <div class="form-group col-md-3 applicant-filter">
                        <div>
                            <select class="form-control select-category" id="selectpicker_category" title="Select Job Category" name="category">
                            <option value="">Select Job Category</option>
                            @foreach($categories as $category)
                                <option id="{{$category->categoryName}}" class="company_category" data-content = "{{$category->categoryName}}" value="{{$category->category_id}}" @if(isset($filteredCategory)) @if($category->category_id == $filteredCategory) selected @endif @endif>{{$category->categoryName}}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-md-3 applicant-filter">
                        <div>
                            <select class="form-control" id="country" name="country">
                                <option value="">Select Country</option>
                                @foreach($countries as $country)
                                    <option id="{{$country->name}}" class="company_country" data-content = "{{$country->id}}" data-code="{{$country->abbreviation}}" value="{{$country->id}}" @if(isset($filteredCountry)) @if($country->id == $filteredCountry) selected @endif @endif>{{$country->name}}</option>
                                @endforeach
                            </select> 
                        </div>                   
                    </div>
                    <input type="hidden" id="filteredJob" @if(isset($filteredJob)) value="{{$filteredJob}}" @endif />
                        <button type="submit" class="button-search btn btn-default applicant-filter">Filter</button>
                </form>
            
            <div class="portlet box blue-chambray">
                <div class="portlet-title">
                    <div class="caption"><i class="icon-users"></i>Applications Management</div>  
                    <div class="applicants-count caption" style="float: right;">{{$count}}</div>        
                </div>
                <div class="portlet-body no-more-tables">
                    @if (count($applicants) > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover responsive-table">
                            <thead>
                                <tr>
                                    <th> # </th>
                                    <th><a href="{{action('Admin\UserController@getApplicants', ['param' => 'name', 'order' => ($order == 'asc') ? 'desc' : 'asc' , 'company' => (isset($filteredCompany)) ? $filteredCompany : '', 'job' => (isset($filteredJob)) ? $filteredJob : '', 'category' => (isset($filteredCategory)) ? $filteredCategory : '', 'country' => (isset($filteredCountry)) ? $filteredCountry : '' ])}}">Name</a></th>
                                    
                                    <!-- <th><a href="{{URL::to('/admin/applicants',['param' => 'name', 'order' => ($order == 'asc') ? 'desc' : 'asc'])}}">Name</a></th> -->
                                    <th><a href="{{action('Admin\UserController@getApplicants', ['param' => 'email', 'order' => ($order == 'asc') ? 'desc' : 'asc' , 'company' => (isset($filteredCompany)) ? $filteredCompany : '', 'job' => (isset($filteredJob)) ? $filteredJob : '', 'category' => (isset($filteredCategory)) ? $filteredCategory : '', 'country' => (isset($filteredCountry)) ? $filteredCountry : '' ])}}">Email</a></th>
                                    <th><a href="{{action('Admin\UserController@getApplicants', ['param' => 'country', 'order' => ($order == 'asc') ? 'desc' : 'asc' , 'company' => (isset($filteredCompany)) ? $filteredCompany : '', 'job' => (isset($filteredJob)) ? $filteredJob : '', 'category' => (isset($filteredCategory)) ? $filteredCategory : '', 'country' => (isset($filteredCountry)) ? $filteredCountry : '' ])}}">Country</a></th>
                                    <th><a href="{{action('Admin\UserController@getApplicants', ['param' => 'city', 'order' => ($order == 'asc') ? 'desc' : 'asc' , 'company' => (isset($filteredCompany)) ? $filteredCompany : '', 'job' => (isset($filteredJob)) ? $filteredJob : '', 'category' => (isset($filteredCategory)) ? $filteredCategory : '', 'country' => (isset($filteredCountry)) ? $filteredCountry : '' ])}}">City</a></th>
                                    <th><a href="{{action('Admin\UserController@getApplicants', ['param' => 'company', 'order' => ($order == 'asc') ? 'desc' : 'asc' , 'company' => (isset($filteredCompany)) ? $filteredCompany : '', 'job' => (isset($filteredJob)) ? $filteredJob : '', 'category' => (isset($filteredCategory)) ? $filteredCategory : '', 'country' => (isset($filteredCountry)) ? $filteredCountry : '' ])}}">Company</a></th>
                                    <th><a href="{{action('Admin\UserController@getApplicants', ['param' => 'job', 'order' => ($order == 'asc') ? 'desc' : 'asc' , 'company' => (isset($filteredCompany)) ? $filteredCompany : '', 'job' => (isset($filteredJob)) ? $filteredJob : '', 'category' => (isset($filteredCategory)) ? $filteredCategory : '', 'country' => (isset($filteredCountry)) ? $filteredCountry : '' ])}}">Job</a></th>
                                    <th><a href="{{action('Admin\UserController@getApplicants', ['param' => 'date', 'order' => ($order == 'asc') ? 'desc' : 'asc' , 'company' => (isset($filteredCompany)) ? $filteredCompany : '', 'job' => (isset($filteredJob)) ? $filteredJob : '', 'category' => (isset($filteredCategory)) ? $filteredCategory : '', 'country' => (isset($filteredCountry)) ? $filteredCountry : '' ])}}">Date of applying</a></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($applicants as $key => $application)
                                    <tr>
                                        <td>{{$key + 1}}</td>
                                        <td><a href="{{URL::to('/admin/show-user',$application->user_id)}}" target="_blank">{{$application->first_name}} {{$application->last_name}}</a></td>
                                        <td>{{$application->email}}</td>
                                        <td>
                                            {{$application->country}}
                                        </td>
                                        <td>
                                            {{$application->city}}
                                        </td>
                                        <td>
                                            {{$application->company_name}}
                                        </td>
                                        <td>
                                            {{$application->job_name}}
                                        </td>
                                        <td>
                                            {{$application->created_at}}
                                        </td>
                                        <td>
                                            <a href="{{URL::to('/admin/show-user',$application->user_id)}}" target="_blank" type="button" data-id = "{{$application->user_id}}" class="btn btn-info show-modal-applicant" title="Show">
                                            <i class="fa fa-book"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </div>
                        <span class="col-md-offset-5">{{$applicants->links()}}</span>
                    @else
                        <h3>No Applicants</h3>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="/js/applicators_select_jobs.js" type="text/javascript"></script>
@endsection