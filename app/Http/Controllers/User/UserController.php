<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Requests\RegistrationRequest;
use App\Http\Requests\FirstStepUserCreateRequest;
use App\Http\Requests\TablesRequest;
use App\Http\Requests\ContactCreateRequest;
use App\Http\Requests\MyProfileRequest;
use App\Http\Controllers\Controller;
use Dingo\Api\Routing\Helpers;
use App\Contracts\CompanyInterface;
use App\Contracts\CategoryInterface;
use App\Contracts\CountryInterface;
use App\Contracts\SectorInterface;
use App\Contracts\UserInterface;
use App\Contracts\JobInterface;
use App\Contracts\CityInterface;
use App\Contracts\MailInterface;
use App\Contracts\LanguageInterface;
use App\Contracts\ContactInterface;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException; 
use Sentinel;
use Activation;
use User;
use File;
use PDF;
use Auth;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;

class UserController extends Controller
{
    use Helpers;

    /**
     * Object of UserInterface class
     *
     * @var userRepo
     */
    private $userRepo;

    /**
     * Object of CompanyInterface class
     *
     * @var companyRepo
     */
    private $companyRepo;


    /**
     * Object of CategoryInterface class
     *
     * @var categoryRepo
     */
    private $categoryRepo;

    /**
     * Object of CountryInterface class
     *
     * @var countryRepo
     */
    private $countryRepo;

    /**
     * Object of UserInterface class
     *
     * @var userRepo
     */
    private $sectorRepo;

    /**
     * Object of CityInterface class
     *
     * @var cityRepo
     */
    private $cityRepo;

    /**
     * Object of JobInterface class
     *
     * @var jobRepo
     */
    private $jobRepo;

    /**
     * Object of MailInterface class
     *
     * @var mailRepo
     */
    private $mailRepo;

    /**
     * Object of LanguageInterface class
     *
     * @var languageRepo
     */
    private $languageRepo;


    /**
     * Object of ContactInterface class
     *
     * @var languageRepo
     */
    private $contactRepo;

    /** 
     * Create a new instance of Controller class.
     *
     * @param CompanyInterface $companyRepo
     * @return void
     */
	public function __construct(CompanyInterface $companyRepo, CategoryInterface $categoryRepo, CountryInterface $countryRepo, SectorInterface $sectorRepo, CityInterface $cityRepo, JobInterface $jobRepo, UserInterface $userRepo, MailInterface $mailRepo, LanguageInterface $languageRepo, ContactInterface $contactRepo)
	{
        // $this->middleware("cors");
        $this->companyRepo = $companyRepo;
        $this->categoryRepo = $categoryRepo;
		$this->countryRepo = $countryRepo;
        $this->sectorRepo = $sectorRepo;
        $this->cityRepo = $cityRepo;
        $this->jobRepo = $jobRepo;
        $this->userRepo = $userRepo;
        $this->mailRepo = $mailRepo;
        $this->languageRepo = $languageRepo;
        $this->contactRepo = $contactRepo;
	}

    /**
     * get user latitde and longitude
     *
     * @param $request
     * @return array
     */
    public function getUserLatLng(Request $request)
    {
        try {
            //getting user's ip from the request
            $ip = $request->ip();
            //getting countryCode and record from the ip
            $record = geoip_record_by_name($ip);
            $countryCode = strtolower( $record['country_code'] );
            if ($record) {
                //defining default city and country
                $cityDefault = $record['city'];
                $countryDefault = $record['country_name'];
                if(!$cityDefault) {
                    $locationDefault = $countryDefault;
                }else {
                    $locationDefault = $cityDefault.', '.$countryDefault;
                }
                //getting latitude, longitude and contry object
                $latitude = $record['latitude'];
                $longtitude = $record['longitude'];
                $country = $this->countryRepo->getCountryByLocale($countryCode);
                if ($country) {
                    $countryName = $country->name;
                }else {
                    $countryName = "";
                }
            }else {
                //if couldn't get record from geoip, setting some default values
                $latitude = '41.9028';
                $longtitude = '12.4964';
                $countryCode = 'unknown'; 
                $countryName = '';
                $cityDefault = '';
                $countryDefault = '';
                $locationDefault = '';
                $unknown = true;
            }

            $details =  [
                'longtitude' => $longtitude,
                'latitude' => $latitude,
                'countryName' => $countryName,
                'cityName' => $cityDefault,
                'defaultLocation' => $locationDefault,
            ];
            if($countryCode != 'unknown') {
                $details['countryCode'] = $countryCode;
            }
            if(isset($unknown)) {
                $details['unknown'] = true;
            }

            $country_temp = $this->countryRepo->getCountryByLocale($countryCode);
            $details['languageCode'] = $country_temp->language;

            //if location changed from user's location, set all location info
            if (isset($request->lang) && $request->lang !== '') {
               $code = strtoupper($request->lang);
               $localization = $this->countryRepo->getCountryByLocale($code);
               if(!$localization) {
                    $localization = $this->countryRepo->getCountryByLocale('it');
               }
               if($details['countryName'] != $localization->name){
                    $details['defaultLocation'] = $localization->capital.', '.$localization->name;
                    $details['defaultLatitude'] = $localization->latitude;
                    $details['defaultLongitude'] = $localization->longitude;
                    $details['defaultCountryCode'] = $localization->abbreviation;
                    $details['defaultCountryName'] = $localization->name;
                    $details['defaultCityName'] = $localization->capital;
                }else{
                    $details['defaultLatitude'] = $latitude;
                    $details['defaultLongitude'] = $longtitude;
                    $details['defaultCountryCode'] = $countryCode;
                    $details['defaultCountryName'] = $countryName;
                    $details['defaultCityName'] = $cityDefault;
                }
            }
            //if request es sent from angular, return data in json, otherwise return array
            if ($request->ajax()) {
                return response()->json($details);
            }else {
                return $details;    
            }
        } catch (Exception $e) {
            \Log::error($e);
        }      
    }

    /**
     * Return country code for url
     *
     * @Get('/return-country-code')
     */
    public function getReturnCountryCode(Request $request)
    {
        try {
            $details = [];
            //getting user's ip from the request
            $ip = $request->ip();
            //getting countryCode and record from the ip
            $record = geoip_record_by_name($ip);
            if(!$record) {
                $countryCode = 'it';
            }else {
                $countryCode = strtolower( $record['country_code'] );
            }
            $details['countryCode'] = $countryCode;
            
            // if($countryCode != 'unknown') {
            //     $details['countryCode'] = $countryCode;
            // }

            $country_temp = $this->countryRepo->getCountryByLocale($countryCode);
            $details['languageCode'] = $country_temp->language;
            return response()->json($details);
            
        } catch (Exception $e) {
            \Log::error($e);
        }      
    }

    /**
     * Information for home page.
     *
     * @Get("/dashboard")
     */
    public function getDashboard(Request $request)
    {   
        //get locales
        $locales = config('translatable.locales');
        //get information about location
        $details = $this->getUserLatLng($request);
        //if location is choosed, get country by that location. otherwise, get country from user's location
        if($request->lang) {
            $country = $this->countryRepo->getCountryByLocale($request->lang);
        }else {
            $country = $this->countryRepo->getCountryByLocale($details['countryCode']);
        }
        
        //getting top categories, sectors, locations of country, and setting english versions of their names, in case there is no translation for current locale
        if(isset($details['countryCode'])) {
            $data['countryCode'] = $details['countryCode'];
        }else {
            $data['unknown'] = true;
        }
        if($country) {
            $expiresAt = Carbon::now()->addMinutes(60*24*365);
            if(Cache::tags($country->abbreviation)->get('top_categories')) {
                $topCategories = Cache::tags($country->abbreviation)->get('top_categories');
            }else {
                $topCategories = $country->topCategories(8)->get();
                Cache::tags($country->abbreviation)->put('top_categories', $topCategories, $expiresAt);
            }
            
            foreach($topCategories as $category) {
                $category->defaultCategory =  $category->category->getTranslation('en', true);               
            }
            if(Cache::tags($country->abbreviation)->get('home_categories')) {
                $homeCategories = Cache::tags($country->abbreviation)->get('home_categories');
            }else {
                $homeCategories = $country->topCategories(4)->get();
                Cache::tags($country->abbreviation)->put('home_categories', $homeCategories, $expiresAt);
            }
            
            foreach($homeCategories as $hcategory) {
                $hcategory->defaultCategory =  $hcategory->category->getTranslation('en', true);               
            }

            if(Cache::tags($country->abbreviation)->get('top_sectors')) {
                $topSectors = Cache::tags($country->abbreviation)->get('top_sectors');
            }else {
                $topSectors = $country->topSectors;
                Cache::tags($country->abbreviation)->put('top_sectors', $topSectors, $expiresAt);
            }
            
            \Log::info($topSectors);
            $languageCode = \App::getLocale();        
            foreach($topSectors as $sector) {
                if($sector->sector->getTranslation($languageCode)) {
                    if($sector->sector->getTranslation($languageCode)->name == '') {
                        $sector->defaultSector =  $sector->sector->getTranslation('en', true); 
                    }
                }else{
                    $sector->defaultSector =  $sector->sector->getTranslation('en', true); 
                }
            }
            $topCities = $country->topCities;
            $data['categories'] = $topCategories;
            $data['homeCategories'] = $homeCategories;
            $data['sectors'] = $topSectors;
            $data['locations'] = $topCities;   
        }
    	return $this->response->array($data);

    }

    public function getCountryLanguage(Request $request)
    {
        $countryCode = $request->countryCode;
        $country = $this->countryRepo->getCountryByLocale($countryCode);
        if($country) {
            $locale = $country->language;
        }else {
            $locale = $request->countryCode;
        }
        $data = ['language' => $locale];
        return response()->json($data);
    }

    /**
     * Get all languages
     *
     * @Get('/country-languages')
     */
    public function getCountryLanguages(Request $request)
    {
        $languages = $this->languageRepo->getAll();
        return response()->json($languages);
    }

    /**
     * Get all languages
     *
     * @Get('/country-languages')
     */
    public function getCountries(Request $request)
    {
        $countries = $this->countryRepo->getAllCountries();
        return response()->json(['countries' => $countries]);
    }

    /**
     * get the language by location of the user
     */
    public function getUserLanguage(Request $request)
    {
        // $ip = $request->ip();
        // $record = geoip_record_by_name($ip);
        // $countryCode = strtolower( $record['country_code'] );
        $languageCode = \App::getLocale();
        $languageObject = $this->languageRepo->getLanguageByCode(strtolower($languageCode));
        if(!$languageObject) {
            $languageObject = $this->languageRepo->getLanguageByCode('it');
        }
        $language = $languageObject->language;
        $native = $languageObject->native;
        $data = ['language' => $language, 'languageCode' => $languageCode, 'native' => $native];
        return response()->json($data);
    }

    /**
     * Checking if user with given email exists
     *
     * @Get('/check-email')
     */
    public function getCheckEMail(Request $request)
    {
        $email = $request->email;
        $user = $this->userRepo->getUserByEmail($email);
        //if user exists and activated, return error
        if($user && Activation::completed($user)) {
            return response()->json(['error' => 'user exists']);
        }else{
            return response()->json(['success' => 1]);
        }
    }

    /**
     * Get information for my profile page
     *
     * @Get('/my-profile')
     */
    public function getMyProfile(Request $request)
    {
        //get the authenticated user
        $user = \JWTAuth::parseToken()->authenticate();
        $countries = $this->countryRepo->getAllCountries();
        if ($user) {
            //get user's skills and set english version of their names in case there is no translation for current language
            $user['skills'] = $user->skills;
            foreach ($user['skills'] as $category) {
                $category->defaultCategory =  $category->getTranslation('en', true);               
            }
            //get user's applications
            $user['applications'] = $user->applications;
            if ($user->applications) {
                foreach($user['applications'] as $job) {
                    $job['company'] == $job->company;
                }
            }
            //get user's subscribtions
            $subscribtions = $user->subscribtions;
            $locale = \App::getLocale();
            foreach ($subscribtions as $subscribtion) {
                if($subscribtion->category){
                    $category = $subscribtion->category;
                    if($category->getTranslation('en', true)->name){
                        $subscribtion->categoryName = $category->getTranslation($locale, true)->name;
                    }else{
                        $subscribtion->categoryName = $category->getTranslation('en', true)->name;
                    }
                }else{
                    $subscribtion->categoryName = 'N/A';
                }

                if($subscribtion->sector){
                    $sector = $subscribtion->sector;
                    if($sector->getTranslation('en', true)->name){
                        $subscribtion->sectorName = $sector->getTranslation($locale, true)->name;
                    }else{
                        $subscribtion->sectorName = $sector->getTranslation('en', true)->name;
                    }
                }else{
                    $subscribtion->sectorName = 'N/A';
                }
            }
            if($user->birth_date) {
                $birth_date = Carbon::createFromFormat('m/d/Y',$user->birth_date);
                $age = $birth_date->diff(Carbon::now())->y;
                $user['age'] = $age;
            }
            
            $user['subscribtions'] = $subscribtions;
            //get the user
            $data = [
                'user' => $user,
                'countries' => $countries
            ];
            //if user is attached to languages, get the array of languages
            if ($user->languages) {
                $languages_array = [];
                foreach($user->languages as $language) {
                    $languages_array[] = $language->native;
                }
                $data['languages'] = $languages_array;
            }
            return response()->json(['data' => $data]);
        }
    }

    

    /**
     * Get phone code of given country code
     *
     * @Get('/phone-code')
     */
    public function getPhoneCode(Request $request)
    {
        //get county code from request
        $countryCode = $request->country_code;
        //get phone code of the country
        $countries = File::getRequire('phone_codes/countries.php');
        foreach($countries as $country) {
            if ($country['code'] == $countryCode) {
                $phone_code = $country['d_code'];
            }
        }
        $data = ['phone_code' => $phone_code];
        return response()->json($data);
    }

    /**
     * Get phone code of given country name
     *
     * @Get('/phone-code')
     */
    public function getPhoneCodeByName(Request $request)
    {
        //get country name from request
        $countryName = $request->country_name;
        //get phone code of the country
        $countries = File::getRequire('phone_codes/countries.php');
        foreach($countries as $country) {
            if (strtolower($country['name']) == strtolower($countryName)) {
                $phone_code = $country['d_code'];
            }
        }
        $data = ['phone_code' => $phone_code];
        return response()->json($data);
    }

    /**
     * Editing user's profile from first tab of my profile page
     *
     * @Post('/edit-my-profile-first')
     */
    public function postEditMyProfileFirstTab(MyProfileRequest $request)
    {
        //get registration data
        $editProfileData = $request->inputsFirstTab($request);
        //get json decoded data
        $data = $request->data($request);
        //get the authenticated user
        $user = \JWTAuth::parseToken()->authenticate();   
        //validate data     
        $validator = \Validator::make($editProfileData, [
            'first_name' => 'required|max:255|regex:/^[\pL\s\-]+$/u',
            'last_name' => 'required|max:255|regex:/^[\pL\s\-]+$/u',
            'country' => 'required',
            'city' => 'required',
            'phone_number' => 'required|numeric',
            'birth_date' => 'required'
        ]);
        //if validation fails, return error
        if ($validator->fails()) {
            $responseData = ['errors' => $validator->errors()];
            return response()->json($responseData);
        }
        //image upload
        // $file = $request['image'];
        // if($file) {
        //     $destinationPath = public_path().'/uploads';
        //     $extension = $file->getClientOriginalExtension();
        //     $fileName = str_random(8).'.'.$extension;
        //     $file->move($destinationPath, $fileName);
        //     $editProfileData['image'] = $fileName;
        //     $editProfileData['last_uploaded'] = Carbon::now();
        // }
        $cropped = $request['cropped'];
        $file = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $cropped));
        if($file) {
            $destinationPath = public_path().'/uploads';
            $extension = 'jpg';
            $fileName = str_random(8).'.'.$extension;
            //$file->move($destinationPath, $fileName);
            file_put_contents($destinationPath.'/'.$fileName, $file);
            $editProfileData['image'] = $fileName;
            $editProfileData['last_uploaded'] = Carbon::now();
        }
        //if user stopped registration process on the first step, set the step ecual to 2      
        if($user->step == 1) {
            $editProfileData['step'] = 2;
        }
        //username of user
        $username = strtolower($user->first_name).'-'.strtolower($user->last_name).'-'.$user->id;
        $editProfileData['username'] = $username;
        //updating user
        $user = Sentinel::update($user, $editProfileData);
        //detaching user from all languages and attach to selected and old ones
        $user->languages()->detach();
        if(isset($data['speakLang']) && $data['speakLang'] == true) {
            $language = $this->languageRepo->getLanguageByNative($data['doYouSpeakLang']);
            $user->languages()->attach($language->id);
        }
        if(isset($data['spokenLanguages'])) {
            $languages = $data['spokenLanguages'];
            foreach($languages as $language) {
                $language = $this->languageRepo->getLanguageByNative($language);
                $user->languages()->attach($language->id);
            }
        }        
        return $this->response->array($user);
    }

    /**
     * Editing user's profile from second tab of my profile page
     *
     * @Post('/edit-my-profile-second')
     */
    public function postEditMyProfileSecondTab(MyProfileRequest $request)
    {
        //get json decoded data
        $data = $request->data($request);
        //get the autenticated user
        $user = \JWTAuth::parseToken()->authenticate();
        //detach user form all skills and attach to selected and old ones
        $user->skills()->detach();
        foreach($data['selectedList'] as $category) {
            $user->skills()->attach($category['id']);
        }

        if($user->step == 2) {
            $updateData = ['step' => 3];
        }
        $validator = \Validator::make($data, [
            'user_experience' => 'max:2000'
        ]);
        if ($validator->fails()) {
            $responseData = ['errors' => $validator->errors()];
            return response()->json($responseData);
        }
        if(isset($data['user_experience'])) {
            $updateData['user_experience'] = $data['user_experience'];
        }

        if(isset($data['facebook_link'])) {
            $updateData['facebook_link'] = $data['facebook_link'];
        }
        //image upload
        // $file = $request['image'];
        // if($file) {
        //     $destinationPath = public_path().'/uploads';
        //     $extension = $file->getClientOriginalExtension();
        //     $fileName = str_random(8).'.'.$extension;
        //     $file->move($destinationPath, $fileName);
        //     $data['image'] = $fileName;
        //     $data['last_uploaded'] = Carbon::now();
        // }
        $file = $request['cropped'];
        $file = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $file));
        if($file) {
            $destinationPath = public_path().'/uploads';
            $extension = 'jpg';
            $fileName = str_random(8).'.'.$extension;
            //$file->move($destinationPath, $fileName);
            file_put_contents($destinationPath.'/'.$fileName, $file);
            $updateData['image'] = $fileName;
            $updateData['last_uploaded'] = Carbon::now();
        }
        //update user
        $this->userRepo->editUser($user, $updateData);
        return $this->response->array($user);
    }

    /**
     * Editing user's profile from third tab of my profile page
     *
     * @Post('/edit-my-profile-third')
     */
    public function postEditMyProfileThirdTab(MyProfileRequest $request)
    {
        //get registration data
        $editProfileData = $request->inputsThirdTab($request);
        //image upload
        // $file = $request['image'];
        // if($file) {
        //     $destinationPath = public_path().'/uploads';
        //     $extension = $file->getClientOriginalExtension();
        //     $fileName = str_random(8).'.'.$extension;
        //     $file->move($destinationPath, $fileName);
        //     $editProfileData['image'] = $fileName;
        //     $editProfileData['last_uploaded'] = Carbon::now();
        // }
        $data = $request['cropped'];
        $file = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $data));
        if($file) {
            $destinationPath = public_path().'/uploads';
            $extension = 'jpg';
            $fileName = str_random(8).'.'.$extension;
            //$file->move($destinationPath, $fileName);
            file_put_contents($destinationPath.'/'.$fileName, $file);
            $editProfileData['image'] = $fileName;
            $editProfileData['last_uploaded'] = Carbon::now();
        }
        //get the autenticated user
        $user = \JWTAuth::parseToken()->authenticate();
        //if user left registration process on 3rd step, set the step equal to 4
        if($user->step == 3) {
            $editProfileData['step'] = 4;
        }
        //updating the user
        $user = Sentinel::update($user, $editProfileData);
        return $this->response->array($user);
    }

    /**
     * Get all categories
     *
     * @Get('/categories')
     */
    public function getCategories(Request $request)
    {
        //get all categories with default english names
        $categories = $this->categoryRepo->getAllCategories();
        foreach($categories as $category) {
            $category->defaultCategory =  $category->getTranslation('en', true);               
        }
        return response()->json($categories);
    }

    /**
     * Get user details
     *
     * @Get('/user-details')
     */
    public function getUserDetails(Request $request)
    {
        //get the autenticated user
        $user = \JWTAuth::parseToken()->authenticate();
        return response()->json($user);
    }

    /** 
     * Get user details
     *
      * @Get('/user-details')
    */
    public function getUserDetailsRegister(Request $request)
    {
        $user_id = $request->id;
        $user = $this->userRepo->getUserById($user_id);
        return response()->json($user);
    }

    /**
     * download profile details in pdf
     *
     * @Get('/download-details')
     */
    public function getDownloadDetails($token)
    {
        $userToken = $this->userRepo->getUserByDownloaToken($token);
        if($userToken){
            $user = $this->userRepo->getUserById($userToken->user_id);
            $workingAreas = json_decode($user->working_area);
            $area = '';
            if(count($workingAreas) > 0){
                foreach($workingAreas as $workingArea){
                    $area .= '/'.str_replace("_"," ",$workingArea);
                }
            }
            
            $transportaions = json_decode($user->transport);
            $transport = '';
            if(count($transportaions) > 0){
                foreach($transportaions as $trans){
                    $transport .= '/'.str_replace("_"," ",$trans);
                }
            }
             
            $drivingLicense = '';
            if($user->driving_license == true){
                $drivingLicense = 'Yes';
            }elseif($user->driving_license == false){
                $drivingLicense = 'No';
            }

            $student = '';
            if($user->currently_student == true){
                $student = 'Yes';
            }elseif($user->currently_student == false){
                $student = 'No';
            }
            $data = [
                'firstName' => $user->first_name,
                'lastName' => $user->last_name,
                'email' => $user->email, 
                'birthDate' => $user->birth_date,
                'phoneNumber' => $user->phone_number,
                'location' => $user->location,
                'workingArea' => substr($area, 1),
                'schedule' => ucfirst($user->schedule),
                'transports' => json_decode($user->transport),
                'educations' => json_decode($user->education),
                'skills' => $user->skills,
                'languages' => $user->languages,
                'image' => $user->image,
                'drivingLicense' => $drivingLicense,
                'student' => $student
                ];
        $pdf = PDF::loadView('pdf.profile_details', $data);
        return $pdf->download($user->first_name.$user->last_name.'-CV.pdf');
        }else{
            $data = [
                'status' => 'failed'

                ];
            return response()->json($data);
        }
        
    }

     /**
     * generate or update user download token 
     *
     * @Get('/generate-token')
     */
    public function getGenerateToken()
    {
        $authUser = \JWTAuth::parseToken()->authenticate();
        $userId = $authUser->id;
        $token = str_random(40);
        $user = $this->userRepo->generateToken($userId, $token);
        $data = ['token' => $token];
        return response()->json($data);
    }

    /**
     * refresh jwt token
     *
     * @Get('/token-refresh')
     */
    public function refreshToken(Request $request)
    {
        $token = JWTAuth::getToken();
        $newToken = JWTAuth::refresh($token);
        $data = ['new_token' => $newToken];
        return response()->json($data);
    }

     /**
     * contact to admin:senc details to admin
     *
     * @Post('/contact-us)
     */
     public function postContactUs(Request $request)
     {
        $data = $request->data;
        $validator = \Validator::make($data, [
            'company_name' => 'required|max:256',
            'email' => 'required|email|max:256',
            'message' => 'required',
            'name' => 'required|max:256',
            'surname' => 'required|max:256',
            'privacy' => 'required'
        ]);

        if ($validator->fails()) {
            $responseData = ['errors' => $validator->errors()];
            return response()->json($responseData);
        }else{
            $data['seen'] = false;
            $this->contactRepo->createContact($data);
            $email = 'contact@sharado.com';
            $subject = 'Contact Us';
            $mailTemplate = "contact_us";
            $data['mailMessage'] = $data['message'];
            try {
                $this->mailRepo->send_email($email, $data, $mailTemplate, $subject);
            } catch (\Exception $e) {
                dd($e);
                \Log::error($e);
                return response()->json(['error' => 'Email sending failed']);
            } 
            return response()->json();
        }
        
     }

     /** *
     */
    public function getAllCategories(Request $request)
    {
       // $locale = $request->lang;
       // \App::setLocale($locale);
       // $categories = $this->categoryRepo->getOrderedCategoriesForUser('en');
       // // foreach($categories as $category) {
       // //     if($category->getTranslation($locale, true)) {
       // //          if($category->name == '') {
       // //              $category->name =  $category->getTranslation($locale, true)->name; 
       // //          }
       // //      }

       // //  }

       // $data = ['categories' => $categories];
       
       // return response()->json($data);

        $categories = $this->categoryRepo->getAllCategories();
        foreach($categories as $category) {
            $category->defaultCategory = $category->getTranslation('en', true);               
        }
        return response()->json($categories);
    }

    public function getPublicProfile(Request $request)
    {
        $user = $this->userRepo->getUserByUsername($request->username);
        if($user) {
            if(isset($user->birth_date)) {
                $birth_date = Carbon::createFromFormat('m/d/Y',$user->birth_date);
                $age = $birth_date->diff(Carbon::now())->y;
                $user->age = $age;
            }
            if(isset($user->week_days)) {
                $week_days = json_decode($user->week_days);
            }
            if(isset($user->hours)) {
                $hours = json_decode($user->hours);
            }
            if(isset($user->working_area)) {
                $area = json_decode($user->working_area);
            }
            if($user->skills){
                foreach ($user->skills as $skill) {
                   $skill->defaultSkill =  $skill->getTranslation('en', true);
                }
            }
            $data = [
                'user' => $user,
                'week_days' => isset($week_days) ? $week_days : null,
                'hours' => isset($hours) ? $hours : null,
                'area' => isset($area) ? $area : null
            ];
            return response()->json($data);
        }else{
            return response()->json(['error' => 'user does not exist']);
        }
    } 
}
