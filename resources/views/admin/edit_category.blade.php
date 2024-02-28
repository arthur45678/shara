@extends('admin/app_admin')
@section('content')
<link href="/css/admin/categories.css" rel="stylesheet" type="text/css" />
<div class="portlet box blue-chambray">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-gift"></i>Update Category
        </div>
        <div class="tools">
            <a href="javascript:;" class="collapse"> </a>
            <a href=""><i class="icon-refresh" style="color: white"></i> </a>
        </div>
    </div>
    <div class="portlet-body form">
	<form method="post" files = "true" class="form-horizontal" id="create-user-form" action="{{action('Admin\CategoryController@postEditCategory')}}">
        {{csrf_field()}}
            
            <div class="form-body">
                @foreach($locales as $key => $locale)
                <div class="form-group">
                    <label class="col-md-3 control-label locale-label">{{$locale}}</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" placeholder="Name" maxlength="40" name="translated_names[{{$locale}}]" @if(isset($translated_names[$locale])) value="{{$translated_names[$locale]}}" @endif/>
                        @if($errors->first('translated_names.en') && $locale == 'en')
                        <span class="error-message" style="color:red">English name is required.</span>
                        @endif 
                    </div>
                </div>
                @endforeach
                <div class="form-group">
                <label class="col-md-3 control-label">Icon</label>
                    <div class="col-md-3"> 
                    <select class="form-control " name="icon">
                        <option value="">Select Icon</option>
                        @foreach($icons as $icon)
                            <option id="{{$icon->id}}" class="icon" data-content="{{$icon->id}}" value="{{$icon->name}}" @if($icon->name == $category->icon) selected @endif >{{$icon->name}}</option>
                        @endforeach
                    </select>
                    </div>                   
                </div>
                <div class="form-group">
                <label class="col-md-3 control-label">Image</label>
                    <div class="col-md-3"> 
                    <select class="form-control " name="image">
                        <option value="">Select Image</option>
                            <option class="icon" value="baby_sitter.jpg">Baby Sitter</option>
                            <option class="icon" value="bar_tender.jpg">Bar Tender</option>
                            <option class="icon" value="beauty.jpg">Beauty</option>
                            <option class="icon" value="car_driver.jpg">Car Driver</option>
                            <option class="icon" value="driver_people.jpg">Car Driver People Transportation</option>
                            <option class="icon" value="care.jpg">Care Givers</option>
                            <option class="icon" value="check_in_service.jpg">Check In Service</option>
                            <option class="icon" value="cook.jpg">Cook</option>
                            <option class="icon" value="courier.jpg">Courier</option>
                            <option class="icon" value="dog_walking.jpg">Dog Walking</option>
                            <option class="icon" value="food_delivery.jpg">Food Delivery</option>
                            <option class="icon" value="general_delivery.jpg">General Delivery</option>
                            <option class="icon" value="beauty.jpg">Hair and Beauty</option>
                            <option class="icon" value="home_cleaning.jpg">Home Cleaning</option>
                            <option class="icon" value="hosstes_stewart.jpg">Hostess Steward</option>
                            <option class="icon" value="ikea_assembly.jpg">Ikea Assembly</option>
                            <option class="icon" value="laundry.jpg">Laundry</option>
                            <option class="icon" value="legal_services.jpg">Legal Services</option>
                            <option class="icon" value="massage.jpg">Massage</option>
                            <option class="icon" value="mechanic.jpg">Car Mechanic</option>
                            <option class="icon" value="moving_services.jpg">Moving Services</option>
                            <option class="icon" value="multiple_professional.jpg">Multiple Professional</option>
                            <option class="icon" value="nail_care.jpg">Nail Care</option>
                            <option class="icon" value="personal_trainer.jpg">Personal Trainer</option>
                            <option class="icon" value="pet_care.jpg">Pet Care</option>
                            <option class="icon" value="photographer.jpg">Photographer</option>
                            <option class="icon" value="remote.jpg">Remote</option>
                            <option class="icon" value="retail_assistant.jpg">Retail Assistant</option>
                            <option class="icon" value="security.jpg">Security</option>
                            <option class="icon" value="software_programming.jpg">Software Programming</option>
                            <option class="icon" value="tech_suppport.jpg">Tech Support</option>
                            <option class="icon" value="tutoring.jpg">Tutoring</option>
                            <option class="icon" value="various.jpg">Various</option>
                            <option class="icon" value="waiiter.jpg">Waiter</option>
                            <option class="icon" value="baby_sitter.jpg">Baby Sitter</option>
                            <option class="icon" value="driver_rider.jpg">Driver/Rider</option>
                            <option class="icon" value="online.jpg">Online</option>
                            <option class="icon" value="smartphone_app.jpg">Smartphone App</option>
                    </select>
                    </div>                   
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Activate</label>
                    <div class="col-md-6">
                        <input type="checkbox" class="form-control check-box" name="activate" id="activate" @if($category->activation == 'activated') checked @endif/>
                          
                    </div>
                </div>
                <input type="hidden" name="category_id" value="{{$category->id}}">
            </div>
            <div class="form-actions form-actions-create-item">
                <div class="row">
                    <div class="col-md-offset-3 col-md-9">
                        <button type="submit" class="btn green">Submit</button>
                        <a type="button" class="btn grey-salsa btn-outline" href="{{URL::to('admin/categories')}}">Cancel</a>
                    </div>
                </div>
            </div>

    </form>
	</div>
</div>

@endsection