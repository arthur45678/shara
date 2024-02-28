@extends('admin/app_admin')
@section('content')
<link href="/css/admin/roles.css" rel="stylesheet" type="text/css" />
<div class="portlet box blue-chambray">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-gift"></i>Create Role
        </div>
        <div class="tools">
            <a href="javascript:;" class="collapse"> </a>
            <a href=""><i class="icon-refresh" style="color: white"></i> </a>
        </div>
    </div>
    <div class="portlet-body form">
	<form method="post" files = "true" class="form-horizontal" id="create-user-form" action="{{action('Admin\RoleController@postCreateRole')}}">
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
                    <label class="col-md-3 control-label">Permissions</label>
                    <div class="col-md-6">
                        <select class="selectpicker show-tick select-permissions" id="selectpicker" multiple title="Select Permissions" name="permissions[]">
						@foreach($permissions as $permission)
							<option id="{{$permission->name}}" class="role_permissions" data-content = "<span class='label label-success role-tag'>{{$permission->name}}</span>">{{$permission->name}}</option>
						@endforeach
						</select>
						<div class="append-roles"></div>
                    </div>
                </div>
            </div>
            <div class="form-actions form-actions-create-item">
                <div class="row">
                    <div class="col-md-offset-3 col-md-9">
                        <button type="submit" class="btn green">Submit</button>
                        <a type="button" class="btn grey-salsa btn-outline" href="{{URL::to('admin/roles')}}">Cancel</a>
                    </div>
                </div>
            </div>

	</form>
	</div>
</div>

@endsection