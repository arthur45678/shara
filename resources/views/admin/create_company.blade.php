@extends('admin/app_admin')
@section('content')
<link href="/css/admin/companies.css" rel="stylesheet" type="text/css" />
<div class="portlet box blue-chambray">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-gift"></i>Create Company 
        </div>
        <div class="tools">
            <a href="javascript:;" class="collapse"> </a>
            <a href=""><i class="icon-refresh" style="color: white"></i> </a> 
        </div>
    </div>
    <div class="portlet-body form">
	<form method="post" files = "true" class="form-horizontal" id="create-company-form" action="{{action('Admin\CompanyController@postCreateCompany')}}" enctype="multipart/form-data">
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
                <!-- <div class="form-group">              
                    <label class="col-md-3 control-label">Logo</label>
                    <div class="input-group col-md-4 col-md-offset-4" >
                        <div class="form-group">              
                            <div class="input-group col-md-4" >
                                <input id="image" name="image" type="hidden">
                            </div>
                            <label class=" col-md-6" for="input_24" id="imag_slider">
                                <img src="{{URL::asset('/images/button_add-file.png')}}"  class="img-rounded show-image logo-file" alt="Cinque Terre" width="76%"> 
                            </label> 
                                <img src="{{URL::asset('/images/button_add-url.png')}}"  class="img-rounded show-image" alt="Cinque Terre" width="33%" data-toggle="modal" data-target="#add-url">
                        </div>                                       
                    </div>
                    <span class="error-message col-md-offset-3">{{$errors->first('logo')}}</span>
                </div> -->

                       
                        <div class="form-group">
                            <label class="col-md-3 control-label">Logo</label>

                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                    <img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image" alt="">
                                </div>
                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> 

                                </div>

                                <div>                                
                                    <span class="btn btn-success btn-file">
                                        <span class="fileinput-new"> Select image </span>
                                        <span class="fileinput-exists"> Change </span>
                                        <input type="file"  accept=".jpg, .jpeg, .gif, .png" name="logo_name" class="logo-file" /> 
                                    </span>
                                    <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput"> Remove 

                                    </a>
                                    <input type="text" class="form-control logo_image_url" placeholder="Logo url" name="logo_url" value="{{old('logo_url')}}" /> 
                                    <a href="#" class="clear-logo-url">
                                        <i class="fa fa-times"></i>
                                    </a>
                                    <span class="error-message">{{Session::get('invalidUrl')}}</span>
                                    <span class="error-message">{{$errors->first('logo_url')}}</span>
                                </div>

                            </div>

                            <!-- <div class="fileinput fileinput-new col-md-6" data-provides="fileinput">
                                <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                    <img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image" alt="" />
                                </div>
                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;">

                                </div>
                                <div>
                                    <span class="btn btn-success btn-file">
                                        <span class="fileinput-new"> Replace </span>
                                        <span class="fileinput-exists"> Replace </span>
                                        <input type="file" accept=".jpg, .jpeg, .gif, .png" name="logo_name" class="logo-file"> </span>
                                    <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput">
                                        <i class="fa fa-times"></i>
                                    </a>
                                    <input type="text" class="form-control logo_image_url" placeholder="Logo url" name="logo_url" value="{{old('logo_url')}}" /> 
                                    <a href="#" class="clear-logo-url">
                                        <i class="fa fa-times"></i>
                                    </a>

                                    <span class="error-message">{{Session::get('invalidUrl')}}</span>
                                    <span class="error-message">{{$errors->first('logo_url')}}</span>
                                </div>
                            </div> -->
                            <!-- <a class="btn btn-default" data-toggle="modal" data-target="#add-url">Add Url</a> -->
                            
                        </div>
                        
                        

                        <!-- <input type="hidden" name="logo_name" id="logo_name" value="{{old('logo_name')}}"> -->
                        <input type="hidden" class="company-has-logo" value="{{old('logo_name')}}">
                <div class="form-group">
                    <label class="col-md-3 control-label">URL</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" placeholder="URL" name="url" value="{{ old('url') }}"/> 
                		<span class="error-message">{{$errors->first('url')}}</span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label">About Company</label>
                    <div class="col-md-6">
                        <textarea class="form-control" name="description" id="company-desc" placeholder="Description" style="height: 150px;">{{ old('description') }}</textarea>
                        <div class="company-desc-result">0 chars</div>
                		<span class="error-message">{{$errors->first('description')}}</span>

                    </div>
                </div>
                <div class="form-group">               
                    <label class="col-md-3 control-label">About Company Short</label>
                    <div class="col-md-6">
                        <textarea type="text" id="short-description" class="form-control" placeholder="Short Description" rows="3" name="short_description">{{old('short_description')}}</textarea>
                        <div class="short-description-result">0 chars</div>
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
                    <label class="col-md-3 control-label">City HQ</label> 
                    <div class="col-md-6">
                        <!-- <select class="form-control select-city" id="selectpicker_city" title="Select City" old-name="{{ old('city') }}" name="city">
                         <option value="">Select City</option>
                        </select>
                        <span class="error-message">{{$errors->first('city')}}</span> -->
                        <input type="text" class="form-control" placeholder="City" name="city_name" id="city-name" data-type="company" value="{{old('city_name')}}">
                        <span class="error-message">{{$errors->first('city')}}</span>
                        <span class="error-message">{{$errors->first('city_country')}}</span>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-md-3 control-label">Country HQ</label>
                    <div class="col-md-6">
                        <select id="country" class="form-control" id="selectpicker_country" name="country">
                        <option value="">Select Country</option>
                        @foreach($countries as $country)
                            <option id="{{$country->name}}" class="company_country" data-content="{{$country->id}}" data-code="{{$country->abbreviation}}" @if($country->name == old('country')) selected @endif>{{$country->name}}</option>
                        @endforeach
                        </select>
                        <span class="error-message">{{$errors->first('country')}}</span>
                    </div>
                </div>
                

                <input type="hidden" class="form-control" name="city_longtitude" id="city-longtitude" value="{{old('city_longtitude')}}">
                <input type="hidden" class="form-control" name="city_latitude" id="city-latitude" value="{{old('city_latitude')}}">
                <input type="hidden" class="form-control" name="city_population" id="city-population" value="{{old('city_population')}}">
                <input type="hidden" class="form-control" name="city_country" id="city-country" value="{{old('city_country')}}">
                <input type="hidden" class="form-control" name="region" id="region" value="{{old('region')}}">

                <div class="form-group">
                    <label class="col-md-3 control-label">Industry</label>
                    <div class="col-md-6">
                        <select class="form-control select-sector" id="selectpicker_sector" title="Select Industry" name="industry">
                        <option value="">Select Industry</option> 
                        @foreach($sectors as $sector)
                            <option id="{{$sector->sectorName}}" class="company_country" data-content = "{{$sector->sectorName}}" value="{{$sector->sector_id}}" @if($sector->sector_id == old('industry')) selected @endif>{{$sector->sectorName}}</option>
                        @endforeach
                        </select>
                        <span class="error-message">{{$errors->first('industry')}}</span>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-md-3 control-label">Job Category</label>
                    <div class="col-md-6">
                        <select class="form-control select-category" id="selectpicker_category" title="Select Job Category" name="category">
                        <option value="">Select Job Category</option>
                        @foreach($categories as $category)
                            <option id="{{$category->categoryName}}" class="company_category" data-content = "{{$category->categoryName}}" value="{{$category->category_id}}" @if($category->category_id == old('category')) selected @endif>{{$category->categoryName}}</option>
                        @endforeach
                        </select>
                        <span class="error-message">{{$errors->first('category')}}</span>
                    </div>
                </div>
<!--                 <h3 class="information"><b>Job Information</b></h3>
                <div class="form-group">
                    <label class="col-md-3 control-label">Job Description</label> 
                    <div class="col-md-6">
                        <textarea type="text" class="form-control" id="looking-for" placeholder="Looking For" name="looking_for" value="{{ old('looking_for') }}" style="height: 150px;">{{old('looking_for')}}</textarea>
                        <div class="looking-for-result">0 chars</div> 
                        <span class="error-message">{{$errors->first('looking_for')}}</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Requirement</label>
                    <div class="col-md-6">
                        <textarea type="text" class="form-control" id="company-requirements" placeholder="Requirement" name="requirement" value="{{ old('requirement') }}" style="height: 150px;">{{ old('requirement') }}</textarea>
                        <div class="company-requirements-result">0 chars</div> 
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
                        <textarea type="text" id="comp-why-us" class="form-control" placeholder="Why Us" name="why_us" value="{{ old('why_us') }}" rows="3">{{ old('why_us') }}</textarea> 
                        <div class="comp-why-us-result">0 chars</div>
                        <span class="error-message">{{$errors->first('why_us')}}</span>
                    </div>
                </div> -->
<!--                 <h3 class="information"><b>Job Applying</b></h3>
                <div class="form-group">
                    
                        <input id="redirect" type="radio" class="form-control col-md-1 radio-check" name="job_applying" value="redirect" @if(old('job_applying') == 'redirect') checked @endif/ style="margin-left:110px">
                        <label class="control-label col-md-2">Redirect</label>
                    <div class="col-md-3">
                        
                        <input type="text" name="url_to_redirect" class="redirect_url" placeholder="Url to redirect" @if(old('job_applying') == 'redirect') value="{{old('url_to_redirect')}}" @endif>


                    </div>

                    <span class="error-message col-md-6 col-md-offset-3">{{$errors->first('url_to_redirect')}}</span>
                    
                </div>
                <div class="form-group">
                    
                        <input id="redirect" type="radio" class="form-control radio-check col-md-1" name="job_applying" style="margin-left:110px" value="form" @if(old('job_applying') == 'form') checked @endif /> 
                        <label class="control-label col-md-2">Internal Process</label>

                    <span class="error-message col-md-6 col-md-offset-3">{{$errors->first('job_applying')}}</span>
                </div> -->
                <input type="hidden" name="type" value="generic">
                
            </div>
            <input type='hidden'  name="is_published" class="is_published">
            <div class="form-actions form-actions-create-item">
                <div class="row">
                    <div class="col-md-offset-3 col-md-9">
                        <button type="submit" class="btn green publish">Publish</button>
                        <button type="submit" class="btn blue-madison save">Save</button>
                        <a type="button" class="btn grey-salsa btn-outline" href="{{action('Admin\CompanyController@getCompanies', ['date', 'asc']) }}">Cancel</a>
                    </div>
                </div>
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
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default logo-url-ok">OK</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

      </div>
    </div>

  </div>
</div>  -->         

	</form>
    <input id="input_24" name="image_name" type="file" accept="image/*" class="file-loading" style="display:none">
    <input type='hidden' id="token" value="{{csrf_token()}}">
	</div>
</div>
<script type="text/javascript">
    
</script>
@endsection

@section('scripts')
<script src="/js/google_map_autocomplete.js" type="text/javascript"></script>
<script src="/js/save_job.js" type="text/javascript"></script>
<script src="/js/company/upload.js" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('#company-desc').keydown(
            function(event){
              
             if (event.which == '13') {
                document.getElementById("company-desc").value =document.getElementById("company-desc").value + "\n";
                return false;
              }
        });
        // document.getElementById('arsh').innerHTML+="<br />"
    })

</script>

@endsection