<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Contracts\JobInterface;
use App\Contracts\CountryInterface;
use App\Contracts\CityInterface;
use App\Contracts\CategoryInterface;
use App\Contracts\CompanyInterface;
use App\Contracts\SectorInterface;
use App\Http\Requests\JobCreateRequest;
use App\Http\Requests\JobEditRequest;
use App\Http\Requests\SearchRequest;
use Stichoza\GoogleTranslate\TranslateClient;  
use Sentinel;
use Log;

class JobController extends Controller
{
	/**
     * Object of JobInterface class
     *
     * @var jobRepo
     */
    private $jobRepo;

    /**
     * Object of CompanyInterface class
     *
     * @var companyRepo
     */
    private $companyRepo;

    /**
     * Object of CountryInterface class
     *
     * @var countryRepo
     */
    private $countryRepo;

    /**
     * Object of CityInterface class
     *
     * @var cityRepo
     */
    private $cityRepo;

    /**
     * Object of CategoryInterface class
     *
     * @var categoryRepo
     */
    private $categoryRepo;

    /**
     * Object of UserInterface class
     *
     * @var userRepo
     */
    private $sectorRepo;

    /** 
     * Create a new instance of Controller class.
     *
     * @param CompanyInterface $companyRepo
     * @return void
     */
	public function __construct(JobInterface $jobRepo, CompanyInterface $companyRepo, CountryInterface $countryRepo, CityInterface $cityRepo, CategoryInterface $categoryRepo, SectorInterface $sectorRepo)
	{
		$this->jobRepo = $jobRepo;
		$this->companyRepo = $companyRepo;
		$this->countryRepo = $countryRepo;
		$this->cityRepo = $cityRepo;
		$this->categoryRepo = $categoryRepo;
		$this->sectorRepo = $sectorRepo;
		$this->middleware("admin");
        $this->middleware("genericAdmin", ['except' => [
                                        'getCreateJob', 
                                        'postCreateJOb',
                                        'getEditJob',
                                        'postEditJob',
                                        'postCloneJob'
                                    ]]);
	}

    /**
     * get job management page,
     * that show the all jobs ordered ascending by creation date 
     * and allows to create a new job, edit jobs, view job details, search jobs
     * order by company name, name, country, city descending and ascending 
     * GET /admin/jobs
     * 
     * @param search details Request $request
     * @param sorting parameter string $sort
     * @param sorting type string $type
     * @return view
     */
    public function getJobs(Request $request, $sort, $type)
    {
        // get the auth user
        $loggedUser = Sentinel::getUser();
        // check if the user has permission to view the jobs
        if($loggedUser->hasAccess('job.view')){
            // check if there are search parameter
            $searchDetails = $request->except('page');;
            if(count($searchDetails) > 0){
                // if yes
                // get the search parameters
                // job name
                $name = $request->job_search;
                // country name
                $country = $request->country;
                if($country){
                    // if ther is a country request, get the country object
                    $countryObject = $this->countryRepo->getCountryByName($country);
                    // get the country id 
                    $countryId = $countryObject->id;
                }else{
                    $countryId = '';
                }
                $city = $request->city;     
                $categoryId = $request->category;
                $sectorId = $request->industry;
                // get the jobs with search parameters
                $jobs = $this->jobRepo->searchJob($name, $countryId, $city, $categoryId, $sectorId, $sort, $type);
            }else{
                // if no
                // get all jobs
                $jobs = $this->jobRepo->getAllJobsPaginate($sort, $type);
            }

            // get all countries, categories, sectors for search ordered alphabetically
            $countries = $this->countryRepo->getAllCountries();
            $categories = $this->categoryRepo->getOrderedCategories();
            $sectors = $this->sectorRepo->getOrderedSectors();
            // reverse the sorting type
            if($type == 'asc')
                $newType = 'desc';
            else
                $newType = 'asc';

            $data = [
                'jobs' => $jobs,
                'countries' => $countries,
                'categories' => $categories,
                'sectors' => $sectors,
                'type' => $newType
                ];

            // return job management page
            return view('admin.jobs', $data);

        }else{
            // if no
            // redirect back
            return redirect()->back();
        }
    }

    /**
     * get job create page
     * GET /admin/create-job
     * 
     * @param string $type
     * @return view
     */
    public function getCreateJob($type)
    {
        // get auth user
        $loggedUser = Sentinel::getUser();
        // check if the user hass permission to create a job
        if ($loggedUser->hasAccess('job.create') || $loggedUser->admin_type != 'generic'){
            // if yes
            // get data to create a job
            // get all companies and countries
            $countries = $this->countryRepo->getAllCountries();
            $companies = $this->companyRepo->getSubsidiaries(); 
            // get active sectors and categories
            $categories = $this->categoryRepo->getActiveOrderedCategories();
            $sectors = $this->sectorRepo->getActiveOrderedSectors();

            if(strpos($type, 'company') !== false)
            {
                $companyId = ltrim($type, 'company');
                $company = $this->companyRepo->getCompanyById($companyId);
                $data = [
                        'company_id' => $companyId,
                        'sector_id' => $company->sector_id,
                        'category_id' => $company->category_id,
                        'countries' => $countries,
                        'categories' => $categories,
                        'sectors' => $sectors,
                        'companies' => $companies,
                        'comp' => $company,
                        'companyType' => $type,
                        'about_company' => $company->description,
                        'whyUs' => $company->why_us

                    ];

                $cities = [];
                $countries = [];
                if($company->type == 'generic')
                {
                    
                    $subsidiaries = $company->subsidiaries;
                    $countries = [];
                    foreach ($subsidiaries as $key => $value) {
                        $country = $value->country;
                        if(!in_array($country, $countries)){
                            $countries[] = $country;
                        }
                        
                    }
                    $countries = collect($countries);
                    $data['countries'] = $countries;
                }else{
                    if($company->sub_type == 'country_subsidiary'){
                        $data['country_id'] = $company->country_id;
                        $cities = $this->companyRepo->getCompaniesByCountry($company->id, $company->country_id);
                        $data['cities'] = $cities;
                    }elseif($company->sub_type == 'city_subsidiary'){
                        $data['country_id'] = $company->country_id;
                        $data['city_name'] = $company->city_name;
                        $data['city_longtitude'] = $company->city_longtitude;
                        $data['city_latitude'] = $company->city_latitude;
                    }
                    $countries[] = $company->country;
                    $countries = collect($countries);
                    $data['countries'] = $countries;
                }
            }else{
                 $data = [
                    'countries' => $countries,
                    'categories' => $categories, 
                    'sectors' => $sectors,
                    'companies' => $companies,
                ];
            }

            Log::info(print_r($sectors, true));
            
           
            // return job create page     
            return view('admin.create_job', $data);
        }else{
            // redirect back
            return redirect()->back();
        }
    }

    /**
     * create a job
     * POST  /admin/create-job
     * 
     * @param JobCreateRequest $request
     * @return redirect
     */
    public function postCreateJOb(JobCreateRequest $request)
    {
        //get auth user
        $authUser = Sentinel::getUser();
        //get request data to create a job
        $name = $request->name;
        $companyId = isset($request->company) &&  $request->company != ""? $request->company : null;
        $countryName = $request->country;
        $country = $this->countryRepo->getCountryByName($countryName);
        $countryId = isset($country) ? $country->id : null;
        $city = $request->city;
        $cityLatitude = $request->city_latitude;
        $cityLongtitude = $request->city_longtitude;
        $region = $request->region;
        $sectorId = isset($request->sector) &&  $request->sector != ""? $request->sector : null;
        $categoryId = isset($request->category) &&  $request->category != ""? $request->category : null;
        $description = $request->description;
        $aboutCompany = $request->about_company;
        $requirement = $request->requirement;
        $compensation = $request->compensation;
        $schedule = $request->schedule;
        $whyUs = $request->why_us;
        $benefits = $request->benefits;
        $jobApplying = $request->job_applying;
        $redirectUrl = $jobApplying == 'redirect' ? $request->url_to_redirect : '';
        $activation = isset($request->activation) && $request->activation == 'activated' ? 'activated' : 'deactivated';
        if($authUser->admin_type == 'generic'){
            $restrict = isset($request->is_published) && $request->is_published != '' ? 'true' : '';

        }else{
            $restrict = 'true';
        }


        //if job has company and company is not published,job can not be published
        if($companyId && $restrict == ''){
            $company = $this->companyRepo->getCompanyById($companyId);
            if($company->restrict == 'true'){
                return redirect()->back()->withInput()->with('error', 'You can not publish the job if the company is not published.');
            }
        }
  
        // if($countryId && $city && $companyId)
        //         $type = 'specific';
        
        // else
        //         $type = 'generic';

        $data = [
                'name' => $name,
                'company_id' => $companyId,
                'country_id' => $countryId,
                'city_name' => $city,
                'city_latitude' => $cityLatitude,
                'city_longtitude' => $cityLongtitude,
                'region' => $region,
                'sector_id' => $sectorId,
                'category_id' => $categoryId,
                'description' => $description,
                'about_company' => $aboutCompany,
                'requirement' => $requirement,
                'compensation' => $compensation,
                'schedule' => $schedule,
                'why_us' => $whyUs,
                'benefits' => $benefits,
                'job_applying' => $jobApplying,
                'url_to_redirect' => $redirectUrl,
                'type' => 'specific',
                'activation' => $activation,
                'restrict' => $restrict,
                ];
       
       // create the job
        $job = $this->jobRepo->createJob($data);

        //check if the job is is createing from a company 
        $fromCompanyId = $request->main_company;
        if($fromCompanyId){
            // if yes
            // get the company id and type, and redirect to the company page
            $fromCompany = $this->companyRepo->getCompanyById($fromCompanyId);
            if($fromCompany->type == 'generic'){
                $urlType = 'generic';
            }else{
                if($fromCompany->sub_type == 'country_subsidiary'){
                    $urlType = 'sub';
                }else{
                    $urlType = 'sub_city';
                }
            }
            return redirect()->action('Admin\CompanyController@getEditCompany', [$fromCompanyId, $urlType])->with('message', 'New job has been successfully added.');
        }else{
            // if no
            // redirect to job management page
            return redirect()->action('Admin\JobController@getJobs', ['date', 'asc'])->with('message', "The job has been successfully added");;
        }
    }

    /**
     * gte edit job page
     * GET /admin/edit-job/{jobId}
     * 
     * @param int $jobId
     * @return view
     */
    public function getEditJob($jobId)
    {
        // get the auth user
        $loggedUser = Sentinel::getUser();
        // check if the user has permission to edit the job
        if($loggedUser->hasAccess('job.update') || $loggedUser->admin_type != 'generic'){
            // if yes
            // get the job
            $job = $this->jobRepo->getJobById($jobId);
            // get all companies and countries, ordered alphabetically
            $countries = $this->countryRepo->getAllCountries();
            $companies = $this->companyRepo->getSubsidiaries();
            // get active sectors and categories, ordered alphabetically
            $categories = $this->categoryRepo->getActiveOrderedCategories();
            $sectors = $this->sectorRepo->getActiveOrderedSectors();

            $data = [
                    'job' => $job,
                    'companies' => $companies,
                    'countries' => $countries,
                    'categories' => $categories,
                    'sectors' => $sectors,
                    ]; 

            // check if the job has company
            if($job->company){
                // if yes
                // get job company
                $jobCompany = $job->company;

                // if job has not a compensation, get company compensation
                if(!$job->compensation){
                    $job->compensation = $jobCompany->compensation;
                }
                // get company countries, not all countries
                $countries = [];
                $cities = [];
                if($jobCompany->type == 'generic'){
                    // if company is a generic company, get all subsidiaries' countries
                    $subsidiaries = $jobCompany->subsidiaries;
                    if($subsidiaries){
                        foreach ($subsidiaries as $key => $subsidiary) {
                            $subCountry = $subsidiary->country;
                            if($subCountry && !in_array($subCountry->name, $countries)){
                                $countries[] = $subCountry;
                            }
                        }
                    }
                }else{
                    // if job has not a compensation and the job company, which is subsidiary, has not a compensation, get generic company compensation
                    if(!$job->compensation){
                        $job->compensation = $jobCompany->generic->compensation;
                    }
                    // if the company is a subsidiary, get only this company country
                   $countries[] = $jobCompany->country;
                   if($jobCompany->country){
                    if($jobCompany->sub_type == 'country_subsidiary'){
                        // if the company is a country subsidiay, get this subsidiary cities
                        $cities = $this->companyRepo->getCompaniesByCountry($jobCompany->id, $jobCompany->country_id);
                       }else{
                        // if the company is a city subsidiary, get the company city
                        $cities = $jobCompany->city_name;
                       }
                   }

                }

                // sort countries alphabetically
                sort($countries);
                // collect countries
                $countries = collect($countries);

                // if the job has not city latitude or city longtitude, get the company city latitude and longtitude
                $job->city_latitude = !$job->city_latitude || $job->city_latitude == '' ? $jobCompany->city_latitude : $job->city_latitude;
                $job->city_longtitude = !$job->city_longtitude || $job->city_longtitude == '' ? $jobCompany->city_longtitude : $job->city_longtitude;
                // $data['countries'] = $countries;
                $data['cities'] = $cities;
            }

            // return job edit page
            return view('admin.edit_job', $data);
        }else{
            // if no
            // redirect back
            return redirect()->back();
        }
    }

    /**
     * edit the job
     * POST /admin/edit-job
     * 
     * @param JobEditRequest $request
     * @return redirect
     */
    public function postEditJob(JobEditRequest $request)
    {
        //get auth user
        $authUser = Sentinel::getUser();
       //get request data to create a job
        $jobId = $request->job_id;
        $name = $request->name;
        $companyId = isset($request->company) &&  $request->company != ""? $request->company : null;
        $countryName = $request->country;
        $country = $this->countryRepo->getCountryByName($countryName);
        $countryId = isset($country) ? $country->id : null;
        $city = $request->city;
        $cityLatitude = $request->city_latitude;
        $cityLongtitude = $request->city_longtitude;
        $region = $request->region;
        $sectorId = isset($request->sector) &&  $request->sector != ""? $request->sector : null;
        $categoryId = isset($request->category) &&  $request->category != ""? $request->category : null;
        $description = $request->description;
        $aboutCompany = $request->about_company;
        $requirement = $request->requirement;
        $compensation = $request->compensation;
        $schedule = $request->schedule;
        $whyUs = $request->why_us;
        $benefits = $request->benefits;
        $jobApplying = $request->job_applying;
        $redirectUrl = $request->url_to_redirect;
        $activation = isset($request->activation) && $request->activation == 'activated' ? 'activated' : 'deactivated';
        if($authUser->admin_type == 'generic'){
            $restrict = isset($request->is_published) && $request->is_published != '' ? 'true' : '';

        }else{
            $restrict = 'true';
        }

        //if the job has company and the company is not published,the job can not be published
        if($companyId && $restrict == ''){
            $company = $this->companyRepo->getCompanyById($companyId);
            if($company->restrict == 'true'){
                return redirect()->back()->withInput()->with('error', 'You can not publish the job if the company is not published.');
            }
        }

        // // if there are country, city, sector and category for job, job type is generic
        // if($countryId && $city && $companyId)
        //         $type = 'specific';
        // // else, job is specific
        // else
        //         $type = 'generic';

        $data = [
                'name' => $name,
                'company_id' => $companyId,
                'country_id' => $countryId,
                'city_name' => $city,
                'city_latitude' => $cityLatitude,
                'city_longtitude' => $cityLongtitude,
                'region' => $region,
                'sector_id' => $sectorId,
                'category_id' => $categoryId,
                'description' => $description,
                'about_company' => $aboutCompany,
                'requirement' => $requirement,
                'compensation' => $compensation,
                'schedule' => $schedule,
                'why_us' => $whyUs,
                'benefits' => $benefits,
                'job_applying' => $jobApplying,
                'url_to_redirect' => $redirectUrl,
                'type' => 'specific',
                'activation' => $activation,
                'restrict' => $restrict,
                ];
       
        // get the job and edit
        $job = $this->jobRepo->getJobById($jobId);
        $job = $this->jobRepo->editJob($job, $data);
        // redirect back with success message
        return redirect()->back()->with('message', 'Your changes have been successfully applied');
    }

    /**
     * get show job page
     * GET /admin/show-job
     *
     * @param int $jobId
     * @return view
     */
    public function getShowJob($jobId)
    {
        // get the auth user
        $loggedUser = Sentinel::getUser();
        // check if the user has permission to view job details
        if($loggedUser->hasAccess('job.view')) {
            // if yes
            // get the job object
            $job = $this->jobRepo->getJobById($jobId);

            $data = [
                    'job' => $job,
                     ];
            // return job details view
            return view('admin.show_job', $data);
        }else {
            // if no
            // redirect back
            return redirect()->back();
        }
    }

    /**
     * delete a job
     * GET /admin/delete-job/{jobId}
     *
     * @param int $jobId
     * @return redirect
     */
    public function getDeleteJob($jobId)
    {
        // get auth user
        $logged_user = Sentinel::getUser();
        // check if the user has permission to delete the job
        if($logged_user->hasAccess('job.delete')) {
            // if yes
            // get the job object
            $job = $this->jobRepo->getJobById($jobId);
            // delete the job
            $this->jobRepo->deleteJob($job);
            // redirect back
            return redirect()->back();
        }else {
            // if no
            // redirect back
            return redirect()->back();
        }
    }

    /**
     * clone job for company from another job
     * POST /admin/clone-job
     * 
     * @param Request $request
     * @return redirect
     */
    public function postCloneJob(Request $request)
    {
        $jobFromId = $request->job_from;
        $jobFrom = $this->jobRepo->getJobById($jobFromId);

        // get some details from job
        $name = $jobFrom->name;
        $sectorId = $jobFrom->sector_id;
        $categoryId = $jobFrom->category_id;
        $requirement = $jobFrom->requirement;
        $schedule = $jobFrom->schedule;
        $whyUs = $jobFrom->why_us;
        $benefits = $jobFrom->benefits;
        $type = $jobFrom->type;
        $description = $jobFrom->description;

        // get some details from company
        $companyId = $request->company_from_id; 
        $company = $this->companyRepo->getCompanyById($companyId);
        $aboutCompany = $company->description;
        $jobApplying = $company->job_applying;
        $redirectUrl = $company->url_to_redirect;

        // if the company is generic, there is no need to get the location details
        $countryId = null;
        $city = null;
        $cityLatitude = null;
        $cityLongtitude = null;
        $region = null;

        // check the company type
        if($company->type == 'subsidiary'){
            // if the company is subsidiary, get the company country
            $countryId = $company->country_id;
            if($company->sub_type == 'city_subsidiary'){
                // if the company is city subsidiary, get the company city, city latitude, longtitude, region
                $city = $company->city_name;
                $cityLatitude = $company->city_latitude;
                $cityLongtitude = $company->city_longtitude;
                $region = $company->region;
                // if the city subsidiary has not job applying details, get from country parent
                if(!$jobApplying){
                    $jobApplying = $company->countryParent->job_applying;
                    $redirectUrl = $company->countryParent->url_to_redirect;
                }
            }

            // if no job applying details, get from generic company
            if(!$jobApplying){
                $jobApplying = $company->generic->job_applying;
                $redirectUrl = $company->generic->url_to_redirect;
            }
        }

        // by deafult set the restriction status is true(the job is unpublished by default)
        $restrict = 'true';

        $data = [
            'name' => $name,
            'company_id' => $companyId,
            'sector_id' => $sectorId,
            'category_id' => $categoryId,
            'country_id' => $countryId,
            'city_name' => $city,
            'city_latitude' => $cityLatitude,
            'city_longtitude' => $cityLongtitude,
            'region' => $region,
            'description' => $description,
            'about_company' => $aboutCompany,
            'why_us' => $whyUs,
            'benefits' => $benefits,
            'requirement' => $requirement,
            'schedule' => $schedule,
            'type' => $type,
            'job_applying' => $jobApplying,
            'url_to_redirect' => $redirectUrl,
            'restrict' => $restrict
        ];

        // create the job

        $job = $this->jobRepo->createJob($data);

        // redirect to the created job to edit it
        return redirect()->action('Admin\JobController@getEditJob', $job->id);

    }

    /**
     * make the job published
     * GET /admin/make-job-publish/{id}
     * 
     * @param int $id
     * @return redirect
     */
    public function getMakeJobPublish($id)
    {
        // get the job object
        $job = $this->jobRepo->getJobById($id);
        // check if the required fields are filled
        if ($job->requirement != '' && $job->sector_id != 0 && $job->category_id != 0 && $job->job_applying != '' && $job->restrict == 'true') {
            // if yes
            // make the job published
            $data['restrict'] = null;
            $this->jobRepo->editJob($job, $data);
            // redirect back
            return redirect()->back()->with('message', 'The job is successfully published');
        } else {
            return redirect()->back()->with('error_danger', 'Warning! You can not publish this job until there are empty required fields. Please edit the job to fill required information.');
        }
    }

    /**
     * make the job unpublished
     * GET /admin/make-job-published/{id}
     * 
     * @param int $id
     * @return redirect
     */
    public function getMakeJobUnpublished($id) 
    {
        // get the job object
        $job = $this->jobRepo->getJobById($id);
        if ($job->restrict != '' || $job->restrict == null) {
            // if the job is not unpublished yet
            $data['restrict'] = 'true';
            // make it unpublished
            $this->jobRepo->editJob($job, $data);
            // redirect back
            return redirect()->back()->with('message', 'The job is successfully unpublished');
        } else {
            // if the job is unpublished already, return back with warning message
            return redirect()->back()->with('error_danger', 'The job is already Unpublished');
        }
    }

    public function getDetachJob($job_id)
    {
        $job = $this->jobRepo->getJobById($job_id);
        $data = ['company_id' => 0];
        $this->jobRepo->editJob($job, $data);
        return redirect()->back();
    }

    public function getCompanyJobs($company_id)
    {
        $jobs = $this->jobRepo->getCompanyJobs($company_id);
        $data = ['jobs' => $jobs];
        return $data;
    }

}