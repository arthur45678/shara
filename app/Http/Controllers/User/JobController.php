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
use App\Contracts\SubscribtionInterface;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException; 
use Sentinel;
use Activation;
use User;
use File;

class JobController extends Controller
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
     * Object of SubscribtionInterface class
     *
     * @var subscribtionRepo
     */
    private $subscribtionRepo;

    /** 
     * Create a new instance of Controller class.
     *
     * @param CompanyInterface $companyRepo
     * @return void
     */
	public function __construct(CompanyInterface $companyRepo, CategoryInterface $categoryRepo, CountryInterface $countryRepo, SectorInterface $sectorRepo, CityInterface $cityRepo, JobInterface $jobRepo, UserInterface $userRepo, MailInterface $mailRepo, LanguageInterface $languageRepo, SubscribtionInterface $subscribtionRepo)
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
        $this->subscribtionRepo = $subscribtionRepo;
	}

	/**
     * Get job information for job details page
     *
     * @get('/job-details')
     */
    public function getJobDetails(Request $request)
    {
        if ($request) {
            $jobId = $request->jobId;
            $job = $this->jobRepo->getJobById($jobId);
            $data = [
                'job' => $job,            
            ];
            return response()->json($data);
        } else {
            return response()->json(['error' => 'something went wrong']);
        }
    }

    /**
     * Apply for a job
     *
     * @Get('/apply-job')
     */
    public function getApplyForJob(Request $request)
    {
        // JWTAuth::parseToken();
        $user_id = $request->user_id;
        if($request->user_id !== 0) {
            $user = $this->userRepo->getUserById($user_id);
        }else {
            return response()->json(['status' => 'not_logged_in']);
        }
        $job_id = $request->job_id;
        $job = $this->jobRepo->getJobById($job_id);
        if ($user) {
            if($user->applications->contains($job_id)){
                $job = $this->jobRepo->getJobById($job_id);
                return response()->json(['status' =>'applied_for_job', 'user_id' => $user->id, 'job_applying' => $job->job_applying]);
            }
            if ($user->step !== 4) {
                return response()->json(['status' =>'registration_not_completed', 'user_id' => $user->id]);
            }elseif($user->step == 4) {
                if($job->job_applying == 'form') {
                    $user->applications()->attach($job_id, ['company_id' => $job->company_id]);
                    return response()->json(['status' => 'job_apply_success', 'user_id' => $user->id]);
                }else {
                    $user->applications()->attach($job_id, ['company_id' => $job->company_id]);
                    return response()->json(['status' => 'job_apply_redirect_success', 'user_id' => $user->id]);
                }       
                
            }            
        }else {
            return response()->json(['status' => 'not_logged_in']);
        }
        
        // return response()->json($user);
    }

    /**
     * Checks if user has applied for the job
     *
     * @Get('/user-applied-job')
     */
    public function getUserAppliedJob(Request $request)
    {
        $job_id = $request->job_id;
        $job = $this->jobRepo->getJobById($job_id);
        $data = ['job_applying' => $job->job_applying];
        $user_id = $request->user_id;
        if($user_id !== 0) {
            $user = $this->userRepo->getUserById($user_id);
        }else {
            $data['status'] = 'not_logged_in';
            return response()->json($data);
        }
        if($user) {
            $jobs = $user->applications;
        }
        foreach($jobs as $job) {
            if($job_id == $job->id) {
                $data['status'] = 'applied_for_job';
                return response()->json($data);
            }
        }
        $data['status'] = 'not_applied_for_job';
        return response()->json($data);
    }

    /**
     * get subscribtion
     *
     * @Get('/get-subscribtion')
     */
    public function getSubscribtion(Request $request)
    {
        // check if the user is logged in
        if($request->authToken){
            // if yes, get the auth user
            $authUser = \JWTAuth::parseToken()->authenticate();
            // get the auth user's email and id
            $email = $authUser->email;
            $userId = $authUser->id;
        }else{
            // if not, get request email
            $email = $request->email;
        }
        $subData = [
                'email' => $email,
                'keyword' => $request->keyword,
                'latitude' => $request->latitude,
                'longtitude' => $request->longtitude,
                'category_id' => $request->category_id,
                'sector_id' => $request->sector_id,
                'country' => $request->country,
                'city' => $request->city,
                ];
        $subscribtion = $this->subscribtionRepo->getSubscribtion($subData);
        if($subscribtion)
        {
            $data = ['status' => 'exists']; 
        } else {
            $data = ['status' => 'not_found'];
        }
        return response()->json($data);
    }
    /**
     * Subscribe for job notifications
     * 
     * @Get('/user-applied-job')
     */
    public function getSubscribeForJobs(Request $request)
    {
        // check if the user is logged in
        if($request->authToken){
            // if yes, get the auth user
            $authUser = \JWTAuth::parseToken()->authenticate();
            // get the auth user's email and id
            $email = $authUser->email;
            $userId = $authUser->id;
        }else{
            // if not, get request email
            $email = $request->email;
        }

        // get request data for validation
        $data = [
                'email' => $email,
                'keyword' => $request->keyword, 
                'country' => $request->country,
                'code' => $request->lang,
                'city' => $request->city,
                'longtitude' => $request->longtitude,
                'latitude' => $request->latitude,
                'code' => $request->countryCode,
                'category_id' => $request->category_id,
                'sector_id' => $request->sector_id
                ];

                // if no location matched, get user current location
                if(!$request->country){
                    $ip = $request->ip();
                    $record = geoip_record_by_name('212.91.77.88');
                    $countryCode = strtolower( $record['country_code'] );

                    $latitude = $record['latitude'];
                    $longtitude = $record['longitude'];
                    $country = $this->countryRepo->getCountryByLocale($countryCode);
                    if($country){
                        $data['country'] = $country->name;
                        $data['code'] = $country->abbreviation;
                    }
                    $data['latitude'] = $latitude;
                    $data['longtitude'] = $longtitude;
                }
        $validator = \Validator::make($data, [
            'email' => 'required|email',
            'country' => 'required',
            'code' => 'required'
        ]);
        if($validator->fails()){
            // if validation fails, return validation errors
            $responseData = ['errors' => $validator->errors()];
            return response()->json($responseData);
        }else{
            // subscribe for job
            $subscribtion = $this->subscribtionRepo->subscribeForJob($data);
            $responseData = [];
            if(isset($subscribtion['successMessage'])){ 
                // if there is no subscribtion with subscribtion data, subscribe
                $responseData = ['successMessage' => $subscribtion['successMessage']];
            }elseif(isset($subscribtion['existsMessage'])){
                // else, return warning message
                $responseData = ['existsMessage' => $subscribtion['existsMessage']];
            }
            return response()->json($responseData);
        }
    }

    /**
     * public function remove subscibtion
     * @Get('/remove-alert')
     */
    public function getRemoveAlert($alertId, Request $request)
    {
        $alertId = $request->alertId;
            if($alertId){
                $alert = $this->subscribtionRepo->getSubscibtionById($alertId);
                $notifications = $alert->notifications;
                if(count($notifications) > 0){
                    foreach ($notifications as $notification) {
                        $this->subscribtionRepo->removeNotification($notification->id);
                    }
                }
                $this->subscribtionRepo->removeSubscribtion($alertId);
                $data = ['status' => 'success'];
            }else{
                $data = ['status' => 'error'];
            }
            return response()->json($data);
        
    }

    /**
     * remove alert from email
     */
    public function getRemoveAlertFromEmail($alertId)
    {
        $alert = $this->subscribtionRepo->getSubscibtionById($alertId);
        if($alert){
            $notifications = $alert->notifications;
            if(count($notifications) > 0){
                foreach ($notifications as $notification) {
                    $this->subscribtionRepo->removeNotification($notification->id);
                }
            }
            $this->subscribtionRepo->removeSubscribtion($alertId);
        }
        return view('success');
        
    }


    public function getJobCompanyInfo(Request $request)
    {
        $job = $this->jobRepo->getJobById($request->jobId);
        $company = $job->company;
        $data = ['companyName' => $company->name];
        if($company->type == 'generic') {
            $data['companyType'] = 'generic';
        }else if($company->type == 'subsidiary') {
            if($company->sub_type == 'country_subsidiary') {
                $data['companyType'] = 'country_subsidiary';
                $data['countryName'] = $company->country->name;
            }else if ($company->sub_type == 'city_subsidiary') {
                $data['companyType'] = 'city_subsidiary';
                $data['countryName'] = $company->country->name;
                $data['cityName'] = $company->city_name;
            }
        }

        return response()->json($data);
    }
}
