@extends('admin/app_admin')
@section('content')
<link href="/css/admin/countries.css" rel="stylesheet" type="text/css" />
<div class="portlet box blue-chambray">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-gift"></i>Add Country
        </div>
        <div class="tools">
            <a href="javascript:;" class="collapse"> </a>
            <a href=""><i class="icon-refresh" style="color: white"></i> </a>
        </div>
    </div>
    <div class="portlet-body form">
    <form method="post" files = "true" class="form-horizontal" id="create-user-form" action="{{action('Admin\CountryController@postAddCountry')}}">
        {{csrf_field()}}
            
            <div class="form-body">
                <div class="form-group">
                
                
                    <label class="col-md-3 control-label">Name</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" placeholder="Name" maxlength="40" name="name" value="{{ old('name') }}"/>
                        <span class="error-message">{{$errors->first('name')}}</span>   
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label">Abbreviation</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" placeholder="Abbreviation" name="abbreviation" value="{{ old('abbreviation') }}"/> 
                        <span class="error-message">{{$errors->first('abbreviation')}}</span>

                    </div>
                </div>
                <div class="form-group">               
                    <label class="col-md-3 control-label">Language</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" placeholder="Language" name="language" value="{{ old('language') }}"/>
                        <span class="error-message">{{$errors->first('language')}}</span>                      
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Currency</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" placeholder="Currency" name="currency" value="{{ old('currency') }}"/>
                        <span class="error-message">{{$errors->first('currency')}}</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Metric</label>
                    <div class="col-md-6">
                        <select class="selectpicker show-tick select-metric" id="selectpicker" title="Metric" name="metric">
                        
                            <option data-content = "Kilometers">Kilometers</option>
                            <option data-content = "Miles">Miles</option>
                        </select>
                        <span class="error-message metric-error">{{$errors->first('metric')}}</span>
                    </div>
                </div>
            </div>
            <div class="form-actions form-actions-create-item">
                <div class="row">
                    <div class="col-md-offset-3 col-md-9">
                        <button type="submit" class="btn green">Submit</button>
                        <a type="button" class="btn grey-salsa btn-outline" href="{{URL::to('admin/countries')}}">Cancel</a>
                    </div>
                </div>
            </div>

    </form>
    </div>
</div>

@endsection