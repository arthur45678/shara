<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Requests\RegistrationRequest;
use App\Http\Requests\FirstStepUserCreateRequest;
use App\Http\Requests\TablesRequest;
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
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException; 
use Sentinel;
use Activation;
use App\Activation as userActivation;
use User;
use File;
use Carbon\Carbon;

class RegistrationController extends Controller
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
     * Create a new instance of Controller class.
     *
     * @param CompanyInterface $companyRepo
     * @return void
     */
	public function __construct(CompanyInterface $companyRepo, CategoryInterface $categoryRepo, CountryInterface $countryRepo, SectorInterface $sectorRepo, CityInterface $cityRepo, JobInterface $jobRepo, UserInterface $userRepo, MailInterface $mailRepo, LanguageInterface $languageRepo)
	{
        $this->companyRepo = $companyRepo;
        $this->categoryRepo = $categoryRepo;
		$this->countryRepo = $countryRepo;
        $this->sectorRepo = $sectorRepo;
        $this->cityRepo = $cityRepo;
        $this->jobRepo = $jobRepo;
        $this->userRepo = $userRepo;
        $this->mailRepo = $mailRepo;
        $this->languageRepo = $languageRepo;
	}

    /**
     * get user latitde and longitude
     *
     * @param $request
     * @return array
     */
    private function getUserLatLng($ip)
    {
        //getting countryCode and record from user's ip
        $record = geoip_record_by_name($ip);
        $countryCode = strtolower( $record['country_code'] );
        if ($record) {
            //getting user's latitude and longitude grom record
            $latitude = $record['latitude'];
            $longtitude = $record['longitude'];
            //getting user's country
            $country = $this->countryRepo->getCountryByLocale($countryCode);
            if($country){
                $countryName = $country->name;
            }else{
                $countryName = "";
            }  
        } else {
            $latitude = '';
            $longtitude = '';
            $countryCode = 'fr'; 
            $countryName = '';
        }

        $details =  [
            'longtitude' => $longtitude,
            'latitude' => $latitude,
            'countryCode' => $countryCode,
            'countryName' => $countryName
        ];
        return $details;    
    }

	/**
     * Creating a user and sending activation email on first step of registration.
     *
     * @Post('/registration-first-step')
     */
    public function postRegistrationFirstStep(FirstStepUserCreateRequest $request)
    {
        //get user data from request
        $data = $request['user'];
        $data = json_decode($data, true);
        //validate the data
        $validator = \Validator::make($data, [
            'first_name' => 'required|max:255|regex:/^[\pL\s\-]+$/u',
            'last_name' => 'required|max:255|regex:/^[\pL\s\-]+$/u',
            'email' => 'required|email|max:255',
            'password' => 'required_without:registeredByFacebook|min:6|alpha_dash',
        ]);
        //if validation fails, return response with error messages
        if ($validator->fails()) {
            $responseData = ['errors' => $validator->errors()];
            return response()->json($responseData);
        }       
        $registration_data = [
            'email' => $data['email'],
            'activation' => 'activated',
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'step' => 1,
            'role' => 'from_registration',
            'retsrict' => 'false',
            'last_uploaded' => Carbon::now(),
            'ip' => $request->ip(),
            'last_login' => Carbon::now()
        ];
        //if user is registered by facebook, generate a password
        if (isset($data['password'])) {
            $registration_data['password'] = $data['password'];
        }else {
            $registration_data['password'] = str_random(10);
        }
        //if user is registered by facebook, get user's profile image from facebook account
        if (isset($data['facebook_id'])) {
            if($data['image']) {
                $image_url = "http://graph.facebook.com/" . $data['facebook_id'] . "/picture?type=large";
                $extension = pathinfo($image_url, PATHINFO_EXTENSION);
                $fileNameUrl = str_random(8).'.jpg';
                $file = file_get_contents($image_url);
                $save = file_put_contents('uploads/'.$fileNameUrl, $file);
                $registration_data['image'] = $fileNameUrl;
            }
            $registration_data['facebook_id'] = $data['facebook_id'];
        }
        //if user is already registered but not activated, the user is being deleted
        $user = $this->userRepo->getUserByEmail($data['email']);
        if ($user && !Activation::completed($user)) {
            $user->delete();
        } else if ($user && Activation::completed($user)) {
            return response()->json(['error' => 'user exists']);
        }
        //if user is registered with facebook, register and activate him, otherwise register and send activation email
        if (isset($data['registeredByFacebook']) && $data['registeredByFacebook'] == true) {
            $user = Sentinel::registerAndActivate($registration_data);
        } else {
            $registerTime = time();
            $registration_data['registration_time'] = $registerTime;
            $user = Sentinel::register($registration_data);
            $activation = Activation::create($user);
            if (!$activation) {
                return response()->json(['error' => 'user exists']);
            }
            $email = $data['email'];
            $subject = "Account Activation";
            $mailTemplate = "registration";
            $maildata = ['username' => $data['first_name'], 'token' => $activation->code];
            if($request->lang) {
                $maildata['lang'] = $request->lang;
            }else {
                $lang = '';
            }
            try {
                $this->mailRepo->send_email($data['email'], $maildata, $mailTemplate, $subject);
            } catch (\Exception $e) {
                \Log::error($e);
                return response()->json(['error' => 'Email sending failed']);
            } 
        }
        $username = strtolower($user->first_name).'-'.strtolower($user->last_name).'-'.$user->id;
        $user->update(['username' => $username]);
        return response()->json(['user' => $user]);
    }

    /**
     * Second, third and fourth steps of registration.
     *
     * @Post('/registration')
     */
    public function postRegistration(RegistrationRequest $request)
    {
        $data = $request->data($request);
        //get user that was created on first step
        $user = $this->userRepo->getUserByEmail($data['email']);
        if ($data['step'] == 2) {
            $registrationData = $request->inputsSecondStep($request);
            //detach user from all languages and attach to selected and old ones
            if (isset($data['spokenLanguages'])) {
                $languages = $data['spokenLanguages'];
                $user->languages()->detach();
                foreach($languages as $language) {
                    // $language = $this->languageRepo->getLanguageByName($language);
                    $language = $this->languageRepo->getLanguageByNative($language);
                    $user->languages()->attach($language->id);
                }
            }
            if (isset($data['speakLang']) && $data['speakLang'] == true) {
                //$language = $this->languageRepo->getLanguageByName($data['doYouSpeakLang']);
                $language = $this->languageRepo->getLanguageByNative($data['doYouSpeakLang']);
                $user->languages()->attach($language->id);
            }
            //validate registration data
            $validator = \Validator::make($registrationData, [
                'first_name' => 'required|max:255|regex:/^[\pL\s\-]+$/u',
                'last_name' => 'required|max:255|regex:/^[\pL\s\-]+$/u',
                'country' => 'required',
                'city' => 'required',
                'phone_number' => 'required|numeric',
                'education' => 'required',
                'birth_date' => 'required'
            ]);
            //if validation falis, send error
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
            //     $registrationData['image'] = $fileName;
            // }
            $data = $request['cropped'];
            $file = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $data));
            if($file) {
                $destinationPath = public_path().'/uploads';
                $extension = 'jpg';
                $fileName = str_random(8).'.'.$extension;
                //$file->move($destinationPath, $fileName);
                file_put_contents($destinationPath.'/'.$fileName, $file);
                $registrationData['image'] = $fileName;
            }
            $username = strtolower($user->first_name).'-'.strtolower($user->last_name).'-'.$user->id;
            $registrationData['username'] = $username;
            //update and save user
            $user->update($registrationData);
            $user->save();
            $user->load('skills');
        } elseif ($data['step'] == 3) {
            $validator = \Validator::make($data, [
                'user_experience' => 'max:2000'
            ]);
            if ($validator->fails()) {
                $responseData = ['errors' => $validator->errors()];
                return response()->json($responseData);
            }
            //attach user to selected skills
            foreach($data['selectedList'] as $category) {
                $user->skills()->attach($category['id']);
            }
            $registrationData = ['step' => 3];
            if(isset($data['user_experience'])) {
                $registrationData['user_experience'] = $data['user_experience'];
            }
            if(isset($data['facebook_link'])) {
                $registrationData['facebook_link'] = $data['facebook_link'];
            }
            //update and save user
            $user->update($registrationData);           
            $user->save();
            $user->load('skills');
        } elseif ($data['step'] == 4) {
            $registrationData = $request->inputsFourthStep($request);
            //update and save user
            $user->update($registrationData);
            $user->save();
            $user->load('skills');
        }       
        return $this->response->array($user);
    }

    /**
     * Login the user
     *
     * @Post('/login')
     */
    public function postLogin(Request $request) 
    {
        //get credentials from request and user by email
        $credentials = $request->only('email', 'password');
        $user = $this->userRepo->getUserByEmail($request->email);
        if($user) {
            $user = Sentinel::findByCredentials($credentials);
            //if user is restricted send an error
            if($user->restrict == 'true'){
                return response()->json(['error' => 'suspended', 'user_id' => $user->id]);
            }
            //if user is not activated send an error
            if(!Activation::completed($user)) {
                return response()->json(['error' => 'not_activated_user', 'user_id' => $user->id]);
            }
        }
        try {
            // attempt to verify the credentials and create a token for the user
            if ((! $token = JWTAuth::attempt($credentials)) || ($user->role == 'from_admin')) {
                return response()->json(['error' => 'invalid_credentials']);
            } else {
                $user = $this->userRepo->getUserByEmail($request->email);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token']);
        }
        $data = ['token' => compact('token'), 'user' => $user
        ];
        $updateData = ['last_login' => Carbon::now()];
        $this->userRepo->editUser($user, $updateData);
        // all good so return the token 
        return response()->json($data);
    }

    /**
     * Login via facebook
     *
     * @Post('/facebook-login')
     */
    public function postFacebookLogin(Request $request)
    {
        //get user by facebook id
        $user = $this->userRepo->getUserByFacebookId($request->facebook_id);
        if($user)
        {
            //if user is restricted send an error
            if($user->restrict == 'true'){
                return response()->json(['error' => 'fbsuspended', 'user_id' => $user->id]);
            }
            $credentials = ['email' => $request->email, 'facebook_id' => $request->facebook_id];
                try {
                // attempt to verify the credentials and create a token for the user
                if (! $token = JWTAuth::fromUser($user)) {
                    return response()->json(['error' => 'invalid_credentials'], 401);
                } else {
                    $user = $this->userRepo->getUserByEmail($request->email);
                }
            } catch (JWTException $e) {
                // something went wrong whilst attempting to encode the token
                return response()->json(['error' => 'could_not_create_token'], 500);
            }
            $data = ['token' => compact('token'), 'user' => $user];

        }else{
            return response()->json(['error' => 'can not login user']);
        }
        $updateData = ['last_login' => Carbon::now()];
        $this->userRepo->editUser($user, $updateData);
        return response()->json($data);
    }

    /**
     * Logout
     *
     * @Post('/logout')
     */
    public function postLogout(Request $request)
    {
        try{
            $token = JWTAuth::getToken();
            JWTAuth::invalidate($token);
            return response()->json(['success' => 1]);
        } catch(\Exception $e) {
            $response = [
             'error_text' => 'The user is not logged in',
             'error_status' => -1000
          ];
            return response()->json($response);
        }
        
    }

    /**
     * Activate the user
     *
     * @param string $token
     */
    public function getActivateUser(Request $request, $token, $lang)
    {
        \App::setLocale($lang);
        //if user is already activated, redirect to login page
        $activation = userActivation::where('code', $token)->where('completed', 0)->first();
        if (!$activation) 
            return redirect(url('/').'/login');
        //getting user
        $user = $this->userRepo->getUserById($activation->user_id);
        //defining localization
        if ($user->country) {
            $country = $this->countryRepo->getCountryByName($user->country);
            if($country) {
                $localization = strtolower($country->abbreviation);
            }else {
                $details = $this->getUserLatLng($request->ip());
                $localization = $details['countryCode'];
            }
            
        } else {
            $details = $this->getUserLatLng($request->ip());
            $localization = $details['countryCode'];
        }
        if ($user) {
            $currentTime = time();
            $registerTime = $user->registration_time;
            $difference = $currentTime - $registerTime;
            //check if activation link is expired
            if ($difference > 86400) {

                return redirect(url('/').'/'.$localization.'/account-activation/expired/'.$token);
            } else {
                Activation::complete($user, $activation->code);
                $email = $user->email;
                $subject = "Welcome To Sharado";
                $mailTemplate = "welcome";
                $maildata = ['user_id' => $user->id];

                try {
                    $this->mailRepo->send_email($email, $maildata, $mailTemplate, $subject);
                } catch (\Exception $e) {
                    \Log::error($e);
                    return response()->json(['error' => 'Email sending failed']);
                } 
                return redirect(url('/').'/'.$localization.'/login?activated=1');
            }
        } else {
            return redirect(url('/'));
        }
    }

    /**
     * Send reset password email
     * Show message if link is expired
     *
     * @Get('/reset-password')
     */
    public function postResetPassword(Request $request) 
    {
        \App::setLocale($request->lang);
        //set password reset token for the user
        $email = $request->email;
        $user = $this->userRepo->getUserByEmail($email);
        //defining localization
        if ($user->country) {
            $country = $this->countryRepo->getCountryByName($user->country);
            $localization = strtolower($country->abbreviation);
        } else {
            $details = $this->getUserLatLng($request->ip());
            $localization = $details['countryCode'];
        }
        //generate reset token
        $reset_token = str_random(30);
        $data = ['password_reset_token' => $reset_token];
        if ($user) {
            $this->userRepo->editUser($user, $data);
            // $currentTime = time();
            // $registerTime = $user->registration_time;
            // $difference = $currentTime - $registerTime;
            //check if account confirmation link is expired
            // if ($difference > 86400) {            
            //     return redirect(url('/').'/#!/'.$localization.'/account-activation/expired/');
            // } else {
                //if everything is good, send password reset email
                $userName = $user->first_name;
                $subject = "Reset Password";
                $mailTemplate = "reset-password";
                $maildata = ['email' => $email, 'name' => $userName, 'token' => $reset_token, 'localization' => $localization ];
                $currentTime = time();
                $this->userRepo->editUser($user, ['registration_time' => $currentTime]);
                \Log::info(\App::getLocale());
                $this->mailRepo->send_email($email, $maildata, $mailTemplate, $subject);
                       
        } else {
            return response()->json(['success' => 0]);
        }
    }

    /**
     * Get change password screen if url is valid.
     * Show message if link is expired.
     *
     * @Get("/reset-password/{email}/{token}")
     */
    public function getResetPass($email, $token)
    {
        $user = $this->userRepo->getUserByEmailAndToken($email, $token);
        if ($user) {
            $currentTime = time();
            $registerTime = $user->registration_time;
            $difference = $currentTime - $registerTime;
            //check if password reset link is expired
            if ($difference > 86400) {
                return response()->json(['success' => '0', 'response' => 'reset-password-expired']);
            } else {
                //if everything is good return reset password screen
                return response()->json(['success' => '1', 'response' => 'reset-password']);
            }
        } else {
            return response()->json(['success' => '0']);
        }
    }

    /**
     * Changing user's password
     *
     * @Get('/change-pass')
     */
    public function postChangePass(Request $request)
    {
        $email = $request->email;
        $password = $request->password;
        $reset_token = $request->token;
        $user = $this->userRepo->getUserByEmail($email);
        $user_password_token = $user->password_reset_token;
        \Log::info($user_password_token);
        \Log::info($user);
        if($user_password_token == $reset_token) {
            //if user's password reset token matches the given token, the user is being edited
            $data = ['password' => bcrypt($password), 'password_reset_token' => ''];
            $x = $this->userRepo->editUser($user, $data);
            \Log::info($x);
        }else{
            return response()->json(['success' => 0]);
        }   
    }

    /**
     * Resending activation email
     *
     * @Get('/resend-activation-email')
     */
    public function getResendActivationMail(Request $request)
    {
        $user = $this->userRepo->getUserById($request->user_id);
        if (!Activation::completed($user)) {
            //generate activation token for user
            $registration_data['verify_token'] = str_random(30);
            userActivation::where('user_id', $user->id)->update(['code' => $registration_data['verify_token'], 'completed' => 0]);
            $registerTime = time();
            $email = $user->email;
            $subject = "Account Activation";
            $mailTemplate = "registration";
            $maildata = ['username' => $user->first_name, 'token' => $registration_data['verify_token'] ];
            if($request->lang) {
                $maildata['lang'] = $request->lang;
            }else {
                $lang = '';
            }
            try {
                $this->mailRepo->send_email($user->email, $maildata, $mailTemplate, $subject);
            } catch (\Exception $e) {
                return response()->json(['resend_status' => 'Email sending failed']);
            }
            return response()->json(['resend_status' => 'success']);
        }else {
            return response()->json(['resend_status' => 'user_is_already_active']);
        }
    }

    /**
     * sending user to log in page with congratulation message
     */
    public function getWelcome(Request $request, $user_id)
    {
        $user = $this->userRepo->getUserById($user_id);
        if ($user->country) {
            $country = $this->countryRepo->getCountryByName($user->country);
            $localization = strtolower($country->abbreviation);
        } else {
            $details = $this->getUserLatLng($request->ip());
            $localization = $details['countryCode'];
        }
        if ($user) {
            
                return redirect(url('/').'/'.$localization.'/login');
            
        } else {
            return redirect(url('/'));
        }

    }
}
