@extends('admin/app_admin')
@section('content')
<link href="/css/admin/companies.css" rel="stylesheet" type="text/css" />
<div class="portlet box blue-chambray">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-gift"></i>Add Subsidiary
        </div>
        <div class="tools">
            <a href="javascript:;" class="collapse"> </a>
            <a href=""><i class="icon-refresh" style="color: white"></i> </a>
        </div>
    </div>
    <div class="portlet-body form">
	<form method="post" files = "true" class="form-horizontal" id="create-company-form" action="{{action('Admin\CompanyController@postAddSubsidiary')}}">
		{{csrf_field()}}
			
			<div class="form-body">
            <h3 class="information"><b>Company Information</b></h3>
                <div class="form-group">
                    <label class="col-md-3 control-label">Name</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" placeholder="Name" maxlength="40" name="name" value="{{ old('name') }}"/>
                     	<span class="error-message">{{$errors->first('name')}}</span>   
                    </div>
                </div>
                <div class="form-group">              
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
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">URL</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" placeholder="URL" name="url" value="{{ old('url') }}"/> 
                		<span class="error-message">{{$errors->first('url')}}</span>

                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label">Description</label>
                    <div class="col-md-6">
                    <textarea class="form-control" name="description" placeholder="Description" value="{{ old('description') }}"></textarea>
                        
                		<span class="error-message">{{$errors->first('description')}}</span>

                    </div>
                </div>
                <div class="form-group">               
                    <label class="col-md-3 control-label">Short Description</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" placeholder="Short Description" name="short_description"/>
                        <span class="error-message">{{$errors->first('short_description')}}</span>                      
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Facebook url</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" placeholder="Facebook url" name="facebook_url" value="{{ old('facebook_url') }}"/> 
                        <span class="error-message">{{$errors->first('facebook_url')}}</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Linkedin url</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" placeholder="Linkedin url" name="linkedin_url" value="{{ old('linkedin_url') }}"/> 
                        <span class="error-message">{{$errors->first('linkedin_url')}}</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Twitter url</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" placeholder="Twitter url" name="twitter_url" value="{{ old('twitter_url') }}"/> 
                        <span class="error-message">{{$errors->first('twitter_url')}}</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Crunchbase url</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" placeholder="Crunchbase url" name="crunchbase_url" value="{{ old('crunchbase_url') }}"/> 
                        <span class="error-message">{{$errors->first('crunchbase_url')}}</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Ios url</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" placeholder="Ios url" name="ios_url" value="{{ old('ios_url') }}"/> 
                        <span class="error-message">{{$errors->first('ios_url')}}</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Android url</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" placeholder="Android url" name="android_url" value="{{ old('android_url') }}"/> 
                        <span class="error-message">{{$errors->first('android_url')}}</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Country</label>
                    <div class="col-md-6">
                        <select class="selectpicker show-tick select-country" id="selectpicker_country" title="Select Country" name="country">
                        @foreach($countries as $country)
                            <option id="{{$country->name}}" class="company_country" data-content = "{{$country->name}}">{{$country->name}}</option>
                        @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">City</label>
                    <div class="col-md-6">
                        <select class="selectpicker show-tick select-city" id="selectpicker_city" title="Select City" name="city">
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Industry</label>
                    <div class="col-md-6">
                        <select class="selectpicker show-tick select-sector" id="selectpicker_sector" title="Select Industry" name="sector">
                        @foreach($sectors as $sector)
                            <option id="{{$sector->name}}" class="company_country" data-content = "{{$sector->name}}" value="{{$sector->id}}">{{$sector->name}}</option>
                        @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Job Category</label>
                    <div class="col-md-6">
                        <select class="selectpicker show-tick select-category" id="selectpicker_category" title="Select Job Category" name="category">
                        @foreach($categories as $category)
                            <option id="{{$category->name}}" class="company_category" data-content = "{{$category->name}}" value="{{$category->id}}">{{$category->name}}</option>
                        @endforeach
                        </select>
                    </div>
                </div>
                <h3 class="information"><b>Job Information</b></h3>
                <div class="form-group">
                    <label class="col-md-3 control-label">Looking For</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" placeholder="Looking For" name="looking_for" value="{{ old('looking_for') }}"/> 
                        <span class="error-message">{{$errors->first('looking_for')}}</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Requirement</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" placeholder="Requirement" name="requirement" value="{{ old('requirement') }}"/> 
                        <span class="error-message">{{$errors->first('requirement')}}</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Compensation</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" placeholder="Compensation" name="compensation" value="{{ old('compensation') }}"/> 
                        <span class="error-message">{{$errors->first('compensation')}}</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Why Us</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" placeholder="Why Us" name="why_us" value="{{ old('why_us') }}"/> 
                        <span class="error-message">{{$errors->first('why_us')}}</span>
                    </div>
                </div>
                <h3 class="information"><b>Job Applying</b></h3>
                <div class="form-group">
                    <label class="col-md-3 control-label">Redirect</label>
                    <div class="col-md-6">
                        <input id="redirect" type="radio" class="form-control radio-check" name="job_applying" value="redirect"/>
                        <input type="text" name="url_to_redirect" class="redirect_url" placeholder="Url to redirect">
                    </div>
                    
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Internal Process</label>
                    <div class="col-md-6">
                        <input id="redirect" type="radio" class="form-control radio-check" name="job_applying" value="form"/> 
                    </div>
                </div>
                <input type="hidden" name="type" value="generic">
                <input type="hidden" name="parent_id" value="{{$company_id}}">
            </div>
            <div class="form-actions form-actions-create-item">
                <div class="row">
                    <div class="col-md-offset-3 col-md-9">
                        <button type="submit" class="btn green">Submit</button>
                        <a type="button" class="btn grey-salsa btn-outline" href="/admin/edit-company/{{$company_id}}">Cancel</a>
                    </div>
                </div>
            </div>

            

	</form>
    <input id="input_24" name="image_name" type="file" accept="image/*" class="file-loading" style="display:none">
    <input type='hidden' id="token" value="{{csrf_token()}}">
	</div>
</div>

@endsection