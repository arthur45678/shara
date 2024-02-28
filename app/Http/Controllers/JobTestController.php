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
	}

	/**
	 * get all jobs page
	 *
	 * @return view
	 */
	public function getJobs(Request $request, $sort, $type)
	{
		$logged_user = Sentinel::getUser();
		if($logged_user->hasAccess('job.view')) {

			$countries = $this->countryRepo->getAllCountries();
			$categories = $this->categoryRepo->getTranslations();
			$sectors = $this->sectorRepo->getTranslations();
			if($type == 'asc')
				$newType = 'desc';
			else
				$newType = 'asc';

			$searchDetails = $request->all();
			if(count($searchDetails) > 0)
			{
				$name = $request->job_search;
				$country = $request->country;
		    	if($country)
		    	{
		    		$country_object = $this->countryRepo->getCountryByName($country);
		    		$country_id = $country_object->id;
		    	}else{
		    		$country_id = '';
		    	}
		    	$city = $request->city;   	
		    	$category_id = $request->category;
		    	$sector_id = $request->industry;
				$jobs = $this->jobRepo->searchJob($name, $country_id, $city, $category_id, $sector_id, $sort, $type);

				$countries = $this->countryRepo->getAllCountries();
				$cities = $this->cityRepo->getAllCities();
				$categories = $this->categoryRepo->getTranslations();
				$sectors = $this->sectorRepo->getTranslations();
			}else{
				$jobs = $this->jobRepo->getAllJobsPaginate($sort, $type);
			}
			
			$data = [
				'jobs' => $jobs,
				'countries' => $countries,
				'categories' => $categories,
				'sectors' => $sectors,
				'type' => $newType
				];
			return view('admin.jobs', $data);
		}else {
			return redirect()->back();
		}
	}

	/**
	 * get create job page
	 *
	 * @return view
	 */
	public function getCreateJob()
	{
		$logged_user = Sentinel::getUser();
		if ($logged_user->hasAccess('job.create')) {
			$countries = $this->countryRepo->getAllCountries();
			$cities = $this->cityRepo->getAllCities();
			$categories = $this->categoryRepo->getTranslations();
			$companies = $this->companyRepo->getAllCompanies();
			$sectors = $this->sectorRepo->getTranslations();
			$data = [
				'countries' => $countries,
				'categories' => $categories, 
				'sectors' => $sectors,
				'companies' => $companies,
				'cities' => $cities
			];
			
		return view('admin.create_job', $data);
		} else {
			return redirect()->back();
		}
	}

	public function getCreateCompanyJob($company_id, $type)   
	{
		$logged_user = Sentinel::getUser();
		if($logged_user->hasAccess('job.create'))
		{

			$countries = $this->countryRepo->getAllCountries();
			$company = $this->companyRepo->getCompanyById($company_id);
			$job_applying = $company->job_applying;
			if($job_applying == '')
			{
				return redirect()->back()->with('jobApplyingMessage', 'Fill the job applying fieald to clone a job.');
			}
			$url_to_redirect = $company->url_to_redirect;
			$categories = $this->categoryRepo->getTranslations();
			$companies = $this->companyRepo->getAllCompanies();
			$sectors = $this->sectorRepo->getTranslations();
			$sector_id = $company->sector_id;
			$data = [
				'company_id' => $company_id,
				'sector_id' => $sector_id,
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
				$country_id = $company->country_id;
				$city_name = $company->city_name;
				// $city_object = $this->cityRepo->getCityById($city_id);
				// $city_name = $city_object->name;
				if($company->sub_type == 'country_subsidiary')
				{
					$data['country_id'] = $country_id;

				}
				if($company->sub_type == 'city_subsidiary'){
					$data['country_id'] = $country_id;
					$data['city_name'] = $city_name;
					$data['city_longtitude'] = $company->city_longtitude;
					$data['city_latitude'] = $company->city_latitude;
				}
				

				$cities = $this->companyRepo->getCompaniesByCountry($company->id, $country_id);
				$data['cities'] = $cities;
				$countries = [];
				if($company->type == 'generic')
				{
					
					$subsidiaries = $company->subsidiaries;
			    	$countries = [];
			    	foreach ($subsidiaries as $key => $value) {
			    		$country = $value->country;
			    		if(!in_array($country, $countries))
			    		{
			    			$countries[] = $country;
			    		}
			    		
			    	}
			    	$countries[] = $company->country;
					$countries = collect($countries);
					$data['countries'] = $countries;
				}else{
					$countries[] = $company->country;
					$countries = collect($countries);
					$data['countries'] = $countries;
				}
			return view('admin.create_job', $data);
		}else{
			return redirect()->back();
		}
		
	}

	/**
	 * create job
	 *
	 * @param JobCreateRequest $request
	 * @return redirect
	 */
	public function postCreateJob(JobCreateRequest $request)
	{
		if ($request->is_published != '') {
			$name = $request->name;
			$about_company = $request->about_company;
			$why_us = $request->why_us;
			$company_id = $request->company;
			//$companies = $request->companies;
			if($company_id == '')
			{
				$company_id = 0;
			}		
			if($why_us == '')
			{
				if($request->company !== '')
				{					
					$company = $this->companyRepo->getCompanyById($company_id);
					$why_us = $company->why_us;
				}else{
					$why_us = '';
				}
			}
			$region = $request->region;
			if($request->company !== '')
			{
				$company = $this->companyRepo->getCompanyById($company_id);
				$region = $company->region;
			}
			$benefits = $request->benefits; 
			$requirement = $request->requirement;
			$schedule = $request->schedule;
			$description = $request->description;
			$country_id = 0;
			$country = $request->country;
			if($country !== '')
			{
				$country_object = $this->countryRepo->getCountryByName($country);
				$country_abbreviation = $country_object->abbreviation;
				$country_id = $country_object->id; 
			}
			$city_id = 0;
			$city = $request->city; 
			$cityLongtitude = $request->city_longtitude;
			$cityLatitude = $request->city_latitude;
			
			$job_applying = $request->job_applying;
			$url_to_redirect = '';
			if($job_applying == 'redirect')
			{
				$url_to_redirect = $request->url_to_redirect;
				if($url_to_redirect == '')
				{
					return redirect()->back()->with('url_to_redirect_error', 'Please fill in the redirect url.');
				}
			}

			$sector_id = $request->sector;
			if($sector_id == '')
			{
				$sector_id = 0;
			}
			$category_id = $request->category;
			if($request->category == '')
			{
				$category_id = 0;
			}
			
			
			$activation = $request->activation;
	        if($activation)
	        {
	            $activation = 'activated';
	        }else{
	            $activation = 'deactivated';
	        }

	        if($country_id == 0 && $city_id == 0 && $sector_id == 0 && $company_id == 0)
	        {
	        	$type = 'generic';
	        }else {
	        	$type = 'specific';
	        }
	        $data = [
	        	'type' => $type,
	        	'name' => $name,
	        	'about_company' => $about_company,
	        	'why_us' => $why_us,
	        	'benefits' => $benefits,
	        	'requirement' => $requirement,
	        	'schedule' => $schedule,
	        	'country_id' => $country_id,
	        	'city_id' => 0,
	        	'city_name' => $city,
	        	'region' => $region,
	        	'city_longtitude' => $cityLongtitude,
	        	'city_latitude' => $cityLatitude,
	        	'sector_id' => $sector_id,
	        	'category_id' => $category_id,
	        	'job_applying' => $job_applying,
	        	'url_to_redirect' => $url_to_redirect,
	        	'activation' => $activation,
	        	'company_id' => $company_id,
	        	'description' => $description,
	        	'restrict' => 'true'
	        ];
		} else {
			$name = $request->name;
			$about_company = $request->about_company;
			$why_us = $request->why_us;
			$company_id = $request->company;
			//$companies = $request->companies;
			if($company_id == '')
			{
				$company_id = 0;
			}		
			if($why_us == '')
			{
				if($request->company !== '')
				{					
					$company = $this->companyRepo->getCompanyById($company_id);
					$why_us = $company->why_us;
				}else{
					$why_us = '';
				}
			}
			$region = $request->region;
			if($request->company !== '')
			{
				$company = $this->companyRepo->getCompanyById($company_id);
				$region = $company->region;
			}
			$benefits = $request->benefits; 
			$requirement = $request->requirement;
			$schedule = $request->schedule;
			$description = $request->description;
			$country_id = 0;
			$country = $request->country;
			if($country && $country !== '')
			{
				$country_object = $this->countryRepo->getCountryByName($country);
				$country_abbreviation = $country_object->abbreviation;
				$country_id = $country_object->id; 
			}
			$city_id = 0;
			$city = $request->city;
			 
			$cityLongtitude = $request->city_longtitude;
			$cityLatitude = $request->city_latitude;
			
			$sector_id = $request->sector;
			if($sector_id == '')
			{
				$sector_id = 0;
			}
			$category_id = $request->category;
			if($request->category == '')
			{
				$category_id = 0;
			}
			$job_applying = $request->job_applying;
			$url_to_redirect = '';
			if($job_applying == 'redirect')
			{
				$url_to_redirect = $request->url_to_redirect;
				if($url_to_redirect == '')
				{
					return redirect()->back()->with('url_to_redirect_error', 'Please fill in the redirect url.');
				}
			}
			$activation = $request->activation;
	        if($activation)
	        {
	            $activation = 'activated';
	        }else{
	            $activation = 'deactivated';
	        }

	        if($country_id == 0 && $city_id == 0 && $sector_id == 0 && $company_id == 0)
	        {
	        	$type = 'generic';
	        }else {
	        	$type = 'specific';
	        }
	        $data = [
	        	'type' => $type,
	        	'name' => $name,
	        	'about_company' => $about_company,
	        	'why_us' => $why_us,
	        	'benefits' => $benefits,
	        	'requirement' => $requirement,
	        	'schedule' => $schedule,
	        	'country_id' => $country_id,
	        	'city_id' => 0,
	        	'city_name' => $city,
	        	'region' => $region,
	        	'city_longtitude' => $cityLongtitude,
	        	'city_latitude' => $cityLatitude,
	        	'sector_id' => $sector_id,
	        	'category_id' => $category_id,
	        	'job_applying' => $job_applying,
	        	'url_to_redirect' => $url_to_redirect,
	        	'activation' => $activation,
	        	'company_id' => $company_id,
	        	'description' => $description
	        ];
	    }

	        $job = $this->jobRepo->createJob($data);
	        if($request->main_company)
	        {
	        	$mainId = $request->main_company;
	        	$companyType = $request->company_type;
	        	
	        	return redirect()->action('Admin\CompanyController@getEditCompany', [$mainId, $companyType])->with('message', 'New job has been successfully added.');
	        }else{
	        	return redirect()->action('Admin\JobController@getJobs', ['date', 'asc'])->with('message', "The job has been successfully added");;

	        }
	}

	/**
	 * get edit job page
	 *
	 * @param int $job_id
	 * @param view
	 */
	public function getEditJob($job_id)
	{
		$logged_user = Sentinel::getUser();
		if($logged_user->hasAccess('job.update')) {
			$job = $this->jobRepo->getJobById($job_id);
			$job_country = $job->country;
			$country_cities = '';
			if($job_country)
			{
				$country_cities = $job_country->cities;
			}
			
			$countries = $this->countryRepo->getAllCountries();
			$categories = $this->categoryRepo->getTranslations();
			$sectors = $this->sectorRepo->getTranslations();
			$companies = $this->companyRepo->getAllCompanies();
			$data = [
				'job' => $job,
				'job_country' => $job_country,
				'country_cities' => $country_cities,
				'countries' => $countries,
				'categories' => $categories,
				'sectors' => $sectors,
				'companies' => $companies
			];
			if($job->company)
			{
				$cities = $this->companyRepo->getCompaniesByCountry($job->company->name, $job->country_id);
			    $countries = [];

				if($job->company->type == 'generic')
				{

					$subsidiaries = $job->company->subsidiaries;
			    	foreach ($subsidiaries as $key => $value) {
			    		$country = $value->country;
			    		if(!in_array($country, $countries))
			    		{
			    			$countries[] = $country;
			    		}
			    		
			    	}
			    	$countries[] = $job->company->country;
					
				}else{
					$generic = $job->company->generic;
					$subsidiaries = $generic->subsidiaries;
					if($subsidiaries){
						foreach ($subsidiaries as $key => $value) {
			    		$country = $value->country;
			    		if(!in_array($country, $countries))
			    		{
			    			$countries[] = $country;
			    		}
			    		
			    	}
					}
				}
				$countries = collect($countries);
				$data['cities'] = $cities;
				$data['countries'] = $countries;
			}
			
			return view('admin.edit_job', $data);
		}else {
			return redirect()->back();
		}
	}

	/**
	 * edit job
	 *
	 * @param Request $request
	 * @return redirect
	 */
	public function postEditJob(JobEditRequest $request)
	{
		$job_id = $request->job_id;
		$job = $this->jobRepo->getJobById($job_id);
		$name = $request->name;
		$about_company = $request->about_company;
		$why_us = $request->why_us;
		$benefits = $request->benefits;
		$requirement = $request->requirement;
		$schedule = $request->schedule;
		$description = $request->description;
		// $country_id = $request->country;
		// if($country_id == 'Select Country')
		// {
		// 	$country_id = 0;
		// }
		$country_id = 0;
		$country = $request->country;

		if($country !== 'Select Country' && $country != null)
		{
			$country_object = $this->countryRepo->getCountryByName($country);
			// $country_abbreviation = $country_object->abbreviation;
			$country_id = $country_object->id;
		}
		$city = $request->city;
		$cityLongtitude = $request->city_longtitude;
		$cityLatitude = $request->city_latitude;
		$region = $request->region;
		// if($city)
		// {
		// 	$city_object = $this->cityRepo->getCityByName($city);
		// 	$city_id = $city_object->id;
		// }
		
		$sector_id = $request->sector;
		if($sector_id == '')
		{
			$sector_id = 0;
		}
		$category_id = $request->category;
		if($category_id == '')
		{
			$category_id = 0;
		}
		$company_id = $request->company;
		if($company_id == '')
		{
			$company_id = 0;
		}
		$job_applying = $request->job_applying;
		$url_to_redirect = '';
		if($job_applying == 'redirect')
		{
			$url_to_redirect = $request->url_to_redirect;
			if($url_to_redirect == '')
			{
				return redirect()->back()->with('url_to_redirect_error', 'Please fill in the redirect url.');
			}
		}
		$activation = $request->activation;
        if($activation)
        {
            $activation = 'activated';
        }else{
            $activation = 'deactivated';
        }

        $data = [
        	'name' => $name,
        	'about_company' => $about_company,
        	'why_us' => $why_us,
        	'benefits' => $benefits,
        	'requirement' => $requirement,
        	'schedule' => $schedule,
        	'country_id' => $country_id,
        	'city_name' => $city,
        	'region' => $region,
        	'city_longtitude' => $cityLongtitude,
        	'city_latitude' => $cityLatitude,
        	'sector_id' => $sector_id,
        	'category_id' => $category_id,
        	'job_applying' => $job_applying,
        	'url_to_redirect' => $url_to_redirect,
        	'activation' => $activation,
        	'company_id' => $company_id,
        	'description' => $description
        ];
        if ($request->is_published != '') {
        	$data['restrict'] = 'true';
        } else {
        	$data['restrict'] = null;
        }
        $job = $this->jobRepo->editJob($job, $data);
        // if($data['restrict'] != true)
        // {
        // 	return redirect()->action('Admin\JobController@getJobs', ['date', 'asc'])->with('message', 'Your changes have been successfully applied');
        // }else{
        // 	return redirect()->action('Admin\JobController@getJobs', ['date', 'asc']);
        // }
        return redirect()->back()->with('message', 'Your changes have been successfully applied');
        
	}

	/**
	 * get show job page
	 *
	 * @param int $job_id
	 * @return view
	 */
	public function getShowJob($job_id)
	{
		$logged_user = Sentinel::getUser();
		if($logged_user->hasAccess('job.view')) {
			$job = $this->jobRepo->getJobById($job_id);
			$data = ['job' => $job];
			if($job->country)
			{
				$country = $job->country->name;
				$data['country'] = $country;
			}
			if($job->city_name)
			{
				$city = $job->city_name;
				$data['city'] = $city;
				
			}
			if($job->sector)
			{
				$sector = $job->sector->name;
				$data['sector'] = $sector;
			}
			if($job->category)
			{
				$category = $job->category->name;
				$data['category'] = $category;
			}
			
			return view('admin.show_job', $data);
		}else {
			return redirect()->back();
		}
	}

	/**
	 * delete a job
	 *
	 * @param int $job_id
	 * @return redirect
	 */
	public function getDeleteJob($job_id)
	{
		$logged_user = Sentinel::getUser();
		if($logged_user->hasAccess('job.delete')) {
			$job = $this->jobRepo->getJobById($job_id);
			$this->jobRepo->deleteJob($job);
			return redirect()->back();
		}else {
			return redirect()->back();
		}
	}

	public function postSearchJob(SearchRequest $request)
	{
		$name = $request->job_search;
		$country = $request->country;
    	if($country)
    	{
    		$country_object = $this->countryRepo->getCountryByName($country);
    		$country_id = $country_object->id;
    	}else{
    		$country_id = '';
    	}
    	$city = $request->city;   	
    	$category_id = $request->category;
    	$sector_id = $request->industry;
		$results = $this->jobRepo->searchJob($name, $country_id, $city, $category_id, $sector_id);

		$countries = $this->countryRepo->getAllCountries();
		$cities = $this->cityRepo->getAllCities();
		$categories = $this->categoryRepo->getTranslations();
		$sectors = $this->sectorRepo->getTranslations();
		$data = [
			'search_results' => $results,
			'companies' => $companies,
			'countries' => $countries,
			// 'cities' => $cities,
			'categories' => $categories,
			'sectors' => $sectors
			];
		return view('admin.search_results_job', $data);
	}

	public function postCloneJob(Request $request)
	{
		$job_from = $this->jobRepo->getJobById($request->job_from);
		$company_id = $request->parent_id;
		$company = $this->companyRepo->getCompanyById($company_id);
		$sector_id = $job_from->sector_id;
		$country_id = 0;
		$city_name = '';
		if($company->type == 'subsidiary')
		{
			$country_id = $company->country_id;
		}
		if($company->sub_type == 'city_subsidiary')
		{
			$city_name = $company->city_name;
		}
		$category_id = $job_from->category_id;
		$name = $job_from->name;
		$about_company = $job_from->about_company;
		$why_us = $job_from->why_us;
		$benefits = $job_from->benefits;
		$requirement = $job_from->requirement;
		$schedule = $job_from->schedule;
		$type = $job_from->type;
		$job_applying = $company->job_applying;
		$url_to_redirect = $company->url_to_redirect;
		if($job_applying == '')
		{
			if($company->type == 'generic')
			{
				return redirect()->back()->with('jobApplyingMessage', 'Fill the job applying fieald to clone a job.');

			}elseif($company->type == 'subsidiary'){
				$parentCompany = $company->generic;
				$job_applying = $parentCompany->job_applying;
				$url_to_redirect = $parentCompany->url_to_redirect;
			}
		}
		$activation = $job_from->activation;
		$data = [
			'company_id' => $company_id,
			'sector_id' => $sector_id,
			'category_id' => $category_id,
			'country_id' => 0,
			'city_name' => $city_name,
			'city_latitude' => $company->city_latitude,
			'city_longtitude' => $company->city_longtitude,
			'region' => $company->region,
			'name' => $name,
			'about_company' => $about_company,
			'why_us' => $why_us,
			'benefits' => $benefits,
			'requirement' => $requirement,
			'schedule' => $schedule,
			'type' => $type,
			'job_applying' => $job_applying,
			'url_to_redirect' => $url_to_redirect,
			'activation' => $activation,
			'city_id' => 0
		];
		$job = $this->jobRepo->createJob($data);
		return redirect()->action('Admin\JobController@getEditJob', $job->id);
		// return redirect()->back();

	}

	public function getDetachJob($job_id)
	{

		$job = $this->jobRepo->getJobById($job_id);
		$data = ['company_id' => 0];
		$this->jobRepo->editJob($job, $data);
		return redirect()->back();
	}

	public function getMakeJobPublish($id)
	{
		$job = $this->jobRepo->getJobById($id);
		if ($job->requirement != '' && $job->sector_id != 0 && $job->category_id != 0 && $job->job_applying != '' && $job->restrict == 'true') {
			$data['restrict'] = null;
			$this->jobRepo->editJob($job, $data);
			return redirect()->back()->with('error', 'The job is successfully published');
		} else {
			return redirect()->back()->with('error_danger', 'Warning! You cant publish this job until there are empty required fields. Please edit the job to fill required information.');
		}
	}

	public function getMakeJobUnpublished($id) 
	{
		$job = $this->jobRepo->getJobById($id);
		if ($job->restrict != '' || $job->restrict == null) {
			$data['restrict'] = 'true';
			$this->jobRepo->editJob($job, $data);
			return redirect()->back()->with('error', 'The job is successfully unpublished');
		} else {
			return redirect()->back()->with('error_danger', 'The job is already Unpublished');
		}
	}
}
