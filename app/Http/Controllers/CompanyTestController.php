<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Contracts\CountryInterface;
use App\Contracts\CityInterface;
use App\Contracts\CategoryInterface;
use App\Contracts\CompanyInterface;
use App\Contracts\SectorInterface;
use App\Contracts\JobInterface;
use App\Http\Requests\CompanyCreateRequest;
use App\Http\Requests\CompanyEditRequest;
use App\Http\Requests\SubsidiaryEditRequest;
use App\Http\Requests\SubsidiaryCreateRequest; 
use App\Http\Requests\SubsidiaryCityAddRequest;
use App\Http\Requests\SubsidiaryCloneRequest;
use App\Http\Requests\SearchRequest;
use Sentinel;
use Illuminate\Validation\Rule;
use Validator;

class CompanyController extends Controller
{
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
     * Object of JobInterface class
     *
     * @var jobRepo
     */
    private $jobRepo;

    /** 
     * Create a new instance of Controller class.
     *
     * @param CompanyInterface $companyRepo
     * @return void
     */
	public function __construct(CompanyInterface $companyRepo, CountryInterface $countryRepo, CityInterface $cityRepo, CategoryInterface $categoryRepo, SectorInterface $sectorRepo, JobInterface $jobRepo)
	{
		$this->companyRepo = $companyRepo;
		$this->countryRepo = $countryRepo;
		$this->cityRepo = $cityRepo;
		$this->categoryRepo = $categoryRepo;
		$this->sectorRepo = $sectorRepo;
		$this->jobRepo = $jobRepo;
		$this->middleware("admin");
	}

	/**
	 * get all generic companies list page
	 *
	 * @return view
	 */
	public function getCompanies($sort, $type, Request $request)
	{
		$logged_user = Sentinel::getUser();
		if($logged_user->hasAccess('company.view')) {
			$searchDetails = $request->all();
			if($type == 'asc')
				$newType = 'desc';
			else
				$newType = 'asc';

			$countries = $this->countryRepo->getAllCountries();
			$cities = $this->cityRepo->getAllCities();
			$categories = $this->categoryRepo->getTranslations();
			$sectors = $this->sectorRepo->getTranslations();

			if(count($searchDetails) > 0){
				$name = $request->company_search;
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
				$companies = $this->companyRepo->searchCompany($request->old_results, $name, $country_id, $city, $category_id, $sector_id, $sort, $type);

			}else{
				
					$companies = $this->companyRepo->getAllGenericsPaginate($sort, $type); 
			}

			$data = [
					'companies' => $companies,
					'countries' => $countries,
					'cities' => $cities,
					'categories' => $categories,
					'sectors' => $sectors, 
					'type' => $newType,
					'searchDetails' => $searchDetails
					];
			return view('admin.companies', $data);
		}else{
			return redirect()->back();
		}

	}

	/**
	 * get create company page
	 *
	 * @return view
	 */
	public function getCreateCompany()
	{
		$logged_user = Sentinel::getUser();
		if($logged_user->hasAccess('company.create')) {
			$countries = $this->countryRepo->getAllCountries();
			//$cities = $this->cityRepo->getAllCities();
			$categories = $this->categoryRepo->getTranslations();
			$sectors = $this->sectorRepo->getTranslations();
			$jobs = $this->jobRepo->getAllJobs();
			$data = [
				'countries' => $countries,
				'categories' => $categories,
				'sectors' => $sectors,
				'jobs' => $jobs
			];
			return view('admin.create_company', $data);
		}else{
			return redirect()->back();
		}
	}

	/**
	 * creating company
	 *
	 * @param CompanyCreateRequest $request
	 * @return redirect
	 */
	public function postCreateCompany(CompanyCreateRequest $request)
	{
		// $file = $request->logo; 
		// if($file)
		// {
		// 	$destinationPath = public_path().'/uploads';
	 //        $extension = $file->getClientOriginalExtension();
	 //        $fileName = str_random(8).'.'.$extension;
	 //        $file->move($destinationPath, $fileName);
		// }
        
        // $data=[
        //     'name' => $fileName
        // ];
       if ($request->is_published != '') {
       		$name = $request->name;
			$type = $request->type;
			if($type == 'generic')
			{
				$parent_id = 0;
			}
			
			$url = $request->url;
			$description = $request->description;
			$short_description = $request->short_description;
			$facebook_url = $request->facebook_url;
			$linkedin_url = $request->linkedin_url;
			$twitter_url = $request->twitter_url;
			$crunchbase_url = $request->crunchbase_url;
			$ios_url = $request->ios_url;
			$android_url = $request->android_url;
			if ($request->country != '') {
				$country = $request->country;
			} else {
				$country = 'China';
			}
			$cityName = $request->city_name;
			$region = $request->region;
			$cityLongtitude =$request->city_longtitude;
			$cityLatitude =$request->city_latitude;
			$cityPopulation =$request->city_population;
			$country_object = $this->countryRepo->getCountryByName($country);
			if($country_object)
			{
				$country_id = $country_object->id;
			}else{
				$country_id = 1;

			}
			$logo = $request->logo_name;
			
			// $sector = $request->sector;
			// $sector_object = $this->sectorRepo->getSectorByName($sector);
			if ($request->industry != '') {
				$sector_id = $request->industry;
			} else {
				$sector_id = 0;
			}
			if ($request->category != '') {
			
				$category_id = $request->category;
			} else {

				$category_id = 0;
			}
			
			// $category = $request->category;
			// $category_object = $this->categoryRepo->getCategoryByName($category);
			$looking_for = $request->looking_for;
			$requirement = $request->requirement;
			$compensation = $request->compensation;
			$why_us = $request->why_us;
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
			$data = [
				'name' => $name,
				'parent_id' => $parent_id,
				'url' => $url,
				'description' => $description,
				'short_description' => $short_description,
				'facebook_url' => $facebook_url,
				'linkedin_url' => $linkedin_url,
				'twitter_url' => $twitter_url,
				'crunchbase_url' => $crunchbase_url,
				'ios_url' => $ios_url,
				'android_url' => $android_url,
				'country_id' => $country_id,
				'city_name' => $cityName,
				'region' => $region,
				'city_longtitude' => $cityLongtitude,
				'city_latitude' => $cityLatitude,
				'city_population' => $cityPopulation,
				'sector_id' => $sector_id,
				'category_id' => $category_id,
				'looking_for' => $looking_for,
				'requirement' => $requirement,
				'compensation' => $compensation,
				'why_us' => $why_us,
				'job_applying' => $job_applying,
				'url_to_redirect' => $url_to_redirect,
				'restrict' => 'true'
			];

			// $city = $request->city;
			// if($city)
			// {
			// 	$city_object = $this->cityRepo->getCityByName($city);
			// 	$city_id = $city_object->id;
			// 	$data['city_id'] = $city_id;
			// }

			// $logo = $request->image;
			$logo_url = $request->logo_url;

			if($logo && $logo_url !== '')
			{
				$data['logo'] = $logo;
			}elseif($logo == null || $logo == ''){
				if($logo_url !== '' && $logo_url !== null)
				{
			        if(@GetImageSize($logo_url))
			        {
			        	$extension = pathinfo($logo_url, PATHINFO_EXTENSION);
			        	$fileNameUrl = str_random(8).'.'.$extension;
				        $file = file_get_contents($logo_url);
				        $save = file_put_contents('uploads/'.$fileNameUrl, $file);
				        $data['logo'] = $fileNameUrl;
			        }else{
			        	return redirect()->back()->withInputs()->with('invalidUrl', 'Invalid image url.');
			        }
			        
				}
			}elseif($logo != null && $logo != ''){
				$data['logo'] = $logo;
			}
        } else { 
			$name = $request->name;
			$type = $request->type;
			if($type == 'generic')
			{
				$parent_id = 0;
			}
			
			$url = $request->url;
			$description = $request->description;
			$short_description = $request->short_description;
			$facebook_url = $request->facebook_url;
			$linkedin_url = $request->linkedin_url;
			$twitter_url = $request->twitter_url;
			$crunchbase_url = $request->crunchbase_url;
			$ios_url = $request->ios_url;
			$android_url = $request->android_url;
			$country = $request->country;
			$cityName = $request->city_name;
			$region = $request->region;
			$cityLongtitude =$request->city_longtitude;
			$cityLatitude =$request->city_latitude;
			$cityPopulation =$request->city_population;
			$country_object = $this->countryRepo->getCountryByName($country);
			$country_id = $country_object->id;
			$logo = $request->logo_name;
			
			// $sector = $request->sector;
			// $sector_object = $this->sectorRepo->getSectorByName($sector);
			$sector_id = $request->industry;
			// $category = $request->category;
			// $category_object = $this->categoryRepo->getCategoryByName($category);
			$category_id = $request->category;
			$looking_for = $request->looking_for;
			$requirement = $request->requirement;
			$compensation = $request->compensation;
			$why_us = $request->why_us;
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
			$data = [
				'name' => $name,
				'parent_id' => $parent_id,
				'url' => $url,
				'description' => $description,
				'short_description' => $short_description,
				'facebook_url' => $facebook_url,
				'linkedin_url' => $linkedin_url,
				'twitter_url' => $twitter_url,
				'crunchbase_url' => $crunchbase_url,
				'ios_url' => $ios_url,
				'android_url' => $android_url,
				'country_id' => $country_id,
				'city_name' => $cityName,
				'region' => $region,
				'city_longtitude' => $cityLongtitude,
				'city_latitude' => $cityLatitude,
				'city_population' => $cityPopulation,
				'sector_id' => $sector_id,
				'category_id' => $category_id,
				'looking_for' => $looking_for,
				'requirement' => $requirement,
				'compensation' => $compensation,
				'why_us' => $why_us,
				'job_applying' => $job_applying,
				'url_to_redirect' => $url_to_redirect,
			];

			// $city = $request->city;
			// if($city)
			// {
			// 	$city_object = $this->cityRepo->getCityByName($city);
			// 	$city_id = $city_object->id;
			// 	$data['city_id'] = $city_id;
			// }

			// $logo = $request->image;
			$logo_url = $request->logo_url;

			if($logo && $logo_url !== '')
			{
				$data['logo'] = $logo;
			}elseif($logo == null || $logo == ''){
				if($logo_url !== '' && $logo_url !== null)
				{
			        if(@GetImageSize($logo_url))
			        {
			        	$extension = pathinfo($logo_url, PATHINFO_EXTENSION);
			        	$fileNameUrl = str_random(8).'.'.$extension;
				        $file = file_get_contents($logo_url);
				        $save = file_put_contents('uploads/'.$fileNameUrl, $file);
				        $data['logo'] = $fileNameUrl;
			        }else{
			        	return redirect()->back()->withInputs()->with('invalidUrl', 'Invalid image url.');
			        }
			        
				}
			}elseif($logo != null && $logo != ''){
				$data['logo'] = $logo;
			}
		}
		
		$company = $this->companyRepo->createCompany($data);
		return redirect()->action('Admin\CompanyController@getCompanies', ['date', 'asc']);

	}

	/**
	 * image upload function
	 *
	 * @param Request $request
	 * @return response
	 */
	public function postFileUpload(Request $request)
    {
    	
        $file = $request->file('file');
        $destinationPath = public_path().'/uploads';
        $extension = $file->getClientOriginalExtension();
        $fileName = str_random(8).'.'.$extension;
        $file->move($destinationPath, $fileName);
        $data=[
            'name' => $fileName
        ];
        return response()->json($data);
    }

    /**
     * edit company page
     * 
     * @param int $company_id 
     * @return view
     */
    public function getEditCompany($company_id, $company_sub_type)
    {
    	$logged_user = Sentinel::getUser();
		if($logged_user->hasAccess('company.update')) {
				$company = $this->companyRepo->getCompanyById($company_id);
				$company_country = $company->country;
				$country_cities = $company_country->cities;
				$countries = $this->countryRepo->getAllCountries();
				$categories = $this->categoryRepo->getTranslations();
				$sectors = $this->sectorRepo->getTranslations();
				$jobs = $this->jobRepo->getAllJobs();
				$count = 0;
				// $data = [
				// 	'company' => $company,
				//     'country_cities' => $country_cities,
				//     'countries' => $countries,
				//     'sectors' => $sectors,
				//     'categories' => $categories,
				//     'count' => $count];
			 //    if($company->type == 'subsidiary')
				// {
				// 	foreach($company->generic->subsidiaries as $subsidiary)
				// 	{ 
				// 		if($subsidiary->city && $subsidiary->country->name == $company_country->name && $subsidiary->id !== $company->id)
				// 		{
				// 			$count++;
				// 		}
				// 	}
				// 	$data['count'] = $count;

				// }
				// if($company->type == 'generic')
				// {
					
				// }
				if($company_sub_type == 'generic')
				{
					$data = [
						'company' => $company,
					    // 'country_cities' => $country_cities,
					    'countries' => $countries,
					    'sectors' => $sectors,
					    'categories' => $categories,
					    'count' => $count,
					    'jobs' => $jobs
					    ];

					    $country_subsidiaries = [];
						$unique_subsidiaries = [];
						if(count($company->subsidiaries) > 0)
						{
							foreach($company->subsidiaries as $subsidiary)
							{
								if($subsidiary->country){
									if(!in_array($subsidiary->country->name, $country_subsidiaries)){
										$country_subsidiaries[] = $subsidiary->country->name;
										$unique_subsidiaries[] = $subsidiary;
									}
								}
								
							}
						}
					$data['uniquie_subsidiaries'] = $unique_subsidiaries;
					return view('admin.edit_company', $data);
				}elseif($company_sub_type == 'sub'){
					foreach($company->generic->subsidiaries as $subsidiary)
					{ 
						// dd($subsidiary);
						if($subsidiary->city_name && $subsidiary->country && $subsidiary->country->name == $company_country->name && $subsidiary->id !== $company->id)
						{
							$count++;
						}
					}
					$name = $company->name;
					if (strpos($name, '/') !== false) {
					    $name = explode('/',$name)[0];
					} elseif (strpos($name, '-') !== false) {
						$name = explode('-',$name)[0];
					}
					$data = [
						'company' => $company,
					    'country_cities' => $country_cities,
					    'countries' => $countries,
					    'sectors' => $sectors,
					    'categories' => $categories,
					    'count' => $count,
					    'jobs' => $jobs,
					    'name' => $name
					    ];
					return view('admin.edit_subsidiary', $data);
				}elseif($company_sub_type == 'sub_city')
				{

					$name = $company->name;
					if (strpos($name, '/') !== false) {
					    $name = explode('/',$name)[0];
					} elseif (strpos($name, '-') !== false) {
						$name = explode('-',$name)[0];
					}
					$countryCompany = $this->companyRepo->countrySubsidiary($company_country->id, $company->generic->id);
					$data = [
						'company' => $company,
						'jobs' => $jobs,
						'countryCompany' => $countryCompany
					    ];
					return view('admin.edit_subsidiary_city', $data);
				}
				
		}else{
			return redirect()->back();
		}
    }

    /**
     * edit company
     *
     * @param CompanyEditRequest $request
     */
    public function postEditCompany(CompanyEditRequest $request)
    {
    	// dd($request->logo, $request->logo_name, $request->logo_url);
  //   	$file = $request->logo;
		// if($file)
		// {
		// 	$destinationPath = public_path().'/uploads';
	 //        $extension = $file->getClientOriginalExtension();
	 //        $fileName = str_random(8).'.'.$extension;
	 //        $file->move($destinationPath, $fileName);

		// }
		// dd($request->all());
		$logo = $request->logo_name;
    	$name = $request->name;
		$url = $request->url;
		$description = $request->description;
		$short_description = $request->short_description;
		$facebook_url = $request->facebook_url;
		$linkedin_url = $request->linkedin_url;
		$twitter_url = $request->twitter_url;
		$crunchbase_url = $request->crunchbase_url;
		$ios_url = $request->ios_url;
		$android_url = $request->android_url;
		if ($request->country == '') {
			$country = 'China';
		} else {
			$country = $request->country;
		}
		
		$cityName = $request->city_name;
		$region = $request->region;
		$cityLongtitude =$request->city_longtitude;
		$cityLatitude =$request->city_latitude;
		$cityPopulation =$request->city_population;
		$country_object = $this->countryRepo->getCountryByName($country);
		if($country_object)
		{
			$country_id = $country_object->id;
		}else{
			$country_id = 1;
		}
		// $city = $request->city;
		// if($city)
		// {
		// 	$city_object = $this->cityRepo->getCityByName($city);
		// 	$city_id = $city_object->id;
		// }else{
		// 	$city_id = 0;
		// }
		
		// $sector = $request->sector;
		// $sector_object = $this->sectorRepo->getSectorByName($sector);
		$sector_id = $request->industry;
		// $category = $request->category;
		// $category_object = $this->categoryRepo->getCategoryByName($category);
		$category_id = $request->category;
		$looking_for = $request->looking_for;
		$requirement = $request->requirement;
		$compensation = $request->compensation;
		$why_us = $request->why_us;
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

		$data = [
			'name' => $name,
			'url' => $url,
			'description' => $description,
			'short_description' => $short_description,
			'facebook_url' => $facebook_url,
			'linkedin_url' => $linkedin_url,
			'twitter_url' => $twitter_url,
			'crunchbase_url' => $crunchbase_url,
			'ios_url' => $ios_url,
			'android_url' => $android_url,
			'country_id' => $country_id,
			// 'city_id' => $city_id,
			'city_name' => $cityName,
			'region' => $region,
			'city_longtitude' => $cityLongtitude,
			'city_latitude' => $cityLatitude,
			'city_population' => $cityPopulation,
			'sector_id' => $sector_id,
			'category_id' => $category_id,
			'looking_for' => $looking_for,
			'requirement' => $requirement,
			'compensation' => $compensation,
			'why_us' => $why_us,
			'job_applying' => $job_applying,
			'url_to_redirect' => $url_to_redirect
		];
		if ($request->is_published) {
			if ($request->industry == '') {
				$data['sector_id'] = 0;
			} else {
				$data['sector_id'] = $sector_id;
			}
			if ($request->category == '') {
				$data['category_id'] = 0;
			} else {
				$data['category_id'] = $category_id;
			}
		}
		if ($request->is_published != '') {
    		$data['restrict'] = 'true';
    	} else {
    		$data['restrict'] = null;
    	}
		// $logo = $request->image;
		$logo_url = $request->logo_url;
		if($logo && $logo_url !== '')
		{
			$data['logo'] = $logo;
		}elseif($logo == '' || $logo == null){
			if($logo_url !== null && $logo_url !== '')
			{
		        if(@GetImageSize($logo_url))
		        {
		        	$extension = pathinfo($logo_url, PATHINFO_EXTENSION);
		        	$fileNameUrl = str_random(8).'.'.$extension;
			        $file = file_get_contents($logo_url);
			        $save = file_put_contents('uploads/'.$fileNameUrl, $file);

			        $data['logo'] = $fileNameUrl;

		        }else{
		        	return redirect()->back()->withInput()->with('invalidUrl', 'Invalid image url.');
		        }
			}elseif($logo_url == null && $logo_url == ''){
				$data['logo'] = '';
			}
		}elseif($logo != '' && $logo != null){
			$data['logo'] = $logo;
		}
		$company_id = $request->company_id;
		$company = $this->companyRepo->getCompanyById($company_id);
		if($company->type == 'generic')
		{
			$data['parent_id'] = 0;
		}
		$company_updated = $this->companyRepo->editCompany($company, $data);

		//EDIT SUBSIDIARY COMPANIES

		$subsidiaries = $company->subsidiaries;
		foreach($subsidiaries as $sub)
		{
			if($sub->sub_type == 'country_subsidiary')
			{
				// $subData['name'] = $company->name.' / '.$sub->country->name;
				$subData['sector_id'] = $company->sector_id;
				$subData['category_id'] = $company->category_id;
			}elseif($sub->sub_type == 'city_subsidiary'){
				// $subData['name'] = $company->name.' / '.$sub->country->name.' / '.$sub->city_name;
				$subData['sector_id'] = $company->sector_id;
				$subData['category_id'] = $company->category_id;
				
			}else{
				// $subData['name'] = $sub->name;
				$subData['sector_id'] = $company->sector_id;
				$subData['category_id'] = $company->category_id;
			}
			// $subData['description'] = $company->description;
			// $subData['short_description'] = $company->short_description;
			// $subData['why_us'] = $company->why_us;
			// $subData['requirement'] = $company->requirement;
			// $subData['looking_for'] = $company->looking_for;
			// $subData['compansation'] = $company->compansation;
			$subsidiaryUpdate = $this->companyRepo->editCompany($sub, $subData);
		
		}
		// if($data['restrict'] != 'true')
		// {
		// 	return redirect()->action('Admin\CompanyController@getCompanies', ['date', 'asc'])->with('message', 'Your changes have been successfully applied');
		// }else{
		// 	return redirect()->action('Admin\CompanyController@getCompanies', ['date', 'asc']);
		// }
		return redirect()->back()->with('message', 'Your changes have been successfully applied');
    }

    /**
     * edit company
     *
     * @param CompanyEditRequest $request
     */
    public function postEditSubsidiary(SubsidiaryEditRequest $request)
    {
    	//dd($request->all());
		$logo = $request->logo_name;
    	$company_id = $request->company_id;
		$company = $this->companyRepo->getCompanyById($company_id);
    	$cityName = $request->city_name;
    	$parent_company = $company->generic;
		$cityLongtitude = $request->city_longtitude;
		$cityLatitude = $request->city_latitude;
		$region = $request->region;
		$url = $request->url;
		if(!$url)
		{
			$url = '';
		}
		$description = $request->description;
		// if(!$description)
		// {
		// 	$description = '';
		// }
		$short_description = $request->short_description;
		// if($short_description == '')
		// {
		// 	$short_description = $company->short_description;
		// }
		$facebook_url = $request->facebook_url;
		if($facebook_url == null)
		{
			$facebook_url = '';
		}
		$linkedin_url = $request->linkedin_url;
		if($linkedin_url == null)
		{
			$linkedin_url = '';
		}
		$twitter_url = $request->twitter_url;
		if($twitter_url == null)
		{
			$twitter_url = '';
		}
		$crunchbase_url = $request->crunchbase_url;
		if($crunchbase_url == null)
		{
			$crunchbase_url = '';
		}
		$ios_url = $request->ios_url;
		if($ios_url == null)
		{
			$ios_url = '';
		}
		$android_url = $request->android_url;
		if($android_url == null)
		{
			$android_url = '';
		}
		$country = $request->country;

		$country_object = $this->countryRepo->getCountryByName($country);
		$country_id = $country_object->id;
		
		
		// $sector = $request->sector;
		// $sector_object = $this->sectorRepo->getSectorByName($sector);
		$sector_id = $request->industry;
		if($sector_id == '')
		{
			$sector_id = 0;
		}
		// $category = $request->category;
		// $category_object = $this->categoryRepo->getCategoryByName($category);
		$category_id = $request->category;
		if($category_id == '')
		{
			$category_id = $company->category_id;
		}
		$looking_for = $request->looking_for;
		// if($looking_for == '')
		// {
		// 	$looking_for = $company->looking_for;
		// }
		$requirement = $request->requirement;
		// if($requirement == '')
		// {
		// 	$requirement = $company->requirement;
		// }
		$compensation = $request->compensation;
		// if($compensation == '')
		// {
		// 	$compensation = $company->compensation;
		// }
		$why_us = $request->why_us;
		// if($why_us == '')
		// {
		// 	$why_us = $company->why_us;
		// }
		$job_applying = $request->job_applying;
		if($job_applying)
		{
			$url_to_redirect = '';
			if($job_applying == 'redirect')
			{
				$url_to_redirect = $request->url_to_redirect;
				if($url_to_redirect == '')
				{
					return redirect()->back()->with('url_to_redirect_error', 'Please fill in the redirect url.');
				}
			}
		}else{
			$job_applying = $company->job_applying;
			$url_to_redirect = '';
			if($job_applying == 'redirect')
			{
				$url_to_redirect = $request->url_to_redirect;
				if($url_to_redirect == '')
				{
					return redirect()->back()->with('url_to_redirect_error', 'Please fill in the redirect url.');
				}
			}
		}
		$name = $request->name.'/'.$country_object->name;


		$data = [
			'name' => $name,
			'url' => $url,
			'description' => $description,
			'short_description' => $short_description,
			'facebook_url' => $facebook_url,
			'linkedin_url' => $linkedin_url,
			'twitter_url' => $twitter_url,
			'crunchbase_url' => $crunchbase_url,
			'ios_url' => $ios_url,
			'android_url' => $android_url,
			'country_id' => $country_id,
			'city_name' => $cityName,
			'region' => $region,
			'city_longtitude' => $cityLongtitude,
			'city_latitude' => $cityLatitude,
			'sector_id' => $company->generic->sector_id,
			'category_id' => $company->generic->category_id,
			'looking_for' => $looking_for,
			'requirement' => $requirement,
			'compensation' => $compensation,
			'why_us' => $why_us,
			'job_applying' => $job_applying,
			'url_to_redirect' => $url_to_redirect,
			'type' => $company->type,
			'sub_type' => $company->sub_type,
		];

		if($company->sub_type == 'country_subsidiary')
		{
	    	$generic = $company->generic;
			// $citySubsidiaries = $this->companyRepo->getCitySubsidiaries($country_id, $company->parent_id);
			// $mustHave = ['job_applying', 'url_to_redirect', 'looking_for', 'requirement', 'compensation', 'why_us', 'description', 'short_description'];
			// $subData = [];
	  //   	foreach($mustHave as $mustKey => $mustParam)
	  //   	{
	  //   		if($company[$mustParam] != '')
	  //   		{
	  //   			$subData[$mustParam] = $company[$mustParam];
	  //   		}else{
	  //   			$subData[$mustParam] = $generic[$mustParam];
	  //   		}
	  //   	}
	  //   	if ($company->restrict == 'true') {
			// 	$subData['restrict'] = 'true';
			// }

	  //   	foreach ($citySubsidiaries as $key => $value) {
	  //   		$this->companyRepo->editCompany($value, $subData);
	  //   	}

			if ($generic->restrict == 'true') {
				$data['restrict'] = 'true';
			}
		}else{
			$countryParent = $this->companyRepo->countrySubsidiary($country_id, $company->parent_id);
			$data['parent_company'] = $countryParent->id;
			if ($countryParent->restrict == 'true') {
				$data['restrict'] = 'true';
			}
		}
		

		// if($city)
		// {
		// 	$city_object = $this->cityRepo->getCityByName($city);
		// 	$city_id = $city_object->id;
		// 	$data['city_id'] = $city_id;
		// }
		// $logo = $request->image;
		if($company->sub_type == 'city_subsidiary')
		{
			$data['logo'] = '';
		}else{

			$logo_url = $request->logo_url;
			if($logo && $logo_url !== '')
			{
				$data['logo'] = $logo;
			}elseif($logo == '' || $logo == null){
				if($logo_url !== '' && $logo_url !== null)
				{
			        if(@GetImageSize($logo_url))
			        {
			        	$extension = pathinfo($logo_url, PATHINFO_EXTENSION);
			        	$fileNameUrl = str_random(8).'.'.$extension;
				        $file = file_get_contents($logo_url);
				        $save = file_put_contents('uploads/'.$fileNameUrl, $file);
				        $data['logo'] = $fileNameUrl;
			        }else{
			        	return redirect()->back()->withInput()->with('invalidUrl', 'Invalid image url.');
			        }
				}elseif($logo_url == '' && $logo_url == null){
					$data['logo'] = '';
				}
			}elseif($logo != null && $logo != ''){
				$data['logo'] = $logo;
			}
		}
		
		
		if($company->type == 'generic')
		{
			$data['parent_id'] = 0;
		}
		
		$parentCompany = $this->companyRepo->getCompanyById($company->parent_id);
		$type =  $request->type;
		if($parentCompany)
		{
			if($company->sub_type == 'country_subsidiary')
			{
				if ($request->is_published) {
					 if ($request->is_published != '') {
						$data['restrict'] = 'true';
					}
				} else {
					if ($parent_company->restrict != 'true' || $parent_company->restrict == null) {
						$data['restrict'] = null;
					} else {
						return redirect()->back()->withInput()->with('error_danger', "Subsidary Company can't be published");
					}
				}
				$name = $request->name.' / '.$country_object->name;
				$data['name'] = $name;
				$company_updated = $this->companyRepo->editCompany($company, $data);
				// if($data['restrict'] != 'true')
				// {
				// 	return redirect()->action('Admin\CompanyController@getEditCompany', [$company->parent_id,'generic'])->with('message', 'Your changes have been successfully applied');
				// }else{
				// 	return redirect()->action('Admin\CompanyController@getEditCompany', [$company->parent_id,'generic']);
				// }
				return redirect()->back()->with('message', 'Your changes have been successfully applied');
			}elseif($company->sub_type == 'city_subsidiary'){
				$name = $request->name.' / '.$country_object->name.' / '.$cityName;
				$data['name'] = $name;
				$countryCompany = $this->companyRepo->countrySubsidiary($country_object->id, $parentCompany->id);
				$data['country_parent'] = $countryCompany->id;

				if ($countryCompany) {
					if ($request->is_published) {
						$data['restrict'] = 'true';
					} else {
						if ($parent_company->restrict == null && $countryCompany->restrict == null) {
							$data['restrict'] = null;
						} else {
							return redirect()->back()->withInput()->with('error_danger', 'The city subsidiary can not be published because the parent company is unpublished');
						}
					}
				}
				$company_updated = $this->companyRepo->editCompany($company, $data);
				// if($data['restrict'] != 'true')
				// {
				// 	return redirect()->action('Admin\CompanyController@getEditCompany', [$countryCompany->id,'sub'])->with('message', 'Your changes have been successfully applied'); 
				// }else{
					
				// 	return redirect()->action('Admin\CompanyController@getEditCompany', [$countryCompany->id,'sub']); 
				// }
				return redirect()->back()->with('message', 'Your changes have been successfully applied');
			}
			
		}else{
			$company_updated = $this->companyRepo->editCompany($company, $data);
			// return redirect()->action('Admin\CompanyController@getCompanies', ['date', 'asc']);
			return redirect()->back()->with('message', 'Your changes have been successfully applied');
		}
		
    }


  //   public function getSubsidiaries($company_id)
  //   {
  //   	$logged_user = Sentinel::getUser();
		// if($logged_user->hasAccess('company.update')) {
		// 	$company = $this->companyRepo->getCompanyById($company_id);
		// 	$data = ['company' => $company];
		// 	return view('admin.subsidiaries', $data);
		// }else{
		// 	return redirect()->back();
		// }
  //   }

  //   public function getAddSubsidiary($company_id)
  //   {
  //   	$logged_user = Sentinel::getUser();
		// if($logged_user->hasAccess('company.create')) {
		// 	$countries = $this->countryRepo->getAllCountries();
		// 	$categories = $this->categoryRepo->getAllActivatedCategories();
		// 	$sectors = $this->sectorRepo->getAllActivatedSectors();
		// 	$data = [
		// 		'countries' => $countries,
		// 		'categories' => $categories,
		// 		'sectors' => $sectors,
		// 		'company_id' => $company_id
		// 	];
		// 	return view('admin.add_subsidiary', $data);
		// }else{
		// 	return redirect()->back();
		// }
  //   }

    /**
     * add subsidiary
     *
     * @param Request $request
     * @return redirect
     */
    public function postAddSubsidiary(Request $request)
    {
    	$ajaxRequest = $request->ajaxRequest;
    	$parent_id = $request->parent_id;
    	$parent_company = $this->companyRepo->getCompanyById($parent_id);
    	
    	// $short_description = $parent_company->short_description;			
    	$category_id = $parent_company->category_id;
    	$country = $request->country;
    	if($country)
    	{
    		$country_object = $this->countryRepo->getCountryByName($country);
    		$name = $parent_company->name.' /'.$country_object->name;
			$country_id = $country_object->id;
			foreach($parent_company->subsidiaries as $subsidiary)
	    	{
	    		if($subsidiary->country->id == $country_id)
	    		{
	    			if($ajaxRequest && $ajaxRequest == true){
	    				return response()->json('country-exists');
	    			}else{
	    				return redirect()->back()->with('clone-error', 'The country already exists');
	    			}
	    			
	    		}
	    	}
    	}else{
    		$country_id = $parent_company->country_id;
    	}

    	//JOB APPLYING:GET FROM COUNTRY PARENT , IF NOT EXIST GET FROM GENERIC PARENT
    	$countryParent = $this->companyRepo->countrySubsidiary($country_object->id, $parent_company->id);

   //  	if($countryParent && $countryParent->job_applying != ''){
	  // 		$job_applying = $countryParent->job_applying;
			// $url_to_redirect = $countryParent->url_to_redirect;
			// $looking_for = $countryParent->looking_for;
			// $requirement = $countryParent->requirement;
			// $compensation = $countryParent->compensation;
			// $why_us = $countryParent->why_us;
			// $description = $countryParent->description;
			// $short_description = $countryParent->short_description;


   //  	}else{
   //  		$job_applying = $parent_company->job_applying;
			// $url_to_redirect = $parent_company->url_to_redirect;
			// $looking_for = $parent_company->looking_for;
			// $requirement = $parent_company->requirement;
			// $compensation = $parent_company->compensation;
			// $why_us = $parent_company->why_us;
			// $description = $parent_company->description;
			// $short_description = $parent_company->short_description;
   //  	}


		$sector_id = $parent_company->sector_id;
    	$data = [
			'type' => 'subsidiary',
			'sub_type' => 'country_subsidiary',
			'parent_id' => $parent_id,
			// 'short_description' => $short_description,
			'category_id' => $category_id,a
			// 'looking_for' => $looking_for,
			// 'requirement' => $requirement,
			// 'compensation' => $compensation,
			// 'why_us' => $why_us,
			// 'job_applying' => $job_applying,
			// 'url_to_redirect' => $url_to_redirect,
			'sector_id' => $sector_id,
			'country_id' => $country_id,
			'name' => $name,
			'restrict' => 'true'
		];
		$mustHave = ['job_applying', 'url_to_redirect', 'logo'];
    	foreach($mustHave as $mustKey => $mustParam)
    	{
    		if($countryParent && $countryParent[$mustParam] != '')
    		{
    			$data[$mustParam] = $countryParent[$mustParam];
    		}else{
    			$data[$mustParam] = $parent_company[$mustParam];
    		}
    	}
		
    	$subsidiary = $this->companyRepo->createCompany($data);
    	if($ajaxRequest && $ajaxRequest == true){
    		return response()->json('success');
    	}else{
    		return redirect()->action('Admin\CompanyController@getEditCompany',['company_id' => $subsidiary->id, 'company' => 'sub']);
    	}

    }

    /**
     * add city subsidiary
     *
     * @param SubsidiaryCityAddRequest $request
     * @return redirect 
     */
    public function postAddCitySubsidiary(Request $request)
    {
    	$ajaxRequest = $request->ajaxRequest;
    	$parent_id = $request->parent_id;
    	$parent_company = $this->companyRepo->getCompanyById($parent_id);
    	$short_description = $parent_company->short_description;			
    	$category_id = $parent_company->category_id;
    	$sector_id = $parent_company->sector_id;
    	// $looking_for = $parent_company->looking_for;
    	// $requirement = $parent_company->requirement;
    	// $compensation = $parent_company->compensation;
    	// $why_us = $parent_company->why_us;
    	$country = $request->country;
		$country_object = $this->countryRepo->getCountryByName($country);

		//JOB APPLYING:GET FROM COUNTRY PARENT , IF NOT EXIST GET FROM GENERIC PARENT
    	$countryParent = $this->companyRepo->countrySubsidiary($country_object->id, $parent_company->id);
    	
  //   	$job_applying = $parent_company->job_applying;
		// $url_to_redirect = $parent_company->url_to_redirect;
  //   	if($countryParent->job_applying != ''){
	 //  		$job_applying = $countryParent->job_applying;
		// 	$url_to_redirect = $countryParent->url_to_redirect;

  //   	}
	  	
		
		$city = $request->subsidiary_city; 
		$region = $request->city_region;
		$cityLongtitude = $request->city_longtitude;
		$cityLatitude = $request->city_latitude;
		$name = $parent_company->name.'/'.$country_object->name.'/'.$city;
		foreach($parent_company->subsidiaries as $subsidiary)
		{
			if(($subsidiary->country_id == $country_object->id && $subsidiary->city_name == $city && $subsidiary->region == $region) && $subsidiary->id != $countryParent->id)
			{
				if($ajaxRequest && $ajaxRequest == true){
					return response()->json('city-exists');
				}else{
					return redirect()->back()->with('city-error', 'The city already exists');
				}
			}
		}

    	$data = [
			'type' => 'subsidiary',
			'sub_type' => 'city_subsidiary',
			'name' => $name,
			'parent_id' => $parent_id,
			'description' => $parent_company->description,
			'category_id' => $category_id,
			'sector_id' => $sector_id,
			// 'looking_for' => $looking_for,
			// 'requirement' => $requirement,
			// 'compensation' => $compensation,
			// 'why_us' => $why_us,
			// 'job_applying' => $job_applying,
			// 'url_to_redirect' => $url_to_redirect,
			'country_id' => $country_object->id,
			'city_name' => $city,
			'region' => $region,
			'city_longtitude' => $cityLongtitude,
			'city_latitude' => $cityLatitude,
			'country_parent' => $countryParent->id,
			'restrict' => 'true'
		];

		// $mustHave = ['job_applying', 'url_to_redirect', 'looking_for', 'requirement', 'compensation', 'why_us', 'description', 'short_description'];
  //   	foreach($mustHave as $mustKey => $mustParam)
  //   	{
  //   		if($countryParent && $countryParent[$mustParam] != ''){
  //   			$data[$mustParam] = $countryParent[$mustParam];
  //   		}else{
  //   			$data[$mustParam] = $parent_company[$mustParam];
  //   		}
  //   	}

		$subsidiary = $this->companyRepo->createCompany($data);
		
		if($ajaxRequest && $ajaxRequest == true){
			return response()->json('success');
		}else{
			return redirect()->action('Admin\CompanyController@getEditCompany',['company_id' => $subsidiary->id, 'company' => 'sub_city']);

		}
    }

    /**
     * get show company page
     *
     * @param int $company_id
	 * @return view
     */
    public function getShowCompany($company_id)
    {
    	$company = $this->companyRepo->getCompanyById($company_id);
    	$data = ['company' => $company];
    	return view('admin.show_company', $data);
    }

    /**
     * REMOVE COMPANY AND IT'S SUBSIDIARIES
     * 
     * @param int $company_id
     * @param string $type
     * @return redirect
     */
    public function getDeleteCompany($company_id, $type)
    {
    	$company = $this->companyRepo->getCompanyById($company_id);
    	//Delete company jobs
		$this->companyRepo->deleteAllJobs($company_id);
    	if($type == 'generic')
    	{
    		if($company->subsidiaries)
    		{
    			foreach($company->subsidiaries as $subsidiary)
    			{
    				$this->companyRepo->deleteCompany($subsidiary);
    			}
    		}
    	}elseif($type == 'sub')
    	{
    		$generic = $company->generic;
    		if(count($generic->subsidiaries) > 1)
    		{
    			foreach($generic->subsidiaries as $subsidiary)
    			{
    				if($subsidiary->country_id == $company->country_id && $subsidiary->id !== $company->id)
    				{
    					$this->companyRepo->deleteCompany($subsidiary);
    				}
    			}
    		}
    	}
		
		
		//Delete company    	 
    	$this->companyRepo->deleteCompany($company);
    	return redirect()->back();
    }

    /**
     * clone subsidiary from existing company
     *
     * @param Request $request
     * @return redirect
     */
    public function postCloneSubsidiary(Request $request)
    {
    	$parent_id = $request->parent_id;
    	$parent_company = $this->companyRepo->getCompanyById($parent_id);
    	$country_to = $request->country_to;
    	$company_from_id = $request->company_from;

    	if(!$country_to)
    	{
    		$errors['countryError'] = 'The country field is required.';
    	}
    	if(!$company_from_id)
    	{
    		$errors['companyError'] = 'The company field is reuqired.';
    	}
    	if(!$company_from_id || !$country_to)
    	{
    		return redirect()->back()->with('message', $errors);
    	}
		$country_object = $this->countryRepo->getCountryByName($country_to);
		$country_id = $country_object->id;
    	
    	
    	$company_from = $this->companyRepo->getCompanyById($company_from_id);
    	foreach($parent_company->subsidiaries as $subsidiary)
    	{
    		if($subsidiary->country->id == $country_id)
    		{
    			return redirect()->back()->with('clone-error', 'The country already exists');
    		}
    	}    	
    	$name = $company_from->name.' / '.$country_to;
    	$url = $company_from->url;
    	$description = $company_from->description;
    	$short_description = $company_from->short_description;
    	$facebook_url = $company_from->facebook_url;
    	$linkedin_url = $company_from->linkedin_url;
    	$twitter_url = $company_from->twitter_url;
    	$crunchbase_url = $company_from->crunchbase_url;
    	$ios_url = $company_from->ios_url;
    	$android_url = $company_from->android_url;			
    	$sector_id = $company_from->sector_id;
    	$category_id = $company_from->category_id;
    	$looking_for = $company_from->looking_for;
    	$requirement = $company_from->requirement;
    	$compensation = $company_from->compensation;
    	$why_us = $company_from->why_us;
    	$job_applying = $parent_company->job_applying;
		$url_to_redirect = $parent_company->url_to_redirect;
    	$logo = $company_from->logo;

    	$data = [
			'name' => $name,
			'type' => 'subsidiary',
			'sub_type' => 'country_subsidiary',
			'parent_id' => $parent_id,
			'url' => $url,
			'description' => $description,
			'short_description' => $short_description,
			'facebook_url' => $facebook_url,
			'linkedin_url' => $linkedin_url,
			'twitter_url' => $twitter_url,
			'crunchbase_url' => $crunchbase_url,
			'ios_url' => $ios_url,
			'android_url' => $android_url,
			'country_id' => $country_id,
			'sector_id' => $sector_id,
			'category_id' => $category_id,
			'looking_for' => $looking_for,
			'requirement' => $requirement,
			'compensation' => $compensation,
			'why_us' => $why_us,
			'job_applying' => $job_applying,
			'logo' => $logo,
			'url_to_redirect' => $url_to_redirect,
			'restrict' => true
		];
    	$subsidiary = $this->companyRepo->createCompany($data);
		return redirect()->action('Admin\CompanyController@getEditCompany',['company_id' => $subsidiary, 'company' => 'sub']);
    }

    public function postSearchCompany(SearchRequest $request)
    {

    	$name = $request->company_search;
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
		$results = $this->companyRepo->searchCompany($name, $country_id, $city, $category_id, $sector_id);
		$companies = $this->companyRepo->getAllGenericsPaginate('date', 'asc');
		$countries = $this->countryRepo->getAllCountries();
		$cities = $this->cityRepo->getAllCities();
		$categories = $this->categoryRepo->getTranslations();
		$sectors = $this->sectorRepo->getAllSectors();
		$data = [
			'search_results' => $results,
			'companies' => $companies,
			'countries' => $countries,
			'cities' => $cities,
			'categories' => $categories,
			'sectors' => $sectors
			];

		return view('admin.search_results_company', $data);
    }

    public function getRemoveImage($company_id)
    {
    	$company = $this->companyRepo->getCompanyById($company_id);
    	$data = ['logo' => ''];
    	$this->companyRepo->editCompany($company, $data);
    	return redirect()->back();
    }

    /**
     * get company cities by country
     */
    public function getCompanyCities($company, $countryId)
    {

    	$cities = $this->companyRepo->getCompaniesByCountry($company, $countryId);
    	return response()->json($cities);
    }

    /**
     * get countries by company name
     * 
     */
    public function getCompanyCountries($company)
    {
    	$company = $this->companyRepo->getCompanyById($company);
	    $countries = [];

    	if($company->type == 'generic')
    	{
    		$subsidiaries = $company->subsidiaries;
	    	foreach ($subsidiaries as $key => $value) {
	    		$country = $value->country;
	    		if(!in_array($country, $countries))
	    		{
	    			$countries[] = $country;
	    		}
	    		
	    	}
    	}else{
    		$countries[] = $company->country;
    	}
    	

    	return response()->json($countries);
    }

    /**
    * Proceed make company publish.
    * get /make-company-publish/{$id}
    *company
    * @param  integer $id
    *
    * @return redirect action
    */
    public function getMakeCompanyPublish($id)
	{
		$company = $this->companyRepo->getCompanyById($id);
		if ($company->name != '' && $company->url != '' && $company->description != '' && $company->short_description != '' && $company->job_applying != '' && $company->country_id != 0 && $company->sector_id != 0 && $company->category_id != 0 && $company->restrict == 'true') {
			$data['restrict'] = null;
			$this->companyRepo->editCompany($company, $data);
			return redirect()->back()->with('error', 'The company is successfully published');
		} else {
			return redirect()->back()->with('error_danger', 'Warning! You cant publish this company until there are empty required fields. Please edit the company to fill required information.');
		}
	}

	/**
    *  Proceed make company unpublish.
    * get /make-company-unpublish/{$id}
    *
    * @param  integer $id
    * 
    * @return redirect action
    */
	public function getMakeCompanyUnpublished($id) 
	{
		$company = $this->companyRepo->getCompanyById($id);
		if ($company->restrict != '' || $company->restrict == null) {
			$data['restrict'] = 'true';
			$this->companyRepo->editCompany($company, $data);
			$subsidiaries = $company->subsidiaries;
			foreach ($subsidiaries as $key => $value) {
				$this->companyRepo->editCompany($value, $data);
			}
			return redirect()->back()->with('error', 'The company is successfully unpublished');
		} else {
			return redirect()->back()->with('error_danger', 'The company is already Unpublished');
		}
	}

	/**
    * Proceed make subsidary publish.
    * get /make-subsidary-publish/{$id}
    *
    * @param  integer $id
    *
    * @return redirect action
    */
    public function getMakeSubsidaryPublish($id)
	{
		
		$company = $this->companyRepo->getCompanyById($id);
		$parent_company = $company->generic;
		if($company->sub_type == 'city_subsidiary'){
			$countryCompany = $this->companyRepo->countrySubsidiary($company->country_id, $company->parent_id);
			if ($parent_company->restrict != 'true' && $countryCompany->restrict != 'true') {

				$data['restrict'] = null;
				$this->companyRepo->editCompany($company, $data);
				return redirect()->back()->with('error', 'The subsidary company is successfully published');
			} else {
				return redirect()->back()->with('error_danger', "You can't publish subsidiary company until its Generic Company is not publish yet");
			}
		}else{
			if ($parent_company->restrict != 'true' ) {
				$data['restrict'] = null;
				$this->companyRepo->editCompany($company, $data);
				return redirect()->back()->with('error', 'The subsidary company is successfully published');
			}else {
				return redirect()->back()->with('error_danger', "You can't publish subsidiary company until its Generic Company is not publish yet");
			}
		}
		
	}

	/**
    *  Proceed make subsidary unpublish.
    * get /make-subsidary-unpublish/{$id}
    *
    * @param  integer $id
    * 
    * @return redirect action
    */
	public function getMakeSubsidaryUnpublish($id) 
	{
		$company = $this->companyRepo->getCompanyById($id);
		if($company->sub_type == 'city_subsidiary'){
			$countryCompany = $this->companyRepo->countrySubsidiary($company->country_id, $company->parent_id);
			if ($company->restrict == null) {
				$data['restrict'] = 'true';
				$this->companyRepo->editCompany($company, $data);
				return redirect()->back()->with('error', 'The company is successfully unpublished');
			} else {
				return redirect()->back()->with('error_danger', 'The company is already Unpublished');
			}
		}else{
			if ($company->restrict == null ) {
				$data['restrict'] = 'true';
				$this->companyRepo->editCompany($company, $data);
				return redirect()->back()->with('error', 'The company is successfully unpublished');
			} else {
				return redirect()->back()->with('error_danger', 'The company is already Unpublished');
			}
		}
		
	}

}
