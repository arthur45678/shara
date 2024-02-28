@extends('admin/app_admin')
@section('content')
<link href="/css/admin/users.css" rel="stylesheet" type="text/css" />
<div class="portlet box blue-chambray">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-gift"></i>Create User
        </div>
        <div class="tools">
            <a href="javascript:;" class="collapse"> </a>
            <a href=""><i class="icon-refresh" style="color: white"></i> </a>
        </div>
    </div>
    <div class="portlet-body form">
	<form method="post" files = "true" class="form-horizontal" id="create-user-form" action="{{action('Admin\UserController@postCreateUser')}}">
		{{csrf_field()}}
			
			<div class="form-body">
                <div class="form-group">
                
                
                    <label class="col-md-3 control-label">First Name</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" placeholder="First Name" maxlength="40" name="first_name" value="{{ old('first_name') }}"/>
                     	<span class="error-message">{{$errors->first('first_name')}}</span>   
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label">Last Name</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" placeholder="Last Name" name="last_name" value="{{ old('last_name') }}"/> 
                		<span class="error-message">{{$errors->first('last_name')}}</span>

                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label">Email</label>
                    <div class="col-md-6">
                        <input type="email" class="form-control" placeholder="Email" name="email" value="{{ old('email') }}"/>   
                		<span class="error-message">{{$errors->first('email')}}</span>

                    </div>
                </div>
                <div class="form-group">               
                    <label class="col-md-3 control-label">Password</label>
                    <div class="col-md-6">
                        <input type="password" class="form-control" placeholder="Password" name="password"/>
                        <span class="error-message">{{$errors->first('password')}}</span>                      
                    </div>
                </div>

                <input type="hidden" name="main_type" id="admin-type" value="{{old('admin_type')}}">
                <div class="form-group">
                    <label class="col-md-3 control-label">Generic Admin</label>
                    <div class="col-md-1 " >
                        <input type="radio" id="main-role-generic" name="admin_type" value="generic"  @if(old('admin_type') == 'generic') checked @else unchecked @endif class=" main-role">
                    </div>
                    <label class="col-md-2 control-label">Company Admin</label>
                    <div class="col-md-2" >
                        <input type="radio" id="main-role-company" style="" name="admin_type" value="company_admin"  @if(old('admin_type') == 'company_admin') checked @else unchecked @endif class=" main-role">
                    </div>
                    <span class="error-message col-md-5 col-md-offset-3">{{$errors->first('admin_type')}}</span> 
                    
                </div>
                <div class="form-group  hide" id="generic-admin">
                    <label class="col-md-3 control-label">Roles</label>
                    <div class="col-md-6">
                        <select class="selectpicker show-tick select-roles" id="selectpicker" multiple title="Select Roles" name="roles[]">
						@foreach($roles as $role)
							<option id="{{$role->slug}}" class="user_role" data-content = "<span class='label label-success role-tag'>{{$role->name}}</span>">{{$role->name}}</option>
						@endforeach
						</select>
						<div class="append-roles"></div>
                    </div>
                </div>

                <div class="form-group  hide" id="company-admin">
                    <label class="col-md-3 control-label">Companies</label>
                    <div class="col-md-6">
                        <select class="selectpicker show-tick select-roles" id="selectpicker" multiple autocomplete="true" title="Select Companies" name="companies[]">
                        @foreach($companies as $company)
                            <option class="user_role" data-content = "<span class='label label-warning role-tag'>{{$company->name}}</span>" value="{{$company->id}}">{{$company->name}}</option>
                        @endforeach
                        </select>
                    </div>
                    <span class="error-message col-md-5 col-md-offset-3">{{$errors->first('companies')}}</span>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label">Activation</label>
                    <div class="col-md-6">
                        <input type="checkbox" name="activation" checked class="check-box">
                        <div class="append-roles"></div>
                    </div>
                </div>
            </div>

            <div class="form-actions form-actions-create-item">
                <div class="row">
                    <div class="col-md-offset-3 col-md-9">
                        <button type="submit" class="btn green">Submit</button>
                        <a type="button" class="btn grey-salsa btn-outline" href="{{URL::to('admin/users/admins/date/asc')}}">Cancel</a>
                    </div>
                </div>
            </div>

	</form>
	</div>
</div>

@endsection

@section('scripts')
<script type="text/javascript">
    $(document).ready(function(){
        var adminType = $('#admin-type').val();

        $(window).load(function() {
              if(adminType == 'generic'){
                    $('#generic-admin').removeClass('hide');
                }else if(adminType == 'company_admin'){
                    $('#company-admin').removeClass('hide');
                }
        });

        $('#main-role-generic').on('click', function(){
            console.log($('#company-admin').attr('class'));
            $('#company-admin').addClass('hide');
            $('#generic-admin').removeClass('hide');
        });

        $('#main-role-company').on('click', function(){
            $('#company-admin').removeClass('hide');
            $('#generic-admin').addClass('hide');
        });
    })
</script>
@endsection