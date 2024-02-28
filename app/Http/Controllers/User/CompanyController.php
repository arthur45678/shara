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
use User;
use File;
use Exception;

class CompanyController extends Controller
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
     * Get company information for company details page
     *
     * @Get('/company-details')
     */
    public function getCompanyDetails(Request $request)
    {
        if ($request) {
            //get company and country from request
            $companyName = $request->companyName;
            if(isset($request->lang)) {
                // $country = $this->countryRepo->getCountryByName($request->countryName);
                $country = $this->countryRepo->getCountryByLocale($request->lang);
                $ip = $request->ip();
                try {
                    //getting countryCode and record from the ip
                    $record = geoip_record_by_name($ip);
                    if($record) {
                        $latitude = $record['latitude'];
                        $longitude = $record['longitude'];
                    }else {
                        $latitude = $country->latitude;
                        $longitude = $country->longitude;
                    }
                    
                } catch (Exception $e) {
                    $latitude = $country->latitude;
                    $longitude = $country->longitude;
                }
                               
                $company = $this->companyRepo->getCountrySubsidiary($companyName, $country->id, $latitude, $longitude);
                if(isset($company['error'])) {
                    return response()->json(['error' => 'redirect_to_homepage', 'lang' => $request->lang]);
                }
                if ($company->sector) {
                    $company->sector->defaultSector =  $company->sector->getTranslation('en', true)->name;
                }
                $company = $this->helperFuncion($company, 'company');
            }            
            $data = [
                'company' => $company,            
            ];
            //if user is autenticated, check if the user has applied for company
            try {
                $user = \JWTAuth::parseToken()->authenticate();
                if($user->step == 4) {
                    $company->logged_in = true;
                }else {
                    $company->logged_in = false;
                }
                
                    if((count($user->applications) > 0)) {
                        $contains = $this->userRepo->checkIfUserAppliedCompany($user->id, $company->id);
                        if($contains){
                            if($company->job_applying == 'form') {
                                $company->apply = false;
                            }else {
                                $company->apply = true;
                            }
                            
                        }else{
                            $company->apply = true;
                        }
                    }else if((count($user->applications) == 0)) {
                        $company->apply = true;
                    }
            } catch (Exception $e) {
                \Log::info($e->getMessage());
                $company->apply = true;
                $company->logged_in = false;
            }
            if ($company->has('jobs', '>', '0')) {
                //get company's jobs
                $jobs = $this->jobRepo->getCompanyJobs($company->id);
                $company->jobs = $jobs;
                if($jobs) {
                    foreach($jobs as $job) {
                        $job->about_company = $company->description;
                        $job = $this->helperFuncion($job, 'job');
                        //if user is autenticated, check if the user has applied for each job
                        try {
                            $user = \JWTAuth::parseToken()->authenticate();
                                if((count($user->applications) > 0)) {
                                    if($user->applications->contains($job->id)){
                                        $job->userApplied = true;
                                        if($job->job_applying == 'form') {
                                            $job->apply = false;
                                        }else {
                                            $job->apply = true;
                                        }
                                    }else{
                                        $job->userApplied = false;
                                        $job->apply = true;
                                    }
                                }else if((count($user->applications) == 0)) {
                                    $job->apply = true;
                                }
                        } catch (Exception $e) {
                            \Log::info($e->getMessage());
                            $job->apply = true;
                        }
                    }
                }
            }            
            return response()->json($data);
        } else {
            return response()->json(['error' => 'something went wrong']);
        }
    }

    /**
     * Get company information
     *
     * @Get('/company-subsidiary-info')
     */
    public function getCompanySubsidiaryInfo(Request $request)
    {
        $companyId = $request->companyId;
        $company = $this->companyRepo->getCompanyById($companyId);
        $countryName = $company->country->name;
        $category = $company->category;
        $category->defaultCategory =  $category->getTranslation('en', true);
        $data = ['countryName' => $countryName, 'category' => $category];
        
        return response()->json($data);

    }

    /**
     * Apply for company
     *
     * @Get('/apply-company')
     */
    public function getApplyForCompany(Request $request)
    {
        //if user is autenticated, get the user, otherwise return error
        $user_id = $request->user_id;
        if($request->user_id !== 0) {
            $user = $this->userRepo->getUserById($user_id);
        }else {
            return response()->json(['status' => 'not_logged_in']);
        }
        //get company
        $company_id = $request->company_id;
        $company = $this->companyRepo->getCompanyById($company_id);
        //check if user has applied for company
        $contains = $this->userRepo->checkIfUserAppliedCompany($user_id, $company_id);
        if ($user) {
            if($contains){
                //if user has applied for company, return according status
                return response()->json(['status' =>'applied_for_company', 'user_id' => $user->id, 'company_applying' => $company->job_applying]);
            }
            if ($user->step !== 4) {
                //if user hasn't finished the registration process, return accoridng message
                return response()->json(['status' =>'registration_not_completed', 'user_id' => $user->id]);
            }elseif($user->step == 4) {
                //if everything is good, apply for company depending on applying method
                if($company->job_applying == 'form') {
                    $this->userRepo->applyForCompany($user_id, $company_id);
                    return response()->json(['status' => 'company_apply_success', 'user_id' => $user->id]);
                }else {
                    $this->userRepo->applyForCompany($user_id, $company_id);
                    return response()->json(['status' => 'company_apply_redirect_success', 'user_id' => $user->id]);
                }       
            }            
        }else {
            return response()->json(['status' => 'not_logged_in']);
        }
    }
    /**
     * 
     */
    private function helperFuncion($object, $type)
    {
        if($type == 'job'){
            $object->description = nl2br($object->description);
            $object->requirement = nl2br($object->requirement);
            $object->why_us = nl2br($object->why_us);
            $object->benefits = nl2br($object->benefits);
        }elseif($type == 'company'){
            $object->description = nl2br($object->description);
        }
        return $object;

    }

    public function getCompanyDetailsStatic($lang, $companyName, $countryName)
    {
        \Log::info('Log from Facebook');
        $country = $this->countryRepo->getCountryByName($countryName);
        $latitude = $country->latitude;
        $longitude = $country->longitude;
        $companyName = urldecode($companyName);                      
        $company = $this->companyRepo->getCountrySubsidiary($companyName, $country->id, $latitude, $longitude);
        $company = $this->helperFuncion($company, 'company');


        $data = [
            'title' => $company->name,
            'url' => url('/').'/'.$lang.'/company/'.$company->name.'/'.$countryName,
            'image' => url('/').'/uploads/'.$company->logo,
            'description' => $company->description,
            'company' => $company
        ];
        return view('company-details-static-full', $data);
    }
}
