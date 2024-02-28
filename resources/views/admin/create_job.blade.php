@extends('admin/app_admin')
@section('content')
<link href="/css/admin/jobs.css" rel="stylesheet" type="text/css" /> 
<div class="portlet box blue-chambray">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-gift"></i>Create Job
        </div>
        <div class="tools">
            <a href="javascript:;" class="collapse"> </a>
            <a href=""><i class="icon-refresh" style="color: white"></i> </a>
        </div>
    </div>
    <div class="portlet-body form">
    @include('messages')
	<form method="post" files = "true" class="form-horizontal" id="create-company-form" action="{{action('Admin\JobController@postCreateJob')}}">
		{{csrf_field()}}
			@if(isset($comp))
            <input type="hidden" name="main_company" value="{{$comp->id}}">
            <input type="hidden" name="company_type" value="{{$companyType}}">
            @endif
			<div class="form-body">
            <h3 class="information"><b>Job Information</b></h3>
                <div class="form-group">
                    <label class="col-md-3 control-label">Company</label>
                    <div class="col-md-3">
                        <select class="form-control select-company"  title="Select Company" name="company"> 
                        <option value="">Select Company</option>
                        @foreach($companies as $company) 
                            <option id="{{$company->name}}" data-country='{{$company->country}}' data-type="{{$company->type}}" data-subtype="{{$company->sub_type}}" data-city="{{$company->city_name}}" data-latitude="{{$company->city_latitude}}" data-longtitude="{{$company->city_longtitude}}" data-sector="{{$company->sector_id}}" data-category="{{$company->category_id}}" class="job_company" data-content="{{$company->id}}" value="{{$company->id}}" data-why="{{$company->why_us}}" data-about="{{$company->description}}" @if($company->id == old('company')) selected  @elseif(isset($company_id) && $company->id == $company_id) selected @endif>{{$company->name}} @if($company->type == 'subsidiary') / {{$company->country->name}} @endif @if($company->sub_type == 'city_subsidiary') / {{$company->city_name}} @endif</option> 
                        @endforeach 
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label">Country</label>
                    <div class="col-md-3">
                        <select class="form-control select-country" id="country" title="Select Country"  name="country" data-countries="{{$countries}}" @if(isset($country_id))  @endif>
                        @if((isset($comp) && $comp->type == 'generic') || !isset($comp))
                            <option value="">Select Country</option>
                            @foreach($countries as $country)
                                <option id="{{$country->name}}" value="{{$country->name}}" class="company_country" data-content="{{$country->id}}" data-code="{{$country->abbreviation}}" @if(isset($country_id)) @if($country_id == $country->id) selected  @endif @elseif($country->name == old('country')) selected @endif>{{$country->name}}</option>
                            @endforeach
                        @elseif(isset($country_id) && $comp->country)
                            <option selected hidden value="{{$comp->country->name}}" data-content="{{$comp->country->id}}" data-code="{{$comp->country->abbreviation}}">{{$comp->country->name}}</option>
                        @endif
                        </select>
                        <input type="hidden" value="" id="job-country">
                    </div>
                </div>
<!-- 
                <div class="form-group">
                    <label class="col-md-3 control-label">City</label>
                    <div class="col-md-3" id="select-city">

                    @if(isset($comp))
                        <select class="form-control city-dropdown" id="city-dropdown" title="Select City" name="city">
                            @if(isset($city_name) && $comp->sub_type == 'city_subsidiary')

                                <option value="{{$city_name}}" selected hidden>{{$city_name}}</option>
                            @elseif($comp->sub_type == 'country_subsidiary')
                                
                                <option value="">Select City</option>
                                    @foreach($cities as $cit)
                                    <option @if(old('city') == $cit['city']) selected @endif>{{$cit['city']}}</option>
                                    @endforeach
                            @else
                                <option value="">Select City</option>
                            @endif
                        </select>

                    @else
                        <input type="text" name="city" class="form-control" @if(old('city')) value="{{old('city')}}" @else value="" @endif id="city-name" placeholder="City">
                    @endif
                        <span class="error-message">{{$errors->first('city')}}</span>
                        <span class="error-message">{{$errors->first('city_country')}}</span>

                        <input type="hidden" class="form-control" name="city_latitude" @if(isset($city_latitude)) value="{{$city_latitude}}" @else value="{{ old('city_latitude') }}" @endif id="city-latitude">
                        <input type="hidden" class="form-control" name="city_longtitude" @if(isset($city_longtitude)) value="{{$city_longtitude}}" @else value="{{ old('city_longtitude') }}" @endif id="city-longtitude">
                        <input type="hidden" class="form-control" name="city_country" id="city-country" value="{{old('city_country')}}">
                        <input type="hidden" class="form-control" name="region" value="{{ old('region') }}"  id="region">

                    </div>
                    <input type="hidden" value="" id="job-city">
                </div> -->

                
                

                <div class="form-group">
                    <label class="col-md-3 control-label">Industry</label> 
                    <div class="col-md-3">
                        <select class="form-control select-sector" id="selectpicker_sector" title="Select Industry" name="sector">
                        <option value="" >Select Industry</option>
                        @foreach($sectors as $sector)
                            <option id="{{$sector->sectorName}}" class="company_country" data-content = "{{$sector->sectorName}}" value="{{$sector->sector_id}}" @if(isset($sector_id)) @if($sector_id == $sector->sector_id || old('sector') == $sector->sector_id) selected  @endif @elseif($sector->sector_id == old('sector')) selected  @endif>{{$sector->sectorName}}</option>
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
                            <option id="{{$category->categoryName}}" class="company_category" data-content = "{{$category->categoryName}}" value="{{$category->category_id}}" @if(isset($category_id)) @if($category_id == $category->category_id || old('category') == $category->category_id) selected @endif @elseif($category->category_id == old('category')) selected  @endif>{{$category->categoryName}}</option>
                        @endforeach
                        </select> 
                        <span class="error-message">{{$errors->first('category')}}</span>
                    </div>

                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Job Offer Title</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" placeholder="Name" maxlength="40" name="name" value="{{ old('name') }}"/>
                     	<span class="error-message">{{$errors->first('name')}}</span>   
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Job Description</label>
                    <div class="col-md-6">
                        <textarea style="height: 150px;" class="form-control" name="description" placeholder="Description" id="job-desc">{{ old('description') }}</textarea>
                        <div class="job-desc-result">0 chars</div>
                        <span class="error-message">{{$errors->first('description')}}</span>

                    </div>
                </div>
<!--                 <div class="form-group">
                    <label class="col-md-3 control-label">About Company</label>
                    <div class="col-md-6">
                        <textarea style="height: 150px;" id="about-company" class="form-control" placeholder="About Company" name="about_company">@if(old('about_company')){{old('about_company')}}@elseif(isset($about_company)){{$about_company}}@endif</textarea> 
                        <div class="about-comp-result">0 chars</div>
                		<span class="error-message">{{$errors->first('about_company')}}</span>

                    </div>
                </div> -->
                <div class="form-group">
                    <label class="col-md-3 control-label">Requirements</label>
                    <div class="col-md-6">
                        <textarea style="height: 150px;" id="job-requirements" class="form-control" placeholder="Requirement" name="requirement" >{{ old('requirement') }}</textarea>
                        <div class="job-requirements-result">0 chars</div>
                        <span class="error-message">{{$errors->first('requirement')}}</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Compensation</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" placeholder="Compensation" name="compensation" @if(old('compensation')) value="{{old('compensation')}}"@endif /> 
                        <span class="error-message">{{$errors->first('compensation')}}</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Job Schedule</label>
                    <div class="col-md-3">
                        <!-- <input type="text" class="form-control" placeholder="Schedule" name="schedule" value="{{ old('schedule') }}"/>  -->
                        <select class="form-control select-schedule" id="selectpicker_schedule" title="Schedule" name="schedule">
                            <option value="">Select Schedule</option>
                            <option id="full_time" class="job_schedule" data-content = "Full time" value="full time" @if(old('schedule') == 'full time') selected @endif)>Full time</option>
                            <option id="part_time" class="job_schedule" data-content = "Part time" value="part time" @if(old('schedule') == 'part time') selected @endif)>Part time</option>
                            <option id="part_time" class="job_schedule" data-content = "Both" value="both" @if(old('schedule') == 'both') selected @endif)>Both</option>
                        </select>
                        <span class="error-message schedule-error">{{$errors->first('schedule')}}</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Why Us?</label>
                    <div class="col-md-6">
                        <textarea rows="3" id="why-us" class="form-control" placeholder="Why Us" name="why_us">@if(old('why_us')){{ old('why_us') }}@elseif(isset($whyUs)){{$whyUs}}@endif</textarea>
                        <div class="why-us-result">0 chars</div> 
                        <span class="error-message">{{$errors->first('why_us')}}</span>
                        <span class="error-message">{{Session::get('why_us_error')}}</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Benefits</label>
                    <div class="col-md-6">
                        <textarea type="text" id="job-benefits" rows="3" class="form-control" placeholder="Benefits" name="benefits">@if(old('benefits')){{old('benefits')}}@endif</textarea>
                        <div class="job-benefits-result">0 chars</div>
                        <span class="error-message">{{$errors->first('benefits')}}</span>
                    </div>
                </div>
               
                <h3 class="information"><b>Job Applying</b></h3>
                <div class="form-group">
                    
                    <div class="col-md-6 col-md-offset-2">
                        <input id="redirect" type="radio" class="form-control radio-check" name="job_applying" value="redirect" @if(old('job_applying') == 'redirect') checked="" @endif)/>
                        <label class="control-label">Redirect</label>
                        <input type="text" name="url_to_redirect" class="redirect_url" placeholder="Url to redirect" value="{{old('url_to_redirect')}}">
                    </div>
                    <span class="error-message col-md-6 col-md-offset-3">{{$errors->first('url_to_redirect')}}</span>
                </div>
                <div class="form-group">
                    
                    <div class="col-md-6 col-md-offset-2">
                        <input id="redirect" type="radio" class="form-control radio-check" name="job_applying" value="form" @if(old('job_applying') == 'form') checked="" @endif)/> 
                        <label class="control-label">Internal Process</label>
                    </div><br />
                    <span class="error-message col-md-6 col-md-offset-3">{{$errors->first('job_applying')}}</span>
                </div>

                <!-- <div class="form-group">
                    <label class="col-md-3 control-label">Activation</label>
                    <div class="col-md-6">
                        <input type="checkbox" name="activation" checked class="check-box">
                        <div class="append-roles"></div>
                    </div>
                </div> -->
                
                
            </div>
            <input type="hidden" name="is_published" class="is_published">
            <div class="form-actions form-actions-create-item">
                <div class="row">
                    <div class="col-md-offset-3 col-md-9">
                        <button type="submit" class="btn green">Publish</button>
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