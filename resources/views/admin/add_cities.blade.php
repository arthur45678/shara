@extends('admin/app_admin')
@section('content')
<link href="/css/admin/cities.css" rel="stylesheet" type="text/css" />
<div class="portlet box blue-chambray">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-gift"></i>Import
        </div>
        <div class="tools">
            <a href="javascript:;" class="collapse"> </a>
            <a href=""><i class="icon-refresh" style="color: white"></i> </a>
        </div>
    </div>
    <div class="portlet-body form">
    <form method="post" files = "true" class="form-horizontal" id="create-city-form" action="{{action('Admin\CityController@postAddCities')}}" enctype = "multipart/form-data">
        {{csrf_field()}}
            
            <div class="form-body">
                <div class="form-group">
                
                
                    <label class="col-md-3 control-label">Csv file</label>
                    <div class="col-md-6">
                        <input type="file" class="form-control"  name="csv_file" accept=".csv"/>
                        <span class="error-message">{{Session::get('csv_file')}}</span>    
                    </div>
                </div>
            </div>
            <div class="form-actions form-actions-create-item">
                <div class="row">
                    <div class="col-md-offset-3 col-md-9">
                        <button type="submit" class="btn green">Submit</button>
                        <a type="button" class="btn grey-salsa btn-outline" href="{{URL::to('admin/cities')}}">Cancel</a>
                    </div>
                </div>
            </div>

    </form>
    </div>
</div>

@endsection