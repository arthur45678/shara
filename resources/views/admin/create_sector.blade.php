@extends('admin/app_admin')
@section('content')
<link href="/css/admin/sectors.css" rel="stylesheet" type="text/css" />
<div class="portlet box blue-chambray">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-gift"></i>Create Industry
        </div>
        <div class="tools">
            <a href="javascript:;" class="collapse"> </a>
            <a href=""><i class="icon-refresh" style="color: white"></i> </a>
        </div>
    </div>
    <div class="portlet-body form">
	<form method="post" files = "true" class="form-horizontal" id="create-user-form" action="{{action('Admin\SectorController@postCreateSector')}}">
		{{csrf_field()}}
			
			<div class="form-body">
                @foreach($locales as $key => $locale)
                <div class="form-group">
                    <label class="col-md-3 control-label locale-label">{{$locale}}</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" placeholder="Name" maxlength="40" name="translated_names[{{$locale}}]" value="{{ old('name') }}"/>
                        @if($errors->first('translated_names.en') && $locale == 'en')
                        <span class="error-message" style="color:red">English name is required.</span>
                        @endif   
                    </div>
                </div>
                @endforeach
                <div class="form-group">
                    <label class="col-md-3 control-label">Activate</label>
                    <div class="col-md-6">
                        <input type="checkbox" class="form-control check-box" name="activate" id="activate" checked />
                          
                    </div>
                </div>
            </div>
            <div class="form-actions form-actions-create-item">
                <div class="row">
                    <div class="col-md-offset-3 col-md-9">
                        <button type="submit" class="btn green">Submit</button>
                        <a type="button" class="btn grey-salsa btn-outline" href="{{URL::to('admin/sectors')}}">Cancel</a>
                    </div>
                </div>
            </div>

	</form>
	</div>
</div>

@endsection