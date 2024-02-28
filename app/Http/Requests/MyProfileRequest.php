<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MyProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }

    /**
     * Get data from request and modify it for editing the user
     *
     * @param Illuminate\Http\Request $request
     * @return array
     */
    public function inputsFirstTab($request)
    {
        $data = $request['user'];        
        $data = json_decode($data, true);
        $user = \JWTAuth::parseToken()->authenticate();
        $transport = [
            'car'     => (isset($data['car']) && $data['car']) ? true : false,
            'bike'    => (isset($data['bike']) && $data['bike']) ? true : false,
            'truck'   => (isset($data['truck']) && $data['truck']) ? true : false,
            'scooter' => (isset($data['scooter']) && $data['scooter']) ? true : false,
        ];
        $transport_data = [];
        foreach($transport as $key => $value) {
            if($value == 'true') {
                $transport_data[] = $key;
            }
        }
        $transport_data = json_encode($transport_data);
        $education = [
            'School'        => (isset($data['school']) && $data['school']) ? true : false,
            'Undergraduate' =>  (isset($data['undergraduate']) && $data['undergraduate']) ? true : false,
            'Graduate'      =>  (isset($data['graduate']) && $data['graduate']) ? true : false,
        ];

        $education_data = [];
        foreach($education as $key => $value) {
            if($value == 'true') {
                $education_data[] = $key;
            }           
        }
        $education_data = json_encode($education_data);
        if(isset($data['nationality'])) {
            $nationality = $data['nationality'];
        }else {
            $nationality = null;
        }
        if(isset($data['gender'])) {
            $gender = $data['gender'];
        }else {
            $gender = null;
        }
        (isset($data['driving_license']) && $data['driving_license']) ? $driving_license = 1 : $driving_license = 0;
        (isset($data['currently_student']) && $data['currently_student']) ? $currently_student = 1 : $currently_student = 0;
        (isset($data['latitude']) && $data['latitude']) ? $latitude = $data['latitude'] : $latitude = '';
        (isset($data['longitude']) && $data['longitude']) ? $longitude = $data['longitude'] : $longitude = '';

        $location = $data['location'];
        (isset($data['latitude']) && $data['latitude']) ? $latitude = $data['latitude'] : $latitude = '';
        (isset($data['longitude']) && $data['longitude']) ? $longitude = $data['longitude'] : $longitude = '';

        $registration_data = [
            'activation' => 'activated',
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'birth_date' => $data['birth_date'],
            'phone_number' => $data['phone'],
            'transport' => $transport_data,
            'education' => $education_data,
            'driving_license' => $driving_license,
            'currently_student' => $currently_student,
            'role' => 'from_registration',
            'latitude' => $latitude,
            'longitude' => $longitude,
            'location' => $location,
            'nationality' => $nationality,
            'gender' => $gender
            
        ];
        if($location == $user->location) {
            $registration_data['country'] = $user->country;
            $registration_data['city'] = $user->city;
        }else {
            if(isset($data['country_name'])) {
                $registration_data['country'] = $data['country_name'];
            }

            if(isset($data['city_name'])) {
                $registration_data['city'] = $data['city_name'];
            }
        }
        return $registration_data;
    }

    /**
     * get json data from request and make it array
     *
     * @param Illuminate\Http\Request $request
     * @return array
     */
    public function data($request)
    {
        $data = $request['user'];        
        $data = json_decode($data, true);
        return $data;
    }

    /**
     * Get data from request and modify it for editing the user
     *
     * @param Illuminate\Http\Request $request
     * @return array
     */
    public function inputsThirdTab($request)
    {
        $data = $request['user'];        
        $data = json_decode($data, true);
        $week = [
            'monday' => (isset($data['monday']) && $data['monday']) ? true : false,
            'tuesday' =>  (isset($data['tuesday']) && $data['tuesday']) ? true : false,
            'wednesday' => (isset($data['wednesday']) && $data['wednesday']) ? true : false,
            'thursday' => (isset($data['thursday']) && $data['thursday']) ? true : false,
            'friday' =>  (isset($data['friday']) && $data['friday']) ? true : false,
            'saturday' => (isset($data['saturday']) && $data['saturday']) ? true : false,
            'sunday' => (isset($data['sunday']) && $data['sunday']) ? true : false
        ];
        $week_data = [];
        foreach($week as $key => $value) {
            if($value == 'true') {
                $week_data[] = $key;
            }
        }
        $week_data = json_encode($week_data);
        $hours = [
            'morning'   =>  (isset($data['morning']) && $data['morning']) ? true : false,
            'afternoon' =>  (isset($data['afternoon']) && $data['afternoon']) ? true : false,
            'evening'   =>  (isset($data['evening']) && $data['evening']) ? true : false,
            'night'     =>  (isset($data['night']) && $data['night']) ? true : false
        ];
        $hours_data = [];
        foreach($hours as $key => $value) {
            if($value == 'true') {
                $hours_data[] = $key;
            }
        }
        $hours_data = json_encode($hours_data);
        $area = [
            'My_area'         =>   (isset($data['my_area']) && $data['my_area']) ? true : false,
            'Outside_my_area' => (isset($data['outside_my_area']) && $data['outside_my_area']) ? true : false,
            'Remotely'        =>  (isset($data['remotely']) && $data['remotely']) ? true : false,
        ];
        $area_data = [];
        foreach($area as $key => $value) {
            if($value == 'true') {
                $area_data[] = $key;
            }
        }
        $area_data = json_encode($area_data);

        $registration_data = [
            
            'week_days' => $week_data,
            'hours' => $hours_data,
            'working_area' => $area_data
            
        ];
        // if(isset($data['schedule'])) {
        //     $schedule = $data['schedule'];
        //     $registration_data['schedule'] = $schedule;
        // }
        $schedule = (isset($data['full_time']) && $data['full_time']) ? 'full time' : ((isset($data['part_time']) && $data['part_time']) ? 'part time' : ((isset($data['both']) && $data['both']) ? 'both' : ''));
        $registration_data['schedule'] = $schedule;
        return $registration_data;
    }
}
