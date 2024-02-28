<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Contracts\UserInterface;
use App\Contracts\RoleInterface;
use App\Contracts\SubscribtionInterface;
use App\Contracts\CompanyInterface;
use App\Contracts\JobInterface;
use App\Contracts\LanguageInterface;
use App\Http\Requests\UserCreateRequest;
use Sentinel;
use Validator;
use Charts;
use Exception;

class AdminController extends Controller
{
	/**
     * Object of UserInterface class
     *
     * @var userRepo
     */
    private $userRepo;

    /**
     * Object of RoleInterface class
     *
     * @var roleRepo
     */
    private $roleRepo;

    /**
     * Object of SubscribtionInterface class
     *
     * @var subscribtionRepo
     */
    private $subscribtionRepo;

    /**
     * Object of CompanyInterface class
     *
     * @var companyRepo
     */
    private $companyRepo;

    /**
     * Object of JobInterface class
     *
     * @var jobRepo
     */
    private $jobRepo;

    /**
     * Object of LanguageInterface class
     *
     * @var languageRepo
     */
    private $languageRepo;

    /** 
     * Create a new instance of Controller class.
     *
     * @param UserInterface $userRepo
     * @return void
     */
	public function __construct(UserInterface $userRepo, RoleInterface $roleRepo, SubscribtionInterface $subscribtionRepo, CompanyInterface $companyRepo, JobInterface $jobRepo, LanguageInterface $languageRepo)
	{
		$this->userRepo = $userRepo;
		$this->roleRepo = $roleRepo;
        $this->subscribtionRepo = $subscribtionRepo;
        $this->companyRepo = $companyRepo;
        $this->jobRepo = $jobRepo;
        $this->languageRepo = $languageRepo;
        $this->middleware("admin", ['except' => [
                                        'getLogin', 
                                        'postLogin',
                                        'getChangeAdminInfo'
                                    ]]);

	}

	/**
	 * get admin login page
     * GET /admin/login
	 *
	 * @return view
	 */
    public function getLogin()
    {
    	$user = Sentinel::getUser();
        //check if there is a logged user and logged user role is admin
    	if($user && ($user->role == 'from_admin')){
            //if the user is admin or super admin, return dashboard
    		return redirect()->action('Admin\AdminController@getDashboard');
    	}else{
            //else, return back to login page
    		return view('admin.login');
    	}
    	
    }

    /**
     * get admin dashboard
     * GET /admin/dashboard
     * 
     * @return view
     */
    public function getDashboard()
    {
    	$user = Sentinel::getUser();
        $userCount = $this->userRepo->getRegisteredUsers()->total();
        $userCountToday = $this->userRepo->getUsersTodayCount();
        $jobsCount = count($this->jobRepo->getAllJobs());
        $jobsCountToday = $this->jobRepo->getAllJobsTodayCount();
        $companiesCount = count($this->companyRepo->getAllGenerics());
        $companiesCountToday = $this->companyRepo->getAllGerenicsTodayCount();
        $alertsCount = count($this->subscribtionRepo->getSubscribtions());
        $alertsCountToday = $this->subscribtionRepo->getSubscriptionsTodayCount();
        $applicationsCount = $this->userRepo->applicationsCount();
        $applicationsCount = $applicationsCount[0]->count;
        $applicationsCountToday = $this->userRepo->applicationsCountToday();
        $applicationsCountToday = $applicationsCountToday[0]->count;
        $users = $this->userRepo->getUsers();
        $chart = Charts::database($users, 'bar', 'fusioncharts')
          ->title('Users Registered')
          ->elementLabel("Total")
          ->dimensions(0, 400)
          ->responsive(false)
          ->groupByDay();
        $data = [
                'usersCount' => $userCount,
                'jobsCount' => $jobsCount,
                'companiesCount' => $companiesCount,
                'alertsCount' => $alertsCount,
                'applicationsCount' => $applicationsCount,
                'chart' => $chart,
                'usersCountToday' => $userCountToday,
                'jobsCountToday' => $jobsCountToday,
                'companiesCountToday' => $companiesCountToday,
                'alertsCountToday' => $alertsCountToday,
                'applicationsCountToday' => $applicationsCountToday
                ];
       	return view('admin.dashboard', $data);
    }

    /**
     * login admin
     * POST /admin/login
     *
     * @param  Request $request
     * @return view
     */
    public function postLogin(Request $request)
    {
        //get user email and password
    	$email = $request->email;
    	$password = $request->password;
    	$credentials = [
            		'email' => $email,
            		'password' => $password
            	   ];
        //get user with credentials        
    	$user = Sentinel::findByCredentials($credentials);
        if($user){
            //if ther is a user, check validation
            $validated = Sentinel::validateCredentials($user, $credentials);
            if($validated && $user->role == 'from_admin'){
                //if validated and the user is created by admin, or user is admin or super admin,login user
                Sentinel::login($user);
            }
        }
        //return admin dashboard
    	return redirect()->action('Admin\AdminController@getDashboard');
    }

    /**
     * logout admin
     * GET /admin/logout
     *
     * @return view
     */
    public function getLogout()
    {
        //get the auth user
    	$user = Sentinel::getUser();

        //logout the user
    	Sentinel::logout($user);

        //return login page
    	return redirect()->action('Admin\AdminController@getLogin');
    }

    /**
     * get location details
     */
    public function getLocationDetails(Request $request)
    {
        $header = $request->header('Accept-Language');
        $headerLang = substr($header, 0, 2);
        $headerCountryCode = substr($header, 3, 2);
        $ip = $request->ip();
        $record = geoip_record_by_name($ip);
        $countryCode = strtolower( $record['country_code'] );
        if ($record) {
            $cityName = $record['city'];
            $countryName = $record['country_name'];
            $countryCode = $record['country_code'];
            $latitude = $record['latitude'];
            $longitude = $record['longitude'];            
        } else {
            $cityName = '';
            $countryName = '';
            $countryCode = '';
            $latitude = '';
            $longitude = '';
        }

        $data =  [
                    'cityName' => $cityName,
                    'countryName' => $countryName,
                    'countryCode' => $countryCode,
                    'longitude' => $longitude,
                    'latitude' => $latitude,
                    'ip' => $ip,
                    'headerLang' => $headerLang,
                    'headerCountryCode' => $headerCountryCode
                ];
        return view('admin.location_details', $data);
    }

    /**
     * get alerts
     * GET /admin/alerts
     * 
     * return view
     */
    public function getAlerts()
    {
        $alerts = $this->subscribtionRepo->getSubscribtionsPaginated();
        $data = ['alerts' => $alerts];
        return view('admin.alerts', $data);
    }

    /**
     * remove alerts
     * Get /admin/remove-alert
     */
    public function getRemoveAlert($alertId)
    {
        $this->subscribtionRepo->removeSubscribtion($alertId);

        return redirect()->back();
        
    }

    /**
     * Save json translations in laravel languages in array
     */

    public function getSaveTranslationsJson()
    {
        $locales = config('translatable.locales');
        foreach($locales as $locale) {
            $json = \File::get(public_path()."/angular/languages/".$locale.".json");
            $array = json_decode($json, true);
            $save = file_put_contents('../resources/lang/'.$locale.'/angular.php', '<?php return ' . var_export($array, true) . ';');
        }
        dd('success');
        
    }

    public function postPublishTranslations($group = null)
    {
       $locales = config('translatable.locales');
       foreach ($locales as $locale) {
            //$array = \File::get('../resources/lang/'.$locale.'/angular.php');
            //$translations = \Translation::ofTranslatedGroup('angular')->get();
            $translations = $this->languageRepo->getGroupTranslation();
            $array = array();
            foreach($translations as $translation){
                array_set($array[$translation->locale][$translation->group], $translation->key, $translation->value);
            }

            foreach ($array as $locale => $groups) {
                $translations = $groups['angular'];
                $json = json_encode($translations);
                //$path = $this->app['path.lang'].'/'.$locale.'/'.$group.'.php';
                $path = '/angular/languages/'.$locale.'.json';
                // $output = var_export($json, true).";\n";
                file_put_contents(public_path().$path, $json);
            }
            dd('success');
       }
    }

    public function getPhoneCode(Request $request)
    {
    //get county code from request
        $countryCode = $request->countryCode;
        //get phone code of the country
        $countries = \File::getRequire('phone_codes/countries.php');
        foreach($countries as $country) {
            if ($country['code'] == $countryCode) {
                $phone_code = $country['d_code'];
            }
        }
        $data = ['phone_code' => $phone_code];
        return response()->json($data);
    }

    public function getUsernameGenerate()
    {
        $users = $this->userRepo->getAllUsers();
        foreach($users as $user) {
            $username = strtolower($user->first_name).'-'.strtolower($user->last_name).'-'.$user->id;
            $user->update(['username' => $username]);
        }

        dd('success');
    }

    public function getChangeAdminInfo()
    {
        $superAdmin = $this->userRepo->getSuperAdmin();
        $data = [
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'username' => null,
            'birthdate' => null,
            'country' => null,
            'city' => null,
            'phone_number' => null,
            'user_experience' => null,
            'gender' => null,
            'nationality' => null,
            'transport' => null,
            'education' => null,
            'week_days' => null,
            'hours' => null,
            'location' => null,
            'role' => 'from_admin',
            'working_area' => null,
            'schedule' => null,
            'latitude' => null,
            'longitude' => null
        ];

        $this->userRepo->editUser($superAdmin, $data);
        dd($superAdmin);
    }
}
