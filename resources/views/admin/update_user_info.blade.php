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
	<form method="post" files = "true" class="form-horizontal" id="create-user-form" action="{{action('Admin\UserController@postUpdateUserInfo')}}">
		{{csrf_field()}}
			<div class="form-body">
                @if($user->image)
                    <div  style="width:30%;margin:10px auto;">
                        <img src="/uploads/{{$user->image}}" class="user-update-pic">
                    </div>
                @else
                    <div  style="width:30%;margin:10px auto;">
                        <img src="http://www.placehold.it/200x200/EFEFEF/AAAAAA&text=no+image" class="user-update-pic" alt="" id="pic" />
                    </div>
                @endif
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
                        <input type="email" class="form-control" placeholder="Email" name="email" value="{{ $user->email }}" readonly="readonly" />
                        <span class="error-message">{{$errors->first('email')}}</span>                      
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Birth Date</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" placeholder="Birth Date" name="birth_date" value="{{ $user->birth_date }}" />
                        <span class="error-message">{{$errors->first('birth_date')}}</span>                      
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Location</label>
                    <div class="col-md-6">
                        <input type="text" id="user-location" class="form-control" placeholder="Location" name="location" @if(old('location'))value="{{ old('location') }}" @else value="{{$user->location}}" @endif/> 
                        <span class="error-message">{{$errors->first('location')}}</span>
                        <input type="hidden" id="country" name="country" @if(old('country'))value="{{ old('country') }}" @else value="{{$user->country}}" @endif>
                        <input type="hidden" id="city" name="city" @if(old('city'))value="{{ old('city') }}" @else value="{{$user->city}}" @endif>
                        <input type="hidden" id="latitude" name="latitude" @if(old('latitude'))value="{{ old('latitude') }}" @else value="{{$user->latitude}}" @endif>
                        <input type="hidden" id="longitude" name="longitude" @if(old('longitude'))value="{{ old('longitude') }}" @else value="{{$user->longitude}}" @endif>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Phone Number</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control phone_number" placeholder="Phone Number" name="phone_number" value="{{ $user->phone_number }}" />
                        <input type="hidden" name="phone_code" class="phone_code" />
                        <span class="error-message">{{$errors->first('phone_number')}}</span>                      
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Gender</label>
                    <select name="gender">
                        <option value="Male" @if($user->gender == 'male') selected @endif>Male</option>
                        <option value="Female" @if($user->gender == 'female') selected @endif>Female</option>
                    </select>
                    <span class="error-message">{{$errors->first('gender')}}</span>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Nationality</label>
                    <select name="nationality">
                        @foreach($countries as $country)
                            <option value="{{$country->name}}" @if($user->nationality == $country->name) selected @endif>{{$country->name}}</option>
                        @endforeach
                    </select>
                    <span class="error-message">{{$errors->first('gender')}}</span>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Transport</label>
                    <select multiple name="transport[]">
                        <option value="car" @if($userTransport) @if(in_array('car', $userTransport)) selected @endif @endif>Car</option>
                        <option value="bike" @if($userTransport) @if(in_array('bike', $userTransport)) selected @endif @endif>Bike</option>
                        <option value="scooter" @if($userTransport) @if(in_array('scooter', $userTransport)) selected @endif @endif>Scooter</option>
                        <option value="truck" @if($userTransport) @if(in_array('truck', $userTransport)) selected @endif @endif>Truck</option>
                    </select>
                    <span class="error-message">{{$errors->first('transport')}}</span>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Education</label>
                    <select name="education[]">
                        <option value="Hight School" @if($userEducation) @if($userEducation == 'School')) selected @endif @endif>High School</option>
                        <option value="Undergraduate" @if($userEducation) @if($userEducation == 'Undergraduate') selected @endif @endif>Undergraduate</option>
                        <option value="Graduate" @if($userEducation) @if($userEducation == 'Graduate') selected @endif @endif>Graduate</option>
                    </select>
                    <span class="error-message">{{$errors->first('education')}}</span>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Languages</label>
                    <select multiple name="languages[]">
                        @foreach($languages as $language)
                        <option value="{{$language['id']}}" @if(in_array($language['id'], $userLanguages)) selected @endif>{{$language['language']}}</option>
                        @endforeach
                    </select>
                    
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label">Skills</label>
                    <select multiple name="skills[]">
                        @foreach($categories as $category)
                        <option value="{{$category['id']}}" @if(in_array($category['id'], $userCategories)) selected @endif>{{$category['name']}}</option>
                        @endforeach
                    </select>
                    <span class="error-message">{{$errors->first('skills')}}</span>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label">User Experience</label>
                    <div class="col-md-6">
                        <textarea type="text" class="form-control" placeholder="User Experience" name="user_experience">{{$user->user_experience}}</textarea>
                        <span class="error-message">{{$errors->first('user_experience')}}</span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label">Facebook Profile</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" placeholder="Facebook Link" name="facebook_link" value="{{$user->facebook_link}}" />
                        <span class="error-message">{{$errors->first('facebook_link')}}</span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label">Availability</label>
                    <select name="schedule">
                        <option value="part time" @if($userSchedule == 'part time') selected @endif>Part Time</option>
                        <option value="full time" @if($userSchedule == 'full time') selected @endif>Full Time</option>
                        <option value="both" @if($userSchedule == 'both') selected @endif>Both</option>
                    </select>
                    <span class="error-message">{{$errors->first('schedule')}}</span>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label">Daily</label>
                    <select multiple name="week_days[]">
                        <option value="monday" @if($userWeekDays) @if(in_array('monday', $userWeekDays)) selected @endif @endif>Monday</option>
                        <option value="tuesday" @if($userWeekDays) @if(in_array('tuesday', $userWeekDays)) selected @endif @endif>Tuesday</option>
                        <option value="wednesday" @if($userWeekDays) @if(in_array('wednesday', $userWeekDays)) selected @endif @endif>Wednesday</option>
                        <option value="thursday" @if($userWeekDays) @if(in_array('thursday', $userWeekDays)) selected @endif @endif>Thursday</option>
                        <option value="friday" @if($userWeekDays) @if(in_array('friday', $userWeekDays)) selected @endif @endif>Friday</option>
                        <option value="saturday" @if($userWeekDays) @if(in_array('saturday', $userWeekDays)) selected @endif @endif>Saturday</option>
                        <option value="sunday" @if($userWeekDays) @if(in_array('sunday', $userWeekDays)) selected @endif @endif>Sunday</option>
                    </select>
                    <span class="error-message">{{$errors->first('week_days')}}</span>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label">Hourly</label>
                    <select multiple name="hours[]">
                        <option value="morning" @if($userHours) @if(in_array('morning', $userHours)) selected @endif @endif>Morning: 8-13</option>
                        <option value="afternoon" @if($userHours) @if(in_array('afternoon', $userHours)) selected @endif @endif>Afternoon: 13-18</option>
                        <option value="evening" @if($userHours) @if(in_array('evening', $userHours)) selected @endif @endif>Evening: 18-23</option>
                        <option value="night" @if($userHours) @if(in_array('night', $userHours)) selected @endif @endif>Night: 23-8</option>
                    </select>
                    <span class="error-message">{{$errors->first('hours')}}</span>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label">Working Area</label>
                    <select multiple name="working_area[]">
                        <option value="My_area" @if($userWorkingArea) @if(in_array('My_area', $userWorkingArea)) selected @endif @endif>In My Area</option>
                        <option value="Outside_my_area" @if($userWorkingArea) @if(in_array('Outside_my_area', $userWorkingArea)) selected @endif @endif>Outside My Area</option>
                        <option value="Remotely" @if($userWorkingArea) @if(in_array('Remotely', $userWorkingArea)) selected @endif @endif>Remotely</option>
                    </select>
                    <span class="error-message">{{$errors->first('working_area')}}</span>
                </div>
                <!-- <div class="form-group">               
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
                </div> -->
                
                
                
                <!-- <div class="form-group">
                    <label class="col-md-3 control-label">Activation</label>
                    <div class="col-md-6">
                        <input type="checkbox" class="check-box" name="activation" @if($user->activation == 'activated') checked @endif>
                        <div class="append-roles"></div>
                    </div>
                </div> -->
                <input type="hidden" name="user_id" value="{{$user->id}}" />
                
            </div>
            <div class="form-actions form-actions-create-item">
                <div class="row">
                    <div class="col-md-offset-3 col-md-9" style="padding-left: 70px;">
                        <button type="submit" class="btn green">Update</button>

                            <a type="button" class="btn grey-salsa btn-outline" href="{{URL::to('admin/users/users/date/asc')}}">Cancel</a>
                        
                    </div>
                </div>
            </div>

	</form>
	</div>
</div>

@endsection
@section('scripts')
<script src="/js/google_map_autocomplete.js" type="text/javascript"></script>
@endsection
