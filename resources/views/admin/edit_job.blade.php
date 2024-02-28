@extends('admin/app_admin')
@section('content')
<link href="/css/admin/jobs.css" rel="stylesheet" type="text/css" />
<div class="portlet box blue-chambray">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-gift"></i>Update Job
        </div>
        <div class="tools">
            <a href="javascript:;" class="collapse"> </a>
            <a href=""><i class="icon-refresh" style="color: white"></i> </a>
        </div>
    </div>
    <div class="portlet-body form">
    @include('messages')
	<form method="post" files = "true" class="form-horizontal" id="create-company-form" action="{{action('Admin\JobController@postEditJob')}}">
		{{csrf_field()}}
			
			<div class="form-body">
            <h3 class="information"><b>Job Information</b></h3> 
                <div class="form-group">
                    <label class="col-md-3 control-label">Company</label>  
                    <div class="col-md-3">
                        <select class="form-control select-company" id="selectpicker_company" title="Select Company" name="company">
                        <option value="">Select Company</option>
                        @foreach($companies as $company)
                            <option id="{{$company->name}}" data-subtype="{{$company->sub_type}}" data-city="{{$company->city_name}}" data-country='{{$company->country}}' data-type="{{$company->type}}" data-sector="{{$company->sector_id}}" data-category="{{$company->category_id}}" class="job_company" data-content = "{{$company->id}}" data-about="{{$company->description}}" data-why="{{$company->why_us}}" value="{{$company->id}}" @if($company->id == $job->company_id || $company->id == old('company') ) selected @endif>{{$company->name}} @if($company->type == 'subsidiary') / {{$company->country->name}} @endif @if($company->sub_type == 'city_subsidiary') / {{$company->city_name}} @endif</option>
                        @endforeach
                        </select>
                    </div>
                </div>


                <div class="form-group">
                    <label class="col-md-3 control-label">Country</label>
                    <div class="col-md-3">
                        <select class="form-control select-country" id="country" title="Select Country" name="country" data-countries="{{$countries}}">
                           <!--  <option value="">Select Country</option>
                        @foreach($countries as $country)
                            <option id="{{$country->name}}" class="company_country" data-content = "{{$country->id}}"  @if($job->country_id == $country->id) selected @endif>{{$country->name}}</option>
                        @endforeach -->
                        @if((isset($job->company) && $job->company->type == 'generic') || !isset($job->company))
                            <option value="">Select Country</option>
                            @foreach($countries as $country)
                                <option id="{{$country->name}}" value="{{$country->name}}" class="company_country" data-code="{{$country->abbreviation}}" data-content="{{$country->id}}" @if($job->country_id == $country->id || $country->name == old('country')) selected  @endif >{{$country->name}}</option>
                            @endforeach
                        @elseif(isset($job->company) && $job->company->type != 'generic'))
                            @if($job->country)
                            <option selected hidden value="{{$job->country->name}}" data-code="{{$country->abbreviation}}" data-content="{{$job->country->id}}">{{$job->country->name}}</option>
                            @endif
                        @endif
                        </select>
                         <input type="hidden" id="job-country">
                    </div>
                </div>
<!-- 
                <div class="form-group">
                    <label class="col-md-3 control-label" >City</label>
                    <input type="hidden" id="old-job-city" value="{{$job->city_name}}">
                    <div class="col-md-3" id="select-city">
                    @if($job->company_id == '' || $job->company_id == null)
                        <input type="text" class="form-control" placeholder="City" name="city" id="city-name"  value="{{$job->city_name}}" > 
                    @elseif($job->company)
                        @if($job->company->sub_type == 'city_subsidiary')
                        <input type="text" class="form-control" placeholder="City" name="city" id="city-name" @if($job->city_name) value="{{$job->city_name}}" @else value ="" @endif disabled>
                        <input type="hidden"  id="job-city" name="city" value="{{$job->city_name}}">
                        @else
                            <select class="form-control city-dropdown" id="city-dropdown" title="Select City" name="city" >
                                <option value="">Select City</option>
                            @if(isset($cities))
                                @foreach($cities as $city)
                                <option @if($job->city_name == $city['city'] || $city['city'] == old('city')) selected @endif>{{$city['city']}}</option>
                                @endforeach
                            @else
                            <option value="{{$job->city_name}}">{{$job->city_name}}</option>
                            @endif
                            </select>
                        @endif
                    @endif
                        <input type="hidden" value="" id="job-city">

                        <input type="hidden" class="form-control" name="city_latitude" id="city-latitude" @if($job->city_latitude) value="{{$job->city_latitude}}" @endif>
                        <input type="hidden" class="form-control" name="city_longtitude" id="city-longtitude" @if($job->city) value="{{$job->city_longtitude}}" @endif>
                        <input type="hidden" class="form-control" name="city_country" id="city-country" @if(isset($job->country)) value="{{$job->country->name}}" @endif>
                        <input type="hidden" class="form-control" name="region" id="region" @if(isset($job->region)) value="{{$job->region}}" @endif>
                    </div>
                </div> -->
                
                <div class="form-group">
                    <label class="col-md-3 control-label">Industry</label>
                    <div class="col-md-3">
                        <select class="form-control select-sector" id="selectpicker_sector" title="Select Industry" name="sector">
                            <option value="">Select Industry</option>
                        @foreach($sectors as $sector)
                            <option id="{{$sector->sectorName}}" class="company_sector" data-content = "{{$sector->sectorName}}" value="{{$sector->sector_id}}" @if($job->sector_id == $sector->sector_id) selected @endif>{{$sector->sectorName}}</option>
                        @endforeach
                        </select>
                        <span class="error-message">{{$errors->first('sector')}}</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Category</label>
                    <div class="col-md-3">
                        <select class="form-control select-category" id="selectpicker_category" title="Select Category" name="category">
                            <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option id="{{$category->categoryName}}" class="company_category" data-content = "{{$category->categoryName}}" value="{{$category->category_id}}" @if($job->category_id == $category->category_id) selected @endif>{{$category->categoryName}}</option>
                        @endforeach
                        </select>
                        <span class="error-message">{{$errors->first('category')}}</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Job Offer Title</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" placeholder="Name" maxlength="40" name="name" @if(old('name')) value="{{old('name')}}"@else value="{{ $job->name }}"@endif/>
                     	<span class="error-message">{{$errors->first('name')}}</span>   
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Job Description</label>
                    <div class="col-md-6">
                        <textarea style="height: 150px;" class="form-control" name="description" placeholder="Description" value="{{ $job->description }}" id="job-desc">@if(old('description')){{old('description')}}@else{{ $job->description }}@endif</textarea>
                        <div class="job-desc-result">0 chars</div>
                        <span class="error-message">{{$errors->first('description')}}</span>

                    </div>
                </div>
<!--                 <div class="form-group">
                    <label class="col-md-3 control-label">About Company</label>
                    <div class="col-md-6">
                        <textarea style="height: 150px;" id="about-company" class="form-control" placeholder="About Company" name="about_company" value="{{$job->about_company}}"/>@if(old('about_company')){{old('about_company')}}@else{{$job->about_company}}@endif</textarea>
                        <div class="about-comp-result">0 chars</div>
                		<span class="error-message">{{$errors->first('about_company')}}</span>

                    </div>
                </div> -->
                <div class="form-group">
                    <label class="col-md-3 control-label">Requirement</label>
                    <div class="col-md-6">
                        <textarea style="height: 150px;" id="job-requirements" class="form-control" placeholder="Requirement" name="requirement" value="{{$job->requirement}}"/>@if(old('requirement')){{old('requirement')}}@else{{$job->requirement}}@endif</textarea> 
                        <div class="job-requirements-result">0 chars</div>
                        <span class="error-message">{{$errors->first('requirement')}}</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Compensation</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" placeholder="Compensation" name="compensation" @if(old('compensation')) value="{{old('compensation')}}" @else value="{{$job->compensation}}" @endif /> 
                        <span class="error-message">{{$errors->first('compensation')}}</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Job Type</label>
                    <div class="col-md-3">
                        <!-- <input type="text" class="form-control" placeholder="Schedule" name="schedule" value="{{ old('schedule') }}"/>  -->
                        <select class="form-control select-schedule" id="selectpicker_schedule" title="Schedule" name="schedule">
                            <option value="">Select Schedule</option>
                            <option id="full_time" class="job_schedule" data-content = "Full time" value="full time" @if($job->schedule == 'full time' || old('schedule') == 'full time') selected @endif)>Full time</option>
                            <option id="part_time" class="job_schedule" data-content = "Part time" value="part time" @if($job->schedule == 'part time' || old('schedule') == 'part time') selected @endif)>Part time</option>
                            <option id="part_time" class="job_schedule" data-content = "Both" value="both" @if($job->schedule == 'both' || old('schedule') == 'both') selected @endif)>Both</option>
                        </select>
                        <span class="error-message schedule-error">{{$errors->first('schedule')}}</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Why Us?</label>
                    <div class="col-md-6">
                        <textarea rows="3" id="why-us" class="form-control" placeholder="Why Us" name="why_us" value="{{ $job->why_us }}"/>@if(old('why_us')){{old('why_us')}}@else{{$job->why_us}}@endif</textarea>
                        <div class="why-us-result">0 chars</div> 
                        <span class="error-message">{{$errors->first('why_us')}}</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Benefits</label>
                    <div class="col-md-6">
                        <textarea rows="3" id="job-benefits" type="text" class="form-control" placeholder="Benefits" name="benefits">@if(old('benefits')){{old('benefits')}}@else{{$job->benefits}}@endif</textarea>
                        <div class="job-benefits-result">0 chars</div>
                        <span class="error-message">{{$errors->first('benefits')}}</span>
                    </div>
                </div>
                
                
                
                <!-- <input type="hidden" id="company_country" name="country" @if($job->country) value="{{$job->country->name}}" @endif> -->
                
                
                               
                <h3 class="information"><b>Job Applying</b></h3>
                <div class="form-group">
                    
                    <div class="col-md-6 col-md-offset-2">
                        <input id="redirect" type="radio" class="form-control radio-check" name="job_applying" value="redirect" @if($job->job_applying == 'redirect') checked @endif/>
                        <label class="control-label">Redirect</label>
                        <input type="text" name="url_to_redirect" class="redirect_url" placeholder="Url to redirect" @if(old('url_to_redirect')) value="{{old('url_to_redirect')}}"@else value="{{$job->url_to_redirect}}" @endif>
                    </div>
                    <span class="error-message col-md-6 col-md-offset-3">{{$errors->first('url_to_redirect')}}</span>
                </div>
                <div class="form-group">
                    
                    <div class="col-md-6 col-md-offset-2">
                        <input id="redirect" type="radio" class="form-control radio-check" name="job_applying" value="form" @if($job->job_applying == 'form') checked @endif/> 
                        <label class="control-label">Internal Process</label>
                    </div>
                    <span class="error-message col-md-6 col-md-offset-3">{{$errors->first('job_applying')}}</span>
                </div>
                <!-- <div class="form-group">
                    <label class="col-md-3 control-label">Activation</label>
                    <div class="col-md-6">
                        <input type="checkbox" class="check-box" name="activation" @if($job->activation == 'activated') checked @endif>
                        <div class="append-roles"></div>
                    </div>
                </div> -->
                <input type="hidden" name="job_id" value="{{$job->id}}">
                
            </div>
            <input type="hidden" name="is_published" class="is_published"> 
            <input type="hidden" name="old_restriction" class="old_restriction" value="{{$job->restrict}}">
            <div class="form-actions form-actions-create-item">
                <div class="row">
                    <div class="col-md-offset-3 col-md-9">
                        @if($job->restrict == 'true')
                        <button type="submit" class="btn green pubish">Publish</button>
                        @else
                        <button type="submit" class="btn purple btn-outline unpublish">Unpublish </button>
                        @endif
                        <button type="submit" class="btn blue-madison save">Save</button>
                        <a type="button" class="btn grey-salsa btn-outline" href="{{url('/admin/jobs/date/asc')}}">Cancel</a>
                    </div>
                </div>
            </div>
	</form>
    <input id="input_24" name="image_name" type="file" accept="image/*" class="file-loading" style="display:none">
    <input type='hidden' id="token" value="{{csrf_token()}}">
	</div>
</div>

@endsection

@section('scripts')
<script src="/js/save_job.js" type="text/javascript"></script>
<script src="/js/job_location_test.js" type="text/javascript"></script>
@endsection