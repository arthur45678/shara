@extends('admin/app_admin')
@section('style')
<style type="text/css">
    .pac-container {
    z-index: 1051 !important;
}
</style>
@endsection
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
	<form method="post" files="true" class="form-horizontal" id="create-company-form" action="{{action('Admin\CompanyController@postEditCompany')}}" enctype="multipart/form-data">
    <input type="hidden" name="parent_id" value="{{$company->parent_id}}">
    <input type="hidden" name="id" value="{{$company->id}}">
    <input type="hidden" name="sub_type" value="country_subsidiary">
    
		{{csrf_field()}}
			<input type="hidden" name="type" value="subsidiary">
			<div class="form-body">
                <h3 class="information"><b>Subsidiary Information</b></h3>             
                <div class="form-group">
                    <label class="col-md-3 control-label">Name</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" placeholder="Name" maxlength="40" name="name" @if(old('name')) value="{{old('name')}}" @else value="{{ $name }}" @endif/>
                     	<span class="error-message">{{$errors->first('name')}}</span>   
                    </div>
                </div>
                
                <!-- <div class="form-group">              
                    <label class="col-md-3 control-label">Logo</label>
                    <div class="input-group col-md-4 col-md-offset-4" >
                        <div class="form-group">              
                            <div class="input-group col-md-4" >
                                <input id="image" name="image" type="hidden">
                            </div>
                            <label class="input-group col-md-8" for="input_24" id="imag_slider">
                                <img src="{{URL::asset('/images/select_file.PNG')}}"  class="img-rounded show-image" alt="Cinque Terre" width="100%">
                            </label>                 
                        </div>                                       
                    </div>
                    <span class="error-message col-md-offset-3">{{$errors->first('logo')}}</span>
                </div> -->
                <!-- <div class="form-group">              
                    <label class="col-md-3 control-label">Logo</label>
                    <div class="input-group col-md-4 col-md-offset-4" >
                        <div class="form-group">              
                            <div class="input-group col-md-4" >
                                <input id="image" name="image" type="hidden">
                            </div>
                            @if($company->logo)
                            <label class=" col-md-6" for="input_24" id="imag_slider">
                                <img src="{{URL::asset('/images/button_replace.png')}}"  class="img-rounded show-image logo-file" alt="Cinque Terre" width="76%"> 
                            </label>
                            @else
                            <label class=" col-md-6" for="input_24" id="imag_slider">
                                <img src="{{URL::asset('/images/button_add-file.png')}}"  class="img-rounded show-image logo-file" alt="Cinque Terre" width="76%"> 
                            </label>
                            @endif
                                <img src="{{URL::asset('/images/button_add-url.png')}}"  class="img-rounded show-image" alt="Cinque Terre" width="33%" data-toggle="modal" data-target="#add-url">
                        </div>                                       
                    </div>
                    <span class="error-message col-md-offset-3">{{$errors->first('logo')}}</span>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Logo image url</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" placeholder="Logo url" name="logo_url" /> 
                        <span class="error-message">{{$errors->first('logo_url')}}</span>

                    </div>
                </div> -->

                <div class="form-group">
                    <label class="col-md-3 control-label">Logo</label>

                    <div class="fileinput @if($company->logo) fileinput-exists @else fileinput-new @endif" data-provides="fileinput">
                        <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">                            
                            <img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image" alt="">
                        </div>
                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> 
                        @if($company->logo)
                            <img src="/uploads/{{$company->logo}}" alt="">
                        @endif
                        </div>

                        <div>                                
                            <span class="btn btn-success btn-file">
                                <span class="fileinput-new"> Select image </span>
                                <span class="fileinput-exists"> Change </span>
                                <input type="file"  accept=".jpg, .jpeg, .gif, .png" name="logo_name" class="logo-file" /> 
                            </span>
                            <a href="javascript:;" class="btn default fileinput-exists remove-image" data-dismiss="fileinput"> Remove 

                            </a>
                            <input type="text" class="form-control logo_image_url" placeholder="Logo url" name="logo_url" value="{{old('logo_url')}}" /> 
                            <a href="#" class="clear-logo-url">
                                <i class="fa fa-times"></i>
                            </a>
                            <span class="error-message">{{Session::get('invalidUrl')}}</span>
                            <span class="error-message">{{$errors->first('logo_url')}}</span>
                        </div>

                    </div> 
                    <input type="hidden" id="old-logo" name="oldLogo" value="{{$company->logo}}">
                </div>  

                <div class="form-group">
                    <label class="col-md-3 control-label">URL</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" placeholder="URL" name="url" @if(old('url')) value="{{old('url')}}" @else value="{{ $company->url }}" @endif/> 
                		<span class="error-message">{{$errors->first('url')}}</span>

                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label">About Company</label>
                    <div class="col-md-6">
                        <textarea style="height: 150px;" id="company-desc" class="form-control" name="description" placeholder="Description">@if(old('description')){{old('description')}}@else{{$company->description}}@endif</textarea>
                        <div class="company-desc-result">0 chars</div>
                		<span class="error-message">{{$errors->first('description')}}</span>
                        <span class="error-message">{{$errors->first('logo_url')}}</span>
                    </div>
                </div>
                <div class="form-group">               
                    <label class="col-md-3 control-label">About Company Short</label>
                    <div class="col-md-6">
                        <textarea rows="3" id="short-description" type="text" class="form-control" placeholder="Short Description" name="short_description">@if(old('short_description')){{old('short_description')}}@else{{$company->short_description}}@endif</textarea>
                        <div class="short-description-result">0 chars</div>
                        <span class="error-message">{{$errors->first('short_description')}}</span>                      
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Facebook url</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" placeholder="Facebook url" name="facebook_url" @if(old('facebook_url')) value="{{old('facebook_url')}}" @else value="{{ $company->facebook_url }}" @endif/> 
                        <span class="error-message">{{$errors->first('facebook_url')}}</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Linkedin url</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" placeholder="Linkedin url" name="linkedin_url" @if(old('linkedin_url')) value="old('linkedin_url')" @else value="{{ $company->linkedin_url }}" @endif/> 
                        <span class="error-message">{{$errors->first('linkedin_url')}}</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Twitter url</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" placeholder="Twitter url" name="twitter_url" @if(old('twitter_url')) value="{{old('twitter_url')}}" @else value="{{ $company->twitter_url }}" @endif/> 
                        <span class="error-message">{{$errors->first('twitter_url')}}</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Crunchbase url</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" placeholder="Crunchbase url" name="crunchbase_url" @if($company->crunchbase_url) value="{{ $company->crunchbase_url }}" @endif/> 
                        <span class="error-message">{{$errors->first('crunchbase_url')}}</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Ios url</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" placeholder="Ios url" name="ios_url" @if(old('ios_url')) value="{{old('ios_url')}}" @else value="{{ $company->ios_url }}" @endif/> 
                        <span class="error-message">{{$errors->first('ios_url')}}</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Android url</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" placeholder="Android url" name="android_url" @if(old('android_url')) value="{{old('android_url')}}" @else value="{{ $company->android_url }}" @endif/> 
                        <span class="error-message">{{$errors->first('android_url')}}</span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label">City HQ</label>
                    <div class="col-md-6">
                        
                        <input type="text" class="form-control" placeholder="City" name="city_name" id="city-name" data-type="company" @if(old('city_name')) value="{{old('city_name')}}" @else value="{{$company->city_name}}" @endif>
                        <span class="error-message">{{$errors->first('city_name')}}</span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label">Country HQ</label>
                    <div class="col-md-6">
                        <select id="country" class="form-control" id="selectpicker_country" title="Select Country" name="country">
                        <option value="">Select Country</option>
                        @foreach($countries as $country)
                            <option id="{{$country->name}}" class="company_country" data-content="{{$country->id}}" data-code="{{$country->abbreviation}}" @if($company->country_id == $country->id) selected @endif>{{$country->name}}</option>
                        @endforeach
                        </select>
                        <span class="error-message">{{$errors->first('country')}}</span>
                    </div>
                </div>

                <input type="hidden" class="form-control" name="city_longtitude" id="city-longtitude" @if($company->city_longtitude) value="{{$company->city_longtitude}}" @endif>
                <input type="hidden" class="form-control" name="city_latitude" id="city-latitude" @if($company->city_latitude) value="{{$company->city_latitude}}" @endif>
                <input type="hidden" class="form-control" name="city_population" id="city-population" @if($company->city_population) value="{{$company->city_population}}" @endif>
                <input type="hidden" class="form-control" name="city_country" id="city-country" value="{{$company->country->name}}">
                <input type="hidden" class="form-control" name="region" id="region" value="{{$company->region}}">
                
                <div class="form-group">
                    <label class="col-md-3 control-label">Industry</label>
                    <div class="col-md-6">
                        <!-- <select class="select-sector form-control" id="selectpicker_sector" title="Select Industry" name="industry">
                        <option value="">Select Industry</option>
                        @foreach($sectors as $sector)
                            <option id="{{$sector->name}}" class="company_country" data-content = "{{$sector->name}}" value="{{$sector->id}}" @if($company->sector_id == $sector->id) selected @endif>{{$sector->name}}</option>
                        @endforeach
                        </select> -->
                        <input type="text" class="form-control" disabled name="industry" @if($company->sector) value="{{ $company->sector->name }}" @endif  /> 
                        <input type="hidden" class="form-control" name="industry" @if($company->sector) value="{{ $company->sector_id }}" / @endif>
                        <span class="error-message">{{$errors->first('industry')}}</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Job Category</label>
                    <div class="col-md-6">
                        <!-- <select class="select-category form-control" id="selectpicker_category" title="Select Job Category" name="category">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option id="{{$category->name}}" class="company_category" data-content = "{{$category->name}}" value="{{$category->id}}" @if($company->category_id == $category->id) selected @endif>{{$category->name}}</option>
                        @endforeach
                        </select> -->
                        <input type="text" class="form-control" name="category" disabled @if($company->category) value="{{ $company->category->name }}" @endif  /> 
                        <input type="hidden" class="form-control" name="category" @if($company->category) value="{{ $company->category_id }}" @endif/>
                        <span class="error-message">{{$errors->first('category')}}</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Meta Description</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" placeholder="Meta Description" name="meta_description" @if(old('meta_description')) value="{{old('meta_description')}}" @else value="{{ $company->meta_description }}" @endif/> 
                        <span class="error-message">{{$errors->first('meta_description')}}</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Meta Keywords</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" placeholder="Meta Keywords" name="meta_keywords" @if(old('meta_keywords')) value="{{old('meta_keywords')}}" @else value="{{ $company->meta_keywords }}" @endif/> 
                        <span class="error-message">{{$errors->first('meta_keywords')}}</span>
                    </div>
                </div>
<!--                 <h3 class="information"><b>Job Information</b></h3>
                <div class="form-group">
                    <label class="col-md-3 control-label">Job Description</label>
                    <div class="col-md-6">
                        <textarea style="height: 150px;" id="looking-for" type="text" class="form-control" placeholder="Job Description" name="looking_for">@if(old('looking_for')){{old('looking_for')}}@else{{$company->looking_for}}@endif</textarea>
                        <div class="looking-for-result">0 chars</div>
                        <span class="error-message">{{$errors->first('looking_for')}}</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Requirement</label>
                    <div class="col-md-6">
                        <textarea style="height: 150px;" id="company-requirements" type="text" class="form-control" placeholder="Requirement" name="requirement">@if(old('requirement')){{old('requirement')}}@else{{ $company->requirement }}@endif</textarea>
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
                        <textarea type="text" id="why-us" class="form-control" placeholder="Why Us" rows="3" name="why_us">@if(old('why_us')){{old('why_us')}}@else{{$company->why_us}}@endif</textarea>
                        <div class="why-us-result">0 chars</div>
                        <span class="error-message">{{$errors->first('why_us')}}</span>
                    </div>
                </div> -->
                <h3 class="information"><b>Job Applying</b></h3><br />
                <span style="margin-left: 97px;" class="error-message">{{Session::get('jobApplyingMessage')}}</span>
                <div class="form-group">
                    <label class="col-md-3 control-label">Redirect</label>
                    <div class="col-md-6">
                        <input id="redirect" type="radio" class="form-control radio-check" style="margin-left: -171px; margin-top:10px" name="job_applying" value="redirect" @if($company->job_applying == 'redirect') checked @endif/>
                        <input type="text" name="url_to_redirect" class="redirect_url" placeholder="Url to redirect" @if($company->url_to_redirect) value = "{{$company->url_to_redirect}}" @endif>
                    </div>
                    <span class="error-message col-md-6 col-md-offset-3">{{$errors->first('url_to_redirect')}}</span>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Internal Process</label>
                    <div class="col-md-6">
                        <input id="redirect" type="radio" class="form-control radio-check" style="margin-left: -171px; margin-top:10px" name="job_applying" value="form" @if($company->job_applying == 'form') checked @endif/> 
                    </div>
                    <span class="error-message col-md-6 col-md-offset-3">{{$errors->first('job_applying')}}</span>
                </div>
                <!-- <input type="hidden" name="type" value="generic"> -->
                <input type="hidden" name="company_id" value="{{$company->id}}">
                <div>
                    <b class="information">Cities</b>
                    <a type="button" class="btn btn-success col-md-offset-2" data-toggle="modal" data-target="#add-city" >Add New</a>
                    <span class="error-message">{{Session::get('city-error')}}</span>
                    <span class="error-message">{{$errors->first('subsidiary_city')}}</span>
                    <span class="error-message">{{$errors->first('city_country')}}</span>
                    
                </div>
                
                        @if(count($companyCities) > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover col-md-8 col-md-offset-2 subsidiary-table responsive-table">
                                <thead>
                                    <tr>
                                        <th> # </th>
                                        <th>City</th>
                                        <th>Action</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i=0; ?>
                                    @foreach ($companyCities as $sub)

                                        <tr>
                                            <td>{{$i+=1}}</td>
                                            <td>{{$sub->city}}</td>                                        

                                            <td>
                                            <a type="button" data-toggle="modal" data-target = "#company_delete" href="#small" alt="{{$sub->id}}" data-type = "sub_city" class="btn btn-danger show_modal_company_city" title="Delete">
                                                <i class="fa fa-trash-o"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            </div>
                        @else
                            <h3 class="col-md-offset-2">No Cities</h3>
                        @endif

                    <div>
                    <b class="information">Jobs</b>
                    <!-- <a type="button" class="btn btn-success col-md-offset-2 add-new-job" target="_blank" href="{{action('Admin\JobController@getCreateCompanyJob',['company_id' => $company->id, 'type' => 'sub'])}}">Add New</a> -->
                    <a type="button" class="btn btn-success col-md-offset-2 add-new-job" href="{{action('Admin\JobController@getCreateJob', 'company'.$company->id)}}">Add New</a>
                    <a type="button" class="btn btn-success" data-toggle="modal" data-target="#clone_job" href="" >Clone from existing</a>
                    <span class="error-message">{{Session::get('clone-error')}}</span>                                    
                    </div>
                    @if (count($company->jobs) > 0)
                    <div class="table-responsive">
                    <table class="table table-bordered table-hover col-md-8 col-md-offset-2 job-table">
                            <thead>
                                <tr>
                                    <th> # </th>
                                    <th>Name</th>
                                    <th>Schedule</th>
                                    <th></th>
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
                                        <td>
                                            @if($job->restrict != 'true')
                                            <a type="button" href="{{URL::to('/admin/make-unpublish-job',$job->id)}}" alt="{{$job->jobId}}" class="btn purple btn-outline show_modal_job" title="Delete">
                                                Unpublish
                                            </a>
                                            @else
                                            <a type="button"  href="{{URL::to('/admin/make-publish-job',$job->id)}}" alt="{{$job->jobId}}" class="btn green-haze btn-outline show_modal_job" title="Delete">
                                                Publish
                                            </a>
                                            @endif
                                        </td>
                                        <td>
                                        <a type="button" data-toggle="modal" data-target = "#job_delete" href="#small" alt="{{$job->id}}" data-type = "sub" class="btn btn-danger show_modal_job_detach" title="Delete">
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
            <input type="hidden" name="old_restriction" class="old_restriction" value="{{$company->restrict}}">
            <input type="hidden" name="is_published" class="is_published"> 
            <div class="form-actions form-actions-create-item">
                <div class="row">
                    <div class="col-md-offset-3 col-md-9">
                        <!-- <button type="submit" class="btn green">Publish</button> -->
                        @if($company->restrict == 'true')
                        <button type="submit" class="btn green pubish">Publish</button>
                        @else
                        <button type="submit" class="btn purple btn-outline unpublish">Unpublish </button>
                        @endif
                        <button type="submit" class="btn blue-madison save">Save</button>
                        <a type="button" class="btn grey-salsa btn-outline" href="{{action('Admin\CompanyController@getEditCompany', [$company->parent_id,'generic'])}}">Cancel</a>
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

    <!-- <div id="add-url" class="modal fade" role="dialog">
  <div class="modal-dialog">

   
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        
      </div>
      <div class="modal-body">
        <div class="form-group">
            <label class="col-md-3 control-label">Logo image url</label>
            <div class="col-md-6">
                <input type="text" class="form-control logo_image_url" placeholder="Logo url" name="logo_url" value="{{old('logo_url')}}" /> 
                <span class="error-message">{{$errors->first('logo_url')}}</span>

            </div>
        </div>
        <input type="hidden" class="edit_hidden" value="edit">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default logo-url-ok">OK</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

      </div>
    </div>

  </div>
</div>  -->

	</form>


    <div class="modal fade " id="add-city" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Add Subsidiary </h4>
                    <div class="col-md-8 col-md-offset-2">
                        <div class="alert alert-success" style="display: none;">
                            <button class="close" data-close="alert"></button>
                            <label class="success-message"></label>
                        </div>
                        <div class="alert alert-danger" style="display: none;">
                            <button class="close" data-close="alert"></button>
                            <label class="danger-message"></label>
                            
                        </div>
                    </div>
                </div>
                <div>
                <form  method="post" files = "true" class="form-horizontal" id="clone-form" action="{{action('Admin\CompanyController@postCreateCity')}}">
                {{csrf_field()}}
                    <div class="form-group">
                    
                    <label class="col-md-3 control-label">City</label>

                    <div class="col-md-6">
                        
                        <input type="text" class="form-control" placeholder="City" name="subsidiary_city" id="subsidiary-name" data-type="company">

                        <input type="hidden" class="form-control" name="city_longtitude" id="subsidiary-longtitude"> 
                        <input type="hidden" class="form-control" name="city_latitude" id="subsidiary-latitude">
                        <input type="hidden" class="form-control" name="city_population" id="subsidiary-population">
                        <input type="hidden" class="form-control" name="city_country" id="subsidiary-country">
                        <input type="hidden" class="form-control" name="country" value="{{$company->country->id}}" id="parent-country-id">
                        <input type="hidden" value="{{$company->country->name}}" name="country_name" id="parent-country">
                        <input type="hidden" value="" name="city_region" id="subsidiary-region">
                        <input type="hidden" name="type" value="subsidiary" id="type"> 
                        <input type="hidden" name="sub_type" value="city_subsidiary" id="sub-type">
                        <input type="hidden" name="country_parent" value="{{$company->id}}" id="country-parent">

                    </div>
                    </div>
                   <input type="hidden" name="parent_id" id="parent-id" value="{{$company->parent_id}}"> 
                   <!-- <input type="hidden" name="country" value="{{$company->country_id}}">            -->
                <div class="modal-footer">
                    
                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn dark btn-outline" >Add</button>
                    <button type="button" id="addCity" class="btn dark btn-outline" >Add and Stay</button> 
                </div>
                </form>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

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
                                <option id="{{$job->name}}" class="job_clone_from" data-content = "{{$job->name}}" value="{{$job->id}}" ></option>
                                @endif
                            @endforeach
                            </select>   
                        </div>
                    </div>    
                    <input type="hidden" name="company_from_id" value="{{$company->id}}">           
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
<script src="/js/add_city_subsidiary.js" type="text/javascript"></script>
<script src="/js/delete_company_city.js" type="text/javascript"></script>
<script src="/js/logo.js" type="text/javascript"></script>
<script src="/js/google_map_autocomplete.js" type="text/javascript"></script>
@endsection