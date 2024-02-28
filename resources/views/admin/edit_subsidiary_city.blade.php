@extends('admin/app_admin')
@section('content')
<link href="/css/admin/companies.css" rel="stylesheet" type="text/css" />
<div class="portlet box blue-chambray">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-gift"></i>Update Subsidiary  
        </div>
        <div class="tools">
            <a href="javascript:;" class="collapse"> </a>
            <a href=""><i class="icon-refresh" style="color: white"></i> </a>
        </div>
    </div>
    <div class="portlet-body form">
    @include('messages')
	<form method="post" files = "true" class="form-horizontal" id="create-company-form" action="{{action('Admin\CompanyController@postEditCompany')}}">
		{{csrf_field()}}
            <input type="hidden" name="id" value="{{$company->id}}">
            <input type="hidden" name="type" value="subsidiary">
            <input type="hidden" name="sub_type" value="city_subsidiary">
            <input type="hidden" name="parent_id" value="{{$company->parent_id}}">
            <input type="hidden" name="country_parent" value="{{$countryCompany->id}}">
            <input type="hidden" name="name" value="{{$company->name}}">
            <input type="hidden" name="country" value="{{$company->country->name}}">
            <input type="hidden" name="city_country" value="{{$company->country->name}}">
            <input type="hidden" name="city_name" value="{{$company->city_name}}">
            <input type="hidden" name="city_latitude" value="{{$company->city_latitude}}">
            <input type="hidden" name="city_longtitude" value="{{$company->city_longtitude}}">
            <input type="hidden" name="region" value="{{$company->region}}">
            <input type="hidden" name="company_id" value="{{$company->id}}">
            <input type="hidden" name="description" value="{{$company->description}}">
            <input type="hidden" name="url" value="{{$company->url}}">
            <input type="hidden" name="short_description" value="{{$company->short_description}}">

			<div class="form-body">
            <h3 class="information"><b>Subsidiary - City Information</b></h3>
                <h3 class="information"><b>Job Information</b></h3>
                <div class="form-group">
                    <label class="col-md-3 control-label">Job Description</label>
                    <div class="col-md-6">
                        <textarea style="height: 150px" id="looking-for" type="text" class="form-control" placeholder="Job Description" name="looking_for">@if(old('looking_for')){{old('looking_for')}}@else{{$company->looking_for}}@endif</textarea>
                        <div class="looking-for-result">0 chars</div>
                        <span class="error-message">{{$errors->first('looking_for')}}</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Requirement</label>
                    <div class="col-md-6">
                        <textarea style="height: 150px" id="company-requirements" type="text" class="form-control" placeholder="Requirement" name="requirement">@if(old('requirement')){{old('requirement')}}@else{{$company->requirement}}@endif</textarea>
                        <div class="company-requirements-result">0 chars</div>
                        <span class="error-message">{{$errors->first('requirement')}}</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Compensation</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" placeholder="Compensation" name="compensation" value="{{ $company->compensation }}"/> 
                        <span class="error-message">{{$errors->first('compensation')}}</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Why Us</label>
                    <div class="col-md-6">
                        <textarea type="text" id="why-us" class="form-control" placeholder="Why Us" rows="3" name="why_us">@if($company->why_us){{$company->why_us}}@else{{old('why_us')}}@endif</textarea>
                        <div class="why-us-result">0 chars</div>
                        <span class="error-message">{{$errors->first('why_us')}}</span>
                    </div>
                </div>
                <h3 class="information"><b>Job Applying</b></h3>
                <span style="margin-left: 97px;" class="error-message">{{Session::get('jobApplyingMessage')}}</span>
                <div class="form-group">
                    <label class="col-md-3 control-label">Redirect</label>
                    <div class="col-md-6">
                        <input id="redirect" type="radio" class="form-control radio-check" style="margin-left: -160px; margin-top: 11px;" name="job_applying" value="redirect" @if($company->job_applying == 'redirect') checked @endif/>
                        <input type="text" name="url_to_redirect" class="redirect_url" placeholder="Url to redirect" @if($company->url_to_redirect) value = "{{$company->url_to_redirect}}" @endif>
                    </div>
                    <span class="error-message col-md-6 col-md-offset-3">{{$errors->first('url_to_redirect')}}</span>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Internal Process</label>
                    <div class="col-md-6">
                        <input id="redirect" type="radio" class="form-control radio-check" style="margin-left: -160px;; margin-top: 11px;" name="job_applying" value="form" @if($company->job_applying == 'form') checked @endif/> 
                    </div>
                    <span class="error-message col-md-6 col-md-offset-3">{{$errors->first('job_applying')}}</span>
                </div>
                <!-- <input type="hidden" name="type" value="generic"> -->
                <input type="hidden" name="company_id" value="{{$company->id}}">
                
            <div>
                    <b class="information">Jobs</b>
                    <!-- <a type="button" class="btn btn-success col-md-offset-2 add-new-job" target="_blank" href="{{action('Admin\JobController@getCreateCompanyJob',['company_id' => $company->id, 'type' => 'sub_city'])}}">Add New</a> -->
                    <a type="button" class="btn btn-success col-md-offset-2 add-new-job" href="{{action('Admin\JobController@getCreateJob', 'company'.$company->id)}}">Add New</a>
                    <a type="button" class="btn btn-success" data-toggle="modal" data-target="#clone_job" href="" >Clone from existing</a>
                    <span class="error-message">{{Session::get('clone-error')}}</span>                                    
                    </div>
                    @if (count($company->jobs) > 0)
                    <div class="table-responsive">
                    <table class="table table-bordered table-hover col-md-8 col-md-offset-2 job-table responsive-table">
                            <thead>
                                <tr>
                                    <th> # </th>
                                    <th>Name</th>
                                    <th>Schedule</th>
                                    <th></th>
                                    <th>Action</th>
                                    <th></th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($company->jobs as $key => $job)
                                    <tr>
                                        <td>{{$key + 1}}</td>
                                        <td>{{$job->name}}</td>                                        
                                        <td>{{ucfirst($job->schedule)}}</td>
                                        <td>
                                        <a href = "{{URL::to('/admin/edit-job', $job->id)}}" type="button" class="btn btn-primary" title="Update">
                                            <i class="fa fa-edit"></i></a>
                                        </td>
                                        <!-- <td>
                                        <a href = "{{URL::to('/admin/subsidiaries',$company->id)}}" type="button" class="btn btn-primary" >
                                            <i class="fa fa-edit"></i> Subsidiaries </a>
                                        </td> -->
                                        <td>
                                        <a type="button" data-toggle="modal" data-target = "#job_delete" href="#small" alt="{{$job->id}}" data-type = "sub_city" class="btn btn-danger show_modal_job_detach" title="Delete">
                                            <i class="fa fa-trash-o"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </div>
                    @else
                        <h3 class=" col-md-6 col-md-offset-2">No Jobs</h3>
                    @endif        
                    
            </div>
            <input type='hidden'  name="is_published" class="is_published">
            <div class="form-actions form-actions-create-item">
                <div class="row">
                    <div class="col-md-offset-3 col-md-9">
                       <button type="submit" class="btn green">Publish</button>
                        <button type="submit" class="btn blue-madison save">Save</button>
                        <a type="button" class="btn grey-salsa btn-outline" href="{{action('Admin\CompanyController@getEditCompany', [$countryCompany->id,'sub'])}}">Cancel</a>
                    </div>
                </div>
            </div>


	</form>

    <div class="modal fade " id="clone_job" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Clone</h4>
                </div>
                <div>
                <form  method="post" files = "true" class="form-horizontal" id="clone-form" action="{{action('Admin\JobController@postCloneJob')}}">
                {{csrf_field()}}
                    <div class="form-group">
                        <label class="col-md-3 control-label">From</label>
                        <div class="col-md-6">
                            <select class="selectpicker show-tick select-clone-from" id="selectpicker_clone_job_from" title="Select Job" name="job_from">
                            @foreach($jobs as $job)
                                @if(isset($job->company))
                                <option id="{{$job->name}}" class="job_clone_from" data-content = "{{$job->name}} - {{$job->company->name}}" value="{{$job->id}}" ></option>
                                @else
                                <option id="{{$job->name}}" class="job_clone_from" data-content = "{{$job->name}} " value="{{$job->id}}" ></option>
                                @endif
                            @endforeach
                            </select>   
                        </div>
                    </div>    
                    <input type="hidden" name="parent_id" value="{{$company->id}}">           
                <div class="modal-footer">                    
                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn dark btn-outline" >Clone</button>
                </div>
                </form>
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

    <input id="input_24" name="image_name" type="file" accept="image/*" class="file-loading" style="display:none">
    <input type='hidden' id="token" value="{{csrf_token()}}">

    

	</div>
</div>

@endsection
@section('scripts')
<script src="/js/save_job.js" type="text/javascript"></script>
@endsection

