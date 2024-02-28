@extends('admin/app_admin')
@section('content')
<link href="/css/admin/users.css" rel="stylesheet" type="text/css" />
	<div class="row-fluid">
        <div class="span12">
            @if($role == 'users')
            <form action="{{action('Admin\UserController@getExportCsv')}}" method="get" class="form-horizontal search_form">
            <input type="hidden" value="{{$filteredUsers}}" name="filtered_users">
             <button type="submit" class="button-search btn btn-success  pull-right">Export</button>
            </form>
            @endif
        	<a href="{{url('/admin/create-user')}}" type="button" class="btn btn-success create-button">Create User</a>
            
                
            </form>
            @if($role == 'users')
            <button type="submit" class="button-search" data-toggle="collapse" data-target="#filter"><i style="color:black" class='fa fa-search header-search'></i></button>
           @include('messages')
            
            <div id="filter" class="collapse">
                <form action="{{action('Admin\UserController@getUsers', ['users', 'last_uploaded', $type])}}" method="get" class="form-horizontal search_form">
                    {{csrf_field()}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <select class="form-control" id="country" name="country">
                                        <option value="">Select Country</option>
                                        @foreach($countries as $country)
                                            <option id="{{$country->name}}" class="company_country" data-content = "{{$country->id}}" data-code="{{$country->abbreviation}}" @if($country->name == old('country')) selected @endif>{{$country->name}}</option>
                                        @endforeach
                                    </select>
                                    <span class="error-message">{{$errors->first('country')}}</span> 
                                </div>                   
                            </div>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <input type="text" class="form-control" placeholder="City" name="city" id="city-name">
                                    <span class="error-message">{{$errors->first('city')}}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12">

                                    <input type="text" class="form-control" placeholder="Email" name="email">
                                    <span class="error-message">{{$errors->first('email')}}</span>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="col-md-12">
                                    <select class="form-control select-category" id="selectpicker_category" title="Select Job Category" name="skill">
                                    <option value="">Select Skill</option>
                                    @foreach($categories as $category)
                                        <option id="{{$category->categoryName}}" class="company_category" data-content = "{{$category->categoryName}}" value="{{$category->category_id}}" @if($category->category_id == old('category')) selected @endif>{{$category->categoryName}}</option>
                                    @endforeach
                                    </select>
                                    <span class="error-message">{{$errors->first('category')}}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <select class="form-control select-category" id="selectpicker_category" title="Select Job Category" name="transport">
                                    <option value="">Select Transportation</option>
                                        <option value="bike">Bike</option>
                                        <option value="scooter">Scooter</option>
                                        <option value="car">Car</option>
                                        <option value="truck">Truck</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-5">
                                    <input class="form-control" name="fromDate" type="date" />
                                </div>
                                <div class="col-md-2" style="text-align: center;">-</div>
                                <div class="col-md-5">
                                    <input name="toDate" class="form-control" type="date" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <select class="form-control select-category" id="selectpicker_category" title="Select Job Category" name="education">
                                    <option value="">Select Education</option>
                                        <option value="School">High School</option>
                                        <option>Undergraduate</option>
                                        <option>Graduate</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <select class="form-control select-category" id="selectpicker_category" title="Select Job Category" name="schedule">
                                    <option value="">Schedule</option>
                                        <option>Full Time</option>
                                        <option>Part Time</option>
                                        <option>Both</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <select class="form-control select-category" id="selectpicker_category" title="Select Job Category" name="week_days">
                                    <option value="">Available to work: daily</option>
                                        <option value="monday">Monday</option>
                                        <option value="tuesday">Tuesday</option>
                                        <option value="wednesday">Wednesday</option>
                                        <option value="thursday">Thursday</option>
                                        <option value="friday">Friday</option>
                                        <option value="saturday">Saturday</option>
                                        <option value="sunday">Sunday</option>

                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <select class="form-control select-category" id="selectpicker_category" title="Select Job Category" name="hours">
                                    <option value="">Available to work: hourly</option>
                                        <option value="morning">Morning: 8-13</option>
                                        <option value="afternoon">Afternoon: 13-18</option>
                                        <option value="evening">Evening: 18-23</option>
                                        <option value="night">Night: 23-8</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <select class="form-control select-category" id="selectpicker_category" title="Select Job Category" name="working_area">
                                    <option value="">Working area</option>
                                        <option value="My_area">In my area</option>
                                        <option value="Outside_my_area">Outside my area: 20-100Km</option>
                                        <option value="Remotely">Remotely</option>
                                    </select>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    <button type="submit" class="button-search btn btn-default">Filter</button>
                </form>
            </div>
            @endif
            <div class="portlet box blue-chambray">
                <div class="portlet-title">
                    <div class="caption"><i class="icon-users"></i>Users Management</div>
                    <div class="users-count caption" style="float: right;">{{$count}}</div>          
                </div>
                <div class="portlet-body no-more-tables">
                    @if (count($users) > 0)
                    @if($role == 'admins')
                    <div class="table-responsive">
                    <table class="table table-bordered table-hover responsive-table">
                            <thead>
                                <tr>
                                    <th> # </th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Roles</th>
                                    <th></th>
                                    <th>Action</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $key => $user)
                                    <tr>
                                        <td>{{($page-1)*50 + $key + 1}}</td>
                                        <td>{{$user->first_name}} {{$user->last_name}}</td>
                                        <td>{{$user->email}}</td>
                                        <td>
                                        	@if(count($user->roles) > 0)
		                                        @foreach($user->roles as $key => $role)
		                                        	{{$role->name}}
		                                        	@if($key != count($user->roles) - 1),
		                                        	@endif
		                                        @endforeach
		                                    @else
		                                    	None
		                                    @endif
                                        </td>
                                        
                                        <td>
                                        <a href="{{URL::to('/admin/show-user',$user->id)}}" type="button" class="btn btn-info" title="Show">
                                            <i class="fa fa-book"></i></a>
                                        </td>
                                        <td>
                                        <a href = "{{URL::to('/admin/edit-user',$user->id)}}" type="button" title="Update" @if(count($user->roles) > 0) @foreach($user->roles as $role) @if($role->slug == 'superadmin' && $superadmins_count == 1) class = "btn btn-primary not-active" disabled @else class="btn btn-primary" @endif @endforeach @else class="btn btn-primary" @endif >
                                        	<i class="fa fa-edit"></i></a>
                                        </td>
                                        <td>
                                        <a type="button" data-toggle="modal" data-target = "#user_delete" href="#small" alt="{{$user->id}}" title="Delete" @if(count($user->roles) > 0) @foreach($user->roles as $role) @if($role->slug == 'superadmin') class="btn btn-danger show_modal_user not-active" disabled @else class="btn btn-danger show_modal_user" @endif @endforeach @else class="btn btn-danger show_modal_user" @endif>
                                        	<i class="fa fa-trash-o"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </div>
                        @elseif($role == 'users')
                        <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th> # </th>
                                    <th>
                                        <form action="{{action('Admin\UserController@getUsers', ['users', 'last_uploaded', $type])}}" method="get" class="form-horizontal search_form">
                                            <input type="hidden" name="country" @if(isset($searchDetails['country']))value="{{$searchDetails['country']}}"@endif>
                                            <input type="hidden" name="email" @if(isset($searchDetails['email']))value="{{$searchDetails['email']}}"@endif>
                                            <input type="hidden" name="skill" @if(isset($searchDetails['skill']))value="{{$searchDetails['skill']}}"@endif>
                                            <input type="hidden" name="city" @if(isset($searchDetails['city']))value="{{$searchDetails['city']}}"@endif>
                                            <input type="hidden" name="transport" @if(isset($searchDetails['transport']))value="{{$searchDetails['transport']}}"@endif>
                                            <input type="hidden" name="education" @if(isset($searchDetails['education']))value="{{$searchDetails['education']}}"@endif>
                                            <input type="hidden" name="schedule" @if(isset($searchDetails['schedule']))value="{{$searchDetails['schedule']}}"@endif>
                                            <input type="hidden" name="week_days" @if(isset($searchDetails['week_days']))value="{{$searchDetails['week_days']}}"@endif>
                                            <input type="hidden" name="hours" @if(isset($searchDetails['hours']))value="{{$searchDetails['hours']}}"@endif>
                                            <input type="hidden" name="working_area" @if(isset($searchDetails['working_area']))value="{{$searchDetails['working_area']}}"@endif>
                                            <button class="link-button" type="submit">Image</button>
                                        </form>
                                    </th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Skills</th>
                                    <th>Country</th>
                                    <th>City</th>
                                    <th></th>
                                    <th>Action</th>
                                    <th></th>
                                    <th></th>
                                    <th>Restriction</th> 
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $key => $user)
                                    <tr>
                                        <td>{{($page-1)*50 + $key + 1}}</td>
                                        <td>
                                            <div data-toggle="modal" data-target = "#show_image">
                                                @if($user->image && $user->image != '')
                                                <img src="/uploads/{{$user->image}}" class="list_image image" alt="" id="pic" />
                                                @else
                                                <img src="http://www.placehold.it/200x200/EFEFEF/AAAAAA&text=no+image" class="list_image image" alt="" id="pic" />
                                                
                                                @endif
                                            </div>
                                        </td>

                                        <td><a href="{{URL::to('/admin/update-user-info',$user->id)}}" target="__blank">{{$user->first_name}} {{$user->last_name}}</a></td>
                                        <td>{{$user->email}}</td>
                                        <td>
                                            @if(count($user->skills) > 0)
                                                @foreach($user->skills as $key => $value)
                                                    {{$value->name}}
                                                    @if($key != count($user->roles) - 1),
                                                    @endif
                                                @endforeach
                                            @else
                                                None
                                            @endif
                                        </td>
                                        <td>
                                            {{$user->country}}
                                        </td>
                                        <td>
                                            {{$user->city}}
                                        </td>
                                        <td>
                                        <a href="{{$user->redirectLink}}" target="_blank" type="button" class="btn btn-primary" title="Show">
                                            <i class="fa fa-eye"></i></a>
                                        </td>
                                        <td>
                                        <a href="{{URL::to('/admin/show-user',$user->id)}}" type="button" class="btn btn-info" title="Show">
                                            <i class="fa fa-book"></i></a>
                                        </td>
                                        <td>
                                        <a href = "{{URL::to('/admin/edit-user',$user->id)}}" type="button" title="Update" class="btn btn-primary">
                                            <i class="fa fa-edit"></i></a>
                                        </td>
                                        <td>
                                        <a type="button" data-toggle="modal" data-target = "#user_delete" href="#small" alt="{{$user->id}}" title="Delete" class="btn btn-danger show_modal_user" >
                                            <i class="fa fa-trash-o"></i></a>
                                        </td>
                                        <td>

                                            <a type="button" href="{{URL::to('admin/change-restriction',$user->id)}}" alt="{{$user->id}}" title="Restriction" @if($user->restrict == 'true') class="btn btn-danger " @else class="btn btn-success " @endif>
                                                @if($user->restrict == 'true')<i class="fa fa-ban"> @else<i class="fa fa-plus">@endif</i></a>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </div>
                        @endif

                        <span class="col-md-offset-5">{{$users->appends(Request::except('page'))->render()}}</span>
                    @else
                        <h3>No Users</h3>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade bs-modal-sm" id="user_delete" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Delete User </h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                    <a href=""  class="delete_user"><button type="button" class="btn red">Delete</button></a>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <div class="modal fade bs-modal-lg" id="show_image" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">User Image </h4>
                </div>
                <div class="">
                    <div class="image_modal_content">
                        <img class="modal_image" src="">
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

@endsection

@section('scripts')
<script type="text/javascript">
    $(document).ready(function(){
       $('.list_image').on('click', function(){
        var modalSrc = $(this).attr('src');
        $('.modal_image').attr('src', modalSrc);
       }) 
    })
</script>
@endsection