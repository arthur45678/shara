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
use App\Http\Requests\CloneCompanyRequest;
use App\Http\Requests\SubsidiaryEditRequest;
use App\Http\Requests\SubsidiaryCreateRequest;
use App\Http\Requests\SubsidiaryCityAddRequest;
use App\Http\Requests\SubsidiaryCloneRequest;
use App\Http\Requests\SearchRequest;
use App\Http\Requests\CompanyCityRequest;
use Sentinel;
use Illuminate\Validation\Rule;
use Validator;
use DB;
use Carbon\Carbon;
use App\CompanyCity;
use Illuminate\Support\Facades\Cache;

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
        $this->middleware("genericAdmin", ['except' => [
                                        'getCompanies', 
                                        'getCreateCompany',
                                        'postCreateCompany',
                                        'getEditCompany',
                                        'postEditCompany',
                                        'postCloneSubsidiary',
                                        'getShowCompany',
                                        'postFileUpload',
                                        'getCompanyCountries',
                                        'getCompanyCities'
                                    ]]);
	}

    /**
     * get company management page,
     * that show the all companies ordered ascending by creation date 
     * and allows to create a new job, edit companies, view job details, search companies
     * order by company name, industry, category descending and ascending 
     * GET /admin/companies
     * 
     * @param search details Request $request
     * @param sorting parameter string $sort
     * @param sorting type string $type
     * @return view
     */
    public function getCompanies($sort, $type, Request $request)
    {
        // get the auth user
        $loggedUser = Sentinel::getUser();
        // check if the user has permission to view companies
        if($loggedUser->hasAccess('company.view') || $loggedUser->admin_type != 'generic') {
            // if yes
            // get search details
            $searchDetails = $request->except('page');
            // if there are search parameters
            if(count($searchDetails) > 0){
                $searchDetails['id'] = $loggedUser->id;
                // get generic companies with search parameters
                if($loggedUser->admin_type == 'generic'){
                    $searchDetails['adminType'] = 'generic';
                    $companies = $this->companyRepo->searchCompany($searchDetails, $sort, $type);
                }else{
                    $searchDetails['adminType'] = 'company_admin';
                    $companies = $this->companyRepo->searchCompany($searchDetails, $sort, $type);
                }

            }else{
                // if auth user is generic admin get all generic companies
                if($loggedUser->admin_type == 'generic')
                    $companies = $this->companyRepo->getAllGenericsPaginate($sort, $type);
                else
                    $companies = $this->companyRepo->adminCompaniesPaginated($sort, $type, $loggedUser->id);
            }

            if($request->page){
                $page = $request->page;
            }else{
                $page = 1;
            }
            // reverse the sorting type
            if($type == 'asc')
                $newType = 'desc';
            else
                $newType = 'asc';

            // get all countries, categories, sectors for search ordered alphabetically
            $countries = $this->countryRepo->getAllCountries();
            $categories = $this->categoryRepo->getOrderedCategories();
            $sectors = $this->sectorRepo->getOrderedSectors();

            $data = [
                    'companies' => $companies,
                    'countries' => $countries,
                    'categories' => $categories,
                    'sectors' => $sectors, 
                    'type' => $newType,
                    'searchDetails' => $searchDetails,
                    'page' => $page
                    ];
            // return company management page
            return view('admin.companies', $data);
        }else{
            // if no
            // redirect back
            return redirect()->back();
        }
    }

    /**
     * get create company page
     * GET /admin/create-company
     *
     * @return view
     */
    public function getCreateCompany()
    {
        // get the auth user
        $loggedUser = Sentinel::getUser();
        // check if the use has access to create a company
        if($loggedUser->hasAccess('company.create') || $loggedUser->admin_type != 'generic') {
            // if yes
            // get all countries, categories, sectors ordered alphabetically
            $countries = $this->countryRepo->getAllCountries();
            // get active sectors and categories
            $categories = $this->categoryRepo->getActiveOrderedCategories();
            $sectors = $this->sectorRepo->getActiveOrderedSectors();
            $data = [
                'countries' => $countries,
                'categories' => $categories,
                'sectors' => $sectors,
            ];
            // return company create page
            return view('admin.create_company', $data);
        }else{
            // if no
            // redirect back
            return redirect()->back();
        }
    }

    /**
     * create a company
     * POST /admin/create-company
     * 
     * @param CompanyCreateRequest $request
     * @return redirect
     */
    public function postCreateCompany(CompanyCreateRequest $request)
    {
        // get auth user
        $user = Sentinel::getUser();
        //get request data to create a company
        $type = $request->type;
        $name = $request->name;
        $url = $request->url;
        $description = $request->description;
        $shortDescription = $request->short_description;
        $facebookUrl = $request->facebook_url;
        $linkedinUrl = $request->linkedin_url;
        $twitterUrl = $request->twitter_url;
        $crunchbaseUrl = $request->crunchbase_url;
        $iosUrl = $request->ios_url;
        $androidUrl = $request->android_url;
        $city = $request->city_name;
        $cityLongtitude = $request->city_longtitude;
        $cityLatitude = $request->city_latitude;
        $region = $request->region;
        $countryName = $request->country;
        $country = $this->countryRepo->getCountryByName($countryName);
        $countryId = isset($country) ? $country->id : null;
        $sectorId = isset($request->industry) &&  $request->industry != ""? $request->industry : null;
        $categoryId = isset($request->category) &&  $request->category != ""? $request->category : null;
        $lookingFor = $request->looking_for;
        $requirement = $request->requirement;
        $compensation = $request->compensation;
        $whyUs = $request->why_us;
        $jobApplying = $request->job_applying;
        $redirectUrl = ($jobApplying == 'redirect') ? $request->url_to_redirect : '';
        $countryParentId = null;

        //clear caches for this country
        if(!$request->is_published) {
            Cache::tags($country->abbreviation)->flush();
        }
        

        //upload logo
        $companyLogo = "";
        $logo = $request->logo_name;
        $logoUrl = $request->logo_url;
        // if there is logo uploaded, take uploaded logo: not matter if ther is a logo url
        if($logo != null && $logo != ''){
            $companyLogo = $logo;            
            $destinationPath = public_path().'/uploads';
            $extension = $companyLogo->getClientOriginalExtension();
            $fileName = str_random(8).'.'.$extension;
            $companyLogo->move($destinationPath, $fileName);
            $companyLogo = $fileName;
        }elseif($logo == null || $logo == ''){
            // if no logo uploaded, check if there is a logo url
            if($logoUrl !== '' && $logoUrl !== null)
            {
                // if logo url, check logo url is a valid image url
                if(@GetImageSize($logoUrl))
                {
                    // if yes,get and upload image from url
                    $extension = pathinfo($logoUrl, PATHINFO_EXTENSION);
                    $fileNameUrl = str_random(8).'.'.$extension;
                    $file = file_get_contents($logoUrl);
                    $save = file_put_contents('uploads/'.$fileNameUrl, $file);
                    $companyLogo = $fileNameUrl;
                }else{
                    //if url is not valid, redirect back with warning message
                    return redirect()->back()->withInputs()->with('invalidUrl', 'Invalid image url.');
                }
            }
        }

        // if type is generic , set restirct status and subtype
        if($type == 'generic'){
            $genericId = null;
            $subType = 'generic';
            $restrict = isset($request->is_published) && $request->is_published != '' ? 'true' : '';
        }else{
            // if type is subsidiary
            // set parent id
            $genericId = $request->parent_id;
            $genericCompany = $this->companyRepo->getCompanyById($genericId);
            // restriction is true
            $restrict = 'true';
            // get sector and category from generic company
            $categoryId = $genericCompany->category_id;
            $sectorId = $genericCompany->sector_id;
            $name = $genericCompany->name;
            // if no logo for subsidiary, get from generic company
            if(!$logo && !$logoUrl){
                $companyLogo = $genericCompany->logo;
            }
            // get job applying details from generic company
            $jobApplying = $genericCompany->job_applying;
            $redirectUrl = $genericCompany->url_to_redirect;
            // get subtype
            $subType = $request->sub_type;
            if($subType == 'country_subsidiary'){
                // if subtype is country subsidiary
                // check if there is no subsidiary with request country
                foreach($genericCompany->subsidiaries as $subsidiary){
                    if($subsidiary->country && $subsidiary->country->id == $countryId){
                        // if country subsidiary already exists
                        if($request->ajax()){
                            // if it is ajax request, send json warning message
                            return response()->json('country-exists');
                        }else{
                            // else, redirect back, with warning message
                            return redirect()->back()->with('clone-error', 'The country already exists');
                        }

                    }
                }
            }elseif($subType == 'city_subsidiary'){
                // if subtype is city subsidiary
                $city = $request->subsidiary_city;
                $countryParentId = $request->country_parent;
                $countryParent = $this->companyRepo->getCompanyById($countryParentId);
                // get this fields from parent company
                // if parent company fields are empty, get from generic company
                $description = ($countryParent->description != "" && $countryParent->description != null) ? $countryParent->description : $genericCompany->description;
                $shortDescription = ($countryParent->short_description != "" && $countryParent->short_description != null) ? $countryParent->short_description : $genericCompany->short_description;
                $url = ($countryParent->url != "" && $countryParent->url != null) ? $countryParent->description : $genericCompany->url;
                $jobApplying = ($countryParent->job_applying != "" && $countryParent->job_applying != null) ? $countryParent->job_applying : $genericCompany->job_applying;
                $redirectUrl = ($countryParent->url_to_redirect != "" && $countryParent->url_to_redirect != null) ? $countryParent->url_to_redirect : $genericCompany->url_to_redirect;
                $companyLogo = ($countryParent->logo != "" && $countryParent->logo != null) ? $countryParent->logo : $companyLogo;
                if($countryParent->citySubsidiaries){
                    foreach ($countryParent->citySubsidiaries as $citySubsidiary) {
                        if($citySubsidiary->city_name == $city){
                            if($request->ajax()){
                                return response()->json('city-exists');
                            }else{
                                return redirect()->back()->with('city-error', 'The city already exists');
                            }
                        }
                    }
                    if($countryParent->job_applying){
                        $jobApplying = $countryParent->job_applying;
                        $redirectUrl = $countryParent->url_to_redirect;
                    }

                }
            }
        }

        // if user is not generic admin, he can not publish the company
        if($user->admin_type != 'generic')
            $restrict == 'true';

        $data = [
                'type' => $type,
                'name' => $name,
                'url' => $url,
                'logo' => $companyLogo,
                'description' => $description,
                'short_description' => $shortDescription,
                'facebook_url' => $facebookUrl,
                'linkedin_url' => $linkedinUrl,
                'twitter_url' => $twitterUrl,
                'crunchbase_url' => $crunchbaseUrl,
                'ios_url' => $iosUrl,
                'android_url' => $androidUrl,
                'country_id' => $countryId,
                'city_name' => $city,
                'region' => $region,
                'city_longtitude' => $cityLongtitude,
                'city_latitude' => $cityLatitude,
                'sector_id' => $sectorId,
                'category_id' => $categoryId,
                'looking_for' => $lookingFor,
                'requirement' => $requirement,
                'compensation' => $compensation,
                'why_us' => $whyUs,
                'job_applying' => $jobApplying,
                'url_to_redirect' => $redirectUrl,
                'restrict' => $restrict,
                'parent_id' => $genericId,
                'country_parent' => $countryParentId,
                'sub_type' => $subType
                ];
        // create company
        $company = $this->companyRepo->createCompany($data);



        if($user->admin_type == 'company_admin'){
            $company->admins()->attach($user);
        }
        // redirect to the company management page
        if($type == 'generic'){
            return redirect()->action('Admin\CompanyController@getCompanies', ['date', 'asc']);
        }else{
            if($subType == 'country_subsidiary'){
                if($request->ajax()){
                    return response()->json('success');
                }else{
                    return redirect()->action('Admin\CompanyController@getEditCompany',['company_id' => $company->id, 'company' => 'sub']);
                }
            }else{
                if($request->ajax()){
                    return response()->json('success');
                }else{
                    return redirect()->action('Admin\CompanyController@getEditCompany',['company_id' => $company->id, 'company' => 'sub_city']);

                }
            }
        }
    }

    /**
     * get edit company page
     * GET /admin/edit-company/{companyId}/{companyType}
     * 
     * @param int $companyId
     * @param int $companyType 
     * @return view
     */
    public function postCreateCity(CompanyCityRequest $request)
    {
        // dd($request->all());
        $data = [
                'city' => $request->subsidiary_city,
                'region' => $request->city_region,
                'latitude' => $request->city_latitude,
                'longitude' => $request->city_longtitude,
                'company_id' => $request->country_parent,
                'country_id' => $request->country
                ];
        $country = $this->countryRepo->getCountryById($request->country);        
        //clear caches for this country
        Cache::tags($country->abbreviation)->flush();
               
        $companyCity = $this->companyRepo->createCity($data);
        // if job has company and that company has cities, attach job to these cities
        $company = $companyCity->company;
        if($company->jobs) {
            foreach ($company->jobs as $job) {
                $this->jobRepo->setJobUnsent($job->id);
            }
        }

        if($companyCity == 'exists'){
            if($request->ajax()){
                return response()->json('city-exists');
            }else{
                return redirect()->back()->with('city-error', 'The city already exists');
            }
        }
        if($request->ajax()){
            return response()->json('success');
        }else{
            return redirect()->back();
        }
    }

    /**
     * get edit company page
     * GET /admin/edit-company/{companyId}/{companyType}
     * 
     * @param int $companyId
     * @param int $companyType 
     * @return view
     */
    public function getEditCompany($companyId, $companyType)
    {
        // get auth user
        $loggedUser = Sentinel::getUser();
        // check if the user has permission to edit the company
        if($loggedUser->hasAccess('company.update') || $loggedUser->admin_type != 'generic') {
            // if yes
            // get the company
            $company = $this->companyRepo->getCompanyById($companyId);
            // get all countries ordered alphabetically
            $countries = $this->countryRepo->getAllCountries();
            // get active sectors and categories ordered alphabetically
            $categories = $this->categoryRepo->getActiveOrderedCategories();
            $sectors = $this->sectorRepo->getActiveOrderedSectors();
            // get all jobs
            $jobs = $this->jobRepo->getAllJobs();

            $data = [
                    'company' => $company,
                    'countries' => $countries,
                    'sectors' => $sectors,
                    'categories' => $categories,
                    'jobs' => $jobs,
                    'companyCities' => $company->cities
                    ];

            // check company type
            if($companyType == 'generic'){
                // if type is generic

                // return generic company edit page
                return view('admin.edit_company', $data);
            }elseif($companyType == 'sub'){
                // if type is country subsidiary, slice company name
                $genericCompany = $company->generic;
                $name = $company->name;
                if(!$company->logo && $genericCompany->logo){
                    $company->logo = $genericCompany->logo;
                }
                $data['name'] = $name; 
                // return country subsidiary edit page
                return view('admin.edit_subsidiary', $data);

            }elseif($companyType == 'sub_city'){
                // get country parent
                $countryCompany = $this->companyRepo->countrySubsidiary($company->country->id, $company->generic->id);
                $data = [
                        'company' => $company,
                        'jobs' => $jobs,
                        'countryCompany' => $countryCompany
                        ];

                // return city subsidiary edit page
                return view('admin.edit_subsidiary_city', $data);
            }else{
                // else redirect back
                return redirect()->back();
            }

        }else{
            // if no permission
            return redirect()->back();
        }
    }

    /**
     * edit the company
     * POST /admin/edit-company
     * 
     * @param
     * @return redirect
     */
    public function postEditCompany(CompanyEditRequest $request)
    {
        // get auth user
        $user = Sentinel::getUser();

        // get the company
        $companyId = $request->company_id;
        $company = $this->companyRepo->getCompanyById($companyId);

        //get request data to edit a company
        $type = $request->type;
        $name = $request->name;
        $url = $request->url;
        $description = $request->description;
        $shortDescription = $request->short_description;
        $facebookUrl = $request->facebook_url;
        $linkedinUrl = $request->linkedin_url;
        $twitterUrl = $request->twitter_url;
        $crunchbaseUrl = $request->crunchbase_url;
        $iosUrl = $request->ios_url;
        $androidUrl = $request->android_url;
        $city = $request->city_name;
        $cityLongtitude = $request->city_longtitude;
        $cityLatitude = $request->city_latitude;
        $region = $request->region;
        $countryName = $request->country;
        $country = $this->countryRepo->getCountryByName($countryName);
        $countryId = isset($country) ? $country->id : null;
        $sectorId = isset($request->industry) &&  $request->industry != ""? $request->industry : null;
        $categoryId = isset($request->category) &&  $request->category != ""? $request->category : null;
        $lookingFor = $request->looking_for;
        $requirement = $request->requirement;
        $compensation = $request->compensation;
        $whyUs = $request->why_us;
        $jobApplying = $request->job_applying;
        $redirectUrl = $jobApplying == 'redirect' ? $request->url_to_redirect : '';
        $countryParentId = null;
        $restrict = isset($request->is_published) && $request->is_published != '' ? 'true' : '';
        $metaDescription = $request->meta_description;
        if(!$metaDescription || $metaDescription == '') {
            if($request->short_description) {
                $metaDescription = $request->short_description;
            }
        } 
        $metaKeywords = $request->meta_keywords;

        //clear caches for this country
        Cache::tags($country->abbreviation)->flush();

        //upload logo
        $companyLogo = "";
        $logo = $request->logo_name;
        $logoUrl = $request->logo_url;
        $oldLogo = $request->oldLogo;
        // if there is logo uploaded, take uploaded logo: not matter if ther is a logo url
        if($logo && $logoUrl !== ''){
            $companyLogo = $logo;
            $destinationPath = public_path().'/uploads';
            $extension = $companyLogo->getClientOriginalExtension();
            $fileName = str_random(8).'.'.$extension;
            $companyLogo->move($destinationPath, $fileName);
            $companyLogo = $fileName;
        }elseif($logo == '' || $logo == null){
            if($logoUrl !== '' && $logoUrl !== null)
            {

                if(@GetImageSize($logoUrl))
                {
                    $extension = pathinfo($logoUrl, PATHINFO_EXTENSION);
                    $fileNameUrl = str_random(8).'.'.$extension;
                    $file = file_get_contents($logoUrl);
                    $save = file_put_contents('uploads/'.$fileNameUrl, $file);
                    $companyLogo = $fileNameUrl;
                }else{
                    return redirect()->back()->withInput()->with('invalidUrl', 'Invalid image url.');
                }
            }elseif($logoUrl == '' || $logoUrl == null){

                $companyLogo = $oldLogo;
            }
        }elseif($logo != null && $logo != ''){
            $companyLogo = $logo;
        } 

        // if type is generic , set restirct status and subtype
        if($type == 'generic'){
            $genericId = null;
            $subType = 'generic';
            // edit subsidiaries industry and category
            $subsidiaries = $company->subsidiaries;
            if($subsidiaries){
                $subData['sector_id'] = $sectorId;
                $subData['category_id'] = $categoryId;
                if($restrict == 'true'){
                    $subData['restrict'] = 'true';
                }
                foreach($subsidiaries as $sub){
                    $subsidiaryUpdate = $this->companyRepo->editCompany($sub, $subData);
                }
            }
            
        }else{
            // if type is subsidiary
            // set parent id
            $genericId = $request->parent_id;
            $genericCompany = $this->companyRepo->getCompanyById($genericId);

            // get sector and category from generic company
            $categoryId = $genericCompany->category_id;
            $sectorId = $genericCompany->sector_id;
            if(!$logo && !$logoUrl){
                $companyLogo = $genericCompany->logo;
            }
            // get subtype
            $subType = $request->sub_type;
            if($subType == 'country_subsidiary'){
                // if subtype is country subsidiary
                // if request restrict status is false, check the generic restirct status
                if($restrict != 'true' && $genericCompany->restrict == 'true'){
                    return redirect()->back()->withInput()->with('error_danger', "Subsidary Company can't be published, because the generic company is not published.");
                }
                // check if there is no subsidiary with request country
                foreach($genericCompany->subsidiaries as $subsidiary){
                    if($subsidiary->country_id == $countryId && $subsidiary->id != $request->company_id && $subsidiary->sub_type == 'country_subsidiary'){
                        // if country subsidiary already exists
                        // redirect back with warning message
                        return redirect()->back()->with('error', 'The country subsidiary already exists');
                    }
                }
            }elseif($subType == 'city_subsidiary'){
                // if subtype is city subsidiary
                // get country parent company
                $countryParentId = $request->country_parent;
                $countryParent = $this->companyRepo->getCompanyById($countryParentId);
                // if request restrict status is false, check the country parent restirct status
                if($restrict != 'true' && $countryParent->restrict == 'true'){
                    return redirect()->back()->withInput()->with('error_danger', "City subsidary company can't be published, because the parent company is not published.");
                }
                $description = ($countryParent->description != "" && $countryParent->description != null) ? $countryParent->description : $genericCompany->description;
                $shortDescription = ($countryParent->short_description != "" && $countryParent->short_description != null) ? $countryParent->short_description : $genericCompany->short_description;
                $url = ($countryParent->url != "" && $countryParent->url != null) ? $countryParent->url : $genericCompany->url;
                $companyLogo = ($countryParent->logo != "" && $countryParent->logo != null) ? $countryParent->logo : $companyLogo;
                $name = ($countryParent->name != "" && $countryParent->name != null) ? $countryParent->name : $genericCompany->name;
            }
        }

        // if user is not generic admin, he can not publish the company
        if($user->admin_type != 'generic')
            $restrict == 'true';

        if($companyLogo == null){
            $companyLogo = '';
        }
        $data = [
                'type' => $type,
                'name' => $name,
                'url' => $url,
                'logo' => $companyLogo,
                'description' => $description,
                'short_description' => $shortDescription,
                'facebook_url' => $facebookUrl,
                'linkedin_url' => $linkedinUrl,
                'twitter_url' => $twitterUrl,
                'crunchbase_url' => $crunchbaseUrl,
                'ios_url' => $iosUrl,
                'android_url' => $androidUrl,
                'country_id' => $countryId,
                'city_name' => $city,
                'region' => $region,
                'city_longtitude' => $cityLongtitude,
                'city_latitude' => $cityLatitude,
                'sector_id' => $sectorId,
                'category_id' => $categoryId,
                'looking_for' => $lookingFor,
                'requirement' => $requirement,
                'compensation' => $compensation,
                'why_us' => $whyUs,
                'job_applying' => $jobApplying,
                'url_to_redirect' => $redirectUrl,
                'restrict' => $restrict,
                'parent_id' => $genericId,
                'country_parent' => $countryParentId,
                'sub_type' => $subType,
                'meta_description' => $metaDescription,
                'meta_keywords' => $metaKeywords
                ];

        // edit the company
        $newCompany = $this->companyRepo->editCompany($company, $data);

        // redirect back
        return redirect()->back()->with('message', 'Your changes have been successfully applied');
    }

    /**
     * clone company for slected country from another company
     * POST /admin/clone-subsidiary
     * 
     * @param CloneCompanyRequest $request
     * @return redirect back
     */
    public function postCloneSubsidiary(CloneCompanyRequest $request)
    {
        // get parent company
        $parentId = $request->parent_id;
        $parentCompany = $this->companyRepo->getCompanyById($parentId);
        // get company from which should be cloned
        $companyFromId = $request->company_from;
        $companyFrom = $this->companyRepo->getCompanyById($companyFromId);
        // get country for which should be cloned
        $countryTo = $request->country_to;
        $country = $this->countryRepo->getCountryByName($countryTo);
        $countryId = $country->id;
        
        //clear caches for this country
        Cache::tags($country->abbreviation)->flush();

        foreach($parentCompany->subsidiaries as $subsidiary){
            if($subsidiary->country->id == $countryId){
                return redirect()->back()->with('clone-error', 'The country already exists');
            }
        }

        // get company name
        $companyFromName = $companyFrom->name;
        $name = $companyFromName;
        // get some details from companyfrom
        $url = $companyFrom->url;
        $description = $companyFrom->description;
        $shortDescription = $companyFrom->short_description;
        $facebookUrl = $companyFrom->facebook_url;
        $linkedinUrl = $companyFrom->linkedin_url;
        $twitterUrl = $companyFrom->twitter_url;
        $crunchbaseUrl = $companyFrom->crunchbase_url;
        $iosUrl = $companyFrom->ios_url;
        $androidUrl = $companyFrom->android_url;          
        $sectorId = $companyFrom->sector_id;
        $categoryId = $companyFrom->category_id;
        $lookingFor = $companyFrom->looking_for;
        $requirement = $companyFrom->requirement;
        $compensation = $companyFrom->compensation;
        $whyUs = $companyFrom->why_us;
        // get some details from parent company
        $jobApplying = $parentCompany->job_applying;
        $redirectUrl = $parentCompany->url_to_redirect;
        $logo = $parentCompany->logo;

        $data = [
            'name' => $name,
            'type' => 'subsidiary',
            'sub_type' => 'country_subsidiary',
            'parent_id' => $parentId,
            'url' => $url,
            'description' => $description,
            'short_description' => $shortDescription,
            'facebook_url' => $facebookUrl,
            'linkedin_url' => $linkedinUrl,
            'twitter_url' => $twitterUrl,
            'crunchbase_url' => $crunchbaseUrl,
            'ios_url' => $iosUrl,
            'android_url' => $androidUrl,
            'country_id' => $countryId,
            'sector_id' => $sectorId,
            'category_id' => $categoryId,
            'looking_for' => $lookingFor,
            'requirement' => $requirement,
            'compensation' => $compensation,
            'why_us' => $whyUs,
            'job_applying' => $jobApplying,
            'logo' => $logo,
            'url_to_redirect' => $redirectUrl,
            'restrict' => 'true'
        ];
        // create company
        $subsidiary = $this->companyRepo->createCompany($data);
        // redirect back
        return redirect()->action('Admin\CompanyController@getEditCompany',['company_id' => $subsidiary, 'company' => 'sub']);
    }

    /**
     * show company details
     * GET /admin/show-company/{companyId}
     * 
     * @param int $companyId
     * @return company details page
     */
    public function getShowCompany($companyId)
    {
        $company = $this->companyRepo->getCompanyById($companyId);
        $data = [
                'company' => $company
                ];
        return view('admin.show_company', $data);
    }

    /**
     * remove company and it's subsidiaries
     * GET /admin/delete-company/{companyId}/{type}
     * 
     * @param int $companyId
     * @param string $type
     * @return redirect
     */
    public function getDeleteCompany($companyId, $type)
    {
        // get auth user
        $loggedUser = Sentinel::getUser();
        // check if the user has permission to delete the company
        if($loggedUser->hasAccess('company.delete') || $loggedUser->admin_type != 'generic') {
            // get company
            $company = $this->companyRepo->getCompanyById($companyId);
            //Delete company jobs
            $this->companyRepo->deleteAllJobs($companyId);

            if($type == 'generic')
            {
                // if the company is generic, delete all subsidiaries
                if($company->subsidiaries)
                {
                    foreach($company->subsidiaries as $subsidiary)
                    {
                        $subsidiary->cities()->delete();
                        $this->companyRepo->deleteCompany($subsidiary);
                    }
                }
            }elseif($type == 'sub'){
                // if the company is country subsidiary, delete city subsidiaries
                $company->cities()->delete();
            }
            
            //clear caches for this country
            if($company->country) {
                Cache::tags($company->country->abbreviation)->flush();
            }
            

            //delete the company         
            $this->companyRepo->deleteCompany($company);
        }
        
        // redirect back
        return redirect()->back();
    }

    /**
     * image upload function
     * POST /admin/file-upload
     *
     * @param Request $request
     * @return response
     */
     public function deleteCityCompany($id)
     {
        $this->companyRepo->deleteCityCompany($id);
        return redirect()->back();
     }    

    /**
     * image upload function
     * POST /admin/file-upload
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
     * remove company logo
     * GET /admin/remove-image/{companyId}
     * 
     * @param companyId
     * @return redirect back
     */
    public function getRemoveImage($companyId)
    {
        $company = $this->companyRepo->getCompanyById($companyId);
        $data = ['logo' => ''];
        $this->companyRepo->editCompany($company, $data);
        return redirect()->back();
    }

    /**
     * get company cities by for the country
     * GET /admin/company-cities/{companyId}/{countryId}
     * 
     * @param int $companyId
     * @param int $countryId
     * @return response
     */
    public function getCompanyCities($company, $countryId)
    {
        // get cities
        $cities = $this->companyRepo->getCompaniesByCountry($company, $countryId);
        // return josn data
        return response()->json($cities);
    }

    /**
     * get company countries
     * GET /admin/company-countries/{companyId}
     * 
     * @param int $companId
     * @return response
     */
    public function getCompanyCountries($companyId)
    {
        // get the company
        $company = $this->companyRepo->getCompanyById($companyId);
        $countries = [];
        if($company->type == 'generic')
        {
            // if the company is generic, get all subsidiaries countries
            $subsidiaries = $company->subsidiaries;
            foreach ($subsidiaries as $key => $value) {
                $country = $value->country;
                if(!in_array($country, $countries))
                {
                    $countries[] = $country;
                }
                
            }
        }else{
            // if the company is subsidiary, get only the company country
            $countries[] = $company->country;
        }
        // return json data
        return response()->json($countries);
    }

    /**
    * proceed make company publish
    * GET /admin/make-company-publish/{$id}
    *
    * @param  int $id
    * @return redirect back
    */
    // public function getMakeCompanyPublish($id)
    // {
    //     // get company
    //     $company = $this->companyRepo->getCompanyById($id);
    //     // check if all required fields are filled
    //     if ($company->name != '' && $company->url != '' && $company->description != '' && $company->short_description != '' && $company->job_applying != '' && $company->country_id != 0 && $company->sector_id != 0 && $company->category_id != 0) {
    //         // if yes
    //         // check the company type
    //         if($company->type == 'subsidiary'){
    //             // if company is subsidiary
    //             // get generic company
    //             $parentCompany = $company->generic;
    //             if($company->sub_type == 'city_subsidiary'){
    //                 // if company is city subsidiary
    //                 // get country parent
    //                 $countryCompany = $this->companyRepo->countrySubsidiary($company->country_id, $company->parent_id);
    //                 if ($parentCompany->restrict != 'true' && $countryCompany->restrict != 'true') {
    //                     // if generic company and country parent company are published
    //                     // publishd the company
    //                     $data['restrict'] = null;
    //                     $this->companyRepo->editCompany($company, $data);
    //                     // redirect back, with success message
    //                     return redirect()->back()->with('message', 'The subsidary company is successfully published');
    //                 }else{
    //                     // if the parent companies are not published, the company can't be published
    //                     // redirect back with warning message
    //                     return redirect()->back()->with('error_danger', "You can't publish subsidiary company until it's Generic Company is not published");
    //                 }
    //             }else{
    //                 // if the company is country subsidiary
    //                 // check if the generic company is published
    //                 if ($parentCompany->restrict != 'true' ) {
    //                     // if yes, publish the company
    //                     $data['restrict'] = null;
    //                     $this->companyRepo->editCompany($company, $data);
    //                     // redirect back with success message
    //                     return redirect()->back()->with('message', 'The subsidary company is successfully published');
    //                 }else {
    //                     // if no, redirect back
    //                     return redirect()->back()->with('error_danger', "You can't publish subsidiary company until its Generic Company is not publish yet");
    //                 }
    //             }
    //         }else{
    //             // if the company is generic
    //             // publish the company
    //             $data['restrict'] = null;
    //             $this->companyRepo->editCompany($company, $data);
    //             return redirect()->back()->with('message', 'The company is successfully published');
    //         }
            
    //     } else {
    //         // if the required fields are not filled, redirect back with warning message
    //         return redirect()->back()->with('error_danger', 'Warning! You cant publish this company until there are empty required fields. Please edit the company to fill required information.');
    //     }
    // }

    public function getMakeCompanyPublish($id)
    {
        $company = $this->companyRepo->getCompanyById($id);
        $requiredFields = ['name', 'url', 'description', 'short_description', 'job_applying', 'country_id', 'sector_id', 'catedory_id'];

        //clear caches for this country
        Cache::tags($company->country->abbreviation)->flush();

        if($company->type == 'generic'){
            foreach($requiredFields as $key => $value){
                if($company[$value] === "" || $company[$value] === 0){
                    return redirect()->back()->with('error_danger', 'Warning! You cant publish this company untill there are empty required fields. Please edit the company to fill required information.');
                }
            }
            $this->companyRepo->editCompany($company, ['restrict' => null]);
            return redirect()->back()->with('message', 'The company is successfully published');
        }else{
            $genericCompany = $company->generic;
            if($genericCompany->restrict == 'true'){
                return redirect()->back()->with('error_danger', "You can't publish subsidiary company untill its Generic Company is not publish yet");
            }else{
                $data = [
                    'name' => $company->name ? $company->name : $genericCompany->name,
                    'url' => $company->url ? $company->url : $genericCompany->url,
                    'description' => $company->description ? $company->description : $genericCompany->description,
                    'short_description' => $company->short_description ? $company->short_description : $genericCompany->short_description,
                    'job_applying' => $company->job_applying ?  $company->job_applying : $genericCompany->job_applying,
                    'country_id' => $company->country_id && $company->country_id != 0 ? $company->country_id : $genericCompany->country_id,
                    'sector_id' => $company->sector_id && $company->sector_id != 0 ? $company->sector_id : $genericCompany->sector_id,
                    'category_id' => $company->category_id && $company->category_id != 0 ? $company->category_id : $genericCompany->category_id,
                    'restrict' => null

                    ];

                $this->companyRepo->editCompany($company, $data);
                return redirect()->back()->with('message', 'The company is successfully published');
            }
            
        }
    }

    /**
    * proceed make company unpublish
    * get /admin/make-company-unpublish/{$id}
    *
    * @param  integer $id
    * 
    * @return redirect action
    */
    public function getMakeCompanyUnpublished($id) 
    {
        // get the company
        $company = $this->companyRepo->getCompanyById($id);

        //clear caches for this country
        Cache::tags($company->country->abbreviation)->flush();

        // if the company is published
        if ($company->restrict != '' || $company->restrict == null) {
            // unpublish the company
            $data['restrict'] = 'true';
            $this->companyRepo->editCompany($company, $data);
            // check company type
            if($company->type == 'generic'){
                // if the company is generic
                // unpublish all subsidiaries
                $subsidiaries = $company->subsidiaries;
                foreach ($subsidiaries as $key => $value) {
                    $this->companyRepo->editCompany($value, $data);
                    $subJobs = $value->jobs;
                    if($subJobs){
                        foreach ($subJobs as $subJob) {
                            $this->jobRepo->editJob($subJob, $data);
                        }
                    }
                }
            }else{
                // if the company is subsidiary, check company subtype
                if($company->sub_type == 'country_subsidiary'){
                    // if the company is country subsidiary
                    // ubpublish thic company's city sibsidiaries
                    $citySubsidiaries = $company->citySubsidiaries;
                    foreach ($citySubsidiaries as $key => $value) {
                        $this->companyRepo->editCompany($value, $data);
                    }
                }
            }

            $jobs = $company->jobs;
            if($jobs){
                foreach ($jobs as $job) {
                    $this->jobRepo->editJob($job, $data);
                }
            }
            // redirect back with success message
            return redirect()->back()->with('message', 'The company is successfully unpublished');
        } else {
            // if the company is unpublished
            // redirect back with warning message
            return redirect()->back()->with('error_danger', 'The company is already Unpublished');
        }
    }

    /**
     * change company names
     */
    public function getChangeName()
    {
        // $companies = DB::table('companies')->select('*')->whereNotNull('city_name')->where(function($query){
        //         $query->whereNull('city_latitude')->whereNull('city_longtitude')->orWhere('city_longtitude', "")->orWhere('city_latitude', "");
        // })->get();
        // $companies = $this->companyRepo->getAllCompanies(); 
        // foreach($companies as $company){
        //     if($company->sub_type == 'city_subsidiary'){
        //         $this->companyRepo->deleteCompany($company);
        //     }
        // //    //  if(strpos($company->name,'/') !== false){
        // //    //      $name = explode("/", $company->name);
        // //    //      if($name && count($name) > 0){
        // //    //          $newName = $name[0];
                    
        // //    //      }
        // //    // }elseif(strpos($company->name,'-') !== false){
        // //    //      $name = explode("-", $company->name);
        // //    //      if($name && count($name) > 0){
        // //    //          $newName = $name[0];
                    
        // //    //      }
        // //    // }
        // //    // $data = [
        // //    //          'name' => $newName,
        // //    //          ];
        // //    // if($company->city_name == "old('city_name')"){
        // //    //  $data['city_name'] = "";
        // //    // }

        // //     // if($company->city_name && (!$company->latitude || !$company->longitude)){

        // //     // }
        // //     $companies = Company::select(*)->whereNull('city_name');
        // //    $this->companyRepo->editCompany($company, $data);

        // }

        // CompanyCity::where('id', '>', 0)->delete();
        // $companies = DB::connection('mysql')->table('companies')->where('sub_type', 'city_subsidiary')
        //     ->get();
        // foreach ($companies as $company) {
        //     $parentId = $company->country_parent;
        //     if(!$parentId){
        //         $company1 = DB::connection('mysql')->table('companies')->where('sub_type', 'country_subsidiary')->where('parent_id', $company->parent_id)->where('country_id', $company->country_id)->first();
        //             $parentId = $company1->id;
        //     }
        //    $data[] = [
        //             'city' => $company->city_name,
        //             'latitude' => $company->city_latitude,
        //             'longitude' => $company->city_longtitude,
        //             'region' => $company->region,
        //             'company_id' =>  $parentId ,
        //             'country_id' => $company->country_id,
        //             'created_at' => Carbon::now()->toDateTimeString(),
        //             'updated_at' => Carbon::now()->toDateTimeString(),
        //             ];
        // }
        // //dd($data);
        // CompanyCity::insert($data);
        

        // dd('done');
        // $companies = CompanyCity::whereNull('latitude')->orWhere('latitude', '')->orWhereNull('longitude')->orWhere('longitude', '')->delete();
        // dd($companies);

        // $company = $this->companyRepo->getCompanyById('685');
        // $data = ['type' => 'subsidiary', 
        // 'parent_id' => '70',
        // 'sub_type' => 'country_subsidiary'];
        // $newCompany = $this->companyRepo->editCompany($company, $data);
        // $cities = DB::connection('mysql')->table('company_cities')->where('company_id', '1750')
            // ->get();
            // foreach($cities as $city){
            //     $company = $this->companyRepo->getCompanyById($city->company_id);
            //     $data['country_id'] = $company->country_id;
            //     $this->companyRepo->editCompanycity($city->id, $data);
            // }

            // $cities = DB::connection('mysql')->table('company_cities')->where('country_id', null)
            // ->get();
        // dd($cities);
        $not_16 = DB::connection('mysql')->table('notifications')->whereNotNull('user_email')->delete();
        $sub_16 = DB::connection('mysql')->table('subscribtions')->whereNotNull('email')->delete();
        $not_17 = DB::connection('mysql1')->table('notifications')->whereNotNull('user_email')->delete();
        $sub_17 = DB::connection('mysql1')->table('subscribtions')->whereNotNull('email')->delete();
        dd($not_16, $sub_16, $not_17, $sub_17);

    }

    public function getCompaniesWithNullCity()
    {
        $companies = $this->companyRepo->getAllCompanies();
        $empties = [];
        foreach ($companies as $company) {
            if($company->has('cities', '>', '0')) {
                foreach ($company->cities as $city) {
                    if(!$city->latitude || !$city->longitude || !$city->country_id) {
                        $empties[] = $city->city;
                        dd($city->city, $company->name);
                    }
                }
            }
        }

        dd($empties);
    }

    public function getDeleteSpacesFromCompaniesNames()
    {
        $companies = $this->companyRepo->getAllCompanies();
        foreach ($companies as $company) {
            $name = $company->name;
            $name = trim($name);
            $this->companyRepo->editCompany($company, ['name' => $name]);
        }
        dd('success');
    }

    public function getSetShortDescriptionMeta()
    {
        $companies = $this->companyRepo->getAllCompanies();
        foreach ($companies as $company) {
            if($company->short_description) {
                $this->companyRepo->editCompany($company, ['meta_description' => $company->short_description]);
            }
        }

        dd('success');
    }
}