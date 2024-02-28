@extends('admin/app_admin')
@section('content')
<link href="/css/admin/users.css" rel="stylesheet" type="text/css" />
<div class="portlet box blue-chambray" onload="type()">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-gift"></i>Update User
        </div>
        <div class="tools">
            <a href="javascript:;" class="collapse"> </a>
            <a href=""><i class="icon-refresh" style="color: white"></i> </a>
        </div>
    </div>
    <div class="portlet-body form">
	<form method="post" files = "true" class="form-horizontal" id="create-user-form" action="{{action('Admin\UserController@postEditUser')}}">
		{{csrf_field()}}
			<div class="form-body">
                <div class="form-group">
                    <label class="col-md-3 control-label">First Name</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" placeholder="First Name" maxlength="40" name="first_name" value="{{$user->first_name}}"/>
                        <span class="error-message">{{$errors->first('first_name')}}</span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label">Last Name</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" placeholder="Last Name" name="last_name" value="{{ $user->last_name }}"/>
                        <span class="error-message">{{$errors->first('last_name')}}</span>                    
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label">Email</label>
                    <div class="col-md-6">
                        <input type="email" class="form-control" placeholder="Email" name="email" value="{{ $user->email }}" @if($user->role == 'from_admin')  readonly="readonly" @endif/>
                        <span class="error-message">{{$errors->first('email')}}</span>                      
                    </div>
                </div>
                @if($user->role == 'from_registration')
                <div class="form-group">
                    <label class="col-md-3 control-label">Location</label>
                    <div class="col-md-6">
                        <input type="text" id="user-location" class="form-control" placeholder="Location" name="location" @if(old('location'))value="{{ old('location') }}" @else value="{{$user->location}}" @endif/> 
                        <span class="error-message">{{$errors->first('location')}}</span>
                        <input type="hidden" id="country" name="country" @if(old('country'))value="{{ old('country') }}" @else value="{{$user->country}}" @endif>
                        <input type="hidden" id="city" name="city" @if(old('city'))value="{{ old('city') }}" @else value="{{$user->city}}" @endif>
                    </div>
                </div>
                @endif
                <div class="form-group">               
                    <label class="col-md-3 control-label">Old Password</label>
                    <div class="col-md-6">
                        <input type="password" class="form-control" placeholder="Old Password" name="old_password"/>
                        <span class="error-message">{{Session::get('old_password')}}</span>                      
                    </div>
                </div>
                <div class="form-group">               
                    <label class="col-md-3 control-label">New Password</label>
                    <div class="col-md-6">
                        <input type="password" class="form-control" placeholder="New Password" name="new_password"/>
                        <span class="error-message">{{$errors->first('new_password')}}</span>                      
                    </div>
                </div>
                
                @if($user->role == 'from_admin')
                <div class="form-group">
                    <label class="col-md-3 control-label">Generic Admin</label>
                    <div class="col-md-1 ">
                        <input type="radio" style="" name="admin_type" value="generic" id="main-role-generic" @if($user->admin_type == 'generic') checked @endif class=" main-role">
                    </div>
                    <label class="col-md-2 control-label">Company Admin</label>
                    <div class="col-md-2">
                        <input type="radio" style="" name="admin_type" value="company_admin" id="main-role-company" @if($user->admin_type == 'company_admin') checked @endif class=" main-role">
                    </div>
                    <span class="error-message col-md-5 col-md-offset-3">{{$errors->first('admin_type')}}</span> 
                    
                </div>
                <input type="hidden" @if(old('admin_type')) value="{{old('admin_type')}}" @else value="{{$user->admin_type}}" @endif name="main_type" id="admin-type">

                <div class="form-group hide" id="generic-admin">
                    <label class="col-md-3 control-label">Roles</label>
                    <div class="col-md-6">
                        <select class="selectpicker show-tick select-roles" id="selectpicker" multiple title="Select Roles" name="roles[]">
                        @foreach($roles as $role)
                            @if(in_array($role->slug, $user_roles))
                                <option id="{{$role->slug}}" class="user_role" data-content = "<span class='label label-success role-tag'>{{$role->name}}</span>" selected>{{$role->name}}</option>
                            @else
                                <option id="{{$role->slug}}" class="user_role" data-content = "<span class='label label-success role-tag'>{{$role->name}}</span>">{{$role->name}}</option>
                            @endif
                        @endforeach
                        </select>
                        <div class="append-roles"></div>
                    </div>
                </div>

                <div class="form-group hide" id="company-admin">
                    <label class="col-md-3 control-label">Companies</label>
                    <div class="col-md-6">
                        <select class="selectpicker show-tick select-roles" id="selectpicker" multiple autocomplete="true" title="Select Companies" name="companies[]">
                        @foreach($companies as $company)
                            <option value="{{$company->id}}" class="user_role" data-content = "<span class='label label-warning role-tag' >{{$company->name}}  </span>" @if($company->admins->contains($user->id)) selected @endif> {{$company->name}}</option>
                        @endforeach
                        </select>
                    </div>
                    <span class="error-message col-md-5 col-md-offset-3">{{$errors->first('companies')}}</span>
                </div>

                @endif

                <div class="form-group">
                    <label class="col-md-3 control-label">Suspended</label>
                    <div class="col-md-6">
                        <input type="checkbox" class="check-box" name="suspended" @if($user->activation != 'activated') checked @endif>
                        <div class="append-roles"></div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-md-3 control-label">Activation</label>
                    <div class="col-md-6">
                        <input type="checkbox" class="check-box" onclick="return false;" @if($activated) checked @endif>
                        <div class="append-roles"></div>
                    </div>
                </div>
                <input type="hidden" name="user_id" value="{{$user->id}}" />
                
            </div>
            <div class="form-actions form-actions-create-item">
                <div class="row">
                    <div class="col-md-offset-3 col-md-9">
                        <button type="submit" class="btn green">Update</button>
                        @if($user->role == 'from_admin')
                            <a type="button" class="btn grey-salsa btn-outline" href="{{URL::to('admin/users/admins/date/asc')}}">Cancel</a>
                        @else
                            <a type="button" class="btn grey-salsa btn-outline" href="{{URL::to('admin/users/users/date/asc')}}">Cancel</a>
                        @endif
                        
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
            $('#company-admin').addClass('hide');
            $('#generic-admin').removeClass('hide');
        });

        $('#main-role-company').on('click', function(){
            $('#company-admin').removeClass('hide');
            $('#generic-admin').addClass('hide');
        });
    })
</script>
<script src="/js/google_map_autocomplete.js" type="text/javascript"></script>
@endsection